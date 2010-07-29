<?php
namespace Plugins\IRC\Commands;

use Sentry\Plugin as Plugin;
use Sentry\PluginCommand as PluginCommand;

use Plugins\IRC\Socket as Socket;

class TestCommand extends PluginCommand {

    /**
     *
     * @var Socket
     */
    private $socket;

    public function __construct( Plugin &$plugin, array $config, Socket &$socket ) {
        parent::__construct( $plugin, $config );
        $this->socket =& $socket;
    }

    public function execute() {
        echo 'Executing command ' . $this->name . PHP_EOL;

        if ( $this->socket->isConnected() ) {
            echo 'Connected!';
        }
        else {
            echo 'Not connected!';
        }
        echo PHP_EOL;
    }
}