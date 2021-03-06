
<h1><?php echo $page['title']?></h1>

<?php $this->plugin->do_action('page_pre_content', $page['id']); ?>


<?php if ($this->user->level['page'] >= LEVEL_ADD) : ?>
<div class="admin-box">
<?php echo anchor('admin/page/create/' . $page['id'], __("Add subpage", "page")) ?>
<?php if ($this->user->level['page'] >= LEVEL_EDIT) : ?>
 | <?php echo anchor('admin/page/edit/' . $page['id'], __("Edit", "page")) ?>
<?php endif; ?>
<?php if ($this->user->level['page'] >= LEVEL_DEL) : ?>
 | <?php echo anchor('admin/page/delete/' . $page['id'], __("Delete", "page")) ?>
<?php endif; ?>
</div>
<?php endif; ?>
<?php
	if($page_break_pos = strpos($page['body'], "<!-- page break -->"))
	{
		$page['body'] = substr($page['body'], $page_break_pos + 19);
	}
?>	
<?php echo $page['body']?>




<?php if(isset($page['options']['show_subpages']) && $page['options']['show_subpages'] == 1) :?>
<?php if ( $sub_pages = $this->pages->get_subpages($page['id'])) : ?>
<div class='sub_pages'>
<ul>
<?php foreach($sub_pages as $sub_page) : ?>
<li><a href="<?php echo site_url($sub_page['uri'])?>"><?php echo $sub_page['title']?></a></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if(isset($page['options']['show_navigation']) && $page['options']['show_navigation'] == 1) :?>
<?php $this->pages->get_nextpage($page) ?>

<div class='pagenav'>
<?php if ( isset($page['previous_page'])) : ?>
<div class='previous_page'>
<a href="<?php echo site_url($page['previous_page']['uri'])?>"><span>&lt;</span> <?php echo $page['previous_page']['title']?></a>
</div>
<?php endif; ?>
<?php if ( isset($page['next_page'])) : ?>
<div class='next_page'>
<a href="<?php echo site_url($page['next_page']['uri'])?>"><?php echo $page['next_page']['title']?> <span>&gt;</span></a>
</div>
<?php endif; ?>
</div>
<?php endif; ?>


<?php $this->plugin->do_action('page_post_content', $page['id']); ?>
<?php
//general page setting
if (isset($this->system->page_approve_comments) && $this->system->page_approve_comments==1): ?>

<?php if (!empty($comments)): ?>
<div id="comments">

	<h2><?php echo __("Comments:", $module) ?></h2>
<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<div class="notice"><?php echo $notice;?></div>
<?php endif;?>

	<?php $i = 1; foreach ($comments as $comment): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif; ?>
	<div class="<?php echo $rowClass?>">
	<h4><?php if (!empty($comment['website'])):?><a href="<?php echo $comment['website']?>"><?php endif;?><?php echo $i . ". " . $comment['author']?><?php if (!empty($comment['website'])):?></a><?php endif;?></h4>
	<?php if(isset($this->user->level['page']) && $this->user->level['page'] >= LEVEL_DEL): ?>
	<div class="adminbox">
	<?php echo anchor('admin/page/comments/delete/' . $comment['id'], __("Delete", $module)) ?>
	</div>
	<?php endif; ?>
	<p><?php echo nl2br(strip_tags($comment['body'], "<b><i><img>")) ?><br /><i>(<?php echo date("d/m/Y H:i:s", $comment['date']) ?>)</i></p>
	</div>
	<?php $i++; endforeach; ?>
	</div>
<?php endif; ?>

<?php if(isset($page['options']['allow_comments']) && $page['options']['allow_comments'] == 1) :?>
<div id='comment_form' class='clear'>
<h2><?php echo __("Add a comment", $module)?></h2>
<form action="<?php echo site_url('page/comment')?>" method='post'>
<input type='hidden' name='id' value='<?php echo $page['id']?>' />
<input  class="input-text" type='hidden' name='uri' value='<?php echo $page['uri']?>' />
<label for='author'><?php echo __("Name:", $module)?>[*]</label>
<?php if($this->user->logged_in): ?>
<?php echo $this->user->username; ?> <br />
<?php else: ?>
<input  class="input-text" type='text' name='author' value='' id='name' /><br />

<label for='email'><?php echo __("Email:", $module)?>[*]</label>
<input  class="input-text" type='text' name='email' value='' id='email' /><br />

<label for='website'><?php echo __("Website:", $module)?></label>
<input type='text' name='website' value='' id='website' /><br />
<?php endif; ?>
<label for='body'><?php echo __("Comment", $module)?>[*]</label>
<textarea  class="input-textarea" name='body' id='body' rows="10" /></textarea><br />
<?php if(!$this->user->logged_in) : ?>
<label><?php echo __("Security code:", $module)?></label><?php echo $captcha?><br />
<label for="captcha"><?php echo __("Confirm security code:", $module)?></label>
<input class="input-text" type='text' name='captcha' value='' /><br />
<?php endif; ?>
[*] <?php echo __("Required", $module)?><br />
<input type='submit' name='submit' class="input-submit" value="<?php echo __("Add comment", $module)?>" /><br />
</form>
</div>
<?php endif; ?>

<?php endif; //general page module setting ?>