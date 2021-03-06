<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Che\ServiceLocator;

/**
 * Dynamic Service Locator interface
 * 
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */ 
interface ServiceLocator 
{
    /**
     * Get service by name
     *
     * @param string $name Service name
     *
     * @return object|null Service for this name, or null if service does not exist
     */
    public function getService($name);
}
