<h1><?php echo $title; ?> </h1>
<p><?php echo __("These are the admins of this forum", $module) ?></p>
<?php $last_admin = ''; $i = 0; ?>
<?php foreach ($rows as $row) : ?>
<?php if($row['username'] != $last_admin): ?>
<?php if($i > 0) echo "</ul></li></ul>" ?>
<ul><li><?php echo $row['username'] ?><ul>
<?php endif; ?>
<li><?php echo anchor('forum/topic/' . $row['tid'], $row['title']) ?></li>
<?php $last_admin = $row['username'] ?>
<?php $i++; endforeach; ?>
</ul></li></ul>
