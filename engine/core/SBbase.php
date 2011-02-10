<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

abstract class SBbase
{
	private static $loads;
	public function __construct()
	{
		foreach(sambhuti::ping('SBbase') as $key=>$object)
			$this->_cannula($key,$object);
	}
	final public function _cannula($key,$value)
	{
		self::$loads[$key]=$value;
	}
	final function __get($key)
	{
		if(isset(self::$loads[$key]))
			return self::$loads[$key];
		return false;
	}
}


/**
 * End of file Base
 */
