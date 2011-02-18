<!-- [Content] start -->
<div class="content wide">

<h1><?php  echo __("Login", $module)?></h1>

<form class="login" action="<?php  echo site_url('member/login')?>" method="post" accept-charset="utf-8">
		<?php  if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php  echo $notice;?></p>
		<?php  endif;?>
		<p>
		<?php 
		$msg = __("Please enter here your login and password", $module);
		echo $this->plugin->apply_filters('login_pre_form', $msg);
		?>
		</p>
		<input type='hidden' name='redirect' value='<?php  if(isset($redirect)) echo $redirect ?>' />
		
		<label for="username"><?php  echo __("Username", $module)?>:</label>
		<input type='text' name='username' id='username' class="input-text" /><br />
		<label for="password"><?php  echo __("Password", $module)?>:</label>
		<input type="password" name="password" value="" id="password" class="input-text" />
		<br class="clearfloat" />
		<p><input type="submit" name="submit" value="Login &raquo;" id="submit" class="input-submit" /></p>
	<?php 

		if (isset($this->system->login_signup_enabled) && $this->system->login_signup_enabled == 1)
		{
			echo "<p><a href='" . site_url('member/signup') . "'>" . __("Sign up", $module) . "</a></p>";
		}
		echo "<p><a href='" . site_url('member/adino') . "'>" . __("Lost password ?", $module) . "</a></p>";
	?>
</form>

</div>
<!-- [Content] end -->
