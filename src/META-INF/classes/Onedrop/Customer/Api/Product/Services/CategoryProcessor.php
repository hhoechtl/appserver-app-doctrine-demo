<?php

namespace Onedrop\Customer\Api\Product\Services;

use Onedrop\Customer\App\Common\Entities\Category\Category;

/**
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 *
 * @Stateless
 */
class CategoryProcessor extends AbstractProcessor // implements SampleProcessorInterface
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
     * Example method that should be invoked after constructor.
     *
     * @return void
     * @PreDestroy
     */
    public function destroy()
    {
        $this->getInitialContext()->getSystemLogger()->info(
            sprintf('%s has successfully been invoked by @PreDestroy annotation', __METHOD__)
        );
    }

    /**
     * Loads and returns the entity with the ID passed as parameter.
     *
     * @param integer $id The ID of the entity to load
     *
     * @return object The entity
     */
    public function load($id)
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->find('Onedrop\Customer\App\Common\Entities\Category\Category', $id);
    }

    /**
     * Persists the passed entity.
     *
     * @param Category $entity The entity to persist
     * @return Category The persisted entity
     */
    public function persist(Category $entity)
    {
        // load the entity manager
        $entityManager = $this->getEntityManager();
        // check if a detached entity has been passed
        if ($entity->getId()) {
            $merged = $entityManager->merge($entity);
            $entityManager->persist($merged);
        } else {
            $entityManager->persist($entity);
        }
        // flush the entity manager
        $entityManager->flush();
        // and return the entity itself
        return $entity;
    }

    /**
     * Deletes the entity with the passed ID.
     *
     * @param integer $id The ID of the entity to delete
     *
     * @return array An array with all existing entities
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entityManager->merge($this->load($id)));
        $entityManager->flush();
        return $this->findAll();
    }

    /**
     * Returns an array with all existing entities.
     *
     * @return array An array with all existing entities
     */
    public function findAll()
    {
        // load all entities
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('Onedrop\Customer\App\Common\Entities\Category\Category');
        return $repository->findAll();
    }
}
