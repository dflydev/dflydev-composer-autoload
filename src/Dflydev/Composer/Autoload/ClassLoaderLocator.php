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
     * @var \Composer\Autoload\ClassLoader[]
     */
    private static $classLoaders = null;

    /**
     * @var ClassLoaderLocator
     */
    private static $classLoaderLocator = null;

    /**
     * Get the first ClassLoader registered
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public function getFirstClassLoader()
    {
        $classLoaders = static::getClassLoaders();

        if (!count($classLoaders)) {
            return null;
        }

        return $classLoaders[0];
    }

    /**
     * Get the last ClassLoader registered
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public function getLastClassLoader()
    {
        $classLoaders = static::getClassLoaders();

        if (!count($classLoaders)) {
            return null;
        }

        return $classLoaders[count($classLoaders)-1];
    }

    /**
     * Locate all Composer Autoload ClassLoader instances
     *
     * @return \Composer\Autoload\ClassLoader[]
     */
    public function getClassLoaders()
    {
        if (null !== static::$classLoaders) {
            return static::$classLoaders;
        }

        static::$classLoaders = array();

        foreach (spl_autoload_functions() as $function) {
            if (is_array($function) && count($function[0]) > 0 && is_object($function[0]) && 'Composer\Autoload\ClassLoader' === get_class($function[0])) {
                static::$classLoaders[] = $function[0];
            }
        }

        return static::$classLoaders;
    }

    /**
     * Get a ClassLoader Reader
     *
     * If multiple ClassLoaders or no ClassLoaders are registered a Composite
     * ClassLoader Reader will be returned.
     *
     * @return ClassLoaderReaderInterface
     */
    public function getReader()
    {
        $classLoaders = static::getClassLoaders();

        return 1 === count($classLoaders)
            ? new ClassLoaderReader($classLoaders[0])
            : new CompositeClassLoaderReader($classLoaders);
    }

    /**
     * Get the first ClassLoader Reader
     *
     * @return ClassLoaderReader
     */
    public function getFirstReader()
    {
        $classLoader = $this->getFirstClassLoader();

        if ($classLoader) {
            return new ClassLoaderReader($classLoader);
        }

        return new CompositeClassLoaderReader;
    }

    /**
     * Get the last ClassLoader Reader
     *
     * @return ClassLoaderReader
     */
    public function getLastReader()
    {
        $classLoader = $this->getLastClassLoader();

        if ($classLoader) {
            return new ClassLoaderReader($classLoader);
        }

        return new CompositeClassLoaderReader;
    }

    /**
     * Get ClassLoader Readers for each registered ClassLoader
     *
     * @return ClassLoaderReaderInterface[]
     */
    public function getReaders()
    {
        $readers = array();
        foreach (static::getClassLoaders() as $classLoader) {
            $readers[] = new ClassLoaderReader($classLoader);
        }

        return $readers;
    }

    /**
     * Initialize static instance
     */
    public static function init()
    {
        if (null !== static::$classLoaders) {
            return;
        }

        if (null !== static::$classLoaderLocator) {
            return;
        }

        static::$classLoaderLocator = new static;
        static::$classLoaderLocator->getClassLoaders();
    }

    /**
     * Reset the static instance
     *
     * This effectively clears the located ClassLoader instances.
     */
    public static function reset()
    {
        static::$classLoaders = null;
    }

    /**
     * Set the class loaders
     *
     * This is here primarily for testing purposes.
     *
     * @param \Composer\Autoload\ClassLoader[] $classLoaders
     */
    public static function set(array $classLoaders)
    {
        static::$classLoaders = $classLoaders;
    }
}
