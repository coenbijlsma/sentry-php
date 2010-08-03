<?php
namespace Plugins\IRC\Bot;

use Config\Config as Config;
use Config\ConfigSection as ConfigSection;

use Plugins\IRC\Message as Message;
use Plugins\IRC\Bot\Subject as Subject;

class Bot {
    
    /**
     *
     * @var Bot
     */
    private static $instance = null;

    /**
     *
     * @var Config
     */
    private $config;

    /**
     * Constructor
     */
    private function __construct() {
        $this->config = new Config( \dirname( __FILE__ ) . \DIRECTORY_SEPARATOR . 'bot.conf' );
    }

    /**
     * Returns whether ths Bot accepts this type of message.
     * @param Message $message
     * @return bool
     */
    public function accepts( Message $message ) {
        return \in_array( $message->getCommand(), $this->config->getValue( 'messages', 'accepted_messages', array() ) );
    }

    /**
     * Processes the given message and returns whether it succeeded.
     * @param Message $message
     * @return Message The reply, if any
     */
    public function process( Message &$message ) {
        if ( $this->accepts( $message ) ) {

            $botCommand = BotCommand::fromMessage( $this, $message );
            if ( !\is_null( $botCommand ) ) {
                if ( !\is_null( $message->getPrefix() ) ) {
                    $prefix = $message->getPrefix();
                    $subject = Subject::findByHostAndName( $prefix->getUser(), $prefix->getHost() );
                    return $botCommand->execute( $subject );
                }
                else {
                    $ref = null;
                    return $botCommand->execute( $ref );
                }
            }
        }
        return null;
    }

    public function containsBotCommand( Message &$message ) {
        $params = $message->getParams();
        if ( \count( $params ) == 2 ) {
            // we have a valid PRIVMSG
            $cmd = $params[ 1 ];

            if ( \strlen( \trim( $cmd ) ) ) {
                if (\in_array( $cmd[ 0 ], $this->config->getValue( 'general', 'control_char', array( '$' ) ) ) ) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     *
     * @return Bot
     */
    public static function getInstance() {
        if ( \is_null( self::$instance ) ) {
            self::$instance = new Bot();
        }

        return self::$instance;
    }
}