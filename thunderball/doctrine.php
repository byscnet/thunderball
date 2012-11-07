<?php
// doctrine.php - Put in your application root

set_include_path('/Users/christiankeck/Downloads/DoctrineORM-2.3.0/');
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\DBAL\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\ORM\Configuration;

/*
$config = new Configuration;
$cache = new \Doctrine\Common\Cache\ArrayCache;
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir(APPLICATION_PATH . '/proxies');
		$config->setProxyNamespace('application\proxies');
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(realpath($doctrineConfig['path']['models'])));
		$config->setAutoGenerateProxyClasses(false);

$connectionOptions = array(
            'driver'    => $doctrineConfig['conn']['driv'],
            'user'      => $doctrineConfig['conn']['user'],
            'password'  => $doctrineConfig['conn']['pass'],
            'dbname'    => $doctrineConfig['conn']['dbname'],
            'host'      => $doctrineConfig['conn']['host'],
			'charset' 	=> 'UTF8',
			'collate'	=> 'utf8_general_ci',);
$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
*/







$helperSet = new HelperSet(array(
    'db' => new ConnectionHelper($em->getConnection()),
    'em' => new EntityManagerHelper($em)
));

ConsoleRunner::run($helperSet);
