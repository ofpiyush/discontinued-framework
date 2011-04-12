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

class SB_Input extends SB_Base
{
	private $validate;
	private $hasvar;
	function __construct()
	{
	
	}
	function set($key,$filter,$options=null,$flags=null)
	{
		$this->validate[$key]['filter']=$filter;
		$this->validate[$key]['options']=$options;
		$this->validate[$key]['flags']=$flags;
		//print_r($this->validate);
	}
	function get($key,$post=true)
	{
		
		if($post)
			return filter_input(INPUT_POST,$key,$this->validate[$key]['filter'],$this->validate[$key]['options']);
		else
			return filter_input(INPUT_GET,$key,$this->validate[$key]['filter'],$this->validate[$key]['options']);
	}
	function getAll($post=true)
	{
		if($post)
			return filter_input_array(INPUT_POST,$this->validate);
		else
			return filter_input_array(INPUT_GET,$this->validate);
	}
	function hasVar($var,$post=true)
	{
		if($post)
			return filter_has_var(INPUT_POST,$var);
		else
			return filter_has_var(INPUT_GET,$var);
	}
}

/**
 * End of file Input
 */
