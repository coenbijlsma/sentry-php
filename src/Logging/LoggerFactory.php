<?php
namespace Logging;
use Exceptions\UnknownLoggerException as UnknownLoggerException;

class LoggerFactory {

    const LOGGER_STDOUT = 0;
    const LOGGER_FILE = 1;
    const LOGGER_SYSLOG = 2;

    private static $loggers = array();

    /**
     *
     * @param string $class
     * @param string $type
     * @return Logger
     * @throws UnknownLoggerException if the type of logger is not known.
     */
    public static function getLogger( $class, $type = self::LOGGER_STDOUT ) {
        if ( isset( self::$loggers[ $class ] ) ) {
            return self::$loggers[ $class ];
        }

        switch( $type ) {
            case self::LOGGER_FILE:
                $logger = new FileLogger( $class );
                self::$loggers[ $class ] = $logger;
                return $logger;

            case self::LOGGER_SYSLOG:
                $logger = new SyslogLogger( $class );
                self::$loggers[ $class ] = $logger;
                return $logger;

            case self::LOGGER_STDOUT:
            default:
                $logger = new StdOutLogger( $class );
                self::$loggers[ $class ] = $logger;
                return $logger;
        }
    }
}