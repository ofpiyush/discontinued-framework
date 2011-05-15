<?php
namespace app\controller;
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

class welcome extends \sb\controller\base
{
	function execute(\sb\model\request $request)
	{
		global $time1;
		try
		{
			$test = \sb\model\load::model('test');
		}
		catch(\sb\model\Exception $e){}
		try
		{
			$test2 = \sb\model\load::model('test2');
		}
		catch(\sb\model\Exception $e){}
		
		throw new \sb\model\Exception("hahah");
		echo SB_APP_PATH ,"<br />", SB_ENGINE_PATH , "<br />";
		echo "default controller called <br />";
		echo microtime(true)-$time1," Seconds";
	}
}

/**
 * End of file Welcome
 */
