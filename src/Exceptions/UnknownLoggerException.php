<?php
namespace Exceptions;

class UnknownLoggerException extends \Exception {

    public function __construct( $message = 'UnknownLoggerException', $code = 0, \Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }
}