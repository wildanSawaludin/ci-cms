<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forum_model extends CI_Model {

	var $options = array();
	var $settings = array('style' => null);
	function __construct()
	{
		parent::__construct();
		$this->get_settings();		
		
	}
	
	function get_user_level()
	{
		$level = array();
		if($this->user->logged_in)
		{
		$query = $this->db->query("SELECT username, level, tid FROM " . $this->db->dbprefix('forum_admins') . " WHERE username = " . $this->db->escape($this->user->username));
		if($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$level[$row['tid']] = $row['level'];
			}
		}
		
		$this->user->forum_level = $level;
		}
		else
		{
		$this->user->forum_level = array();
		}
	}
	
	function get_option($name, $default = null)
	{
		if(is_empty($this->options))
		{
			$query = $this->db->get('forum_options');
			
			if($query->num_rows() > 0)
			{
				$rows = $query->result_array();
				foreach($rows as $row)
				{
					$this->options[ $row['name'] ] = $row['value'];
				}
			}
		}
		
		if(isset($this->options[ $name ]))
		{
			return $this->options[ $name ];
		}
		else
		{
			return $default;
		}
	}
	
	function set_option($key, $value = null)
	{
		$this->_get_options();
		
		if ( ! is_array($key))
		{
			$key = array($key => $value);
		}
		
		foreach($key as $akey => $avalue)
		{
			$this->option[ $akey ] = $avalue;
		}
		
		$this->_save_options;
		
	}
	
	function _get_options()
	{
		if(is_empty($this->options))
		{
			$query = $this->db->get('forum_options');
			
			if($query->num_rows() > 0)
			{
				$rows = $query->result_array();
				foreach($rows as $row)
				{
					$this->options[ $row['name'] ] = $row['value'];
				}
			}
		}
	
	}
	
	function _save_options()
	{
		$this->db->delete('forum_options');
		
		$sql = "INSERT INTO " . $this->db->dbprefix("forum_options") . " (`name`, `value`) VALUES ";
		foreach($this->options as $key => $val)
		{
			$sql .= "('" . $key . "', '" . $val . "') ";
			$i++;
			if( $i < count($this->options)) $sql .= ", ";
		}
		$query = $this->db->query($sql);
	}

	function get_settings()
	{
		$query = $this->db->get('forum_settings');
		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $row)
		   {
			  $this->settings[$row->name] = $row->value;
		   }
		   
		}			
	}
	function save_settings($name, $value)
	{	
		//update only if changed
		if (!isset($this->settings[$name])) {
			$this->settings[$name] = $value;
			$this->db->insert('forum_settings', array('name' => $name, 'value' => $value));
		}
		elseif ($this->settings[$name] != $value) 
		{
			$this->settings[$name] = $value;
			$this->db->update('forum_settings', array('value' => $value), "name = '$name'");
		}
		
		
	}

	function load_bbcode()
	{
		$this->load->library("bbcode");
		
		//YOUTUBE
		$this->bbcode->add_element('youtube', array(
			'type'=>BBCode::TYPE_OPTARG,		
			'content_handling' => 'forum_bbcode_youtube_function'
			));
		//dailymotion
		$this->bbcode->add_element('dailymotion', array(
			'type'=>BBCode::TYPE_OPTARG,		
			'content_handling' => 'forum_bbcode_dailymotion_function'
			));

		//soundcloud
		$this->bbcode->add_element('soundcloud', array(
			'type'=>BBCode::TYPE_OPTARG,
			'content_handling' => 'forum_bbcode_soundcloud_function'
			));

		$this->bbcode->add_element('', array(
			'type'=>BBCode::TYPE_ROOT,		
			'content_handling' => 'forum_bbcode_root_function'
			));
		
		
		$smileys = array(
			':-)'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/grin.gif" />',
			':lol:'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/lol.gif" />',
			':cheese:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/cheese.gif" />',
			':)'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/smile.gif" />',
			';-)'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/wink.gif" />',
			';)'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/wink.gif" />',
			':smirk:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/smirk.gif" />',
			':roll:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/rolleyes.gif" />',
			':wow:'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/surprise.gif" />',
			':-S'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/confused.gif" />',
			':bug:'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/bigsurprise.gif" />',
			':-P'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/tongue_laugh.gif" />',
			'%-P'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/tongue_rolleye.gif" />',
			';-P'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/tongue_wink.gif" />',
			':P'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/rasberry.gif" />',
			':blank:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/blank.gif" />',
			':long:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/longface.gif" />',
			':ohh:'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/ohh.gif" />',
			':grrr:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/grrr.gif" />',
			':gulp:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/gulp.gif" />',
			'8-/'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/ohoh.gif" />',
			':down:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/downer.gif" />',
			':red:'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/embarrassed.gif" />',
			':sick:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/sick.gif" />',
			':shut:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shuteye.gif" />',
			':-/'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/hmm.gif" />',
			'>:('			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/mad.gif" />',
			':mad:'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/mad.gif" />',
			'>:-('			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/angry.gif" />',
			':angry:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/angry.gif" />',
			':zip:'			=>	'<img src="' . base_url() . 'application/modules/forum/smileys/zip.gif" />',
			':kiss:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/kiss.gif" />',
			':ahhh:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shock.gif" />',
			':coolsmile:'	=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shade_smile.gif" />',
			':coolsmirk:'	=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shade_smirk.gif" />',
			':coolgrin:'	=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shade_grin.gif" />',
			':coolhmm:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shade_hmm.gif" />',
			':coolmad:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shade_mad.gif" />',
			':coolcheese:'	=>	'<img src="' . base_url() . 'application/modules/forum/smileys/shade_cheese.gif" />',
			':vampire:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/vampire.gif" />',
			':snake:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/snake.gif" />',
			':exclaim:'		=>	'<img src="' . base_url() . 'application/modules/forum/smileys/exclaim.gif" />',
			':question:'	=>	'<img src="' . base_url() . 'application/modules/forum/smileys/question.gif" />'
			);
			
		$this->bbcode->attach_smileys($smileys);
	
	}

	
}

