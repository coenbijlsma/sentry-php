<?php
namespace Plugins\IRC\Commands;

use Logging\LoggerFactory as LoggerFactory;
use Logging\Logger as Logger;

use Plugins\IRC\Socket as Socket;
use Plugins\IRC\Message as Message;

use Sentry\Plugin as Plugin;
use Sentry\PluginCommand as PluginCommand;

class MessageDispatcher extends PluginCommand {

    /**
     *
     * @var Logger
     */
    private $logger;

    /**
     *
     * @var Socket
     */
    private $socket;

    /**
     * Constructor
     */
    public function __construct( Plugin &$plugin, array $config, Socket &$socket ) {
        parent::__construct( $plugin, $config );
        $this->socket =& $socket;
        $this->logger = LoggerFactory::getLogger( __CLASS__, LOGGER_TYPE );
    }

    /**
     * Handles the PING message
     * @param Message $message
     */
    private function dispatchPingMessage( Message &$message ) {
        $params = $message->getParams();

        if ( \count( $params ) ) {
            $response = Message::create( 'PONG', array( $params[ 0 ] ) );
            if ( false === $this->socket->write( $response ) ) {
                $this->logger->log( 'Count not send PONG response: ' . $this->socket->lastError()->getDescription(), Logger::LEVEL_WARNING );
            }
        }
        else {
            $this->logger->log( 'Could not send PONG message because I received no daemon name.', Logger::LEVEL_WARNING );
        }
    }

    /**
     * Handles the PRIVMSG message
     * @param Message $message
     */
    private function dispatchPrivmsgMessage( Message &$message ) {
        $params = $message->getParams();
        echo $message->getPrefix() . ' sais: ' . $params[ 1 ] . \PHP_EOL;
    }

    /**
     * Dispatches the given message.
     * @param Message $message
     */
    public function dispatch( Message &$message ) {
        switch( $message->getCommand() ) {
            case 'PING':
                $this->dispatchPingMessage( $message );
                break;
            case 'PRIVMSG':
                $this->dispatchPrivmsgMessage( $message );
                break;
        }
        echo 'Command: ' .$message->getCommand() . ', Params: ' . \print_r( $message->getParams(), true );
        echo \PHP_EOL . '---------------------------------------------------------' . \PHP_EOL;
    }

    /**
     * Executes this command
     * @param array $params
     */
    public function execute( array $params = array() ) {
        if ( !\count( $params ) ) {
            return;
        }
        $message = $params[ 0 ];

        if ( ! ( $message instanceof Message ) ) {
            return;
        }
        $this->dispatch( $message );
    }
}