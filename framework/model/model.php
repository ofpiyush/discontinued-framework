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

/**
 * Abstract model class
 *
 * Provides a set of tools for child model classes
 * Inspired from ThinkUp PDODAO model
 * Adding other authors from there for attribution
 *
 * @package    Sambhuti
 * @subpackage model
 * @author     Christoffer Viken <christoffer@viken.me>
 * @author     Mark Wilkie
 * @author     Gina Trapani <ginatrapani[at]gmail[dot]com>
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 * @link       https://github.com/ginatrapani/ThinkUp/blob/master/webapp/_lib/dao/class.PDODAO.php
 */
abstract class model {

    /**
     * Connection
     *
     * Stores PDO connection
     *
     * @var null|\PDO
     */
    protected $conn = null;

    /**
     * Constructor
     *
     * Stores PDO connection in \sambhuti\model\model::$conn
     *
     * @param \PDO $conn
     */
    function __construct ( \PDO $conn ) {
        $this->conn = $conn;
    }

    /**
     * Execute
     *
     * Executes the query with Bindings and returns Statement
     *
     * @todo do some real exception handling
     *
     * @param  string $sql
     * @param array   $bindings
     *
     * @return \PDOStatement
     */
    protected function execute ( $sql, array $bindings = array() ) {
        /** @var $stmt \PDOStatement */
        $stmt = $this->conn->prepare($sql);
        try {
            if (is_array($bindings)) {
                $stmt->execute($bindings);
            } else {
                $stmt->execute();
            }
        } catch (\PDOException $e) {
        }
        return $stmt;
    }

    /**
     * Fetch
     *
     * Gets a single row and closes cursor.
     *
     * @param \PDOStatement $stmt
     *
     * @return mixed
     */
    protected function fetch ( \PDOStatement $stmt ) {
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }

    /**
     * Fetch All
     *
     * Gets multiple rows and closes cursor.
     *
     * @param \PDOStatement $stmt
     *
     * @return array
     */
    protected function fetchAll ( \PDOStatement $stmt ) {
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }

    /**
     * Update Count
     *
     * Get the number of updated rows
     *
     * @param \PDOStatement $stmt
     *
     * @return int Update Count
     */
    protected function updateCount ( \PDOStatement $stmt ) {
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }

    /**
     * Get Count
     *
     * Gets the result returned by a count query
     * (value of col count on first row)
     *
     * @param \PDOStatement $stmt
     *
     * @return int
     */
    protected function getCount ( \PDOStatement $stmt ) {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $this->fetch($stmt);
        if (!$row or !isset($row['count'])) {
            return 0;
        } else {
            return (int)$row['count'];
        }
    }

    /**
     * Insert Id
     *
     * Gets data "insert ID" from a statement
     *
     * @param \PDOStatement $stmt
     *
     * @return int
     */
    protected function insertId ( \PDOStatement $stmt ) {
        $count = $this->updateCount($stmt);
        $id = $this->conn->lastinsertId();
        return ($count > 0 && $id > 0) ? $id : 0;
    }

    /**
     * is Returned
     *
     * Gets whether a statement returned anything
     *
     * @param \PDOStatement $stmt
     *
     * @return bool
     */
    protected function isReturned ( \PDOStatement $stmt ) {
        $row = $this->fetch($stmt);
        return ($row && count($row) > 0);
    }

    /**
     * Assoc Row
     *
     * Gets the first returned row as array
     *
     * @param \PDOStatement $stmt
     *
     * @return array Array with named keys
     */
    protected function assocRow ( \PDOStatement $stmt ) {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetch($stmt);
    }

    /**
     * Assoc Rows
     *
     * Gets the rows returned by a statement as array with arrays
     *
     * @param \PDOStatement $stmt
     *
     * @return array Array of arrays
     */
    protected function assocRows ( \PDOStatement $stmt ) {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetchAll($stmt);
    }

    /**
     * Object Row
     *
     * Returns the first row as an object
     *
     * @param \PDOStatement $stmt
     * @param               $class
     *
     * @return object
     */
    protected function objRow ( \PDOStatement $stmt, $class ) {
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $this->fetch($stmt);
    }

    /**
     * Object rows
     *
     * Gets the rows returned by a statement as array of objects.
     *
     * @param \PDOStatement $stmt
     * @param               $class
     *
     * @return array Array of objects
     */
    protected function objRows ( \PDOStatement $stmt, $class ) {
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $this->fetchAll($stmt);
    }

    /**
     * Disconnector
     *
     * Caution! This will disconnect for ALL DAOs
     */
    function disconnect () {
        $this->conn = null;
    }
}
