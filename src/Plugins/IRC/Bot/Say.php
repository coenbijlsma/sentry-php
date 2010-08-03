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
}