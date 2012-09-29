<?php

/*
 * This file is a part of dflydev/composer-autoload
 *
 * (c) Dragonfly Development Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dflydev\Composer\Autoload;

class ClassLoaderLocator
{
    /**
     * @var ClassLoader
     */
    private $classLoader = false;

    /**
     * Locate registered Composer ClassLoader if it is registered
     *
     * @return ClassLoader
     */
    public function locate()
    {
        if (false !== $this->classLoader)
        {
            return $this->classLoader;
        }

        $functions = spl_autoload_functions();
        if (false === $functions)
        {
            return $this->classLoader = null;
        }

        foreach ($functions as $function) {
            if (is_array($function) && count($function[0]) > 0 && is_object($function[0]) && 'Composer\Autoload\ClassLoader' === get_class($function[0])) {
                return $this->classLoader = $function[0];
            }
        }

        return $this->classLoader = null;
    }
}
