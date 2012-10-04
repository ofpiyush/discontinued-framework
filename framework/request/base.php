<?php
namespace sambhuti\request;
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
use sambhuti\core;

abstract class base implements face {

    protected $data = null;
    protected $controller = '';
    protected $get = array();
    protected $post = array();
    protected $server = array();

    final function __construct(core\dataFace $data) {
        $this->go();
        $this->data = $data
            ->set('controller',trim($this->controller, '/'))
            ->set('get',$this->get)
            ->set('post',$this->post)
            ->set('server',$this->server);
    }

    function parse(array &$array, $string) {
        $vars = explode('=', $string, 2);
        $array[$vars[0]] = !empty($vars[1]) ? $vars[1] : '';
    }

    final function data() {
        return $this->data;
    }

}