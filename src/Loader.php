<?php

use Kernel\Exception\LoaderException;

/**
 * Loader Class. Responsible for including CMS files by classname
 *
 * @author Mikhail Lantukh <lantukhmikhail@gmail.com>
 */
class Loader
{
    /**
     * The file container
     *
     * @var \ArrayObject
     */
    private static $_registry = null;

    /**
     * Loader instance
     *
     * @var \Loader
     */
    private static $_instance = null;

    /**
     * Constructor. Registers object for auto loading
     *
     * @return \Loader
     */
    final private function __construct()
    {
        self::$_registry = new \ArrayObject();
        spl_autoload_register(array(__CLASS__, 'load'));

        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
    }

    /**
     * Clone. Prevent object cloning
     *
     * @final
     * @codeCoverageIgnore
     * @return void
     */
    final private function __clone()
    {
    }

    /**
     * Method for loading classes.
     *
     * @param string $class Called class
     *
     * @throws LoaderException If class doesn't exist
     * @return bool Returns TRUE on success throws exception on failure
     */
    public static function load($class)
    {
        $paths    = explode('\\', $class);
        $filePath = __DIR__.'/'.(1 === count($paths)?$class:implode('/', $paths)).'.php';

        if ($filePath !== false && !in_array($filePath, get_included_files()) && file_exists($filePath)) {
            $included = include $filePath;

            if ($included && (class_exists($class, false) || interface_exists($class, false))) {
                return $filePath;
            } else {
                throw new LoaderException(sprintf('Class "%s" doesn\'t exist in "%s"', $class, $filePath));
            }
        }

        return false;
    }

    /**
     * Returns singleton instance of the loader
     *
     * @static
     *
     * @return self
     */
    public static function instantiate()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Unregisters autoload from the system and removes current instance
     */
    public static function destroy()
    {
        if (!is_null(self::$_instance)) {
            spl_autoload_unregister(array(__CLASS__, 'load'));
            self::$_instance = null;
        }
    }
}

Loader::instantiate();