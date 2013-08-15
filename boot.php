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

/**
 * Sambhuti Root Path
 *
 * Path to sambhuti/framework
 */
if (!defined('SAMBHUTI_ENV')) {
    define('SAMBHUTI_ENV', 'development');
}
if (!defined('SAMBHUTI_CONFIG_ADAPTER')) {
    define('SAMBHUTI_CONFIG_ADAPTER', 'Json');
}
//bad hack would be define(strtoupper(SAMBHUTI_ENV).'_MODE',true)
define('DEVELOPMENT_MODE', SAMBHUTI_ENV === 'development');
define('PRODUCTION_MODE', SAMBHUTI_ENV === 'production');
define('TEST_MODE', SAMBHUTI_ENV === 'test');

define('SAMBHUTI_ROOT_PATH', realpath(dirname(__FILE__)) . '/');
require_once(SAMBHUTI_ROOT_PATH . 'app/core/IContainer.php');
require_once(SAMBHUTI_ROOT_PATH . 'app/loader/IContainer.php');
require_once(SAMBHUTI_ROOT_PATH . 'app/loader/Container.php');

set_error_handler(
    function ($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }
);
