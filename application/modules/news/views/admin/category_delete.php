<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?php  echo __("Delete category", $this->template['module'])?></h1>

<hr />


<p style="margin-bottom: 2em;"><?php  echo __("Do you want to delete the category?", $this->template['module'])?></span></p>

<form class="delete" action="<?php  echo site_url('admin/news/category/delete/'.$id .'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?php  echo __("No", $this->template['module'])?>" onclick="parent.location='<?php  echo site_url('admin/news/category')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php  echo __("Yes", $this->template['module'])?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->