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

use sambhuti\controller\Controller;

/**
 * Error
 *
 * Implements all errors
 *
 * @package    Sambhuti
 * @subpackage controller
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Error extends Controller
{

    /**
     * Unknown error index
     *
     * @param array $args
     */
    public function index(array $args = [])
    {
        echo $this->request->get('command') . " Unknown Error";
    }

    /**
     * 404 not found page
     */
    public function notFound()
    {
        echo $this->request->get('command') . " Not Found";
    }

    /**
     * 403 forbidden access page
     */
    public function forbidden()
    {
        echo $this->request->get('command') . " Forbidden Access";
    }
}
