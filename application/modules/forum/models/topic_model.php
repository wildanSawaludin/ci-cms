<?php 
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Topic_model extends CI_Model {

	var $fields = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->fields = array(
			'forum_topics' => array(
				'id' => 0,
				'tid'  => '',
				'title'  => '',
				'description' => '',
				'date' => mktime(),
				'username' => $this->user->username,
				'email' => $this->user->email,
				'messages' => 0,
				'last_mid' => '',
				'last_username' => '',
				'last_date' => time(),
				'gid' => '0'
				
			),
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
		if(!$result = $this->cache->get('get' . $hash, 'topics'))
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
			$this->db->from('forum_topics');
			
			$query = $this->db->get();

			if ($query->num_rows() == 0 )
			{
				$result =  false;
			}
			else
			{
				$result = $query->row_array();
			}
			
			$this->cache->save('get' . $hash, $result, 'topics', 0);
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
		$this->cache->remove_group('topics');
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_list' . $hash, 'topics'))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				if(is_array($params['where']) && isset($params['where']['tid']))
				{
					// to avoid rewrite all call to tid
					$params['where']['forum_topics.tid'] = $params['where']['tid'];
					$params['where']['tid'] = null;
				} 
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);
			$this->db->limit($params['limit'], $params['start']);
			//$this->db->select('forum_topics.*, count(forum_messages.id) as messages');

			$this->db->from('forum_topics');
			//$this->db->join('forum_messages', 'forum_topics.tid=forum_messages.tid', 'left');
			
			$query = $this->db->get();
			

			if ($query->num_rows() == 0 )
			{
				$result =  false;
			}
			else
			{
				$result = $query->result_array();
			}
			
			$this->cache->save('get_list' . $hash, $result, 'topics', 0);
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
		if(!$result = $this->cache->get('get_total' . $hash, 'topics'))
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
			$this->db->from('forum_topics');
			
			$query = $this->db->get();

			$row = $query->row_array();
			
			$result = $row['cnt'];
			
			$this->cache->save('get_total' . $hash, $result, 'topics', 0);
		}
		
		return $result;
		
	}
	
	function delete($params = array())
	{
		$this->db->where($params['where']);
		$this->db->delete('forum_topics');
		$this->cache->remove_group('topics');
	}
	
	function save($data = array())
	{
		$this->db->set($data);
		$this->db->insert('forum_topics');
		$this->cache->remove_group('topics');
	}
	
	function update($where = array(), $data = array(), $escape = true)
	{
		$this->db->where($where);
		$this->db->set($data, null, $escape);
		$this->db->update('forum_topics');
		$this->cache->remove_group('topics');
	}
	
	function get_topic($tid)
	{
		$topic = $this->get(array('where' =>  array('tid' => $tid)));
		
		$query = $this->db->get_where('forum_admins', array('tid' => $tid));
		if($query->num_rows() >0)
		{
			$topic['admins'] = $query->result_array();
		}
		
		return $topic;
	}
	
	function delete_topic($tid)
	{
		$this->delete(array('where' => array('tid' => $tid)));
	}
	
	function update_topic($tid, $data, $escape = true)
	{
		$this->update(array('tid' => $tid), $data, $escape);
	}	

	function get_params($id)
	{
		if($params = $this->cache->get($id, 'topic_search_cache'))
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
		$id = md5($params);
		if($this->cache->get($id, 'topic_search_cache'))
		{
			return $id;
		}
		else
		{
		
			$this->cache->save($id, $params, 'topic_search_cache', 0);
			return $id;
		}
	}


}
