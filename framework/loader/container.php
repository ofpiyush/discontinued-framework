<?php
/**
 * Sambhuti
 * Copyright (C) 2012-2013 Piyush
 *
 * License:
 * This file is part of Sambhuti (http://sambhuti.org)
 *
 * Sambhuti is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sambhuti is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sambhuti.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace sambhuti\loader;

/**
 * loader Container
 *
 * Contains auto loader for lazy loading.
 * Allows class fetching for loading classes from particular modules
 * Can be accessed by the string 'loader'.
 *
 * <code>
 * class test implements \sambhuti\core\iContainer {
 *     static $dependencies = array('loader');
 *     public $loader = null;
 *
 *     function __construct(\sambhuti\loader\iLoader $loader) {
 *         $this->loader = $loader;
 *     }
 * }
 * </code>
 *
 * @package    Sambhuti
 * @subpackage loader
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class container implements iContainer {

    /**
     * Lazy Path
     *
     * Array of [namespace => Path] for lazy loading
     *
     * @var array $lazyPath
     */
    protected $lazyPath = array();

    /**
     * Constructor
     *
     * Registers loader::get() as the autoload method
     *
     */
    function __construct () {
        spl_autoload_register(array($this, 'get'));
    }

    /**
     * Get - Autoloader
     *
     * Autoloader to load the classes under all lazy paths
     *
     * @param string $class name of the class to be loaded
     *
     * @return bool true if found, false otherwise
     */
    function get ( $class = null ) {
        if (class_exists($class)) {
            return true;
        }
        $array = explode('\\', $class);
        if (array_key_exists($array[0], $this->lazyPath)) {
            $array[0] = $this->lazyPath[$array[0]];
            return $this->checkRequire(implode($array, DIRECTORY_SEPARATOR));
        }
        return false;
    }

    /**
     * Check Require
     *
     * Looks for a file and require_once it if it exists
     *
     * @param string $name partial path or class name to look for.
     *
     * @return bool true if found, false otherwise
     */
    function checkRequire ( $name ) {
        $fullPath = str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';
        if (file_exists($fullPath)) {
            require_once($fullPath);
            return true;
        }
        return false;
    }

    /**
     * Fetch
     *
     * Looks for a class in all registered paths and
     * returns the class name if matches else gives null
     *
     * @param string $class name of class to look for
     *
     * @return string|null string full class name if class exists else null
     */
    function fetch ( $class ) {
        if (class_exists($class)) {
            return $class;
        }
        $paths = array_reverse($this->lazyPath);
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        foreach ( $paths as $ns => $path ) {
            if ($this->checkRequire($path . DIRECTORY_SEPARATOR . $classPath)) {
                return $ns . '\\' . $class;
            }
        }
        return null;
    }

    /**
     * Add Lazy Path
     *
     * Add single lazyPath for loader
     *
     * @param string $namespace namespace of the path
     * @param string $path      the full path to the directory to be added
     *
     * @return \sambhuti\loader\iContainer instance
     */
    function addLazyPath ( $namespace, $path ) {
        $path = rtrim($path, '/');
        $this->lazyPath[$namespace] = $path;
        return $this;
    }

    /**
     * Get Lazy Path
     *
     * Get single lazyPath from loader
     *
     * @param string $key namespace of the lazy path
     *
     * @return string|bool string path if $key exists else boolean false
     */
    function getLazyPath ( $key ) {
        if (!empty($this->lazyPath[$key])) {
            return $this->lazyPath[$key];
        }
        return false;
    }

    /**
     * Get Lazy Paths
     *
     * Get all lazyPath from loader
     *
     * @return array all lazy paths.
     */
    function getLazyPaths () {
        return $this->lazyPath;
    }

    /**
     * Sleep
     *
     * @return array lazy path to be cached
     */
    function __sleep () {
        return array('lazyPath');
    }

}
