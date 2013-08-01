
<h1><?php  echo $news['title']?></h1>
<?php  if ($this->user->level['news'] >= LEVEL_EDIT) : ?>
<div class="adminbox">
<?php  echo anchor('admin/news/create/' . $news['id'], __("Edit", "news")) ?>
<?php  if ($this->user->level['news'] >= LEVEL_DEL) : ?>
 | <?php  echo anchor('admin/news/delete/' . $news['id'], __("Delete", "news")) ?>

<?php  endif; ?>
</div>
<?php  endif; ?>
<?php  
	$pre_content = "<div class='meta'><div class='author'>" . __("Submitted by:", "news") . " " . $news['author'] . "</div>";
	$pre_content .= "<div class='date'>" . __("On:", "news") . " " . date("d/m/Y", $news['date']) . "</div>";
	$pre_content .= "<div class='category'>" . __("In:", "news") . " " . anchor('news/cat/' . $news['cat_uri'], (($news['category'])?$news['category']:__("No category", "news"))) . "</div></div>";
	echo $this->plugin->apply_filters("news_pre_content", $pre_content);
?>
<?php 
	if($page_break_pos = strpos($news['body'], "<!-- page break -->"))
	{
		$news['body'] = substr($news['body'], $page_break_pos + 19);
	}
?>	
<?php  echo $this->plugin->apply_filters("news_content", $news['body']) ?>


<?php $this->plugin->do_action('news_post_content', $news['id']); ?>
<div class="back">
<a href="javascript:history.back()"><?php  echo __("Go back", $module) ?></a>
</div>
<?php  if (!empty($comments)): ?>
<div id="comments">

	<h2><?php  echo __("Comments:", "news") ?></h2>
<?php  if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<div class="notice"><?php  echo $notice;?></div>
<?php  endif;?>

	<?php  $i = 1; foreach ($comments as $comment): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif; ?>
	<div class="<?php  echo $rowClass?>">
	<h4><?php  if (!empty($comment['website'])):?><a href="<?php  echo $comment['website']?>"><?php  endif;?><?php  echo $i . ". " . $comment['author']?><?php  if (!empty($comment['website'])):?></a><?php  endif;?></h4>
	<p><?php  echo nl2br(strip_tags($comment['body'], "<b><i><img>")) ?></p>
	</div>
	<?php  $i++; endforeach; ?>
	</div>
<?php  endif; ?>
<?if ((isset($settings['allow_comments']) && $settings['allow_comments'] == 1) && $news['allow_comments']) :?>
<div id='comment_form' class='clear'>
<h2><?php  echo __("Add a comment", $module)?></h2>
<form action="<?php  echo site_url('news/comment')?>" method='post'>
<input type='hidden' name='news_id' value='<?php  echo $news['id']?>' />
<input  class="input-text" type='hidden' name='uri' value='<?php  echo $news['uri']?>' />
<label for='author'><?php  echo __("Name:", $module)?>[*]</label>
<?php  if ($this->user->logged_in) : ?>
<input type='hidden' name='author' value="<?php  echo $this->user->username ?>" /> <?php  echo $this->user->username ?><br />
<?php  else: ?>
<input  class="input-text" type='text' name='author' value='' id='name' /><br />
<?php  endif; ?>
<label for='email'><?php  echo __("Email:", $module)?>[*]</label>
<?php  if ($this->user->logged_in) : ?>
<input type='hidden' name='email' value="<?php  echo $this->user->email ?>" /> <?php  echo $this->user->email ?><br />
<?php  else: ?>

<input  class="input-text" type='text' name='email' value='' id='email' /><br />
<?php  endif; ?>
<label for='body'><?php  echo __("Comment", $module)?>[*]</label>
<textarea  class="input-textarea" name='body' id='body' rows="10" /></textarea><br />
<?php  
$tmp = '';
$tmp = $this->plugin->apply_filters('news_comment_form', $tmp);
echo $tmp;
?>
[*] <?php  echo __("Required", $module)?><br />
<input type='submit' name='submit' class="input-submit" value="<?php  echo __("Add comment", $module)?>" /><br />
</form>
</div>
<?php endif ;?>