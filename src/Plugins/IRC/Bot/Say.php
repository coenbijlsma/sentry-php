<?php
namespace Plugins\IRC\Bot;

use Plugins\IRC\Socket as Socket;
use Plugins\IRC\Message as Message;

class Say extends BotCommand {

    public function __construct( Bot &$bot ) {
        parent::__construct( $bot, 'say' );
    }

    /**
     * @return Message
     */
    public function  execute( Subject &$caller = null ) {
        $messageParams = array();
        $messageParams[] = $this->params[ 'to' ];
        $messageParams[] = ( ':' . $this->params[ 'message' ] );
        return Message::create( 'PRIVMSG', $messageParams );
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
        $helpParams[] = ":Help for 'say'. Description: says something. Syntax: say <receiver> <message> Example:say #sentry Sentry rocks!";
        $message = Message::create( 'PRIVMSG', $helpParams );
        return array( $message );
    }
}