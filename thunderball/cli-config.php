<?php

require_once 'Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->setIncludePath(realpath(APPLICATION_PATH . 'library/'));
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();

$cache = new \Doctrine\Common\Cache\ArrayCache();

$config->setMetadataCacheImpl($cache);
$config->setProxyDir(realpath('application/proxies'));

# namespaces
$config->setProxyNamespace('application\proxies');
$config->setEntityNamespaces(array('Thunderball\Domain'));


#$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(realpath(APPLICATION_PATH . 'application/models/meta/')));
#$driver = new \Doctrine\ORM\Mapping\Driver\YamlDriver(__DIR__.'/application/models/doctrine/schema/');
#$driver = new Doctrine\ORM\Mapping\Driver\XmlDriver(__DIR__."/application/models/doctrine/schema");

$driver = $config->newDefaultAnnotationDriver(__DIR__."/application/models");

$config->setMetadataDriverImpl($driver);
$config->setAutoGenerateProxyClasses(true);

$connectionOptions = array(
'driver' => 'pdo_mysql',
'host' => '127.0.0.1',
'user' => 'root',
'password' => '',
'dbname' => 'thunderball',
//'unix_socket' => '/tmp/mysql.sock'
       );

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
$em->getConnection()->setCharset('UTF8');

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
	'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
#$cli->setHelperSet($helperSet);

