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
 * data Interface
 *
 * Basic functions you can be sure of to be present in a data object
 *
 * __set is there to throw exceptions
 *
 * @package    Sambhuti
 * @subpackage core
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
interface IData
{

    /**
     * Constructor
     *
     * Should accept array list of arguments and store them
     *
     * @param array $array
     */
    public function __construct(array $array = array());

    /**
     * Get
     *
     * Should accept the key identifier to the array.
     * Optionally should accept list of arguments for a multidimensional array
     *
     * @param $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Set
     *
     * Should accept key value pair to be stored.
     * Should throw exception if key already stored.
     *
     * @param $key
     * @param $value
     *
     * @throws \Exception
     *
     * @return \sambhuti\core\IData
     */
    public function set($key, $value);

    /**
     * Update
     *
     * Should accept key value pair to be updated
     *
     * @param $key
     * @param $value
     *
     * @return \sambhuti\core\IData
     */
    public function update($key, $value);

    /**
     * Get All
     *
     * Should return all key value pairs in the array
     *
     * @return array
     */
    public function getAll();

    /**
     * Magic Get
     *
     * Should function the same as \sambhuti\core\IData::get()
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key);

    /**
     * Magic set
     *
     * Should always throw exception
     *
     * @throws \Exception
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function __set($key, $value);
}
