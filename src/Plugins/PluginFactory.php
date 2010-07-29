<?php
namespace Plugins;
use Sentry\Plugin as Plugin;

class PluginFactory {

    /**
     *
     * @var array
     */
    private static $plugins = array();

    /**
     *
     * @var array
     */
    private static $errors = array();

    /**
     *
     * @param array $which
     * @return array
     */
    public static function loadAll( array $which ) {
        foreach( $which as $name ) {
            if ( !isset( self::$plugins[ $name ] ) ) {
                $plugin = self::loadPlugin( $name );
                if ( !is_null( $plugin ) ) {
                    self::$plugins[ $plugin->getName() ] = $plugin;
                }
            }
        }
        return self::$plugins;
    }

    /**
     *
     * @param string $name
     * @return Plugin
     * @throws NoSuchPluginException If there is no such plugin
     * @throws \Exception If the loaded class is not a plugin.
     */
    public static function loadPlugin( $name ) {
        $bs_pos = strrpos( $name, '\\' );

        if ( false === $bs_pos ) {
            throw new \Exceptions\NoSuchPluginExceptoin( 'No such plugin: ' . $name );
        }
        else {
            $pluginClass = 'Plugins\\' . $name;
            $plugin = new $pluginClass();
            if ( ! ( $plugin instanceof Plugin ) ) {
                self::$errors[] = 'Could not load class ' . get_class( $plugin ) . ': not an instance of Plugin.';
                return null;
            }
            return $plugin;
        }
    }

    /**
     *
     * @return array
     */
    public static function getErrors() {
        $errors = array_values( self::$errors );
        self::$errors = array();
        return $errors;
    }
}