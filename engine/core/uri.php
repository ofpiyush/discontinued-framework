<?php
namespace sb;
if ( ! defined('SB_ENGINE_PATH')) exit('No direct script access allowed');
/**
 * @package sambhuti
 * @author Piyush Mishra<me[at]piyushmishra[dot]com>
 */

class uri
{
	private $_segments;
	private $_request_uri;
	public $site_url;
	function __construct($apps)
	{
		$scheme= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ? 'https':'http';
		$host_parts = explode(':', $_SERVER['HTTP_HOST'], 2);
		$http_host = array_shift($host_parts);
		$http_port = array_shift($host_parts);
		$request_uri=$this->request_uri();
		if(filter_var($scheme."://".$http_host.$request_uri, FILTER_VALIDATE_URL))
		{
			if(isset($apps) && is_array($apps))
			foreach($apps as $url=>$relpath)
			{
				$urla=parse_url($url);
				if(!isset($urla['port']))
					$urla['port']='';
				if($urla['scheme']==$scheme && $urla['host']==$http_host && $urla['port'] == $http_port)
				{
					if(!isset($urla['path']) || strpos($request_uri,$urla['path'])===0)
					{
						$this->site_url=rtrim($url,'/').'/';
						$this->populate($urla['path']);
						define('SB_APP_PATH',realpath($relpath).'/');
						break;
					}
				}
			}
		}
	}
	public function segment($id,$false=false)
	{
		if(array_key_exists($id,$this->_segments))
			return $this->_segments[$id];
		return $false;
	}
	public function total_segments()
	{
		return count($this->_segments);
	}
	public function segment_array()
	{
		return $this->_segments;
	}
	private function populate($path)
	{
		$relative = trim(substr($this->_request_uri, strlen($path)),'/');
		$segments=explode('/',$relative);
		$this->_segments=$segments;
		if($this->_segments[0]!='')
			array_unshift($this->_segments,'');
		unset($this->_segments[0]);
	}
	private function request_uri()
	{
		$this->_request_uri=rtrim($_SERVER['REQUEST_URI'],'/').'/';
		return $this->_request_uri;
	}
}

/**
 * End of file Uri
 */
