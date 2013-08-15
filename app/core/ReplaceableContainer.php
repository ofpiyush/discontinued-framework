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
 * Replaceable Container
 *
 * Base Abstract class for replacing Container
 *
 * @package    Sambhuti
 * @subpackage core
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
abstract class ReplaceableContainer implements IContainer
{
    /**
     * Instance
     *
     * @var null|object
     */
    protected $instance = null;

    /**
     * Instance
     * @return null|object
     */
    public function instance()
    {
        return $this->instance;
    }

    /**
     * Get
     *
     * @param null|string $id
     * @throws \Exception
     * @return void
     */
    public function get($id = null)
    {
        throw new \Exception("Calling $id on ".get_called_class());
    }

}
