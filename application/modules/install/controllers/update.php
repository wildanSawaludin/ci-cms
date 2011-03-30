<?php 
/**
 * @modified: Randall Morgan
 * @email: rmorgan62@gmail.com
 * 
 * @desc: This file has been modified to
 * work with Modular Extension and CI 2.0.0
 * in an effort to update CI-CMS to work under
 * Code Igniter 2.0.0 and PHP 5.1.6 or greater.
 */

class Update extends CI_Controller 
{
	var $_settings = array();
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->_get_settings();
		$this->config->set_item('cache_path', './cache/');
		$dir = $this->config->item('cache_path');
		$this->load->library('cache', array('dir' => $dir));
		
	}
	
	function _get_settings()
	{
            /*
             * if version is bigger than 2.1.0.0 then get settings with the base_url field
             */
            $query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('settings') . " WHERE Field = 'base_url'");
            if($query->num_rows == 0)
            {
                
                //version < 2.1.0.0
                $query = $this->db->get("settings");
                $rows = $query->result_array();
                foreach($rows as $row)
                {
                    $this->_settings[ $row['name'] ] = $row['value'];
                }
            }
            else
            {
                $base_url = $this->config->item('base_url');
                $this->db->where('base_url', $base_url);
                $query = $this->db->get("settings");
                if($query->num_rows > 0)
                {
                    $rows = $query->result_array();
                    if($rows)
                    {
                        foreach($rows as $row)
                        {
                            $this->_settings[ $row['name'] ] = $row['value'];
                        }    
                    }
                }
                else
                {
                    /*
                     * set a new base_url setting 
                     * get from a valid setting and just change the base_url
                     */
                    
                    $query= $this->db->get("settings");
                    $rows = $query->result_array();
                    
                    foreach($rows as $row)
                    {
                        if(!isset($this->_settings[ $row['name']]))
                        {
                            $this->_settings[ $row['name'] ] = $row['value'];
                        }
                        $data = array('name' => $row['name'], 'value' => $row['value'], 'base_url' => $base_url);
                        $this->db->insert("settings", $data);
                    }
                    
                }
                    
            }

	}

	function _set($name, $value)
	{	
		//update only if changed
        $query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('settings') . " WHERE Field = 'base_url'");
        if($query->num_rows == 0)
        {

            if (!isset($this->_settings[$name])) {
                $this->_settings[$name] = $value;
                $this->db->insert('settings', array('name' => $name, 'value' => $value));
            }
            elseif ($this->_settings[$name] != $value) 
            {
                $this->_settings[$name] = $value;
                $this->db->update('settings', array('value' => $value), "name = '$name'");
            }            
        }
        else
        {
            
            $base_url = $this->config->item('base_url');

            if (!isset($this->_settings[$name])) {
                $this->_settings[$name] = $value;
                $this->db->insert('settings', array('name' => $name, 'value' => $value, 'base_url' => $base_url));
            }
            elseif ($this->_settings[$name] != $value) 
            {
                $this->_settings[$name] = $value;
                $this->db->update('settings', array('value' => $value), "name = '$name' AND base_url='$base_url' ");
            }           
        }

	}
	
	
	function index()
	{
		$this->load->helper('file');
		$new_version = read_file('./application/version.txt');
		$old_version = $this->_settings['version'];
		
		if($old_version >= $new_version)
		{
			echo "<p>You have already the latest version. You cannot upgrade anymore.</p>";
			echo "<p>Go to " . anchor('admin/module') . " to update the modules.</p>";
			exit();
		}
		
		
		//start upgrade
		$to_version = "0.9.1.0";
		if($old_version <= $to_version)
		{
				$query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('users') . " WHERE Field = 'online'");
				if($query->num_rows() == 0)
				{
					$this->db->query("ALTER TABLE " . $this->db->dbprefix('users') . " ADD `online` INT( 1 ) NOT NULL DEFAULT 0") ;
					echo "<p>User table updated</p>";
					echo "<p>Now go to " . anchor('admin/module', 'admin/module') . " to update the modules.</p>";
				}
			
			$this->_set('version', $to_version);
		}
		
		$to_version = "0.9.2.0";
		if($old_version <= $to_version)
		{
				$query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('navigation') . " WHERE Field = 'g_id'");
				if($query->num_rows() == 0)
				{
					$this->db->query("ALTER TABLE " . $this->db->dbprefix('navigation') . " ADD `g_id` VARCHAR( 20 ) NOT NULL DEFAULT '0'") ;
					echo "<p>Navigation table updated</p>";
				}
				$query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('pages') . " WHERE Field = 'g_id'");
				if($query->num_rows() == 0)
				{
					$this->db->query("ALTER TABLE " . $this->db->dbprefix('pages') . " ADD `g_id` VARCHAR( 20 ) NOT NULL DEFAULT '0'") ;
					echo "<p>Page table updated</p>";
					echo "<p>Now go to " . anchor('admin/module', 'admin/module') . " to update the modules.</p>";
				}
			
			$this->_set('version', $to_version);
		}
		
		$to_version = "0.9.2.1";
		if($old_version <= $to_version)
		{
			$this->db->query("ALTER TABLE " . $this->db->dbprefix('navigation') . " CHANGE  `g_id`  `g_id` VARCHAR( 20 ) NOT NULL DEFAULT  '0'") ;
			echo "<p>Navigation table updated</p>";
			echo "<p>Now go to " . anchor('admin/module', 'admin/module') . " to update the modules.</p>";
			
			$this->_set('version', $to_version);
		}
		
		$to_version = "2.0.0.0";
		//using ci-cms 2
		if($old_version < $to_version)
		{
			$this->_set('theme_dir', 'themes/');
			echo "<p>2.0.0.0: All themes in this version is Themes directory</p>";
			
			$this->_set('version', $to_version);
			$this->cache->remove('settings', 'settings');
		}
        
        $to_version = "2.1.0.0";
		if($old_version < $to_version)
		{
            $base_url = $this->config->item('base_url');
            $query = $this->db->query("SHOW COLUMNS FROM " . $this->db->dbprefix('settings') . " WHERE Field = 'base_url'");
            if($query->num_rows == 0)
            {
                $this->db->query("ALTER TABLE " . $this->db->dbprefix('settings') . " ADD  `base_url` VARCHAR( 100 ) NOT NULL DEFAULT  '" . $base_url . "'") ;
			
            }
			echo "<p>2.1.0.0: Can use another base_url</p>";
			
			$this->_set('version', $to_version);
			$this->cache->remove('settings', 'settings');
		}
	}

}