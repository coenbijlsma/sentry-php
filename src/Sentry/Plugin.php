<?php
namespace Sentry;

abstract class Plugin extends Executor {

    /**
     * @return string
     */
    public abstract function getName();

    /**
     * @return array
     */
    public abstract function getDependencies();

    /**
     * @return array
     */
    public abstract function getCommands();

    /**
     * @return array
     */
    public abstract function getCommandsByHookPoint( $name );
}