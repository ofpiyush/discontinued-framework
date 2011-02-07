<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhumi
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

require_once SB_ENGINE_PATH.'core/sambhuti.php';
/**
 * Start the Lazy Loader
 */
sambhuti::run($sb_apps);


/**
 * End of file Init
 */
