<?php
namespace Plugins\IRC;


final class Message {

    const MSG_PREFIX_IDENT = ':';
    const MSG_SPACE = ' ';
    const MSG_CRLF = "\r\n";

    /**
     *
     * @var string
     */
    private $raw;

    /**
     *
     * @var string
     */
    private $prefix;

    /**
     *
     * @var string
     */
    private $command;

    /**
     *
     * @var array
     */
    private $params;

    /**
     * Constructor
     * @param string $raw
     * @param bool $received
     * @throws Exception If the message came from IRC but is empty.
     */
    public function __construct( $raw = null, $received = true ) {
        if ( $received && !is_null( $raw ) ) {
            $this->initFromReceivedMessage( $raw );
        }
        else {
            $this->prefix = null;
            $this->command = null;
            $this->params = null;
            $this->raw = null;
        }
    }

    /**
     *
     * @param string $raw
     */
    private function initFromReceivedMessage( $raw ) {
        if ( empty( $raw ) ) {
            throw new \Exception( 'Empty message' );
        }

        $this->raw = $raw;
        $this->params = array();

        if ( \strlen( $raw ) && \substr( $raw, 0, 1 ) == self::MSG_PREFIX_IDENT ) {
            // We have a prefix
            $space_pos = \strpos( $raw, self::MSG_SPACE );

            if ( false !== $space_pos ) {
                $this->prefix = new MessagePrefix( \substr( $raw, 1, $space_pos - 1 ) );
            }
            else {
                $this->prefix = null;
            }
        }

        $prefix_len = \strlen( $this->prefix );
        if ( $prefix_len ) {
            $raw = \substr( $raw, $prefix_len + 2 );
        }

        $this->command = \substr( $raw, 0, \strpos( $raw, self::MSG_SPACE ) );

        $raw = \substr( $raw, \strlen( $this->command ) + 1 );

        $raw = \str_replace( self::MSG_CRLF, '', $raw );
        $raw = \trim( $raw );
        $colon_pos = \strpos( $raw, self::MSG_PREFIX_IDENT );

        if ( $colon_pos ) {
            $left = \substr( $raw, 0, $colon_pos );
            $right = \substr( $raw, $colon_pos );
            $right = \str_replace( self::MSG_PREFIX_IDENT, '', $right );
            $this->params = \explode( self::MSG_SPACE, \trim( $left ) );

            if ( !empty( $right ) ) {
                $this->params[] = $right;
            }
        }
        else {
            $this->params = \explode( self::MSG_SPACE, $raw );
        }
    }

    /**
     *
     * @param string $command
     */
    private function setCommand( $command ) {
        $this->command = \strtoupper( $command );
    }

    /**
     * 
     * @param array $params
     */
    private function setParams( array $params ) {
        $param_count = \count( $params );

        if ( $param_count ) {
            $params = \array_values( $params );
            $last_value = $params[ $param_count - 1 ];

            if ( \strlen( $last_value ) && \strpos( $last_value, self::MSG_SPACE ) ) {
                if ( self::MSG_PREFIX_IDENT != $last_value[ 0 ] ) {
                    $params[ $param_count - 1 ] = ( self::MSG_PREFIX_IDENT . $last_value );
                }
            }
        }
        $this->params = $params;
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
     * @return MessagePrefix
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     *
     * @return string
     */
    public function getCommand() {
        return $this->command;
    }

    /**
     *
     * @return <type>
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @return string
     */
    public function __toString() {
        if ( !\is_null( $this->raw ) ) {
            return $this->raw;
        }

        return $this->command . self::MSG_SPACE . \implode( self::MSG_SPACE, $this->params ) . self::MSG_CRLF;
    }

    /**
     *
     * @param string $command
     * @param array $params
     * @return Message
     */
    public static function create( $command, array $params ) {
        $message = new Message( null, false );
        $message->setCommand( $command );
        $message->setParams( $params );
        return $message;
    }
}