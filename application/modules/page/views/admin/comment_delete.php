<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?php echo __("Delete a comment", $module) ?></h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<p style="margin-bottom: 2em;"><?php echo __("You are about to delete this comment:", $module) ?>
<blockquote><?php echo nl2br(strip_tags($row['body']))?></blockquote></p>

<form class="delete" action="<?php echo site_url('admin/page/comments/delete/'.$row['id'])?>" method="post">
	<input type="hidden" name="id" value="<?php echo $row['id']?>" />
	<p>
		<input type="button" name="noway" value="<?php echo __("No", $module) ?>" onclick="parent.location='<?php echo site_url('admin/page/comments')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php echo __("Yes", $module) ?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->