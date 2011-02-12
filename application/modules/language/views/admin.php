<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php  echo __("Language administration", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/language/settings')?>"><?php  echo __("Settings", $this->template['module'])?></a></li>
	<li><a href="<?php  echo site_url('admin/language/add')?>"><?php  echo __("Add new language", $this->template['module'])?></a></li>
	<li><a href="<?php  echo site_url('admin')?>" class="last"><?php  echo __("Cancel", $this->template['module'])?></a></li>
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
				<th width="15%"><?php  echo __("Code", $this->template['module'])?></th>
				<th width="20%"><?php  echo __("Name", $this->template['module'])?></th>
				<th width="10%"><?php  echo __("Ordering", $this->template['module'])?></th>
				<th width="15%"><?php  echo __("Default", $this->template['module'])?></th>
				<th width="10%"><?php  echo __("Active", $this->template['module'])?></th>
				<th width="25%" colspan=3><?php  echo __("Action", $this->template['module'])?></th>
		</tr>
	</thead>
	<tbody>
<?php  $i = 1; foreach ($langs as $lang): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php  echo $rowClass?>">
				<td class="center"><?php  echo $i?></td>
				<td><?php  echo $lang['code']?></td>
				<td><?php  echo $lang['name']?></td>
				<td><?php  echo $lang['ordering']?></td>
				<td><?php  if ($lang['default']==1): echo __("Yes", $this->template['module']); else: echo "<a href='" . site_url('admin/language/setdefault/'. $lang['id']) . "'>" . __("Make default", $this->template['module']) . "</a>" ;endif;?></td>
				<td><?php  if ($lang['active']==1): echo __("Yes", $this->template['module']); else: echo __("No", $this->template['module']); endif;?></td>
				<td><?php  if ($lang['active']==1): echo "<a href='" . site_url('admin/language/active/0/'. $lang['id']) . "'>" . __("Deactivate", $this->template['module']) . "</a>"; else: echo "<a href='" . site_url('admin/language/active/1/'. $lang['id']) . "'>" . __("Activate", $this->template['module']) . "</a>" ;endif;?></td>
				<td><a href='<?php  echo site_url('admin/language/delete/'. $lang['id'])?>'><?php  echo __("Delete", $this->template['module'])?></a></td>
				<td><a href='<?php  echo site_url('admin/language/edit/'. $lang['id'])?>'><?php  echo __("Edit", $this->template['module'])?></a></td>
		</tr>
<?php  $i++; endforeach;?>
	</tbody>
</table>

</div>
<!-- [Content] end -->
