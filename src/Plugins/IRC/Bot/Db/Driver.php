<?php
namespace Plugins\IRC\Bot\Db;

abstract class Driver {
    
    /**
     *
     * @var int
     */
    private $type;

    /**
     *
     * @var array
     */
    private $config;

    /**
     *
     * @param int $type
     * @param array $config
     */
    public function __construct( $type, array $config = array() ) {
        $this->type = $type;
        $this->config = $config;
    }

    /**
     *
     * @return int
     */
    public  function getType() {
        return $this->type;
    }
}