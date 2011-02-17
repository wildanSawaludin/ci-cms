<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 |-----------------------------------------------------------------------
 | Language Public Controller
 |-----------------------------------------------------------------------
 */

	class Language extends MX_Controller {
		
		var $template = array();
		
		function __construct()
		{
			parent::__construct();
			
			// Not truely required as it should be auto-loaded
			$this->load->library('locale');
			
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
			
			if($this->uri->uri_string() == $lang)
			{
				$redirect = base_url();
			}
			else
			{
				$redirect = str_replace("$lang/" , "", $this->uri->uri_string());
			}
			
			redirect($redirect);
		}
	}
?>