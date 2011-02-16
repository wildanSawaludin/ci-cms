<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?php  echo __("Quick menu", $module)?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><span><?php  echo __("General settings", $module)?></span></a></li>
		<li><a href="#two"><span><?php  echo __("Comments settings", $module)?></span></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings">Settings</h1>
<form class="settings" action="<?php  echo site_url('admin/news/settings/save')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?php  echo __("Save Settings", $module)?>" class="input-submit" /></li>
			<li><a href="<?php  echo site_url('admin/news')?>" class="input-submit last"><?php  echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php  if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php  echo $notice;?></p>
		<?php  endif;?>
		
		<p><?php  echo __("Change the settings for the news module", $module);?></p>
		
		<div id="one">
			
            <label for="settings[use_alt_header]"><?php  echo __("Use Alternate RSS Header", $module)?></label>
			<select name="settings[use_alt_header]" class="input-select">
			<option value='1' <?php  echo (($settings['use_alt_header']==1)?"selected":"")?>><?php  echo __("Yes", $module)?></option>
			<option value='0' <?php  echo (($settings['use_alt_header']==0)?"selected":"")?>><?php  echo __("No", $module)?></option>
			</select>
			
			
		</div>
		<div id="two">
			<label for="settings[allow_comments]"><?php  echo __("Allow comments", $module)?></label>
			<select name="settings[allow_comments]" class="input-select">
			<option value='1' <?php  echo (($settings['allow_comments']==1)?"selected":"")?>><?php  echo __("Yes", $module)?></option>
			<option value='0' <?php  echo (($settings['allow_comments']==0)?"selected":"")?>><?php  echo __("No", $module)?></option>
			</select>
			<br />

			<label for="settings[approve_comments]"><?php  echo __("Approve comments", $module)?></label>
			<select name="settings[approve_comments]" class="input-select">
			<option value='1' <?php  echo (($settings['approve_comments']==1)?"selected":"")?>><?php  echo __("Yes", $module)?></option>
			<option value='0' <?php  echo (($settings['approve_comments']==0)?"selected":"")?>><?php  echo __("No", $module)?></option>
			</select>
			<br />
			
			<label for="settings[notify_admin]"><?php  echo __("Nodify admin", $module)?></label>
			<select name="settings[notify_admin]" class="input-select">
			<option value='1' <?php  echo (($settings['notify_admin']==1)?"selected":"")?>><?php  echo __("Yes", $module)?></option>
			<option value='0' <?php  echo (($settings['notify_admin']==0)?"selected":"")?>><?php  echo __("No", $module)?></option>
			</select>			
			
		</div>
	</form>

</div>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
<!-- [Content] end -->
