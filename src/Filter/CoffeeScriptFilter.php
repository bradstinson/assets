<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2013 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assets\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;


/**
 * Filters assets through CoffeeScript.
 *
 * @link http://code.google.com/p/cssmin
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class CoffeeScriptFilter implements FilterInterface
{
    private $filters;
    private $plugins;

    public function __construct()
    {
        $this->filters = array();
        $this->plugins = array();
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }

    public function setFilter($name, $value)
    {
        $this->filters[$name] = $value;
    }

    public function setPlugins(array $plugins)
    {
        $this->plugins = $plugins;
    }

    public function setPlugin($name, $value)
    {
        $this->plugins[$name] = $value;
    }

    public function filterLoad(AssetInterface $asset)
    {
        $asset->setContent(\CoffeeScript\Compiler::compile($asset->getContent()));
    }

    public function filterDump(AssetInterface $asset)
    {
    }
}
