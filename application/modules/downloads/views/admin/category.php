<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php  echo __("Files", 'downloads')?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/downloads/document/create')?>"><?php  echo __("New File", 'downloads')?></a></li>
	<li><a href="<?php  echo site_url('admin/downloads/category/index/' . $cat['id'])?>" class="last"><?php  echo __("Cancel", 'downloads')?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php  if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?php  echo __("Name", 'downloads')?></th>
				<th width="27%"><?php  echo __("Summary", 'downloads')?></th>
				<th width="15%"><?php  echo __("Status", 'downloads')?></th>
				<th width="5%"><?php  echo __("Ordering", 'downloads')?></th>
				<th width="20%" colspan="2"><?php  echo __("Action", 'downloads')?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php  if ($rows) : ?>
<?php  $i = 1; foreach ($rows as $row): ?>
<?php  
if($page_break_pos = strpos($row['desc'], "<!-- page break -->"))
{
	$row['summary'] = substr($row['desc'], 0, $page_break_pos);
}
else
{
	$row['summary'] = $row['desc'];
}
?>		

<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php  echo $rowClass?>">
				<td class="center"><?php  echo $i?></td>
				<td><?php  echo ($row['level'] > 0) ? "|".str_repeat("__", $row['level']): ""?> <?php  echo (strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></td>
				<td><?php  echo $row['summary']?></td>
				<td><?php  if ($row['status']==1): echo 'Active'; else: echo 'Suspended'; endif;?></td>
				<td>
				<a href="<?php  echo site_url('admin/downloads/category/move/up/'. $row['id'])?>"><img src="<?php  echo site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?php  echo __("Move up", $module)?>" alt="<?php  echo __("Move up", 'downloads')?>"/></a>
				<a href="<?php  echo site_url('admin/downloads/category/move/down/'. $row['id'])?>"><img src="<?php  echo site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?php  echo __("Move down", $module)?>" alt="<?php  echo __("Move down", 'downloads')?>"/></a></td>
				<td><a href="<?php  echo site_url('admin/downloads/category/create/'.$row['id'])?>">Edit</a></td>
				<td><a href="<?php  echo site_url('admin/downloads/category/delete/'.$row['id'])?>">Delete</a></td>
				<td class="center"><?php  echo $row['id']?></td>
		</tr>
<?php  $i++; endforeach;?>
<?php  endif; ?>
	</tbody>
</table>
<?php  echo $pager?>
</div>
<!-- [Content] end -->
