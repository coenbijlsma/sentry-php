<?php

function sentry_error_handler( $errno, $errstr, $errfile = null, $errline = null, $errcontext = null ) {
    $message = 'Exception ';
    if ( !is_null( $errfile ) ) {
        $message .= ( 'in file ' . $errfile . ' ' );
    }
    if ( !is_null( $errline ) ) {
        $message .= ( 'on line ' . $errline . ' ' );
    }
    if ( !is_null( $errcontext ) ) {
        $message .= ( 'in context ' . print_r( $errcontext, true) . ' ' );
    }
    $message .= ( ': ' . $errstr );
    throw new \Exception( $message, $errno );
}