<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 * @author Gabriel <thehobbit[at]primorial[dot]net>
 */

class load extends SBbase
{
	function model($model_name,$fake_name=null)
	{		
		$model=str_replace('/#type#/',$this->config->get('db','type'),str_replace('/#class#/',$model_name,$this->config->get('db','DAO_name')));
		if(! is_null($fake_name))
			$model_name=$fake_name;
		sambhuti::autoload($model,'model');
		$this->pimpleLoadPush($model,$model_name);
	}
	private function pimpleLoadPush($class,$name=null) 
	{
		$pimple=sambhuti::pimple();
		extract(sambhuti::explodeNS($class));
		if(! isset($pimple->$classname))
		$pimple->$classname=$pimple->asShared(function($p) use($class)
		{
			return new $class();
		});
		if(is_null($name))
			$name=$classname;
		$this->_cannula($name,$pimple->$classname);
	}
	function view($pb_view,$array=array())
	{
		$pb_view_fn = sambhuti::getFullPath('view_path', $pb_view);
		if (!is_array($array))
			$array = array();
		$this->cleanRequire($pb_view_fn, $array);
	}
	function library($library)
	{
		$lib=(file_exists(SB_ENGINE_PATH.'lib/'.$library.'.php'))? "sb\\".$library : $library;
		sambhuti::autoload($lib,'library');
		$this->pimpleLoadPush($lib,$library);
	}
	function helper($helper)
	{
		sambhuti::autoload($helper,'helper');
	}
	 function cleanRequire($file, $vars)
	{
		unset($file, $vars);
		$this->load->helper('functions');
		extract(func_get_arg(1));
		require(func_get_arg(0));
	}
	
	
}

/**
 * End of file Load
 */
