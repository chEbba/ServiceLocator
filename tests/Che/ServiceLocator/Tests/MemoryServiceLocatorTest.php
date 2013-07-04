<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Che\ServiceLocator\Tests;

use Che\ServiceLocator\MemoryServiceLocator;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Test for MemoryServiceLocator
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class MemoryServiceLocatorTest extends TestCase
{
    /**
     * @test setService register object with name
     */
    public function setRemoveServiceObject()
    {
        $locator = new MemoryServiceLocator();

        $service = new \stdClass();

        $locator->setService('foo', $service);
        $this->assertSame($service, $locator->getService('foo'));

        $locator->removeService('foo');
        $this->assertNull($locator->getService('foo'));
    }

    /**
     * @test __construct set services
     */
    public function setOnConstruct()
    {
        $service = new \stdClass();

        $locator = new MemoryServiceLocator(['foo' => $service]);

        $this->assertSame($service, $locator->getService('foo'));
    }

    /**
     * @test setService excepts only objects
     */
    public function setNonObject()
    {
        $locator = new MemoryServiceLocator();

        try {
            $locator->setService('foo', 'bar');
        } catch (\InvalidArgumentException $e) {
            return;
        }

        $this->fail('Exception was not thrown');
    }
}
