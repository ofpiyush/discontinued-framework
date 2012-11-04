<?php
namespace sambhuti\request;
if(!defined('SAMBHUTI_ROOT_PATH')) exit;
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
 * @package Sambhuti
 * @author Piyush<piyush[at]cio[dot]bz>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2012 Piyush
 */
use sambhuti\core;
class request extends core\container {
    private $request = null;
    private $controller = '';
    private $get = array();
    private $post = array();
    private $server = array();

    function __construct(array $dependencies = array()) {
        $data = ISCLI ? $this->cli() : $this->web();
        $this->request = new core\data($data);
        $this->response = new core\data();
        
    }

    function get($data = null) {
        if($data === 'response') return $this->response;
        return $this->request;
    }

    function web() {
        $request_uri = $_SERVER["REQUEST_URI"];
        if( false !== strpos($request_uri,'?'))
            $request_uri = substr($request_uri, 0 , -1-strlen($_SERVER["QUERY_STRING"]));
        $path = dirname($_SERVER["SCRIPT_NAME"]);
        $command = $request_uri;
        if(strpos($request_uri,$path) === 0)
            $command = substr($request_uri,strlen($path));
        return array (
            'command' => trim($command,"/"),
            'get' =>$_GET,
            'post' =>$_POST,
            'server'=>$_SERVER,
            'file'=>$_FILES,
            'cookies'=>$_COOKIE
        );
    }

    function cli() {
        global $argv;
        var_dump($argv);
        return array();
    }
}
