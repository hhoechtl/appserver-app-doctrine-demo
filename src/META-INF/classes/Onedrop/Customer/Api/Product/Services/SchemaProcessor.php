<?php

namespace Onedrop\Customer\Api\Product\Services;

use Doctrine\ORM\Tools\SchemaTool;

/**
 * A singleton session bean implementation that handles the
 * schema data for Doctrine by using Doctrine ORM itself.
 *
 * @Stateless
 */
class SchemaProcessor extends AbstractProcessor // implements SchemaProcessorInterface
{

    /**
     * Example method that should be invoked after constructor.
     *
     * @return void
     * @PostConstruct
     */
    public function initialize()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PostConstruct annotation', __METHOD__)
        );
    }

    /**
     * Deletes the database schema and creates it new.
     *
     * Attention: All data will be lost if this method has been invoked.
     *
     * @return void
     */
    public function createSchema()
    {
        // load the entity manager and the schema tool
        $entityManager = $this->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);

        // load the class definitions
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();

        // drop the schema if it already exists and create it new
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }
}
