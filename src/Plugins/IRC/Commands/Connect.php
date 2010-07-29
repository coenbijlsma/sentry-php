<?php
namespace Plugins\IRC\Commands;

use Sentry\Plugin as Plugin;
use Sentry\HookPoint as HookPoint;
use Sentry\PluginCommand as PluginCommand;
use Exceptions\IOException as IOException;
use Plugins\IRC\Socket as Socket;

class Connect extends PluginCommand {

    /**
     *
     * @var string
     */
    private $pass;

    /**
     *
     * @var string
     */
    private $realname;

    /**
     *
     * @var array
     */
    private $channels;

    /**
     *
     * @var string
     */
    private $user;

    /**
     *
     * @var string
     */
    private $nick;

    /**
     *
     * @var Socket
     */
    private $socket;
    
    public function __construct( Plugin &$plugin, array $config, Socket &$socket ) {
        parent::__construct( $plugin, $config );

        $this->pass = $this->config[ 'pass' ];
        $this->realname = $this->config[ 'realname' ];
        $this->channels = \explode( ',', $this->config[ 'channels' ] );
        $this->user = $this->config[ 'user' ];
        $this->nick = $this->config[ 'nick' ];
        $this->socket =& $socket;
    }

    /**
     * @throws IOException If an I/O error occurs during execution of
     * this command.
     */
    public function execute() {
        echo 'Executing command ' . $this->name . \PHP_EOL;

        if ( false === $this->socket->connect() ) {
            throw new IOException( \socket_strerror(\socket_last_error() ) );
        }

        $this->socket->write( 'PASS ' . $this->pass . "\r\n" );
        $this->socket->write( 'NICK ' . $this->nick . "\r\n" );
        $this->socket->write( 'USER ' . $this->user . ' foo bar :' . $this->user . "\r\n" );

        \usleep( 100000 );
        
        foreach( $this->channels as $channel ) {
            $this->socket->write( 'JOIN ' . $channel . "\r\n" );
        }
        
        $this->plugin->executeCommandsFrom( $this->plugin->getHookPointByName( 'irc.post_connect' ) );
    }
}