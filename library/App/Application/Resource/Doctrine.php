<?php

use App\Application\Container;

class App_Application_Resource_Doctrine extends \Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        $config = $this->getOptions();

        $this->registerAutoloaders($config);

        $container = new Container\DoctrineContainer($config);

        Zend_Registry::set('doctrine', $container);

        return $container;
    }

    private function registerAutoloaders(array $config = array())
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $doctrineIncludePath = isset($config['includePath'])
            ? $config['includePath'] : APPLICATION_PATH . '/../library/Doctrine';

        require_once $doctrineIncludePath . '/Common/ClassLoader.php';

        $doctrineAutoloader = new \Doctrine\Common\ClassLoader('Doctrine');
        $autoloader->pushAutoloader(array($doctrineAutoloader, 'loadClass'), 'Doctrine');
    }
}
