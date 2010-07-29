<?php
namespace Logging;

abstract class Logger {

    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_CRITICAL = 'CRITICAL';

    /**
     *
     * @var string
     */
    protected $className;

    /**
     *
     * @var array
     */
    protected $messages;

    /**
     * Constructor
     * @param string $className
     */
    public function __construct( $className ) {
        $this->className = $className;
        $this->messages = array();
    }

    /**
     * Destructor
     */
    public function __destruct() {
        $this->flush();
    }

    /**
     * Puts a message in the log buffer.
     * @param string $message
     * @param string $level
     */
    public function log( $message, $level = self::LEVEL_INFO ) {
        $message = array(
            'date'=>date( 'c' ),
            'level'=>$level,
            'message'=>$message
        );

        $this->messages[] = $message;
    }

    /**
     * @return bool
     */
    public abstract function flush();
}