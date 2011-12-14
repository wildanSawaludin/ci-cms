<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?php _e("Page informations", $module) ?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Content", $module)?></a></li>
		<li><a href="#two"><?php echo __("Other fields", $module)?></a></li>
		<li><a href="#three"><?php echo __("Options", $module)?></a></li>
		<?php /*<li><a href="#four"><?php echo __("Group access", $module)?></a></li>*/ ?>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<script type="text/javascript">
function change_parent() {
	selected = document.getElementById('parent').selectedIndex;
	document.getElementById('pre_uri').innerHTML = '/'+document.getElementById('parent').options[selected].value;
}

$(document).ready(function(){
	$("#image")
	.after("<img src='<?php echo site_url('application/views/admin/images/ajax_circle.gif')?>' id='loading'/><input type='button' id='upload_now' value='  <?php echo __('Upload', $module) ?>  ' />");
	$("#loading").hide();
	$("#upload_now").click(function(){
		ajaxFileUpload();
	});
	handleDeleteImage();
});

function handleDeleteImage() {
	$("a.ajaxdelete").click(function(){
		if (confirm("Delete image?"))
		{
		deleteImage(this);
		return false;
		} else {
		return false;
		}
	});
}
function deleteImage(obj) {
	var id = obj.id
	$.post('<?php echo site_url('admin/page/ajax_delete')?>',
		{ id: id},
		function(data){
			alert(data);
		}
	);
	$(obj).parent().parent().hide();
}

function ajaxFileUpload() {
	$("#upload_now")
	.ajaxStart(function(){
		$("img#loading").show();
		$(this).hide();
	})
	.ajaxComplete(function(){
		$("img#loading").hide();
		$(this).show();
	});

	$.ajaxFileUpload
	(
		{
			url:'<?php echo site_url('admin/page/ajax_upload')?>',
			secureuri:false,
			fileElementId: 'image',
			dataType: 'json',
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}else
					{
						$("#image_list tbody").append("<tr><td><input type='hidden' name='image_ids[]' value='"+data.imageid+"' />"+data.image+"</td><td><a href='#' onclick=\"tinyMCE.execCommand('mceInsertContent',false,'<a href=\\'<?php echo site_url('media/images/o') ?>/"+data.image+"\\'><img border=0 align=left hspace=10 src=\\'<?php echo site_url('media/images/s') ?>/"+data.image+"\\'></a>');return false;\"><?php echo __("Small", $module) ?></a> | <a href='#' onclick=\"tinyMCE.execCommand('mceInsertContent',false,'<a href=\\'<?php echo site_url('media/images/o') ?>/"+data.image+"\\'><img border=0 align=left hspace=10 src=\\'<?php echo site_url('media/images/m') ?>/"+data.image+"\\'></a>');return false;\"><?php echo __('Medium', $module) ?></a> | <a href='#' onclick=\"tinyMCE.execCommand('mceInsertContent',false,'<img border=0 align=left hspace=10 src=\\'<?php echo site_url('media/images/o') ?>/"+data.image+"\\'>');return false;\"><?php echo __("Original", $module) ?></a><td><a href='#' class='ajaxdelete' id='"+data.imageid+"'><?php echo __('Delete image', $module) ?></a></td></tr>");
					
						$("#image").val("");
						handleDeleteImage();
						
					}
				}
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)
	return false;
}
</script>

