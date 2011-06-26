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
$config['defaultController']	= 'welcome'; //string (fullname of class. fill in the blanks for app/controller/_______.php)
$config['displayExceptions']	= false; // bool (true|false)
$config['autologExceptions']	= true; // bool (true|false)
/**
 * Twig Environment vars
 * http://www.twig-project.org/doc/api.html#environment-options
 */
$config['twigEnvVars']['debug']                 = false; // bool (true|false)
$config['twigEnvVars']['charset']               = 'utf-8'; // string charset
$config['twigEnvVars']['cache']                 = SB_APP_PATH."cache/twig"; //mixed {bool (false) for no caching | string ('/fullpath/to/twig/cache/dir')}
$config['twigEnvVars']['auto_reload']           = false; // bool (true|false)
$config['twigEnvVars']['autoescape']            = true; // bool (true|false)
$config['twigEnvVars']['optimizations']         =  -1;
$config['twigEnvVars']['strict_variables']      = false; // bool (true|false)
$config['twigEnvVars']['base_template_class']   = 'Twig_Template'; //string (fullname of class with NS if necessary)
/**
 * Db config vars
 */
$config['db']['type'] = 'mysql';
/**
 * Conn params
 */
$config['db']['master']['host']		= 'localhost';
$config['db']['master']['dbname']	= 'sambhuti';
$config['db']['master']['user']		= 'mysqluser';
$config['db']['master']['pass']		= 'mysqlpass';
$config['db']['master']['options']	= array();
$config['db']['master']['prefix']	= 'sb_';

