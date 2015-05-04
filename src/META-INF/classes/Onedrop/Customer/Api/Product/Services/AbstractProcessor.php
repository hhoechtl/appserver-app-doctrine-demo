<?php

namespace Onedrop\Customer\Api\Product\Services;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;
use AppserverIo\Apps\Example\Entities\User;
use AppserverIo\Psr\Application\ApplicationInterface;

/**
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 */
class AbstractProcessor
{

    /**
     * Datasource name to use.
     *
     * @var string
     */
    protected $datasourceName = 'customer.database';

    /**
     * Relative path to the folder with the database entries.
     *
     * @var string
     */
    protected $pathToEntities = 'vendor/onedrop-customer/common/src/Onedrop/Customer/App/Common/Entities';

    /**
     * The array with the Doctrine connection parameters.
     *
     * @var array
     */
    protected $connectionParameters;

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * Injects the application into all extending instances.
     *
     * ATTENTION: Will only be used if you activate it in the epb.xml file!
     *
     * @param \AppserverIo\Psr\Application\ApplicationInterface $application The application instance
     *
     * @return void
     */
    public function injectApplication(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Initializes the database connection parameters necessary
     * to connect to the database using Doctrine.
     *
     * @return void
     * @PostConstruct
     */
    public function initConnectionParameters()
    {

        // iterate over the found database sources
        foreach ($this->getDatasources() as $datasourceNode) {
            // if the datasource is related to the session bean
            if ($datasourceNode->getName() == $this->getDatasourceName()) {
                // initialize the database node
                $databaseNode = $datasourceNode->getDatabase();

                // initialize the connection parameters
                $connectionParameters = array(
                    'driver'   => $databaseNode->getDriver()->getNodeValue()->__toString(),
                    'user'     => $databaseNode->getUser()->getNodeValue()->__toString(),
                    'password' => $databaseNode->getPassword()->getNodeValue()->__toString(),
					'dbname' => $databaseNode->getDatabaseName()->getNodeValue()->__toString()
                );

                // set the connection parameters
                $this->setConnectionParameters($connectionParameters);
            }
        }
    }

    /**
     * Return's the path to the doctrine entities.
     *
     * @return string The path to the doctrine entities
     */
    public function getPathToEntities()
    {
        return $this->pathToEntities;
    }

    /**
     * Return's the datasource name to use.
     *
     * @return string The datasource name
     */
    public function getDatasourceName()
    {
        return $this->datasourceName;
    }

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * The database connection parameters used to connect to Doctrine.
     *
     * @param array $connectionParameters The Doctrine database connection parameters
     *
     * @return void
     *
     */
    public function setConnectionParameters(array $connectionParameters = array())
    {
        $this->connectionParameters = $connectionParameters;
    }

    /**
     * Returns the database connection parameters used to connect to Doctrine.
     *
     * @return array The Doctrine database connection parameters
     */
    public function getConnectionParameters()
    {
        return $this->connectionParameters;
    }

    /**
     * Returns the initial context instance.
     *
     * @return \AppserverIo\Appserver\Core\InitialContext The initial context instance
     */
    public function getInitialContext()
    {
        return $this->getApplication()->getInitialContext();
    }

    /**
     * Return's the system configuration
     *
	 * @return \AppserverIo\Appserver\Core\Interfaces\SystemConfigurationInterface The system configuration
     */
    public function getSystemConfiguration()
    {
        return $this->getInitialContext()->getSystemConfiguration();
    }

    /**
     * Return's the array with the datasources.
     *
	 * @return \AppserverIo\Appserver\Core\Api\Node\DatasourceNode[] The array with the datasources
     */
    public function getDatasources()
    {
        return $this->getSystemConfiguration()->getDatasources();
    }

    /**
     * Return's the initialized Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManager The initialized Doctrine entity manager
     */
    public function getEntityManager()
    {

        // prepare the path to the entities
        $absolutePaths = array();
        if ($relativePaths = $this->getPathToEntities()) {
            foreach (explode(PATH_SEPARATOR, $relativePaths) as $relativePath) {
                $absolutePaths[] = $this->getApplication()->getWebappPath() . DIRECTORY_SEPARATOR . $relativePath;
            }
        }

        // create the database configuration and initialize the entity manager
        $metadataConfiguration = Setup::createAnnotationMetadataConfiguration($absolutePaths, true);
        return EntityManager::create($this->getConnectionParameters(), $metadataConfiguration);
    }
}
