<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

class load
{
	private $_config;
	function __construct(config $config)
	{
		$this->_config=$config;
	}
	function model($model)
	{
		sambhuti::autoload($model,'model');
		$pimple=sambhuti::pimple();
		$modname="_".$model;
		$pimple->$modname=$pimple->asShared(function($p) use($model)
			{
				return new $model();
			});
		$pimple->controller->_cannula($model,$pimple->$modname);
		
	}
	function view($pb_view,$array=array())
	{
		$view_path= realpath($this->_config->get('view_path'))."/";
		if($view_path!="/")
		{
			$pb_view_fn=$view_path.$pb_view.".php"; 
			if(file_exists($pb_view_fn))
				{
					if(is_array($array))
						extract($array);
					unset($array);
					require_once($pb_view_fn);
				}
		}
		else
		{
			//throw new PBException(__CLASS__,0,"Set the view path correctly");
		}
	}
	function helper($helper)
	{
		echo "helpers are not yet supported";
		die();
	}
	
}

/**
 * End of file Load
 */
