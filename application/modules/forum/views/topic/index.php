<h1><?php  echo $title;?></h1>

<?php  if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>

<div class="adminbox">
<?php  echo anchor('forum/message/new/' . $topic['tid'] , __("Create a new message", $module)) ?>
</div>



<table class="forum-list" width="100%">
<thead>
<tr>
<th width="40%">
<?php  echo __("Message", $module) ?>
</th>
<th width="10%">
<?php  echo __("Author", $module) ?>
</th>
<th width="10%">
<?php  echo __("Replies", $module) ?>
</th>
<th width="10%">
<?php  echo __("Hits", $module) ?>
</th>
<th width="30%">
<?php  echo __("Last change", $module) ?>
</th>
</tr>
</thead>
<?php  if($messages) :?>
<tbody>
<?php  $i = 0; foreach ($messages as $row): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<tr class="<?php  echo $rowClass?>">
<td valign="top">
<?php  echo anchor('forum/message/' . $row['mid'], strip_tags($row['title'])) ?>
</td>
<td valign="top">
<?php  echo $row['username'] ?>
</td>
<td valign="top">
<?php  echo $row['replies'] ?>
</td>
<td valign="top">
<?php  echo $row['hits'] ?>
</td>
<td valign="top">
<?php  
if($row['last_mid']) :
echo sprintf(__("on %s <br />by %s", $module), anchor('forum/message/' . $row['last_mid'], date("d/M/Y H:i", $row['last_date'])), anchor('profile/' . $row['last_username'], $row['last_username']));
endif;
?>
</td>
</tr>
<?php  $i++; endforeach; ?> 
</tbody>
<?php  endif ?>
</table>
