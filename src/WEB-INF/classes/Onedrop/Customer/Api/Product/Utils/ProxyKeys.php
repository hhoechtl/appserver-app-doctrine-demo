<?php

namespace Onedrop\Customer\Api\Product\Utils;

/**
 * Context keys that are used to store data in a application context.
 */
class ProxyKeys
{

    /**
     * Private to constructor to avoid instancing this class.
     */
    private function __construct()
    {
    }

    /**
     * The naming directory key for the 'AppserverIo\Apps\Example\Services\SampleProcessor' session bean.
     *
     * @return string
     */
    const SAMPLE_PROCESSOR = 'SampleProcessor'; // 'php:global/example/SampleProcessor/remote' for remote access

    /**
     * The naming directory key for the 'AppserverIo\Apps\Example\Services\UserProcessor' session bean.
     *
     * @return string
     */
    const USER_PROCESSOR = 'UserProcessor'; // 'php:global/example/UserProcessor/remote' for remote access
}
