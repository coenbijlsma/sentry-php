<?php
namespace Plugins\IRC;

use Config\Config as Config;
use Config\ConfigSection as ConfigSection;
use Sentry\Plugin as Plugin;
use Sentry\HookPoint as HookPoint;
use Plugins\IRC\Commands\Connect as Connect;
use Plugins\IRC\Commands\TestCommand as TestCommand;
use Plugins\IRC\Commands\Listener as Listener;
use Plugins\IRC\Commands\MessageDispatcher as MessageDispatcher;

class IRCPlugin extends Plugin {

    /**
     *
     * @var string
     */
    private $name;

    /**
     * Contains the available commands of this plugin, indexed by name
     * @var array
     */
    private $commands;

    /**
     * The configuration of this plugin, as read by \parse_ini_file()
     * @var Config
     */
    private $config;

    /**
     *
     * @var array
     */
    private $dependencies;

    /**
     *
     * @var Socket
     */
    private $socket;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->config = new Config( \dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'irc.conf' );
        $this->name = $this->config->getValue( 'general','plugin.name' );
        $this->commands = array();
        $this->dependencies = array();
        $this->socket =  new Socket( 
                \AF_INET,
                \SOCK_STREAM,
                \SOL_TCP,
                $this->config->getValue( 'general', 'host', 'localhost' ),
                $this->config->getValue( 'general', 'port', 6667 )
        );
        $this->initHookPoints();
        $this->initCommands();
        $this->attachPluginCommands( $this );
    }

    /**
     * Destructor
     */
    public function __destruct() {
        if ( $this->socket->isConnected() ) {
            $this->socket->close();
        }
    }

    /**
     * Initializes the available commands.
     */
    private function initCommands() {
        $connect = new Connect( $this, $this->config->getValue( 'commands', 'connect' ), $this->socket );
        $listener = new Listener( $this, $this->config->getValue( 'commands', 'listener', array() ), $this->socket );
        $messagedispatcher = new MessageDispatcher( $this, $this->config->getValue( 'commands', 'messagedispatcher', array() ), $this->socket );
        
        $this->commands[ $connect->getName() ] = $connect;
        $this->commands[ $listener->getName() ] = $listener;
        $this->commands[ $messagedispatcher->getName() ] = $messagedispatcher;
    }

    /**
     * Initialize the available hookpoints
     */
    private function initHookPoints() {
        $this->hookpoints[ 'irc.post_connect' ] = new HookPoint(
                'irc.post_connect',
                $this->config->getValue(
                    'hookpoints',
                    'irc.post_connect.acl',
                    array( '*' )
                )
        );

        $this->hookpoints[ 'irc.post_receive_message' ] = new HookPoint(
                'irc.post_receive_message',
                $this->config->getValue(
                    'hookpoints',
                    'irc.post_receive_message.acl',
                    array( '*' )
                )
        );
    }

    /**
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     *
     * @return array
     */
    public function getDependencies() {
        return $this->dependencies;
    }

    /**
     *
     * @return Socket
     */
    public function getSocket() {
        return $this->socket;
    }

    /**
     *
     * @return array
     */
    public function getCommands() {
        return array_values( $this->commands );
    }

    /**
     *
     * @param string $name
     * @return array
     */
    public function getCommandsByHookPoint( $name ) {
        $commands = array();

        if ( empty( $name ) ) {
            return $commands;
        }

        foreach( $this->commands as $command ) {
            if ( $command->getHookPointName() == $name ) {
                $commands[] = $command;
            }
        }
        return $commands;
    }

}