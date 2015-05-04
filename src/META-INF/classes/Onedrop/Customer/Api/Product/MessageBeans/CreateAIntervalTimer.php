<?php

namespace Onedrop\Customer\Api\Product\MessageBeans;

use AppserverIo\Lang\String;
use AppserverIo\Psr\Pms\MessageInterface;
use AppserverIo\Messaging\AbstractMessageListener;
use AppserverIo\Psr\EnterpriseBeans\TimerInterface;

/**
 * This is the implementation of a message bean that simply creates and starts an interval timer.
 *
 * @MessageDriven
 */
class CreateAIntervalTimer extends AbstractMessageListener
{

    /**
     * Will be invoked when a new message for this message bean will be available.
     *
     * @param \AppserverIo\Psr\Pms\MessageInterface $message   A message this message bean is listen for
     * @param string                                $sessionId The session ID
     *
     * @return void
     * @see \AppserverIo\Psr\Pms\MessageListenerInterface::onMessage()
     */
    public function onMessage(MessageInterface $message, $sessionId)
    {


        // load the timer service registry
        $timerServiceRegistry = $this->getApplication()->search('TimerServiceContextInterface');

        // load the timer service for this class -> that allows us to invoke the
        // CreateAIntervalTimer::timeout() every 10 secondes
        $timerService = $timerServiceRegistry->lookup('CreateAIntervalTimer');

        // our single action timer should be invoked 10 seconds from now, every 10 seconds
        $initialExpiration = 10000000;
        $intervalDuration = 10000000;

        // we create the interval timer
        $timerService->createIntervalTimer($initialExpiration, $intervalDuration, new String($message->getMessage()));

        // log a message that the single action timer has been successfully created
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf(
                'Successfully created a interval timer starting in %d seconds and a interval of %d seconds',
                $initialExpiration / 1000000,
                $intervalDuration / 1000000
            )
        );

        // update the message monitor for this message
        $this->updateMonitor($message);
    }

    /**
     * Invoked by the container upon timer expiration.
     *
     * @param \AppserverIo\Psr\EnterpriseBeans\TimerInterface $timer Timer whose expiration caused this notification
     *
     * @return void
     * @Timeout
     **/
    public function timeout(TimerInterface $timer)
    {

        // log a message with the directory name we found
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf(
                '%s has successfully been invoked by @Timeout annotation to watch directory %s',
                __METHOD__,
                $timer->getInfo()
            )
        );
    }
}
