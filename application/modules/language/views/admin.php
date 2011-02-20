<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php  echo __("Language administration", $module)?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/language/settings')?>"><?php  echo __("Settings", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin/language/add')?>"><?php  echo __("Add new language", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin')?>" class="last"><?php  echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php  if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="5%" class="center">#</th>
				<th width="15%"><?php  echo __("Code", $module)?></th>
				<th width="20%"><?php  echo __("Name", $module)?></th>
				<th width="10%"><?php  echo __("Ordering", $module)?></th>
				<th width="15%"><?php  echo __("Default", $module)?></th>
				<th width="10%"><?php  echo __("Active", $module)?></th>
				<th width="25%" colspan=3><?php  echo __("Action", $module)?></th>
		</tr>
	</thead>
	<tbody>
<?php  $i = 1; foreach ($langs as $lang): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php  echo $rowClass?>">
				<td class="center"><?php  echo $i?></td>
				<td><?php  echo $lang['code']?></td>
				<td><?php  echo $lang['name']?></td>
				<td><a href="<?php  echo site_url('admin/language/move/up/'. $lang['id'])?>"><img src="<?php  echo base_url() . 'application/views/' . $this->system->theme_dir . '/admin/images/moveup.gif'?>" width="16" height="16" title="<?php  echo __("Move up", $module)?>" alt="<?php  echo __("Move up", $module)?>"/></a>
				<a href="<?php  echo site_url('admin/language/move/down/'. $lang['id'])?>"><img src="<?php  echo base_url() . 'application/views/' . $this->system->theme_dir . '/admin/images/movedown.gif'?>" width="16" height="16" title="<?php  echo __("Move down", $module)?>" alt="<?php  echo __("Move down", $module)?>"/></a></td>
				<td><?php  if ($lang['default']==1): echo __("Yes", $module); else: echo "<a href='" . site_url('admin/language/setdefault/'. $lang['id']) . "'>" . __("Make default", $module) . "</a>" ;endif;?></td>
				<td><?php  if ($lang['active']==1): echo __("Yes", $module); else: echo __("No", $module); endif;?></td>
				<td><?php  if ($lang['active']==1): echo "<a href='" . site_url('admin/language/active/0/'. $lang['id']) . "'>" . __("Deactivate", $module) . "</a>"; else: echo "<a href='" . site_url('admin/language/active/1/'. $lang['id']) . "'>" . __("Activate", $module) . "</a>" ;endif;?></td>
				<td><a href='<?php  echo site_url('admin/language/delete/'. $lang['id'])?>'><?php  echo __("Delete", $module)?></a></td>
				<td><a href='<?php  echo site_url('admin/language/edit/'. $lang['id'])?>'><?php  echo __("Edit", $module)?></a></td>
		</tr>
<?php  $i++; endforeach;?>
	</tbody>
</table>

</div>
<!-- [Content] end -->
