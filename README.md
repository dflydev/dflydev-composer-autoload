Composer Autoload Tools
=======================

Provide a standard interface for accessing Composer's autoloader.


Why?
----

While it is generally not considered best practice for an application
to be aware of underlying autoloaders, or be aware of how Composer
is managing things, accessing the autoloader is sometimes a
necessity. This library helps get access to the underlying Composer
autoloader registered during runtime.


Usage
-----

```php
<?php
$locator = new Dflydev\Composer\Autoload\ClassLoaderLocator;
$loader = $locator->locate();
```

If the Composer autoloader has been installed, it will be returned.
If it has not been installed `null` will be returned.


Requirements
------------

 * PHP 5.3+


License
-------

MIT, see LICENSE.


Community
---------

If you have questions or want to help out, join us in the
[#dflydev](irc://irc.freenode.net/#dflydev) channel on irc.freenode.net.
