<?php

if ( !defined( 'SENTRY_BASE_PATH' ) ) {
    define( 'SENTRY_BASE_PATH', dirname( __FILE__ ) );
}

function __autoload( $class ) {
    $path = '';
    $class_name = '';
    $namespace = '\\';
    $bs_pos = strrpos( $class, '\\' );

    // First, see if the classname contains a namespace
    if ( $bs_pos ) {
        $namespace = substr( $class, 0, $bs_pos );
        $class_name = substr( $class, $bs_pos + 1 );
    }

    $full_path = SENTRY_BASE_PATH
            . DIRECTORY_SEPARATOR
            . str_replace( '\\', DIRECTORY_SEPARATOR, $namespace )
            . DIRECTORY_SEPARATOR
            . str_replace( '_', DIRECTORY_SEPARATOR, $class_name )
            . '.php';

    require_once( $full_path  );
}