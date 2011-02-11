<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

class input extends SBbase
{
	private $validate;
	private $hasvar;
	function __construct()
	{
	
	}
	function set($key,$filter,$options=null,$flags=null)
	{
		$this->validate[$key]['filter']=$filter;
		$this->validate[$key]['options']=$options;
		$this->validate[$key]['flags']=$flags;
		//print_r($this->validate);
	}
	function get($key,$post=true)
	{
		
		if($post)
			return filter_input(INPUT_POST,$key,$this->validate[$key]['filter'],$this->validate[$key]['options']);
		else
			return filter_input(INPUT_GET,$key,$this->validate[$key]['filter'],$this->validate[$key]['options']);
	}
	function getAll($post=true)
	{
		if($post)
			return filter_input_array(INPUT_POST,$this->validate);
		else
			return filter_input_array(INPUT_GET,$this->validate);
	}
	function hasVar($var,$post=true)
	{
		if($post)
			return filter_has_var(INPUT_POST,$var);
		else
			return filter_has_var(INPUT_GET,$var);
	}
}

/**
 * End of file Input
 */
