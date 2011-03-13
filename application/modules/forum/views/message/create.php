<h1><?php  echo $title ?></h1>
<?php  if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>
<form class="edit" id="message_create" method="post" action="<?php  echo site_url('forum/message/save') ?>">
<input type='hidden' name='pid' value="<?php  echo $message['pid'] ?>" />
<?php  if($this->user->level['forum'] >= LEVEL_EDIT && $message['mid']): ?>
<input type='hidden' name='mid' value="<?php  echo $message['mid'] ?>" />
<?php  endif; ?>
<label for="username"><?php  echo __("Username:", $module) ?></label>
<?php  
echo $this->user->username ;
?>
<br />
<label for="title"><?php  echo __("Title:", $module) ?></label>
<input type="text" name="title" value="<?php  echo $message['title'] ?>" class="input-text" /> <br />

<label for="topic"><?php  echo __("Topic:", $module) ?></label>
<?php  if($topic && $topic['tid']): ?>
<?php  
echo $topic['title'] ;
?>
<input type='hidden' name='tid' value="<?php  echo $topic['tid'] ?>"/>
<?php  else: ?>
<select name="tid" id="topic" class="select">
<?php  foreach($topics as $t): ?>
<option value="<?php  echo $t['tid'] ?>"><?php  echo $t['title'] ?></option>
<?php  endforeach; ?>
</select>
<?php  endif; ?>

<label for="message"><?php  echo __("Message:", $module) ?></label>
<div class="btn bold" title="bold"></div><div class="btn italic"></div><div class="btn underline"></div><div class="btn link"></div><div class="btn quote"></div>
<div class="btn code"></div><div class="btn image"></div><div class="btn usize"></div><div class="btn dsize"></div><div class="btn nlist"></div>
<div class="btn blist"></div><div class="btn litem"></div><div class="btn back"></div><div class="btn forward"></div>
<textarea name="message" class="bbcode input-textarea" rows="10" cols="68" style="height: 200px"><?php  echo $message['message'] ?></textarea><br />

<label for="notify"><?php  echo __("Notify me when replied:", $module) ?>
<input type="checkbox" name="notify" value="Y" /> </label><br />

<input type="submit" name="submit" value="<?php  echo __("Save", $module)?>" class="input-submit" />
<a href="<?php  echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php  echo __("Cancel", $module)?></a>

</form>
