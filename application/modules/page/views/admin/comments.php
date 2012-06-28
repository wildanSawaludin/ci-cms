<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo $title ?></h1>

<ul class="manage">
	<li><a href="<?php echo ($this->uri->segment(4) == 'search') ? site_url('admin/page/comments') : site_url('admin/page')?>" class="last"><?php _e("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<form method='post' action="<?php echo site_url('admin/page/comments/search') ?>" >
<div class="filter">
<label for="tosearch"><?php echo __("Search comment", $module) ?></label>
<input type="text" name="tosearch" id="tosearch" value="<?php if(isset($tosearch)) echo $tosearch ?>" />
<input type="submit" name="submit" value="<?php echo __('Search', $module) ?>" />
</div>
</form>
<table class="page-list">
	<thead>
		<tr>
				<th width="2%" class="center">#</th>
				<th width="10%"><?php echo __("Date", $module) ?></th>
				<th width="15%"><?php echo __("Author", $module) ?></th>
				<th width="50%"><?php echo __("Comment", $module)?></th>
				<th width="23%" colspan="3"><?php echo __("Action", $module)?></th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php echo $rowClass?>">
				<td class="center" valign='top'><?php echo $i?></td>
				<td  valign='top'><?php echo date('d/m/Y', $row['date']) ?></td>
				<td valign='top'><? echo nl2br(strip_tags($row['author'])) ?></td>
				<td valign='top'><? echo nl2br(strip_tags($row['body'])) ?></td>
				<td><a href="<?php echo site_url('page/view/' . $row['page_id'])?>" rel="external" target="page"><?php echo __("View", $module) ?></a></td>
				<td><a href="<?php echo site_url('admin/page/comments/edit/'.$row['id'])?>"><?php echo __("Edit", $module) ?></a></td>
				<td><a href="<?php echo site_url('admin/page/comments/delete/'.$row['id'])?>"><?php echo __("Delete", $module) ?></a></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?php echo $pager?>
</div>
<!-- [Content] end -->
