<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<h3><?php  echo __("Latest Pages", $this->template['module'])?></h3>
<ul>
<?php  foreach ($pages as $page): ?>
	<li><a href="<?php  echo site_url($page['uri'])?>"><?php  echo (($page['title']) > 20 )? substr($page['title'], 0, 20) . '...': $page['title']?></a></li>
<?php  endforeach; ?>
</ul>