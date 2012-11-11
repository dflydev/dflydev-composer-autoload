Composer Autoload Tools
=======================

Provide a standard interface for accessing and reading [Composer][0]
autoloaders.


#### Why?

While it is generally not considered best practice for an application
to be aware of underlying autoloaders, accessing the autoloader is
sometimes a necessity.


Usage
-----

```php
<?php
use Dflydev\Composer\Autoload\ClassLoaderLocator;
$locator = new ClassLoaderLocator;

$reader = $locator->getReader();

// Get access to all of the prefixes registered to all of
// the Composer Class Loaders in one array.
$prefixes = $reader->getPrefixes();
```


Installation
------------

Through [Composer][00] as [dflydev/composer-autoload][01].


Requirements
------------

 * PHP 5.3+


API
---

### ClassLoaderLocator Class

#### Static Methods

 * **init()**:
   Initialize static instance.
   
   Can be used to ensure that everything is setup before it is actually used
   at a later time. For example, if something may be going to modify the list
   of registered autoloaders, this will ensure that the Composer ones can be
   found and recorded right away.
 * **reset()**:
   Reset the static instance.
   
   This effectively clears the located Class Loader instances. The next time
   something tries to access the class loaders the list of registered
   autoloaders will be scanned again.
 * **set(array $classLoaders)**:
   Set the list of Class Loaders.
   
   This is here primarily for testing purposes.

#### Class Loaders

Direct access to Composer Class Loaders.

 * *Composer\Autoload\ClassLoader[]* **getClassLoaders()**:
   Get all Composer Autoload Class Loader instances.
 * *Composer\Autoload\ClassLoader* **getFirstClassLoader()**:
   Get the first Class Loader registered.
 * *Composer\Autoload\ClassLoader* **getFirstClassLoader()**:
   Get the last Class Loader registered.

#### Class Loader Readers

 * *ClassLoaderReaderInterface* **getReader()**:
   Get a ClassLoader Reader.

   If multiple ClassLoaders or no Class Loaders are registered a Composite
   Class Loader Reader will be returned.
 * *ClassLoaderReaderInterface[]* **getReaders()**:
   Get Class Loader Readers for each registered Class Loader.
 * *ClassLoaderReaderInterface* **getFirstClassLoader()**:
   Get the Class Loader Reader for the first Class Loader registered.
 * *ClassLoaderReaderInterface* **getFirstClassLoader()**:
   Get the Class Loader Reader for the last Class Loader registered.

### ClassLoaderReaderInterface

Mimics the data methods from `Composer\Autoload\ClassLoader`.

 * *array* **getPrefixes()**:
   Get namespace to directory mapping

 * *array* **getFallbackDirs()**:
   Get list of fallback directories

 * *array* **getClassMap()**:
   Get class mapping
   
 * *string|null* **findFile($class)**:
   Find the file for a specific class.

Gotchas
-------

In some cases Composer's Class Loader may be replaced by another
autoload implementation. The common example for this is when a
specialized Debug Class Loader is registered on top of Composer.
In these cases it is advised to call `init()` immediately after
`autload.php` is required to ensure that Composer's Class Loader can
be located.

```php
<?php
Dflydev\Composer\Autoload\ClassLoaderLocator::init();
```


License
-------

MIT, see LICENSE.


Community
---------

If you have questions or want to help out, join us in the
[#dflydev](irc://irc.freenode.net/#dflydev) channel on irc.freenode.net.

[0]: http://getcomposer.org
[1]: https://packagist.org/packages/dflydev/composer-autoload