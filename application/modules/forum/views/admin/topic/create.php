<script type="text/javascript">
<!--
$(document).ready(function(){
	$("#add_admin_button").click(function()
	{
		var admin = $("#new_admin").val();
		$("#admin-list tbody").append("<tr id='" + admin +"'><td><input type='hidden' name='admins[]' value='"+admin+"' />"+admin +"</td><td><?php  echo addslashes(anchor('admin/forum/topic/add#', __("Delete", $module), array('class' => 'remove-admin'))) ?></td></tr>");
		$("#new_admin").val("");
		$("a.remove-admin").click(function(){
			$(this).parent().parent().remove();
			return false;
		});
		
	});
	$("a.remove-admin").click(function(){
		$(this).parent().parent().remove();
		return false;
	});
	$("#topic_create").submit(function(){
		if($("#title").val() == "") 
		{
			alert("<?php  echo addslashes(__("Title cannot be empty", $module)) ?>");
			return false;
		}
		
	});
});
//-->
</script>
<div class="leftmenu">

	<h1 id="pageinfo"><?php  echo __("Information", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php  echo __("Details", $this->template['module'])?></a></li>
		<li><a href="#two"><?php  echo __("Administrators", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<form class="edit" id="topic_create" method="post" action="<?php  echo site_url('admin/forum/topic/save') ?>">
<h1 id="edit"><?php  echo $title ?></h1>
		<ul>
			<li><input type="submit" name="submit" value="<?php  echo __("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?php  echo site_url('admin/forum/topics')?>" class="input-submit last"><?php  echo __("Cancel", $this->template['module'])?></a></li>
		</ul>

<br class="clearfloat" />
<hr />

<?php  if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>
<div id="one">
<input type='hidden' name='tid' value="<?php  echo $topic['tid'] ?>" />
<label for="title"><?php  echo __("Title:", $module) ?></label>
<input type="text" id="title" name="title" value="<?php  echo $topic['title'] ?>" class="input-text" /> <br />
<label for="description"><?php  echo __("Description:", $module) ?></label>
<textarea name="description" id="description" class="input-text"><?php  echo $topic['description'] ?></textarea><br />

<label for="gid"><?php  echo __("Access:", $module)?> </label>
<select name="gid" id="groups" class="input-select">
<?php  foreach ($this->user->get_group_list() as $group): ?>
	<option value="<?php  echo $group['g_id'] ?>" <?php  if (isset($topic['gid']) && ($group['g_id'] == $topic['gid'])) echo "selected" ?> ><?php  echo __($group['g_name'], $module) ?></option>
<?php  endforeach; ?> 
</select>
<br class="clearfloat"/>
</div>
<div id="two">
<p><?php  echo __("Users who can edit and delete messages in this topic.", $module) ?></p>
<p>
<?php  echo __("New admin:", $module) ?> <input type="text" id="new_admin" name="new_admin" /> <input type="button" value="<?php  echo __("Add", $module) ?>" id="add_admin_button" />
</p>
<table id="admin-list" class="page-list">
<thead>
<tr><th width="60%"><?php  echo __("Username", $module) ?></th><th width="40%"><?php  echo __("Action", $module) ?></th></tr>
</thead>
<tbody>
<?php  if(isset($topic['admins'])): ?>
<?php  foreach($topic['admins'] as $admin): ?>
<tr id="<?php  echo $admin?>"><td><input type="hidden" name="admins[]" value="<?php  echo $admin['username'] ?>" /><?php  echo $admin['username'] ?></td><td>
<?php  if($this->user->level['forum'] >= LEVEL_DEL && $admin['username'] != $this->user->username) : ?>
<?php  echo anchor('admin/forum/topic/add#', __("Delete", $module), array('class' => 'remove-admin')) ?>
<?php  endif; ?> 
</td></tr>
<?php  endforeach; ?>
<?php  else: ?>
<tr><td><input type="hidden" name="admins[]" value="<?php  echo $this->user->username ?>" /><?php  echo $this->user->username ?></td><td></td></tr>
<?php  endif; ?>
</tbody>
</table>
</div>
</form>
</div>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	