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

/**
 * Utils Class
 *
 * This class acts as a wrapper for Util functions for all classes
 *
 * @package    Sambhuti
 * @subpackage core
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Utils
{
    /**
     * Array Merge Recursive
     *
     * Gets rid of vaue to array conversion in php's inbuilt array_merge_recursive
     * @throws \Exception
     * @return array
     */
    public static function arrayMergeRecursive()
    {
        $arrays = func_get_args();
        $output = array();
        foreach ($arrays as $array) {
            if (!is_array($array)) {
                throw new \Exception("Array not passed to arrayMergeRecursive");
            }
            foreach ($array as $k => $v) {
                if (is_array($v) && isset($output[$k]) && is_array($output[$k])) {
                    $output[$k] = static::arrayMergeRecursive($output[$k], $v);
                } else {
                    $output[$k] = $v;
                }
            }
        }

        return $output;
    }

    public static function camelCase($string, array $options = [])
    {
        $options = static::arrayMergeRecursive(['delimiter' => "_", 'caps' => false], $options);
        if ($options['caps']) {
            $string = ucfirst($string);
        }

        return preg_replace_callback(
            '/' . $options['delimiter'] . '([a-z])/',
            function ($c) {
                return strtoupper($c[1]);
            },
            $string
        );
    }
}
