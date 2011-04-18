<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(){
		if (confirm("<?php  echo addslashes(__("Confirm delete?", $module))?>", $module))
		{
		window.location = this+'/1';
		return false;
		} else {
		return false;
		}
	});
});
</script>
<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?php  echo __("Delete", $module)?></h1>

<hr />

<p style="margin-bottom: 2em;"><?php  echo __("Confirm?", $module)?>

<form class="delete" action="<?php  echo site_url('admin/freeback/delete/'.$mailto[0]['id'])?>" method="post">
	<p>
		<input type="button" name="noway" value="<?php  echo __("No", $module)?>" onclick="parent.location='<?php  echo site_url('admin/freeback')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php  echo __("Yes", $module)?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->