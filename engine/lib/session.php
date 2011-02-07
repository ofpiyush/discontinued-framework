<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */
/**
 * Singleton class to add/remove data to the session and keep it consistent during page loads
 */
final class session
{
	private $_session=array();
	protected function __construct()
	{
		session_start();
		if(isset($_SESSION[__CLASS__]))
		{
			$this->_session=$_SESSION[__CLASS__];
		}
	}
	public function set($key,$val)
	{
	 	$this->_session[$key]=$val;
	}
	
	public function get($key)
	{
		if(isset($this->_session[$key]))
			return 	$this->_session[$key];		
	}
	public function destroy()
	{
		unset($this->_session);
		session_destroy();
	}
	private function log()
	{
	
	}
	function __destruct()
	{
		if(isset($this->_session))
		{
			$this->log();
			$_SESSION[__CLASS__]=$this->_session;
		}
	}
}

/**
 *End of file Session
 */
