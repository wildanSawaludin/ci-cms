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
	
	function test5()
	{
		$this->load->library('autologon');
		
		// Create new token  0.
		if($this->autologon->new_token('tom'))
			echo "Token Created";
		else
			echo "Token cannot be created!";
		
	}
	
	function test6()
	{
		$this->load->library('autologon');
		
		// Get token
		if($token = $this->autologon->get())
			print_r($token);
		else
			echo "Cannot get token";	
	}
	
	function test7()
	{
		$this->load->library('autologon');

		if($this->autologon->validate())
		{
			echo "Autologon Valid";
		}
		else
		{
			echo "Autologon Invalid!!!";
		}
		
		if($token = $this->autologon->get())
			print_r($token);
		
	}
	
	function test8()
	{
		if($this->autologon->has_autologon())
		{
			echo "Has autologon ";
		}
		else
		{
			echo "Autologon not found!";
		}	
	}
	
	
	function test9()
	{
		$this->user->require_login();
		
		echo "Logged in<br>";
		echo $this->user->is_autologin?"Auto Login":"Manual Login";
		echo "<br>";
		echo "Logged_in: ".$this->user->logged_in." <br>";
			
	}
	
	function test10()
	{
		$this->user->require_manual_login();
		
		echo "Manually Logged in <br>";
		echo $this->user->is_autologin?"Auto Login":"Manual Login";
		echo "<br>";
		echo "Logged_in: ".$this->user->logged_in." <br>";
		
			
	}
	
}

?>