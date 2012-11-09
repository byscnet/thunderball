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

class Thunderball_Mailer_Mail
{
	const TYPE_ERROR = 'error.phtml';
	const TYPE_NEW_PASSWORD = 'new-password.phtml';
	
	const ERROR_MAIL_TO = 'support@byscnet.de';

	private $bodyText;
	private $bodyHtml;
	private $from;
	private $fromName;
	private $to;
	private $subject;
	private $placeholders;
	private $template;


	function __construct($type)
	{
		$this->from = 'thunderball@byscnet.de';
		$this->fromName = 'Thunderball Project Payment';
		$this->placeholders = array();
		$this->to = array();
		switch ($type)
		{
			
			/*
			 * Wenn auf Wendy ein Fehler auftritt, wird automatisch vom
			 * ErrorController eine Fehlermail an uns gesendet.
			 */
			case self::TYPE_ERROR:
				$this->subject = 'Fehler auf Thunderball aufgetreten!!';
				$this->template = self::TYPE_ERROR;
				$this->to = array(self::ERROR_MAIL_TO);
				$user = Zend_Auth::getInstance()->getIdentity();
				if ($user)
				$this->placeholders['user'] = $user->email;
				else
				$this->placeholders['user'] = 'guest';
				$now = new DateTime();
				$this->placeholders['datetime'] = $now->format('d.m.Y H:i:s');
				break;

			case self::TYPE_NEW_PASSWORD:
				$this->subject = 'Ihr neues Passwort für Thunderball Project Payment';
				$this->template = self::TYPE_NEW_PASSWORD;
				break;
			
		}
	}

	public function addPlaceholder($key, $value)
	{
		$this->placeholders[$key] = $value;
	}

	public function addTo($address)
	{
		$this->to[] = $address;
	}

	public function send()
	{
		//$tr = new Zend_Mail_Transport_Smtp('localhost');
		//Zend_Mail::setDefaultTransport($tr);

		$html = new Zend_View();
		$html->setScriptPath(APPLICATION_PATH . '/../library/Thunderball/Mailer/Templates/');

		foreach ($this->placeholders as $key => $value)
		$html->assign($key, str_replace("\n", '<br />', $value));

		$mail = new Zend_Mail('utf-8');

		$bodyText = $html->render($this->template);

		foreach ($this->to as $address)
		$mail->addTo($address);
			
		$mail->setSubject($this->subject);
		$mail->setFrom($this->from, $this->fromName);
		$mail->setBodyHtml($bodyText);
		$mail->send();
	}
}
?>