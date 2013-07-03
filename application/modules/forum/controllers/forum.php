<?php
/*
 * $Id$
 *
 *
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends MX_Controller {
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
		$this->topics();


	}

	
	function topics()
	{
		$this->template['title'] = __("Topic list", "forum");
		$params = array(
		'where' => "username =  '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "') ",
		'order_by' => 'title'
		);

		$this->template['rows'] = $this->topic->get_list($params);

		$this->layout->load($this->template, "topic/list" );

	}

	function topic($action = null, $start = 0)
	{
		switch ($action)
		{
			case "add":
			case "create":
				$tid = $start;
				if($this->user->level['forum'] < LEVEL_ADD)
				{
					$this->template['title'] = __("Not authorized", "forum") ;
					$this->template['message'] = __("You cannot add a new topic.", "forum");
					$this->layout->load($this->template, 'error');
					return;
				}
				$this->template['title'] = __("Create a new topic", "forum");
				$this->template['topic'] = $this->topic->fields['forum_topics'];
				if($tid !== 0)
				{
					$this->user->check_level('forum', LEVEL_EDIT);
					$this->template['topic'] = $this->topic->get_topic($tid);
				}

				$this->layout->load($this->template, 'topic/create');
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

				$this->session->set_flashdata(__("Topic saved succesfully"), "forum");
				redirect('forum/topics');
				//redirect('forum/topic/' . $data['tid']);
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
				$this->check_level('forum', LEVEL_DEL);


				$nb_msg = $this->message->get_total(array('where' => array ('tid' => $tid)));
				if ($nb_msg >0)
				{
					$this->template['message'] = __("The topic is not empty. Delete all messages in it then try again.", "forum");
					$this->layout->load($this->template, "error");
					return;
				}

				if($confirm > 0)
				{
					$this->topic->delete(array('tid' => $tid));
					$this->session->set_flashdata('notification', __("Topic deleted successfully", "forum"));
					redirect('forum/topics');
					return;

				}
				else
				{
					$this->layout->load($this->template, "topic_delete");
					return;
				}
			break;
			case "edit":
				$tid = $start;
				if($tid === 0)
				{
					$this->template['message'] = __("Please specify a topic", "forum");
					$this->layout->load($this->template, "error");
					return;
				}
				$this->user->check_level('forum', LEVEL_EDIT);
				if($topic = $this->topic->get(array('where' => array('tid' => $tid), 'limit' =>1)))
				{
					$this->template['topic'] = $topic;
					$this->layout->load($this->template, "topic_add");
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
					redirect ('forum/topics');
					return true;
				}


				$per_page = 20;
				$params = array(
				'where' => "tid = '" . $tid . "' AND (username = '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "')) ",
				'order_by' => 'title'
				);

				if ($topic = $this->topic->get($params))
				{
					$this->template['topic'] = $topic;
				}
				else
				{
					$this->template['title'] = __("Not authorized", "forum");
					$this->template['message'] = __("You cannot view this topic or the topic does not exist.", "forum");
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

					$config['uri_segment'] = 4;
					$config['first_link'] = __('First', 'forum');
					$config['last_link'] = __('Last', 'forum');
					$config['base_url'] = base_url() . 'forum/topic/' . $tid ;
					$config['total_rows'] = $this->message->get_total($params);
					$config['per_page'] = $per_page;

					$this->pagination->initialize($config);

					$this->template['messages'] = $messages;
					$this->template['title'] = $topic['title'];
					$this->template['start'] = $start;
					$this->template['pager'] = $this->pagination->create_links();
					$this->layout->load($this->template, 'topic/index');

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

	function _write_header()
	{
		echo "
		<link rel='stylesheet' href='" . base_url() . "application/modules/forum/js/jquery/jquery-bbcode-editor-4/style.css' />
		<script type='text/javascript' src='" . base_url() . "application/modules/forum/js/jquery/jquery-bbcode-editor-4/jquery.bbcodeeditor-1.0.min.js'></script>
		<script type=\"text/javascript\">
		$(document).ready(function(){
				$('textarea.bbcode').bbcodeeditor(
				{
					bold:$('.bold'),italic:$('.italic'),
					underline:$('.underline'),link:$('.link'), quote:$('.quote'),code:$('.code'),image:$('.image'),
					usize:$('.usize'),dsize:$('.dsize'),nlist:$('.nlist'),blist:$('.blist'),litem:$('.item'),youtube:$('.youtube'),dailymotion:$('.dailymotion'),
					back:$('.back'),forward:$('.forward'),back_disable:'btn back_disable',forward_disable:'btn forward_disable',
					exit_warning:true
				});
				$('form.edit').submit(function(){
					$().bbcodeeditor.pause();
				});
			});
		</script>

		";
		echo '<link rel="stylesheet" href="' . site_url() . '/application/modules/forum/css/' . $this->forum->settings['style'] . '.css" type="text/css" media="screen" charset="utf-8" />';
	}

	
	function message($action = 'list', $start = 0, $confirm = 0)
	{
		switch($action)
		{
			case "move":
				$this->user->require_login();
				$this->template['title'] = __("Move a message", "forum");
				$mid = $start;
				if($mid === 0)
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
					'where' => "tid = '" . $message['tid'] . "'"
				);
				
				$topic = $this->topic->get($params);
				
				if($this->user->level['forum'] < LEVEL_EDIT && !isset($this->user->forum_level[ $topic['tid'] ]))
				{
					$this->template['title'] = __("Error", "forum");
					$this->template['message'] = __("You cannot move this message", "forum");
					$this->layout->load($this->template, 'error');
					return;
				}
				
				if($message['pid'] != '')
				{
					$this->template['title'] = __("Error", "forum");
					$this->template['message'] = __("You cannot move this message", "forum");
					$this->layout->load($this->template, 'error');
					return;
				
				}
				
				if(!$this->input->post('submit'))
				{
					$this->template['message'] = $message;
					$params = array(
						'where' => "(username = '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "')) ",
						'order_by' => 'title'
						);
					$topics = $this->topic->get_list($params);
					$this->template['topics'] = $topics;
					$this->layout->load($this->template, 'message/move');
				}
				else
				{
					$tid = $this->input->post('tid');
					$this->message->update(array('mid' => $mid), array('tid' => $tid));
					$this->session->set_flashdata('notification', __("Message is now moved", "forum"));
					
					redirect('forum/message/' . $mid);
					return;
				}
				
			break;
			case "user":
				//user's message
				$username = $start;
				if($username === 0)
				{
					$this->user->require_login();
					$username = $this->user->username;
				}
				$params = array();
				
				$params['where'] = array('username' => $username, 'pid' => '');
				$params['order_by'] = 'id DESC';
				$params['title'] = sprintf(__("%s's messages", "forum"), $username);

				$search_id = $this->message->save_params(serialize($params));
				

				$this->results($search_id);
			
				
			break;
			case "reply":

				$this->user->require_login();
			    $mid = $start;
			    $quote = $confirm;
			    if ($mid === 0)
			    {
				$this->template['title'] = __("Reply error", "forum");
				$this->template['message'] = __("You did not choose a message to reply to", "forum");
				$this->layout->load($this->template, "error");
				return;
			    }
			    $message = $this->message->get_message($mid);
			    if($message === false)
			    {
				$this->template['title'] = __("Reply error", "forum");
				$this->template['message'] = __("Message not found", "forum");
				$this->layout->load($this->template, "error");
				return;
			    }
			    $reply = $this->message->fields['forum_messages'];

			    if ($quote !== 0 && $tmp = $this->message->get_message($quote))
			    {
				$reply['message'] = "\n\n[quote=" . $tmp['username'] . "]\n" . $tmp['message'] . "\n[/quote]";
			    }
			    $reply['pid'] = $message['mid'];
			    $reply['title'] = "Re: " . $message['title'];


			    $params = array(
			    'where' => "tid = '" . $message['tid'] . "' AND (username = '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "')) ",
			    'order_by' => 'title'
			    );
			    $topic = $this->topic->get($params);
			    if($topic === false)
			    {
				//should not happen but...
				$this->template['title'] = __("Posting error", "forum");
				$this->template['message'] = __("You are not allowed to reply the message", "forum");
				$this->layout->load($this->template, "error");
				return;
			    }

			    $this->template['topic'] = $topic;
			    $this->template['message'] = $reply;
			    $this->template['title'] = $reply['title'];
			    $this->layout->load($this->template, "message/create");

			break;
			case "add":
			case "new":
				$this->user->require_login();

				$this->template['title'] = __("Create a new message", $this->template['module']);
				$tid = $start;
				$topic = false;

				if ($tid != '0')
				{
					$params = array(
					'where' => "tid = '" . $tid . "' AND (username = '" . $this->user->username . "' OR  gid IN ('" . implode("', '", $this->user->groups) . "')) ",
					'order_by' => 'title'
					);
					$topic = $this->topic->get($params);
				}

				$this->template['message'] = $this->message->fields['forum_messages'];

				$params = array(
					'where' => "(username = '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "')) ",
					'order_by' => 'title'
					);
				$topics = $this->topic->get_list($params);

				if($topics === false)
				{
				    $this->template['title'] = __("Posting error", "forum");
				    $this->template['message'] = __("There is no topic available", "forum");
				    $this->layout->load($this->template, "error");
				    return;
				}


				$this->template['topic'] = $topic;
				$this->template['topics'] = $topics;				
				$this->layout->load($this->template, 'message/create');
				return;

			break;
			case "edit":
				$this->user->require_login();
				$this->template['title'] = __("Modify message", "forum");
				$mid = $start;
				if($mid === 0)
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
					'where' => "tid = '" . $message['tid'] . "'"
				);
				
				$topic = $this->topic->get($params);
				
				if($this->user->level['forum'] < LEVEL_EDIT && !isset($this->user->forum_level[ $topic['tid'] ]) && ($message['username'] != $this->user->username || $message['messages'] > 0))
				{
					$this->template['title'] = __("Error", "forum");
					$this->template['message'] = __("You cannot edit this message", "forum");
					$this->layout->load($this->template, 'error');
					return;
				}
				
				$params = array(
					'where' => "(username = '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "')) ",
					'order_by' => 'title'
					);
				$topics = $this->topic->get_list($params);

				if($topics === false)
				{
				    $this->template['title'] = __("Posting error", "forum");
				    $this->template['message'] = __("There is no topic available", "forum");
				    $this->layout->load($this->template, "error");
				    return;
				}


				$this->template['topic'] = $topic;
				$this->template['topics'] = $topics;
				$this->template['message'] = $message;

				$this->layout->load($this->template, 'message/create');
				return;
			break;
			case "save":
				$this->user->require_login();
				$title = strip_tags($this->input->post('title'));
				$message = strip_tags($this->input->post('message'));
				

				$tid = $this->input->post('tid');
				if($tid === false)
				{
					$this->template['title'] = __("Topic missing", "forum");
					$this->template['message'] = __("Please go back and choose a topic", "forum");
					$this->layout->load($this->template,'error');
					return;
				}
				
				if(trim($message) == '' )
				{
					$this->template['title'] = __("Message required", "forum");
					$this->template['message'] = __("You forgot to write the message", "forum");
					$this->layout->load($this->template,'error');
					return;

				}

				if(trim($title) == '')
				{
					$title = substr($message, 0, 50) . "...";
				}

				$data = array(
				'pid' => $this->input->post('pid'),
				'date' => mktime(),
				'title' => $title,
				'message' => $message
				);
				
				if($this->input->post('preview'))
				{
					$data['tid'] = $this->input->post('tid');
					$data['mid'] = $this->input->post('mid');
					$data['notify'] = $this->input->post('notify');
					
					$params = array(
					'where' => "(username = '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "')) ",
					'order_by' => 'title'
					);
					
					$topics = $this->topic->get_list($params);
					
					
					$this->template['topics'] = $topics;
					$this->template['data'] = $data;
					$this->template['title'] = __("Preview message", "forum");
					$this->layout->load($this->template, 'message/preview');
					return;
				}
				if($this->input->post('mid'))
				{
					if(!$this->input->post('pid') || $this->input->post('pid') == '')
					{
						$data['tid'] = $this->input->post('tid');
					}
					$data['mid'] = $this->input->post('mid');
					$this->message->update_message($data['mid'], $data);
				}
				else
				{
					$data['tid'] = $this->input->post('tid');
					$data['mid'] = uniqid('m');
					$data['username'] = $this->user->username;
					$data['email'] = $this->user->email;
					$data['last_date'] = $data['date'];
					$data['last_username'] = $this->user->username;
					$data['last_mid'] = ($data['pid'])? $data['pid'] . '#' . $data['mid']: $data['mid'];
					$data['notify'] = $this->input->post('notify');
				
                    $this->message->save($data);
					$this->plugin->do_action('forum_message_save', $data);
					$this->topic->update_topic($data['tid'], array('last_mid' => $this->db->escape($data['last_mid']), 'last_username' => $this->db->escape($this->user->username), 'last_date' => $data['date'], 'messages' => 'messages+1'), false);
					if($data['pid'])
					{
						$this->message->update_message($data['pid'],  array('last_mid' => $this->db->escape($data['last_mid']), 'last_username' => $this->db->escape($this->user->username), 'last_date' => $data['date'], 'replies' => ' replies + 1'), false);
					}
					
					if($data['pid']) $this->message->notify($data['pid']);
				}
				$this->session->set_flashdata("notification", __("Message saved succesfully", "forum"));

				redirect('forum/topic/' . $tid);
			break;
			case "delete":
				
				$mid = $start;
				if($mid === 0)
				{

					$this->template['title'] = __("No message found", "forum");
					$this->template['message'] = __("There is no message to delete", "forum");
					$this->layout->load($this->template,'error');
					return;

				}
				if(!$message = $this->message->get_message($mid))
				{
					$this->template['title'] = __("No message found", "forum");
					$this->template['message'] = __("There is no message to delete", "forum");
					$this->layout->load($this->template,'error');
					return;
				}

				$params = array(
					'where' => "tid = '" . $message['tid'] . "'"
				);
				
				$topic = $this->topic->get($params);
				
				
				if($this->user->level['forum'] < LEVEL_DEL &&  !isset( $this->user->forum_level[ $topic['tid'] ])  && $this->user->forum_level[ $topic['tid'] ] < LEVEL_DEL && $message['username'] != $this->user->username)
				{
					$this->template['title'] = __("Error", "forum");
					$this->template['message'] = __("You cannot delete this message", "forum");
					$this->layout->load($this->template, 'error');
					return;
				}

				if ( $confirm > 0 )
				{
					$this->message->delete(array('where' =>array('mid' => $mid)));

					$this->session->set_flashdata('notification', __('The message  has been deleted.', "forum"));
					if($message['pid'])
					{
						redirect('forum/message/' . $message['pid'], 'refresh');
						return;
					}
					else
					{
						redirect('forum/topic/' . $message['tid'], 'refresh');
						return;
					}
				}
				else
				{
					$this->template['title'] = __("Delete message?", "forum");
					$template['children'] = false;
					
					if($message['pid'] == '')
					{
						$message['children'] = $this->message->get_total(array('where' => array('pid' => $message['mid'])));
					}
					$this->template['message'] = $message;
					$this->layout->load($this->template, "message/delete");
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
					$this->template['title'] = __("Search", "forum");
					$this->layout->load($this->template, "search");
					return;
				}

				$searchfield = array('title', 'message', 'username');

				if($infield = $this->input->post('infield'))
				{
					if(in_array($infield, $searchfield))
					{
						if($this->input->post('exactsearch') == 'on')
						{
							$params['where'] =  $infield . " = '" . $this->input->post('tosearch') . "'";
						}
						else
						{
							$params['where'] =   $infield . " LIKE '%" . $this->input->post('tosearch') . "%'";
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
						$params['or_where']["username"]= $tosearch ;
					}
					else
					{
						$params['where'] = "title LIKE   '%" . $tosearch . "%' OR message LIKE   '%" . $tosearch . "%' OR username LIKE   '%" . $tosearch . "%'";
					}
				}

				$search_id = $this->message->save_params(serialize($params));


				$this->results($search_id);
				return;
			break;
			case "list":
				if (is_null($mid))
				{
					redirect ('forum/topics');
					return true;
				}

			break;
			default:
				$mid = $action;
				$per_page = 20;
				// allowed to read the message?
				// the only way to get that is from its topic

				$message = $this->message->get(array('where' => array('mid' => $mid)));

				$params = array(
				'where' => "tid = '" . $message['tid'] . "' AND (username = '" . $this->user->username . "' OR gid IN ('" . implode("', '", $this->user->groups) . "')) ",
				'order_by' => 'title'
				);

				if ($topic = $this->topic->get($params))
				{
					$this->template['topic'] = $topic;
				}
				else
				{
					$this->template['title'] = __("Not authorized", "forum");
					$this->template['message'] = __("You cannot view this message or the message does not exist.", "forum");
					$this->layout->load($this->template,'error');
					return;
				}

				//now get messages
				$params = array(
				'where' => "mid = '" . $message['mid'] . "' OR pid = '" . $message['mid'] . "' ",
				'order_by' => 'id',
				'limit' => $per_page,
				'start' => $start
				);


				if($messages = $this->message->get_list($params))
				{

					$this->load->library('pagination');

					$config['uri_segment'] = 4;
					$config['first_link'] = __('First', 'forum');
					$config['last_link'] = __('Last', 'forum');
					$config['base_url'] = base_url() . 'forum/message/' . $mid ;
					$config['total_rows'] = $this->message->get_total($params);
					$config['per_page'] = $per_page;

					$this->pagination->initialize($config);

					$this->template['messages'] = $messages;
					$this->template['title'] = $message['title'];
					$this->template['start'] = $start;
					$this->template['pid'] = $message['mid'];
					$this->template['pager'] = $this->pagination->create_links();
					$this->message->update("mid = '" . $message['mid'] . "' OR pid = '" . $message['mid'] . "' ", array('hits' => 'hits+1'), false);
					$this->layout->load($this->template, 'message/index');
					

				}
				else
				{
					$this->template['title'] = __("No message found", "forum");
					$this->template['message'] = __("There is no message available in this topic", "forum");
					$this->layout->load($this->template,'error');

				}
			break;
		}

	}

	// message search
	function results($search_id = 0, $start = 0)
	{
		$params = array();

		//sorting

		if ($search_id !== 0 && $tmp = $this->message->get_params($search_id))
		{
			$params = unserialize( $tmp);

		}

		$per_page = 20;
		$params['start'] = $start;

		$params['limit'] = $per_page;

		$this->template['rows'] = $this->message->get_list($params);
		//echo $this->db->last_query();
		
		if(isset($params['title'])) $this->template['title'] = $params['title'];

		if(!isset($this->template['title'])) $this->template['title'] = __("Search result", "forum");
		$config['first_link'] = __('First', 'forum');
		$config['last_link'] = __('Last', 'forum');
		$config['total_rows'] = $this->message->get_total($params);
		$config['per_page'] = $per_page;
		$config['base_url'] = base_url() . 'forum/results/' . $search_id;
		$config['uri_segment'] = 4;
		$config['num_links'] = 20;
		$this->load->library('pagination');

		$this->pagination->initialize($config);

		$this->template['pager'] = $this->pagination->create_links();
		$this->template['start'] = $start;
		$this->template['total'] = $config['total_rows'];
		$this->template['per_page'] = $config['per_page'];
		$this->template['total_rows'] = $config['total_rows'];

		$this->layout->load($this->template, 'results');

	}

	function search()
	{
		$this->template['title'] = __("Search messages", "forum");
		$this->layout->load($this->template, "search");

		return;
	}

	function unsubscribe($mid)
	{
		$this->user->require_login();
		$where = "notify = 'Y' AND `username` = '" . $this->user->username . "' AND ( `mid` = '" . $mid . "' OR `pid` = '" . $mid . "') ";
		$data = array('notify' => 'N');
		$this->message->update($where, $data);
		
		$this->template['title'] = __("Unsubscribed", "forum");
		$this->template['message'] = __("You will no longer receive notification about that message", "forum");
		$this->layout->load($this->template, "error");
	}
}
