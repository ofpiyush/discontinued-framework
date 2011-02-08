<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 * @author Gabriel <thehobbit[at]primorial[dot]net>
 */

class load
{
	private $_config;
	function __construct(config $config)
	{
		$this->_config=$config;
	}
	function model($model_orig)
	{
		$model=str_replace('/#type#/',$this->_config->get('db','type'),str_replace('/#class#/',$model_orig,$this->_config->get('db','DAO_name')));
		sambhuti::autoload($model,'model');
		$pimple=sambhuti::pimple();
		$modname="_".$model;
		$pimple->$modname=$pimple->asShared(function($p) use($model)
			{
				return new $model($p->config);
			});
		$pimple->controller->_cannula($model_orig,$pimple->$modname);
	}
	function view($pb_view,$array=array())
	{
		$pb_view_fn = sambhuti::getfullpath('view_path', $pb_view);
		if (!is_array($array))
			$array = array();
		self::cleanrequire($pb_view_fn, $array);
	}
	function helper($helper)
	{
		echo "helpers are not yet supported";
		die();
	}

	

	// FIXME: iyush: Please put this function wherever you deem appropriate.
	// This seems like a good place to me, please move it if you have
	// something better in mind.
	static function cleanrequire($file, $vars)
	{
		unset($file, $vars);
		extract(func_get_arg(1));
		require(func_get_arg(0));
	}
	
	
}

/**
 * End of file Load
 */
