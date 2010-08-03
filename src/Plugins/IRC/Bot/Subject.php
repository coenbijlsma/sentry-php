<?php
namespace Plugins\IRC\Bot;

class Subject {

    /**
     *
     * @var int
     */
    private $id;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $host;

    /**
     *
     * @var array
     */
    private $roles;

    /**
     *
     * @param int $id
     * @param string $name
     * @param string $host
     */
    public function __construct( $id, $name, $host ) {
        $this->id = $id;
        $this->name = $name;
        $this->host = $host;
        $this->roles = null;
    }

    /**
     *
     * @return int
     */
    public function getId() {
        return $this->id;
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
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     *
     * @return array
     */
    public function getRoles() {
        if ( \is_null( $this->roles ) ) {
            $this->roles = Role::findBySubject( $this );
        }

        return $this->roles;
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function hasRole( $name ) {
        $roles = $this->getRoles();

        return isset( $roles[ $name ] );
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function hasPermission( $name ) {
        foreach( $this->getRoles() as $role ) {
            if ( $role->hasPermission( $name ) ) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param string $name
     * @param string $host
     * @return Subject
     */
    public static function findByHostAndName( $name, $host ) {
        return null;
        // todo db stuff
    }
}