<?php
/*
 * $Id: result.php 359 2009-06-10 13:06:00Z heriniaina.eugene $
 *
 *
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<h1><?=__("Search again", "search")?></h1>
<form method='post' action="<?=site_url('search/result')?>">
<table border='0'>
<tr>
<td valign='top'>
<?=__("Text to search", "search")?>
</td>
<td valign='top'>
<input type='text' name='tosearch' value='<?=$tosearch?>'>
</td>
</tr>
<tr>
<td valign='top' align='center'>
<input type='submit' name='bouton' value='     <?=__("Search", "search")?>    '>
</td>
</tr>
</table>
<h1><?=__("Search result", "search")?></h1>

<table width="100%" id="search-result">
<tbody>
<? $i=0; foreach ($rows as $row): ?>
<? $class = ($i %2 == 0)? "odd": "even" ?>
<tr class="<?=$class?>">
<td class="title"><?=((isset($row['result_link']))?"<a href='".$row['result_link']."'>":"")?><?=$row['result_title']?><?=((isset($row['result_link']))?"</a>":"")?> <?=((isset($row['result_type']))?"(".$row['result_type'].")":"")?> <?=((isset($row['result_date']))?"(".$row['result_date'].")":"")?></td>
</tr>
<tr class="description">
<td><?=eregi_replace("(".$tosearch. ")","<span style='background-color: yellow'>\\1</span>", strip_tags($row['result_text']))?></td>
</tr>
<? $i++; endforeach; ?>
</tbody>
</table>
<div class="pager">
<?=$pager?>
</div>