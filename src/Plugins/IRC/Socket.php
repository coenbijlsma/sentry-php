<?php
namespace Plugins\IRC;

use Logging\LoggerFactory as LoggerFactory;
use Logging\Logger as Logger;
use Exceptions\IOException as IOException;

class Socket {

    /**
     *
     * @var resource
     */
    private $socket;

    /**
     *
     * @var string
     */
    private $address;

    /**
     *
     * @var int
     */
    private $port;

    /**
     *
     * @var bool
     */
    private $isConnected;

    /**
     *
     * @var Logger
     */
    private $logger;

    /**
     *
     * Constructor.
     * @param int $domain
     * @param int $type
     * @param int $protocol
     * @param string $address
     * @param int $port
     * @throws IOException If an I/O error occurs during construction.
     */
    public function __construct( $domain, $type, $protocol, $address, $port = 0 ) {
        $this->logger = LoggerFactory::getLogger( __CLASS__, LOGGER_TYPE );
        $this->socket = socket_create( $domain, $type, $protocol );

        if ( false === $this->socket ) {
            throw new IOException( 'Exception while creating socket: ' . \socket_strerror(\socket_last_error() ) );
        }

        $this->address = $address;
        $this->port = $port;
        $this->isConnected = false;
    }

    /**
     * Destructor.
     */
    public function __destruct() {
        $this->close();
    }

    /**
     *
     * @return bool
     */
    public function connect() {
        $result = \socket_connect( $this->socket, $this->address, $this->port );
        if ( true === $result ) {
            $this->isConnected = true;
        }
        return $result;
    }

    /**
     *
     * @return bool
     */
    public function isConnected() {
        return $this->isConnected;
    }

    /**
     *
     * @return bool
     */
    public function close() {
        if ( $this->isConnected ) {
            \socket_close( $this->socket );
            $this->isConnected = false;
            $this->logger->log( 'Disconnected', Logger::LEVEL_INFO );
        }
        return true;
    }

    /**
     *
     * @param bool $blocking
     * @return bool
     */
    public function setBlocking( $blocking = false ) {
        if ( $blocking ) {
            return \socket_set_block( $this->socket );
        }
        else {
            return \socket_set_nonblock( $this->socket );
        }
    }

    /**
     *
     * @param int $level
     * @param string $optname
     * @param mixed $optval
     * @return bool
     */
    public function setOption( $level, $optname, $optval ) {
        return \socket_set_option( $this->socket, $level, $optname, $optval );
    }

    /**
     *
     * @param int $level
     * @param string $optname
     * @return bool
     */
    public function getOption( $level, $optname ) {
        return \socket_get_option( $this->socket , $level, $optname );
    }

    /**
     *
     * @param int $length
     * @return string
     */
    public function read( $length = 1024 ) {
        $result = \socket_read( $this->socket, $length, \PHP_BINARY_READ );
        if ( $result !== false && \strlen( $result ) == 0 ) {
            return null;
        }

        return $result;
    }

    /**
     *
     * @param string $buffer
     * @return int|bool
     */
    public function write( $buffer ) {
        return \socket_write( $this->socket, $buffer );
    }

    /**
     *
     * @return bool
     * @throws IOException when socket_select() fails.
     */
    public function poll( $timeout = 0 ) {
        $read = array( $this->socket );
        $write = null;
        $except = null;

        $result = \socket_select( $read, $write, $except, $timeout );

        if ( $result === false ) {
            //error
            $msg = \socket_strerror( \socket_last_error( $this->socket ) );
            $this->logger->log( $msg, Logger::LEVEL_WARNING );
            $this->close();
            throw new IOException( 'Error while select()ing socket: ' . $msg );
        }
        elseif ( $result === 0 ) {
            return false;
        }
        return true;
    }

    /**
     * @return SocketError
     */
    public function lastError() {
        return new SocketError( \socket_last_error( $this->socket ) );
    }
}