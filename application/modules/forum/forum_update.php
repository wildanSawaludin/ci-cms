<?php   if (!defined('BASEPATH')) exit('No direct script access allowed');

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
