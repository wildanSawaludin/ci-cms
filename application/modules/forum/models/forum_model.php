<?php 
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forum_model extends CI_Model {

	var $options = array();
	
	function __construct()
	{
		parent::__construct();
	}
	
	function get_user_level()
	{
		$level = array();
		$query = $this->db->query("SELECT username, level, tid FROM " . $this->db->dbprefix('forum_admins') . " WHERE username = " . $this->db->escape($this->user->username));
		if($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$level[$row['tid']] = $row['level'];
			}
		}
		$this->user->forum_level = $level;
	}
	
	function get_option($name, $default = null)
	{
		if(is_empty($this->options))
		{
			$query = $this->db->get('forum_options');
			
			if($query->num_rows() > 0)
			{
				$rows = $query->result_array();
				foreach($rows as $row)
				{
					$this->options[ $row['name'] ] = $row['value'];
				}
			}
		}
		
		if(isset($this->options[ $name ]))
		{
			return $this->options[ $name ];
		}
		else
		{
			return $default;
		}
	}
	
	function set_option($key, $value = null)
	{
		$this->_get_options();
		
		if ( ! is_array($key))
		{
			$key = array($key => $value);
		}
		
		foreach($key as $akey => $avalue)
		{
			$this->option[ $akey ] = $avalue;
		}
		
		$this->_save_options;
		
	}
	
	function _get_options()
	{
		if(is_empty($this->options))
		{
			$query = $this->db->get('forum_options');
			
			if($query->num_rows() > 0)
			{
				$rows = $query->result_array();
				foreach($rows as $row)
				{
					$this->options[ $row['name'] ] = $row['value'];
				}
			}
		}
	
	}
	
	function _save_options()
	{
		$this->db->delete('forum_options');
		
		$sql = "INSERT INTO " . $this->db->dbprefix("forum_options") . " (`name`, `value`) VALUES ";
		foreach($this->options as $key => $val)
		{
			$sql .= "('" . $key . "', '" . $val . "') ";
			$i++;
			if( $i < count($this->options)) $sql .= ", ";
		}
		$query = $this->db->query($sql);
	}

}
