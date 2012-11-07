<?php
/**
 * @Entity @Table(name="customer")
 * Kunde
 */

class Thunderball_Model_Customer
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
	 * Name
	 */
    protected $name;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Firmenbeschreibung
	 */
    protected $description;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_Company")
     * Kunde der Firma
     */
    protected $company;
    
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