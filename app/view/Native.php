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
 */

namespace sambhuti\view;

use sambhuti\core;

/**
 * View Native
 *
 * @package    Sambhuti
 * @subpackage view
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Native implements IView
{
    protected $conf = null;
    protected $vars = [];
    protected $data = '';
    public static $dependencies = ['config.view'];

    function __construct(core\IData $viewConf)
    {
        $this->conf = $viewConf;
    }


    function render($path)
    {
        $file = $path . '.php';
        ob_start();
        self::load($this->vars, $this->conf->get("view_dir") . $file);
        $this->data = ob_get_clean();
    }

    function getData()
    {
        return $this->data;
    }

    function set($key, $value)
    {
        $this->vars[$key] = $value;
    }


    protected static function load()
    {
        extract(func_get_arg(0));
        require_once(func_get_arg(1));
    }
}