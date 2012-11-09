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
 * Interface Request
 *
 * Container for request and response objects
 * Differentiates between web and cli request and populates the request object appropriately
 * Can be accessed by the string 'request.*'.
 *
 * <code>
 * use sambhuti\core;
 * class test implements core\iContainer {
 *     static $dependencies = array('request.request','request.response');
 *     public $request = null;
 *     public $response = null;
 *
 *     function __construct(core\iData $request, core\iData $response) {
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
interface iRequest extends core\iContainer {

    /**
     * Constructor
     *
     * Determines Command line or web request based on ISCLI constant and executes
     * the relevant method.
     * Initializes request and response objects
     *
     */
    function __construct ();


    /**
     * Web Request
     *
     * Parses REQUEST_URI to determine the command and returns
     * other standard request parameters
     *
     * @return array list of options for request data
     */
    function web ();

    /**
     * Command line Request
     *
     * Command is always 'cli' gives 'argv' and 'argc' for request
     *
     * @return array list of options for request data
     */
    function cli ();
}
