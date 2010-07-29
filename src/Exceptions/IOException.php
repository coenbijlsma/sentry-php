<?php
namespace Exceptions;

class IOException extends \Exception {

    public function __construct( $message = 'IOException', $code = 0, \Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }
}