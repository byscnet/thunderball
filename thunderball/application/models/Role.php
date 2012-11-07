<?php
/**
 * @Entity @Table(name="role")
 * Rolle
 */

class Thunderball_Model_Role
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
	 * Rollenbezeichnung
	 */
    protected $name;
    
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