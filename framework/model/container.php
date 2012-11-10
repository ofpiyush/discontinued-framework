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

/**
 * model Container
 *
 * Connects to db and stores all models
 *
 * @todo       add support for master slave
 *
 * @package    Sambhuti
 * @subpackage model
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class container implements iContainer {

    /**
     * Dependencies
     *
     * @static
     * @var array Array of dependency strings
     */
    static $dependencies = array('loader', 'config.database');

    /**
     * Connection
     *
     * Stores PDO connection object
     *
     * @var null|\PDO
     */
    protected $connection = null;

    /**
     * All types
     *
     * Stores all db type data name identifiers
     *
     * @todo move this to a config sometime
     *
     * @var array
     */
    protected $allTypes = array('mysql' => 'MySQL');

    /**
     * Type
     *
     * Stores Db type identifier
     * Eg: mysql
     *
     * @var string
     */
    protected $type = '';

    /**
     * Loader
     *
     * Instance of loader
     *
     * @var null|\sambhuti\loader\iContainer
     */
    protected $loader = null;

    /**
     * Models
     *
     * Stores all model objects
     *
     * @var array
     */
    protected $models = array();

    /**
     * Constructor
     *
     * Connects to the mysql server
     *
     * @todo may be have connection separate later if the need be?
     * @todo do some real exception handling :P
     *
     * @param \sambhuti\loader\iContainer $loader
     * @param \sambhuti\core\iData        $databaseConfig database config
     */
    function __construct ( loader\iContainer $loader, core\iData $databaseConfig ) {
        $this->loader = $loader;
        $type = strtolower($databaseConfig->get('type'));
        $this->type = $this->allTypes[$type];
        $dsn = $type . ":dbname=" . $databaseConfig->get('select') . ";host=" . $databaseConfig->get('database') . ";charset=utf8";
        try {
            $this->connection = new \PDO($dsn, $databaseConfig->get('username'), $databaseConfig->get('password'));
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
        }
    }

    /**
     * Get
     *
     * Accepts string identifier and returns model of that
     *
     * @param null|string $id
     *
     * @fixme make model interface and @return that instead.
     * @return \sambhuti\model\model
     * @throws \Exception
     */
    function get ( $id = null ) {
        if (empty($this->models[$id])) {
            $class = $this->loader->fetch('model\\' . $this->type . '\\' . $id);
            if (null === $class) {
                $this->models[$id] = new $class($this->connection);
            } else {
                throw new \Exception("Cannot find model " . $id);
            }
        }
        return $this->models[$id];
    }
}
