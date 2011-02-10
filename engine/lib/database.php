<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

abstract class database extends model
{
	protected static $_dbh=null;
	function __construct()
	{
		parent::__construct();
		foreach(sambhuti::ping('database') as $key=>$value)
			$this->_cannula($key,$value); 
	}
	/**
	 * Connects to the dbase if no connection already exists
	 *
	 * @access protected
	 * @param $key string default 'master'
	 * @return $key string type of connection handle
	 */
	protected final function connect($key='master')
	{
		if(!isset(self::$_dbh[$key]) && is_null(self::$_dbh[$key]))
		{
			//list($db_type,$db_host,$db_dbname, $db_user, $db_pass, $db_options)=array_values(self::$_config->$method());
			extract($this->config->get('db',$key),EXTR_PREFIX_ALL,'db');
			$db_type=strtolower($this->config->get('db','type'));
			$dsn=$db_type.":dbname=".$db_dbname.";host=".$db_host;
			try
			{
				self::$_dbh[$key] = new \PDO($dsn, $db_user, $db_pass, $db_options);
				self::$_dbh[$key]->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
				self::$_dbh[$key]->exec('SET CHARACTER SET utf8');
			}catch(PDOException $e)
			{
				echo $e->getMessage();
				die();
			}
			
		}
		return $key;
	}
	
	/**
	 * Executes the query with Bindings and returns Statement
	 *
	 * @TODO Edit $get_master to false when actually using master slave
	 *
	 * @access protected
	 * @param $sql string the query to be executed
	 * @param $bindings array bindings for each query
	 * @param $get_master bool default false(set this when making an actual master slave)
	 * @return $stmt PDOStatement Object
	 */
	protected final function execute($sql, $bindings=array(),$key='master')
	{
		$key=$this->connect($key);
		$sql=str_replace('/#prefix#/',$this->config->get('db',$key,'prefix'),$sql);
		$stmt = self::$_dbh[$key]->prepare($sql);
		if(is_array($bindings) and count($bindings) >= 1) 
		{
            foreach ($bindings as $key => $value) {
                if(is_int($value)) {
                    $stmt->bindValue($key, $value, \PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value, \PDO::PARAM_STR);
                }
            }
        }
		try
		{
			$stmt->execute();
		}catch(PDOException $e)
		{
			$exception_details="Database Error";
			$exception_details .= '<br />Could not execute the following query:<br /> '.
				str_replace(chr(10), "", $stmt->queryString) . '  <br />PDOException: '. $e->getMessage();
			throw new PBException ($exception_details,get_called_class());
			
		}
		return $stmt;
	}
	
	
	protected final function fetch($stmt)
	{
		$result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
	}
		
	protected final function fetchall($stmt)
	{
		$result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
	}
	
	protected final function updatecount($stmt)
	{
		$count = $stmt->rowCount();
		$stmt->closeCursor();
		return $count;
	}
	
	protected final function insertid($stmt,$key='master')
	{
		$count=$this->updatecount($stmt);
		$id= self::$_dbh[$key]->lastInsertId();
		return ($count>0 && $id>0) ? $id : 0 ;
	}
	
	protected final function isreturned($stmt)
	{
		$row = $this->fetch($stmt);
		$return = false;
		if($row && count($row) > 0) 
			$return = true;
		return $return;
	}
	
	protected final function assocrow($stmt)
	{
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetch($stmt);
	}
	protected final function assocrows($stmt)
	{
		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->fetchall($stmt);
	}
	
	protected final function objrow($stmt,$class)
	{
		$stmt->setFetchMode(\PDO::FETCH_CLASS,$class);
        return $this->fetch($stmt);
	}
	protected final function objrows($stmt,$class)
	{
		$stmt->setFetchMode(\PDO::FETCH_CLASS,$class);
		return $this->fetchall($stmt);
	}
	protected final function disconnect($key='all')
	{
		if(is_array(self::$_dbh))
			if($key=='all')
			{
				foreach(self::$_dbh as $index=>$val)	
					self::$_dbh[$index]=null;
			}elseif(array_key_exists($key,self::$_dbh))
				self::$_dbh[$key]=null;
	}
	
	function __destruct()
	{
		$this->disconnect();
	}
	
}


/**
 *End of file database
 */
