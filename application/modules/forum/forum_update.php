<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//get the new module version from xml file.
$module = 'forum';


$version = "1.1.0";

//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	
	$query = $this->db->query("UPDATE " . $this->db->dbprefix('modules') . " SET with_admin = 1 WHERE name='forum' ");
	
	$this->session->set_flashdata("notification", sprintf(__("Forum module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}

$version = "1.2.0";

//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	
	$query = $this->db->query("ALTER TABLE " . $this->db->dbprefix('forum_messages') . " ADD notify CHAR(1) NOT NULL DEFAULT 'N' ");
	
	$this->session->set_flashdata("notification", sprintf(__("Forum module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}

$version = "1.3.0";

//settings
//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	
$query = 
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('forum_settings')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`name` VARCHAR( 100 ) NOT NULL ,
`value` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `name` )
);";

$this->db->query($query);
	
	$this->session->set_flashdata("notification", sprintf(__("Forum module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}


$version = "1.4.0";

//preview, age color


if ($this->system->modules[$module]['version'] < $version)
{
	
	
	$this->session->set_flashdata("notification", sprintf(__("Forum module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}


$version = "2.0.0";

//Switch to CI-CMS2


if ($this->system->modules[$module]['version'] < $version)
{
	
	
	$this->session->set_flashdata("notification", sprintf(__("Forum module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}

$version = "2.1.0";

//smileys and js removed from static.serasera.org


if ($this->system->modules[$module]['version'] < $version)
{
	
	
	$this->session->set_flashdata("notification", sprintf(__("Forum module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}
