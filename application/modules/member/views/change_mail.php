<h1 id="settings"><?php  echo $title ?></h1>



<form class="settings" action="" method="post" accept-charset="utf-8">
		<?php  echo validation_errors(); ?>
		
			<?php  echo __("Please submit your new email.", $module); ?>
			<br /> 
			<?php  echo __("You will receive a confirmation in your new email.", $module);?>
			<br />
			<label for="email"><?php  echo __("New email", $module)?>:</label>
			<input type="text" name="email" value="<?php echo set_value('email');?>" id="email" class="input-text" /><br />

			<label for="remail"><?php  echo __("Confirm", $module)?>:</label>
			<input type="text" name="remail" value="<?php echo set_value('remail');?>" id="remail" class="input-text" /><br />			
			<input type="submit" name="submit" value="<?php  echo __("Save", $module)?>" class="input-submit" />
			<a href="<?php  echo site_url()?>" class="input-submit"><?php  echo __("Cancel", $module)?></a>

</form>

<!-- [Content] end -->
