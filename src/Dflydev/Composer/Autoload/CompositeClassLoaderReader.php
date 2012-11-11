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
 * Composite ClassLoader Reader
 *
 * Useful for information gathering for multiple ClassLoader's.
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class CompositeClassLoaderReader implements ClassLoaderReaderInterface
{
    /**
     * Class Loaders
     *
     * @var \Composer\Autoload\ClassLoader[]
     */
    protected $classLoaders;

    /**
     * Constructor
     *
     * @param \Composer\Autoload\ClassLoader[] $classLoaders
     */
    public function __construct(array $classLoaders = array())
    {
        $this->classLoaders = $classLoaders;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefixes()
    {
        $prefixes = array();
        foreach ($this->classLoaders as $classLoader) {
            foreach ($classLoader->getPrefixes() as $key => $value) {
                if (!isset($prefixes[$key])) {
                    $prefixes[$key] = array();
                }

                $prefixes[$key] = array_merge($prefixes[$key], $value);
            }
        }

        foreach ($prefixes as $key => $value) {
            $prefixes[$key] = array_values(array_unique($value));
        }

        return $prefixes;
    }

    /**
     * {@inheritdoc}
     */
    public function getFallbackDirs()
    {
        $fallbackDirs = array();
        foreach ($this->classLoaders as $classLoader) {
            $fallbackDirs = array_merge($fallbackDirs, $classLoader->getFallbackDirs());
        }

        return array_values(array_unique($fallbackDirs));
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMap()
    {
        $classMap = array();
        foreach ($this->classLoaders as $classLoader) {
            foreach ($classLoader->getClassMap() as $key => $value) {
                if (!isset($classMap[$key])) {
                    $classMap[$key] = $value;
                }
            }
        }

        return $classMap;
    }

    /**
     * {@inheritdoc}
     */
    public function findFile($class)
    {
        foreach ($this->classLoaders as $classLoader) {
            if (null !== $file = $classLoader->findFile($class)) {
                return $file;
            }
        }
    }
}
