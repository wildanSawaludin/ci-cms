<!-- [Content] start -->
<h1 id="page"><?php echo $title ?></h1>
<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>


<div id="pagelist">
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $page): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
    <div class="<?php echo $rowClass?>">
        <div class="title"><?php echo anchor($page['uri'], $page['title']) ?></div>
        <div class="summary">
            <?php if($page['image']): ?>
            <img src="<?php echo site_url('media/images/s/' . $page['image']['file']) ?>" />
            <?php endif; ?>
        <?php echo $page['summary'] ?>
        </div>
            
    </div>
<?php $i++; endforeach;?>
<div style="clear: both; text-align: center;">
<?php echo $pager?>
</div>
<?php else: ?>
        <?php echo __("Page not found", $module) ?>
<?php endif; ?>

</div>
<!-- [Content] end -->
