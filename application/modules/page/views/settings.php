<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Page informations</h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one">General settings</a></li>
		<!-- <li><a href="#two">Theme settings</a></li> -->
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?php  echo __("Settings", $module)?></h1>

<form class="settings" action="<?php  echo site_url('admin/page/settings')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="Save Settings" class="input-submit" /></li>
			<li><a href="<?php  echo site_url('admin/page')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php  if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php  echo $notice;?></p>
		<?php  endif;?>
		
		<p><?php  echo __("Use this page to change the settings for the page module.", $module)?></p>
		
		<div id="one">
		
			<label for="site_name"><?php  _e("Homepage:", "page") ?></label>
			<input type="text" name="page_home" value="<?php  echo isset($this->system->page_home)?$this->system->page_home:"home"?>" id="page_home" class="input-text" /><br />
		
<label for="page_approve_comments"><?php  echo __("Approve comments", $module)?></label>
			<select name="page_approve_comments" class="input-select">
			<option value='1' <?php  echo ((isset($this->system->page_approve_comments) && $this->system->page_approve_comments==1)?"selected":"")?>><?php  echo __("Yes", $module)?></option>
			<option value='0' <?php  echo ((isset($this->system->page_approve_comments) && $this->system->page_approve_comments==0)?"selected":"")?>><?php  echo __("No", $module)?></option>
			</select>
			<br />

<label for="page_notify_admin"><?php  echo __("Notify admin for comments", $module)?></label>
			<select name="page_notify_admin" class="input-select">
			<option value='1' <?php  echo ((isset($this->system->page_notify_admin) && $this->system->page_notify_admin==1)?"selected":"")?>><?php  echo __("Yes", $module)?></option>
			<option value='0' <?php  echo ((isset($this->system->page_notify_admin) && $this->system->page_notify_admin==0)?"selected":"")?>><?php  echo __("No", $module)?></option>
			</select>
			<br />

<label for="page_notify_admin"><?php  echo __("Publish feed for page", $module)?></label>
			<select name="page_publish_feed" class="input-select">
			<option value='1' <?php  echo ((isset($this->system->page_publish_feed) && $this->system->page_publish_feed==1)?"selected":"")?>><?php  echo __("Yes", $module)?></option>
			<option value='0' <?php  echo ((isset($this->system->page_publish_feed) && $this->system->page_publish_feed==0)?"selected":"")?>><?php  echo __("No", $module)?></option>
			</select>
			<br class="clear" /><?php  echo __("Whether to publish also page content with feed", $module) ?>
			<br />
</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
</div>
<!-- [Content] end -->
