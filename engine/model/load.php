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
class load
{
	private static $models = array();
	private static $lazyPaths = array();
	public static function register()
	{
		self::addLazyPath('sb',SB_ENGINE_PATH);
		spl_autoload_register(array(__CLASS__, 'auto' ));
	}
	public static function model($class,$new = false,$args = array())
	{
		if(! $new && array_key_exists($class,self::$models))
		{
			if(array_key_exists('instance',self::$models[$class]))
				return self::$models[$class]['instance'];
		}
		else
		{
			$name = self::fetch('model',$class);
			if($name)
			{
				self::$models[$class]['reflection'] = new \ReflectionClass($name);
				return self::$models[$class]['instance'] = self::$models[$class]['reflection']->newInstance($args);
			}
			else
			{
				throw new Exception("No model for ".$class." found");
			}
		}
			
	}
	
	public static function fetch($type,$classname)
	{
		foreach(array_reverse(self::$lazyPaths) as $ns => $path)
		{
			if(self::checkRequire($path.'/'.$type.'/'.$classname))
				return $ns.'\\'.$type.'\\'.$classname;
		}
		return false;
	}
	public static function auto($class)
	{
		if(class_exists($class))
			return true;
		$array = explode('\\',$class);
		if(array_key_exists($array[0],self::$lazyPaths))
		{
			$array[0] = self::$lazyPaths[$array[0]];
			return self::checkRequire(implode($array,'/'));
		}
		return false;
	}
	public static function unreg()
	{
		self::$lazy_paths=array();
		spl_autoload_unregister(array(__CLASS__, 'auto' ));
	}
	public static function checkRequire($path)
	{
		$fullpath = $path.'.php';
		if(file_exists($fullpath))
			{
				require_once($fullpath);
				return true;
			}
			return false;
	}
	public static function addLazyPath($namespace,$path)
	{
		self::$lazyPaths[$namespace] = rtrim($path,'/');
	}
}
