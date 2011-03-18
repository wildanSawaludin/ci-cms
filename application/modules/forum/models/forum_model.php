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
	var $bbcode_buttons = '<div class="btn bold" title="bold"></div><div class="btn italic"></div><div class="btn underline"></div><div class="btn link"></div><div class="btn quote"></div>
<div class="btn code"></div><div class="btn image"></div><div class="btn usize"></div><div class="btn dsize"></div><div class="btn nlist"></div>
<div class="btn blist"></div><div class="btn litem"></div><div class="btn back"></div><div class="btn forward"></div><div class="btn youtube"></div><div class="btn dailymotion"></div>';
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

		$this->bbcode->add_element('', array(
			'type'=>BBCode::TYPE_ROOT,		
			'content_handling' => 'forum_bbcode_root_function'
			));
		
		
		$smileys = array(
			':-)'			=>	'<img src="http://static.serasera.org/smileys/grin.gif" />',
			':lol:'			=>	'<img src="http://static.serasera.org/smileys/lol.gif" />',
			':cheese:'		=>	'<img src="http://static.serasera.org/smileys/cheese.gif" />',
			':)'			=>	'<img src="http://static.serasera.org/smileys/smile.gif" />',
			';-)'			=>	'<img src="http://static.serasera.org/smileys/wink.gif" />',
			';)'			=>	'<img src="http://static.serasera.org/smileys/wink.gif" />',
			':smirk:'		=>	'<img src="http://static.serasera.org/smileys/smirk.gif" />',
			':roll:'		=>	'<img src="http://static.serasera.org/smileys/rolleyes.gif" />',
			':wow:'			=>	'<img src="http://static.serasera.org/smileys/surprise.gif" />',
			':-S'			=>	'<img src="http://static.serasera.org/smileys/confused.gif" />',
			':bug:'			=>	'<img src="http://static.serasera.org/smileys/bigsurprise.gif" />',
			':-P'			=>	'<img src="http://static.serasera.org/smileys/tongue_laugh.gif" />',
			'%-P'			=>	'<img src="http://static.serasera.org/smileys/tongue_rolleye.gif" />',
			';-P'			=>	'<img src="http://static.serasera.org/smileys/tongue_wink.gif" />',
			':P'			=>	'<img src="http://static.serasera.org/smileys/rasberry.gif" />',
			':blank:'		=>	'<img src="http://static.serasera.org/smileys/blank.gif" />',
			':long:'		=>	'<img src="http://static.serasera.org/smileys/longface.gif" />',
			':ohh:'			=>	'<img src="http://static.serasera.org/smileys/ohh.gif" />',
			':grrr:'		=>	'<img src="http://static.serasera.org/smileys/grrr.gif" />',
			':gulp:'		=>	'<img src="http://static.serasera.org/smileys/gulp.gif" />',
			'8-/'			=>	'<img src="http://static.serasera.org/smileys/ohoh.gif" />',
			':down:'		=>	'<img src="http://static.serasera.org/smileys/downer.gif" />',
			':red:'			=>	'<img src="http://static.serasera.org/smileys/embarrassed.gif" />',
			':sick:'		=>	'<img src="http://static.serasera.org/smileys/sick.gif" />',
			':shut:'		=>	'<img src="http://static.serasera.org/smileys/shuteye.gif" />',
			':-/'			=>	'<img src="http://static.serasera.org/smileys/hmm.gif" />',
			'>:('			=>	'<img src="http://static.serasera.org/smileys/mad.gif" />',
			':mad:'			=>	'<img src="http://static.serasera.org/smileys/mad.gif" />',
			'>:-('			=>	'<img src="http://static.serasera.org/smileys/angry.gif" />',
			':angry:'		=>	'<img src="http://static.serasera.org/smileys/angry.gif" />',
			':zip:'			=>	'<img src="http://static.serasera.org/smileys/zip.gif" />',
			':kiss:'		=>	'<img src="http://static.serasera.org/smileys/kiss.gif" />',
			':ahhh:'		=>	'<img src="http://static.serasera.org/smileys/shock.gif" />',
			':coolsmile:'	=>	'<img src="http://static.serasera.org/smileys/shade_smile.gif" />',
			':coolsmirk:'	=>	'<img src="http://static.serasera.org/smileys/shade_smirk.gif" />',
			':coolgrin:'	=>	'<img src="http://static.serasera.org/smileys/shade_grin.gif" />',
			':coolhmm:'		=>	'<img src="http://static.serasera.org/smileys/shade_hmm.gif" />',
			':coolmad:'		=>	'<img src="http://static.serasera.org/smileys/shade_mad.gif" />',
			':coolcheese:'	=>	'<img src="http://static.serasera.org/smileys/shade_cheese.gif" />',
			':vampire:'		=>	'<img src="http://static.serasera.org/smileys/vampire.gif" />',
			':snake:'		=>	'<img src="http://static.serasera.org/smileys/snake.gif" />',
			':exclaim:'		=>	'<img src="http://static.serasera.org/smileys/exclaim.gif" />',
			':question:'	=>	'<img src="http://static.serasera.org/smileys/question.gif" />'
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
		$youtubeID = strchr($youtubeURL,'=');
		//remove that equals sign to get the video ID 
		$youtubeID = substr($youtubeID,1);
	}
	else
	{
		$youtubeID = $youtubeURL;
	}
	
	
 
	//get the video id in to the embed code
	return "<object width=\"480\" height=\"385\"><param name=\"movie\" value=\"http://www.youtube.com/v/".$youtubeID."&hl=en&fs=1&rel=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.youtube.com/v/".$youtubeID."&hl=en&fs=1&rel=0\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"480\" height=\"385\"></embed></object>"; 
	
}

function forum_bbcode_dailymotion_function($url)
{
	$vid = substr(strrchr($url, '/'), 0, strpos($url, '_'));
	
	
	return '<object width="480" height="360"><param name="movie" value="http://www.dailymotion.com/swf/video/' . $vid .'"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed type="application/x-shockwave-flash" src="http://www.dailymotion.com/swf/video/'. $vid .'" width="480" height="360" allowfullscreen="true" allowscriptaccess="always"></embed></object>';
}