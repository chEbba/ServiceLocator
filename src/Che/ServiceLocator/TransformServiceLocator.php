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
 * Service locator with name and service transformation.
 * Before using $internalLocator service name is transformed with $nameTransformer.
 * After service loading it is transformed with $serviceTransformer.
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class TransformServiceLocator implements ServiceLocator
{
    private $nested;
    private $nameTransformer;
    private $serviceTransformer;

    /**
     * Construct new locator from nested one.
     * One of transformers must be set.
     *
     * @param ServiceLocator $nested             Actual service locator for loading
     * @param callable|null  $nameTransformer    Signature: string|null function (string $name);
     *                                           If null is returned, service will not be loaded
     * @param callable|null  $serviceTransformer Signature: object|null function (object|null $service);
     */
    public function __construct(ServiceLocator $nested,
                                callable $nameTransformer = null, callable $serviceTransformer = null)
    {
        $this->nested = $nested;
        if (!$nameTransformer && !$serviceTransformer) {
            throw new \InvalidArgumentException('At least one of $nameTransformer or $serviceTransformer must be set');
        }
        $this->nameTransformer = $nameTransformer;
        $this->serviceTransformer = $serviceTransformer;
    }

    /**
     * {@inheritDoc}
     */
    public function getService($name)
    {
        if ($this->nameTransformer) {
            $name = call_user_func($this->nameTransformer, $name);
        }

        $service = $name !== null ? $this->nested->getService($name) : null;

        if ($this->serviceTransformer) {
            $service = call_user_func($this->serviceTransformer, $service);
        }

        return $service;
    }
}