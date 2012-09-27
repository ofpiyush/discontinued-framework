<?php
namespace sambhuti\controllers;
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
 * @author Piyush<piyush[at]codeitout[dot]com>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2012 Piyush
 */
use sambhuti\di;
class container {

    private $face = null;
    private $container = null;
    protected $notFound = null;
    protected $controllers = array();
    
    function __construct($face, $default, di\container $container) {
        $this->face = $face;
        $this->notFound = $this->get('_notFound');
        $this->container = $container;
        $this->controllers['home'] = $this->get($default);
    }

    function get($controller) {
        if(empty($controller)) {
            return $this->controllers['home'];
        } elseif(empty($this->controllers[$controller])) {
            $this->controllers[$controller] = $this->go($this->name($controller))->newInstance($this->container);
        }
        return $this->controllers[$controller];
    }

    function name($class, array $extras = array()) {
        return (strpos($class, '\\') === false) ? $class.'\\index' : $class);
    }

    protected function go($controller) {
        if(class_exists($controller)) {
            $reflection = new ReflectionClass($controller);
            if($reflection->implementsInterface($this->face) && $reflection->isInstantiable()) {
                return $reflection;
            }
        }
        if($this->notFound instanceof ReflectionClass) {
            return $this->notFound;
        }
        return null;
    }

}