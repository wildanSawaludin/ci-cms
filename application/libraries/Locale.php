<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */



class Locale {
	//initializing
	var $_data;
	var $locale;
	var $_l10n;
	var $table;
	var $codes;
	var $default;

	function __construct() {
		$this->obj =& get_instance();
		$this->obj->load->model('language/language_model');
		
		$this->codes = $this->get_codes();
		$this->default = $this->get_default();
		if (!$this->obj->session->userdata('lang')) {
			$this->obj->session->set_userdata('lang', $this->default);
		}

		log_message('debug', 'Locale Class Initialized');

	}
/*
	function load_messages()
	{
		$handle = opendir(APPPATH.'modules');
	
		if ($handle)
		{
			while ( false !== ($module = readdir($handle)) )
			{
				
				// make sure we don't map silly dirs like .svn, or . or ..

				if (substr($module, 0, 1) != ".")
				{
					if ( file_exists(APPPATH . 'modules/'.$module.'/locale/' . $this->obj->session->userdata('lang') . '.mo' )) { 
						//echo APPPATH . 'modules/'.$module.'/locale/' . $this->obj->session->userdata('lang') . '.mo' ;
						$this->load_textdomain(APPPATH . 'modules/'.$module.'/locale/' . $this->obj->session->userdata('lang') . '.mo', $module );
					}
				}	
			}
		}	
	}
*/	
	
	function get_active()
	{
		return $this->obj->language_model->get_list(array('active' => 1));
	}	
	
	function get_list()
	{
		return $this->obj->language_model->get_list();
	}

	function get_default()
	{
		$row = $this->obj->language_model->get(array('default' => 1));
		
		if ($row)
		{
			return $row['code'] ;
		}
		elseif (strlen( $this->obj->config->item('language') ) == 2 )
		{
			return $this->obj->config->item('language');
		}
		else
		{
			return 'en';
		}
		
	}
	function get_codes()
	{
		
		$rows = $this->obj->language_model->get_list(array('active' => 1));
		$codes = array();
		
		if ( $rows )
		{
			foreach ( $rows as $row )
			{
				$codes[] = $row['code'];
			}
			return $codes;
		}
		else
		{
			return false;
		}
		
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