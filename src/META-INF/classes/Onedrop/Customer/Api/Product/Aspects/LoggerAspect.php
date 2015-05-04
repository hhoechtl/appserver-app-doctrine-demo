<?php

namespace Onedrop\Customer\Api\Product\Aspects;

use AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface;

/**
 * Aspect which allows for logging within the app's classes.
 *
 * @Aspect
 */
class LoggerAspect
{

    /**
     * Pointcut which targets all index actions for all action classes
     *
     * @return null
     *
     * @Pointcut("call(\Onedrop\Customer\Api\Product\Actions\*->indexAction())")
     */
    public function allIndexActions()
    {
    }

    /**
     * Advice used to log the call to any advised method
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Before("pointcut(allIndexActions())")
     */
    public function logInfoAdvice(MethodInvocationInterface $methodInvocation)
    {
        $methodInvocation->getContext()
            ->getServletRequest()
            ->getContext()
            ->getInitialContext()
            ->getSystemLogger()
            ->info(sprintf(
                'The method %s::%s is about to be called',
                $methodInvocation->getStructureName(),
                $methodInvocation->getName()
            ));
    }
}
