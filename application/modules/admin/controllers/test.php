<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Test extends MX_Controller {
		
		
		function __construct()
		{

			parent::__construct();
			
			$this->load->library('administration');
		}
		
		function index()
		{
			var_dump(format_title("Inona izao no mety Hasehon'ity?"));
		}


	}

?>