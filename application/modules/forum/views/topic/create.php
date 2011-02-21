<h1><?php  echo $title ?></h1>
<?php  if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>

<form class="edit" id="topic_create" method="post" action="<?php  echo site_url('forum/topic/save') ?>">
<input type='hidden' name='tid' value="<?php  echo $topic['tid'] ?>" />
<label for="title"><?php  echo __("Title:", $module) ?></label>
<input type="text" name="title" value="<?php  echo $topic['title'] ?>" class="input-text" /> <br />
<label for="description"><?php  echo __("Description:", $module) ?></label>
<textarea name="description" class="input-text"><?php  echo $topic['description'] ?></textarea><br />

<label for="gid"><?php  echo __("Access:", $module)?> </label>
<select name="gid" id="groups" class="select">
<?php  foreach ($this->user->get_group_list() as $group): ?>
	<option value="<?php  echo $group['g_id'] ?>" <?php  if (isset($topic['gid']) && ($group['g_id'] == $topic['gid'])) echo "selected" ?> ><?php  echo __($group['g_name'], $module) ?></option>
<?php  endforeach; ?> 
</select>
<br />
		<input type="submit" name="submit" value="<?php  echo __("Save", $module)?>" class="input-submit" />
		<a href="<?php  echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php  echo __("Cancel", $module)?></a>

</form>
