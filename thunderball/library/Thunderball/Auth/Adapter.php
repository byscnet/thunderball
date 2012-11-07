<?php

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
			
			if ($this->user->is_active != true ) { 
				return $this->createResult(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, array(self::ACC_NOT_ACTIVE)); 
			}
			
			return $this->createResult(Zend_Auth_Result::SUCCESS);
		} 
		catch(Exception $e) {
			if ($e->getMessage() == Wendy_Service_UserService::WRONG_PW) {
				return $this->createResult(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, array(self::ACCESS_DENIED));
			}
			
			if ($e->getMessage() == Wendy_Service_UserService::NOT_FOUND) {
				return $this->createResult(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, array(self::ACCESS_DENIED));
			}
		}
	}
	
	private function createResult($code, $messages = array())
	{
		return new Zend_Auth_Result($code, $this->user, $messages);
	}
	
}