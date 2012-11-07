<?php
/**
 * @Entity @Table(name="user")
 * Benutzer
 */

class Thunderball_Model_User
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var integer
     * Primärer Schlüssel
     */
    protected $id;

    /**
	 * @Column(type="integer")
	 * @var integer
	 * Anrede (0 = Herr / 1 = Frau)
	 */
    protected $salutation;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Titel
	 */
    protected $title;
    
	/**
	 * @Column(type="string")
	 * @var string
	 * Vorname
	 */
    protected $firstname;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Name
	 */
    protected $lastname;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Email
	 */
    protected $email;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Passwort
	 */
    protected $password;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Anmerkungen zur Person
	 */
    protected $notice;
    
    /**
	 * @Column(type="float")
	 * @var float
	 * Stundensatz
	 */
    protected $hourly_rate;
    
    /**
	 * @Column(type="float")
	 * @var float
	 * Arbeitsstunden pro Tag
	 */
    protected $hours_of_work_per_day;
    
	/**
     *  Magische Funktion
     *  Setter
     */
	public function __set($key, $val) 
	{
		$this->$key = $val;
	}
    
	/**
     *  Magische Funktion
     *  Getter
     */
    public function __get($key) 
    {
		return $this->$key;
	}
}