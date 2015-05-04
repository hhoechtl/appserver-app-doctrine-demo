<?php

namespace Onedrop\Customer\Api\Product\Utils;

/**
 * Context keys that are used to store data in a application context.
 */
class ContextKeys
{

    /**
     * Private to constructor to avoid instancing this class.
     */
    private function __construct()
    {
    }

    /**
     * The key for a collection with error messages.
     *
     * @return string
     */
    const ERROR_MESSAGES = 'error.messages';

    /**
     * The key for a collection with entities.
     *
     * @return string
     */
    const OVERVIEW_DATA = 'overview.data';

    /**
     * The key for an entity.
     *
     * @return string
     */
    const VIEW_DATA = 'view.data';
}
