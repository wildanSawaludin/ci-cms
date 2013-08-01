<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
class Settings extends MX_Controller {
	var $template = array();
	
	
	var $fields;
	var $_settings = array();
	function __construct()
	{

		parent::__construct();
		$this->load->library('administration');
		$this->template['module'] = "forum";
		$this->load->model('forum_model', 'forum');
		$this->template['admin']		= true;
		
	}
	
	
	function index()
	{
		//fields
		
		$this->user->check_level($this->template['module'], LEVEL_EDIT);		
		
		$this->template['title'] = __(" settings", "forum");

		
		$this->template['settings'] = $this->forum->settings;

		$handle = opendir(APPPATH.'modules/forum/css');
		$forum_themes = array();

		if ($handle)
		{
			while ( false !== ($theme = readdir($handle)) )
			{
				// make sure we don't map silly dirs like .svn, or . or ..

				if (substr($theme, 0, 1) != "." && $theme != 'index.html' && $theme != 'admin')
				{
					$forum_themes[] = str_replace('.css', '', $theme);
				}
			}
		}
		
		$this->template['forum_themes'] = $forum_themes;
		
		$this->layout->load($this->template, 'admin/settings');
	}
	
	function save()
	{
		$settings = $this->input->post('settings');
		foreach($settings as $key => $val)
		{
			$this->forum->save_settings($key, $val);
		}
		$this->session->set_flashdata('notification', __("Settings saved", $this->template['module']));
		redirect('admin/forum/settings');
	}

}

?>