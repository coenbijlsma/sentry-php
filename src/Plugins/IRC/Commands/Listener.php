<?php
namespace Plugins\IRC\Commands;

use Sentry\Plugin as Plugin;
use Sentry\PluginCommand as PluginCommand;

use Plugins\IRC\Socket as Socket;
use Plugins\IRC\Message as Message;

class Listener extends PluginCommand {

    /**
     *
     * @var Socket
     */
    private $socket;

    /**
     * Constructor
     * @param Plugin $plugin
     * @param array $config
     * @param Socket $socket
     */
    public function __construct( Plugin &$plugin, array $config, Socket &$socket ) {
        parent::__construct( $plugin, $config );
        $this->socket =& $socket;
    }

    /**
     * Executes this Command.
     */
    public function execute() {
        echo 'Executing command ' . $this->name . PHP_EOL;

        while( true ) {
            if ( $this->socket->poll() ) {
                $message = new Message( $this->readMessage() );

                echo 'Command: ' .$message->getCommand() . ', Params: ' . \print_r( $message->getParams(), true );
                echo \PHP_EOL . '---------------------------------------------------------' . \PHP_EOL;
            }
            \usleep( 100000 );
        }
    }

    private function readMessage() {
        $message = '';

        while( strpos( $message, "\r\n" ) === false ) {
            $result = $this->socket->read( 2048 );

            if ( !is_null( $result ) && $result !== false ) {
                $message .= $result;
            }
            else {
                break;
            }
        }

        return $message;
    }
}