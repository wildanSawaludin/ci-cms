<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="quicklaunch"><?php echo __("Settings", $module)?></h1>
	
	<ul class="quickmenu">
		<li><a href="<?php echo site_url('admin/settings')?>"><?php echo __("General settings", $module)?></a></li>
		<li><a href="<?php echo site_url('admin/module')?>"><?php echo __("Modules settings", $module)?></a></li>		
		<li><a href="<?php echo site_url('admin/admins')?>"><?php echo __("Administrators", $module)?></a></li>		
		<li><a href="<?php echo site_url('admin/groups')?>"><?php echo __("Group management", $module)?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="dashboard"><?php echo __("Dashboard", $module)?></h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<div class="row">

	<div class="left">
		<h2><?php echo __("System informations", $module)?></h2>
		<dl>
			<dt><?php echo __("Currently running:", $module)?></dt>
			<dd class="bold">CI-CMS <?php echo  $this->system->version ?></dd>
<?php if ( $this->system->version < $latest_version ): ?>
			<dt><?php echo __("Latest version:", $module)?></dt>
			<dd class="red">CI CMS <?php echo $latest_version?></dd>
			<dt><?php echo __("Get new version:", $module)?></dt>
			<dd class="bold"><a href="http://code.google.com/p/ci-cms/downloads/list" rel="external"><?php echo __("Upgrade Now!", $module)?></a></dd>
<?php endif;?>
			<dt><?php echo __("Site name:", $module)?></dt>
			<dd><?php echo $this->system->site_name?></dd>
			<dt><?php echo __("Site adress:", $module)?></dt>
			<dd><a href="<?php echo base_url()?>"><?php echo base_url()?></a></dd>
			<dt><?php echo __("Staff:", $module)?></dt>
			<dd><?php echo $this->administration->no_active_users()?></dd>
			<dt><?php echo __("Database size:", $module)?></dt>
			<dd><?php echo formatfilesize($this->administration->db_size())?></dd>
			<dt>C<?php echo __("Cache size:", $module)?> <?php  echo anchor('admin/clear_cache', __("Clear", $module)) ?></dt>
			<dd><?php echo recursive_directory_size($this->config->item('cache_path'), TRUE);?></dd>
		</dl>
	</div>
	
	<div class="right">
		<h2><?php echo __("Latest News", $module)?></h2>
		<ul>
			<?php $i = 0; if (CICMS_VERSION < $latest_version): $k = 8; else: $k = 6; endif;?>
			<?php foreach ($cicms_news as $news):?>
            <?php $i ++; if ($i > $k) continue;?>
                        <li><a href="<?php echo $news->get_link()?>" rel="external"><?php echo $news->get_title()?></a></li>
            <?php endforeach;?>
		</ul>
	</div>	
    
    <br />
    <?php $i = 0; if (!empty($cicms2_news)):?>
    <div class="right">
    	<h2><?php echo __("Development News", $module)?></h2>
        <ul>
			<?php foreach ($cicms2_news as $news2):?>
			<?php $i ++; if ($i > $k) continue;?>
                        <li><a href="<?php echo $news2->get_link()?>" rel="external"><?php echo $news2->get_title()?></a></li>
            <?php endforeach;?>        
        </ul>
    </div>
    <?php endif; ?>
</div>

<br class="clearfloat" />

</div>
<!-- [Content] end -->
