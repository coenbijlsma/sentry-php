<?php
namespace Logging;

use Exceptions\IOException as IOException;

class FileLogger extends Logger {

    /**
     *
     * @var resource
     */
    private $file;

    /**
     * Constructor
     * @param string $class
     * @throws IOException If the log file cannot be opened for reading.
     */
    public function __construct( $class ) {
        parent::__construct( $class );
        $this->file = @\fopen( \SENTRY_BASE_PATH . \DIRECTORY_SEPARATOR . $class . '.log', 'a' );

        if ( $this->file === false ) {
            throw new IOException( 'Error opening log file ' . \SENTRY_BASE_PATH . \DIRECTORY_SEPARATOR . $class .'.log for reading' );
        }
    }

    /**
     * Destructor
     */
    public function __destruct() {
        parent::__destruct();
        \fclose( $this->file );
    }

    /**
     * Flushes the log to the output.
     * @return boolean Whether flushing succeeded
     */
    public function flush() {
        $success = true;

        foreach( $this->messages as $message ) {
            $result = \fwrite( $this->file, $message[ 'date' ] . ' [' . $message[ 'level' ] . '] ' . $message[ 'message' ] . \PHP_EOL );
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