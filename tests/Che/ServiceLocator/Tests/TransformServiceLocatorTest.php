<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Che\ServiceLocator\Tests;

use Che\ServiceLocator\TransformServiceLocator;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Test for ServiceTransformLocator
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class TransformServiceLocatorTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $nested;

    /**
     * Setup nested locator
     */
    protected function setUp()
    {
        $this->nested = $this->getMock('Che\ServiceLocator\ServiceLocator');
    }

    /**
     * @test __construct with no transformers throws exception
     */
    public function transformerConstruction()
    {
        try {
            new TransformServiceLocator($this->nested);
        } catch (\InvalidArgumentException $e) {
            return;
        }

        $this->fail('Exception was not thrown');
    }

    /**
     * @test getService transforms service name
     */
    public function nameOnlyTransformer()
    {
        $locator = new TransformServiceLocator($this->nested, function ($name) {
            $this->assertEquals('service_name', $name);

            return 'converted_name';
        });

        $service = new \stdClass();
        $this->nested
            ->expects($this->once())
            ->method('getService')
            ->with('converted_name')
            ->will($this->returnValue($service))
        ;

        $this->assertSame($service, $locator->getService('service_name'));
    }

    /**
     * @test getService transforms object
     */
    public function objectOnlyTransformer()
    {
        $service1 = new \stdClass();
        $service2 = new \stdClass();

        $locator = new TransformServiceLocator($this->nested, null, function ($service) use ($service1, $service2) {
            $this->assertSame($service1, $service);

            return $service2;
        });

        $this->nested
            ->expects($this->once())
            ->method('getService')
            ->with('service_name')
            ->will($this->returnValue($service1))
        ;

        $this->assertSame($service2, $locator->getService('service_name'));
    }

    /**
     * @test if name transformer returns null nested loading is not used
     */
    public function nullNameTransformer()
    {
        $locator = new TransformServiceLocator($this->nested, function ($name) {
            return null;
        });

        $this->nested
            ->expects($this->never())
            ->method('getService')
        ;

        $this->assertNull($locator->getService('service_name'));
    }

    /**
     * @test if name transformer returns null it is still passed to service transformer
     */
    public function nullNameServiceTransformer()
    {
        $service1 = new \stdClass();

        $locator = new TransformServiceLocator($this->nested,
            function () {
                return null;
            },
            function ($service) use ($service1) {
                $this->assertNull($service);

                return $service1;
            }
        );

        $this->nested
            ->expects($this->never())
            ->method('getService')
        ;

        $this->assertSame($service1, $locator->getService('service_name'));
    }
}
