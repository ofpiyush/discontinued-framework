<?php
/**
 * Sambhuti
 * Copyright (C) 2012-2013 Piyush
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
 */

namespace sambhuti\view;

use Phalcon\DI;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine;
use sambhuti\core;

/**
 * Volt View
 *
 * @package    Sambhuti
 * @subpackage view
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Volt implements IView
{
    public static $dependencies = ['config.view'];
    /**
     * Volt Instance
     *
     * @var null|\Phalcon\Mvc\View;
     */
    protected $instance = null;
    /**
     * View Config
     *
     * @var null|core\IData
     */
    protected $config = null;
    protected $data = '';

    public function __construct(core\IData $viewConf)
    {
        if (!extension_loaded('phalcon')) {
            throw new \Exception("Phalcon Not loaded");
        }
        $di = new DI();
        $di->set(
            'view',
            function () use ($viewConf) {

                $view = new View();

                $view->setViewsDir($viewConf->get("view_dir"));
                $view->disableLevel(
                    [
                        View::LEVEL_LAYOUT => true,
                        View::LEVEL_MAIN_LAYOUT => true
                    ]
                );
                $view->registerEngines(
                    [
                        ".volt" => function ($view, $di) use ($viewConf) {
                            $volt = new Engine\Volt ($view, $di);

                            $volt->setOptions(
                                [
                                    //Try to make same file structure in generated area
                                    "compiledPath" => function ($path) use ($viewConf) {
                                        $view_dir = $viewConf->get("view_dir");
                                        //Check for view dir and remove it from file path
                                        if (strpos($path, $view_dir) === 0) {
                                            $path = substr($path, strlen($view_dir));
                                        }
                                        //Get all directories and filename
                                        $paths = explode(DIRECTORY_SEPARATOR, $path);
                                        $file = array_pop($paths);
                                        //check if directory exists, if not create it
                                        $current = rtrim($viewConf->get("compile_dir"), "/");
                                        foreach ($paths as $single) {
                                            $current .= "/$single";
                                            if (!is_dir($current)) {
                                                mkdir($current);
                                            }
                                        }
                                        return $current . "/" . $file . ".php";
                                    },
                                    "compileAlways" => (bool)(!$viewConf->get("smart_reload"))
                                ]
                            );
                            $volt->getCompiler()->addFunction(
                                'vars',
                                function()
                                {
                                    return "get_defined_vars()";
                                }
                            );
                            return $volt;
                        }
                    ]
                );
                return $view;
            }
        );
        $di->set('url',function() {
            $url = new Url();
            $url->setBasePath(CIO_ROOT_PATH.'public/');
            return $url;
        });
        $this->config = $viewConf;
        $this->instance = $di->get("view");
    }

    public function set($key, $value)
    {
        $this->instance->setVar($key, $value);
    }

    public function render($path)
    {
        list($key,$value) = array_merge(explode("/",$path,2), array( false ) );
        if(empty($value)) {
            $value = $key;
            $key="";
        }
        $view = $this->instance;
        $view->start();
        $view->render($key, $value);
        $view->finish();
        $this->data = $view->getContent();
    }

    function getData()
    {
        return $this->data;
    }
}