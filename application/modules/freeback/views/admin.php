<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php  echo __("Freeback Administration", $module)?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/freeback/create')?>"><?php  echo __("Create responder", $module)?></a></li>
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
				<th width="10%"><?php  echo __("Email", $module)?></th>
				<th width="20%" colspan="2"><?php  echo __("Action", $module)?></th>
				<th width="7%" class="last center"><?php  echo __("Hits", $module)?></th>
		</tr>
	</thead>
	<tbody>
<?php  if ($rows) : ?>
<?php  $i = 1; foreach ($rows as $row): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php  echo $rowClass?>">
				<td class="center"><?php  echo $row['id']?></td>
				<td><?php  echo $row['title']?></td>
				<td><a href="<?php  echo site_url('admin/freeback/move/up/'. $row['id'])?>"><img src="<?php  echo site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?php  echo __("Move up", $module)?>" alt="<?php  echo __("Move up", $module)?>"/></a>
				<a href="<?php  echo site_url('admin/freeback/move/down/'. $row['id'])?>"><img src="<?php  echo site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?php  echo __("Move down", $module)?>" alt="<?php  echo __("Move down", $module)?>"/></a></td>
				<td><?php  if ($row['status']==1): echo 'Published'; else: echo 'Draft'; endif;?></td>
				<td><a href="mailto:<?php  echo $row['email'];?>" rel="external"><?php  echo __("Mail", $module)?></a></td>
				<td><a href="<?php  echo site_url('admin/'.$module.'/edit/'.$row['id'])?>"><?php  echo __("Edit", $module)?></a></td>
				<td><a href="<?php  echo site_url('admin/'.$module.'/delete/'.$row['id'])?>"><?php  echo __("Delete", $module)?></a></td>
				<td class="center"><?php  echo $row['hit']?></td>
		</tr>
<?php  $i++; endforeach;?>
<?php  endif; ?>
	</tbody>
</table>
<?php  echo $pager?>
</div>
<!-- [Content] end -->