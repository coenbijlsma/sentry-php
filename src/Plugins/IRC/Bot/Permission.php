<?php
namespace Plugins\IRC\Bot;

class Permission {

    const PERM_ALLOW = 0;

    const PERM_DENY = 1;

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
     * @param int $id
     * @param string $name
     * @throws Exception If $id or $name is empty
     */
    public function __construct( $id, $name ) {
        $this->id = $id;
        $this->name = $name;
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
     * @param Role $role
     * @return array
     */
    public static function findByRole( Role $role ) {
        return array();
        // todo: db stuff
    }
}