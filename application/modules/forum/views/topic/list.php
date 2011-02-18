<h1><?php  echo $title;?></h1>

<?php  if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php  echo $notice;?></p>
<?php  endif;?>
<?php  if($this->user->level['forum'] >= LEVEL_ADD ) : ?>
<div class="adminbox">
<?php  echo anchor('forum/topic/add', __("Add a new topic", $module)) ?>
</div>
<?php  endif; ?>
<table class="forum-list" width="100%">
<thead>
<tr>
<th width="30%">
<?php  echo __("Title", $module) ?>
</th>
<th width="35%">
<?php  echo __("Description", $module) ?>
</th>
<th width="10%">
<?php  echo __("Messages", $module) ?>
</th>
<th width="25%">
<?php  echo __("Last change", $module) ?>
</th>
</tr>
</thead>
<?php  if($rows) :?>
<tbody>
<?php  $i= 0; foreach ($rows as $row): ?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<tr class="<?php  echo $rowClass?>">
<td valign="top">
<?php  echo anchor('forum/topic/' . $row['tid'], strip_tags($row['title'])) ?>
</td>
<td valign="top">
<?php  echo $row['description'] ?>
</td>
<td valign="top">
<?php  echo $row['messages'] ?>
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


