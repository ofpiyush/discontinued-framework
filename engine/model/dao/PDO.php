<?php
namespace sb\model\dao;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * Sambhuti
 * Copyright (C) 2010-2011  Piyush Mishra
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
 * @author Piyush Mishra <me[at]piyushmishra[dot]com>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2010-2011 Piyush Mishra
 */


abstract class PDO 
{
    protected static $_dbh  = null;
    protected static $config = null;

    abstract function make();

    abstract function save(\sb\model\base $base);

    function __construct()
    {
        if(is_null(self::$config))
            try
            {
                self::$config = \sb\model\load::model('config');
            }
            catch(Exception $e){}
    }

    /**
     * Connects to the dbase if no connection already exists
     *
     * @access protected
     * @param $key string default 'master'
     * @return $key string type of connection handle
     */
    protected final function connect($key = 'master')
    {
        if(!isset(self::$_dbh[$key]) && is_null(self::$_dbh[$key]))
        {
            //list($db_type,$db_host,$db_dbname, $db_user, $db_pass, $db_options)=array_values(self::$_config->$method());
            extract(self::$config->get('db',$key),EXTR_PREFIX_ALL,'db');
            $db_type=strtolower(self::$config->get('db','type'));
            $dsn=$db_type.":dbname=".$db_dbname.";host=".$db_host;
            try
            {
                self::$_dbh[$key] = new \PDO($dsn, $db_user, $db_pass, $db_options);
                self::$_dbh[$key]->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
                self::$_dbh[$key]->exec('SET CHARACTER SET utf8');
            }
            catch(\PDOException $e)
            {
                throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
            
        }
        return $key;
    }

    /**
     * Executes the query with Bindings and returns Statement
     *
     * @access protected
     * @param $sql string the query to be executed
     * @param $bindings array bindings for each query
     * @param $get_master bool default false(set this when making an actual master slave)
     * @return $stmt PDOStatement Object
     */
    protected final function execute($sql, $bindings = array(), $key = 'master')
    {
        $key=$this->connect($key);
        $sql=str_replace('/#prefix#/',self::$config->get('db', $key,'prefix'),$sql);
        $stmt = self::$_dbh[$key]->prepare($sql);
        try
        {
            if(is_array($bindings))
                $stmt->execute($bindings);
            else
                $stmt->execute();
        }catch(\PDOException $e)
        {
            $exception_details .= 'Could not execute the following query:'.str_replace(chr(10), "", $stmt->queryString) . ' PDOException: '. $e->getMessage();
            throw new \sb\model\Exception ($exception_details,$e->getCode(),$e);
            
        }
        return $stmt;
    }
    
    
    protected final function fetch($stmt)
    {
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }
        
    protected final function fetchAll($stmt)
    {
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
    
    protected final function updateCount($stmt)
    {
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }
    protected final function getCount($stmt)
    {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $this->fetch($stmt);
        if(!$row or !isset($row['count']))
            return 0;
        else 
            return (int) $row['count'];
    }
    protected final function insertId($stmt,$key='master')
    {
        $count=$this->updateCount($stmt);
        $id= self::$_dbh[$key]->lastinsertId();
        return ($count>0 && $id>0) ? $id : 0 ;
    }
    
    protected final function isReturned($stmt)
    {
        $row = $this->fetch($stmt);
        if($row && count($row) > 0) 
            return true;
        return false;
    }
    
    protected final function assocRow($stmt)
    {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetch($stmt);
    }
    protected final function assocRows($stmt)
    {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetchAll($stmt);
    }
    
    protected final function objRow($stmt,$class)
    {
        $stmt->setFetchMode(\PDO::FETCH_CLASS,$class);
        return $this->fetch($stmt);
    }

    protected final function objRows($stmt,$class)
    {
        $stmt->setFetchMode(\PDO::FETCH_CLASS,$class);
        return $this->fetchAll($stmt);
    }

    protected final function disconnect($key='all')
    {
        if(is_array(self::$_dbh))
            if($key=='all')
                foreach(self::$_dbh as $index => $val)    
                    self::$_dbh[$index] = null;
            elseif(array_key_exists($key,self::$_dbh))
                self::$_dbh[$key] = null;
    }
    
    function __destruct()
    {
        $this->disconnect();
    }
    
}
