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

use sambhuti\core;
use sambhuti\view\IView;

/**
 * Controller abstract
 *
 * Makes the way for all child controllers
 *
 * @package    Sambhuti
 * @subpackage controller
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
abstract class Controller
{

    /**
     * Dependencies
     *
     * Should at least have these two if overridden in child classes
     *
     * @static
     * @var array Array of dependency strings
     */
    public static $dependencies = ['core'];
    /**
     * Request
     *
     * @var null|\sambhuti\core\IData $request
     */
    protected $request = null;

    /**
     * Core
     * @var null|\sambhuti\core\Core
     */
    private $core = null;
    /**
     * View
     *
     * @var null|\sambhuti\view\IView
     */
    protected $view = null;

    /**
     * Constructor
     *
     * Should always be called from child constructors
     *
     * @param \sambhuti\core\ICore
     */
    public function __construct(core\ICore $core)
    {
        $this->core = $core;
        $this->request = $core->get('request');
        $this->view = $core->get('view');
        $this->initialize();
    }

    /**
     * Get
     * Wrapper for core
     * 
     * @param $id
     * @return object|null
     */
    protected function get($id)
    {
        return $this->core->get($id);
    }

    abstract protected function initialize();

    /**
     * Index
     *
     * Should implement as index page for all controllers
     *
     * @param \sambhuti\core\IData $args
     * @return mixed|void
     */
    abstract public function index(core\IData $args);
}
