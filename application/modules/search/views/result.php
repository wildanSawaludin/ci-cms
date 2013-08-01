<?php 
/*
 * $Id: result.php 359 2009-06-10 13:06:00Z heriniaina.eugene $
 *
 *
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<h1><?php  echo __("Search again", "search")?></h1>
<form method='post' action="<?php  echo site_url('search/result')?>">
<table border='0'>
<tr>
<td valign='top'>
<?php  echo __("Text to search", "search")?>
</td>
<td valign='top'>
<input type='text' name='tosearch' value='<?php  echo $tosearch?>'>
</td>
</tr>
<tr>
<td valign='top' align='center'>
<input type='submit' name='bouton' value='     <?php  echo __("Search", "search")?>    '>
</td>
</tr>
</table>
<h1><?php  echo __("Search result", "search")?></h1>

<table width="100%" id="search-result">
<tbody>
<?php $i=0; foreach ($rows as $row): ?>
<?php $class = ($i %2 == 0)? "odd": "even" ?>
<tr class="<?php  echo $class?>">
<td class="title"><?php  echo ((isset($row['result_link']))?"<a href='".$row['result_link']."'>":"")?><?php  echo $row['result_title']?><?php  echo ((isset($row['result_link']))?"</a>":"")?> <?php  echo ((isset($row['result_type']))?"(".$row['result_type'].")":"")?> <?php  echo ((isset($row['result_date']))?"(".$row['result_date'].")":"")?></td>
</tr>
<tr class="description">
<td><?php  echo str_replace($tosearch,"<span style='background-color: yellow'>" . $tosearch . "</span>", strip_tags($row['result_text']))?></td>
</tr>
<?php $i++; endforeach; ?>
</tbody>
</table>
<div class="pager">
<?php  echo $pager?>
</div>