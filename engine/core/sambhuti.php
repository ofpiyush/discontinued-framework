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

final class sambhuti
{
	private static $lazy_paths = array();
	private static $third_party = array();
	private static $registry = null;
	private function __construct(){}
	public static function run($sb_apps,$paths=null,$thirdparty=null)
	{
		self::setLazyPaths($paths,$thirdparty);
		spl_autoload_register(array(__CLASS__, 'autoload' ));
		self::registryInit($sb_apps);
		self::init();
	}
	public static function registry($key=null)
	{
		if(is_null($key))
			return self::$registry;
		return self::$registry->get($key);
	}
	public static function getFullPath($type, $relpath, $ext = '.php')
	{
		// Make sure we have a valid root directory.
		try{
		$root = realpath(self::$objects['config']->get($type));
		if (strlen($root) <= 1)
			throw new SB_Exception("An administrator should set the $type path properly.");
		// Make sure the requested path is a real file.
		$fullpath = realpath($root . '/' . $relpath . $ext);
		if (!strlen($fullpath))
			throw new SB_Exception("Requested file, $relpath, does not exist in $type.");
		if (!is_file($fullpath))
			throw new SB_Exception("Requested file, $relpath in $type, is a(n) " . filetype($fullpath) . ", expected regular file.");

		// Make sure we haven't tried to escape the root directory.
		if (substr($fullpath, 0, strlen($root)) != $root)
			throw new SB_Exception("Requested file, $relpath, does not exist within its the $type directory.");
		}
		catch(SB_Exception $e)
		{
			echo $e->getMessage();
			die();
		}
		// All good.
		return $fullpath;
	}
	public static function getThirdParty()
	{
		return self::$third_party;
	}
	public static function addThirdParty($thirdparty)
	{
		if(is_array($thirdparty))
			self::$third_party=array_merge_recursive(self::$third_party,$thirdparty);
	}
	public static function addLazyPath($path,$ulta=false)
	{
		if(is_array($path))
			if(is_bool($ulta) && $ulta)
				self::$lazy_paths=array_merge_recursive($path,self::$lazy_paths);
			else
				self::$lazy_paths=array_merge_recursive(self::$lazy_paths,$path);
	}
	public static function explodeNS($class)
	{
		$return=array();
		$break_array = explode('\\',$class);
		$return['classname']=array_pop($break_array);
		$return['namespace']=implode($break_array,'\\');
		return $return;
	}
	public static function autoload($class,$type="any")
	{
		extract(self::explodeNS($class));//php 5.3 stuff. always gives out global now
		$namespace = ($namespace=='') ? 'global' : $namespace;		
		if(class_exists($classname, false))
			return;
		
		if(array_key_exists($namespace,self::$lazy_paths))
		{
			foreach(self::$lazy_paths[$namespace] as $key=>$path) 
			{
				if($type=='any' || $key==$type)
				{
					$file_name = $path.$classname.'.php';
					if(file_exists($file_name))
					{
						require_once $file_name;
						return true;
					}
				}
			}
			
		}
		if(array_key_exists($class,self::$third_party))
		{
			require_once self::$third_party[$class];
			return true;
		}
			throw new SB_Exception("Not Found",404,$classname);
	}
	public static function stop()
	{
		self::$lazy_paths=array();
		self::$third_party=array();
		spl_autoload_unregister(array(__CLASS__, 'autoload' ));
	}
	private static function registryInit($sb_apps=array())
	{
		self::$registry = new SB_Registry();
		self::$registry->set('uri',new SB_Uri($sb_apps));
		if(! defined('SB_APP_PATH') || SB_APP_PATH=='/')
			exit('Please check your $sb_apps array.');
	}
	private static function init()
	{
		self::addLazyPath(self::$registry->get('config')->get('lazy_path'));
		$cname = (self::$registry->get('uri')->total_segments()) ? self::$registry->get('uri')->segment(1) : self::$registry->get('config')->get('default_controller');
		try{
			$controller= new $cname;
			unset($cname);
			$args=array();
			print_r(self::$registry->get('uri')->total_segments());
			if(self::$registry->get('uri')->total_segments()>1)
			{
				$method=self::$_pimple->uri->segment(2);
				if(method_exists($controller,$method))
				{
					if(self::$registry->get('uri')->total_segments()>2)
					{
						$args=self::$_pimple->uri->segment_array();
						array_shift($args);
						array_shift($args);
					}	
				}else
					throw new SB_Exception("Not Found",404,$method);
			}else
				$method='index';
			if(is_callable(array($controller,$method)))
				call_user_func_array(array($controller,$method),$args);
			
			
		}
		catch(SB_Exception $e)
		{
			
			echo "Exception:".$e->getClassName()." Not found.";

		}
	}
	
	private static function setLazyPaths($paths=null,$thirdparty=null)
	{
		self::$lazy_paths=array
		(
			'global'=>array
			(
				'helper'=>SB_ENGINE_PATH.'helpers/',
				'core'=>SB_ENGINE_PATH.'core/',
				'library'=>SB_ENGINE_PATH.'lib/'
			)
		);
		if(isset($paths) && is_array($paths))
			self::$lazy_paths=array_merge_recursive(self::$lazy_paths,$paths);
		if(isset($thirdparty) && is_array($thirdparty))
			self::$third_party=array_merge_recursive(self::$third_party,$thirdparty);
	}	
	
}


/**
 * End of file loader
 */
