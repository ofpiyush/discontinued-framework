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
    public function execute(\sb\model\request $request)
    {
        if(!defined('SB_APP_PATH')) define('SB_APP_PATH',SB_ENGINE_PATH);
        if(file_exists(SB_APP_PATH.'init.php')) require_once(SB_APP_PATH.'init.php');
        try
        {
            if(file_exists(SB_APP_PATH.'config/config.php'))
            {
                require_once(SB_APP_PATH.'config/config.php');
                \sb\model\load::addLazyPath($config['namespace'],SB_APP_PATH);
                $config = \sb\model\load::model('config',true,$config);
                $resolver = \sb\model\load::model('resolver',true,$config->defaultController);
                $resolver->getController($request->controller)->execute($request);
            }
            else
            {
                throw new \sb\model\Exception("Please setup the config.php file");
            }
        }
        catch(\sb\model\Exception $e) {}
        catch(\Exception $e){ throw new \sb\model\Exception($e->getMessage(),$e->getCode(),$e);}
        \sb\model\utils::handleExceptions();
    }
    
}
