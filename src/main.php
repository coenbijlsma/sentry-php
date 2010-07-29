<?php
require_once( 'globals.php' );
require_once( 'autoload.php' );

set_error_handler( 'sentry_error_handler', E_ALL );
use Exceptions\InvalidVersionException as InvalidVersionException;
use Logging\LoggerFactory as LoggerFactory;
use Sentry\Sentry as Sentry;

define( 'PHP_MIN_VERSION', '5.3.0' );
define( 'LOGGER_TYPE', LoggerFactory::LOGGER_STDOUT );
define( 'SENTRY_RUN_MODE', 'DEBUG' );

if ( version_compare( PHP_VERSION, PHP_MIN_VERSION ) < 0 ) {
    throw new InvalidVersionException( 'Sentry needs at least PHP version ' . PHP_MIN_VERSION . ', you have ' . PHP_VERSION );
}

$sentry = new Sentry();
$sentry->run();