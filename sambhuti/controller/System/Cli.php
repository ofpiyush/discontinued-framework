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
 * @package   Sambhuti
 * @author    Piyush <piyush@cio.bz>
 * @license   http://www.gnu.org/licenses/gpl.html
 * @copyright 2012 Piyush
 */

namespace sambhuti\controller\System;

use sambhuti\controller\Controller;
use sambhuti\core;

/**
 * Command line Controller
 *
 * implements command line functions
 *
 * @package    Sambhuti
 * @subpackage controller
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 *
 */
class Cli extends Controller
{

    /**
     * Commands
     *
     * List of commands with it's help
     *
     * @var array
     */
    protected $commands = [
        'help' => 'Help',
        'app' => 'Create new app'
    ];
    protected $helpers = ['tag'];

    /**
     * Command line index
     *
     * Acts as command line router for now (may be refactored to \sambhuti\controller\controller::get() later)
     */
    public function index()
    {
        $argv = $this->request->get('argv');
        //Get rid of filename
        array_shift($argv);
        if (count($argv)) {
            $method = strtolower(array_shift($argv));
        } else {
            $method = 'help';
        }
        if (is_callable([$this, $method]) && !in_array($method,$this->helpers)) {
            $this->$method($argv);
        } else {
            $this->notFound($method);
        }
        echo PHP_EOL;
    }

    /**
     * Help Controller
     *
     * Gives a list of help responses
     *
     * @todo stop the echo and use response with templating and SGR (SELECT GRAPHIC RENDITION) when available
     * @link http://www.ecma-international.org/publications/files/ECMA-ST/Ecma-048.pdf
     *
     * @param array $args
     */
    public function help(array $args = [])
    {
        $commands = $this->commands;
        if (0 != count($args)) {
            if (array_key_exists($args[0], $this->commands)) {
                $commands = array($args[0] => $this->commands[$args[0]]);
            } else {
                echo PHP_EOL.$this->tag(PHP_EOL."$args[0] Command is not in the help".PHP_EOL,"error",1).PHP_EOL;
            }
        }
        echo $this->tag("== Sambhuti Help ==" . PHP_EOL . PHP_EOL,"normal",1);
        foreach ($commands as $command => $man) {
            echo $this->tag($command."\t","success",1).$this->tag($man.PHP_EOL);
        }
    }

    /**
     * App
     *
     * @todo implement app function
     *
     * @param array $args
     *
     * @return bool
     */
    public function app(array $args = [])
    {
        if(!is_writable(getcwd())) {
            echo $this->tag(PHP_EOL.getcwd()." is not writable".PHP_EOL,"error",1);
            return false;
        }
        if(0 == count($args)) {
            echo $this->tag(PHP_EOL."Please pass a second argument".PHP_EOL,"error",1);
            return false;
        }
        if(is_dir($args[0])) {
           echo $this->tag(PHP_EOL.getcwd()."/".$args[0]." already exists!".PHP_EOL,"error",1);
           return false;
        }
        mkdir($args[0]);
            echo $this->tag(PHP_EOL.getcwd()."/".$args[0]." dir made!".PHP_EOL,"success",1);
        return true;
    }

    /**
     * notFound
     *
     * @param string $command
     */
    public function notFound($command)
    {
        echo $this->tag(PHP_EOL.$command . " command not recognized".PHP_EOL,"error",1);
        $this->help();
    }

    protected function tag($string,$type="normal", $bold = 0) {
        //8.3.117  SGR - SELECT GRAPHIC RENDITION
        // \033[<notation1>[;<notation2>;<notation3>;...;]m<string here>\033[0m
        //eg: \033[1;40;32;4;9m== Sambhuti Help ==\033[0m
        //for green underlined bold text on black background with a strike through
        $tags =[
            'normal' => "\033[".$bold.";40;37m",
            'success' =>"\033[".$bold.";40;32m",
            'error' => "\033[".$bold.";41;37m",
            'end' =>"\033[0m"
        ];
        return $tags[$type].$string.$tags['end'];
    }
}
