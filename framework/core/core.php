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

class core extends container {
    static $dependencies = array('loader');
    protected $processed = array();
    /** @var null|\sambhuti\loader\loader */
    protected $loader = null;

    function __construct ( array $dependencies = array() ) {
        $this->loader = $dependencies['loader'];
        $this->processed['loader'] = $dependencies['loader'];
        $this->processed['core'] = $this;
    }

    function get ( $identifier = null ) {
        if (null === $identifier || 'core' === $identifier) {
            return $this;
        }
        if (empty($this->processed[$identifier])) {
            if (false === strpos($identifier, '.')) {
                $this->processed[$identifier] = $this->process($this->loader->fetch($identifier, $identifier), 'sambhuti\core\container');
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


    function process ( $class, $base ) {
        if (empty($class) || !class_exists($class)) {
            throw new \Exception($class . ' not found');
        }
        if (!is_subclass_of($class, $base)) {
            throw new \Exception($class . ' not subclass of ' . $base);
        }
        /** @var array $dependencies */
        $dependencies = !empty($class::$dependencies) ? array_combine($class::$dependencies, array_map(array(
                                                                                                            $this, 'get'
                                                                                                       ), $class::$dependencies)) : array();
        return new $class($dependencies);
    }

}
