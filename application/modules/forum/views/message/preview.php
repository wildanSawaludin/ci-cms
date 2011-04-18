<h1><?php echo $title ?></h1>
<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>
<table width="100%" class="forum-message">
<tbody>
<tr>
<td valign="top" class="message-header">
<b><?php echo $this->user->username ?></b> - <?php echo date("d/m/Y H:i") ?>
</td>
</tr><tr>
<td valign="top" class="message-body"> <img src="http://avatar.serasera.org/<?php echo md5($this->user->username)?>.jpg" align="left" hspace="5" width="40" height="40" class="avatar"/>
<?php echo $this->bbcode->parse(nl2br(strip_tags($data['message']))) ?>
</td>
</tr>
</tbody>
</table>


<form class="edit" id="message_create" method="post" action="<?php echo site_url('forum/message/save') ?>">
<input type='hidden' name='pid' value="<?php if(isset($data['pid'])) echo $data['pid'] ?>" />
<input type='hidden' name='mid' value="<?php if(isset($data['mid'])) echo $data['mid'] ?>" />
<label for="username"><?php echo __("Username:", $module) ?></label>
<?php 
echo $this->user->username ;
?>
<br />
<?php if(empty($data['pid'])) : ?>
<label for="title"><?php echo __("Title:", $module) ?></label>
<input type="text" name="title" value="<?php if(isset($data['title'])) echo $data['title'] ?>" class="input-text" /> <br />

<label for="topic"><?php echo __("Topic:", $module) ?> </label>
	<select name="tid" id="topic" class="select">
	<option value=""><?php echo __("Choose from here", $module) ?></option>
	<?php foreach($topics as $t): ?>
	<option value="<?php echo $t['tid'] ?>" <?php if($t['tid'] == $data['tid']) echo " selected='selected' " ?> ><?php echo $t['title'] ?></option>
	<?php endforeach; ?>
	</select>
<?php else: ?>
<input type='hidden' name='tid' value='<?php echo $data['tid'] ?>' />
<?php endif; ?>


<label for="message"><?php echo __("Message:", $module) ?></label>
<?php echo $this->forum->bbcode_buttons ?>
<textarea name="message" class="bbcode input-textarea" rows="10" cols="68" style="height: 200px"><?php echo $data['message'] ?></textarea><br />

<label for="notify"><?php echo __("Notify me when replied:", $module) ?>
<input type="checkbox" name="notify" value="Y" checked /> </label><br />

<input type="submit" name="submit" value="<?php echo __("Save", $module)?>" class="input-submit" />
<input type="submit" name="preview" value="<?php echo __("Preview", $module)?>" class="input-submit" />
<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $module)?></a>

</form>


