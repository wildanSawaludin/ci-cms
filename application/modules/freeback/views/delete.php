<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(){
		if (confirm("<?php  echo addslashes(__("Confirm delete?", $this->template['module']))?>", $this->template['module']))
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

<h1 id="delete"><?php  echo __("Delete", $this->template['module'])?></h1>

<hr />

<p style="margin-bottom: 2em;"><?php  echo __("Confirm?", $this->template['module'])?>

<form class="delete" action="<?php  echo site_url('admin/freeback/delete/'.$mailto[0]['id'])?>" method="post">
	<p>
		<input type="button" name="noway" value="<?php  echo __("No", $this->template['module'])?>" onclick="parent.location='<?php  echo site_url('admin/freeback')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php  echo __("Yes", $this->template['module'])?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->