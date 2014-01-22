<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Guestbook extends MX_Controller {

	var $template = array();
	
	function __construct()
	{
		parent::__construct();
	
		$this->template['module']	= 'guestbook';
		$this->load->model('guestbook_model', 'gbook');
		$this->load->helper('smiley');
		
	}


	function index()
	{
		
		//just go to list
		$this->template['title'] = __("Guestbook messages", "guestbook");
		$this->results();
	}
	
	function sign()
	{
		var_dump($this->gbook->settings);
		echo "Guestbook -> Sign<br>";
		exit;
		$this->load->helper('captcha');
		$vals = array(
			'img_path'	 => './media/captcha/',
			'img_url'	 => site_url('media/captcha'). '/',
			'font_path'	 => APPPATH . 'modules/guestbook/fonts/Fatboy_Slim.ttf',
			'img_width'	 => 150,
			'img_height' => 30,
			'expiration' => 1800,
			'word' => $word
		);

		$cap = create_captcha($vals);
		
		$data = array(
			'captcha_id'	=> '',
			'captcha_time'	=> $cap['time'],
			'ip_address'	=> $this->input->ip_address(),
			'word'			=> $cap['word']
		);
		$this->db->insert('captcha', $data);
		$this->template['captcha_image'] = $cap['image'];
		
		$this->template['title'] = __("Sign our guestbook", "guestbook");
		$this->layout->load($this->template, "sign");
	}
	
	function save()
	{
		if (!$this->input->post('captcha'))
		{
			$this->session->set_flashdata('notification', '<p style="color:#900">'.__('You must submit the security code that appears in the image'.'</p>', $this->template['module']));
			redirect('freeback');
		}
		$expiration = time()-7200; // Two hour limit
		$this->db->where("captcha_time <", $expiration);
		$this->db->delete('captcha');
		// Then see if a captcha exists:
		$this->db->where('word', $this->input->post('captcha'));
		$this->db->where('ip_address', $this->input->ip_address());
		$this->db->where('captcha_time >', $expiration);
		$query = $this->db->get('captcha');
		$row = $query->row();
		if ($query->num_rows() == 0)
		{
			$this->template['title'] = __("Error", "guestbook");
			$this->template['message'] = __("You must submit the security code that appears in the image", $this->template['module']);
			
			$this->layout->load($this->template, 'error');
			return;
		}
		
		//
		foreach($this->gbook->fields['guestbook_posts'] as $key => $val)
		{
			if ($this->input->post($key) === false)
			{
				$data[$key] = $val;
			}
			else
			{
				$data[$key] = $this->input->post($key);
			}
		}
		
		$passed = true;
		if(!$data['g_msg']) $passed = false;
		
		if(!$passed)
		{
			$this->template['title'] = __("Error", "guestbook");
			$this->template['message'] = __("There was en error, please fill all fields", "guestbook");
			
			$this->layout->load($this->template, 'error');
			return;
		}
		
		$data['id'] = $this->gbook->save($data);
		
		if($this->gbook->settings['notify_admin'] == 'Y')
		{
		
			$this->load->library('email');
			$this->email->from($this->system->admin_email, "Admin " . $this->system->site_name );
			$this->email->to($this->system->admin_email);
			$subject = '[' . $this->system->site_name . '] ' . __("Guestbook post", "guestbook");
			$this->email->subject($subject);
			$message = sprintf(__("Hello\n\nA new message was posted on your guestbook\n\nBy: %s\nMessage: %s\n\nTo edit: %s\nTo delete: %s\n\nThank you", "guestbook"), $data['g_name'], $data['g_msg'], site_url('admin/guestbook/edit/' . $data['id']), site_url('admin/guestbook/delete/' . $data['id']));
			
			$this->email->message($message);
			$this->email->send();
		
		}
		$this->session->set_flashdata('notification', __("Thank you, your message is registered", "guestbook"));
		redirect('guestbook');
	}
	
	
	function results($search_id = 0, $start = 0)
	{
		$this->plugin->add_action('header', array(&$this, '_write_header'));
		$params = array();

		//sorting

		if ($search_id !== 0 && $tmp = $this->gbook->get_params($search_id))
		{
			$params = unserialize( $tmp);

		}
		
		$pool = '0123456789';
		$str = '';
		for ($i = 0; $i < 6; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		$word = $str;
		
		$this->load->helper('captcha');
		$vals = array(
			'img_path'	 => './media/captcha/',
			'img_url'	 => site_url('media/captcha'). '/',
			'font_path'	 => APPPATH . 'modules/guestbook/fonts/Fatboy_Slim.ttf',
			'img_width'	 => 150,
			'img_height' => 30,
			'expiration' => 1800,
			'word' => $word
		);

		$cap = create_captcha($vals);
		
		$data = array(
			'captcha_id'	=> '',
			'captcha_time'	=> $cap['time'],
			'ip_address'	=> $this->input->ip_address(),
			'word'			=> $cap['word']
		);
		$this->db->insert('captcha', $data);
		$this->template['captcha_image'] = $cap['image'];
		
		$per_page = 20;
		$params['start'] = $start;

		$params['limit'] = $per_page;

		$this->template['rows'] = $this->gbook->get_list($params);
		//echo $this->db->last_query();

		if(!isset($this->template['title'])) $this->template['title'] = __("Search result", "guestbook");
		$config['first_link'] = __('First', 'guestbook');
		$config['last_link'] = __('Last', 'guestbook');
		$config['total_rows'] = $this->gbook->get_total($params);
		$config['per_page'] = $per_page;
		$config['base_url'] = base_url() . 'guestbook/results/' . $search_id;
		$config['uri_segment'] = 4;
		$config['num_links'] = 20;
		$this->load->library('pagination');

		$this->pagination->initialize($config);

		$this->template['pager'] = $this->pagination->create_links();
		$this->template['start'] = $start;
		$this->template['total'] = $config['total_rows'];
		$this->template['per_page'] = $config['per_page'];
		$this->template['total_rows'] = $config['total_rows'];

		$this->layout->load($this->template, 'index');
	
	}
	
	function _write_header()
	{
		if($this->gbook->settings['style'] != 'none')
		{
			echo '<link rel="stylesheet" href="' . site_url() . '/application/modules/guestbook/css/' . $this->gbook->settings['style'] . '.css" type="text/css" media="screen" charset="utf-8" />';
		}
	}
	
}