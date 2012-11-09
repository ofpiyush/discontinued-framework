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
use sambhuti\loader;

class core implements iCore {
    static $dependencies = array('loader');
    protected $processed = array();
    /** @var null|\sambhuti\loader\loader */
    protected $loader = null;

    function __construct ( loader\iLoader $loader ) {
        $this->loader = $loader;
        $this->processed['loader'] = $loader;
        $this->processed['core'] = $this;
    }

    function get ( $identifier = null ) {
        if (null === $identifier || 'core' === $identifier) {
            return $this;
        }
        if (empty($this->processed[$identifier])) {
            if (false === strpos($identifier, '.')) {
                $this->processed[$identifier] = $this->process($this->loader->fetch($identifier . "\\" . $identifier));
            } else {
                $parts = explode('.', $identifier);
                if ($parts[0] == 'core') {
                    unset($parts[0]);
                    $this->processed[$identifier] = $this->get(implode('.', $parts));
                } else {
                    $current = $this;
                    foreach ( $parts as $part ) {
                        if (!is_object($current)) {
                            throw new \Exception ('Cannot load ' . $identifier . ' dependency ' . $part . ' can not be loaded from a non-object');
                        }
                        $current = $current->get($part);
                    }
                    $this->processed[$identifier] = $current;
                }
            }

        }
        return $this->processed[$identifier];
    }


    function process ( $class ) {
        if (empty($class) || !class_exists($class)) {
            throw new \Exception($class . ' not found');
        }
        $dependencies = array();
        if (!empty($class::$dependencies)) {
            $dependencies = array_map(array($this, 'get'), $class::$dependencies);
        }
        $count = count($dependencies);
        //implement an ugly hack for speed
        switch ($count) {
            case 0:
                return new $class();
            case 1:
                return new $class($dependencies[0]);
            case 2:
                return new $class($dependencies[0], $dependencies[1]);
            case 3:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2]);
            case 4:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3]);
            case 5:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4]);
            case 6:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4], $dependencies[5]);
            case 7:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4], $dependencies[5], $dependencies[6]);
            case 8:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4], $dependencies[5], $dependencies[6], $dependencies[7]);
            case 9:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4], $dependencies[5], $dependencies[6], $dependencies[7], $dependencies[8]);
            case 10:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4], $dependencies[5], $dependencies[6], $dependencies[7], $dependencies[8], $dependencies[9]);
            case 11:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4], $dependencies[5], $dependencies[6], $dependencies[7], $dependencies[8], $dependencies[9], $dependencies[10]);
            case 12:
                return new $class($dependencies[0], $dependencies[1], $dependencies[2], $dependencies[3], $dependencies[4], $dependencies[5], $dependencies[6], $dependencies[7], $dependencies[8], $dependencies[9], $dependencies[10], $dependencies[11]);
            default:
                //more than 12 dependencies, go for good old reflection
                $reflection = new \ReflectionClass($class);
                return $reflection->newInstanceArgs($dependencies);
        }
    }

}
