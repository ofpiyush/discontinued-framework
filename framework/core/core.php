<?php
namespace sambhuti\core;
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
use sambhuti\loader;
class core extends container {
    static $dependencies = array('loader');
    protected $processed = array();

    function __construct(loader\loader $loader) {
        $this->processed['loader'] = $loader;
        $this->processed['core'] = $this;
    }

    function get($identifier = null) {
        if(null === $identifier || 'core' === $identifier) return $this;
        if(empty($this->processed[$identifier])) {
            $this->processed[$identifier] = $this->process(
                    $this->get('loader')->fetch($identifier,$identifier),
                    'sambhuti\core\container'
                );
            //Get the class of container to load

        }
        return $this->processed[$identifier];
    }

    function process($class,$base) {
        if(null !== $class && class_exists($class)) {
            //load dependencies
            $reflection = new \ReflectionClass($class);
            $dependencies = array();
            $staticProps = $reflection->getStaticProperties();
            if(array_key_exists('dependencies',$staticProps)) {
                $dependencies = array_map(array($this,'dependency'),$staticProps['dependencies']);
            }
            if($reflection->isSubclassOf($base)) {
                return $reflection->newInstanceArgs($dependencies);
            }
            throw new \Exception($class.' not subclass of '.$base);
        }
        throw new \Exception($class.' not found');
    }

    function dependency($string) {
        if('%' == $string[0]) {
            $string = ltrim($string, '%');
            return $this->get('config')->get($string);
        }
        return $this->get($string);
    }
}