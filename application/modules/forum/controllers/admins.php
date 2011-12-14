<?php
/*
 * $Id$
 *
 *
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admins extends MX_Controller {
	var $template = array();

	function __construct()
	{
		parent::__construct();

		$this->template['module']	= 'forum';
		$this->load->model('forum_model', 'forum');
		$this->load->model('topic_model', 'topic');
		$this->load->model('message_model', 'message');

	}

	/*
	list of admins
	*/

	function index()
	{
		$this->template['title'] = __("Admin list", "forum");
		
		$this->template['rows'] = $this->topic->get_admin_list(array('order_by' => 'forum_admins.username, forum_topics.title '));

		$this->layout->load($this->template, 'admins');
	}
}
