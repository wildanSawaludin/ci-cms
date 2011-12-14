<h1><?php echo $title;?> <a href='<?php echo site_url('forum/rss/topic/' . $topic['tid']) ?>' target='_blank'><img src="http://forum.serasera.org/application/views/dinika_v3/images/feed-icon-14x14.png" width="14" height="14" border="0"></a></h1>
<?php if(is_array($topic['admins'])) : ?>
<p><b><?php echo __("Admins:", $module) ?></b> 
<?php 
$i = 0; foreach($topic['admins'] as $admin)
{
	if($i > 0) echo ", ";
	echo $admin['username'];
	$i++;
}
?></p>
<?php endif; ?>

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<div class="button">
<?php echo anchor('forum/message/new/' . $topic['tid'] , __("Create a new message", $module)) ?>
</div>
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
<th width="40%" colspan="2">
<?php echo __("Message", $module) ?>
</th>
<th width="10%">
<?php echo __("Author", $module) ?>
</th>
<th width="10%">
<?php echo __("Replies", $module) ?>
</th>
<th width="10%">
<?php echo __("Hits", $module) ?>
</th>
<th width="30%">
<?php echo __("Last change", $module) ?>
</th>
</tr>
</thead>
<?php if($messages) :?>
<tbody>
<?php $i = 0; foreach ($messages as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<?php $age = floor((mktime() - $row['last_date'])/ 3600);  ?>
<tr class="<?php echo $rowClass?>">
<td width="1%" class="<?php echo(empty($this->topic->rowclass[$age]) ? "more-days" : $this->topic->rowclass[$age]); ?>">
&nbsp;
</td>
<td valign="top">
<?php echo anchor('forum/message/' . $row['mid'], strip_tags($row['title'])) ?>
</td>
<td valign="top">
<?php echo $row['username'] ?>
</td>
<td valign="top">
<?php echo $row['replies'] ?>
</td>
<td valign="top">
<?php echo $row['hits'] ?>
</td>
<td valign="top">
<?php 
if($row['last_mid']) :
echo sprintf(__("on %s <br />by %s", $module), anchor('forum/message/' . $row['last_mid'], date("d/m/Y H:i", $row['last_date'])), anchor('profile/' . $row['last_username'], $row['last_username']));
endif;
?>
</td>
</tr>
<?php $i++; endforeach; ?> 
</tbody>
<?php endif ?>
</table>
<div class="pager">
<?php echo $pager ?>
</div>
