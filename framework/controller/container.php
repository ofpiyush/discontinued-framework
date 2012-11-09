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
class container implements iContainer {

    static $dependencies = array('config.routing', 'core', 'loader');
    /**
     * @var null|\sambhuti\core\iCore $core
     */
    protected $core = null;
    /**
     * @var null|\sambhuti\core\iData $routing
     */
    protected $routing = null;
    /** @var null|\sambhuti\loader\iContainer $loader */
    protected $loader = null;
    /** @var null|\sambhuti\controller\iController */
    protected $notFound = null;
    protected $controllers = array();


    function __construct ( core\iData $routing, core\iCore $core, loader\iContainer $loader ) {
        $this->routing = $routing;
        $this->core = $core;
        $this->loader = $loader;
        $this->notFound = $this->process($routing->get('404'));
        $this->controllers['home'] = $this->process($routing->get('home'));
    }

    function get ( $command = null ) {
        if (empty($command)) {
            return $this->get('home');
        }
        if (null !== $this->routing->get($command)) {
            $command = $this->routing->get($command);
        }
        $args = explode('/', $command);
        $controller = array_shift($args);
        $method = !empty($args) ? array_shift($args) : 'index';

        if ('_' === $controller[0] && false === ISCLI) {
            $object = $this->notFound;
            $method = '_403';
        } else {
            $object = $this->process($controller);
        }
        if (null === $object || !is_callable(array($object, $method))) {
            $object = $this->notFound;
            $method = '_404';
        }
        $object->$method($args);

        return $object;
    }

    /**
     * @param string controller name
     *
     * @return null|\sambhuti\controller\iController controller instance
     */
    function process ( $controller ) {
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
