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
	public $ip;
	public function __construct()
	{
		session_start();
		$this->ip=filter_input(INPUT_SERVER,'REMOTE_ADDR')
		if(isset($_SESSION[$this->ip]))
		{
			self::$_session=$_SESSION[$this->ip];
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
			$_SESSION[$this->ip]=self::$_session;
		}
	}
}

/**
 *End of file Session
 */
