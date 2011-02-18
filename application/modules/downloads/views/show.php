<h1><?php  echo $row['title']?></h1>
<?php 
	if($page_break_pos = strpos($row['desc'], "<!-- page break -->"))
	{
		$row['desc'] = substr($row['desc'], $page_break_pos + 19);
	}
	
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
<?php  echo $row['desc']?>
<a href="<?php  echo $row['link']?>"><?php  echo __("Download", 'downloads')?></a>