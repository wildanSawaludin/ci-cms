<h1 ><?php  echo __("Unauthorized", $module)?></h1>
	<h2><?php  echo __("Module", $module)?>: <?php  echo ucfirst($data['module'])?></h2>
	<?php  
	switch ($data['level'])
	{
		case 0:
		$levelword = __("have access to", $module);
		break;
		case 1:
		$levelword = __("view in", $module);
		break;
		case 2:
		$levelword = __("add into", $module);
		break;
		case 3:
		$levelword = __("edit in", $module);
		break;
		case 4:
		$levelword = __("delete in", $module);
		break;
	}
	?>
	<?php  echo sprintf( __("Sorry, you cannot %s the %s module", $module), $levelword, $data['module'] )?>
	<p>
	<a href="<?php  echo site_url( $this->session->userdata("last_uri") ) ?>"><?php  _e("Go back", $module) ?></a>
	</p>

