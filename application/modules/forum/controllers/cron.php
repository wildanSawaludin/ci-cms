<?php
/*
 * $Id$
 *
 *
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MX_Controller {
	var $template = array();

	function __construct()
	{
		parent::__construct();

		$this->template['module']	= 'forum';
		$this->load->model('forum_model', 'forum');
		$this->load->model('topic_model', 'topic');
		$this->load->model('message_model', 'message');
	}

	function latestmessage()
	{
		var_dump($this->cache->get('latestmessage', 'forum_messages'));
		if(!$rows = $this->cache->get('latestmessage', 'forum_messages'))
		{
			if($topics = $this->topic->get_list(array('limit' => 20, 'order_by' => 'last_date DESC')))
			{
				 $i = 0; 
				 foreach ($topics as $topic): 
					if($row = $this->message->get(array('where' => array('tid' => $topic['tid'], 'pid' => ''), 'limit' => 1, 'order_by' => 'date DESC')))
					{					 
						$rows[] = $row;
					}
				 $i++; 
				 endforeach; 
			
			}
			$this->cache->save('latestmessage', $rows, 'forum_messages', 0);
		}
	}
}
