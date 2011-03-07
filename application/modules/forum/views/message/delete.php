<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?php echo __("Delete", $module)?></h1>
<?php if(isset($message['children']) && $message['children'] > 0) : ?>
<p style="color: red"><?php echo __("This message was already replied. Do you want to delete it and its replies?", $module) ?></p>
<?php endif; ?>

<p style="margin-bottom: 2em;"><?php echo __("Confirm?", $module)?>

<form class="delete" action="<?php echo site_url('forum/message/delete/'.$message['mid'].'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?php echo __("No", $module)?>" onclick="parent.location='<?php echo site_url('forum/message/') ?>/<?php echo ($message['pid'])?$message['pid']:$message['mid']?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php echo __("Yes", $module)?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->