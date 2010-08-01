<?php
namespace Sentry;

abstract class PluginCommand {

    /**
     *
     * @var Plugin
     */
    protected $plugin;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $hookPointName;

    /**
     *
     * @var array
     */
    protected $config;

    public function __construct( Plugin &$plugin, array $config ) {
        $this->plugin =& $plugin;
        $this->config = $config;
        $this->name = $this->config[ 'name' ];
        $this->hookPointName = $this->config[ 'hook_point' ];
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
     * @return Plugin
     */
    public function &getPlugin() {
        return $this->plugin;
    }

    /**
     * Returns the name of the HookPoint this PluginCommand will attach to.
     * @return string
     */
    public function getHookPointName() {
        return $this->hookPointName;
    }

    /**
     *
     * @return array
     */
    public function getDependencies() {
        if ( isset( $this->config[ 'dependencies' ] ) ) {
            return $this->config[ 'dependencies' ];
        }
        return array();
    }

    /**
     * Executes this command
     * @param array $params
     */
    public abstract function execute( array $params = array() );

}