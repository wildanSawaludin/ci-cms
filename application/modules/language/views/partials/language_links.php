<h3><?php  echo __("Language list", $this->template['module'])?></h3>
<?php if (is_array($langs)) : ?>
<ul>
	<?php foreach ($langs as $lang) :?>
	<li><a href="<?php  echo site_url($lang['code'])?>"><?php  echo $lang['name']?></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>