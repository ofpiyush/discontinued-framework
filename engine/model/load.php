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
class load
{
    private static $models      = array();
    private static $lazyPaths   = array();
    public static function dao($class,$new = false,$args = array())
    {
        $config = self::model('config');
        $model='dao\\'.$config->get('db','type').'\\'.$class;
        try
        {
            return self::model($model,$new,$args);
        }
        catch(Exception $e){}
    }

    public static function view($name,$args = array())
    {
        if(!isset(self::$models['twigEnv']['instance']))
            self::$models['twigEnv']['instance'] = new \Twig_Environment(self::model('twigLoader'), array('cache'=> SB_APP_PATH."cache/twig"));
        return (is_array($args) && count($args))? self::model('twigEnv')->loadTemplate($name)->render($args) : self::model('twigEnv')->loadTemplate($name) ;
    }
    
    public static function model($class,$new = false,$args = array())
    {
        if(! $new && array_key_exists($class,self::$models))
        {
            if(array_key_exists('instance',self::$models[$class]))
                return self::$models[$class]['instance'];
        }
        else
        {
            $name = self::fetch('model',$class);
            if($name)
            {
                self::$models[$class]['reflection'] = new \ReflectionClass($name);
                return self::$models[$class]['instance'] = self::$models[$class]['reflection']->newInstance($args);
            }
            else
            {
                throw new Exception("No Model for '$class' found");
            }
        }
    }
    
    
    
    public static function fetch($type,$classname)
    {
        foreach(array_reverse(self::$lazyPaths) as $ns => $path)
        {
            if(self::checkRequire($path.'/'.$type.'/'.$classname))
                return $ns.'\\'.$type.'\\'.$classname;
        }
        return false;
    }
    public static function checkRequire($path)
    {
        $fullpath = str_replace('\\','/',$path).'.php';
        if(file_exists($fullpath))
            {
                require_once($fullpath);
                return true;
            }
            return false;
    }
    public static function auto($class)
    {
        if(class_exists($class))
            return true;
        $array = explode('\\',$class);
        if(array_key_exists($array[0],self::$lazyPaths))
        {
            $array[0] = self::$lazyPaths[$array[0]];
            return self::checkRequire(implode($array,'/'));
        }
        return false;
    }
    public static function register()
    {
        \Twig_Autoloader::register();
        self::addLazyPath('sb',SB_ENGINE_PATH);
        spl_autoload_register(array(__CLASS__, 'auto' ),false,true);
        
    }
    public static function unreg()
    {
        self::$lazy_paths=array();
        spl_autoload_unregister(array(__CLASS__, 'auto' ));
    }
    public static function addLazyPath($namespace,$path)
    {
        $path = rtrim($path,'/');
        if(isset(self::$models['twigLoader']['instance']))
            self::model('twigLoader')->setPaths($path."/view");
        else
            self::$models['twigLoader']['instance'] = new \Twig_Loader_Filesystem($path."/view");
        self::$lazyPaths[$namespace] = $path;
    }
}
