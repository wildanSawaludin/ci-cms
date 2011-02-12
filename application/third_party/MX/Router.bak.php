<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/*
|----------------------------------------------------------------------
| Modified Modular Extension System Router Class
|
| This class has been modified for use with CI-CMS2
|
| Modified By: Randall Morgan rmorgan62@gmail.com
| Version: 0.8.9
|
| Changes: Added test for module admin controllers
| by reversing the first two uri segments and 
| testing for their existance.
|
|----------------------------------------------------------------------
*/

/* load the MX core module class */
require dirname(__FILE__).'/Modules.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter router class.
 *
 * Install this file as application/third_party/MX/Router.php
 *
 * @copyright	Copyright (c) 2011 Wiredesignz
 * @version 	5.4
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Router extends CI_Router
{
	private $module;
	
	public function fetch_module() {
		return $this->module;
	}
	
	public function _validate_request($segments) {		
		
		/* locate module admin controllers */
		$tmp = $segments;
		
		if($segments[0] == 'admin' AND count($segments) > 1)
		{				
			$segments[0] = $tmp[1];
			$segments[1] = $tmp[0];
		
			if($located = $this->locate($segments))
			{
				return $located;
			}

		}
		
		/* Locate standard module controllers */ 
		/* We only swap segments if more than one exists */ 
		if(count($segments) > 1 AND $segments[0] == 'admin')
		{
			$segments[0] = $tmp[0];
			$segments[1] = $tmp[1];
			
			if($located = $this->locate($segments))
			{
				return $located;
			}
		}
		
		
		// Make sure we block access to module/admin
		if ($located = $this->locate($segments))
		{
			return $located;
		}
		
		// Look for default controller + path
		/*if(count($segments) < 2)
		{
			if($located = $this->locate($segments))
			{
				return $located;
			}
			else
			{
				$tmp = $segments;
				
				$segments[0] = $this->routes['default_controller'];
				$segments[1] = $tmp[0];
				
				print_r($segments);
				
				if($located = $this->locate($segments))
				{
					return $located;
				}
				
			}
		}*/
		
		/* use a default 404_override controller */
		/*if (isset($this->routes['404_override']) AND $segments = explode('/', $this->routes['404_override'])) {
			if ($located = $this->locate($segments))
			{
				 return $Dlocated;
			}
		}*/
		
		
		/* no controller found */
		//show_404();
	}
	
	/** Locate the controller **/
	public function locate($segments) {		
		
		$this->module = '';
		$this->directory = '';
		$ext = $this->config->item('controller_suffix').EXT;
		
		/* use module route if available */
		if (isset($segments[0]) AND $routes = Modules::parse_routes($segments[0], implode('/', $segments))) {
			$segments = $routes;
		}
	
		/* get the segments array elements */
		list($module, $directory, $controller) = array_pad($segments, 3, NULL);	
		
		foreach (Modules::$locations as $location => $offset) {
		
			if (is_dir($source = $location.$module.'/controllers/')) {
				
				$this->module = $module;
				$this->directory = $offset.$module.'/controllers/';
				
				/* module sub-controller exists? */
				if($directory AND is_file($source.$directory.$ext)) {
					return array_slice($segments, 1);
				}
					
				/* module sub-directory exists? */
				if($directory AND is_dir($module_subdir = $source.$directory.'/')) {
							
					$this->directory .= $directory.'/';

					/* module sub-directory controller exists? */
					if(is_file($module_subdir.$directory.$ext)) {
						return array_slice($segments, 1);
					}
				
					/* module sub-directory sub-controller exists? */
					if($controller AND is_file($module_subdir.$controller.$ext))	{
						return array_slice($segments, 2);
					}
				}
			
				/* module controller exists? */			
				if(is_file($source.$module.$ext)) {
					echo "Source: $source <br>";
					echo "Module: $module <br>";
					echo "Ext: $ext <br>";
					echo "Module Controller exists. <br>";
					return $segments;
				}
				
			}
		}
		
		/* application controller exists? */			
		if(is_file(APPPATH.'controllers/'.$module.$ext)) {
			return $segments;
		}
		
		/* application sub-directory controller exists? */
		if(is_file(APPPATH.'controllers/'.$module.'/'.$directory.$ext)) {
			$this->directory = $module.'/';
			return array_slice($segments, 1);
		}
	}

	public function set_class($class) {
		$this->class = $class.$this->config->item('controller_suffix');
	}
}