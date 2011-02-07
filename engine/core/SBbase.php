<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

abstract class SBbase
{
	protected $load;
	protected $uri;
	public function __construct()
	{
		foreach(sambhuti::ping('SBbase')	as $key=>$object)
		{
			$this->_cannula($key,$object);
		}
	}
	final public function _cannula($key,$value)
	{
		$this->$key=$value;
	}
}


/**
 * End of file Base
 */
