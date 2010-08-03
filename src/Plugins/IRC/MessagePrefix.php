<?php
namespace Plugins\IRC;

class MessagePrefix {

    /**
     *
     * @var string
     */
    private $raw;

    /**
     *
     * @var string
     */
    private $serverOrNick;

    /**
     *
     * @var string
     */
    private $user;

    /**
     *
     * @var string
     */
    private $host;

    /**
     * Constructor
     * @param string $raw
     */
    public function __construct( $raw ) {
        if ( empty( $raw ) ) {
            throw new \Exception( 'Illegal value for $raw.' );
        }

        $this->raw = $raw;
        $this->init();
    }

    /**
     * Initializes this prefix
     */
    private function init() {
        $parts = \explode( '!', $this->raw );
        $this->serverOrNick = null;
        $this->user = null;
        $this->host = null;

        switch( \count( $parts ) ) {
            case 2:
                $userhost = $parts[ 1 ];
                $userhost = \explode( '@', $userhost );
                $this->user = $userhost[ 0 ];
                $this->host = isset( $userhost[ 1 ] ) ? $userhost[ 1 ] : null;
            case 1:
                $this->serverOrNick = $parts[ 0 ];
                break;
            default:
                throw new \Exception( 'Invalid message prefix' );
        }
    }

    /**
     *
     * @return string
     */
    public function getServerOrNick() {
        return $this->serverOrNick;
    }

    /**
     *
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     *
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     *
     * @return string
     */
    public function getRaw() {
        return $this->raw;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->raw;
    }

}