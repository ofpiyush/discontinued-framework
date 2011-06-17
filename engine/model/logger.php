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

class logger
{
    private $dir = array();

    function __construct()
    {
        $this->setDir(SB_APP_PATH.'logs/');
    }

    function setDir($path)
    {
        if(is_dir($path) && is_writable($path))
            $this->dir[] = rtrim(realpath($path),'/').'/';
        else
            throw new Exception("$path is not writeable. PS: A full path to directory is needed for this to work");
    }

    function write($file,$data)
    {
        if(is_array($this->dir) && count($this->dir))
        {
            $fp = @fopen(end($this->dir).$file,"a");
            @fwrite($fp,PHP_EOL.date("r").PHP_EOL.$data.PHP_EOL);
            @fclose($fp);
        }
    }

    function restoreDir()
    {
        if(is_array($this->dir) && count($this->dir))
            array_pop($this->dir);
    }
}
