<?php
$time1=microtime(true);
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

$sb_engine='engine';
/**
 * $sb_apps['full_url']='relative/path/from/this/file'
 * specific to generic to prevent overriding
 * donot use a trailing slash
 */
$sb_apps['http://localhost/sambhuti']='site';
/**
 * Now let sambhuti handle the rest
 */


define('SB_ENGINE_PATH',realpath($sb_engine).'/');
if(SB_ENGINE_PATH=='/')
	exit('Please check your $sb_engine '.__FILE__);
require_once SB_ENGINE_PATH.'init.php';




/**
 * End of file Index
 */
