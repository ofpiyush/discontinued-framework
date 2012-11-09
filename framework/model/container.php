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

namespace sambhuti\model;
use sambhuti\core;
use sambhuti\loader;

class container implements iContainer {

    static $dependencies = array('loader', 'config.database');
    /** @var null|\PDO */
    private $connection = null;
    private $allTypes = array('mysql' => 'MySQL');
    private $type = '';
    /** @var null|\sambhuti\loader\iContainer */
    private $loader = null;
    private $instances = array();

    function __construct ( loader\iContainer $loader, core\iData $data ) {
        $this->loader = $loader;
        $type = strtolower($data->get('type'));
        $this->type = $this->allTypes[$type];
        $dsn = $type . ":dbname=" . $data->get('select') . ";host=" . $data->get('database') . ";charset=utf8";
        try {
            $this->connection = new \PDO($dsn, $data->get('username'), $data->get('password'));
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
        }
    }

    function get ( $id = null ) {
        if (empty($this->instances[$id])) {
            $class = $this->loader->fetch('model\\' . $this->type . '\\' . $id);
            if (null === $class) {
                $this->instances[$id] = new $class($this->connection);
            } else {
                throw new \Exception("Cannot find model " . $id);
            }
        }
        return $this->instances[$id];
    }
}
