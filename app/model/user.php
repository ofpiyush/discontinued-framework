<?php
namespace app\model;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

class user
{
	var $uid;
	var $firstname;
	var $lastname;
	var $slug;
	var $email;
	function __construct($val=false)
	{
		if(is_array($val))
		{
			$this->uid		=	$val['uid'];
			$this->firstname=	$val['firstname'];
			$this->lastname	=	$val['lastname'];
			$this->slug		=	$val['slug'];
			$this->email	=	$val['email'];
		}
	}
	function __sleep()
	{
		return array('uid','firstname','lastname','slug','email');
	}
}

/**
 * End of file User
 */
