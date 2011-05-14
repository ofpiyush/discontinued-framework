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

class request
{
	var $type		= null;
	var $controller	= null;
	var $segments	= null;
	var $action		= null;
	var $raw		= null;
	var $filtered	= null;
	var $siteURL	= null;
	function __construct($apps)
	{
		$this->processType();
		if($this->type=='web')
		{
			$this->parseURL($apps);
		}
	}
	private function parseURL($apps)
	{
		$scheme		= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ? 'https':'http';
		$hostParts	= explode(':', $_SERVER['HTTP_HOST'], 2);
		$httpHost	= array_shift($hostParts);
		$httpPort	= array_shift($hostParts);
		$requestURI	= rtrim($_SERVER['REQUEST_URI'],'/').'/';
		if(filter_var($scheme."://".$httpHost.$requestURI, FILTER_VALIDATE_URL))
		{
			if(isset($apps) && is_array($apps))
			foreach($apps as $url=>$relpath)
			{
				$urla=parse_url($url);
				if(!isset($urla['port']))
					$urla['port']='';
				if($urla['scheme']==$scheme && $urla['host']==$httpHost && $urla['port'] == $httpPort)
				{
					if(!isset($urla['path']) || strpos($requestURI,$urla['path'])===0)
					{
						$this->siteURL = rtrim($url,'/').'/';
						$this->populate($urla['path'],$requestURI);
						define('SB_APP_PATH',realpath($relpath).'/');
						break;
					}
				}
			}
		}
	}
	private function populate($path,$requestURI)
	{
		$relative	= trim(substr($requestURI, strlen($path)),'/');
		$segments	= explode('/',$relative);
		if($segments[0]!='')
		{
			$this->controller = $segments[0];
			unset($segments[0]);
			if(array_key_exists(1,$segments))
				$this->segments = $segments;
		}
	}
	private function processType()
	{
		$this->type = (PHP_SAPI == 'cli')?'cli' : 'web';
	}
}
