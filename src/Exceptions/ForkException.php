<?php
namespace Exceptions;

class ForkException extends \Exception {

    public function __construct( $message = 'ForkException', $code = 0, \Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }
}