<?php
namespace Plugins\IRC\Bot;

use Plugins\IRC\Message as Message;

abstract class BotCommand {

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var Bot
     */
    protected $bot;

    /**
     *
     * @var array
     */
    protected $params;

    /**
     *
     * @param string $name
     * @throws Exception If $name is empty or not a string.
     */
    public function __construct( Bot &$bot, $name ) {
        if ( empty( $name ) || !\is_string( $name ) ) {
            throw new \Exception( 'Illegal argument for $name.' );
        }
        $this->name = $name;
        $this->bot = $bot;
        $this->params = array();
    }

    /**
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    public function setParams( array $params = array() ) {
        $this->params = $params;
    }

    /**
     * @return Message
     */
    abstract function execute( Subject &$caller = null );

    /**
     *
     * @param Message $message
     * @return BotCommand
     */
    public static function fromMessage( Bot &$bot, Message &$message ) {
        if ( $message && 'PRIVMSG' == $message->getCommand() ) {
            
            if ( $bot->containsBotCommand( $message ) ) {
                $params = $message->getParams();

                if ( \count( $params ) == 2 ) {
                    $from = $message->getPrefix();
                    $saidto = $params[ 0 ];
                    $text = \explode( ' ', \trim( $params[ 1 ] ) );

                    if ( \count( $text ) ) {
                        $command = \array_shift( $text );

                        return self::createBotCommand( $bot, \substr( $command, 1), $text, $from );
                    }
                }
            }
        }

        return null;
    }

    /**
     *
     * @param Bot $bot
     * @param string $command
     * @param array $params
     * @param string $from
     * @param string $to
     * @return BotCommand
     */
    private static function createBotCommand( Bot &$bot, $command, array $params = array(), $from = null ) {
        switch( \strtolower( $command ) ) {
            case 'say':
                $say = new Say( $bot );
                $sayParams = array();
                $sayParams[ 'from' ] = $from;
                $sayParams[ 'to' ] = \array_shift( $params );
                $sayParams[ 'message' ] = \implode( ' ', $params );
                $say->setParams( $sayParams );
                return $say;
            default:
                return null;
        }
    }
}