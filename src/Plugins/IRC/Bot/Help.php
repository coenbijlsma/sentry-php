<?php
namespace Plugins\IRC\Bot;

use Plugins\IRC\Socket as Socket;
use Plugins\IRC\Message as Message;

class Help extends BotCommand {

    public function __construct( Bot &$bot ) {
        parent::__construct( $bot, 'help' );
    }

    /**
     * @return Message
     */
    public function  execute( Subject &$caller = null ) {

        $help_class = \ucfirst( $this->params[ 'message' ] );
        $help_class = 'Plugins\\IRC\\Bot\\' . $help_class;

        try {
            if (\class_exists( $help_class, true ) ) {

                if ( \is_subclass_of( $help_class, 'Plugins\\IRC\\Bot\\BotCommand' ) ) {
                    $help_response = $help_class::help( $this->params[ 'to' ] );
                    return $help_response[ 0 ];
                }
                else {
                    $messageParams = array();
                    $messageParams[] = $this->params[ 'to' ];
                    $messageParams[] = ( ':No such command ' . $this->params[ 'message' ] );
                    return Message::create( 'PRIVMSG', $messageParams );
                }
            }
            else {
                $messageParams = array();
                $messageParams[] = $this->params[ 'to' ];
                $messageParams[] = ( ':No such command ' . $this->params[ 'message' ] );
                return Message::create( 'PRIVMSG', $messageParams );
            }
        }
        catch( \Exception $ex ) {
            $messageParams = array();
            $messageParams[] = $this->params[ 'to' ];
            $messageParams[] = ( ':No such command ' . $this->params[ 'message' ] );
            return Message::create( 'PRIVMSG', $messageParams );
        }
        
    }

    /**
     * @return array
     */
    public function getRequiredPermissions() {
        return array();
    }

    /**
     * @param string $to
     * @return array
     */
    public static function help( $to ) {
        $helpParams = array();
        $helpParams[] = $to;
        $helpParams[] = ":Help for 'help'. Description: helps you. Syntax: help <command> Example:help say";
        $message = Message::create( 'PRIVMSG', $helpParams );
        return array( $message );
    }
}