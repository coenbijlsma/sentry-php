<?php
namespace Sentry;
use Logging\LoggerFactory as LoggerFactory;
use Logging\Logger as Logger;
use Plugins\PluginFactory as PluginFactory;
use Exceptions\ForkException as ForkException;
use Config\Config as Config;

final class Sentry extends Executor implements IPluginHandler {

    /**
     *
     * @var Config
     */
    private $config;

    /**
     *
     * @var Logger
     */
    private $logger;

    /**
     *
     * @var array
     */
    private $plugins;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->config = new Config( \SENTRY_BASE_PATH . \DIRECTORY_SEPARATOR . 'sentry.conf', true );
        $this->logger = LoggerFactory::getLogger( __CLASS__, LOGGER_TYPE );
        $this->hookpoints = array();
        $this->initHookPoints();

        $this->plugins = PluginFactory::loadAll( $this->config->getValue( 'plugins', 'load', array() ) );

        $errors = PluginFactory::getErrors();
        if ( count( $errors ) ) {
            $this->logger->log( 'There were errors while loading the plugins: ' . print_r( $errors, true ), Logger::LEVEL_WARNING );
        }

        foreach( $this->plugins as $plugin ) {
            $this->attachPluginCommands( $plugin );
        }
    }

    /**
     * Destructor
     */
    public function __destruct() {
        $this->executeCommandsFrom( $this->getHookPointByName( 'core.pre_shutdown' ) );
        $this->prepareExit();
    }

    /**
     * Prepares Sentry for exiting, cleaning up what's needed.
     */
    private function prepareExit() {
        echo 'Preparing exit...' . PHP_EOL;
        return true;
    }

    /**
     * Initializes the available hookpoints.
     */
    private function initHookPoints() {

        $this->hookpoints[ 'core.post_startup' ] = new HookPoint(
            'core.post_startup',
            $this->config->getValue(
                'hookpoints',
                'core.post_startup.acl',
                array( '*' )
            )
        );

        $this->hookpoints[ 'core.pre_shutdown' ] = new HookPoint(
            'core.pre_shutdown',
            $this->config->getValue(
                'hookpoints',
                'core.pre_shutdown.acl',
                array( '*' )
            )
        );
    }

    /**
     * Starts Sentry
     */
    public function run() {
        $this->executeCommandsFrom( $this->getHookPointByName( 'core.post_startup' ) );
    }

    /**
     * Returns whether we're in CLI mode.
     * @return bool
     */
    public static function CliMode() {
        return 'cli' === \php_sapi_name();
    }
}