<?php
namespace App\Application\Container;

use Doctrine\Common\ClassLoader,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ODM\MongoDB\Configuration,
    Doctrine\ODM\MongoDB\Mapping\Annotations,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver,
    Doctrine\MongoDB\Connection,
    Doctrine\ODM\MongoDB\DocumentManager;

require_once APPLICATION_PATH . '/../library/Doctrine/ODM/MongoDB/Mapping/Annotations/DoctrineAnnotations.php';

class DoctrineContainer
{
    /**
    * @var Connection
    */
    private $connection;

    /**
    * @var DocumentManager
    */
    private $documentManager;

    /**
    * @var Configuration
    */
    private $configuration;

    public function __construct(array $config = array())
    {
        $config = $this->prepareConfiguration($config);

        $configuration = new Configuration;

        $configuration->setProxyDir($config['proxy']['dir']);
        $configuration->setProxyNamespace($config['proxy']['namespace']);

        $configuration->setHydratorDir($config['hydrator']['dir']);
        $configuration->setHydratorNamespace($config['hydrator']['namespace']);

        $reader = new AnnotationReader;
        $reader->setDefaultAnnotationNamespace('Doctrine\ODM\MongoDB\Mapping\Annotations\\');
        $configuration->setMetadataDriverImpl(new AnnotationDriver($reader, $config['mappingDirs']));

        $configuration->setDefaultDB($config['defaultDb']);

        $this->configuration = $configuration;
    }

    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new Connection();
        }
        return $this->connection;
    }

    public function getDocumentManager()
    {
        if (null === $this->documentManager) {
            $this->documentManager = DocumentManager::create(
                    $this->getConnection(),
                    $this->configuration
                    );
        }
        return $this->documentManager;
    }

    private function prepareConfiguration(array $config = array())
    {
        $defaultConfiguration = array(
            'mappingDirs' => array(),
            'proxy' => array(
                'dir' => \APPLICATION_PATH . '/../cache/Proxy',
                'namespace' => 'Proxy'
            ),
            'hydrator' => array(
                'dir' => \APPLICATION_PATH . '/../cache/Hydrator',
                'namespace' => 'Hydrator'
            )
        );

        return \array_replace_recursive($defaultConfiguration, $config);
    }
}
