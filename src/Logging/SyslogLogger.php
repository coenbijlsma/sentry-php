<?php
namespace Logging;

class SyslogLogger extends Logger {

    /**
     * Constructor
     * @param string $class
     * @throws IOException If the log file cannot be opened for reading.
     */
    public function __construct( $class ) {
        parent::__construct( $class );
    }

    /**
     * Flushes the log to the output.
     * @return boolean Whether flushing succeeded
     */
    public function flush() {
        $success = true;

        foreach( $this->messages as $message ) {
            /**
             * @var int
             */
            $level = \LOG_INFO;

            switch( $message[ 'level' ] ) {
                case self::LEVEL_CRITICAL:
                    $level = \LOG_CRIT; break;
                case self::LEVEL_DEBUG:
                    $level = \LOG_DEBUG; break;
                case self::LEVEL_ERROR:
                    $level = \LOG_ERR; break;
                case self::LEVEL_INFO:
                    $level = \LOG_INFO; break;
                case self::LEVEL_WARNING:
                    $level = \LOG_WARNING; break;
                default:
                    break;
            }

            $result = \syslog( $level, $message[ 'date' ] . ' [' . $message[ 'level' ] . '] ' . $message[ 'message' ] );
            if ( false === $result ) {
                $success = false;
                break;
            }
        }

        if ( true === $success ) {
            $this->messages = array();
        }

        return $success;
    }
}