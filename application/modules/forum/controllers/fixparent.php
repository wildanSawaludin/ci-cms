<?php
/*
 * $Id$
 * this is to fix parent (before rev 358)
 * of course you have to remove the exit that I put for security reason;
 */

exit(); 
 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fixparent extends MX_Controller {
	var $template = array();

	function __construct()
	{
		parent::__construct();

	}
	
	function index($start = 0)
	{
		$limit = 50;
		$touched = 0;
		$sql = "SELECT id, mid, pid FROM " . $this->db->dbprefix('forum_messages') . " WHERE pid <> '' ORDER BY id DESC LIMIT {$start}, {$limit}";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$rows = $query->result();
			foreach($rows as $row)
			{
				$sql2 = "SELECT id, mid, pid FROM " . $this->db->dbprefix('forum_messages') . " WHERE mid='" . $row->pid . "' LIMIT 1";
				$query2 = $this->db->query($sql2);
				if($query2->num_rows() > 0)
				{
					$row2 = $query2->row();
					if($row2->pid <> '')
					{
					//mettre à jour tous les messages qui ont le mauvais parent
						$this->db->query("UPDATE " . $this->db->dbprefix('forum_messages') . " SET pid='" . $row2->pid . "' WHERE pid='" . $row->pid . "'"); 
					}
					$touched++;
				}
			}
		$start = $start + $limit;
		echo "<html><body><script language='JavaScript'>
		window.location.replace('" . site_url('forum/fixparent/index/' . $start). "');
		</script></body></html>";
		}
		else
		{
			echo "vita. Touchés = " . $touched;
		}
	}
}
