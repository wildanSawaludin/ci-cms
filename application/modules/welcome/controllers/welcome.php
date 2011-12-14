<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MX_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('welcome_message');
	}
	
	function test2()
	{
		echo modules::run('test');
	}
	
	function test3()
	{
		echo modules::run('test/test3');
	}
	
	function test4()
	{
		echo modules::run('test/test4');	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */