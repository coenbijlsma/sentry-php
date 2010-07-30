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
     *
     * @var array
     */
    private $messageBuffer;

    /**
     * Constructor
     * @param Plugin $plugin
     * @param array $config
     * @param Socket $socket
     */
    public function __construct( Plugin &$plugin, array $config, Socket &$socket ) {
        parent::__construct( $plugin, $config );
        $this->socket =& $socket;
        $this->messageBuffer = array();
    }

    /**
     * Executes this Command.
     */
    public function execute() {
        echo 'Executing command ' . $this->name . PHP_EOL;

        while( true ) {
            if ( \count( $this->messageBuffer ) || $this->socket->poll() ) {
                $msg = $this->readMessage();
                $message = new Message( $msg );

                echo 'Command: ' .$message->getCommand() . ', Params: ' . \print_r( $message->getParams(), true );
                echo \PHP_EOL . '---------------------------------------------------------' . \PHP_EOL;
            }
            \usleep( 100000 );
        }
    }

    private function readMessage() {
        $count = \count( $this->messageBuffer );

        if ( $count > 1 ) {
            return \array_shift( $this->messageBuffer );
        }
        elseif ( $count == 1 ) {
            if ( \strpos( $this->messageBuffer[ 0 ], "\r\n" ) ) {
                return \array_shift( $this->messageBuffer );
            }
        }
        
        $this->socketRead();
        return $this->readMessage();
    }

    private function socketRead() {
        $result = $this->socket->read( 512 );

        $result = \preg_split( "/\r\n/", $result );
        $count = \count( $result );

        if ( $count ) {

            // Prepare result for merging
            if ( empty( $result[ $count - 1 ] ) ) {
                \array_pop( $result );
                foreach( $result as $key => $value ) {
                    $result[ $key ] = ( $value . "\r\n" );
                }
            }
            else {
                for( $i = 0; $i < ( $count - 1 ); $i++ ) {
                    $result[ $i ] = ( $result[ $i ] . "\r\n" );
                }
            }

            // See if we have an incomplete result left over
            if ( \count( $this->messageBuffer ) ) {
                if ( !\strpos( $this->messageBuffer[ 0 ], "\r\n" ) ) {
                    $this->messageBuffer[ 0 ] .= \array_shift( $result );
                }

                foreach( $result as $message ) {
                    if ( !empty( $message ) ) {
                        $this->messageBuffer[] = $message;
                    }
                }
            }
            else {
                foreach( $result as $message ) {
                    if ( !empty( $message ) ) {
                        $this->messageBuffer[] = $message;
                    }
                }
            }
        }
    }
}