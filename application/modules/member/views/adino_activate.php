<h1 id="settings"><?php  echo __("Lost password", $this->template['module'])?></h1>



<form class="settings" action="" method="post" accept-charset="utf-8">

			<?php echo validation_errors(); ?>
		
			<?php  echo __("Now, create a new password.", $this->template['module']);
			?>
			<br />
			<label for="newpass"><?php  echo __("New password", $this->template['module'])?>:</label>
			<input type="password" name="newpass" value="" id="newpass" class="input-text" /><br />

			<label for="rnewpass"><?php  echo __("Confirm", $this->template['module'])?>:</label>
			<input type="password" name="rnewpass" value="" id="" class="input-text" /><br />			
			<input type="submit" name="submit" value="<?php  echo __("Save", $this->template['module'])?>" class="input-submit" />
			<a href="<?php  echo site_url()?>" class="input-submit"><?php  echo __("Cancel", $this->template['module'])?></a>

</form>

<!-- [Content] end -->
