<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php  echo __("Forum Administration", $module)?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/forum/settings')?>"><?php  echo __("Settings", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin/forum/category')?>"><?php  echo __("Manage messages", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin/forum/topic/add')?>"><?php  echo __("Add new topic", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin')?>" class="last"><?php  echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php  if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">ID</th>
				<th width="40%"><?php  echo __("Title", $module)?></th>
				<th width="10%"><?php  echo __("Ordering", $module)?></th>
				<th width="10%"><?php  echo __("Status", $module)?></th>
				<th width="30%" colspan="3"><?php  echo __("Action", $module)?></th>
				<th width="7%" class="last center"><?php  echo __("Hits", $module)?></th>
		</tr>
	</thead>
	<tbody>
	<?php  var_dump($rows) ?>
<?php  if ($rows) : ?>
<?php  $i = 1; foreach ($rows as $row): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<tr class="<?php  echo $rowClass?>">
<td valign="top">
<?php  echo anchor('forum/topic/' . $row['tid'], strip_tags($row['title'])) ?>
</td>
<td valign="top">
<?php  echo $row['description'] ?>
</td>
<td valign="top">
<?php  echo $row['username'] ?>
</td>
<td valign="top">
<?php  echo $row['messages'] ?>
</td>
<td valign="top">
<?php  
if($row['last_mid']) :
echo sprintf(__("on %s by %s", $module), anchor('forum/message/' . $row['last_mid'], date("d/M/Y H:i", $row['last_date'])), anchor('profile/' . $row['last_username'], $row['last_username']));
endif;
?>
</td>
</tr>
<?php  $i++; endforeach; ?> 
</tbody>
<?php  endif ?>
</table>

<?php  echo $pager ?>
</div>
