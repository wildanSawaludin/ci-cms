<?php
/*
 * $Id$
 *
 *
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {
	var $template = array();

	function __construct()
	{
		parent::__construct();

		$this->template['module']	= 'forum';
		
		$this->load->library('administration');
		$this->template['admin'] = true;
		$this->load->model('forum_model', 'forum');
		$this->load->model('topic_model', 'topic');
		$this->load->model('message_model', 'message');
		
		$this->forum->get_user_level();
		
		//$this->plugin->add_action('header', array(&$this, '_write_header'));
	}

	/*
	list of forum
	*/

	function index()
	{
		$this->topics();

	}

	
	function topics()
	{
		$this->user->check_level('forum', LEVEL_EDIT);
		$this->template['title'] = __("Topic list", "forum");
		$params = array(
			'order_by' => 'title'
		);

		$this->template['rows'] = $this->topic->get_list($params);
		

		$this->layout->load($this->template, 'admin/topic/list');
		

	}

	function topic($action = null, $start = 0, $confirm = 0)
	{
		switch ($action)
		{
			case "add":
			case "create":
				$tid = $start;
				$this->user->check_level('forum', LEVEL_ADD);

				$this->template['title'] = __("Create a new topic", "forum");
				$this->template['topic'] = $this->topic->fields['forum_topics'];
				if($tid !== 0)
				{
					$this->user->check_level('forum', LEVEL_EDIT);
					$this->template['topic'] = $this->topic->get_topic($tid);
				}

				$this->layout->load($this->template, 'admin/topic/create');
			break;
			case "save":
				$data = array();
				if($tid = $this->input->post('tid'))
				{
					$topic = $this->topic->get_topic($tid);
					if($topic['username'] != $this->user->username)
					{
						$this->user->check_level('forum', LEVEL_EDIT);
					}
					$data['tid'] = $topic['tid'];
				}

				foreach($this->topic->fields['forum_topics'] as $key => $val)
				{
					if($this->input->post($key) !== FALSE)
					{
						$data[$key] = $this->input->post($key);
					}
				}

				if($data['tid'])
				{
					$this->topic->update_topic($data['tid'], $data);
				}
				else
				{
					$data['date'] = time();
					$data['username'] = $this->user->username;
					$data['email'] = $this->user->email;

					$data['tid'] = uniqid('t');
					$this->topic->save($data);
				}
				
				if($admins = $this->input->post('admins'))
				{
					$this->topic->save_admins($data['tid'], $admins);
					
				}
				$this->session->set_flashdata("notification", __("Topic saved succesfully", "forum"));
				redirect('admin/forum/topics');
				break;
			case "delete":
				$this->template['title'] = __("Delete topic", "forum");
				$tid = $start;
				if($tid === 0)
				{
					$this->template['message'] = __("Please specify a topic", "forum");
					$this->layout->load($this->template, "error");
					return;
				}
				$this->user->check_level('forum', LEVEL_DEL);

				$topic = $this->topic->get_topic($tid);
				$nb_msg = $this->message->get_total(array('where' => array ('tid' => $tid)));

				if ($nb_msg >0)
                {
					$this->template['message'] = __("The topic is not empty. Delete all messages in it then try again.", "forum");
					$this->layout->load($this->template, "error");
					return;
				}

				if($confirm > 0)
				{
					$this->topic->delete_topic($tid);
					$this->session->set_flashdata('notification', __("Topic deleted successfully", "forum"));
					redirect('admin/forum/topics');
					return;

				}
				else
				{
					$this->template['topic'] = $topic;
					$this->layout->load($this->template, "admin/topic/delete");
					return;
				}
			break;
			case "edit":
				$tid = $start;
				$this->template['title'] = __("Modify a topic", "forum");
				if($tid === 0)
				{
					$this->template['message'] = __("Please specify a topic", "forum");
					$this->layout->load($this->template, "error");
					return;
				}
				$this->user->check_level('forum', LEVEL_EDIT);
				if($topic = $this->topic->get_topic($tid))
				{
					$this->template['topic'] = $topic;
					
					$this->layout->load($this->template, "admin/topic/create");
				}
				else
				{
					$this->template['message'] = __("Topic not found", "forum");
					$this->layout->load($this->template, "error");
					return;

				}
			break;

			default:
				$tid = $action;
				if (is_null($tid) || $tid === 0)
				{
					redirect ('admin/forum/topics');
					return true;
				}


				$per_page = 20;
				$params = array(
				'where' => "tid = '" . $tid . "' ",
				'order_by' => 'title'
				);

				if ($topic = $this->topic->get($params))
				{
					$this->template['topic'] = $topic;
				}
				else
				{
					$this->template['title'] = __("Error", "forum");
					$this->template['message'] = __("The topic does not exist.", "forum");
					$this->layout->load($this->template,'error');
				}

				//now get messages
				$params = array(
				'where' => "tid = " . $this->db->escape($topic['tid']) . " AND pid = '' ",
				'order_by' => 'id desc',
				'limit' => $per_page,
				'start' => $start
				);


				if($messages = $this->message->get_list($params))
				{
					$this->load->library('pagination');

					$config['uri_segment'] = 5;
					$config['first_link'] = __('First', 'forum');
					$config['last_link'] = __('Last', 'forum');
					$config['base_url'] = base_url() . 'admin/forum/topic/' . $tid ;
					$config['total_rows'] = $this->message->get_total($params);
					$config['per_page'] = $per_page;

					$this->pagination->initialize($config);

					$this->template['messages'] = $messages;
					$this->template['title'] = $topic['title'];
					$this->template['start'] = $start;
					$this->template['pager'] = $this->pagination->create_links();
					$this->layout->load($this->template, 'admin/topic/index');

				}
				else
				{
					$this->template['title'] = __("No message found", "forum");
					$this->template['message'] = __("There is no message available in this topic", "forum")
					. "<br />" . anchor('forum/message/new/' . $tid, __("Add a new message", "forum"));

					$this->layout->load($this->template,'error');

				}

			break;
		}

	}


	function message($action = 'list', $start = 0, $confirm = 0)
	{
		switch($action)
		{
			case "edit":
				$this->user->check_level('forum', LEVEL_EDIT);
				$mid = $start;
				if($mid == 0)
				{
					$this->template['title'] = __("Error", "forum");
					$this->template['message'] = __("You did not choose a message", "forum");
					$this->layout->load($this->template, 'error');
					return;
				}
				$params = array(
					'where' => "mid = '" . $mid . "'"
				);
				$message = $this->message->get($params);
				$params = array(
					'where' => "tid = '" . $message[$tid] . "'"
				);
				$topic = $this->topic->get($params);
				$this->template['topic'] = $topic;
				$this->template['message'] = $message;

				$this->layout->load($this->template, 'message_create');
				return;
			break;
			case "save":
				$this->user->require_login();
				$title = strip_tags($this->input->post('title'));
				$message = strip_tags($this->input->post('message'));
				$tid = $this->input->post('tid');


				if(trim($message) == '' )
				{
					$this->template['title'] = __("Message required found", "forum");
					$this->template['message'] = __("You forgot to write the message", "forum");
					$this->layout->load($this->template,'error');
					return;

				}

				if($title === false)
				{
					$title = substr($message, 0, 50) . "...";
				}

				$data = array(
				'tid' => $tid,
				'pid' => $this->input->post('pid'),
				'date' => mktime(),
				'title' => $title,
				'message' => $message
				);
				if($this->input->post('mid'))
				{
					$data['mid'] = $this->input->post('mid');
					$this->message->update_message($data['mid'], $data);
				}
				else
				{
					$data['mid'] = uniqid('m');
					$data['last_date'] = $data['date'];
					$data['last_username'] = $this->user->username;
					$data['last_mid'] = ($data['pid'])? $data['pid'] . '#' . $data['mid']: $data['mid'];

					$this->message->save($data);
					$this->topic->update_topic($tid, array('last_mid' => $this->db->escape($data['last_mid']), 'last_username' => $this->db->escape($this->user->username), 'last_date' => $data['date'], 'messages' => 'messages+1'), false);
					if($data['pid'])
					{
						$this->message->update_message($data['pid'],  array('last_mid' => $this->db->escape($data['last_mid']), 'last_username' => $this->db->escape($this->user->username), 'last_date' => $data['date'], 'replies' => ' replies + 1'), false);
					}
				}

				redirect('admin/forum/topic/' . $tid);
			break;
			case "delete":
				$this->user->check_level('forum', LEVEL_DEL);
				$mid = $start;
				if($mid == 0)
				{

					$this->template['title'] = __("No message found", "forum");
					$this->template['message'] = __("There is no message to delete", "forum");
					$this->layout->load($this->template,'error');
					return;

				}

				//

				if ( $confirm > 0 )
				{
					$this->message->delete(array('mid' => $mid));

					$this->session->set_flashdata('notification', __('The message  has been deleted.', "forum"));

					redirect('admin/forum/topic/' . $message['tid'], 'refresh');
					return;
				}
				else
				{
					$this->template['title'] = __("Delete message?", "forum");
					$template['children'] = false;
					if($message['pid'] == '')
					{
						$template['children'] = $this->message->get_list(array('where' => array('pid' => $message['mid'])));
					}
					$this->layout->load($this->template, "admin/message/delete");
				}


			break;
			case "search":

				if($start != 0)
				{
					$tosearch = $start;
				}
				elseif ($this->input->post('tosearch'))
				{
					$tosearch = $this->input->post('tosearch');
				}
				else
				{
					$this->template['title'] = __("Error", "forum");
					$this->template['message'] = __("Nothing to search", "forum");
					$this->layout->load($this->template, "error");
					return;
				}

				$searchfield = array('title', 'message', 'author');

				if($infield = $this->input->post('infield'))
				{
					if(in_array($infield, $searchfield))
					{
						if($this->input->post('exactsearch') == 'on')
						{
							$params['where'] = $infield . " = '" . $this->input->post('tosearch') . "'";
						}
						else
						{
							$params['where'] = $infield . " LIKE '%" . $this->input->post('tosearch') . "%'";
						}
					}
					else
					{
						$this->template['title'] = __("Error", "forum");
						$this->template['message'] = __("No valid field", "forum");
						$this->layout->load($this->template, "error");
						return;
					}
				}
				else
				{

					if($this->input->post('exactsearch') == 'on')
					{
						$params['where']["title"] = $tosearch ;
						$params['or_where']["message"] = $tosearch ;
						$params['or_where']["author"]= $tosearch ;
					}
					else
					{
						$params['where'] = "title LIKE   '%" . $tosearch . "%' OR message LIKE   '%" . $tosearch . "%' OR author LIKE   '%" . $tosearch . "%'";
					}
				}

				$search_id = $this->message->save_params(serialize($params));
				$this->results($search_id);
				return;
			break;
			case "list":
			default:
				if (is_null($mid))
				{
					redirect ('admin/forum/topics');
					return true;
				}

			break;
		}

	}

	// message search
	function results($search_id = 0, $start = 0)
	{
		$params = array();

		//sorting

		if ($search_id != 0 && $tmp = $this->message->get_params($search_id))
		{
			$params = unserialize( $tmp);

		}

		$per_page = 20;
		$params['start'] = $start;

		$params['limit'] = $per_page;

		$this->template['rows'] = $this->message->get_list($params);
		//echo $this->db->last_query();

		$this->template['title'] = __("Search result", "forum");
		$config['first_link'] = __('First', 'forum');
		$config['last_link'] = __('Last', 'forum');
		$config['total_rows'] = $this->message->get_total($params);
		$config['per_page'] = $per_page;
		$config['base_url'] = base_url() . 'admin/forum/results/' . $search_id;
		$config['uri_segment'] = 5;
		$config['num_links'] = 20;
		$this->load->library('pagination');

		$this->pagination->initialize($config);

		$this->template['pager'] = $this->pagination->create_links();
		$this->template['start'] = $start;
		$this->template['total'] = $config['total_rows'];
		$this->template['per_page'] = $config['per_page'];
		$this->template['total_rows'] = $config['total_rows'];

		$this->layout->load($this->template, 'admin/results');

	}

	function search()
	{
		$this->title = __("Search messages", "forum");
		$this->layout->load($this->template, "search");

		return;
	}

}
