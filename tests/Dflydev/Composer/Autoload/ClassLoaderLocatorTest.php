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
 * Class Loader Locator Test
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class ClassLoaderLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        ClassLoaderLocator::set(array());

        $classLoaderLocator = new ClassLoaderLocator;

        $this->assertNull($classLoaderLocator->getFirstClassLoader());
        $this->assertNull($classLoaderLocator->getLastClassLoader());
        $this->assertCount(0, $classLoaderLocator->getClassLoaders());

        $this->assertInstanceOf('Dflydev\Composer\Autoload\CompositeClassLoaderReader', $classLoaderLocator->getFirstReader());
        $this->assertInstanceOf('Dflydev\Composer\Autoload\CompositeClassLoaderReader', $classLoaderLocator->getLastReader());
    }

    public function testExactlyOne()
    {
        $classLoader = $this->getMockBuilder('Composer\Autoload\ClassLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $classLoader
            ->expects($this->any())
            ->method('getPrefixes')
            ->will($this->returnValue(array('A' => array('src/a'))));

        ClassLoaderLocator::set(array(
            $classLoader,
        ));

        $classLoaderLocator = new ClassLoaderLocator;

        $firstClassLoader = $classLoaderLocator->getFirstClassLoader();
        $lastClassLoader = $classLoaderLocator->getLastClassLoader();
        $allClassLoaders = $classLoaderLocator->getClassLoaders();

        $this->assertEquals(array('A' => array('src/a')), $firstClassLoader->getPrefixes());
        $this->assertEquals(array('A' => array('src/a')), $lastClassLoader->getPrefixes());
        $this->assertCount(1, $allClassLoaders);

        $reader = $classLoaderLocator->getReader();

        $this->assertInstanceOf('Dflydev\Composer\Autoload\ClassLoaderReader', $reader);

        $prefixes = $reader->getPrefixes();

        $this->assertEquals('src/a', $prefixes['A'][0]);

        $firstReader = $classLoaderLocator->getFirstReader();
        $lastReader = $classLoaderLocator->getLastReader();

        $this->assertEquals(array('A' => array('src/a')), $firstReader->getPrefixes());
        $this->assertEquals(array('A' => array('src/a')), $lastReader->getPrefixes());

        $readers = $classLoaderLocator->getReaders();

        $this->assertCount(1, $readers);
        $this->assertEquals(array('A' => array('src/a')), $readers[0]->getPrefixes());
    }

    public function testTwoOrMore()
    {
        $classLoader0 = $this->getMockBuilder('Composer\Autoload\ClassLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $classLoader0
            ->expects($this->any())
            ->method('getPrefixes')
            ->will($this->returnValue(array('A' => array('src/a'))));

        $classLoader1 = $this->getMockBuilder('Composer\Autoload\ClassLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $classLoader1
            ->expects($this->any())
            ->method('getPrefixes')
            ->will($this->returnValue(array('B' => array('src/b'))));

        $classLoader2 = $this->getMockBuilder('Composer\Autoload\ClassLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $classLoader2
            ->expects($this->any())
            ->method('getPrefixes')
            ->will($this->returnValue(array('C' => array('src/c'))));

        ClassLoaderLocator::set(array(
            $classLoader0,
            $classLoader1,
            $classLoader2,
        ));

        $classLoaderLocator = new ClassLoaderLocator;

        $firstClassLoader = $classLoaderLocator->getFirstClassLoader();
        $lastClassLoader = $classLoaderLocator->getLastClassLoader();
        $allClassLoaders = $classLoaderLocator->getClassLoaders();

        $this->assertEquals(array('A' => array('src/a')), $firstClassLoader->getPrefixes());
        $this->assertEquals(array('C' => array('src/c')), $lastClassLoader->getPrefixes());
        $this->assertCount(3, $allClassLoaders);

        $reader = $classLoaderLocator->getReader();

        $this->assertInstanceOf('Dflydev\Composer\Autoload\CompositeClassLoaderReader', $reader);

        $prefixes = $reader->getPrefixes();

        $this->assertEquals('src/a', $prefixes['A'][0]);
        $this->assertEquals('src/b', $prefixes['B'][0]);
        $this->assertEquals('src/c', $prefixes['C'][0]);

        $firstReader = $classLoaderLocator->getFirstReader();
        $lastReader = $classLoaderLocator->getLastReader();

        $this->assertEquals(array('A' => array('src/a')), $firstReader->getPrefixes());
        $this->assertEquals(array('C' => array('src/c')), $lastReader->getPrefixes());

        $readers = $classLoaderLocator->getReaders();

        $this->assertCount(3, $readers);
        $this->assertEquals(array('A' => array('src/a')), $readers[0]->getPrefixes());
        $this->assertEquals(array('B' => array('src/b')), $readers[1]->getPrefixes());
        $this->assertEquals(array('C' => array('src/c')), $readers[2]->getPrefixes());
    }
}