<h1 id="edit"><?php echo ($page['id'])? __("Edit page", $module) : __("Create New Page", $module)?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php echo site_url('admin/page/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="lang" value="<?php echo (isset($page['lang'])? $page['lang'] : $this->user->lang )?>" />
		<input type="hidden" name="id" value="<?php echo $page['id'] ?>" />
		<ul>
			<li><input type="submit" name="submit" value="Save page" class="input-submit" /></li>
			<?php if($page['id']): ?><li><a href="<?php echo site_url('admin/'.$module.'/delete/'.$page['id'])?>" class="input-submit"><?php echo __("Delete page", $module)?></a></li><?php endif; ?>
			<li><a href="<?php echo site_url('admin/page')?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<div id="one">
		<label for="title"><?php echo __("Page Title:", $module)?></label>
		<input type="text" name="title" value="<?php echo $page['title']?>" id="title" class="input-text" /><br />
		
	
		<label for="uri"><?php echo __("SEF address", $module)?>:</label>
			<input type="text" name="uri" value="<?php echo $uri ; ?>" id="uri" class="input-text" /><br />
		
		
		
		
		<label for="parent_id"><?php _e("Parent", $module)?>: </label>
			<select name="parent_id" class="input-select" />
			<option value='0'/>
			<?php $follow = null;
			if($parents):
			foreach ($parents as $parent):?>
			<?php  
					
					if ($page['id'] == $parent['id'] || $follow == $parent['parent_id']) 
					{
						$follow = $page['id']; 
						continue;
					}
					else
					{
					$follow = null;
					}
			?>
			<option value="<?php echo $parent['id']?>" <?php echo ($page['parent_id'] == $parent['id']|| $parent['id'] == $parent_id)?"selected":""?>><?php echo ($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?php echo (strlen($parent['title']) > 50 )? substr($parent['title'], 0, 50) . '...': $parent['title']?> </option>
			
			<?php endforeach;
			endif;?>
			</select>
		<br />
			
			<label for="status"><?php echo __("Status", $module)?></label>
			<select name="status" id="status" class="input-select">
				<option<?php if ($page['active'] == -1): echo ' selected="selected"'; endif;?> value="-1"><?php echo __("Archived", $module)?></option>
				<option<?php if ($page['active'] == 0): echo ' selected="selected"'; endif;?> value="0"><?php echo __("Draft", $module)?></option>
				<option<?php if ($page['active'] == 1): echo ' selected="selected"'; endif;?> value="1"><?php echo __("Published", $module)?></option>
			</select><br />
		
			<label for="body"><?php echo __("Page Content:", $module)?></label>
			<textarea name="body" class="input-textarea"><?php echo $page['body']?></textarea>

		<label for="image_ids"><?php _e("Available images:", $module)?></label>
		
		<table id="image_list" class="page-list">
			<thead>
				<tr>
					<th><?=__("Name", $module)?></th>
					<th><?=__("Click to insert", $module)?></th>					
					<th><?=__("Action", $module)?></th>
				</tr>
			</thead>
			<tbody>

		<?php if ($images) : ?>
		<?php foreach($images as $image): ?>
		<tr>
		<td><input type='hidden' name='image_ids[]' value='<?php echo $image['id'] ?>' /><?php echo $image['file'] ?></td>
		<td><a href='#' onclick="tinyMCE.execCommand('mceInsertContent',false,'<a href=\'<?php echo site_url('media/images/o')?>/<?php echo $image['file'] ?>\'><img border=\'0\' align=\'left\' hspace=\'10\' src=\'<?php echo site_url('media/images/s')?>/<?php echo $image['file'] ?>\' /></a>');return false;"><?php echo __("Small", $module) ?></a> | <a href='#' onclick="tinyMCE.execCommand('mceInsertContent',false,'<a href=\'<?php echo site_url('media/images/o')?>/<?php echo $image['file'] ?>\'><img border=\'0\' align=\'left\' hspace=\'10\' src=\'<?php echo site_url('media/images/m')?>/<?php echo $image['file'] ?>\' /></a>');return false;"><?php echo __("Medium", $module) ?></a> | <a href='#' onclick="tinyMCE.execCommand('mceInsertContent',false,'<img border=\'0\' align=\'left\' hspace=\'10\' src=\'<?php echo site_url('media/images/o')?>/<?php echo $image['file'] ?>\' />');return false;"><?php echo __("Original", $module) ?></a></td>
		<td><a href="<?php echo site_url('admin/news/removeimg/' . $image['id']) ?>" class="ajaxdelete" id="<?php echo $image['id'] ?>"><?php echo __("Delete image", $module) ?></a></td></tr>
		<?php endforeach; ?>
		<?php endif;?>
		</tbody>
		</table>
		
		
		
		
		<label for="image"><?php echo __("Image", $module)?></label>
		<input type="file" name="image" class="input-file" id="image"/><br />
		</div>
		<div id="two">
		
			<label for="meta_keywords"><?php echo __("Page keywords:", $module)?></label>
			<input type="text" name="meta_keywords" value="<?php echo $page['meta_keywords']?>" id="meta_keywords" class="input-text" /><br />
		
			<label for="meta_description"><?php echo __("Page description:", $module)?></label>
			<input type="text" name="meta_description" value="<?php echo $page['meta_description']?>" id="meta_description" class="input-text" /><br />

			<label for="g_id"><?php _e("Page access:", $module)?></label><br />
		<select name="g_id" id="g_id" class="select">
		<?php foreach ($this->user->get_group_list(array('where' => "g_id IN ('" . join("', '", $this->user->groups) . "')")) as $group): ?>
			<option value="<?php echo $group['g_id'] ?>" <?php if ($group['g_id'] == $page['g_id']) echo "selected" ?> ><?php echo __($group['g_name'], $module) ?></option>
		<?php endforeach; ?> 
		</select>
		<br />
			
			<br />
			
			<?php
			$custom_fields = "";
			
			echo $this->plugin->apply_filters("page_custom_fields", $custom_fields);

			?>
		</div>
		<div id="three">
		
			<label for="options[show_subpages]"><?php _e("Show subpages", $module)?>:</label>
			<select name="options[show_subpages]" class="input-select" id="show_subpages">
			<option value='0' <?php echo ((isset($page['options']['show_subpages']) && $page['options']['show_subpages']==0)?"selected":"")?>><?php echo __("No", $module)?></option>
			<option value='1' <?php echo ((isset($page['options']['show_subpages']) && $page['options']['show_subpages']==1)?"selected":"")?>><?php echo __("Yes", $module)?></option>
			</select><br />
			
			<label for="options[show_navigation]"><?php echo __("Show navigation", $module)?>:</label>
			<select name="options[show_navigation]" class="input-select" id="show_navigation">
			<option value='0' <?php echo ((isset($page['options']['show_navigation']) && $page['options']['show_navigation']==0)?"selected":"")?>><?php echo __("No", $module)?></option>
			<option value='1' <?php echo ((isset($page['options']['show_navigation']) && $page['options']['show_navigation']==1)?"selected":"")?>><?php echo __("Yes", $module)?></option>
			</select><br />
			
			
			<label for="options[allow_comments]"><?php echo __("Allow comments", $module)?>:</label>
			<select name="options[allow_comments]" class="input-select" id="allow_comments">
			<option value='0' <?php echo ((isset($page['options']['allow_comments']) && $page['options']['allow_comments']==0)?"selected":"")?>><?php echo __("No", $module)?></option>
			<option value='1' <?php echo ((isset($page['options']['allow_comments']) && $page['options']['allow_comments']==1)?"selected":"")?>><?php echo __("Yes", $module)?></option>
			</select><br />

			<label for="options[notify]"><?php echo __("Notify for comment", $module)?>:</label>
			<select name="options[notify]" class="input-select" id="notify">
			<option value='0' <?php echo ((isset($page['options']['notify']) && $page['options']['notify']==0)?"selected":"")?>><?php echo __("No", $module)?></option>
			<option value='1' <?php echo ((isset($page['options']['notify']) && $page['options']['notify']==1)?"selected":"")?>><?php echo __("Yes", $module)?></option>
			</select><br />

		</div>
		<?php 
		/*
		This will be here when ready for another ACL for page
		<div id="four">
		
			<label for="page_access"><?php _e("Page access:", $module)?></label><br />
		<input type='hidden' name='groups[0][level]' value='1' />
		<select name="groups[0][g_id]" id="groups" class="select">
		<?php foreach ($this->user->get_group_list() as $group): ?>
			<option value="<?php echo $group['g_id'] ?>" <?php if (isset($row['g_id']) && ($group['g_id'] == $row['g_id'])) echo "selected" ?> ><?php echo __($group['g_name'], $module) ?></option>
		<?php endforeach; ?> 
		</select>
		<br />
			
			<br />
		
		</div>
		*/
		?>
		
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	
</div>
<!-- [Content] end -->
