<?php

class ErrorController extends Zend_Controller_Action
{
	public function errorAction()
	{
		//$jquery = $this->view->jQuery();
		//$jquery->enable();

		$errors = $this->_getParam('error_handler');

		if (!$errors) {
			$this->view->message = 'You have reached the error page';
			return;
		}

		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Die angeforderte Seite wurde nicht gefunden.';
				break;
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Die Administratoren wurden bereits darüber informiert.';
				echo $errors->exception;
				//$this->view->info  = $errors->exception;

				break;
		}

		// Log exception, if logger available
		if ($log = $this->getLog()) {
			$log->crit($this->view->message, $errors->exception);
		}

		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true) {
			$this->view->exception = $errors->exception;
		}

		$this->view->request   = $errors->request;

		$sendEmail = true;

		// send error mail to administrator
		if ($sendEmail)
		{
			$mailer = new Thunderball_Mailer_Mail(Thunderball_Mailer_Mail::TYPE_ERROR);
			$mailer->addPlaceholder('type', $this->view->message);
			$mailer->addPlaceholder('exception', $errors->exception->getMessage());
			$mailer->addPlaceholder('trace', $errors->exception->getTraceAsString());
			$mailer->addPlaceholder('parameters', var_export($errors->request->getParams(), true));
			$mailer->send();
		}
	}

	public function getLog()
	{
		$bootstrap = $this->getInvokeArg('bootstrap');
		if (!$bootstrap->hasResource('Log')) {
			return false;
		}
		$log = $bootstrap->getResource('Log');
		return $log;
	}

}

