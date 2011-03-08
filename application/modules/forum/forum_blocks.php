<?php 

$this->set('forum_get_messages', 'forum_get_messages');
function forum_get_messages($params)
{
	$obj =& get_instance();
	log_message('error', 'teto');
	$obj->load->model('forum/message_model');

	return $obj->message_model->get_list($params);
}

$this->set('forum_get_topics', 'forum_get_topics');
function forum_get_topics($params)
{
	$obj =& get_instance();
	$obj->load->model('forum/topic_model');

	return $obj->topic_model->get_list($params);
}

$this->set('forum_menu', 'forum_menu');
function forum_menu()
{
	$obj =& get_instance();
	
 $menu = array();
	
	
	//$menu[__("User menu", "forum")][__("Your profile", "forum")] = site_url("forum/profile");
		
	if($obj->user->logged_in)
	{
		$menu[__("User menu", "forum")][__("Change password", "forum")] = site_url('member/profile');
		$menu[__("User menu", "forum")][__("My posts", "forum")] = site_url("forum/message/user");
		$menu[__("User menu", "forum")][__("Sign out", "forum")] = site_url("member/logout");
	}
	else
	{
		$menu[__("User menu", "forum")][__("Forgot password", "forum")] = site_url('member/adino');
		$menu[__("User menu", "forum")][__("Sign in", "forum")] = site_url("member/login");
	}


$menu[ __("Forum", "forum") ] =
	array(
		__("Post a new message", "forum") => site_url("forum/message/new"),
		__("Topic list", "forum") => site_url("forum/topic"),
		__("Admin list", "forum") => site_url("forum/admins"),
		__("Search message", "forum") => site_url("forum/search"),
	);
	
	return $menu;
}