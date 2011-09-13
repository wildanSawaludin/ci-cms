<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends MX_Controller {
		
		var $template = array();
		
		function __construct()
		{
			parent::__construct();
			//$this->output->enable_profiler(true);
						
			$this->load->library('administration');
			$this->load->model('member_model');
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			
			$this->template['module']	= 'member';
			$this->template['admin']		= true;
			$this->_init();
		}
		
		function index()
		{
			redirect('admin/member/listall');
		}
		
		function _init() 
		{
			/*
			* default values
			*/
			if (!isset($this->system->login_signup_enabled)) 
			{
				 $this->system->login_signup_enabled = 1;
			}
			
		}
		
		function listall($start = 0, $limit = 20, $order = 'id') 
		{
			$where = array();
			if ($filter = $this->input->post('filter'))
			{
				$where = array('username' => $filter, 'email' => $filter);
			}
			$this->template['members'] = $this->member_model->get_users($where, array('limit' => $limit, 'start' => $start, 'order_by' => $order));
			$this->load->library('pagination');

			$config['uri_segment'] = 4;
			$config['first_link'] = __('First', 'vetso');
			$config['last_link'] = __('Last', 'vetso');
			$config['base_url'] = site_url('admin/member/listall');
			$config['total_rows'] = $this->member_model->member_total;
			$config['per_page'] = $limit; 

			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();
			$this->template['start'] = $start;
			
			$this->layout->load($this->template, 'admin');
			return;
		}
		
		
		function settings()
		{
			echo "Not Yet Implemented";
		}
		
		function save()
		{
			echo "Not Yet Implemented";
		}
		
		function create()
		{
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_rules('username',__('Username', $this->template['module']),"trim|required|min_length[4]|max_length[12]|xss_clean|callback__verify_username");
			$this->form_validation->set_rules('password',__('Password', $this->template['module']),"trim|matches[passconf]|required");
			$this->form_validation->set_rules('passconf', __("Confirm",$this->template['module']), "trim");
			$this->form_validation->set_rules('email', __("Email", $this->template['module']),"trim|required|valid_email|callback__verify_mail");	
			$this->form_validation->set_error_delimiters('<p style="color:#900">', '</p>');

			$this->form_validation->set_message('min_length', __('The %s field is required'));
			$this->form_validation->set_message('required', __('The %s field is required'));
			$this->form_validation->set_message('matches', __('The %s field does not match the %s field'));
			$this->form_validation->set_message('valid_email', 'The email address you entered is not valid.');			
						
			if ($this->form_validation->run() == FALSE)
			{
				$this->layout->load($this->template, 'create');
			}
			else
			{
				$id = $this->user->register(
					$this->input->post('username'),
					$this->input->post('password'),
					$this->input->post('email')
				);
				
				$this->session->set_flashdata('notification', __("User registered", $this->template['module']));
				redirect('admin/member/listall');
			}

		}

		function _verify_username($data)
		{

			$username = $this->input->post('username');
			
			//check if email belongs to someone else
			if ($this->member_model->exists(array('username' => $username)))
			{
				$this->validation->set_message('_verify_username', __("The username is already in use", $this->template['module']));
				return FALSE;
			}

		}		
		function profile($username = null) 
		{
			if ( is_null($username) )
			{
				$username = $this->session->userdata("username");
			}
			
			if ($this->member_model->exists("username = '$username'"))
			{
				echo "exist";
			}
			else 
			{
				$this->session->set_flashdata("notification", __("This member doesn't exist", $this->template['module']));
				redirect("admin/member/listall");
			}
			
		}

		function delete($username = null)
		{
			if (is_null($username))
			{
				$this->session->set_flashdata("notification", __("Username and status required", $this->template['module']));
				redirect("admin/member/listall");
			}
			
			if ($username == $this->session->userdata("username"))
			{
				$this->session->set_flashdata("notification", __("You cannot remove yourself", $this->template['module']));
				redirect("admin/member/listall");
			
			}
			
			$this->db->delete('users', array('username' => $username));
			$this->session->set_flashdata("notification", __("User deleted", $this->template['module']));
			redirect("admin/member/listall");
			
		}
		
		function status($username = null, $fromstatus = null)
		{
			if (is_null($username) || is_null($fromstatus))
			{
				$this->session->set_flashdata("notification", __("Username and status required", $this->template['module']));
				redirect("admin/member/listall");
			}
			
			if ($fromstatus == 'active') 
			{
				$data['status'] = 'disabled';
			}
			else
			{
				$data['status'] = 'active';
			}
			$this->user->update($username, $data);
			$this->session->set_flashdata("notification", __("User status updated", $this->template['module']));
			redirect("admin/member/listall");
			
			
		}
		function edit($username = null) 
		{
			$this->load->library('form_validation');
			$this->form_validation->CI =& $this;
			$this->form_validation->set_rules('password',__('Password', $this->template['module']),"trim|matches[passconf]|required");
			$this->form_validation->set_rules('passconf', __("Confirm",$this->template['module']), "trim");
			$this->form_validation->set_rules('email', __("Email", $this->template['module']),"trim|required|valid_email|callback__verify_mail");	
			$this->form_validation->set_error_delimiters('<p style="color:#900">', '</p>');

			$this->form_validation->set_message('min_length', __('The %s field is required'));
			$this->form_validation->set_message('required', __('The %s field is required'));
			$this->form_validation->set_message('matches', __('The %s field does not match the %s field'));
			$this->form_validation->set_message('valid_email', 'The email address you entered is not valid.');			
			
			if ( is_null($username) )
			{
				$username = $this->input->post("username");
			}
							
			if ($this->template['member'] = $this->member_model->get_user($username))
			{
				
						
				if ($this->form_validation->run() == FALSE)
				{
					$this->layout->load($this->template, 'edit');
				}
				else
				{
					$data['email'] = $this->input->post('email');
					if ($this->user->username != $this->input->post('username'))
					{
						$data['status'] = $this->input->post('status');
					}
					else
					{
						$this->session->set_userdata('email', $data['email']);
						if (!isset($this->system->admin_email))
						{
							$this->system->set('admin_email', $data['email']);
						}
					}
					
					
					if ($this->input->post('password'))
					{
						$data['password'] = $this->input->post('password');
					}

					$this->user->update($username, $data);
					$this->session->set_flashdata('notification', __("User saved", $this->template['module']));
					redirect('admin/member/listall');
				}
			}
			else 
			{
				$this->session->set_flashdata("notification", __("This member doesn't exist", $this->template['module']));
				redirect("admin/member/listall");
			}				

		}


	// ------------------------------------------------------------------------
	
	/**
	 * Registration validation callback
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
		function _verify_mail($data)
		{

			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$username = $this->input->post('username');
			
			//check if email belongs to someone else
			if ($this->member_model->exists(array('email' => $email, 'username !=' => $username)))
			{
				$this->validation->set_message('_verify_mail', __("The email is already in use", $this->template['module']));
				return FALSE;
			}

		}		
		
		
	}
	
?>