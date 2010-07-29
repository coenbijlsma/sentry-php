<?php
namespace Sentry;

use Logging\LoggerFactory as LoggerFactory;
use Logging\Logger as Logger;

class Executor {
    
    /**
     *
     * @var Logger
     */
    private $logger;

    /**
     *
     * @var array
     */
    protected $hookpoints;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->hookpoints = array();
        $this->logger = LoggerFactory::getLogger( __CLASS__, LOGGER_TYPE );
    }

    /**
     *
     * @param string $name
     * @return HookPoint
     */
    public final function getHookPointByName( $name ) {
        if ( empty( $name ) ) {
            return null;
        }elseif ( !isset( $this->hookpoints[ $name ] ) ) {
            return null;
        }
        return $this->hookpoints[ $name ];
    }

    /**
     *
     * @param Plugin $plugin
     * @return bool
     */
    public final function attachPluginCommands( Plugin $plugin ) {
        if ( is_null( $plugin ) ) {
            return false;
        }

        foreach( $plugin->getCommands() as $command ) {
            $hookpoint = $this->getHookPointByName( $command->getHookPointName() );
            if ( !is_null( $hookpoint ) ) {
                $hookpoint->attachPluginCommand( $command );
            }
        }
        return true;
    }

    /**
     *
     * @param HookPoint $hookpoint
     * @return bool
     * @throws
     */
    public final function executeCommandsFrom( HookPoint $hookpoint ) {
        if ( is_null( $hookpoint ) ) {
            return false;
        }

        $attachedCommands = $hookpoint->getAttachedCommands();

        foreach( $attachedCommands as $command ) {
            if ( $hookpoint->isAllowed( $command ) ) {
                $command->execute();
            }
            else {
                $this->logger->log( 'Command ' . $command->getPlugin()->getName() . '.' . $command->getName() . ' is not allowed for hookpoint ' . $hookpoint->getName(), Logger::LEVEL_WARNING );
            }
        }
        return true;
    }
}