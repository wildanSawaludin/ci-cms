<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Test extends CI_Controller {
		var $settings = array();
		function __construct()
		{
			parent::__construct();
			
		
		}
		
		function index()
		{
			var_dump($this);
		}
		
	}

?>