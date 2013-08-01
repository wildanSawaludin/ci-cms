<?php
/*
 * $Id$
 *
 *
 */

exit();
 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {
	var $template = array();

	function __construct()
	{
		parent::__construct();

		$this->load->library("bbcode");
		$this->load->library("bbcode");

		$this->bbcode->add_element('youtube', array(
			'type'=>BBCode::TYPE_OPTARG,		
			'arg_parse'=>false,	
			'open_tag'=>'<object width="480" height="385"><param name="movie" value="{ARG}"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="',
			'close_tag'=>'&hl=fr_FR&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>',
			'default_arg'=>'{CONTENT}'
			));
		
	}
	/*
	list of forum
	*/

	function index()
	{
		$msg = "test<br />[youtube]http://www.youtube.com/v/8kxC8CzwIOY[/youtube]";
		echo $this->bbcode->parse($msg);
		exit;

	}
	
	function xxx($start = 0)
	{
		$this->load->model('message_model', 'message');
		$this->db->select('msgid');
		$this->db->where('submitterx <>' , '');
		$this->db->limit('100', $start);
		
		$query = $this->db->get('dinika_forum_msg_archive');
		
		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
				$this->db->query("UPDATE ci_forum_messages SET username = 'xxx' WHERE mid='" . $row['msgid'] . "'");
				/*
				$query = $this->db->query("SELECT username FROM ci_forum_messages WHERE  mid='" . $row['msgid'] . "'" );
				$row = $query->row_array();
				
				var_dump($row);
				*/
				
			}
			
			$start = $start + 100;
			redirect('forum/test/xxx/' . $start, 'refresh');
		}
		else
		{
			echo  "done";
			exit;
		}
	}
}
