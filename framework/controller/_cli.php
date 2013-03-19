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

namespace sambhuti\controller;

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
class _cli extends controller
{

    /**
     * Commands
     *
     * List of commands with it's help
     *
     * @var array
     */
    protected $commands = array(
        'help' => 'Help',
        'app' => 'Create new app'
    );

    /**
     * Command line index
     *
     * Acts as command line router for now (may be refactored to \sambhuti\controller\controller::get() later)
     *
     * @param array $args
     */
    function index(array $args = array())
    {
        $argv = $this->request->get('argv');
        //Get rid of filename
        array_shift($argv);
        if (count($argv)) {
            $method = strtolower(array_shift($argv));
        } else {
            $method = 'help';
        }
        if (is_callable(array($this, $method))) {
            $this->$method($argv);
        } else {
            $this->_404($method);
        }
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
    function help(array $args = array())
    {
        $commands = $this->commands;
        if (0 != count($args)) {
            if (array_key_exists($args[0], $this->commands)) {
                $commands = array($args[0] => $this->commands[$args[0]]);
            } else {
                echo "\033[1;41;37m$args[0] Command is not in the help\033[0m" . PHP_EOL;
            }
        }
        //8.3.117  SGR - SELECT GRAPHIC RENDITION
        // \033[<notation1>[;<notation2>;<notation3>;...;]m<string here>\033[0m
        //eg: \033[1;40;32;4;9m== Sambhuti Help ==\033[0m
        //for green underlined bold text on black background with a strike through
        echo "\033[1;40;37m== Sambhuti Help ==" . PHP_EOL . PHP_EOL . "\033[0;40m"; //bold white text on black background
        foreach ($commands as $command => $man) {
            echo "\033[1;40;32m" . $command . "\033[0m\033[0;40;37m " . $man . PHP_EOL . "\033[0m";
        }
        echo PHP_EOL;
    }

    /**
     * App
     *
     * @todo implement app function
     *
     * @param array $args
     */
    function app(array $args = array())
    {
        echo "\033[0;40;37mUnimplemented function\033[0m" . PHP_EOL;
    }

    /**
     * notFound
     *
     * @param string $command
     */
    function _404($command)
    {
        echo "\033[1;41;37m" . $command . " command not recognized\033[0m" . PHP_EOL;
        $this->help();
    }
}
