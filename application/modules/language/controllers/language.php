<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Language extends CI_Controller {
		
		function __construct()
		{
			parent::__construct();
			
			$this->template['module'] = "language";
					
		}
		
		function index()
		{
			echo "Found!";	
		}
		
		//all available blocks
		function set () 
		{

			$lang = $this->uri->segment(1);
			if (in_array($lang, $this->locale->codes)) 
			{
				$this->session->set_userdata('lang', $lang);
			} 
			else
			{
				$this->session->set_userdata('lang', $this->locale->default);
			}
			
			//echo $this->uri->uri_string();
			//exit;
			$redirect = str_replace("$lang/" , "", $this->uri->uri_string());

			redirect($redirect);
		}
	}
?>