<!-- [Content] start -->
<div class="content wide">

<h1 id="page">Page Admininistration</h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/page/settings')?>"><?php  echo __("Settings", $this->template['module'])?></a></li>
	<li><a href="<?php  echo site_url('admin/page/create')?>"><?php  _e("Create new Page", $this->template['module'])?></a></li>
	<li><a href="<?php  echo site_url('admin')?>" class="last"><?php  _e("Cancel", $this->template['module'])?></a></li>
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
				<th width="27%"><?php  echo __("Title", $this->template['module'])?></th>
				<th width="27%"><?php  echo __("SEF address", $this->template['module'])?></th>
				<th width="5%"><?php  echo __("Status", $this->template['module'])?></th>
				<th width="5%"><?php  echo __("Ordering", $this->template['module'])?></th>
				<th width="27%" colspan="3"><?php  echo __("Action", $this->template['module'])?></th>
				<th width="6%" class="last center"><?php  echo __("Hits", $this->template['module'])?></th>
		</tr>
	</thead>
	<tbody>
<?php  if ($pages) : ?>
<?php  $i = 1; foreach ($pages as $page): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php  echo $rowClass?>">
				<td class="center"><?php  echo $i?></td>
				<td><?php  echo ($page['level'] > 0) ? "|".str_repeat("__", $page['level']): ""?> <?php  echo (strlen($page['title']) > 20? substr($page['title'], 0,20) . '...': $page['title'])?></td>
				<td><?php  echo $page['uri']?></td>
				<td><?php  if ($page['active']==1): echo 'Published'; else: echo 'Draft'; endif;?></td>
				<td>
				<a href="<?php  echo site_url('admin/page/move/up/'. $page['id'])?>"><img src="<?php  echo site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?php  echo __("Move up", $this->template['module'])?>" alt="<?php  echo __("Move up", $this->template['module'])?>"/></a>
				<a href="<?php  echo site_url('admin/page/move/down/'. $page['id'])?>"><img src="<?php  echo site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?php  echo __("Move down", $this->template['module'])?>" alt="<?php  echo __("Move down", $this->template['module'])?>"/></a></td>
				<td><a href="<?php  echo site_url($page['uri'])?>" rel="external">View</a></td>
				<td><a href="<?php  echo site_url('admin/'.$module.'/edit/'.$page['id'])?>">Edit</a></td>
				<td><a href="<?php  echo site_url('admin/'.$module.'/delete/'.$page['id'])?>">Delete</a></td>
				<td class="center"><?php  echo $page['hit']?></td>
		</tr>
<?php  $i++; endforeach;?>
<?php  endif; ?>
	</tbody>
</table>
<?php  echo $pager?>
</div>
<!-- [Content] end -->
