<?php
/**
 * @Entity @Table(name="working_hour")
 * Gebuchte Arbeitsstunden
 */

class Thunderball_Model_WorkingHour
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
	 * Informationen zu den gebuchten Stunden
	 */
    protected $notice;
    
    /**
	 * @Column(type="date")
	 * @var Date
	 * Buchungsdatum
	 */
	protected $date;
	
	/**
	 * @Column(type="float")
	 * @var float
	 * Gebuchte Stunden
	 */
	protected $hours;
	
	/**
     * @ManyToOne(targetEntity="Thunderball_Model_Project")
     * Projekt
     */
    protected $project;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_WorkingPackage")
     * Arbeitspacket
     */
    protected $working_package;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_User")
     * Benutzer
     */
    protected $user;
    
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