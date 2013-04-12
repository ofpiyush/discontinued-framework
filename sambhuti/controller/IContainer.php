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
 * controller Container Interface
 *
 * @package    Sambhuti
 * @subpackage controller
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
interface IContainer extends core\IContainer
{

    /**
     * Constructor
     *
     * Should set up not found, home etc from routing
     *
     * @param \sambhuti\core\IData        $routing instance of routing
     * @param \sambhuti\core\ICore        $core    instance of Core
     * @param \sambhuti\loader\IContainer $loader  instance of Loader
     */
    public function __construct(core\IData $routing, core\ICore $core, loader\IContainer $loader);

    /**
     * Process
     *
     * Should process single controller identifier to full name and return instance or null
     *
     * @param string controller name
     *
     * @return null|\sambhuti\controller\IController controller instance
     */
    public function process($controller);
}
