<h1><?php echo $title;?></h1>

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<div class="adminbox">
<?php echo anchor('forum/message/new', __("Create a new message", $module)) ?>
</div>


<?php if($rows) :?>
<?php $i = 0; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<table width="100%" class="forum-message">
<tbody>
<tr class="<?php echo $rowClass?>">
<td valign="top" class="message-header">
<a name="<?php echo $row['mid'] ?>"> </a><?php echo $row['username'] ?> - <?php echo date("d/m/Y H:i", $row['date']) ?>

<?php if($this->user->level['forum'] >= LEVEL_EDIT): ?>
<div style="float: right">
<?php echo anchor('forum/message/edit/' . $row['mid'], __("Edit", $module)) ?> 
<?php if($this->user->level['forum'] >= LEVEL_DEL): ?>
 | <?php echo anchor('forum/message/delete/' . $row['mid'], __("Delete", $module)) ?>
<?php endif; ?>
</div>
<?php endif; ?>

</td>
</tr><tr>
<td valign="top" class="message-body">
<img src="http://avatar.serasera.org/<?php echo md5($row['username'])?>.jpg" align="left" hspace="5" width="40" height="40" class="avatar"/>
<?php echo substr(strip_tags($this->bbcode->parse($row['message'])), 0, 200) ?>... <?php echo anchor('forum/message/' . (($row['pid'] == '')? $row['mid'] : $row['pid'] . "#" . $row['mid']) , __("more", $module)) ?>
</td>
</tr>
</tbody>
</table>

<?php $i++; endforeach; ?> 
<div class="pager">
<?php echo $pager ?>
</div>
<?php else: ?>
<?php echo __("No message found", "forum"); ?>
<?php endif ?>

<?php exit; ?>