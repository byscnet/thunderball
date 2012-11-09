<?php
/*
 *   Copyright 2012 by byscnet.de - OpenSource Solutions
 * 
 * 	 This file is part of Thunderball Project Payment.
 *
 *   Thunderball Project Payment is free software: you can redistribute 
 *   it and/or modify it under the terms of the GNU General Public License 
 *   as published by the Free Software Foundation, either version 3 of the 
 *   License, or (at your option) any later version.

 *   Thunderball Project Payment is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Thunderball Project Payment.  If not, see <http://www.gnu.org/licenses/>.
 *
 *   Diese Datei ist Teil von Thunderball Project Payment.

 *   Thunderball Project Payment ist Freie Software: Sie können es unter den Bedingungen
 *   der GNU General Public License, wie von der Free Software Foundation,
 *   Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren
 *   veröffentlichten Version, weiterverbreiten und/oder modifizieren.

 *   Thunderball Project Payment wird in der Hoffnung, dass es nützlich sein wird, aber
 *   OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
 *   Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
 *   Siehe die GNU General Public License für weitere Details.

 *   Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
 *   Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
 */

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
     * @ManyToOne(targetEntity="Thunderball_Model_Role")
     * Rolle
     */
    protected $role;
    
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