function forum_bbcode_root_function($ret)
{
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("/([a-zA-Z0-9-_]+)@([a-zA-Z0-9-_]+)/", "\\1-at-\\2", $ret);
	return $ret;
}


function forum_bbcode_youtube_function($youtubeURL)
{
	if(strpos($youtubeURL, 'http://') !== false)
	{
        $arrayUrl = parse_url($youtubeURL);
        $query = $arrayUrl['query'];
        parse_str($query, $arrayQuery);
		$youtubeID = $arrayQuery['v'];

        }
	else
	{
		$youtubeID = $youtubeURL;
	}
	
	
 
	//get the video id in to the embed code
	/* 
    return "<object width=\"480\" height=\"385\"><param name=\"movie\" value=\"http://www.youtube.com/v/".$youtubeID."&hl=en&fs=1&rel=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.youtube.com/v/".$youtubeID."&hl=en&fs=1&rel=0\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"480\" height=\"385\"></embed></object>"; 
     */
    return "<iframe width=\"560\" height=\"315\" src=\"http://www.youtube.com/embed/" . $youtubeID ."\" frameborder=\"0\" allowfullscreen></iframe>";
	
}

function forum_bbcode_dailymotion_function($url)
{
	$vid = substr(strrchr($url, '/'), 0, strpos($url, '_'));
	
	
	return '<object width="480" height="360"><param name="movie" value="http://www.dailymotion.com/swf/video/' . $vid .'"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed type="application/x-shockwave-flash" src="http://www.dailymotion.com/swf/video/'. $vid .'" width="480" height="360" allowfullscreen="true" allowscriptaccess="always"></embed></object>';
}

function forum_bbcode_soundcloud_function($url)
{
	//try with this later on http://jurawa.com/notes/item/29-embedding-the-new-soundcloud-html5-player-using-the-soundcloud-api-and-php
	//but now just be simple
	
	return '<object height="81" width="500"> <param name="movie" value="http://player.soundcloud.com/player.swf?url=' . $url . '&amp;show_comments=true&amp;auto_play=false"></param> <param name="allowscriptaccess" value="always"></param> <embed allowscriptaccess="always" height="81" src="http://player.soundcloud.com/player.swf?url=$1&amp;show_comments=true&amp;auto_play=false" type="application/x-shockwave-flash" width="500"></embed></object>';
				

}