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

class Thunderball_Service_User extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_User';
	const NOT_FOUND = 1;
	const WRONG_PW = 2;

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
			$userEntity = $registry->entityManager->createQuery($dql)
			->setParameter(1, $username)
			->getSingleResult();
				
			$user = null;
			
			if ($userEntity != null) {
				$user = new Thunderball_Common_User();
				$user->id = $userEntity->id;
				$user->firstname = $userEntity->firstname;
				$user->lastname = $userEntity->lastname;
				$user->email = $userEntity->email;
				$user->role = $userEntity->role->name;
				$user->salutation = $userEntity->salutation;
				$user->roleId = 'r' . $userEntity->role->id;
			}
		}
		catch (Exception $e)
		{
			throw new Exception(self::NOT_FOUND);
		}
			
		if ($user)
		{
			if ($userEntity->password == self::getPasswordHash($password, $username)) {
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

