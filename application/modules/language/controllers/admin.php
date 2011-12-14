<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends MX_Controller {
		
		var $template = array();
		
		function __construct()
		{
			parent::__construct();
			
			$this->load->library('administration');
			$this->load->model('language_model');
			
			$this->template['module']	= 'language';
			$this->template['admin']		= true;
		}
		
		function index()
		{
			
			$this->template['langs'] = $this->cms_locale->get_list();
			
			$this->layout->load($this->template, 'admin');
		}
		
		function active($active, $id)
		{
			
			if(isset($active) && isset($id)) {
				$this->language_model->active($active, $id);
				$this->session->set_flashdata('notification', __("Language updated", $this->template['module']));
			}
			redirect('admin/language');
		}

		function delete($id)
		{
			
			if(isset($id)) {
				
				$this->language_model->delete($id);
				$this->session->set_flashdata('notification', __("Language removed", $this->template['module']));
			}
			redirect('admin/language');
		}		
		
		function setdefault($id) {
			$this->user->check_level($this->template['module'], LEVEL_ADD);
			if(isset($id)) {
				// Unset all languages
				$this->language_model->update(array('active' => 1), array('default' => 0));
				// Now set the passed language as the default
				$this->language_model->update(array('id' => $id), array('default' => 1));
				$this->session->set_flashdata('notification', __("Language updated", $this->template['module']));
			}
			redirect('admin/language');		
		}

		function add()
		{
			$this->user->check_level($this->template['module'], LEVEL_ADD);
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'name'		=> $this->input->post('name'),
							'code'		=> $this->input->post('code'),
							'ordering'	=> $this->input->post('ordering').$this->input->post('uri'),
							'active'	=> $this->input->post('active')
						);
						
				
				$this->language_model->add($data);
					
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
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'name'		=> $this->input->post('name'),
							'code'		=> $this->input->post('code'),
							'ordering'	=> $this->input->post('ordering').$this->input->post('uri'),
							'active'	=> $this->input->post('active')
						);
					
				$this->language_model->update(array('id' => $id), $data);
				
				$this->session->set_flashdata('notification', __("Language updated", $this->template['module']));
				
				redirect('admin/language');
			}
			
			$this->template['row'] = $this->language_model->get($id);
			$this->layout->load($this->template, 'edit');
		}
		
		function move($direction, $id)
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);

			if (!isset($direction) || !isset($id))
			{
				redirect('admin/language');
			}
			
			$this->language_model->move($direction, $id);
			
			redirect('admin/language');					
			
		}
		
	}


	