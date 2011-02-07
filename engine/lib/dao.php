<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

class dao
{
	private $_config;
	public function __construct(config $config)
	{
		$this->_config=$config;
	}
	public function get($class)
	{
		
		$name=str_replace('/#type#/',$this->_config->get('db','type'),str_replace('/#class#/',$class,$this->_config->get('db','DAO_name')));
		return new $name;
	}
}

/**
 * End of file Dao
 */
