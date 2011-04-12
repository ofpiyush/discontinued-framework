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
 * @author Gabriel <thehobbit[at]primorial[dot]net>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2010-2011 Piyush Mishra
 */

class SB_Load extends SB_Base
{
	var $viewvars = array();
	public function model($model_name,$fake_name=null)
	{		
		$model=str_replace('/#type#/',$this->config->get('db','type'),str_replace('/#class#/',$model_name,$this->config->get('db','DAO_name')));
		if(! is_null($fake_name))
			$model_name=$fake_name;
		sambhuti::autoload($model,'model');
		$this->pimpleLoadPush($model,$model_name);
	}
	
	public function view($pb_view,$array=array())
	{
		$pb_view_fn = sambhuti::getFullPath('view_path', $pb_view);
		if(count($array)!=0)
			$this->viewvars = array_merge($this->viewvars,$array);
		$this->cleanRequire($pb_view_fn, $this->viewvars);
	}
	function library($library)
	{
		$lib=(file_exists(SB_ENGINE_PATH.'lib/'.$library.'.php'))? "sb\\".$library : $library;
		sambhuti::autoload($lib,'library');
		$this->pimpleLoadPush($lib,$library);
	}
	function helper($helper)
	{
		sambhuti::autoload($helper,'helper');
	}
	function cleanRequire($file, $vars)
	{
		unset($file, $vars);
		$this->load->helper('functions');
		extract(func_get_arg(1));
		require(func_get_arg(0));
	}
	private function pimpleLoadPush($class,$name=null) 
	{
		$pimple=sambhuti::pimple();
		extract(sambhuti::explodeNS($class));
		if(! isset($pimple->$classname))
		$pimple->$classname=$pimple->asShared(function($p) use($class)
		{
			return new $class();
		});
		if(is_null($name))
			$name=$classname;
		$this->_cannula($name,$pimple->$classname);
	}
	
}

/**
 * End of file Load
 */
