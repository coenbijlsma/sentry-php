<?php
namespace Plugins\IRC\Bot;

class Session {

    /**
     *
     * @var array
     */
    private static $sessions = array();

    /**
     *
     * @var Subject
     */
    private $subject;

    /**
     *
     * @var int
     */
    private $created_ts;

    /**
     *
     * @var int
     */
    private $last_updated_ts;

    /**
     * Constructor
     * @param Subject $subject
     */
    private function __construct( Subject &$subject ) {
        $this->subject =& $subject;
        $this->created_ts = \date( 'U' );
        $this->touch();
    }

    /**
     * Destructor
     */
    public function __destruct() {
        if ( !$this->isAnonymous() ) {
            unset ( self::$sessions[ $this->subject->getName() . $this->subject->getHost() ] );
        }
    }

    /**
     *
     * @return Subject
     */
    public function &getSubject() {
        return $this->subject;
    }

    /**
     * Updates the last time this session was used.
     */
    public function touch() {
        $this->last_updated_ts = \date( 'U' );
    }

    public function isAnonymous() {
        return \is_null( $this->subject );
    }


    /**
     *
     * @param string $name
     * @param string $host
     * @return Subject|null
     */
    public static function get( $name, $host ) {
        $subject = Subject::findByHostAndName( $name, $host );

        return self::getSession( $subject );
    }

    /**
     *
     * @param Subject $subject
     * @return Session 
     */
    private static function getSession( Subject &$subject ) {
        if ( \is_null( $subject ) ) {
            if ( isset( self::$sessions[ 'anonymous' ] ) ) {
                return self::$sessions[ 'anonymous' ];
            }
        }
        else {
            if ( isset( self::$sessions[ $subject->getName() . $subject->getHost() ] ) ) {
                return self::$sessions[ $subject->getName() . $subject->getHost() ];
            }
        }

        $session = new Session( $subject );

        if ( $session->isAnonymous() ) {
            self::$sessions[ 'anonymous' ] = $session;
        }
        else {
            self::$sessions[ $subject->getName() . $subject->getHost() ] = $session;
        }
        return $session;
    }
}