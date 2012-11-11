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
 * ClassLoader Reader Test
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class ClassLoaderReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getters
     */
    public function testGetters()
    {
        $classLoader = $this->getMockBuilder('Composer\Autoload\ClassLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $classLoader
            ->expects($this->once())
            ->method('getPrefixes')
            ->with()
            ->will($this->returnValue(array('A\B\C' => array('src/a/b/c'))));

        $classLoader
            ->expects($this->once())
            ->method('getFallbackDirs')
            ->with()
            ->will($this->returnValue(array('a', 'b', 'c')));

        $classLoader
            ->expects($this->once())
            ->method('getClassMap')
            ->with()
            ->will($this->returnValue(array('A\B\C' => 'src/a/b/c')));

        $classLoaderReader = new ClassLoaderReader($classLoader);

        $this->assertEquals(array('A\B\C' => array('src/a/b/c')), $classLoaderReader->getPrefixes());
        $this->assertEquals(array('a', 'b', 'c'), $classLoaderReader->getFallbackDirs());
        $this->assertEquals(array('A\B\C' => 'src/a/b/c'), $classLoaderReader->getClassMap());
    }

    /**
     * Test find files
     */
    public function testFindFile()
    {
        $classLoader = $this->getMockBuilder('Composer\Autoload\ClassLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $classLoader
            ->expects($this->once())
            ->method('findFile')
            ->with('A\B\C')
            ->will($this->returnValue('src/a/b/c'));

        $classLoaderReader = new ClassLoaderReader($classLoader);

        $this->assertEquals('src/a/b/c', $classLoaderReader->findFile('A\B\C'));
    }
}
