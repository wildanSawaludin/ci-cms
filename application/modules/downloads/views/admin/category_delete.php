<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?php  echo __("Delete category", 'downloads')?></h1>

<hr />


<p style="margin-bottom: 2em;"><?php  echo __("Do you want to delete the category?", 'downloads')?></span></p>

<form class="delete" action="<?php  echo site_url('admin/downloads/category/delete/'.$cat . '/' . $id .'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?php  echo __("No", 'downloads')?>" onclick="parent.location='<?php  echo site_url('admin/downloads/index/' . $cat)?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php  echo __("Yes", 'downloads')?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->