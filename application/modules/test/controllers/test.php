<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('test_view');
	}
	
	function test2()
	{
		echo "Test Two";
	}
	
	function test3()
	{
		$this->load->library('javascripts');	
		
		$this->javascripts->add('test1.js');
		
		print_r($this->javascripts->get());
	}
	
	function test4()
	{
		$this->load->library('administration');
		
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */