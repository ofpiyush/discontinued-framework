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

abstract class model {

    protected $conn = null;

    function __construct ( \PDO $conn ) {
        $this->conn = $conn;
    }

    /**
     * Executes the query with Bindings and returns Statement
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

    protected function fetch ( \PDOStatement $stmt ) {
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }

    protected function fetchAll ( \PDOStatement $stmt ) {
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }

    protected function updateCount ( \PDOStatement $stmt ) {
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }

    protected function getCount ( \PDOStatement $stmt ) {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $this->fetch($stmt);
        if (!$row or !isset($row['count'])) {
            return 0;
        } else {
            return (int)$row['count'];
        }
    }

    protected function insertId ( \PDOStatement $stmt ) {
        $count = $this->updateCount($stmt);
        $id = $this->conn->lastinsertId();
        return ($count > 0 && $id > 0) ? $id : 0;
    }

    protected function isReturned ( \PDOStatement $stmt ) {
        $row = $this->fetch($stmt);
        return ($row && count($row) > 0);
    }

    protected function assocRow ( \PDOStatement $stmt ) {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetch($stmt);
    }

    protected function assocRows ( \PDOStatement $stmt ) {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetchAll($stmt);
    }

    protected function objRow ( \PDOStatement $stmt, $class ) {
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $this->fetch($stmt);
    }

    protected function objRows ( \PDOStatement $stmt, $class ) {
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $this->fetchAll($stmt);
    }

    function disconnect () {
        $this->conn = null;
    }
}
