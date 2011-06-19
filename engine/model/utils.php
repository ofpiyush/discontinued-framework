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

final class utils
{
    private function __construct() {}
    public static function handleExceptions($e = null)
    {
        $config = load::model('config');
        if((is_array($config->exceptions) && count($config->exceptions) )
            && ($config->displayExceptions || $config->autologExceptions))
        {
            $exceptions = implode(PHP_EOL,$config->exceptions);
            if(!is_null($e))
                $exceptions = $e.PHP_EOL.$exceptions;
            if($config->autologExceptions)
            {
                \sb\model\load::model('logger')->write('exceptions',$exceptions);
            }
            if($config->displayExceptions) {
                echo "<pre>",$exceptions,"</pre>";
            }
        }
        die();
    }
}

/**
 * End of file utils
 */
