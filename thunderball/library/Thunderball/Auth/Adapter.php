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

class Thunderball_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	const NOT_FOUND_MSG = 'Account nicht gefunden';
	const BAD_PW_MSG = 'Falsches Passwort';
	const ACCESS_DENIED = 'Fehler: Keine Übereinstimmung der eingebenen E-Mail Adresse und/oder dem Passwort.';
	const ACC_NOT_ACTIVE = 'Account ist nicht aktiv.';
	
	protected $user;
	protected $username = "";
	protected $password = "";
	
	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}
	
	public function authenticate()
	{
		try {
			$this->user = Thunderball_Service_User::authenticate($this->username, $this->password);
			
			//if ($this->user->is_active != true ) { 
			//	return $this->createResult(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, array(self::ACC_NOT_ACTIVE)); 
			//}
			
			return $this->createResult(Zend_Auth_Result::SUCCESS);
		} 
		catch(Exception $e) {
			if ($e->getMessage() == Thunderball_Service_User::WRONG_PW) {
				return $this->createResult(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, array(self::ACCESS_DENIED));
			}
			
			if ($e->getMessage() == Thunderball_Service_User::NOT_FOUND) {
				return $this->createResult(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, array(self::ACCESS_DENIED));
			}
		}
	}
	
	private function createResult($code, $messages = array())
	{
		return new Zend_Auth_Result($code, $this->user, $messages);
	}
	
}