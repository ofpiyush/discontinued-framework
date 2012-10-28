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
use sambhuti\di;
use sambhuti\core;
class controller extends core\container {

    static $dependencies = array('%routing','core');
    private $face = null;
    private $core = null;
    protected $notFound = null;
    protected $controllers = array();


    function __construct(core\data $routing, core\core $core) {
        $this->core = $core;
        $this->face = $routing->get('interface');
        $this->notFound = $this->get($routing->get('404'));
        $this->controllers['home'] = $this->get($routing->get('home'));
    }

    function get($controller = null) {
        if(null === $controller) return $this;
        if(empty($controller)) {
            return $this->get('home');
        } elseif(empty($this->controllers[$controller])) {
            $name = (strpos($controller, '\\') === false) ? $controller.'\\index' : $controller;
            $class = $this->core->get('loader')->fetch('controller',$name);
            if(null !== $class) {
                $this->controllers[$controller] = $this->core->process( $class, 'sambhuti\controller\base');
            } else {
                $this->controllers[$controller] = $this->notFound;
            }
        }
        return $this->controllers[$controller];
    }

}