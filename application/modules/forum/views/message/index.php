<h1><?php  echo $title;?></h1>

<?php  if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>

<div class="meta">
<strong><?php  echo __("Topic:", $module) ?> </strong> <?php  echo anchor('forum/topic/' . $topic['tid'], strip_tags($topic['title'])) ?><br />
<strong><?php  echo __("Thread:", $module) ?> </strong> <?php  echo strip_tags($messages['0']['title']) ?><br />
<strong><?php  echo __("Replies:", $module) ?> </strong> <?php  echo strip_tags($messages['0']['replies']) ?>
</div>

<?php  $i = 0; foreach ($messages as $row): $i++;?>
<table width="100%" class="forum-message">
<tbody>
<td valign="top" class="message-header">
<a name="<?php  echo $row['mid'] ?>"> </a><?php  echo $row['username'] ?> - <?php  echo date("d/m/Y H:i", $row['date']) ?>

<?php  if($this->user->forum_level[ $topic['tid'] ] >= 0): ?>
<div style="float: right">
<?php  if($this->user->forum_level[ $topic['tid'] ] >= 0 || ($row['username'] == $this->user->username && $i == count($messages))): ?>
<?php  echo anchor('forum/message/edit/' . $row['mid'], __("Edit", $module)) ?> | 
<?php  endif; ?>
<?php  echo anchor('forum/message/delete/' . $row['mid'], __("Delete", $module)) ?>
</div>
<?php  endif; ?>

</td>
</tr><tr>
<td valign="top" class="message-body">
<?php  echo $this->bbcode->parse(nl2br(strip_tags($row['message']))) ?>
</td>
</tr>
</tbody>
</table>

<?php  echo anchor('forum/message/reply/'. $messages['0']['mid'] . '/'. $row['mid'], __("Quote", $module)) ?> | 
<a href='#top'><?php  echo __("Back to top", $module) ?></a>

<?php  endforeach; ?> 

<?php  if($this->user->logged_in) : ?>
<form class="edit" id="message-reply" method="post" action="<?php  echo site_url('forum/message/save') ?>">
<input type='hidden' name='pid' value="<?php  echo $messages['0']['mid'] ?>" />
<input type='hidden' name='tid' value="<?php  echo $topic['tid'] ?>" />
<label for="message"><?php  echo __("Reply:", $module) ?></label>
<div class="btn bold" title="bold"></div><div class="btn italic"></div><div class="btn underline"></div><div class="btn link"></div><div class="btn quote"></div>
<div class="btn code"></div><div class="btn image"></div><div class="btn usize"></div><div class="btn dsize"></div><div class="btn nlist"></div>
<div class="btn blist"></div><div class="btn litem"></div><div class="btn back"></div><div class="btn forward"></div>
<textarea name="message" class="bbcode input-textarea" rows="10" cols="68" style="height: 200px"></textarea><br />
<label for="notify"><?php  echo __("Notify me when replied:", $module) ?>
<input type="checkbox" name="notify" value="Y" /> </label><br />

<input type="submit" name="submit" value="<?php  echo __("Save", $module)?>" class="input-submit" />
<a href="<?php  echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php  echo __("Cancel", $module)?></a>

</form>
<?php  else: ?>
<div class="forum_reply_link">
<?php  echo anchor('forum/message/reply/' . $messages['0']['mid'] , __("Reply to the thread", $module)) ?>
</div>
<?php  endif; ?>