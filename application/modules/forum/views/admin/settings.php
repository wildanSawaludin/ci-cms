<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?php echo __("Quick menu", $module)?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><span><?php echo __("General settings", $module)?></span></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?php echo $title ?></h1>
<form class="settings" action="<?php echo site_url('admin/forum/settings/save')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save Settings", $module)?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/news')?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
				
		<div id="one">
		
			
			<label for="settings[style]"><?php echo __("Theme", $module)?></label>
			<select name="settings[style]" class="input-select">
			<option value='none'><?php echo __("None", $module)?></option>
			<?php foreach($forum_themes as $theme): ?>
			<option value='<?php echo $theme ?>' <?php echo (($settings['style']==$theme)?"selected":"")?>><?php echo ucfirst($theme)?></option>
			<?php endforeach; ?>
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
