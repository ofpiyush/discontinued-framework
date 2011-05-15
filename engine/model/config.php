<?php
namespace sb\model;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * Sambhuti
 * Copyright (C) 2010-2011  Piyush Mishra
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
 * @author Piyush Mishra <me[at]piyushmishra[dot]com>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2010-2011 Piyush Mishra
 */

class config
{
	private $conf = array();
	var $exceptions = array();
	function __construct($array = null)
	{
		if(!is_null($array))
			$this->conf = $array;
	}
	public function get($key)
	{
		$args=func_get_args();
		$tmp = $this->conf;
		foreach($args as $arg)
		{
			if(array_key_exists($arg,$tmp))
				$tmp=$tmp[$arg];
			else
				return null;
		}
		return $tmp;
	}
	public function __get($key)
	{
		if(array_key_exists($key,$this->conf))
			return $this->conf[$key];
		return false;
	}
	public function __set($key,$val)
	{
		$this->conf[$key] = $val;	
	}
}
