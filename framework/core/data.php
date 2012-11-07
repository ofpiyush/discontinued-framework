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
class data implements dataFace {

    private $data = array();

    function __construct(array $array = array()) {
        $this->data = $array;
	}

	public function get($key) {
        $args=func_get_args();
        $tmp = $this->data;
        foreach($args as $arg) {
            if(array_key_exists($arg,$tmp))
                $tmp = $tmp[$arg];
            else
                return null;
        }
        return $tmp;
    }

	function set($key,$value) {
        if(!array_key_exists($key,$this->data)){
            $this->update($key,$value);
            return $this;
        }
        throw new Exception('Data already set');
	}

	function update($key,$value) {
        $this->data[$key]=$value;
        return $this;
	}

    function getAll() {
        return $this->data;
    }

    function __get($key) {
        return $this->get($key);
    }

    function __set($key,$value) {
        throw new \Exception('Trying to save "'.$value.'" to Config "'.$key.'" 
        via __set use config::set() instead');
    }
}