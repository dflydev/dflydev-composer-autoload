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
 * ClassLoader Set Getter
 *
 * Mimics the data methods from `Composer\Autoload\ClassLoader`.
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
interface ClassLoaderReaderInterface
{
    /**
     * Get namespace to directory mapping
     *
     * @return array
     */
    public function getPrefixes();

    /**
     * Get list of fallback directories
     *
     * @return array
     */
    public function getFallbackDirs();

    /**
     * Get class mapping
     *
     * @return array
     */
    public function getClassMap();

    /**
     * Find the file for a specific class
     *
     * @param string $class
     *
     * @return string|null
     */
    public function findFile($class);
}
