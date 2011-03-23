<?php
/*
 * $Id$
 *
 *
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rss extends MX_Controller {
	var $template = array();

	function __construct()
	{
		parent::__construct();

		$this->template['module']	= 'forum';
		$this->load->model('forum_model', 'forum');
		$this->load->model('topic_model', 'topic');
		$this->load->model('message_model', 'message');
		$this->load->library("bbcode");
		$this->forum->get_user_level();
		$this->forum->load_bbcode();
		
		$this->plugin->add_action('header', array(&$this, '_write_header'));
	}

	/*
	list of forum
	*/

	function index()
	{
		$channel['title'] = __("Last messages", "forum");
		$channel['link'] = site_url('forum');
		$channel['description'] = __("Last posted messages", "forum");
		
		$rows = $this->message->get_list(array('where' => array('pid' => ''), 'order_by' => 'date desc', 'limit' => 20));
		$items = array();
		
		foreach ($rows as $key => $row)
		{
			$items[$key]['title'] = $row['title'];
			$items[$key]['author'] = $row['username'];
			$items[$key]['link'] = site_url('forum/message/' . $row['mid']);
			$items[$key]['guid'] =  site_url('forum/message/' . $row['mid']);
			$items[$key]['description'] = "<![CDATA[ " . nl2br($this->bbcode->parse(strip_tags($row['message']))) . "]]>";
			$items[$key]['date'] = $row['date'];
		}
		
		$this->template['channel'] = $channel;
		$this->template['items'] = $items;
		
		header("Content-Type: application/rss+xml");
		$this->load->view('rss', $this->template);
		
	}
	
	function updates()
	{
		$channel['title'] = __("Last updates", "forum");
		$channel['link'] = site_url('forum');
		$channel['description'] = __("Last posted replies", "forum");
		
		$rows = $this->message->get_list(array('order_by' => 'date desc', 'limit' => 20));
		$items = array();
		
		foreach ($rows as $key => $row)
		{
			$items[$key]['title'] = substr(strip_tags($row['message']), 0, 20) ."... ";
			$items[$key]['author'] = $row['username'];
			$items[$key]['link'] = ($row['pid'] == '') ? site_url('forum/message/' . $row['mid']) : site_url('forum/message/' . $row['pid'] . '/' . $row['mid']);
			$items[$key]['guid'] =  ($row['pid'] == '') ? site_url('forum/message/' . $row['mid']) : site_url('forum/message/' . $row['pid'] . '/' . $row['mid']);
			$items[$key]['description'] = "<![CDATA[ " . nl2br($this->bbcode->parse(strip_tags($row['message']))) . "]]>";
			$items[$key]['date'] = $row['date'];
		}
		
		$this->template['channel'] = $channel;
		$this->template['items'] = $items;
		
		header("Content-Type: application/rss+xml");
		$this->load->view('rss', $this->template);
	}
	
	function topic($tid = null)
	{
		if (is_null($tid)) return;
		
		if($topic = $this->topic->get_topic($tid))
		{
			$channel['title'] = sprintf(__("Topic: %s", "forum"), $topic['title']);
			$channel['link'] = site_url('forum');
			$channel['description'] = sprintf(__("Last posted from %s", "forum"), $topic['title']);
			
			$rows = $this->message->get_list(array('where' => array('tid' => $tid, 'pid' => ''), 'order_by' => 'date desc', 'limit' => 20));
			$items = array();
			
			foreach ($rows as $key => $row)
			{
				$items[$key]['title'] = $row['title'];
				$items[$key]['author'] = $row['username'];
				$items[$key]['link'] = ($row['pid'] == '') ? site_url('forum/message/' . $row['mid']) : site_url('forum/message/' . $row['pid'] . '/' . $row['mid']);
				$items[$key]['guid'] =  ($row['pid'] == '') ? site_url('forum/message/' . $row['mid']) : site_url('forum/message/' . $row['pid'] . '/' . $row['mid']);
				$items[$key]['description'] = "<![CDATA[ " . nl2br($this->bbcode->parse(strip_tags($row['message']))) . "]]>";
				$items[$key]['date'] = $row['date'];
			}
			
			$this->template['channel'] = $channel;
			$this->template['items'] = $items;
			
			header("Content-Type: application/rss+xml");
			$this->load->view('rss', $this->template);
		}
		else
		{
			$this->template['title'] = __("No message found", "forum");
			$this->template['message'] = __("There is no message to delete", "forum");
			$this->layout->load($this->template,'error');
			return;
			
		}
	}
	
	function message($mid = null)
	{
		if (is_null($mid))
		{
			$this->template['title'] = __("No message found", "forum");
			$this->template['message'] = __("There is no message to delete", "forum");
			$this->layout->load($this->template,'error');
			return;
		
		}
		
		$rows = $this->message->get_list(array('where' => "mid = '" . $mid . "' OR pid = '" . $mid . "'", 'order_by' => 'pid, date desc'));
		$channel['title'] = sprintf(__("Thread: %s", "forum"), $rows['0']['title']);
		$channel['link'] = site_url('forum');
		$channel['description'] = sprintf(__("Thread about the message %s", "forum"), $rows['0']['title']);
		
		$items = array();
		$i = 0;
		foreach ($rows as $key => $row)
		{
			
			$items[$key]['title'] = '';
			if($i > 0) $items[$key]['title'] .= $i . ". ";
			$items[$key]['title'] .= $rows['0']['title'];
			$items[$key]['author'] = $row['username'];
			$items[$key]['link'] = ($row['pid'] == '') ? site_url('forum/message/' . $row['mid']) : site_url('forum/message/' . $row['pid'] . '/' . $row['mid']);
			$items[$key]['guid'] =  ($row['pid'] == '') ? site_url('forum/message/' . $row['mid']) : site_url('forum/message/' . $row['pid'] . '/' . $row['mid']);
			$items[$key]['description'] = "<![CDATA[ " . nl2br($this->bbcode->parse(strip_tags($row['message']))) . "]]>";
			$items[$key]['date'] = $row['date'];
			$i++;
		}
		
		$this->template['channel'] = $channel;
		$this->template['items'] = $items;
		
		header("Content-Type: application/rss+xml");
		$this->load->view('rss', $this->template);
		
	}
}
