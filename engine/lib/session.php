<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */
final class session
{
	private static $_session=array();
	public function __construct()
	{
		session_start();
		if(isset($_SESSION[__CLASS__]))
		{
			self::$_session=$_SESSION[__CLASS__];
		}
	}
	public function set($key,$val)
	{
	 	self::$_session[$key]=$val;
	}
	
	public function get($key)
	{
		if(isset(self::$_session[$key]))
			return 	self::$_session[$key];		
	}
	public function destroy()
	{
		unset(self::$_session);
		session_destroy();
	}
	private function log()
	{
	
	}
	function __destruct()
	{
		if(isset(self::$_session))
		{
			$this->log();
			$_SESSION[__CLASS__]=self::$_session;
		}
	}
}

/**
 *End of file Session
 */
