<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbdcf75fc7b8585189695ed2a8aaa5622
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbdcf75fc7b8585189695ed2a8aaa5622', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbdcf75fc7b8585189695ed2a8aaa5622', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbdcf75fc7b8585189695ed2a8aaa5622::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
