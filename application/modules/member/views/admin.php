<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php  echo __("Member administration", $module)?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/member/settings')?>"><?php  echo __("Settings", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin/member/create')?>"><?php  echo __("Add a new user", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin')?>" class="last"><?php  echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php  if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>
<table>
<form action="<?php  echo site_url($this->uri->uri_string()) ?>" method="post">
<tr>
<td>
</td>
<td>
<input type="text" class="input-text" name="filter" value="<?php  echo $this->input->post('filter') ?>" />
</td>
<td>
<input type="submit" class="input-submit" name="submit" value="<?php  echo __("Search", $module ) ?>" />
</td>
</tr>
</form>
</table>
<?php  if($members) : ?>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?php  echo __("Username", $module)?></th>
				<th width="40%"><?php  echo __("Email", $module)?></th>
				<th width="30%" colspan="3"><?php  echo __("Action", $module)?></th>
		</tr>
	</thead>
	<tbody>
<?php  $i = 1; foreach ($members as $member): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php  echo $rowClass?>">
				<td class="center"><?php  echo ($i + $start) ?></td>
				<td><?php  echo $member['username']?></td>
				<td><?php  echo $member['email']?></td>
				<td>
				<a href="<?php  echo site_url('admin/'.$module.'/status/'. $member['username'].'/'.$member['status'])?>"><?php  echo ($member['status'] == 'active')?__("Deactivate", $module):__("Activate", $module)?></a></td>
				<td><a href="<?php  echo site_url('admin/'.$module.'/edit/'.$member['username'])?>"><?php  echo __("Edit", $module)?></a></td>
				<td><a href="<?php  echo site_url('admin/'.$module.'/delete/'.$member['username'])?>"><?php  echo __("Delete", $module)?></a></td>
		</tr>
<?php  $i++; endforeach;?>
	</tbody>
</table>
<?php  else: ?>

<?php  echo __("No member found", $module) ?>
<?php  endif ; ?>
<?php  echo $pager?>
</div>
<!-- [Content] end -->
