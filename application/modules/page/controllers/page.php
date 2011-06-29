<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Page extends MX_Controller {
		
		var $template = array();
		
		function __construct()
		{
			parent::__construct();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "page";
			$this->load->model('page_model', 'pages');
			$this->user->lang = $this->session->userdata('lang');
		}
		
		//all available blocks
		function blocks () {
			
		}

		function comment()
		{
			//settings
			$page = $this->pages->get_page($this->input->post('uri'));
			
			if (!$this->user->logged_in && !$this->input->post('captcha'))
			{
				$this->session->set_flashdata('notification', __("You must submit the security code that appears in the image", $this->template['module']));
				redirect($this->input->post('uri'));
			}
			
			if(!$this->user->logged_in)
			{
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

					$this->session->set_flashdata('notification', __("You must submit the security code that appears in the image", $this->template['module']));
					redirect($this->input->post('uri'));
				}
				$fields = array('author', 'email', 'website');
				$data = array();
				foreach ($fields as $field)
				{
					$data[$field] = $this->input->post($field);
				}
				
				//since we don't know if registered or not
				$data['author'] .= " (" . __("guest", $this->template['module']) . ")";
			}
			else
			{
				$data = array();
				$data['author'] = $this->user->username;
				$data['email'] = $this->user->email;
				
			}
			$data['body'] = $this->input->post('body');
			$data['page_id'] = $page['id'];
			$data['ip'] = $this->input->ip_address();
			$data['date'] = mktime();
			
			
			if ($this->system->page_approve_comments && $this->system->page_approve_comments = 1)
			{
				$data['status'] = 1;
				if (isset($page['option']['notify']) && $page['option']['notify'] == 1 && $page['email'])
				{
					$this->load->library('email');

					$this->email->from($page['email'], $this->system->site_name );
					$this->email->to($page['email']);

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment Notification", $this->template['module']));
					
					$smsg = __("
Hello,

A new comment has been sent to the page
%s


If you don't want to receive other notification, go to
%s

and disable notification.
", "page");
					$msg = sprintf($smsg, 
							site_url( $page['uri']),
							site_url('admin/page/create/' . $page['id'])
						);
						
					$this->email->message($msg);

					$this->email->send();
					
					//notify admin
				
				}

				if (isset($this->system->page_notify_admin) && $this->system->page_notify_admin == 1)
				{
					$this->load->library('email');

					$this->email->from($page['email'], $this->system->site_name );
					

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment Notification", $this->template['module']));
					$msg = __("
Hello,

A new comment has been sent to the page
%s


If you don't want to receive other notification, go to
%s

and disable notification.
", "page");
					$msg = sprintf($msg,
							site_url($page['uri']),
							site_url('admin/page/settings#two')
						);
					$this->email->to($this->system->admin_email);
					$this->email->message($msg);
					$this->email->send();
				}
				
			}
			else
			{
				
				if ($page['email'] != '')
				{
					$this->load->library('email');

					$this->email->from($page['email'], $this->system->site_name );
					$this->email->to($page['email']);

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment to approve", $this->template['module']));
					
					$msg = __("
Hello,

A new comment has been sent to the page
%s
To approve it click the link below 
%s

If you don't want to receive other notification, go to
%s

and set to approve comments automatically.
", "page");
					$msg = sprintf($msg, 
							site_url($news['uri']),
							site_url('admin/page/comments/approve/' . $page['id']),
							site_url('admin/page/settings#two')
						);
						
					$this->email->message($msg);

					$this->email->send();

				}
				
				$this->session->set_flashdata('notification', __("Thank you for your comment. In this site, the comments need to be approved by the administrator. Once approved, you will see it listed here.", $this->template['module']));
			}
			
			$data = $this->plugin->apply_filters('comment_filter', $data);
			
			
			$this->db->insert('page_comments', $data);
			
			redirect( $this->input->post('uri'), 'refresh');
		}
		
		function index()
		{
			if ( $this->uri->segment(1) )
			{
				$num = 1;
				$built_uri = '';
				
				while ( $segment = $this->uri->segment($num))
				{
					$built_uri .= $segment.'/';
					$num++;
				}
				
				$new_length = strlen($built_uri) - 1;
				$built_uri = substr($built_uri, 0, $new_length);
			}
			else
			{
				$built_uri = $this->system->page_home;
			}
			
			if ( $page = $this->pages->get_page(array('uri' => $built_uri)) )
			{
				//if it does not belong to the UI language
				//then go to the correct lang
				
				if($page['lang'] != $this->user->lang)
				{
					redirect($page['lang'] . '/' . $page['uri']);
				}
				
				$page = $this->plugin->apply_filters('page_item', $page);
				
				//can view?
				if(!in_array($page['g_id'], $this->user->groups))
				{
					$this->output->set_header("HTTP/1.0 403 Forbidden");
					if($this->user->logged_in)
					{
						$this->template['message'] = __("Your are already logged in but not allowed to see this page. If it is an error then contact the administrators of the site.", "page");
					}
					else
					{
						$this->template['message'] = __("Your are not allowed to see this page.", "page") . "<br />" . anchor('member/login', __("Please try to sign in here", "page"));
					}
					
					$this->layout->load($this->template, '403');
					return;
				}
				
				if ($page['active'] != 0)
				{
					
					$this->template['comments'] = $this->pages->get_comments(array('where' => array('page_id' => $page['id'], 'status' => 1), 'order_by' => 'id'));
					$this->template['page'] = $page;
					$view = 'index';
					
					if($parent = $this->pages->get_page(array('id' => $page['parent_id'])))
					{
						$this->template['breadcrumb'][] = 	array(
						'title'	=> (strlen($parent['title']) > 20 )? substr($parent['title'], 0, 20) . '...': $parent['title'],
						'uri'	=> $parent['uri']
						);
					}
					
					if ($page['uri'] != $this->system->page_home)
					{
						$this->template['breadcrumb'][] = 	array(
						'title'	=> (strlen($page['title']) > 20 )? substr($page['title'], 0, 20) . '...': $page['title'],
						'uri'	=> $page['uri']
						);
					}
					
					$this->template['title'] = $this->template['page']['title'];
														
					$this->template['meta_keywords'] 	= $this->template['page']['meta_keywords'];
					$this->template['meta_description'] = $this->template['page']['meta_description'];
					//page hit
					if ($this->session->userdata('page'.$page['id']) != $page['id'])
					{
						$this->session->set_userdata('page'.$page['id'], $page['id']);
						$this->db->where('id', $page['id']);
						$this->db->set('hit', 'hit+1', FALSE);
						$this->db->update('pages');
						$this->cache->remove('pagelist'.$this->user->lang, 'page');
					}
					
					if (isset($page['options']['allow_comments']) && $page['options']['allow_comments'] == 1)
					{
						if(!$this->user->logged_in)
						{
							//generate captcha
							
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
								'font_path'	 => APPPATH . 'modules/news/fonts/Fatboy_Slim.ttf',
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
							
							
							$this->template['captcha'] = $cap['image'];
						}
					
					}
					
				}
				else
				{
					
					$this->template['message'] = __("The page you're looking for is not active!", "page");
					$view = '403';
				}
			}
			else
			{
				// Make sure we send a 404 header
				
				
				$view = '404';
			}
	
			$this->layout->load($this->template, $view);
		}
		
		function body($uri)
		{

			$data['uri'] = $uri;
			//if(!is_null($lang)) $data['lang'] = $lang;
			if ( $page = $this->pages->get_page($data))
			{
				echo $page['body'];
				exit;
			}
		}
		
        function children($parent_id = 0, $start = 0)
        {
            
            $params['where'] = array('parent_id' => $parent_id);		
            $params['order_by'] = 'weight';
            $search_id = $this->pages->save_params(serialize($params));

            $this->results($search_id, $start);
            return;            
        }
        function results($search_id = 0, $start = 0)
        {
            $params = array();

            //sorting
            if ($search_id != '0' && $tmp = $this->pages->get_params($search_id))
            {
                $params = unserialize( $tmp);
            }
            if(isset($params['where']['parent_id']))
            {
                $parent_id = $params['where']['parent_id'];
            }            
            $params['where']['active'] = 1;
            $params['where']['lang'] = $this->user->lang;
            $wheres = array();
            foreach($params['where'] as $key => $val)
            {
                $wheres[] = $key . " = " . $this->db->escape($val) . " ";
            }
            
            $where = join(' AND ', $wheres);
            $where .= " AND g_id IN " . "('"  . join("', '", $this->user->groups) . "') ";
            $params['where'] = $where ;

            $per_page = 20;
            $params['start'] = $start;

            $params['limit'] = $per_page;


            $this->template['rows'] = $this->pages->get_page_list($params);

            $this->template['title'] = __("Page list", "page");
            $config['first_link'] = __('First', 'page');
            $config['last_link'] = __('Last', 'page');
            $config['total_rows'] = $this->pages->get_total($params);
            $config['per_page'] = $per_page;
            $config['base_url'] = base_url() . 'page/results/' . $search_id;
            $config['uri_segment'] = 4;
            $config['num_links'] = 20;
            $this->load->library('pagination');

            $this->pagination->initialize($config);

            $this->template['pager'] = $this->pagination->create_links();
            $this->template['start'] = $start;
            $this->template['total'] = $config['total_rows'];
            $this->template['per_page'] = $config['per_page'];
            $this->template['total_rows'] = $config['total_rows'];
            $this->template['search_id'] = $search_id;

            $this->layout->load($this->template, 'results');

        }

		
	}

?>
