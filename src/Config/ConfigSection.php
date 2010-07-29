<?php
namespace Config;

class ConfigSection {

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var array
     */
    private $data;

    /**
     *
     * @param string $name
     * @param array $data
     */
    public function __construct( $name, array $data ) {
        $this->name = $name;
        $this->data = $data;
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
     * @param string $key
     * @return mixed
     */
    public function get( $key, $default = null ) {
        if ( isset( $this->data[ $key ] ) ) {
            return $this->data[ $key ];
        }
        return $default;
    }
}