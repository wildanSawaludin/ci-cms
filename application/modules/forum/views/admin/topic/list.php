<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo __("Forum Administration", $module)?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/forum/settings')?>"><?php echo __("Settings", $module)?></a></li>
	<li><a href="<?php echo site_url('admin/forum/category')?>"><?php echo __("Manage messages", $module)?></a></li>
	<li><a href="<?php echo site_url('admin/forum/topic/add')?>"><?php echo __("Add new topic", $module)?></a></li>
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="40%"><?php echo __("Title", $module)?></th>
				<th width="17%"><?php echo __("Owner", $module)?></th>
				<th width="10%"><?php echo __("Messages", $module)?></th>
				<th width="30%" colspan="3"><?php echo __("Action", $module)?></th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<tr class="<?php echo $rowClass?>">
<td valign="top">
</td>
<td valign="top">
<?php echo anchor('forum/topic/' . $row['tid'], strip_tags($row['title']), array('target' => '_blank')) ?>
</td>
<td valign="top">
<?php echo $row['username'] ?>
</td>
<td valign="top">
<?php echo $row['messages'] ?>
</td>
				<td><a href="<?php echo site_url('forum/topic/'. $row['tid'])?>" target="_blank"><?php echo __("View", $module)?></a></td>
				<td><a href="<?php echo site_url('admin/forum/topic/edit/'.$row['tid'])?>"><?php echo __("Edit", $module)?></a></td>
				<td><a href="<?php echo site_url('admin/forum/topic/delete/'.$row['tid'])?>"><?php echo __("Delete", $module)?></a></td>
</tr>
<?php $i++; endforeach; ?> 
</tbody>
<?php endif ?>
</table>

</div>
