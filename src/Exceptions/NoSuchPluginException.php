<?php
namespace Exceptions;

class NoSuchPluginExceptoin extends \Exception {

    public function __construct( $message = 'NoSuchPluginException', $code = 0, \Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }
}