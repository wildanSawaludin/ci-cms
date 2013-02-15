<!-- [Content] start -->
<script type="text/javascript">

$(document).ready(function(){
	$("#loading").hide();
	$("#upload_now").click(function(){
		ajaxFileUpload();
		return false;
	});
	$("a.deletefile").click(function(){
		if (confirm("Delete File?"))
		{
		deleteFile(this);
		return false;
		} else {
		return false;
		}
	});	
	/*handleDeleteImage();*/
});

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
			url:'<?php  echo site_url('admin/downloads/upload/ajax_file_upload')?>',
			secureuri:false,
			fileElementId: 'file',
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
						$("#file_list tbody").prepend("<tr><td>"+data.filedate+"</td><td>"+data.file+"</td><td>"+data.size+"</td><td><a href='#' class='deletefile' id='"+data.id+"'><?php  echo __('Delete file') ?></a></td><td><a href='<?php echo site_url('admin/downloads/document/fromfile') ?>/"+data.id+"'><?php echo __("New document", "downloads") ?></a>  </td></tr>");
						$("#file").val("");
						handleDeleteFile();
						
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
function handleDeleteFile() {
	$("a.deletefile").click(function(){
		if (confirm("Delete file?"))
		{
		deleteFile(this);
		return false;
		} else {
		return false;
		}
	});
}

function deleteFile(obj) {
	var id = obj.id
	$.post('<?php  echo site_url('admin/downloads/upload/ajax_file_delete')?>',
		{ id: id},
		function(data){
			if (data.error)
			{
				alert(data.message);
			}
			else 
			{
				alert(data.message);
				$(obj).parent().parent().remove();
			}
		},
		"json"
	);
}

</script>

<div class="content wide">

<h1 id="page"><?php  echo __("Upload files", 'downloads')?></h1>

<ul class="manage">
	<li><a href="<?php  echo site_url('admin/downloads')?>" class="last"><?php  echo __("Cancel", 'downloads')?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php  if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>

<form  enctype="multipart/form-data" class="edit" action="<?php  echo site_url('admin/downloads/upload/save')?>" method="post" accept-charset="utf-8">
		<label for="file"><?php  echo __("File", 'downloads')?>: </label>
		
		<input type="file" name="file" class="input-file" id="file"/>
<img src='<?php  echo site_url('application/views/' . $this->system->theme_dir . 'admin/images/ajax_circle.gif')?>' id='loading'/><input type='submit' id='upload_now' value='  <?php  echo __('Upload') ?>  ' />

</form>
		<table id="file_list" class="page-list">
			<thead>
				<tr>
					<th width="10%"><?php  echo __("Date", 'downloads')?></th>
					<th width="58%"><?php  echo __("File", 'downloads')?></th>					
					<th width="7%"><?php  echo __("Size", 'downloads')?></th>					
					<th colspan="2" width="25%"><?php  echo __("Action", 'downloads')?></th>
				</tr>
			</thead>
			<tbody>
		<?php  if (isset($rows)) : ?>
		<?php  foreach($rows as $file): ?>
		<tr><td><?php  echo date('d/m/Y', $file['date'])?></td><td><a href="<?php  echo site_url($this->downloads->settings['upload_path']  . $file['file'])?>"><?php  echo $file['file']?></a></td><td><?php  echo $file['size']?></td><td><a href="<?php  echo site_url('admin/downloads/upload/delete/' . $file['id']) ?>" class="deletefile" id="<?php  echo $file['id']?>"><?php  echo __("Delete file", 'downloads')?></a></td><td><?php echo anchor('admin/downloads/document/fromfile/' . $file['id'], __("New document", "downloads")) ?></td></tr>
		<?php  endforeach; ?>
		<?php  endif;?>
		</tbody>
		</table>
<?php  echo $pager?>
</div>
<!-- [Content] end -->
