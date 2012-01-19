<?php
/*
 * $Id$
 *
 *
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message_model extends CI_Model {

	var $fields = array();
	function __construct()
	{
		parent::__construct();
		$this->fields = array(
			'forum_messages' => array(
				'mid'  => '',
				'pid'  => '',
				'tid'  => '',
				'title'  => '',
				'message' => '',
				'date' => mktime(),
				'username' => $this->user->username,
				'email' => $this->user->email,
				'messages' => 0,
				'last_mid' => '',
				'last_username' => '',
				'last_date' => time(),
				'notify' => ''
			)
		);

	}


	function get($params = array())
	{
		$default_params = array
		(
			'order_by' => 'id DESC',
			'limit' => 1,
			'start' => null,
			'where' => null,
			'like' => null,
		);

		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get' . $hash, 'forum_messages'))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);
			$this->db->limit(1);
			//$this->db->select('');
			$this->db->from('forum_messages');

			$query = $this->db->get();

			if ($query->num_rows() == 0 )
			{
				$result =  false;
			}
			else
			{
				$result = $query->row_array();
			}

			$this->cache->save('get' . $hash, $result, 'forum_messages', 0);
		}

		return $result;


	}

	function get_list($params = array())
	{
		$default_params = array
		(
			'order_by' => 'id DESC',
			'limit' => null,
			'start' => null,
			'where' => null,
			'like' => null,
		);

		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_list' . $hash, 'forum_messages'))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);
			$this->db->limit($params['limit'], $params['start']);
			//$this->db->select('');
			$this->db->from('forum_messages');

			$query = $this->db->get();

			if ($query->num_rows() == 0 )
			{
				$result =  false;
			}
			else
			{
				$result = $query->result_array();
			}

			$this->cache->save('get_list' . $hash, $result, 'forum_messages', 0);
		}

		return $result;


	}

	function get_total($params = array())
	{
		$default_params = array
		(
			'order_by' => 'id DESC',
			'limit' => null,
			'start' => null,
			'where' => null,
			'like' => null,
		);

		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_total' . $hash, 'forum_messages'))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);

			$this->db->select('count(id) as cnt');
			$this->db->from('forum_messages');

			$query = $this->db->get();

			$row = $query->row_array();

			$result = $row['cnt'];

			$this->cache->save('get_total' . $hash, $result, 'forum_messages', 0);
		}

		return $result;

	}

	function delete($params = array())
	{
		$this->db->where($params['where']);
		$this->db->delete('forum_messages');
		$this->cache->remove_group('forum_messages');
	}

	function save($data = array())
	{
		$this->db->set($data);
		$this->db->insert('forum_messages');
		$this->cache->remove_group('forum_messages');
	}

	function update($where = array(), $data = array(), $escape = true)
	{
		$this->db->where($where);
		$this->db->set($data, null, $escape);
		$this->db->update('forum_messages');
		$this->cache->remove_group('forum_messages');
	}

	function get_message($mid)
	{
		return $this->get(array('where' =>  array('mid' => $mid)));
	}

	function delete_message($id)
	{
		$this->delete(array('id' => $id));
	}

	function update_message($mid, $data, $escape = true)
	{
		$this->update(array('mid' => $mid), $data, $escape);
	}

	function get_params($id)
	{
		if($params = $this->cache->get($id, 'message_search_cache'))
		{
			return $params;
		}
		else
		{
			return false;
		}
	}

	function save_params($params)
	{
		if(is_array($params)) $params = serialize($params);
		$id = md5($params);
		if($this->cache->get($id, 'message_search_cache'))
		{
			return $id;
		}
		else
		{

			$this->cache->save($id, $params, 'message_search_cache', 0);
			return $id;
		}
	}

	function notify($pid)
	{
		//get all notified message
		$query = $this->db->query("SELECT DISTINCT username, email, title FROM " . $this->db->dbprefix('forum_messages') . " WHERE ( pid='" . $pid . "' OR mid='" . $pid . "' ) AND notify='Y' AND username <> '" . $this->user->username . "'");
		if($query->num_rows() > 0)
		{
			$notified = array();
			$this->load->library('email');
			foreach($query->result_array() as $row)
			{
				if(!isset($notified[ $row['email'] ]))
				{
					$this->email->clear();
					$this->email->from($this->system->admin_email, "Admin " . $this->system->site_name );
					$this->email->to($row['email']);
					$subject = '[' . $this->system->site_name . '] ' . sprintf(__("Reply from %s", "forum"), $this->user->username);
					$this->email->subject($subject);
					$message = sprintf(__("Hello %s,\n\nYour message %s has been replied by %s.\n To read the message click the link below\n\n%s\n\n. If you don't want to receive any further notification, go to link below.\n\n%s\n\nThank you.\nAdministrator", "forum"), $row['username'], '"' . $row['title'] . '"' , $this->user->username, site_url('forum/message/' . $pid), site_url('forum/unsubscribe/' . $pid));

					$this->email->message($message);
					$this->email->send();
					$notified[ $row['email'] ] = 1;
				}
			}
		}
		
	}
}
