<h1><?=__("Page Not Found!", $module)?></h1>

<p><?=__("The page you're looking for couldn't be found!", $module)?></p>

<?php if($this->user->level['page'] >= LEVEL_ADD) : ?>
<p><?php echo anchor('admin/page/create/0/' . $this->uri->uri_string(), __("You can create it here", $module) ) ?></p>
<?php endif; ?>