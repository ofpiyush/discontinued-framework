<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

abstract class controller extends SBbase
{

	public function __construct()
	{
		parent::__construct();
	/*
		foreach(sambhuti::ping('controller')	as $key=>$object)
		{
			$this->_cannula($key,$object);
		}
	*/
	}
	abstract function index();
}

/**
 * End of file Controller
 */
