<?php
namespace Plugins\IRC\Bot;

class Role {

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
     * @var array
     */
    private $permissions;

    /**
     *
     * @param int $id
     * @param string $name
     */
    public function __construct( $id, $name ) {
        $this->id = $id;
        $this->name = $name;
        $this->permissions = null;
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
     * @return array
     */
    public function getPermissions() {
        if ( \is_null( $this->permissions ) ) {
            $this->permissions = Permission::findByRole( $this );
        }
        return $this->permissions;
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function hasPermission( $name ) {
        $permissions = $this->getPermissions();

        return isset( $permissions[ $name ] );
    }

    /**
     *
     * @param Subject $subject
     * @return array
     */
    public static function findBySubject( Subject $subject ) {
        return array();
        // todo db stuff
    }

}