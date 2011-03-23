<h1><?php echo $title ?></h1>

<form id="search" name="search" method="post" action="<?php echo site_url('forum/message/search') ?>">
<label for="tosearch">
<?php echo __("Search:", $module) ?>
</label>
<input type="text" name="tosearch" id="tosearch" class="input-text"/><br />
<label for="infield">
<?php echo __("in:", $module) ?>
</label>
<select name="infield" id="infield" class="input-select">
<option value=""><?php echo __("Everywhere", $module) ?></option>
<option value="title"><?php echo __("Title", $module) ?></option>
<option value="message"><?php echo __("Message", $module) ?></option>
<option value="username"><?php echo __("Author", $module) ?></option>
</select><br />
<label for="exactsearch">
<?php echo __("Exact search", $module) ?>
</label>
<input type="checkbox" name="exactsearch" id="exactsearch" value="on" /><br /> 
<input type="submit" name="submit" value="<?php echo __("Search", $module)?>" class="input-submit" />
<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $module)?></a>

</form>
