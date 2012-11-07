<?php
/**
 * @Entity @Table(name="project")
 * Projekt
 */

class Thunderball_Model_Project
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var integer
     * Primärer Schlüssel
     */
    protected $id;

	/**
	 * @Column(type="string", nullable=false)
	 * @var string
	 * Name
	 */
    protected $name;
    
    /**
	 * @Column(type="string", nullable=true)
	 * @var string
	 * Projektbeschreibung
	 */
    protected $description;
    
    /**
	 * @Column(type="string", nullable=true)
	 * @var string
	 * Anmerkungen zum Projekt
	 */
    protected $notice;
    
    /**
	 * @Column(type="integer", nullable=true)
	 * @var integer
	 * Kalkuliertes Risiko
	 */
    protected $risk;
    
    /**
	 * @Column(type="datetime", nullable=false)
	 * @var DateTime
	 * Projektbeginn
	 */
	protected $start_date;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 * @var DateTime
	 * Ist-Enddatum
	 */
	protected $end_date;
	
	/**
	 * @Column(type="datetime", nullable=false)
	 * @var DateTime
	 * Soll-Enddatum
	 */
	protected $planed_end_date;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 * @var DateTime
	 * Stunden eingefroren bis
	 */
	protected $boocking_blocked_till;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 * @var DateTime
	 * Gewährleistung bis
	 */
	protected $garantee_till;
	
	/**
	 * @Column(type="string", nullable=true)
	 * @var string
	 * Farbe für eine bessere Übersicht
	 */
	protected $colour;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_Project")
     * Unterprojekt von Projekt
     */
    protected $parent;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_ProjectCategory")
     * Kategorie
     */
    protected $category;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_Customer")
     * Kunde
     */
    protected $customer;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_Company")
     * Firma
     */
    protected $company;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_ProjectStatus")
     * Status
     */
    protected $status;
    
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