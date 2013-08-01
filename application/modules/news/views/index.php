<h1><?php  echo $title ?></h1>
<?php $news_pre_list = ""; ?>
<?php echo $this->plugin->apply_filters("news_pre_list", $news_pre_list) ?>
<?php  if (is_array($rows) && count($rows) > 0) :?>
<?php  $i = $total_rows; foreach($rows as $row):?>
<?php  if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<div class="<?php  echo $rowClass ?>">
<h2><?php  echo ($i - $start) ?>. <?php  echo $row['title']?></h2>
	<?php if ($row['image']): ?>
	<img src="<?php  echo site_url('media/images/s/' . $row['image']['file'])?>" align="left" hspace="5"/>
	<?php endif; ?>
	<?php  echo strip_tags($row['summary']) ?> <a href="<?php  echo site_url('news/' . $row['uri'])?>" class="more"><?php  echo __("More", "rvg_v6")?>&gt;&gt;</a>
	<div class="clear"></div>
</div>
<?php  $i--; endforeach;?>
<div class="pager">
<?php  echo $pager?>
</div>
<?php  else : ?>
<?php  echo __("No news found", $module)?>
<?php  endif; ?>

<?php $news_post_list = ""; ?>
<?php echo $this->plugin->apply_filters("news_post_list", $news_post_list) ?>



