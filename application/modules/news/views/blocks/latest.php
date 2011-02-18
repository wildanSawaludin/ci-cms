<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<h3><?php  echo __("Latest news", $this->template['module'])?></h3>
<ul>
<?php  foreach ($rows as $row): ?>
	<li><a href="<?php  echo site_url('news/'. $row['uri'])?>"><?php  echo (($row['title']) > 20 )? substr($row['title'], 0, 20) . '...': $row['title']?></a></li>
<?php  endforeach; ?>
</ul>