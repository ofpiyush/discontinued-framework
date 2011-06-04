<?php
namespace app\model\dao;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */
abstract class user extends \sb\model\PDOBase
{
	abstract public function idexists($id);
	abstract public function emailexists($email);
	abstract public function getbyid($id);
	abstract public function getbyslug($slug);
	abstract public function getbyemail($email);
	abstract public function create($fname,$lname,$email,$slug,$pass,$key);
	abstract public function getall($all=true);
	abstract public function getpass($email);
	abstract public function checkactivate($actkey);
	abstract public function clearkey($email);
	abstract public function activate($email);
	protected $_getquery=	" uid, firstname, lastname, email, slug, active ";
	protected $_users	=	" #prefix#users ";
	protected $_object	=	"user";
	public function keygen($email)
	{
		return md5(sha1($email.rand()));
	}
	public function fetchsalt($hashedpass)
	{
		return substr($hashedpass,0,28);
	}
	public function hashpass($pass,$salt=null)
	{
		$base_str='abcdefghijklmnopqrstuvwxyz.ABCDEFGHIJHKLMONPRSTUVWXYZ/0123456789';
		if(is_null($salt) || strlen($salt)<28)
		{
			$salt='$2a$07$';
			for($i=1;$i<21;$i++)
				$salt.=$base_str[mt_rand(0,63)];
			$salt.='$';
		}
		return crypt($pass, $salt);
	}
	
}

/**
 * End of file Userdao
 */
