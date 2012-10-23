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

/**
 * Class Loader Locator
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class ClassLoaderLocator
{
    /**
     * @var ClassLoader
     */
    private static $classLoader = false;

    /**
     * Search registered Composer ClassLoader if it is registered
     *
     * @return ClassLoader
     */
    public static function locate()
    {
        if (false !== static::$classLoader) {
            return static::$classLoader;
        }

        foreach (spl_autoload_functions() as $function) {
            if (is_array($function) && count($function[0]) > 0 && is_object($function[0]) && 'Composer\Autoload\ClassLoader' === get_class($function[0])) {
                return static::$classLoader = $function[0];
            }
        }

        return static::$classLoader = null;
    }
}
