<?php
$time1=microtime(true);
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

$sb_engine='sambhuti-core';
/**
 * $pb_apps['full_url']='relative/path/from/this/file'
 * specific to generic to prevent overriding
 * donot use a trailing slash
 */
$sb_apps['http://localhost/sambhuti']='site';
/**
 * Now let sambhuti handle the rest
 */


define('SB_ENGINE_PATH',realpath($sb_engine).'/');
if(SB_ENGINE_PATH=='/')
	exit('Please check your $sb_engine '.__FILE__);
require_once SB_ENGINE_PATH.'init.php';



/**
 * End of file Index
 */
