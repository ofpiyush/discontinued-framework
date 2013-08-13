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

namespace sambhuti\request;

use sambhuti\core;

/**
 * request Container
 *
 * Container for request and response objects
 * Differentiates between web and cli request and populates the request object appropriately
 * Can be accessed by the string 'request'. As the get method is supposed to give out \sambhuti\core\IData instance
 * every 'request.*' should be marked with \sambhuti\core\IData
 *
 * <code>
 * use sambhuti\core;
 * class test implements core\IContainer {
 *     static $dependencies = array('request','request.request','request.response');
 *     public $requestContainer = null;
 *     public $request = null;
 *     public $response = null;
 *
 *     function __construct(\sambhuti\request\IContainer $container,core\IData $request, core\IData $response) {
 *         $this->requestContainer = $container;
 *         $this->request = $request;
 *         $this->response = $response;
 *     }
 * }
 * </code>
 *
 * @package    Sambhuti
 * @subpackage request
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Container implements IContainer
{

    /**
     * Request
     *
     * Stores request data later passed to controller
     *
     * @var null|\sambhuti\core\IData
     */
    protected $request = null;

    /**
     * Main Conf
     *
     * @var null|\sambhuti\core\IData
     */
    protected $config = null;

    /**
     * Dependencies
     *
     * @static
     * @var array Array of dependency strings
     */
    public static $dependencies = ['config.main'];

    /**
     * Constructor
     *
     * Initializes request and response objects
     *
     */
    public function __construct(core\IData $config)
    {
        $this->config = $config;
        $this->request = new core\Data($this->data());
    }

    /**
     * Get
     *
     * Implements abstract Get method
     * Gives \sambhuti\request\Container::$request
     *
     * @param string|null $type type of object needed
     *
     * @return \sambhuti\core\IData request object
     */
    public function get($type = null)
    {
        return $this->request;
    }

    /**
     * Parse Data
     *
     * Parses REQUEST_URI to determine the command and returns
     * other standard request parameters
     *
     * @return array list of options for request data
     */
    public function data()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $subdomain = '';

        if (false !== ($pos = strpos($uri, '?'))) {
            $uri = substr($uri, 0, $pos);
        }
        $path = $this->config->get('path');
        if (strpos($uri, $path) === 0) {
            $uri = substr($uri, strlen($path));
        }

        if (0 !== ($pos = strpos($_SERVER['HTTP_HOST'], $this->config->get('domain')))) {
            $subdomain = substr($_SERVER['HTTP_HOST'], 0, $pos);
        }

        return array(
            'uri' => trim($uri, '/'),
            'subdomain' => $subdomain,
            'get' => $_GET,
            'post' => $_POST,
            'server' => $_SERVER,
            'file' => $_FILES,
            'cookies' => $_COOKIE
        );
    }
}
