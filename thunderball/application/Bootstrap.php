<?php
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initRegistry(){
		$registry = Zend_Registry::getInstance();
		return $registry;
	}

	protected function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Thunderball_',
            'basePath'  => dirname(__FILE__),
		));

		//$autoloader->addResourceType('serviceImpl', 'services/base/', 'Service_Base');
		$autoloader->addResourceType('common', 'common/', 'Common');

		return $autoloader;
	}

	public function _initDoctrine() {

		require_once('Doctrine/Common/ClassLoader.php');

		$doctrineConfig = $this->getOption('doctrine');

		$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
		$classLoader->setIncludePath(realpath(APPLICATION_PATH . '/../library/'));
		$classLoader->register();
		$cache = new \Doctrine\Common\Cache\ArrayCache;
		$config = new \Doctrine\ORM\Configuration();

		$config->setQueryCacheImpl($cache);
		$config->setProxyDir(APPLICATION_PATH . '/proxies');
		$config->setProxyNamespace('application\proxies');
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(realpath($doctrineConfig['path']['models'])));
		$config->setAutoGenerateProxyClasses(true);

		$connectionOptions = array(
            'driver'    => $doctrineConfig['conn']['driv'],
            'user'      => $doctrineConfig['conn']['user'],
            'password'  => $doctrineConfig['conn']['pass'],
            'dbname'    => $doctrineConfig['conn']['dbname'],
            'host'      => $doctrineConfig['conn']['host'],
			'charset' 	=> 'UTF8',
			'collate'	=> 'utf8_general_ci',);

		
		// event management für das changeLog
		$eventManager = new \Doctrine\Common\EventManager();
		//$eventManager->addEventListener(array(\Doctrine\ORM\Events::preUpdate, \Doctrine\ORM\Events::onFlush), new Wendy_Doctrine_Event_Listener_Change());
		//$eventManager->addEventListener(array(\Doctrine\ORM\Events::onFlush), new Wendy_Doctrine_Event_Listener_Change());
		//$eventManager->addEventSubscriber(new MyEventSubscriber());
		
		// entity manager
		$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $eventManager);
		$em->getEventManager()->addEventSubscriber(new MysqlSessionInit('utf8', 'utf8_unicode_ci'));
		
		// speichern in die zend registry
		$registry = Zend_Registry::getInstance();
		$registry->entityManager = $em;
		$registry->connectionOptions = $connectionOptions;

		return $em;
	}
	
	
	protected function _initAuth()
	{
		$this->bootstrap('frontController');
		$auth = Zend_Auth::getInstance();
		$acl = new Thunderball_Auth_Acl();
		$this->getResource('frontController')->registerPlugin(new Thunderball_Auth_AccessControl($auth, $acl))->setParam('auth', $auth);
		Zend_Registry::set('Zend_Acl', $acl);
	}

	protected function _initNavigation()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();

		$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
		$container = new Zend_Navigation($config);

		Zend_Registry::set('Zend_Navigation', $container);
		
		$role = 'guest';
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$role = Zend_Auth::getInstance()->getIdentity()->roleId;
		}

		$view->navigation()->setAcl(Zend_Registry::get('Zend_Acl'));
		$view->navigation()->setRole($role);
		$view->navigation()->setContainer($container);
	}

	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}

	
	protected function _initJQueryViewHelper()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	}


}

?>


