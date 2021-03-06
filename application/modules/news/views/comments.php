<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(){
		if (confirm("<?php  echo addslashes(__("Confirm delete?", $module))?>", $module))
		{
		window.location = this+'/1';
		return false;
		} else {
		return false;
		}
	});
	/*handleDeleteImage();*/
});
</script>
<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php  echo __("Comments", $module)?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/news/settings#two')?>" class="first"><?php  echo __("Settings", $module)?></a></li>
	<li><a href="<?php  echo site_url('admin/news')?>" class="last"><?php  echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php  if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>
<form action="<?php  echo site_url('admin/news/comments')?>" name="filter" method='post'>
<label for="status"><?php  echo __("Show:", $module)?></label>
<select name="status" style="input-select">
<option><?php  echo __("All", $module)?>
<option value="1" <?php  echo ($status == '1')?"selected": ""?>><?php  echo __("Approved", $module)?></option>
<option value="0" <?php  echo ($status == '0')?"selected": ""?>><?php  echo __("Suspended", $module)?></option>
<input type="submit" name="submit" value="Ok"? />
</select>
</form>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="34%"><?php  echo __("Title", $module)?></th>
				<th width="10%"><?php  echo __("Author", $module)?></th>
				<th width="20%"><?php  echo __("Email", $module)?></th>
				<th width="10%"><?php  echo __("Ip", $module)?></th>
				<th width="20%" colspan="2"><?php  echo __("Action", $module)?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php  if ($rows) : ?>
<?php  $i = 1; foreach ($rows as $row): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php  echo $rowClass?>" <?php  echo ($row['status']==0)?"style='color: #AAAAAA'":""?>>
				<td class="center"><?php  echo $i?></td>
				<td><a href="<?php  echo site_url()?>" target="_blank"><?php  echo (word_limiter($row['body'], 4))?></a></td>
				<td><?php  echo $row['author']?></td>
				<td><?php  echo $row['email']?></td>
				<td><?php  echo $row['ip']?></td>
				<?php  if ($row['status'] == 0):?>
				<td><a href="<?php  echo site_url('admin/news/comments/approve/'.$row['id'])?>"><?php  echo __("Approve", $module)?></a></td>
				<?php  else: ?>
				<td><a href="<?php  echo site_url('admin/news/comments/suspend/'.$row['id'])?>"><?php  echo __("Suspend", $module)?></a></td>			
				<?php  endif; ?>
				<td><a class='delete' href="<?php  echo site_url('admin/news/comments/delete/'.$row['id'])?>"><?php  echo __("Delete", $module)?></a></td>
				<td class="center"><?php  echo $row['id']?></td>
		</tr>
<?php  $i++; endforeach;?>
<?php  endif; ?>
	</tbody>
</table>
<?php  echo $pager?>
</div>
<!-- [Content] end -->
