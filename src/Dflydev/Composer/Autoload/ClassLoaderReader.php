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

use Composer\Autoload\ClassLoader;

/**
 * ClassLoader Reader
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class ClassLoaderReader implements ClassLoaderReaderInterface
{
    /**
     * Class Loader
     *
     * @var \Composer\Autoload\ClassLoader
     */
    protected $classLoader;

    /**
     * Constructor
     *
     * @param \Composer\Autoload\ClassLoader $classLoader
     */
    public function __construct(ClassLoader $classLoader)
    {
        $this->classLoader = $classLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefixes()
    {
        return $this->classLoader->getPrefixes();
    }

    /**
     * {@inheritdoc}
     */
    public function getFallbackDirs()
    {
        return $this->classLoader->getFallbackDirs();
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMap()
    {
        return $this->classLoader->getClassMap();

    }

    /**
     * {@inheritdoc}
     */
    public function findFile($class)
    {
        return $this->classLoader->findFile($class);
    }
}
