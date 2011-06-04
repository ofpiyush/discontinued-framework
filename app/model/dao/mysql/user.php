<?php
namespace app\model\dao\mysql;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @site social-marketplace
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

class user extends \app\model\dao\user
{
	protected $_users = " /#prefix#/users ";
	public function idexists($id)
	{
		$sql = "SELECT uid ";
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE uid=:id";
		$bindings=array(":id"=>$id);
		$stmt=$this->execute($sql,$bindings);
		return $this->isReturned($stmt);
	}
	public function emailexists($email) 
	{
		$sql = "SELECT email ";
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE email=:email";
		$bindings = array(':email'=>$email);
		$stmt = $this->execute($sql, $bindings);
		return $this->isReturned($stmt);
	}
	
	public function getall($all=true)
	{
		$sql = "SELECT ".$this->_getquery;
		$sql.= "FROM ".$this->_users;
		if($all!==true)
		{
			$sql.=" WHERE active = '".$all."'";
		}
		//$bindings=array(":id"=>$id);
		$stmt=$this->execute($sql);
		return $this->objRows($stmt,$this->_object);
	}
	public function getbyslug($slug)
	{
		$sql = "SELECT ".$this->_getquery;
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE slug=:slug";
		$bindings=array(":slug"=>$slug);
		$stmt=$this->execute($sql,$bindings);
		return $this->objRow($stmt,$this->_object);
	}
	public function getbyid($id)
	{
		$sql = "SELECT ".$this->_getquery;
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE uid=:id";
		$bindings=array(":id"=>$id);
		$stmt=$this->execute($sql,$bindings);
		return $this->objRow($stmt,$this->_object);
	}
	public function getbyemail($email)
	{
		$sql = "SELECT ".$this->_getquery;
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE email=:email";
		$bindings=array(":email"=>$email);
		$stmt=$this->execute($sql,$bindings);
		return $this->objRow($stmt,$this->_object);
	}
	public function create($fname,$lname,$email,$slug,$pass,$key)
	{
		if($this->emailexists($email))
			return false;
		$hashpass=$this->hashpass($pass);
		$sql="INSERT INTO ".$this->_users." ";
		$sql.= "( firstname , lastname , email , slug,  pass , actkey ) ";
		$sql.= "VALUES ( :fname , :lname , :email , :slug, :pass , :key )";
		$bindings=array
		(
			':fname'=>$fname,
			':lname'=>$lname,
			':email'=>$email,
			':pass'=>$hashpass,
			':slug'=>$slug,
			':key'=>$key
		);
		$stmt=$this->execute($sql,$bindings);
		return $this->insertId($stmt);
	}
	public function getpass($email)
	{
		$sql = "SELECT pass ";
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE email=:email AND active= '1' ";
		$sql.= "LIMIT 1";
		$bindings=array
		(
			':email'=>$email	
		);
		$stmt=$this->execute($sql,$bindings);
		$row=$this->assocRow($stmt);
		return $row['pass'];
	}
	public function checkactivate($actkey)
	{
		$sql = "SELECT email ";
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE actkey = :actkey ";
		$sql.= "LIMIT 1";
		$bindings=array
		(
			':actkey'=>$actkey
		);
		$stmt=$this->execute($sql,$bindings);
		$row= $this->assocRow($stmt);
		if($this->activate($row['email']))
			return $this->clearkey($row['email']);
		return false;
	}
	public function clearkey($email)
	{
		$sql = "UPDATE ".$this->_users." ";
		$sql.= "SET actkey = NULL ";
		$sql.= "WHERE email=:email ";
		$bindings=array
		(
		":email"=>$email
		);
		$stmt=$this->execute($sql,$bindings);
		return $this->updateCount($stmt);
	}
	public function activate($email)
	{
		$sql = "UPDATE ".$this->_users." ";
		$sql.= "SET active ='1' ";
		$sql.= "WHERE email=:email";
		$bindings=array
		(
		":email"=>$email
		);
		$stmt=$this->execute($sql,$bindings);
		return $this->updateCount($stmt);
	}
	/**
	 * test function
	 
	public function getsome($array)
	{
		$string=implode($array,',');
		$sql = "SELECT ".$this->_getquery;
		$sql.= "FROM ".$this->_users;
		$sql.= "WHERE uid IN ( ".$string." )";

		$stmt=$this->execute($sql);
		return $this->objRows($stmt,$this->_object);
	}
	
	*/

}

/**
 * End of file app\model\dao\mysql\user
 */
