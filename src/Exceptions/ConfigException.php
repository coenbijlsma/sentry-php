<?php
namespace Exceptions;

class ConfigException extends \Exception {

    public function __construct( $message = 'ConfigException', $code = 0, \Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }
}