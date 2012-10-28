<?php
namespace sambhuti\loader;
if(!defined('SAMBHUTI_ROOT_PATH')) exit;
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
 * @package Sambhuti
 * @author Piyush<piyush[at]cio[dot]bz>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2012 Piyush
 */

use sambhuti\core;
class loader extends core\container {

    /**
     * Paths for lazyloading
     * @var array
     */
    private $lazypath = array();

    function __construct() {
        spl_autoload_register(array($this, 'get'));
    }

    /**
     * Autoloader to load the classes under all lazypaths
     * @param string $class name of the class to be loaded
     */
    function get($class = null) {
        if(class_exists($class))
            return true;
        $array = explode('\\',$class);
        if(array_key_exists($array[0],$this->lazypath)) {
            $array[0] = $this->lazypath[$array[0]];
            return $this->checkRequire(implode($array,'/'));
        }
        return false;
    }

    function checkRequire($path) {
        $fullpath = str_replace('\\','/',$path).'.php';
        if(file_exists($fullpath)) {
            require_once($fullpath);
            return true;
        }
        return false;
    }

    function fetch($type,$classname) {
        $paths = array_reverse($this->lazypath);
        foreach( $paths as $ns => $path) {
            if($this->checkRequire($path.'/'.$type.'/'.$classname))
                return $ns.'\\'.$type.'\\'.$classname;
        }
        return null;
    }

    /**
     * Add single lazypath for autoloader
     * @param string $namespace namespace for replacement
     * @param string $path the full path to the directory to be added
     */
    function addLazyPath($namespace, $path) {
        $path = rtrim($path,'/');
        $this->lazypath[$namespace] = $path;
        return $this;
    }

    function getLazyPath($key) {
        if(!empty($this->lazypath[$key])) {
            return $this->lazypath[$key];
        }
        return false;
    }

    function getLazyPaths() {
        return $this->lazypath;
    }

    function __sleep() {
        return array('lazypath');
    }

}