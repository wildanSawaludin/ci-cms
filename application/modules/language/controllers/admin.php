<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends CI_Controller {
		
		function __construct()
		{
			parent::__construct();
			
			$this->load->library('administration');
			
			$this->template['module']	= 'language';
			$this->template['admin']		= true;
		}
		
		function index()
		{
			
			$this->template['langs'] = $this->locale->get_list();
			
			$this->layout->load($this->template, 'admin');
		}
		
		function active($active, $id)
		{
			
			if(isset($active) && isset($id)) {
				$data = array('active' => $active);
				$this->db->where('id', $id);
				$this->db->update('languages', $data);
				$this->session->set_flashdata('notification', __("Language updated", $this->template['module']));
			}
			redirect('admin/language');
		}

		function delete($id)
		{
			
			if(isset($id)) {
				
				$this->db->where('id', $id);
				$this->db->delete('languages');
				$this->session->set_flashdata('notification', __("Language removed", $this->template['module']));
			}
			redirect('admin/language');
		}		
		
		function setdefault($id) {
			if(isset($id)) {
				$this->db->update('languages', array('default' => 0));
				$data = array('default' => 1);
				$this->db->where('id', $id);
				$this->db->update('languages', $data);
				$this->session->set_flashdata('notification', __("Language updated", $this->template['module']));
			}
			redirect('admin/language');		
		}
		/**
		 * Dealing with page module settings
		 **/
		function settings()
		{
			
			if ($post = $this->input->post('submit') )
			{
				$fields = array('page_home');
				
				foreach ($fields as $field)
				{
					if ( $this->input->post($field) !== false)
					{
						$this->system->set($field, $this->input->post($field));
					}
				}
				$this->session->set_flashdata('notification', __("Settings updated", $this->template['module']));	
				redirect('admin/page/settings');
			}
			else
			{
				$this->layout->load($this->template, 'settings');
			}
		}
		function add()
		{
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'name'		=> $this->input->post('name'),
							'code'		=> $this->input->post('code'),
							'ordering'	=> $this->input->post('ordering').$this->input->post('uri'),
							'active'	=> $this->input->post('active')
						);
						
				$this->db->insert('languages', $data);
				$id = $this->db->insert_id();	
					
				$this->session->set_flashdata('notification', __("Language added", $this->template['module']));	
				
				redirect('admin/language');
			}
			else
			{
				
				$this->layout->load($this->template, 'add');
			}
		}
		
		function edit($id)
		{
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'name'		=> $this->input->post('name'),
							'code'		=> $this->input->post('code'),
							'ordering'	=> $this->input->post('ordering').$this->input->post('uri'),
							'active'	=> $this->input->post('active')
						);
					
				$this->db->where('id', $id);
				$this->db->update('languages', $data);
				
				$this->session->set_flashdata('notification', __("Language updated", $this->template['module']));
				
				redirect('admin/language');
			}
			$this->db->where('id', $id);
			$query = $this->db->get('languages');
			$this->template['row'] = $query->row_array();
			$this->layout->load($this->template, 'edit');
		}
		
		
	}

?>