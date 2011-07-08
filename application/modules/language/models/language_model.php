<?php 

class Language_Model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		
		$this->table = 'language';
	}
	
	function active($active, $id)
	{
		$data = array('active' => $active);
		$this->db->where('id', $id);
		$this->db->update('languages', $data);
	}	
	
	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('languages');	
	}
	
	function update($where, $data)
	{
		$this->db->set($data);
		$this->db->where($where);
		$this->db->update('languages');
	}
	
	function add($data)
	{
		$this->db->insert('languages', $data);
	}
	
	function get($where)
	{
		if(!is_array($where)) $where = array('id' => $where);
		$this->db->where($where);
		$query = $this->db->get('languages');
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}

	}
	
	function get_list($where = array())
	{
		if(!empty($where)) $this->db->where($where);
		$this->db->order_by('ordering');
		$query = $this->db->get('languages');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function move($direction, $id)
	{
		
		$move = ($direction == 'up') ? -1 : 1;
		$this->db->where(array('id' => $id));
		
		$this->db->set('ordering', 'ordering+'.$move, FALSE);
		$this->db->update('languages');
		
		$this->db->where(array('id' => $id));
		$query = $this->db->get('languages');
		$row = $query->row();
	
		$new_ordering = $row->ordering;

		if ( $move > 0 )
		{
			$this->db->set('ordering', 'ordering-1', FALSE);
			$this->db->where(array('ordering <=' => $new_ordering, 'id <>' => $id));
			$this->db->update('languages');
		}
		else
		{
			$this->db->set('ordering', 'ordering+1', FALSE);
			$where = array('ordering >=' => $new_ordering, 'id <>' => $id);
			
			$this->db->where($where);
			$this->db->update('languages');
		}
		//reordinate
		$i = 0;
		$this->db->order_by('ordering');

		$query = $this->db->get('languages');
		
		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('ordering', $i);
				$this->db->where('id', $row->id);
				$this->db->update('languages');
				$i++;
			}
		}
		//clear cache
	}
}


