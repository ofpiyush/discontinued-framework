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
use sambhuti\core;

/**
 * Class Loader
 *
 * Contains auto loader for lazy loading.
 * Allows type based class fetching for loading classes from particular modules
 * Can be accessed by the string 'loader'.
 *
 * <code>
 * class test extends \sambhuti\core\container {
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
interface iLoader extends core\iContainer {

    /**
     * Constructor
     *
     * Registers loader::get() as the autoload method
     *
     * @param array $dependencies
     */
    function __construct ();


    /**
     * Check Require
     *
     * Looks for a file and require_once it if it exists
     *
     * @param string $name partial path or classname to look for.
     *
     * @return bool true if found, false otherwise
     */
    function checkRequire ( $name );

    /**
     * Fetch
     *
     * Looks for a class of certain type in all registered paths and
     * returns the class name if matches else gives null
     *
     * @param string $class name of class to look for
     *
     * @return string|null string full classname if class exists else null
     */
    function fetch ( $class );

    /**
     * Add Lazy Path
     *
     * Add single lazyPath for loader
     *
     * @param string $namespace namespace for replacement
     * @param string $path      the full path to the directory to be added
     *
     * @return \sambhuti\loader\loader instance
     */
    function addLazyPath ( $namespace, $path );

    /**
     * Get Lazy Path
     *
     * Get single lazyPath from loader
     *
     * @param string $key namespace of the lazypath
     *
     * @return string|bool string path if $key exists else boolean false
     */
    function getLazyPath ( $key );

    /**
     * Get Lazy Paths
     *
     * Get all lazyPath from loader
     *
     * @return array all lazypaths.
     */
    function getLazyPaths ();

}
