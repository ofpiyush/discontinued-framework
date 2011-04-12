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

final class Sambhuti
{
	private static $_lazy_paths = array();
	private static $_thirdparty = array();
	private static $_objects = array();
	private function __construct(){}
	public static function run($sb_apps,$paths=null,$thirdparty=null)
	{
		self::setLazyPaths($paths,$thirdparty);
		spl_autoload_register(array(__CLASS__, 'autoload' ));
		self::setAppPath($sb_apps);
		self::Init();		
	}
	public static function setAppPath($sb_apps)
	{
		self::$_objects['uri'] = new SB_Uri($sb_apps);
		if(! defined('SB_APP_PATH') || SB_APP_PATH=='/')
		exit('Please check your $sb_apps array.');
	}
	
	public static function getFullPath($type, $relpath, $ext = '.php')
	{
		// Make sure we have a valid root directory.
		try{
		$root = realpath(self::$_pimple->config->get($type));
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
		return self::$_thirdparty;
	}
	public static function addThirdParty($thirdparty)
	{
		if(is_array($thirdparty))
			self::$_thirdparty=array_merge_recursive(self::$_thirdparty,$thirdparty);
	}
	public static function addLazyPath($path,$ulta=false)
	{
		if(is_array($path))
			if(is_bool($ulta) && $ulta)
				self::$_lazy_paths=array_merge_recursive($path,self::$_lazy_paths);
			else
				self::$_lazy_paths=array_merge_recursive(self::$_lazy_paths,$path);
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
		extract(self::explodeNS($class));
		$namespace = ($namespace=='') ? 'global' : $namespace;		
		if(class_exists($classname, false))
			return;
		
		if(array_key_exists($namespace,self::$_lazy_paths))
		{
			foreach(self::$_lazy_paths[$namespace] as $key=>$path) 
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
		if(array_key_exists($class,self::$_thirdparty))
		{
			require_once self::$_thirdparty[$class];
			return true;
		}
			throw new SB_Exception("Not Found",404,$classname);
	}
	public static function stop()
	{
		self::$_lazy_paths=array();
		self::$_thirdparty=array();
		spl_autoload_unregister(array(__CLASS__, 'autoload' ));
	}
	
	private static function pimpleInit()
	{
		self::$_pimple= new Pimple();
		self::$_pimple->config=self::$_pimple->asShared(function($pimple)
			{
				$confinst= new config($pimple->_conf);
				unset($pimple->_conf);
				return $confinst;
			});
		self::$_pimple->load=self::$_pimple->asShared(function()
			{
				return new load();
			});
		self::$_pimple->input=self::$_pimple->asShared(function()
			{
				return new input();
			});
		self::$_pimple->session=self::$_pimple->asShared(function()
			{
				return new session();
			});
		self::$_pimple->controller=self::$_pimple->asShared(function($pimple)
			{
				return new $pimple->_cname();
			});
	}
	private static function Init($asd="aaa")
	{
		require_once SB_APP_PATH.'config.php';
		if(isset($app_config) && is_array($app_config))
			self::$_pimple->_conf=$app_config;
		unset($app_config);
		self::addLazyPath(self::$_pimple->config->get('lazy_path'));
		$segments=self::$_pimple->uri->total_segments();
		//print_r(self::$_pimple->uri->segment_array());
		self::$_pimple->_cname=($segments) ? self::$_pimple->uri->segment(1) : self::$_pimple->config->get('default_controller');		
		try{
			$controller=self::$_pimple->controller;
			$args=array();
			if($segments>1)
			{
				$method=self::$_pimple->uri->segment(2);
				if(method_exists($controller,$method))
				{
					if($segments>2)
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
		self::$_lazy_paths=array
		(
			'global'=>array
			(
				'helper'=>SB_ENGINE_PATH.'helpers/',
				SB_ENGINE_PATH.'core/',
				'library'=>SB_ENGINE_PATH.'lib/'
			)
		);
		if(isset($paths) && is_array($paths))
			self::$_lazy_paths=array_merge_recursive(self::$_lazy_paths,$paths);
		if(isset($thirdparty) && is_array($thirdparty))
			self::$_thirdparty=array_merge_recursive(self::$_thirdparty,$thirdparty);
	}	
	
}


/**
 * End of file loader
 */
