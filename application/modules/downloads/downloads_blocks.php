<?php

$this->set('downloads_new_documents', 'downloads_new_documents');
function downloads_new_documents($params = array())
{
	$obj =& get_instance();
	$params['where'] = "d.acces = '0'" ;
	$obj->load->model('downloads/download_model', 'downloads');
	$rows = $obj->downloads->get_list($params) ;
	return $rows;

}