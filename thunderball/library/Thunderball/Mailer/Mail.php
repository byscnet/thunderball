<?php

class Thunderball_Mailer_Mail
{
	const TYPE_ERROR = 'error.phtml';
	const TYPE_NEW_PASSWORD = 'new-password.phtml';
	
	const ERROR_MAIL_TO = 'c.keck@lucidlogic.de';

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
		$this->fromName = 'Thunderball';
		$this->placeholders = array();
		$this->to = array();
		switch ($type)
		{
			
			/*
			 * Wenn auf Wendy ein Fehler auftritt, wird automatisch vom
			 * ErrorController eine Fehlermail an uns gesendet.
			 */
			case self::TYPE_ERROR:
				$this->subject = 'Fehler auf Wendy aufgetreten!!';
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
				$this->subject = 'Neues Passwort für Thunderball';
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