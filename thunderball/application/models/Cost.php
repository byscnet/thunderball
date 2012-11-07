<?php
/**
 * @Entity @Table(name="cost")
 * Kosten für das Projekt
 */

class Thunderball_Model_Cost
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
	 * Beschreibung
	 */
    protected $description;
    
    /**
	 * @Column(type="string")
	 * @var string
	 * Rechnungsnummer
	 */
    protected $invoice_number;
    
    /**
	 * @Column(type="date")
	 * @var date
	 * Datum
	 */
    protected $date;
    
    /**
	 * @Column(type="float")
	 * @var float
	 * Preis
	 */
    protected $price;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_Project")
     * Kosten für Projekt
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