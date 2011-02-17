<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * $Id
 **/
 
 class Tags extends MX_Controller {
	 
	 var $template = array();
 
 	function __construct()
	{
		parent::__construct();
	

		$this->template['module']	= 'tags';
		$this->settings = isset($this->system->tags_settings) ? unserialize($this->system->tags_settings) : null;

		$this->load->library('cache');
		$this->load->model('tags_model', 'tags');
		

	}
	

	function index($tag = null, $start = null)
	{
		$tag = $this->uri->segment(2);
		$start = $this->uri->segment(3);
		$limit = 20;
		
		if(is_null($tag))
		{
			$tags = $this->tags->get_cloud();
			$this->template['tags'] = $tags;
			
			$this->layout->load($this->template, 'index');
		}
		else
		{
			$rows = $this->tags->get_tags(array('tag' => $tag), array('start' => $start, 'limit'=> $limit));
			$this->template['tag'] = $tag;
			$this->template['rows'] = $rows;
			$this->layout->load($this->template, 'tag');
			
		}
	}

	function rss($tag = null, $lang = null)
	{
		$tag = $this->uri->segment(3);
		$lang = $this->uri->segment(4);
		$limit = 10;
		
		$contents = array();
		
		if($lang= null) $lang = $this->user->lang;
		if(!is_null($tag))
		{
		
			if( $rows = $this->tags->get_tags(array('tag' => $tag, 'lang' => $lang)))
			{
				$data['encoding'] = 'utf-8';
				$data['feed_name'] = __("Tag:", "tags") . " " .  strip_tags($tag) . " - " . $this->system->site_name ;
				$data['feed_url'] = base_url();
				$data['page_description'] = $this->system->meta_description;
				$data['page_language'] = $lang;
				$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
				
				
				foreach ($rows as $key => $row)
				{
					$contents[$key]['title'] =  $row['title'];
					$contents[$key]['url'] = site_url($row['url']);
					$contents[$key]['body'] = $row['summary'];
					$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
					$contents[$key]['author'] = (isset($row['author'])) ? $row['author'] : '';
				}
			}
			else
			{
				$contents[0]['title'] =  $this->system->site_name;
				$contents[0]['url'] = site_url();
				$contents[0]['body'] = "No matching tags found";
				$contents[0]['date'] = date('Y-m-d H:i:s');
				$contents[0]['author'] = $this->system->admin_email;				
			}
			
			$data['contents'] = $contents;
			$this->send_header();
			
			$this->load->view('rss', $data);
				
		}
	}


	/*
	 | Test user agent and return content type based on browser
	 | since Mozilla browser do not want to consume the proper
	 | content type. Force content type to text/xml
	*/
	function send_header()
	{
		$this->load->library('user_agent');
		
		$this->_settings = unserialize($this->system->news_settings);
		
		if((array_key_exists('use_alt_header', $this->_settings) AND $this->_settings['use_alt_header'] == 1) AND (stripos($this->agent->agent_string(), 'Mozilla') !== false))
		{
			$this->output->set_header("content-Type: text/xml");
		}
		else
		{
			$this->output->set_header("Content-Type: application/rss+xml");
		}	
			
	}

}