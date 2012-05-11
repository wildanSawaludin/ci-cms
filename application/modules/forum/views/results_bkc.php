<h1><?php echo $title;?></h1>
<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="forum-list" width="100%">
<thead>
<tr>
<th width="50%">
<?php echo __("Title", $module) ?>
</th>
<th width="10%">
<?php echo __("Author", $module) ?>
</th>
<th width="10%">
<?php echo __("Messages", $module) ?>
</th>
<th width="30%">
<?php echo __("Last change", $module) ?>
</th>
</tr>
</thead>
<tbody>
<?php if($rows) :?>
<?php foreach ($rows as $row): ?>
<tr>
<td valign="top">
<a href="<?php echo ($row['pid'])?$row['pid']:$row['mid'] ?>"><?php echo $row['title'] ?></a>
</td>
<td valign="top">
<?php echo $row['message'] ?>
</td>
<td valign="top">
<?php echo $row['username'] ?>
</td>
<td valign="top">
<?php echo $row['replies'] ?>
</td>
<td valign="top">
<?php 
if($row['last_mid']) :
echo sprintf(__("on %s by %s", $module), anchor('forum/message/' . $row['last_mid'], date("d/M/Y H:i", $row['last_date'])), anchor('profile/' . $row['last_username'], $row['last_username']));
endif;
?>
</td>
</tr>
<?php endforeach; ?> 
<?php endif ?>
</tbody>
</table>

<?php echo $pager ?>
