<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {
	var $template; {

	function __construct()
	{
		parent::__construct();
	
		$this->load->library('administration');

		$this->template['module']	= 'projects';
		$this->template['admin']		= true;
		$this->load->library('country');

	}
	
