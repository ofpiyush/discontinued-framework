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
    public static $dependencies = ['config.routing', 'core', 'loader'];

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
     * Routes
     *
     * @var null|array
     */
    protected $routes = null;

    /**
     * System Routes
     *
     * @var null|array
     */
    protected $system = null;

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
    protected $error = null;

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
     * @param \sambhuti\core\IData        $routing
     * @param \sambhuti\core\ICore        $core
     * @param \sambhuti\loader\IContainer $loader
     */
    public function __construct(core\IData $routing, core\ICore $core, loader\IContainer $loader)
    {
        $this->routing = $routing;
        $this->routes = $routing->get("routes");
        $this->system = $routing->get("system");
        $this->core = $core;
        $this->loader = $loader;
        $this->error = $this->process($this->system['error']);
        $this->controllers['home'] = $this->process($this->system['home']);
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
    public function get($command = null)
    {
        if (empty($command)) {
            return $this->get('home');
        }
        $args = explode('/', $command);
        $controller = array_shift($args);
        $method = !empty($args) ? array_shift($args) : 'index';
        $this->mapRoute($controller, $method);
        if (false !== strpos($controller, 'System') && false === ISCLI) {
            $object = $this->error;
            $method = 'forbidden';
        } else {
            $object = $this->process($controller);
        }
        if (null === $object || !is_callable([$object, $method])) {
            $object = $this->error;
            $method = 'notFound';
        }
        call_user_func_array([$object, $method], $args);

        return $object;
    }

    public function mapRoute(&$controller, &$method)
    {
        $mapping = null;
        if (!empty($this->routes[$controller . '/' . $method])) {
            $mapping = $this->routes[$controller . '/' . $method];
        } elseif (!empty($this->routes[$controller])) {
            $mapping = $this->routes[$controller];
        }
        if (null !== $mapping) {
            $mapping = explode('::', $mapping);
            $controller = $mapping[0];
            $method = !empty($mapping[1]) ? $mapping[1] : $method;
        }
        //Parse and load default style
        $controller = ucfirst($controller);
        $method = core\Utils::camelCase($method);
    }

    /**
     * Process
     *
     * Processes single controller identifier to full name and returns instance or null
     *
     * @param string controller name
     *
     * @return null|\sambhuti\controller\IController controller instance
     */
    public function process($controller)
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
