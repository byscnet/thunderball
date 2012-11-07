<?php
class Thunderball_Service_User extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_User';

	public function getCompleteName($user)
	{
		$salutation = array('Herr', 'Frau');
		$text = array();
		$text[] = $salutation[$user->salutation];
		$text[] = $user->title;
		$text[] = $user->firstname;
		$text[] = $user->lastname;
		
		return join(' ', $text);
	}
	
	public function search($params)
	{
		$aColumns = array('d.firstname', 'd.lastname', );
		$searchInColumns = array('d.firstname', 'd.lastname', );

		return $this->searchQuery($params, $aColumns, $searchInColumns)->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(u.id) AS num FROM Thunderball_Model_User u');
		return $count->getSingleScalarResult();
	}

	public static function authenticate($username, $password)
	{
		try {
			$registry = Zend_Registry::getInstance();
			$dql = "SELECT u FROM Thunderball_Model_User u WHERE u.email = ?1";
			$user = $registry->entityManager->createQuery($dql)
			->setParameter(1, $username)
			->getSingleResult();
		}
		catch (Exception $e)
		{
			throw new Exception(self::NOT_FOUND);
		}
			
		if ($user)
		{
			if ($user->password == self::getPasswordHash($password, $username)) {
				return $user;
			}
			throw new Exception(self::WRONG_PW);
		}
		throw new Exception(self::NOT_FOUND);
	}

	public function generatePassword()
	{
		$pool = "qwertzupasdfghkyxcvbnm";
		$pool .= "23456789";
		$pool .= "WERTZUPLKJHGFDSAYXCVBNM";

		srand ((double)microtime()*1000000);
		$pass_word = '';

		for($index = 0; $index < 5; $index++)
		{
			$pass_word .= substr($pool,(rand()%(strlen ($pool))), 1);
		}
		return $pass_word;
	}

	public static function getPasswordHash($password, $email)
	{
		return md5($password . $email);
	}

	public function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

