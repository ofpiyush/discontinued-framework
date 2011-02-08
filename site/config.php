<?php
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

/**
 * @var $app_config[lazy_path] array
 *				('namespace'=>array
 * 					(
 *						'/full/path1/',
 *						'/full/path2/'
 *					)
 *				) 
 *	Stores all Lazy load paths according to the namespaces they are in.
 *	We've kept the defaults here to make it easier to follow(Dont mess with them unless you are sure what you are doing)
 *
 * 	Note: In case you use 'sb' namespace in your application to 
 *		override the default classes in sambhuti, use loader::addlazypath($path,true)
 *		adding them here will make sambhuti look for them after it has looked into its own directories.
 * 	Note: Order of namespaces donot affect the load time,
 *		but you should really care about the order of paths within the namespaces,
 *		Specially if you are going override older versions of some classes with new ones, higher in the hierarchy
 */
$app_config['lazy_path']['global']['model']=SB_APP_PATH.'model/';
$app_config['lazy_path']['global']['controller']=SB_APP_PATH.'controller/';

/**
 * @var $app_config['view_path'] string path to the view folder
 * @default $app_config['view_path']=SB_APP_PATH.'view/';
 *
 */
$app_config['view_path']=SB_APP_PATH.'view/';

/**
 * Db config vars
 */
$app_config['db']['DAO_name']="/#type#//#class#/DAO";
$app_config['db']['type']='mysql';
/**
 * Conn params
 */
$app_config['db']['master']['host']='localhost';
$app_config['db']['master']['dbname']='phpbull';
$app_config['db']['master']['user']='root';
$app_config['db']['master']['pass']='piyush';
$app_config['db']['master']['options']=array();
$app_config['db']['master']['prefix']='pb_';

$app_config['default_controller']='welcome';
/**
 * End of file Config
 */
