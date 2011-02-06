<?php
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

class welcome extends \sb\controller
{
	function __construct()
	{
		parent::__construct();
		echo "Controller Called <br />";	
	}
	function index()
	{
		global $time1;
		echo (microtime(true)-$time1)." secs from init <br />";
		echo "Index Called <br /> Loading model...<br />";
		$this->load->model('check');
		echo "Success!!! <br /> Loading view...<br />";
		$test=array('test'=>'test variable set!!<br />');
		$this->load->view('check_test',$test);
		echo (microtime(true)-$time1)." secs from init".PHP_EOL;
	}
}

/**
 * End of file Welcome
 */
