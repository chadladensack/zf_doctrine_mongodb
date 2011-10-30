#!/usr/bin/env php

<?php

// Define path to application directory
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
define('APPLICATION_ENV', 'development');

ini_set('memory_limit', '1024M');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// bootstrap Doctrine and the Autoloader
$application->getBootstrap()->bootstrap('Doctrine');

// Retrieve Doctrine Container resource
$container = $application->getBootstrap()->getResource('doctrine');

// Console
$cli = new \Symfony\Component\Console\Application(
    'Doctrine Command Line Interface',
    \Doctrine\Common\Version::VERSION
);

try {
    // Bootstrapping Console HelperSet
    $helperSet = array();

    $dm = $container->getDocumentManager();
    $helperSet['dm'] = new Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper($dm);

} catch (\Exception $e) {
    $cli->renderException($e, new \Symfony\Component\Console\Output\ConsoleOutput());
}

$cli->setCatchExceptions(true);
$cli->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet($helperSet));

$commands = array();
$commands[] = new \Doctrine\ODM\MongoDB\Tools\Console\Command\QueryCommand();
$commands[] = new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateDocumentsCommand();
$commands[] = new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateRepositoriesCommand();
$commands[] = new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateProxiesCommand();
$commands[] = new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateHydratorsCommand();
$commands[] = new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\CreateCommand();
$commands[] = new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\DropCommand();

$cli->addCommands($commands);
$cli->run();
