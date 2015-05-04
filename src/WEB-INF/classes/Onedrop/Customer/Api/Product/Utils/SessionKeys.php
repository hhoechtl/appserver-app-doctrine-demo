<?php

namespace Onedrop\Customer\Api\Product\Utils;

/**
 * Session keys that are used to store data in a session context.
 */
class SessionKeys
{

    /**
     * Private to constructor to avoid instancing this class.
     */
    private function __construct()
    {
    }

    /**
     * The key for a 'username'.
     *
     * @return string
     */
    const USERNAME = 'username';
}
