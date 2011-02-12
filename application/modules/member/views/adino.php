<h1 id="settings"><?php  echo __("Lost password", $this->template['module'])?></h1>


		<?php  echo validation_errors(); ?>

<form class="settings" action="<?php  echo site_url('member/adino')?>" method="post" accept-charset="utf-8">

		<?php  if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php  echo $notice;?></p>
		<?php  endif;?>

		
			<?php  echo __("To create a new password, please enter your email.", $this->template['module']);
			?>
			<br />
			<label for="email"><?php  echo __("Email", $this->template['module'])?>:</label>
			<input type="text" name="email" value="" id="email" class="input-text" /><br />

			<input type="submit" name="submit" value="<?php  echo __("Submit", $this->template['module'])?>" class="input-submit" />
			<a href="<?php  echo site_url()?>" class="input-submit"><?php  echo __("Cancel", $this->template['module'])?></a>

</form>

<!-- [Content] end -->
