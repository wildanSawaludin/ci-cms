<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"> </h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Download settings", "downloads") ?></a></li>

	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?php echo __("Settings", $module)?></h1>

<form class="settings" action="<?php echo site_url('admin/downloads/settings')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save Settings", "downloads") ?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin')?>" class="input-submit last"><?php echo __("Cancel", "downloads") ?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<p><?php echo __("Use this page to change the settings for the download module.", $module)?></p>
		
		<div id="one">
			<table>
			<tbody>
			<tr>
			<th valign="top"><?php _e("Allowed filetypes:", "downloads") ?></th>
			<td valign="top"><input type="text" name="allowed_file_types" value="<?php echo $this->downloads->settings['allowed_file_types'] ?>" id="allowed_file_types" class="input-text" /></td>
			</tr>
			<tr>
			<tr>
			<th valign="top"><?php _e("Upload path:", "downloads") ?></th>
			<td valign="top"><input type="text" name="upload_path" value="<?php echo $this->downloads->settings['upload_path'] ?>" id="upload_path" class="input-text" /></td>
			</tr>
			<tr>
			<tr>
			<th valign="top"><?php _e("Maximum filesize:", "downloads") ?></th>
			<td valign="top"><input type="text" name="max_size" value="<?php echo $this->downloads->settings['max_size'] ?>" id="max_size" class="input-text" /></td>
			</tr>

			</tbody>
			</table>
</div>
	</form>
</div>
<!-- [Content] end -->
