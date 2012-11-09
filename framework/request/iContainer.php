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
 * request Container Interface
 *
 * Container for request and response objects
 * Should differentiate between web and cli request and populate the request object appropriately
 * Should be used for 'request' dependency identifier.
 *
 * @package    Sambhuti
 * @subpackage request
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
interface iContainer extends core\iContainer {

    /**
     * Constructor
     *
     * Should initializes request and response objects
     *
     */
    function __construct ();


    /**
     * Web Request
     *
     * @return array list of options for request data
     */
    function web ();

    /**
     * Command line Request
     *
     * @return array list of options for request data
     */
    function cli ();
}
