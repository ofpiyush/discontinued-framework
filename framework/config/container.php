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

/**
 * config Container
 *
 * All config files are loaded and stored by this class
 *
 *
 * @package    Sambhuti
 * @subpackage config
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
    static $dependencies = array('loader');

    /**
     * Lazy Path
     *
     * Array of [namespace => Path] for lazy loading
     *
     * @see \sambhuti\loader\loader::$lazyPath
     * @var array $lazyPaths
     */
    protected $lazyPaths = array();

    /**
     * Default Path
     *
     * Last path in lazy paths
     *
     * @var string
     */
    protected $defaultPath = '';

    /**
     * Array of \sambhuti\core\iData objects
     *
     * @var array config objects
     */
    protected $confs = array();

    /**
     * Constructor
     *
     * Sets lazy Path and default path (last lazy path)
     *
     * @param \sambhuti\loader\iContainer $loader
     */
    function __construct ( loader\iContainer $loader ) {
        //random comment to test merging
        $this->lazyPaths = $loader->getLazyPaths();
        $this->defaultPath = end($this->lazyPaths);
    }

    /**
     * Get
     *
     * Takes in string identifier eg: "file" and makes it into config/file.php
     * Runs through all available lazy paths.
     *
     * @param null|string $id
     *
     * @return \sambhuti\core\iData object
     */
    function get ( $id = null ) {
        if (empty($this->confs[$id])) {
            $config = array();
            foreach ( $this->lazyPaths as $path ) {
                $fullPath = $path . '/config/' . $id . '.json';
                if (!file_exists($fullPath))
                    continue;
                $tmp = @json_decode(file_get_contents($fullPath), true);
                if (!is_array($tmp))
                    continue;
                foreach ($tmp as $k => $v)
                    $config[$k] = $v;
            }
            $this->confs[$id] = new core\data($config);
        }
        return $this->confs[$id];
    }

    /**
     * Save Config object
     *
     * Saves data in a config object to the default path (last lazy path)
     * Path can be overridden by $lazyId namespace to the path.
     *
     * @see \sambhuti\loader\loader::addLazyPath
     *
     * @param string               $id     config file identifier
     * @param \sambhuti\core\iData $data   config object
     * @param null|string          $lazyId namespace identifier
     *
     * @throws \Exception
     */
    function save ( $id, core\iData $data, $lazyId = null ) {
        $fileString = json_encode($data->getAll());
        $fullPath = $this->defaultPath;
        if ($lazyId !== null && !empty($this->lazyPaths[$lazyId])) {
            $fullPath = $this->lazyPaths[$lazyId];
        }
        $fp = fopen($fullPath . '/config/' . $id . '.json', 'wb');
        if (empty($fp)) {
            throw new \Exception('Config folder not writable');
        }
        fwrite($fp, $fileString);
        fclose($fp);
        $this->confs[$id] = $data;
    }
}
