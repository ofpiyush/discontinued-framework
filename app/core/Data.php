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
 * data
 *
 * Data object
 *
 * __set always throws exceptions
 *
 * @package    Sambhuti
 * @subpackage core
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Data implements IData
{

    /**
     * Data
     *
     * Stores the data being passed to the class
     *
     * @var array
     */
    protected $data = [];

    /**
     * Constructor
     *
     * Accepts array and stores them in \sambhuti\core\Data::$data
     *
     * @param array $array
     */
    public function __construct(array $array = [])
    {
        $this->data = $array;
    }

    /**
     * Get
     *
     * Accepts list of Keys for a multidimensional array and returns the corresponding value
     * Or null if not present
     *
     * @param $key
     *
     * @return array|mixed|null
     */
    public function get($key)
    {
        $args = func_get_args();
        $tmp = $this->data;
        foreach ($args as $arg) {
            if (array_key_exists($arg, $tmp)) {
                $tmp = $tmp[$arg];
            } else {
                return null;
            }
        }

        return $tmp;
    }

    /**
     * Set
     *
     * Accepts key value pair and stores them in the data array
     * if data already present throws exception
     *
     * @param $key
     * @param $value
     *
     * @return \sambhuti\core\IData
     * @throws \Exception
     */
    public function set($key, $value)
    {
        if (!array_key_exists($key, $this->data)) {
            $this->update($key, $value);

            return $this;
        }
        throw new \Exception('Data ' . $key . ' already set with value ' . $this->data[$key]);
    }

    /**
     * Update
     *
     * Sets key value pair without checking like \sambhuti\core\data::set()
     *
     * @param $key
     * @param $value
     *
     * @return \sambhuti\core\IData
     */
    public function update($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Get All
     *
     * Returns all stored data in \sambhuti\core\Data::$data
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Magic Get
     *
     * Same as \sambhuti\core\Data::get() for 1 argument
     *
     * @param $key
     *
     * @return array|mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magic set
     *
     * Throws exception to let user know they messed up somewhere
     *
     * @param $key
     * @param $value
     *
     * @throws \Exception
     */
    public function __set($key, $value)
    {
        throw new \Exception('Trying to save "' . $value . '" to Config "' . $key . '"
        via __set! Use set($key,$value) / update($key,$value) instead');
    }
}
