<h1><?php echo $title ?></h1>
<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>
<form class="edit" id="message_create" method="post" action="<?php echo site_url('forum/message/move/' . $message['mid']) ?>">
<input type='hidden' name='mid' value="<?php echo $message['mid'] ?>" />
<label for="title"><?php echo __("Title:", $module) ?></label>
<?php echo $message['title'] ?>
<br />
<label for="topic"><?php echo __("Topic:", $module) ?> </label>
	<select name="tid" id="topic" class="select">
	<?php foreach($topics as $t): ?>
	<option value="<?php echo $t['tid'] ?>" <?php if($message['tid'] == $t['tid']) echo " selected='selected' "; ?>><?php echo $t['title'] ?></option>
	<?php endforeach; ?>
	</select>

<input type="submit" name="submit" value="<?php echo __("Save", $module)?>" class="input-submit" />
<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $module)?></a>

</form>
