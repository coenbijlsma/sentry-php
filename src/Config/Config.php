<?php
namespace Config;

use Exceptions\IOException as IOException;
use Exceptions\ConfigException as ConfigException;

class Config {

    /**
     *
     * @var array
     */
    private $config;

    /**
     *
     * @param string $file
     */
    public function __construct( $file ) {
        if ( !\file_exists( $file ) ) {
            throw new IOException( 'Cannot find file ' . $file .'.' );
        }

        $config = parse_ini_file( $file, true );

        if ( false === $config ) {
            throw new ConfigException( 'Could not parse ini file.' );
        }

        $this->initSections( $config );
    }

    private function initSections( array &$config ) {

        foreach( $config as $key => &$value ) {
            $this->config[ $key ] = new ConfigSection( $key, $value );
        }
    }

    /**
     *
     * @param string $name
     * @return ConfigSection
     */
    public function getSection( $name ) {
        if ( isset( $this->config[ $name ] ) ) {
            return $this->config[ $name ];
        }
        return null;
    }

    /**
     *
     * @param string $section
     * @param string $key
     * @param mixed $default
     */
    public function getValue( $section, $key, $default = null ) {
        if ( isset( $this->config[ $section ] )  ) {
            return $this->getSection( $section )->get( $key, $default );
        }
        return $default;
    }
}