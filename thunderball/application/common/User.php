<?php
class Thunderball_Common_User
{
	private $firstname;
	private $lastname;
	private $email;
	private $role;
	private $roleId;
	private $salutation;
	private $id;

	public function __set($key, $val)
	{
		$this->$key = $val;
	}

	public function __get($key)
	{
		return $this->$key;
	}
}