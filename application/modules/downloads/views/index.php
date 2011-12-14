<!-- [Content] start -->


<h1><?php echo $title?></h1>
<?php if(isset($parent)) : ?>

<?php
if($page_break_pos = strpos($parent['desc'], "<!-- page break -->"))
{
	$parent['summary'] = substr($parent['desc'], 0, $page_break_pos);
}
else
{
	$parent['summary'] = $parent['desc'];
}
?>
<div class="description">
<?php echo $parent['summary'] ; ?>
</div>
<?php endif; ?>

		
<?php if ($rows && count($rows) > 0) : ?>
<?php foreach ($rows as $row): ?>
<div class="downloads-dir">

<img src="<?php echo site_url('media/images/downloads/dir.gif')?>" >
<a href="<?php echo site_url('downloads/index/' . $row['id'])?>"><?php echo strip_tags($row['title'])?></a><br />
</div>
<?php endforeach;?>
<?php endif; ?>

<?php unset($row); if ($files && count($files) > 0) : ?>
<?php foreach ($files as $row): ?>
<div class="downloads-file">
<?php 

if ($row['file_link'])
{
	$row['link'] = $row['file_link'];
	$row['ext'] = $ext = substr(strrchr($row['file_link'], "."), 1);
	
}
else
{
	$row['ext'] = $ext = substr(strrchr($row['file'], "."), 1);
	$row['link'] = site_url('downloads/document/get/' . $row['file']);
}
?>		
<img src="<?php echo site_url('media/images/downloads/' . $row['ext'] . '.gif')?>" >
<a href="<?php echo $row['link']?>"><?php echo $row['title']?></a><br />
</div>
<?php endforeach;?>
<?php endif; ?>


<?php echo $pager?>

<!-- [Content] end -->
