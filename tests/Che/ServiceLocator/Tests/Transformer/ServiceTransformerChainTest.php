<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Che\ServiceLocator\Tests\Transformer;

use Che\ServiceLocator\Transformer\ServiceTransformerChain;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Test for ServiceTransformerChain
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class ServiceTransformerChainTest extends TestCase
{
    /**
     * @test __invoke call transformers in direct order
     */
    public function transformerChainOrder()
    {
        $chain = new ServiceTransformerChain();
        $chain->pushTransformer($this->createTransformer('foo', 'bar'));
        $chain->pushTransformer($this->createTransformer('bar', 'baz'));

        $this->assertEquals('baz', $chain('foo'));
    }

    /**
     * @test popTransformer remove last transformer from chain
     */
    public function pushPopTransformers()
    {
        $chain = new ServiceTransformerChain();
        $chain->pushTransformer($this->createTransformer('foo', 'bar'));
        $chain->pushTransformer(function () {
            $this->fail('Transformer should not be called');
        });
        $chain->popTransformer();

        $this->assertEquals('bar', $chain('foo'));
    }

    /**
     * @test __construct push transformers
     */
    public function constructPushTransformers()
    {
        $chain = new ServiceTransformerChain([
            $this->createTransformer('foo', 'bar'),
            $this->createTransformer('bar', 'baz')
        ]);

        $this->assertEquals('baz', $chain('foo'));
    }

    /**
     * @param mixed $input
     * @param mixed $output
     *
     * @return callable
     */
    private function createTransformer($input, $output)
    {
        return function ($value) use ($input, $output) {
            $this->assertEquals($input, $value);

            return $output;
        };
    }
}
