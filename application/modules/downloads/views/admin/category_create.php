
<div class="leftmenu">

	<h1 id="pageinfo"><?php  echo __("Information", 'downloads')?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php  echo __("Details", 'downloads')?></a></li>
		<li><a href="#two"><?php  echo __("Options", 'downloads')?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?php  echo __("Add new category", 'downloads')?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php  echo site_url('admin/downloads/category/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?php  echo $row['id']?>" />
		<input type="hidden" name="lang" value="<?php  echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php  echo __("Save", 'downloads')?>" class="input-submit" /></li>
			<li><a href="<?php  echo site_url('admin/downloads/index/' . $cat['id'])?>" class="input-submit last"><?php  echo __("Cancel", 'downloads')?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php  if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php  echo $notice;?></p>
		<?php  endif;?>
		
		<div id="one">
		
		<label for="title"><?php  echo __("Name", 'downloads')?>:</label>
		<input type="text" name="title" value="<?php  echo $row['title']?>" id="title" class="input-text" /><br />
	
		<label for="pid"><?php  echo __("Parent", 'downloads')?>:</label>
		<select name='pid' id='pid' class="input-select">
		<option value=''></option>
		<?php  if($parents): ?>
		<?php  $follow = null; foreach($parents as $parent) : ?>
		<?php 
		if ($row['id'] == $parent['id'] || $follow == $parent['pid'] )
		{
			$follow = $row['id']; 
			continue;
		}
		else
		{
			$follow = null;
		}
		?>
			<option value="<?php  echo $parent['id']?>" <?php  echo ($row['pid'] == $parent['id'] || $parent['id'] == $cat['id'])?"selected":""?>> &nbsp;<?php  echo ($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?php  echo $parent['title'] ?> </option>
		<?php  endforeach; ?>
		<?php endif;?> 
		</select><br />

		<label for="status"><?php  echo __("Status", 'downloads')?>:</label>
		<select name='status' id='status' class="input-select">
		<option value="1" <?php  echo (($row['status']==1)?"selected":"")?>><?php  echo __("Active", 'downloads')?></option>
		<option value="0" <?php  echo (($row['status']==0)?"selected":"")?>><?php  echo __("Suspended", 'downloads')?></option>
		</select><br />
		
		<label for="desc"><?php  echo __("Description", 'downloads')?>:</label>
		<textarea name="desc" class="input-textarea"><?php  echo (isset($row['desc'])?$row['desc'] : "") ?></textarea><br />

		</div>
		<div id="two">
		
		
	
			
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	
</div>
<!-- [Content] end -->