<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Che\ServiceLocator\Adapter;

use Che\ServiceLocator\ServiceLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ServiceLocator adapter for Symfony DependencyInjection container
 * @link http://symfony.com/doc/current/components/dependency_injection/index.html
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class SymfonyContainerLocator implements ServiceLocator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getService($name)
    {
        return $this->container->get($name, ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }
}