<?php 
/*
 * $Id: search_form.php 209 2008-10-22 17:55:05Z heriniaina.eugene $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<h1><?php  echo __("Search text", "search");?></h1>
<form method='post' action="<?php  echo site_url('search/result')?>">
<table border='0'>
<tr>
<td valign='top'>
<?php  echo __("Text to search", "search")?>
</td>
<td valign='top'>
<input type='text' name='tosearch' value=''>
</td>
</tr>
<tr>
<td valign='top' align='center'>
<input type='submit' name='bouton' value='     <?php  echo __("Search", "search")?>    '>
</td>
</tr>
</table>
</form>
