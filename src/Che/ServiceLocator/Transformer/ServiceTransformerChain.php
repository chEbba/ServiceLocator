<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Che\ServiceLocator\Transformer;

/**
 * Chain of service transformers implemented with queue
 * Transformers are applied in direct order (FIFO)
 * 
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */ 
class ServiceTransformerChain
{
    private $queue;

    /**
     * Constructor
     *
     * @param array $transformers
     */
    public function __construct(array $transformers = [])
    {
        $this->queue = new \SplQueue();
        foreach ($transformers as $transformer) {
            $this->pushTransformer($transformer);
        }
    }

    /**
     * Push transformer
     *
     * @param callable $transformer
     *
     * @return ServiceTransformerChain Provides the fluent interface
     */
    public function pushTransformer(callable $transformer)
    {
        $this->queue->push($transformer);

        return $this;
    }

    /**
     * Pop transformer
     *
     * @return callable|null Popped transformer
     */
    public function popTransformer()
    {
        return $this->queue->pop();
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke($value)
    {
        foreach ($this->queue as $transformer) {
            $value = $transformer($value);
        }

        return $value;
    }
}
