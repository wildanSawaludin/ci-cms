<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */



class Cms_locale {
	//initializing
	var $_data;
	var $locale;
	var $_l10n;
	var $table;
	var $codes;
	var $default;
	var $_actives;
	var $_list;

	function __construct() {
		$this->obj =& get_instance();
		$this->obj->load->model('language/language_model');
		
		$this->codes = $this->get_codes();
		$this->default = $this->get_default();
		if (!$this->obj->session->userdata('lang')) {
			$this->obj->session->set_userdata('lang', $this->default);
		}

		log_message('debug', 'CMS_Locale Class Initialized');

	}

	function get_active()
	{
		if (!isset($this->_active))
		{
			$this->_active = $this->obj->language_model->get_list(array('active' => 1));
		}
		return $this->_active;
	}	
	
	function get_list()
	{
		if (!isset($this->_list))
		{
			$this->_list =  $this->obj->language_model->get_list();
		}
		return $this->_list;
	}

	function get_default()
	{
		if(!isset($this->default))
		{
			$rows = $this->get_active();
			
			if ($rows)
			{
				foreach ( $rows as $row )
				{
					if($row['default'] == 1)
					{
						$this->default = $row['code'];
						return $this->default;
					}
				}
			}
			
			if (strlen( $this->obj->config->item('language') ) == 2 )
			{
				$this->default = $this->obj->config->item('language');
			}
			else
			{
				$this->default = 'en';
			}
		}
		
		return $this->default;
	}
	function get_codes()
	{
		if(!isset($this->codes))
		{
			$rows = $this->get_active();
			$codes = array();
			
			if ( $rows )
			{
				foreach ( $rows as $row )
				{
					$codes[] = $row['code'];
				}
				$this->codes = $codes;
			}
			else
			{
				$this->codes = false;
			}
		}
		return $this->codes;
		
	}


	function __($text, $domain = 'default') {

		if (isset($this->_l10n[$domain]))
			return $this->_l10n[$domain]->translate($text);
		else
			return $text;
	}

	function tr($text, $domain = 'default') {
		
		return $this->__($text, $domain);
	}
	function _e($text, $domain = 'default') {
		
		echo $this->__($text, $domain);
	}

	function __ngettext($single, $plural, $number, $domain = 'default') {

		if (isset($this->_l10n[$domain])) {
			return $this->_l10n[$domain]->ngettext($single, $plural, $number);
		} else {
			if ($number != 1)
				return $plural;
			else
				return $single;
		}
	}
	function load_textdomain($mofile, $domain = 'default') {
		
		
		include_once(APPPATH . 'libraries/gettext' . EXT);
		include_once(APPPATH . 'libraries/streams' . EXT);
		
		if (isset($this->_l10n[$domain]))
			
			return;

		if ( is_readable($mofile)) {
			
			$input = new CachedFileReader($mofile);
			
		}	
		else
		{
			return;
		}
		//echo $domain;
		$this->_l10n[$domain] = new gettext_reader($input);
	}
}

?>