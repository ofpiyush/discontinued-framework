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

use sambhuti\core;
use sambhuti\loader;

/**
 * Controller Container
 *
 * Stores all controllers
 *
 * @package    Sambhuti
 * @subpackage controller
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Container implements IContainer
{

    /**
     * Dependencies
     *
     * @static
     * @var array Array of dependency strings
     */
    static $dependencies = ['config.routing', 'core', 'loader'];

    /**
     * Core
     *
     * Instance of Core
     *
     * @var null|\sambhuti\core\ICore $core
     */
    protected $core = null;

    /**
     * Routing Config
     *
     * @var null|\sambhuti\core\IData $routing
     */
    protected $routing = null;

    /**
     * Loader
     *
     * Instance of Loader
     *
     * @var null|\sambhuti\loader\IContainer $loader
     */
    protected $loader = null;

    /**
     * Not Found Controller
     *
     * @var null|\sambhuti\controller\IController
     */
    protected $notFound = null;

    /**
     * Controller instances
     *
     * @var array
     */
    protected $controllers = [];

    /**
     * Constructor
     *
     * Sets up not found, home etc from routing
     *
     * @param \sambhuti\core\IData $routing
     * @param \sambhuti\core\ICore $core
     * @param \sambhuti\loader\IContainer $loader
     */
    function __construct(core\IData $routing, core\ICore $core, loader\IContainer $loader)
    {
        $this->routing = $routing;
        $this->core = $core;
        $this->loader = $loader;
        $this->notFound = $this->process($routing->get('404'));
        $this->controllers['home'] = $this->process($routing->get('home'));
    }

    /**
     * Get
     *
     * Takes in a command in format controller/method/arg1/arg2...
     * and calls controller::method(array(arg1,arg2...))
     * if exists or returns not found controller
     *
     * @param null $command
     *
     * @return \sambhuti\controller\IController
     */
    function get($command = null)
    {
        //@todo: start fixing 5.4 move from here
        if (empty($command)) {
            return $this->get('home');
        }
        /** @todo allow better overrides */
        if (null !== $this->routing->get($command)) {
            $command = $this->routing->get($command);
        }
        $args = explode('/', $command);
        $controller = array_shift($args);
        $method = !empty($args) ? array_shift($args) : 'index';

        if (strpos($controller,'System_') !==false && false === ISCLI) {
            $object = $this->notFound;
            $method = 'forbidden';
        } else {
            $object = $this->process($controller);
        }
        if (null === $object || !is_callable([$object, $method])) {
            $object = $this->notFound;
            $method = 'notFound';
        }
        $object->$method($args);

        return $object;
    }

    /**
     * Process
     *
     * Processes single controller identifier to full name and returns instance or null
     *
     * @param string controller name
     *
     * @return null|\sambhuti\controller\iController controller instance
     */
    function process($controller)
    {
        if (empty($this->controllers[$controller])) {
            $class = $this->loader->fetch('controller' . '\\' . $controller);
            if (null !== $class) {
                $this->controllers[$controller] = $this->core->process($class);
            } else {
                $this->controllers[$controller] = null;
            }
        }
        return $this->controllers[$controller];
    }
}
