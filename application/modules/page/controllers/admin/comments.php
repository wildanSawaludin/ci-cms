<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends MX_Controller {
	
	var $template = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('administration');
		$this->user->lang = $this->session->userdata('lang');

		$this->template['module']	= 'page';
		$this->template['admin']	= true;
		
		$this->load->model('page_model', 'pages');
	}
	
	function index($parent_id = 0, $start = 0)
	{
		$params['order_by'] = 'id DESC';
		$search_id = $this->pages->save_params(serialize($params));
		$this->listall($search_id, $start);
		return;
	}
	
	function listall($search_id = 0, $start = 0)
	{
		$params = array();

		//sorting
		if ($search_id != '0' && $tmp = $this->pages->get_params($search_id))
		{
			$params = unserialize( $tmp);
		}


		$per_page = 20;
		$params['start'] = $start;

		$params['limit'] = $per_page;

	
		$this->template['rows'] = $this->pages->get_comments($params);
		//echo $this->db->last_query();

		$this->template['title'] = __("Comment list", "page");
		$config['first_link'] = __('First', 'page');
		$config['last_link'] = __('Last', 'page');
		$config['total_rows'] = $this->pages->get_total_comments($params);
		$config['per_page'] = $per_page;
		$config['base_url'] = base_url() . 'admin/page/comments/listall/' . $search_id;
		$config['uri_segment'] = 6;
		$config['num_links'] = 20;
		$this->load->library('pagination');

		$this->pagination->initialize($config);

		$this->template['pager'] = $this->pagination->create_links();
		$this->template['start'] = $start;
		$this->template['total'] = $config['total_rows'];
		$this->template['per_page'] = $config['per_page'];
		$this->template['total_rows'] = $config['total_rows'];
		$this->template['search_id'] = $search_id;

		$this->layout->load($this->template, 'admin/comments');
	
	}
	
	function view()
	{
	
	}
	
	function edit()
	{
	
	}
	
	function delete($id = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		if ( $post = $this->input->post('submit') )
		{
			$this->pages->delete_comment($this->input->post('id'));
			
			$this->session->set_flashdata('notification', 'Comment has been deleted.');

			$this->plugin->do_action('page_comment_delete', $this->input->post('id'));
			redirect('admin/page/comments');
		}
		else
		{
			$this->template['row'] = $this->pages->get_comment( $id );
			
			$this->layout->load($this->template, 'admin/comment_delete');
		}
	
	}

	function search()
	{
		$tosearch = $this->input->post("tosearch");
		if($tosearch === false)
		{
			$this->listall();
		}
		
		$params = array(
		"where" => "body LIKE '%" . join("%' AND body LIKE '%", explode(' ', $tosearch)) . "%' "
		);
		
		$params['order_by'] = 'id DESC';
		$search_id = $this->pages->save_params(serialize($params));
		$this->template['tosearch'] = $tosearch;
		$this->listall($search_id);

		
	}		
}
