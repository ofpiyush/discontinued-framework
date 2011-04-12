<?php
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
final class SB_Config
{
	
	private $_conf=array();
	public function __construct($config)
	{
		$this->_conf=$config;
	}
	public function get($key)
	{
		$args=func_get_args();
		$tmp=$this->_conf;
		foreach($args as $arg)
		{
			if(array_key_exists($arg,$tmp))
				$tmp=$tmp[$arg];
			else
				return;
		}
		return $tmp;
	}
	public function set($key,$val)
	{
		$this->_conf[$key]=$val;
	}
	public function __get($key)
	{
		if(array_key_exists($key,$this->_conf))
			return $this->_conf[$key];
	}
}


/**
 * End of file Config
 */
