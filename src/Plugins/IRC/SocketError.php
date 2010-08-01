<?php
namespace Plugins\IRC;

class SocketError {

    /**
     *
     * @var int
     */
    private $code;

    /**
     *
     * @var string
     */
    private $description;

    /**
     *
     * @param int $code
     */
    public function __construct( $code ) {
        $this->code = $code;
        $this->description = \socket_strerror( $code );
    }

    /**
     *
     * @return int
     */
    public function getCode() {
        return $this->code;
    }

    /**
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
}