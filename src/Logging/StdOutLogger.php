<?php
namespace Logging;
use Sentry\Sentry as Sentry;

class StdOutLogger extends Logger {

    /**
     * Constructor
     * @param string $class
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
        $STDERR = fopen( 'php://stderr', 'a+' );
        $STDOUT = fopen( 'php://stdout', 'a+' );

        foreach( $this->messages as $message ) {
            $success = true;

            if ( in_array( $message[ 'level' ], array( self::LEVEL_ERROR, self::LEVEL_CRITICAL ) ) ) {
                //$message[ 'date' ] . ' [' . $message[ 'level' ] . '] ' . $message[ 'message' ]
                if ( Sentry::CliMode() ) {
                    $success = fwrite( \STDERR, $message[ 'date' ] . ' [' . $message[ 'level' ] . '] ' . $message[ 'message' ] . \PHP_EOL );
                }
                else {
                    $success = fwrite( $STDERR, $message[ 'date' ] . ' [' . $message[ 'level' ] . '] ' . $message[ 'message' ] . \PHP_EOL );
                }
            }
            else {
                if ( Sentry::CliMode() ) {
                    $success = fwrite( \STDOUT, $message[ 'date' ] . ' [' . $message[ 'level' ] . '] ' . $message[ 'message' ] . \PHP_EOL );
                }
                else {
                    $success = fwrite( $STDOUT, $message[ 'date' ] . ' [' . $message[ 'level' ] . '] ' . $message[ 'message' ] . \PHP_EOL );
                }
            }

            if ( false === $success ) {
                break;
            }
        }

        fclose( $STDERR );
        fclose( $STDOUT );

        if ( true === $success ) {
            $this->messages = array();
        }

        return $success;
    }
}