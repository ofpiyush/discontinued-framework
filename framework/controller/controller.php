<?php
namespace sambhuti\controller;
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
class controller extends core\container {

    static $dependencies = array('config.routing','core');
    protected $core = null;
    protected $routing = null;
    protected $request = null;
    protected $notFound = null;
    protected $controllers = array();


    function __construct(array $dependencies = array()) {
        $this->routing = $dependencies['config.routing'];
        $this->core = $dependencies['core'];
        $this->notFound = $this->process($this->routing->get('404'));
        $this->controllers['home'] = $this->process($this->routing->get('home'));
    }

    function get($command = null) {
        if(empty($command)) {
            return $this->get('home');
        }
        if(null !== $this->routing->get($command)) $command = $this->routing->get($command);
        //TODO: fixme
        $args = explode('/',$command);
        $controller = array_shift($args);
        $method = !empty($args) ? array_shift($args) : 'index';
        $object = $this->process($controller);
        if(null === $object) {
            $object = $this->notFound;
            $method = '_404';
        }
        $object->$method($args);
        return $object;
    }

    function process($controller) {
        if(empty($this->controllers[$controller])) {
            $class = $this->core->get('loader')->fetch('controller',$controller);
            if(null !== $class) {
                $this->controllers[$controller] = $this->core->process($class, 'sambhuti\controller\base');
            } else {
                $this->controllers[$controller] = null;
            }
        }
        return $this->controllers[$controller];
    }
}