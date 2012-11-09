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

namespace sambhuti\config;

use sambhuti\core;
use \sambhuti\loader;

/**
 * config Container Interface
 *
 * All config files should be loaded and stored by the class implementing this
 * interface
 *
 * @package    Sambhuti
 * @subpackage config
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
interface iContainer extends core\iContainer {

    /**
     * Constructor
     *
     * @param \sambhuti\loader\iContainer $loader
     */
    function __construct ( loader\iContainer $loader );


    /**
     * Save
     *
     * @param                      $id
     * @param \sambhuti\core\iData $data
     * @param null                 $lazyId
     *
     * @return void|\sambhuti\config\iContainer
     */
    function save ( $id, core\iData $data, $lazyId = null );
}
