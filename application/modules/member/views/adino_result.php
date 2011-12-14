<h1 ><?php  echo __("Lost password", $module)?></h1>
<p>
<?php  echo $message ?>
</p>
<p>
<a href="<?php  echo site_url( $this->session->userdata("last_uri") ) ?>"><?php  _e("Go back", $module) ?></a>
</p>

