<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */
final class config
{
	
	private $_conf=array();
	public function __construct($config)
	{
		$this->_conf=$config;
	}
	public function get($key)
	{
		$args=func_get_args();
		$tmp=$this->_conf;
		foreach($args as $arg)
		{
			if(array_key_exists($arg,$tmp))
				$tmp=$tmp[$arg];
			else
				return;
		}
		return $tmp;
	}
	public function set($key,$val)
	{
		$this->_conf[$key]=$val;
	}
}


/**
 * End of file Config
 */
