<h1><?php echo $title;?></h1>
<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>
<?php if($this->user->level['forum'] >= LEVEL_ADD ) : ?>
<div class="adminbox">
<?php echo anchor('forum/topic/add', __("Add a new topic", $module)) ?>
</div>
<?php endif; ?>
<br />
<table width="100%" class="forum-list">
<tr>
<td class="one-hour"><?php echo __("One hour", $module) ?></td>
<td class="five-hours"><?php echo __("Five hours", $module) ?></td>
<td class="one-day"><?php echo __("One day", $module) ?></td>
<td class="five-days"><?php echo __("Five days", $module) ?></td>
<td class="more-days"><?php echo __("More days", $module) ?></td>
</tr>
</table>
<br />

<table class="forum-list" width="100%">
<thead>
<tr>
<th width="60%" colspan="2">
<?php echo __("Title", $module) ?>
</th>
<th width="10%">
<?php echo __("Messages", $module) ?>
</th>
<th width="25%">
<?php echo __("Last change", $module) ?>
</th>
</tr>
</thead>
<?php if($rows) :?>
<tbody>
<?php $i= 0; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<?php $age = floor((mktime() - $row['last_date'])/ 3600);  ?>
<tr class="<?php echo $rowClass?>">
<td class="<?php echo(empty($this->topic->rowclass[$age]) ? "more-days" : $this->topic->rowclass[$age]);  ?>" width="1%">&nbsp;</td>
<td valign="top">
<div class="title"><?php echo anchor('forum/topic/' . $row['tid'], strip_tags($row['title'])) ?></div>
<div class="description"><?php echo $row['description'] ?></div>
</td>
<td valign="top">
<?php echo $row['messages'] ?>
</td>
<td valign="top">
<?php 
if($row['last_mid']) :
echo sprintf(__("on %s <br />by %s", $module), anchor('forum/message/' . $row['last_mid'], date("d/M/Y H:i", $row['last_date'])), anchor('profile/' . $row['last_username'], $row['last_username']));
endif;
?>
</td>
</tr>
<?php $i++; endforeach; ?> 
</tbody>
<?php endif ?>
</table>


