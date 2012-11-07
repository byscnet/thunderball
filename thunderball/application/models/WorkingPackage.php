<?php
/**
 * @Entity @Table(name="working_package")
 * Arbeitspacket
 */

class Thunderball_Model_WorkingPackage
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
	 * Bezeichnung des Pakets
	 */
    protected $name;
    
    /**
	 * @Column(type="datetime")
	 * @var DateTime
	 * Projektbeginn
	 */
	protected $start_date;
	
	/**
	 * @Column(type="datetime")
	 * @var DateTime
	 * Ist-Enddatum
	 */
	protected $end_date;
	
	/**
	 * @Column(type="datetime")
	 * @var DateTime
	 * Soll-Enddatum
	 */
	protected $planed_end_date;
	
	/**
	 * @Column(type="boolean")
	 * @var boolean
	 * Ist abgeschlossen
	 */
	protected $is_closed;
	
	/**
	 * @Column(type="float")
	 * @var float
	 * Verfügbare Stunden
	 */
	protected $hours;
	
	/**
     * @ManyToOne(targetEntity="Thunderball_Model_Project")
     * Projekt
     */
    protected $project;
    
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