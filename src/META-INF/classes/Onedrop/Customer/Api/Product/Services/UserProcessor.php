<?php

namespace Onedrop\Customer\Api\Product\Services;

use AppserverIo\Apps\Example\Exceptions\LoginException;
use AppserverIo\Apps\Example\Exceptions\FoundInvalidUserException;

/**
 * A singleton session bean implementation that handles the
 * data by using Doctrine ORM.
 *
 * @Stateful
 */
class UserProcessor extends AbstractProcessor // implements UserProcessorInterface
{

    /**
     * The default username.
     *
     * @var string
     */
    const DEFAULT_USERNAME = 'appserver';

    /**
     * The user, logged into the system.
     *
     * @var \Onedrop\Customer\App\Common\Entities\Customer\Customer $user
     */
    protected $user;

    /**
     * The DIC provider instance.
     *
     * @var \AppserverIo\Appserver\DependencyInjectionContainer\Interfaces\ProviderInterface $provider
     * @Resource(name="ProviderInterface")
     */
    protected $provider;

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
     * Validates the passed username agains the password.
     *
     * @param string $username The username to login with
     * @param string $password The password that should match the username
     *
     * @return void
     * @throws \Onedrop\Customer\Api\Product\Exceptions\LoginException Is thrown if the user with the passed username doesn't exist or match the password
     */
    public function login($username, $password)
    {

        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('Onedrop\Customer\App\Common\Entities\User');

        // try to load the user
        $user = $repository->findOneBy(array('username' => $username));
        if ($user == null) {
            throw new LoginException('Username or Password doesn\'t match');
        }

        // try to match the passwords
        if ($user->getPassword() !== md5($password)) {
            throw new LoginException('Username or Password doesn\'t match');
        }

        // store the user in the session
        $this->user = $user;
    }


    /**
     * Checks if a default user exists, if not it creates one and returns the entity.
     *
     * @return \Onedrop\Customer\App\Common\Entities\User The default user instance
     */
    public function checkForDefaultCredentials()
    {
        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('Onedrop\Customer\App\Common\Entities\User');

        // try to load the default credentials
        $defaultUser = $repository->findOneBy(array('username' => UserProcessor::DEFAULT_USERNAME));
        if ($defaultUser == null) {
            $defaultUser = $this->createDefaultCredentials();
        }

        // return the default credentials
        return $defaultUser;
    }

    /**
     * Creates the default credentials to login.
     *
     * @return \Onedrop\Customer\App\Common\Entities\User The default user instance
     */
    public function createDefaultCredentials()
    {

        try {
            // load the entity manager
            $entityManager = $this->getEntityManager();

            // set user data and save it
            $user = $this->provider->newInstance('\Onedrop\Customer\App\Common\Entities\User');
            $user->setEmail('info@appserver.io');
            $user->setUsername(UserProcessor::DEFAULT_USERNAME);
            $user->setUserLocale('en_US');
            $user->setPassword(md5('appserver.i0'));
            $user->setEnabled(true);
            $user->setRate(1000);
            $user->setContractedHours(160);
            $user->setLdapSynced(false);
            $user->setSyncedAt(time());

            // persist the user
            $entityManager->persist($user);

            // flush the entity manager
            $entityManager->flush();

            // create the created user instance
            return $user;

        } catch (\Exception $e) {
            // log the exception
            $this->getInitialContext()->getSystemLogger()->error($e->__toString());
        }
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
		$repository = $entityManager->getRepository('Onedrop\Customer\App\Common\Entities\Customer\Customer');
		return $repository->findAll();
	}
}
