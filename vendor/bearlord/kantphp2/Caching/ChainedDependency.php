<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <zhenqiang.zhang@hotmail.com>
 * @copyright (c) KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
namespace Kant\Caching;

/**
 * ChainedDependency represents a dependency which is composed of a list of other dependencies.
 *
 * When [[dependOnAll]] is true, if any of the dependencies has changed, this dependency is
 * considered changed; When [[dependOnAll]] is false, if one of the dependencies has NOT changed,
 * this dependency is considered NOT changed.
 *
 * For more details and usage information on Cache, see the [guide article on caching](guide:caching-overview).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ChainedDependency extends Dependency
{

    /**
     *
     * @var Dependency[] list of dependencies that this dependency is composed of.
     *      Each array element must be a dependency object.
     */
    public $dependencies = [];

    /**
     *
     * @var bool whether this dependency is depending on every dependency in [[dependencies]].
     *      Defaults to true, meaning if any of the dependencies has changed, this dependency is considered changed.
     *      When it is set false, it means if one of the dependencies has NOT changed, this dependency
     *      is considered NOT changed.
     */
    public $dependOnAll = true;

    /**
     * Evaluates the dependency by generating and saving the data related with dependency.
     * 
     * @param Cache $cache
     *            the cache component that is currently evaluating this dependency
     */
    public function evaluateDependency($cache)
    {
        foreach ($this->dependencies as $dependency) {
            $dependency->evaluateDependency($cache);
        }
    }

    /**
     * Generates the data needed to determine if dependency has been changed.
     * This method does nothing in this class.
     * 
     * @param Cache $cache
     *            the cache component that is currently evaluating this dependency
     * @return mixed the data needed to determine if dependency has been changed.
     */
    protected function generateDependencyData($cache)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function isChanged($cache)
    {
        foreach ($this->dependencies as $dependency) {
            if ($this->dependOnAll && $dependency->isChanged($cache)) {
                return true;
            } elseif (!$this->dependOnAll && ! $dependency->isChanged($cache)) {
                return false;
            }
        }
        return ! $this->dependOnAll;
    }
}
