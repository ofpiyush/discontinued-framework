<?php
namespace app;
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


$config['namespace']			= __NAMESPACE__;
$config['defaultController']	= 'welcome';
$config['displayExceptions']	= true;
$config['autologExceptions']	= true;
$app_config['DAOSyntax']		= "#dbType##class#DAO";
/**
 * Db config vars
 */
$app_config['db']['type'] = 'mysql';
/**
 * Conn params
 */
$app_config['db']['master']['host']		= 'localhost';
$app_config['db']['master']['dbname']	= 'sambhuti';
$app_config['db']['master']['user']		= 'mysqluser';
$app_config['db']['master']['pass']		= 'mysqlpass';
$app_config['db']['master']['options']	= array();
$app_config['db']['master']['prefix']	= 'sb_';

