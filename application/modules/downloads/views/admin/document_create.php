
<div class="leftmenu">

	<h1 id="pageinfo"><?php  echo __("Information", 'downloads')?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php  echo __("Details", 'downloads')?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?php  echo __("Add new document", 'downloads')?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php  echo site_url('admin/downloads/document/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?php  echo $row['id']?>" />
		<input type="hidden" name="lang" value="<?php  echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php  echo __("Save", 'downloads')?>" class="input-submit" /></li>
			<li><a href="<?php  echo site_url('admin/downloads/index/'.$cat)?>" class="input-submit last"><?php  echo __("Cancel", 'downloads')?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php  if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php  echo $notice;?></p>
		<?php  endif;?>
		
		<div id="one">
		
		<label for="title"><?php  echo __("Name", 'downloads')?>:</label>
		<input type="text" name="title" value="<?php  echo (isset($row['title'])?$row['title'] : "") ?>" id="title" class="input-text" /><br /><br />
		<strong><?php  echo __("File", 'downloads')?>:</strong><br />
		<label for="file_id"><?php  echo __("Downloaded", 'downloads')?>:</label>
		<select name='file_id' id='file_id' class="input-select">
		<option value=''></option>
		<?php  if($files): ?>
		<?php  foreach($files as $file) : ?>
			<option value="<?php  echo $file['id']?>" <?php  echo (isset($row['file_id']) && $row['file_id'] == $file['id'])?"selected":""?>> <?php  echo $file['file'] ?> </option>
		<?php  endforeach; ?>
		<?php endif;?> 
		</select><br />

		<label for="file_link"><?php  echo __("or Link", 'downloads')?>:</label>
		<input type="text" name="file_link" value="<?php  echo (isset($row['file_link'])?$row['file_link'] : "") ?>" id="file_link" class="input-text" /><br />
		
		
		<label for="cat"><?php  echo __("Category", 'downloads')?>:</label>
		<select name='cat' id='cat' class="input-select">
		<option value='0'></option>
		<?php  if($parents): ?>
		<?php  $follow = null; foreach($parents as $parent) : ?>
			<option value="<?php  echo $parent['id']?>" <?php  echo ($row['cat'] == $parent['id'] || $parent['id'] == $cat)?"selected":""?>> &nbsp;<?php  echo ($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?php  echo $parent['title'] ?> </option>
		<?php  endforeach; ?>
		<?php endif;?> 
		</select><br />

		<label for="status"><?php  echo __("Status", 'downloads')?>:</label>
		<select name='status' id='status' class="input-select">
		<option value="1" <?php  echo (($row['status']==1)?"selected":"")?>><?php  echo __("Active", 'downloads')?></option>
		<option value="0" <?php  echo (($row['status']==0)?"selected":"")?>><?php  echo __("Suspended", 'downloads')?></option>
		</select><br />
		
		<label for="keywords"><?php  echo __("Keywords", 'downloads')?>:</label>
		<input type="text" name="keywords" value="<?php  echo (isset($row['keywords'])?$row['keywords'] : "") ?>" id="keywords" class="input-text" /><br />

		<label for="acces"><?php _e("Group access:", $module)?></label><br />
		<select name="acces" id="acces" class="select">
		<?php foreach ($this->user->get_group_list() as $group): ?>
			<option value="<?php echo $group['g_id'] ?>" <?php if ($group['g_id'] == $row['acces']) echo "selected" ?> ><?php echo __($group['g_name'], $module) ?></option>
		<?php endforeach; ?> 
		</select>
		<br />
					
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