<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Rss extends MX_Controller {
		
		var $settings = array();
		var $template = array();
		
		function __construct()
		{
			parent::__construct();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "news";

			$this->load->library('user_agent');
			$this->load->model('news_model', 'news');
			$this->load->helper('xml');
		}
		
		
		function index($lang = null)
		{
			$per_page = 20;
			if(is_null($lang)) $lang = $this->user->lang;
			$params['limit'] = $per_page;
			$params['where'] = array('news.lang' => $lang);
			
			if($rows = $this->news->get_list($params))
			{
				/* Removed the site theme as it is being served
				 as xml data instead of xhtml data
				 $this->layout->load($this->template, 'rss');
				 $this->load->view('rss', $this->template);
				*/
				$data['encoding'] = 'utf-8';
				$data['feed_name'] = $this->system->site_name;
				$data['feed_url'] = base_url();
				$data['page_description'] = $this->system->meta_description;
				$data['page_language'] = $lang;
				$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
				
				
				foreach ($rows as $key => $row)
				{
					$contents[$key]['title'] =  $row['title'];
					$contents[$key]['url'] = site_url($row['lang']  . '/news/' . $row['uri']);
					$contents[$key]['body'] = $row['body'];
					$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
					$contents[$key]['author'] = $row['author'];
					if (isset($row['image']['file']))
					{
						$contents[$key]['img'] = base_url(). 'media/images/s/' . $row['image']['file'];
					}
				}
				$data['contents'] = $contents;
				$this->send_header();
				
				$this->load->view('rss', $data);
			}
		}
		
		function cat ($uri = null, $lang = null)
		{
			
			
			$per_page = 20;
			
			$params['limit'] = $per_page;
			
			
			if(!is_null($lang))
			{
				$params['where']['news.lang'] = $lang;
			}
			if(is_null($uri))
			{
				$params['where']['cat'] =  0;
				$category = array('title' => __("No category", "news"));
			}
			else
			{
				$cat = $this->news->get_cat(array('uri' => $uri, 'lang' => $lang));
				$params['where']['cat'] = $cat['id'];
				$category = $cat;
			}
			
			if( $rows = $this->news->get_list($params))
			{
			
				$data['encoding'] = 'utf-8';
				$data['feed_name'] = $category['title'];
				$data['feed_url'] = base_url();
				$data['page_description'] = $this->system->meta_description;
				$data['page_language'] = $this->user->lang;
				$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
				
				
				foreach ($rows as $key => $row)
				{
					$contents[$key]['title'] =  $row['title'];
					$contents[$key]['url'] = site_url($row['lang'] . '/news/' . $row['uri']);
					$contents[$key]['body'] = $row['body'];
					$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
					$contents[$key]['author'] = $row['author'];
					if (isset($row['image']['file']))
					{
						$contents[$key]['img'] = base_url(). 'media/images/s/' . $row['image']['file'];
					}
				}
				$data['contents'] = $contents;
				$this->send_header();
				
				$this->load->view('rss', $data);

			}
			
		}
		
		
		function tag ($tag = null, $lang = null)
		{
			
			
			$per_page = 20;
			
			
			
			if(is_null($tag))
			{
				$this->template['title'] = __("Tag list", "news");
				$this->template['rows'] = $this->news->get_tags();
				header("Content-Type: application/rss+xml");
				$this->load->view('rss_tag_list', $this->template);
				return;
			}
			else
			{
				if(!is_null($lang))
				{
					$params['where']['news.lang'] = $lang;
				}
				$params['limit'] = $per_page;
				$params['where']['news_tags.uri'] = $tag;
				$params['order_by'] = 'news.id DESC';
				
				if( $rows = $this->news->get_news_by_tag($params))
				{
					$data['encoding'] = 'utf-8';
					$data['feed_name'] = $this->system->site_name . " - " . $tag;
					$data['feed_url'] = base_url();
					$data['page_description'] = $this->system->meta_description;
					$data['page_language'] = $this->user->lang;
					$data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
					
					
					foreach ($rows as $key => $row)
					{
						$contents[$key]['title'] =  $row['title'];
						$contents[$key]['url'] = site_url($row['lang'] . '/news/' . $row['uri']);
						$contents[$key]['body'] = $row['body'];
						$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
						$contents[$key]['author'] = $row['author'];
						if (isset($row['image']['file']))
						{
							$contents[$key]['img'] = base_url(). 'media/images/s/' . $row['image']['file'];
						}
					}
					$data['contents'] = $contents;
					
					$this->send_header();
					
					$this->load->view('rss', $data);

				}
			}
			
			
		}		
		
		
		
		/*
		 | Test user agent and return content type based on browser
		 | since Mozilla browser do not want to consume the proper
		 | content type. Force content type to text/xml
		*/
		function send_header()
		{
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

