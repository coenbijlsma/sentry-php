<?php
namespace Exceptions;

class InvalidVersionException extends \Exception {

    public function __construct( $message = 'InvalidVersionException', $code = 0, \Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }
}
