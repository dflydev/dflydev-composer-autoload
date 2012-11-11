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
 * Composite ClassLoader Reader Test
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class CompositeClassLoaderReaderTest extends \PHPUnit_Framework_TestCase
{
    protected function createMockClassLoader($prefixes = null, $fallbackDirs = null, $classMap = null, $findFileReturnValueMap = null)
    {
        if (null === $prefixes) {
            $prefixes = array();
        }

        if (null === $fallbackDirs) {
            $fallbackDirs = array();
        }

        if (null === $classMap) {
            $classMap = array();
        }

        $classLoader = $this->getMockBuilder('Composer\Autoload\ClassLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $classLoader
            ->expects($this->any())
            ->method('getPrefixes')
            ->with()
            ->will($this->returnValue($prefixes));

        $classLoader
            ->expects($this->any())
            ->method('getFallbackDirs')
            ->with()
            ->will($this->returnValue($fallbackDirs));

        $classLoader
            ->expects($this->any())
            ->method('getClassMap')
            ->with()
            ->will($this->returnValue($classMap));

        if ($findFileReturnValueMap) {
            $classLoader
                ->expects($this->any())
                ->method('findFile')
                ->will($this->returnValueMap($findFileReturnValueMap));
        } else {
            $classLoader
                ->expects($this->never())
                ->method('findFile');
        }

        return $classLoader;
    }

    /**
     * Test getters
     */
    public function testGetters()
    {
        $classLoader0 = $this->createMockClassLoader(array(
            'A\B\C' => array('vendor/vend-0/psr0-project/src/a/b/c'),
        ), array(
            'vendor/vend-0/fallback-project/src',
        ), array(
            'A\B\C\ClassMapped' => 'vendor/vend-0/psr0-project/src/a/b/c/ClassMapped.php'
        ));

        $classLoader1 = $this->createMockClassLoader(array(
            'L\M\N' => array('vendor/vend-1/psr0-project/src/l/m/n'),
        ), array(
            'vendor/vend-1/fallback-project/src',
        ), array(
            'L\M\N\ClassMapped' => 'vendor/vend-1/psr0-project/src/l/m/n/ClassMapped.php'
        ));

        $classLoader2 = $this->createMockClassLoader(array(
            'X\Y\Z' => array('vendor/vend-2/psr0-project/src/x/y/z'),
        ), array(
            'vendor/vend-2/fallback-project/src',
        ), array(
            'X\Y\Z\ClassMapped' => 'vendor/vend-2/psr0-project/src/x/y/z/ClassMapped.php'
        ));

        // Duplicate of $classLoader2 for testing uniqueness detection capabilities.
        $classLoader3 = $this->createMockClassLoader(array(
            'L\M\N' => array('vendor/vend-1/psr0-project/src/l/m/n'),
        ), array(
            'vendor/vend-1/fallback-project/src',
        ), array(
            'L\M\N\ClassMapped' => 'vendor/vend-1/psr0-project/src/l/m/n/ClassMapped.php'
        ));

        $classLoader4 = $this->createMockClassLoader(array(
            'L\M\N' => array('vendor/vend-1/psr0-project-additional/src/l/m/n'),
        ), array(
            'vendor/vend-1/fallback-project-additional/src',
        ), array(
            'L\M\N\ClassMapped' => 'vendor/vend-1/psr0-project-additional/src/l/m/n/ClassMapped.php'
        ));

        $classLoaderReader = new CompositeClassLoaderReader(array(
            $classLoader0,
            $classLoader1,
            $classLoader2,
            $classLoader3,
            $classLoader4,
        ));

        $prefixes = $classLoaderReader->getPrefixes();
        $fallbackDirs = $classLoaderReader->getFallbackDirs();
        $classMap = $classLoaderReader->getClassMap();

        $this->assertCount(3, $prefixes);
        $this->assertCount(4, $fallbackDirs);
        $this->assertCount(3, $classMap);

        $this->assertCount(1, $prefixes['A\B\C']);
        $this->assertEquals('vendor/vend-0/psr0-project/src/a/b/c', $prefixes['A\B\C'][0]);

        $this->assertCount(2, $prefixes['L\M\N']);
        $this->assertEquals('vendor/vend-1/psr0-project/src/l/m/n', $prefixes['L\M\N'][0]);

        $this->assertCount(1, $prefixes['X\Y\Z']);
        $this->assertEquals('vendor/vend-2/psr0-project/src/x/y/z', $prefixes['X\Y\Z'][0]);

        $this->assertEquals(array(
            'vendor/vend-0/fallback-project/src',
            'vendor/vend-1/fallback-project/src',
            'vendor/vend-2/fallback-project/src',
            'vendor/vend-1/fallback-project-additional/src',
        ), $fallbackDirs);

        $this->assertEquals('vendor/vend-0/psr0-project/src/a/b/c/ClassMapped.php', $classMap['A\B\C\ClassMapped']);
        $this->assertEquals('vendor/vend-1/psr0-project/src/l/m/n/ClassMapped.php', $classMap['L\M\N\ClassMapped']);
        $this->assertEquals('vendor/vend-2/psr0-project/src/x/y/z/ClassMapped.php', $classMap['X\Y\Z\ClassMapped']);
    }

    /**
     * Test find files
     */
    public function testFindFile()
    {
        $classLoader0 = $this->createMockClassLoader(null, null, null, array(
            array('A\B\C\Sample', 'vendor/vend-0/psr0-project/src/a/b/c/Sample.php'),
        ));

        $classLoader1 = $this->createMockClassLoader(null, null, null, array(
            array('L\M\N\Sample', 'vendor/vend-1/psr0-project/src/l/m/n/Sample.php'),
        ));

        $classLoader2 = $this->createMockClassLoader(null, null, null, array(
            array('X\Y\Z\Sample', 'vendor/vend-2/psr0-project/src/x/y/z/Sample.php'),
        ));

        $classLoaderReader = new CompositeClassLoaderReader(array(
            $classLoader0,
            $classLoader1,
            $classLoader2,
        ));

        $this->assertNull($classLoaderReader->findFile('A\B\C\Missing'));
        $this->assertEquals('vendor/vend-0/psr0-project/src/a/b/c/Sample.php', $classLoaderReader->findFile('A\B\C\Sample'));
        $this->assertEquals('vendor/vend-1/psr0-project/src/l/m/n/Sample.php', $classLoaderReader->findFile('L\M\N\Sample'));
        $this->assertEquals('vendor/vend-2/psr0-project/src/x/y/z/Sample.php', $classLoaderReader->findFile('X\Y\Z\Sample'));
    }
}
