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

namespace sambhuti\core;

/**
 * Boot Class
 *
 * @package    Sambhuti
 * @subpackage core
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class boot {

    /**
     * Request Container
     *
     * @var null|\sambhuti\request\iContainer
     */
    private $request = null;

    /**
     * Constructor
     *
     * Sets up everything
     *
     * @param \sambhuti\core\core $core
     */
    function __construct ( core $core ) {
        $this->core = $core;
        $this->request = $core->get('request');
    }

    /**
     * Go
     *
     * Heart of Sambhuti's processing
     *
     * @todo add templating here or in a new method
     *
     * @return \sambhuti\core\iData Response
     */
    function go () {
        $this->core->get('controller')->get($this->request->get()->get('command'));
        return $this->request->get('response');
    }
}
