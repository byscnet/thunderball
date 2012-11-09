<?php
/**
 * @Entity @Table(name="project_member")
 * Projektmitarbeiter: Welcher Benutzer hat für welches Projekt welche Rolle
 */

class Thunderball_Model_ProjectMember
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var integer
     * Primärer Schlüssel
     */
    protected $id;
    
    /**
	 * @Column(type="datetime", nullable=false)
	 * @var DateTime
	 * Dabei seit
	 */
	protected $since;

	/**
     * @ManyToOne(targetEntity="Thunderball_Model_User")
     * Benutzer
     */
    protected $user;
    
    /**
     * @ManyToOne(targetEntity="Thunderball_Model_ProjectRole")
     * Rolle
     */
    protected $role;
    
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