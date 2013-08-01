<a name="top"> </a>
<h1><?php echo $title;?> <a href='<?php echo site_url('forum/rss/message/' . $messages['0']['mid']) ?>' target='_blank'><img src="<?php echo site_url('application/modules/forum/images/feed-icon-14x14.png') ?>" width="14" height="14" border="0"></a></h1>

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<div class="meta">
<strong><?php echo __("Topic:", $module) ?> </strong> <?php echo anchor('forum/topic/' . $topic['tid'], strip_tags($topic['title'])) ?><br />
<strong><?php echo __("Thread:", $module) ?> </strong> <?php echo strip_tags($messages['0']['title']) ?><br />
<strong><?php echo __("Replies:", $module) ?> </strong> <?php echo strip_tags($messages['0']['replies']) ?>
</div>

<?php if($pager): ?>
<div class="pager">
<?php echo __("Pages:", $module) ?> <?php echo $pager ?>
</div>
<?php endif; ?>

<?php $i = 0; foreach ($messages as $row): $i++;?>
<table width="100%" class="forum-message">
<tbody>
<tr>
<td valign="top" class="message-header">
<a name="<?php echo $row['mid'] ?>"> </a><b><?php echo $row['username'] ?></b> - <?php echo date("d/m/Y H:i", $row['date']) ?>

<div style="float: right">
<?php if($this->user->level['forum'] >= LEVEL_EDIT ):?>
<?php echo anchor('forum/message/edit/' . $row['mid'], __("Edit", $module)) ?>  | 
<?php endif; ?>
<?php if($row['pid'] == '' && ($this->user->level['forum'] >= LEVEL_EDIT || (isset($this->user->forum_level[ $topic['tid'] ]) && $this->user->forum_level[ $topic['tid'] ] >= LEVEL_EDIT))): ?>
<?php echo anchor('forum/message/move/' . $row['mid'], __("Move", $module)) ?>  | 
<?php endif; ?>

<?php if($this->user->level['forum'] >= LEVEL_DEL || (isset($this->user->forum_level[ $topic['tid'] ]) && $this->user->forum_level[ $topic['tid'] ] >= LEVEL_DEL) || ($row['username'] == $this->user->username && $i == count($messages))): ?>
<?php echo anchor('forum/message/delete/' . $row['mid'], __("Delete", $module)) ?>
<?php endif; ?>
</div>

</td>
</tr><tr>
<td valign="top" class="message-body"> 
<?php $this->plugin->do_action('forum_avatar_image', array('username' => $row['username'], 'email' => $row['email'])); ?>

<?php echo $this->bbcode->parse(nl2br(strip_tags($row['message']))) ?>
</td>
</tr>
</tbody>
</table>

<?php echo anchor('forum/message/reply/'. $pid . '/'. $row['mid'], __("Quote", $module)) ?> | 
<a href='#top'><?php echo __("Back to top", $module) ?></a>

<?php endforeach; ?> 
<?php if($pager): ?>
<div class="pager">
<?php echo __("Pages:", $module) ?> <?php echo $pager ?>
</div>
<?php endif; ?>
<?php if($this->user->logged_in) : ?>
<form class="edit" id="message-reply" method="post" action="<?php echo site_url('forum/message/save') ?>">
<input type='hidden' name='pid' value="<?php echo $pid ?>" />
<input type='hidden' name='tid' value="<?php echo $topic['tid'] ?>" />
<label for="message"><?php echo __("Reply:", $module) ?></label>
<?php echo $this->bbcode->buttons ?>
<textarea name="message" class="bbcode input-textarea" rows="10" cols="68" style="height: 200px"></textarea><br />
<?php $this->plugin->do_action('forum_post_form'); ?>
<label for="notify"><?php echo __("Notify me when replied:", $module) ?>
<input type="checkbox" name="notify" value="Y" checked /> </label><br />

<input type="submit" name="submit" value="<?php echo __("Save", $module)?>" class="input-submit" />
<input type="submit" name="preview" value="<?php echo __("Preview", $module)?>" class="input-submit" />
<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $module)?></a>

</form>

<?php else: ?>
<div class="forum_reply_link">
<?php echo anchor('forum/message/reply/' . $pid , __("Reply to the thread", $module)) ?>
</div>
<?php endif; ?>
