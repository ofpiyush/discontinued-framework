<?php
namespace sb\controller;
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

class sambhuti extends base
{
	private static $config;
	public function execute(\sb\model\request $request)
	{
		require_once(SB_APP_PATH.'config/config.php');
		\sb\model\load::addLazyPath($config['namespace'],SB_APP_PATH);
		self::$config = new \sb\model\config($config);
		$resolver = new \sb\model\resolver($config['namespace'].'\\controller\\'.$config['defaultController']);
		$resolver->getController($config['namespace'].'\\controller\\'.$request->controller)->execute($request);
	}
	
}
