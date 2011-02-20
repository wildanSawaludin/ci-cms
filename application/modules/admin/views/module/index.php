<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- [Left menu] start -->
<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo __("Modules administration", $module)?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />


<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<p><?php echo __("These are the modules uploaded in your site. Newly uploaded modules need to be installed. You can also deactivate, activate or desinstall all installed modules.", $module)?>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="20%"><?php echo __("Name", $module)?></th>
				<th width="37%"><?php echo __("Description", $module)?></th>
				<th width="10%"><?php echo __("Version", $module)?></th>				
				<th width="30%" colspan="3"><?php echo __("Action", $module)?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($modules as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $i?></td>
				<td><?php echo $row['name']?></td>
				<td><?php echo $row['description']?></td>
				<td><?php echo $row['version']?></td>
				<td>
				<?php if ($row['status'] == 1 && $row['ordering'] >= 100): ?>
				<a href="<?php echo site_url('admin/module/move/up/'. $row['name'])?>"><img src="<?php echo site_url('application/views/' . $this->system->theme_dir  . 'admin/images/moveup.gif')?>" width="16" height="16" title="<?php echo __("Move up", $module)?>"/></a>
				<a href="<?php echo site_url('admin/module/move/down/'. $row['name'])?>"><img src="<?php echo site_url('application/views/' . $this->system->theme_dir  . 'admin/images/movedown.gif')?>" width="16" height="16" title="<?php echo __("Move down", $module)?>"/></a>
				</td>
				<?php endif; ?>
				<td>
				<?php if ($row['status'] == 1 && $row['ordering'] >= 100): ?>
				<a href="<?php echo site_url('admin/module/deactivate/'. $row['name'])?>"><?php echo __("Deactivate", $module)?></a>
				<?php elseif ($row['status'] == 0) : ?>
				<a href="<?php echo site_url('admin/module/activate/'. $row['name'])?>"><?php echo __("Activate", $module)?></a>
				<?php endif; ?>
				</td>
				<td>
				<?php if ($row['status'] == 0  && $row['ordering'] >= 100): ?>
				<a href="<?php echo site_url('admin/module/uninstall/'. $row['name'])?>"><?php echo __("Uninstall", $module)?></a>
				<?php elseif ($row['status'] == -1): ?>
				<a href="<?php echo site_url('admin/module/install/'. $row['name'])?>"><?php echo __("Install", $module)?></a>
				<?php else: ?>
					<?php if (isset($row['nversion']) && $row['nversion'] > $row['version']) : ?>
					<a href="<?php echo site_url('admin/module/update/'. $row['name'])?>"><span style='color: #FF0000'><?php echo __("Update", $module)?></span></a>
					<?php endif; ?>
				<?php endif; ?>
				</td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?//=$pager?>
</div>
<!-- [Content] end -->

