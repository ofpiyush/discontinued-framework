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
 * Can be accessed by the string 'request'. As the get method is supposed to give out \sambhuti\core\iData instance
 * every 'request.*' should be marked with \sambhuti\core\iData
 *
 * <code>
 * use sambhuti\core;
 * class test implements core\iContainer {
 *     static $dependencies = array('request','request.request','request.response');
 *     public $requestContainer = null;
 *     public $request = null;
 *     public $response = null;
 *
 *     function __construct(\sambhuti\request\iContainer $container,core\iData $request, core\iData $response) {
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
     * Response
     *
     * Stores response data later passed to controller
     *
     * @var null|\sambhuti\core\IData
     */
    protected $response = null;

    /**
     * Constructor
     *
     * Determines Command line or web request based on ISCLI constant and executes
     * the relevant method.
     * Initializes request and response objects
     *
     */
    public function __construct()
    {
        $data = ISCLI ? $this->cli() : $this->web();
        $this->request = new core\Data($data);
        $this->response = new core\Data();

    }

    /**
     * Get
     *
     * Implements abstract Get method
     * Gives \sambhuti\request\Container::$response on type 'response' and
     * \sambhuti\request\Container::$request otherwise
     *
     * @param string|null $type type of object needed
     *
     * @return \sambhuti\core\IData request or response object
     */
    public function get($type = null)
    {
        if ($type === 'response') {
            return $this->response;
        }

        return $this->request;
    }

    /**
     * Web Request
     *
     * Parses REQUEST_URI to determine the command and returns
     * other standard request parameters
     *
     * @return array list of options for request data
     */
    public function web()
    {
        $request_uri = $_SERVER["REQUEST_URI"];
        if (false !== ($pos = strpos($request_uri, '?'))) {
            $request_uri = substr($request_uri, 0, $pos);
        }
        $path = dirname($_SERVER["SCRIPT_NAME"]);
        $command = $request_uri;
        if (strpos($request_uri, $path) === 0) {
            $command = substr($request_uri, strlen($path));
        }

        return array(
            'command' => trim($command, "/"),
            'get' => $_GET,
            'post' => $_POST,
            'server' => $_SERVER,
            'file' => $_FILES,
            'cookies' => $_COOKIE
        );
    }

    /**
     * Command line Request
     *
     * Command is always 'cli' gives 'argv' and 'argc' for request
     *
     * @return array list of options for request data
     */
    public function cli()
    {
        return array('command' => 'cli', 'server' => $_SERVER, 'argv' => $_SERVER['argv'], 'argc' => $_SERVER['argc']);
    }
}
