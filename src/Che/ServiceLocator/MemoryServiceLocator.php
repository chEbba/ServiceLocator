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
 * Simple array based service locator
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class MemoryServiceLocator implements ServiceLocator
{
    private $services = [];

    /**
     * @param array $services An array of [name => service]
     */
    public function __construct(array $services = [])
    {
        foreach ($services as $name => $service) {
            $this->setService($name, $service);
        }
    }

    /**
     * Set service object
     *
     * @param string $name    Service name
     * @param object $service Service instance
     *
     * @return $this Provides the fluent interface
     */
    public function setService($name, $service)
    {
        if (!is_object($service)) {
            throw new \InvalidArgumentException(sprintf('Service has "%s" type, object expected', gettype($service)));
        }

        $this->services[$name] = $service;

        return $this;
    }

    /**
     * @param string $name Service name
     *
     * @return $this Provides the fluent interface
     */
    public function removeService($name)
    {
        if (!isset($this->services[$name])) {
             return $this;
        }

        unset($this->services[$name]);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getService($name)
    {
        return isset($this->services[$name]) ? $this->services[$name] : null;
    }
}
