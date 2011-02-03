<?php
function timenow()
{
	$now=explode(" ",microtime());
	return ($now[0]+$now[1]);
}
$time1=timenow();
/**
 * @package phpbull
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

$pb_engine='frame-core';
/**
 * $pb_apps['full_url']='relative/path/from/this/file'
 * specific to generic to prevent overriding
 * donot use a trailing slash
 */
$pb_apps['http://localhost/framework']='site';
/**
 * Now let phpbull handle the rest
 */


define('PB_ENGINE_PATH',realpath($pb_engine).'/');
if(PB_ENGINE_PATH=='/')
	exit('Please check your $pb_engine '.__FILE__);
require_once PB_ENGINE_PATH.'init.php';



/**
 * End of file Index
 */
