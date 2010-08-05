<?php
namespace Plugins\IRC\Bot\Db;

use Config\ConfigSection as ConfigSection;

class Database {

    const TYPE_MYSQL = 0;

    /**
     *
     * @var array
     */
    private static $instances = null;

    /**
     *
     * @var Driver
     */
    private $driver;

    /**
     *
     * @var ConfigSection
     */
    private $config;

    /**
     * Constructor
     * @param int $type
     */
    private function __construct( Configsection $config, $type = self::TYPE_MYSQL ) {
        switch( $type ) {
            case self::TYPE_MYSQL:
                // do driver stuff
                break;
            default:
                throw new \Exception( 'Unsupported type of database: ' . $type );
        }

        $this->type = $type;
    }

    /**
     *
     * @return int
     */
    public function getType() {
        return $this->driver->getType();
    }

    /**
     *
     * @param string $type
     * @return Database
     * @throws Exception If the type of database is not supported.
     */
    public static function create( ConfigSection $config, $type = self::TYPE_MYSQL ) {
        if ( !isset( self::$instances[ $type ] ) ) {
            $db = new Database( $config, $type );
            self::$instances[ $type ] = $db;
        }

        return self::$instances[ $type ];
    }
}