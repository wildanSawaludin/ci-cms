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

class Install extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
 
		$this->load->database();
   		$this->load->dbforge();	
		$this->load->helper('url');
	}
	
	function index()
	{
        $base_url = $this->config->item('base_url');
		$tables = $this->db->list_tables();
        
		if(!empty($tables))
		{
			redirect('install/update');
			exit();
		}

		echo "<p>You are about to install CI-CMS in " . $base_url . "</p>";
		echo "<p>Before you continue, <ol><li>check that you have a file config.php and database.php in your configuration folder and all values are ok.</li><li>make writable the <b>media</b> folder</li></p>";
		echo "<p>If you get a database error in the next step then your database.php file is not ok</p>";
		echo "<p>" . anchor('install/step1', 'Click here to continue') . "</p>";

	}
	
	function step1()
	{
		$folders = array(
			'./media/images', 
			'./media/images/o', 
			'./media/images/m', 
			'./media/images/s',
			'./media/files',
			'./media/captcha'
		);
		
		foreach ($folders as $f)
		{
			if( @mkdir($f))
			{
				echo "Folder $f created<br />";
			}
			else
			{
				echo "<span style='color: red'>ERROR: folder $f not created.</span> Please create it manually.<br />";
			}
		}
			
		echo "<p>" . anchor('install/step2', 'Click here to continue') . "</p>";
		
	}
	
	
	function step2()
	{
	

		
		
		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'code' => array(
				'type' => 'CHAR',
				'constraint' => 5
			 ),
			'name' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '100',
					 'default' => ''
			  ),
			 'ordering' => array(
				'type' => 'INT',
				'constraint' => 5,
				'default' => 0
			 ),
			'active' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'default' => array(
					 'type' => 'TINYINT',
					 'constraint' => '2',
					'default' => 0
			  )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('code');
		$this->dbforge->create_table('languages', TRUE);
		
		$query = $this->db->get('languages');
		
		if($query->num_rows() == 0)
		{
            $data = array(
                array(
                'id' => 1,
                'code' => 'en',
                'name' => 'English',
                'ordering' => 1,
                'active' => 1,
                'default' => 1
                ),
                array(
                'id' => 2,
                'code' => 'fr',
                'name' => 'Fran&ccedil;ais',
                'ordering' => 2,
                'active' => 1,
                'default' => 0
                ),
                array(
                'id' => 3,
                'code' => 'it',
                'name' => 'Italiano',
                'ordering' => 3,
                'active' => 1,
                'default' => 0
                )
                
            );
            $this->db->insert_batch('languages', $data);
		}
		

  
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'parent_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			 ),
			'active' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'weight' => array(
					 'type' => 'INT',
					 'constraint' => '3',
					'default' => 0
			  ),
			'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'g_id' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '20',
					 'default' => '0'
			  ),
			'uri' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
			  )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('active');
		$this->dbforge->add_key('weight');
		$this->dbforge->add_key('parent_id');
		$this->dbforge->create_table('navigation', TRUE);
		
		$query = $this->db->get('navigation');
		
		if($query->num_rows() == 0)
		{
			$this->db->query("INSERT INTO " . $this->db->dbprefix('navigation') . " (id, parent_id, title, uri, lang) VALUES (19, 0, 'leftmenu', '', 'en'), (1, 19, 'Menu', '', 'en'), ( 2, 1, 'Home', 'home', 'en'), ( 3, 1, 'About', 'about', 'en'), ( 20, 0, 'leftmenu', '', 'fr'), ( 4, 20, 'Menu', '', 'fr'), ( 5, 4, 'Accueil', 'home', 'fr'), ( 6, 4, 'A propos', 'about', 'fr'), ( 21, 0, 'leftmenu', '', 'it'), ( 7, 21, 'Menu', '', 'it'), ( 8, 7, 'Home', 'home', 'it'), ( 9, 7, 'About', 'about', 'it'), ( 10, 0, 'topmenu', '', 'en'), ( 11, 10, 'Contact us', 'contact-us', 'en'), ( 12, 10, 'Google', 'http://google.com', 'en'), ( 13, 0, 'topmenu', '', 'fr'), ( 14, 13, 'Contact us', 'contact-us', 'fr'), ( 15, 13, 'Google', 'http://google.com', 'fr'), ( 16, 0, 'topmenu', '', 'it'), ( 17, 16, 'Contact us', 'contact-us', 'it'), ( 18, 16, 'Google', 'http://google.com', 'it') ");
		}


		
		//users
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'username' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'password' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			 ),
			 'status' => array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'default' => 'active'
			),
			 'lastvisit' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			),
			 'registered' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			),
			 'online' => array(
				'type' => 'INT',
				'constraint' => 1,
				'default' => 0
			),
			 'activation' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			 )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('username');
		$this->dbforge->add_key('status');
		$this->dbforge->create_table('users', TRUE);
		
		
		//creating user_persistence table
        $fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'user_id' => array(
				'type' => 'INT',
				'constraint' => 50,
				'default' => 0
			 ),
			 'token' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			 ),
			 'series' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			 ),
			 'date' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			)
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('token');
		$this->dbforge->create_table('user_persistence', TRUE);
        

		
		echo "<p>Step 2 completed.</p>";
		
		$query = $this->db->get('users');
		
		if($query->num_rows() == 0)
		{
			echo "<p> Now we need some information for the super admin.</p><form method='post' action='" . site_url('install/step3') . "'>
			<label for='username' style='width: 150px; font-weight: bold;'>Username : </label><input type='text' name='username' value='admin' style='width: 200px'/><br />
			<label for='password' style='width: 150px; font-weight: bold;'>Password : </label><input type='password' name='password' value='' style='width: 200px'/><br />
			<label for='email' style='width: 150px; font-weight: bold;'>Email : </label><input type='text' name='email' value='' style='width: 200px'/><br />
			<input type='submit' value='  Continue... ' />
			</form>";
		}
		else
		{
			echo "<p>Step 3 not needed, " . anchor('install/step4', 'Click here to continue') . "</p>";
		}

	
	}
	
	function step3()
	{
			
        //creating admins table
        $fields = array(
            'id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             'username' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
             'module' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
             'level' => array(
                'type' => 'INT',
                'constraint' => 1,
                'default' => 0
             )
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('username');
        $this->dbforge->create_table('admins', TRUE);

         $query = $this->db->get('admins');
		
        if($query->num_rows() == 0)
        {

			if(($username = $this->input->post('username')) && ($password = $this->input->post('password')) && ($email = $this->input->post('email')))
			{
                $this->load->library('encrypt');
                $data	= 	array(
                                'username'	=> $username,
                                'password'	=> $this->encrypt->sha1($password.$this->config->item('encryption_key')),
                                'email'		=> $email,
                                'status'	=> 'active',
                                'registered'=> mktime()
                            );
                $this->db->insert('users', $data);
                
                


				
				$this->db->insert('admins', array('username' => $username, 'module' => 'admin', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'page', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'module', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'news', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'member', 'level' => 4));
				$this->db->insert('admins', array('username' => $username, 'module' => 'language', 'level' => 4));
				
				
				
				
				
				echo "<p>Step 3 completed, " . anchor('install/step4', 'Click here to continue') . "</p>";
				
			}
			else
			{
			echo "<p> Please fill all fields.</p><form method='post' action='" . site_url('install/step3') . "'>
			<label for='username' style='width: 150px; font-weight: bold;'>Username : </label><input type='text' name='username' value='" . $this->input->post('username') . "' style='width: 200px'/><br />
			<label for='password' style='width: 150px; font-weight: bold;'>Password : </label><input type='password' name='password' value='" . $this->input->post('password') . "' style='width: 200px'/><br />
			<label for='email' style='width: 150px; font-weight: bold;'>Email : </label><input type='text' name='email' value='" . $this->input->post('username') . "' style='width: 200px'/><br />
			<input type='submit' value='  Try again... ' />
			</form>";
				return;
			}
		}
		else
		{
			echo "<p>Step 3 completed, " . anchor('install/step4', 'Click here to continue') . "</p>";
		}
	
	}
	
	function step4()
	{
		$this->load->dbforge();	
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'parent_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			 ),
			'active' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'weight' => array(
					 'type' => 'INT',
					 'constraint' => '3',
					'default' => 0
			  ),
			'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'uri' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
					 'default' => 'en'
			  ),
			'meta_keywords' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'meta_description' => array(
					 'type' => 'TEXT',
					 'null' => true
			  ),
			'body' => array(
					 'type' => 'LONGTEXT',
					 'null' => true
			  ),
			'hit' => array(
					 'type' => 'INT',
					 'constraint' => '11',
					'default' => 0
			  ),
			'options' => array(
					 'type' => 'TEXT',
					 'null' => true
			  ),
			'email' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			 'date' => array(
					 'type' => 'INT',
					 'constraint' => '11',
					 'default' => mktime()
			  ),
			'username' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'g_id' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '20',
					 'default' => '0'
			  ),

		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('uri');
		$this->dbforge->add_key('active');
		$this->dbforge->create_table('pages', TRUE);


		$query = $this->db->get('pages');
		
		if($query->num_rows() == 0)
		{
            $data = array('title' => 'This is just a test', 'uri' => 'home', 'lang' => 'en', 'body' => '<p>This is how it looks in <b>English</b>. To modify this text go to  <i>admin/pages</i>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
            $this->db->insert('pages', $data);
            $data = array('title' => 'About page', 'uri' => 'about', 'lang' => 'en', 'body' => '<p>About this site..</p>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
            $this->db->insert('pages', $data);

            $data = array('title' => 'This is just a test', 'uri' => 'home', 'lang' => 'fr', 'body' => '<p>This is how it looks in <b>French</b>. To modify this text go to  <i>admin/pages</i>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
            $this->db->insert('pages', $data);
            $data = array('title' => 'About page', 'uri' => 'about', 'lang' => 'fr', 'body' => '<p>About this site..</p>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
            $this->db->insert('pages', $data);

            $data = array('title' => 'This is just a test', 'uri' => 'home', 'lang' => 'it', 'body' => '<p>This is how it looks in <b>Italian</b>. To modify this text go to  <i>admin/pages</i>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
            $this->db->insert('pages', $data);
            $data = array('title' => 'About page', 'uri' => 'about', 'lang' => 'it', 'body' => '<p>About this site..</p>', 'options' => 'a:4:{s:13:"show_subpages";s:1:"1";s:15:"show_navigation";s:1:"1";s:14:"allow_comments";s:1:"0";s:6:"notify";s:1:"0";}');
            $this->db->insert('pages', $data);
		
		}
		//page comments
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			 ),
			'status' => array(
					 'type' => 'TINYINT',
					 'constraint' => '1',
					'default' => 1
			  ),
			'weight' => array(
					 'type' => 'INT',
					 'constraint' => '3',
					'default' => 0
			  ),
			'author' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'website' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'website' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'body' => array(
					 'type' => 'TEXT',
					 'null' => true
			  ),
			'email' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			'ip' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					 'default' => ''
			  ),
			 'date' => array(
					 'type' => 'INT',
					 'constraint' => '11',
					 'default' => mktime()
			  )

		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('page_id');
		$this->dbforge->add_key('date');
		$this->dbforge->create_table('page_comments', TRUE);		
		
		
  		$fields = array(
			'session_id' => array(
					 'type' => 'VARCHAR',
					 'constraint' => 40,
					 'default' => '0'
			  ),
			 'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 16,
				'default' => '0'
			 ),
			'user_agent' => array(
					 'type' => 'VARCHAR',
					 'constraint' => 50,
					 'default' => ''
			  ),
			'last_activity' => array(
					 'type' => 'INT',
					 'constraint' => '10',
					'default' => 0
			  ),
			'user_data' => array(
					 'type' => 'TEXT'
			  ),
		);
		
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->create_table('sessions', TRUE);
		//settings
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => '0'
			 ),
			 'value' => array(
				'type' => 'TEXT',
				'default' => ''
			 ),
                         'base_url' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100,
                                'default' => $this->config->item('base_url')
                         )
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name');
                $this->dbforge->add_key('base_url');
		$this->dbforge->create_table('settings', TRUE);
		
		$query = $this->db->get('settings');
		
		if($query->num_rows() == 0)
		{
            $data = array('name' => 'site_name', 'value' => 'CI-CMS');
            $this->db->insert('settings', $data);
            
            $data = array('name' => 'meta_keywords', 'value' => 'CI-CMS');
            $this->db->insert('settings', $data);
            $data = array('name' => 'meta_description', 'value' => 'CI-CMS, another content managment system');
            $this->db->insert('settings', $data);
            $data = array('name' => 'cache', 'value' => '0');
            $this->db->insert('settings', $data);
            $data = array('name' => 'cache_time', 'value' => '300');
            $this->db->insert('settings', $data);
            $data = array('name' => 'theme_dir', 'value' => 'themes/');
            $this->db->insert('settings', $data);
            $data = array('name' => 'theme', 'value' => 'default');
            $this->db->insert('settings', $data);
            $data = array('name' => 'template', 'value' => 'index');
            $this->db->insert('settings', $data);
            $data = array('name' => 'page_home', 'value' => 'home');
            $this->db->insert('settings', $data);
            $data = array('name' => 'debug', 'value' => '0');
            $this->db->insert('settings', $data);
            $data = array('name' => 'version', 'value' => '2.1.0.1');
            $this->db->insert('settings', $data);
            $data = array('name' => 'page_approve_comments', 'value' => '1');
            $this->db->insert('settings', $data);
            $data = array('name' => 'page_notify_admin', 'value' => '1');
            $this->db->insert('settings', $data);
            $data = array('name' => 'news_settings', 'value' => serialize(array('allow_comments' => 1,'approve_comments' => 1)));
            $this->db->insert('settings', $data);
		
		}
		//modules
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'with_admin' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			 'version' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			 'ordering' => array(
				'type' => 'INT',
				'constraint' => 4,
				'default' => 0
			),
			 'info' => array(
				'type' => 'TEXT'
				),
			 'description' => array(
				'type' => 'TEXT'
			)
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('modules', TRUE);
		
		$query = $this->db->get('modules');
		
		if($query->num_rows() == 0)
		{
            $this->db->query("INSERT INTO " . $this->db->dbprefix('modules') . " (id, name, with_admin, version, status, ordering, info, description) VALUES (1, 'admin', 0, '1.2.0', 1, 5, '', 'Admin core module'), (2, 'module', 0, '1.0.0', 1, 20, '', 'Module core module'), (3, 'page', 1, '1.2.0', 1, 60, '', 'Page core module'), (4, 'language', 1, '1.1.0', 1, 10, '', 'Language core module'), (5, 'member', 1, '1.0.0', 1, 30, '', 'Member core module'), (6, 'search', 0, '1.0.0', 1, 50, '', 'Search core module'), (7, 'news', 1, '1.3.0', 1, 101, '', 'News module')");
		}
        
        //groups
  		$fields = array(
			'id' => array(
					 'type' => 'INT',
					 'constraint' => 11,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
			  ),
			 'g_id' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'g_owner' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			 ),
			 'g_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			 ),
			 'g_date' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			),
			 'g_desc' => array(
				'type' => 'TEXT'
			)
		);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('groups', TRUE);
        
        
		$query = $this->db->get('groups');
		
		if($query->num_rows() == 0)
		{
            $this->db->query("CREATE UNIQUE INDEX g_id_g_name ON " . $this->db->dbprefix("groups") . " (g_id, g_name)");
            
            $this->db->query("INSERT INTO " . $this->db->dbprefix('groups') . " (g_id, g_name, g_desc) VALUES ('0', 'Everybody', 'This is everybody who visits the site including non members')");

            $this->db->query("INSERT INTO " . $this->db->dbprefix('groups') . " (g_id, g_name, g_desc) VALUES ('1', 'Members Only', 'This is everybody who is member of the site')");
        }
        
        
        //groups members
        $fields = array(
            'id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             'g_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => ''
             ),
             'g_user' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => ''
             ),
             'g_to' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
             'g_from' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
             'g_date' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            )
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('group_members', TRUE);
    
		$query = $this->db->get('group_members');
 		if($query->num_rows() == 0)
		{
            $this->db->query("CREATE UNIQUE INDEX g_member_g_id ON " . $this->db->dbprefix("group_members") . " (g_user, g_id)");
        }
    
    
         //search_results 
        $fields = array(
            'id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             's_tosearch' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
			 's_rows' => array(
				'type' => 'TEXT'
			),
             's_date' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            )
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('s_tosearch');
        $this->dbforge->create_table('search_results', TRUE);

         //images 
        $fields = array(
            'id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             'module' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
             'file' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
			 'info' => array(
				'type' => 'TEXT'
			),
             'src_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
             'ordering' => array(
                'type' => 'INT',
                'constraint' => 4,
                'default' => 0
            )
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('module');
        $this->dbforge->create_table('images', TRUE);

         //captcha 
        $fields = array(
            'captcha_id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => ''
             ),
             'word' => array(
                'type' => 'VARCHAR',
                'constraint' => 25,
                'default' => ''
             ),
             'captcha_time' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            )
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('captcha_id', TRUE);
        $this->dbforge->create_table('captcha', TRUE);


 		
		echo "<p>Step 4 completed. " . anchor('install/step5', 'Click here to continue') . "</p>";
		
	}
	
	function step5()
	{
         //news 
        $fields = array(
            'id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
             'uri' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
             'lang' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => ''
             ),
             'author' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
             'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
 			 'body' => array(
				'type' => 'TEXT'
			),
            'cat' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'allow_comments' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'comments' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'status' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'date' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'hit' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'notify' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'ordering' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            )
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('cat');
        $this->dbforge->add_key('title');
        $this->dbforge->create_table('news', TRUE);	
        
