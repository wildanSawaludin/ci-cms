
<div class="leftmenu">

	<h1 id="pageinfo"><?php  echo __("Category information", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php  echo __("Details", $this->template['module'])?></a></li>
		<li><a href="#two"><?php  echo __("Options", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?php  echo __("Add new category", $this->template['module'])?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php  echo site_url('admin/news/category/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?php  echo $row['id']?>" />
		<input type="hidden" name="lang" value="<?php  echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php  echo __("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?php  echo site_url('admin/news/category')?>" class="input-submit last"><?php  echo __("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php  if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php  echo $notice;?></p>
		<?php  endif;?>
		
		<div id="one">
		
		<label for="title"><?php  echo __("Name", $this->template['module'])?>:</label>
		<input type="text" name="title" value="<?php  echo (isset($row['title'])?$row['title'] : "") ?>" id="title" class="input-text" /><br />

		<label for="title"><?php  echo __("Uri", $this->template['module'])?>:</label>
		<input type="text" name="uri" value="<?php  echo (isset($row['uri'])?$row['uri'] : "") ?>" id="uri" class="input-text" /><br />

		
		<label for="pid"><?php  echo __("Parent", $this->template['module'])?>:</label>
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
			<option value="<?php  echo $parent['id']?>" <?php  echo ($row['pid'] == $parent['id'])?"selected":""?>> &nbsp;<?php  echo ($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?php  echo $parent['title'] ?> </option>
		<?php  endforeach; ?>
		<?phpendif;?> 
		</select><br />

		<label for="status"><?php  echo __("Status", $this->template['module'])?>:</label>
		<select name='status' id='status' class="input-select">
		<option value="1" <?php  echo (($row['status']==1)?"selected":"")?>><?php  echo __("Active", $this->template['module'])?></option>
		<option value="0" <?php  echo (($row['status']==0)?"selected":"")?>><?php  echo __("Suspended", $this->template['module'])?></option>
		</select><br />
		
		<label for="desc"><?php  echo __("Description", $this->template['module'])?>:</label>
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