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
abstract class controller implements iController
{

    /**
     * Dependencies
     *
     * Should at least have these two if overridden in child classes
     *
     * @static
     * @var array Array of dependency strings
     */
    static $dependencies = array('request.request', 'request.response');

    /**
     * Request
     *
     * @var null|\sambhuti\core\iData $request
     */
    protected $request = null;

    /**
     * Response
     *
     * @var null|\sambhuti\core\iData $response
     */
    protected $response = null;

    /**
     * Constructor
     *
     * Should always be called from child constructors
     *
     * @param \sambhuti\core\iData $request
     * @param \sambhuti\core\iData $response
     */
    function __construct(core\iData $request, core\iData $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Get
     *
     * Always gives back response
     *
     * @param null $id
     *
     * @return \sambhuti\core\iData Response
     */
    function get($id = null)
    {
        return $this->response;
    }

    /**
     * Index
     *
     * Should implement as index page for all controllers
     *
     * @param array $args
     *
     * @return mixed|void
     */
    abstract function index(array $args = array());
}
