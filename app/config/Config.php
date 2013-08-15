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
abstract class Config implements IConfig
{

    /**
     * App Path
     *
     * Array of [namespace => Path] for lazy loading
     *
     * @see \sambhuti\loader\Loader::$appPath
     * @var array $appPaths
     */
    protected $rootPaths = [];
    public static $dependencies = ['loader'];
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
     * Extension
     *
     * @var string
     */
    protected $ext = '';

    /**
     * Constructor
     *
     * Sets app paths and default path (last app path)
     *
     * @param \sambhuti\loader\IContainer $loader
     */
    public function __construct(loader\IContainer $loader)
    {
        $this->rootPaths = $loader->getRoots();
        $this->defaultPath = end($this->rootPaths);
        $this->ext = '.' . trim($this->getExt(), ".");
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
    public function get($id = null)
    {
        if (empty($this->confs[$id])) {
            $config = [];
            foreach ($this->rootPaths as $path) {
                $fullPaths = [
                    'Default' => $path . '/config/' . $id . $this->ext,
                    'ENV' => $path . '/config/' . SAMBHUTI_ENV . '/' . $id . $this->ext
                ];
                foreach ($fullPaths as $fullPath) {
                    if (!file_exists($fullPath)) {
                        continue;
                    }
                    $tmp = $this->makeTmp($fullPath);
                    if (!is_array($tmp)) {
                        continue;
                    }
                    $config = core\Utils::arrayMergeRecursive($config, $tmp);
                }
            }
            $this->confs[$id] = new core\Data($config);
        }

        return $this->confs[$id];
    }

    abstract protected function makeTmp($path);

    abstract protected function getExt();
}
