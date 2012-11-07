<?php
/**
 * @Entity @Table(name="contact_person")
 * Kunde
 */

class Thunderball_Model_ContactPerson
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var integer
     * Primärer Schlüssel
     */
    protected $id;
	
    /**
	 * @Column(type="string")
	 * @var string
	 * Vorname Ansprechpartner
	 */
    protected $firstname;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Name Ansprechpartner
	 */
    protected $lastname;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Email Ansprechpartner
	 */
    protected $email;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Anmerkungen zur Person
	 */
    protected $notice;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_Customer")
     * Ansprechpartner bei Kunden
     */
    protected $customer;
    
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