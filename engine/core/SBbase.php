<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

abstract class SBbase
{
	private static $loads=null;
	public function __construct(){}
	final public function _cannula($key,$value)
	{
		self::$loads[$key]=$value;
	}
	final function __get($key)
	{
		$output=false;
		if(isset(self::$loads[$key]))
			$output= self::$loads[$key];
		if(sambhuti::pimple($key))
			$output= sambhuti::pimple($key);
		$this->$key=$output;
		return $output;
	}
}


/**
 * End of file Base
 */
