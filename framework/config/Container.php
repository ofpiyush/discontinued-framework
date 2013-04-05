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
class Container implements IContainer
{

    /**
     * Dependencies
     *
     * @static
     * @var array Array of dependency strings
     */
    static $dependencies = ['loader'];

    /**
     * App Path
     *
     * Array of [namespace => Path] for lazy loading
     *
     * @see \sambhuti\loader\Loader::$appPath
     * @var array $appPaths
     */
    protected $appPaths = [];

    /**
     * Default Path
     *
     * Last path in app paths (i.e. last app)
     *
     * @var string
     */
    protected $defaultPath = '';

    /**
     * Array of \sambhuti\core\IData objects
     *
     * @var array config objects
     */
    protected $confs = [];

    /**
     * Constructor
     *
     * Sets app paths and default path (last app path)
     *
     * @param \sambhuti\loader\IContainer $loader
     */
    function __construct(loader\IContainer $loader)
    {
        $this->appPaths = $loader->getApps();
        $this->defaultPath = end($this->appPaths);
    }

    /**
     * Get
     *
     * Takes in string identifier eg: "file" and makes it into config/file.json
     * Runs through all available app paths.
     *
     * @param null|string $id
     *
     * @return \sambhuti\core\IData object
     */
    function get($id = null)
    {
        if (empty($this->confs[$id])) {
            $config = array();
            foreach ($this->appPaths as $path) {
                $fullPath = $path . '/config/' . $id . '.json';
                if (!file_exists($fullPath)) {
                    continue;
                }
                $tmp = @json_decode(file_get_contents($fullPath), true);
                if (!is_array($tmp)) {
                    continue;
                }
                foreach ($tmp as $k => $v) {
                    $config[$k] = $v;
                }
            }
            $this->confs[$id] = new core\Data($config);
        }
        return $this->confs[$id];
    }

    /**
     * Save Config object
     *
     * Saves data in a config object to the default path (last app path)
     * Path can be overridden by $appId namespace of the app path.
     *
     * @see \sambhuti\loader\Loader::addApp
     *
     * @param string $id     config file identifier
     * @param \sambhuti\core\IData $data   config object
     * @param null|string $appId namespace identifier
     *
     * @return void
     *
     * @throws \Exception
     */
    function save($id, core\IData $data, $appId = null)
    {
        $fileString = json_encode($data->getAll());
        $fullPath = $this->defaultPath;
        if ($appId !== null && !empty($this->appPaths[$appId])) {
            $fullPath = $this->appPaths[$appId];
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