//news_comments 
        $fields = array(
            'id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             'website' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
             'ip' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
             'author' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
             'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
 			 'body' => array(
				'type' => 'TEXT'
			),
            'status' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'date' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'news_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			)
         
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('news_id');
        $this->dbforge->add_key('author');
        $this->dbforge->create_table('news_comments', TRUE);	
        

//news_comment 
        $fields = array(
            'id' => array(
                     'type' => 'INT',
                     'constraint' => 11,
                     'unsigned' => TRUE,
                     'auto_increment' => TRUE
              ),
             'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
             'lang' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
             'uri' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => ''
             ),
             'username' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
              'access' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => '0'
             ),
             'icon' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => ''
             ),
 			 'desc' => array(
				'type' => 'TEXT'
			),
            'weight' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'date' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			),
            'status' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1
			),
            'pid' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
			)
         
        );
        $this->dbforge->add_field($fields); 
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('pid');
        $this->dbforge->add_key('uri');
        $this->dbforge->create_table('news_cat', TRUE);	



$fields = array(
	'id' => array(
			 'type' => 'INT',
			 'constraint' => 5,
			 'unsigned' => TRUE,
			 'auto_increment' => TRUE
	  ),
	'tag' => array(
			 'type' => 'VARCHAR',
			 'constraint' => '255',
	  ),
	'uri' => array(
			 'type' => 'VARCHAR',
			 'constraint' => '255',
	  ),
	'news_id' => array(
			 'type' => 'INT',
			 'constraint' => '5',
	  )
);
$this->load->dbforge();
$this->dbforge->add_field($fields); 
$this->dbforge->add_key('id', TRUE);
$this->dbforge->add_key('tag');
$this->dbforge->create_table('news_tags', TRUE);

		
		$query = $this->db->get('news');
		
		if($query->num_rows() == 0)
		{
		$data = array('title' => 'Your first news', 'uri' => 'your-first-news-en', 'lang' => 'en', 'body' => 'This news is supposed to be in English but I leave it in English now', 'status' => 1, 'date' => mktime());
		$this->db->insert('news', $data);
		$data = array('title' => 'Your first news', 'uri' => 'your-first-news-fr', 'lang' => 'fr', 'body' => 'This news is supposed to be in French but I leave it in English now', 'status' => 1, 'date' => mktime());
		$this->db->insert('news', $data);
		$data = array('title' => 'Your first news', 'uri' => 'your-first-news-it', 'lang' => 'it', 'body' => 'This news is supposed to be in Italian but I leave it in English now', 'status' => 1, 'date' => mktime());
		$this->db->insert('news', $data);
		}
		echo "<p>Step 5 completed. " . anchor('install/step6', 'Click here to continue') . "</p>";
		
	}
	
	function step6()
	{
		echo "Installation done. <br />To go to admin interface ". anchor('admin', 'click here') . "<br/>Now you can visit your site " . anchor('', 'here') ;

	}


}