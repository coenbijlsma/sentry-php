<?php
namespace Sentry;

final class HookPoint {

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var array
     */
    private $attachedCommands;

    /**
     *
     * @var array
     */
    private $acl;

    /**
     * Constructor
     * @param string $name
     */
    public function __construct( $name, array $acl ) {
        $this->name = $name;
        $this->attachedCommands = array();
        $this->acl = $acl;
    }

    /**
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     *
     * @param IPluginCommand $command
     * @return bool
     */
    public function attachPluginCommand( PluginCommand $command ) {
        if ( !isset( $this->attachedCommands[ $command->getName() ] ) ) {
            $this->attachedCommands[ $command->getName() ] = $command;
            return true;
        }
        return false;
    }

    /**
     *
     * @return array
     */
    public function getAttachedCommands() {
        return $this->attachedCommands;
    }

    public function isAllowed( PluginCommand $command ) {
        foreach( $this->acl as $item ) {
            if( '*' === $item ||  $item === ( $command->getPlugin()->getName() . '.' . $command->getName() ) ) {
                return true;
            }
        }

        return false;
    }
}