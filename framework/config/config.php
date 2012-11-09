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
use sambhuti\loader;

class config implements iConfig {
    static $dependencies = array('loader');
    private $lazyPaths = array();
    private $defaultPath = '';
    private $confs = array();

    /**
     * @param array $dependencies List of dependencies
     */
    function __construct ( loader\iLoader $loader ) {
        $this->lazyPaths = $loader->getLazyPaths();
        $this->defaultPath = end($this->lazyPaths);
    }

    function get ( $id = null ) {
        if (empty($this->confs[$id])) {
            $config = array();
            foreach ( $this->lazyPaths as $path ) {
                $fullPath = $path . '/config/' . $id . '.php';
                if (file_exists($fullPath)) {
                    include $fullPath;
                }
            }
            $this->confs[$id] = new core\data($config);
        }
        return $this->confs[$id];
    }

    function save ( $id, core\iData $data, $lazyId = null ) {
        $config = $data->getAll();
        $fileString = "<?php" . PHP_EOL;
        foreach ( $config as $key => $value ) {
            $fileString .= '$config["' . $key . '"] = ' . $value . ';' . PHP_EOL;
        }
        $fullPath = $this->defaultPath;
        if ($lazyId !== null && !empty($this->lazyPaths[$lazyId])) {
            $fullPath = $this->lazyPaths[$lazyId];
        }
        $fp = fopen($fullPath . '/config/' . $id . '.php', 'wb');
        if (empty($fp)) {
            throw new \Exception('Config folder not writable');
        }
        fwrite($fp, $fileString);
        fclose($fp);
        $this->confs[$id] = $data;
    }
}
