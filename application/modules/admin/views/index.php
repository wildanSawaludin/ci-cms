<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="quicklaunch"><?php echo $this->locale->__("Settings", $this->template['module'])?></h1>
	
	<ul class="quickmenu">
		<li><a href="<?php echo site_url('admin/settings')?>"><?php echo $this->locale->__("General settings", $this->template['module'])?></a></li>
		<li><a href="<?php echo site_url('admin/module')?>"><?php echo $this->locale->__("Modules settings", $this->template['module'])?></a></li>		
		<li><a href="<?php echo site_url('admin/admins')?>"><?php echo $this->locale->__("Administrators", $this->template['module'])?></a></li>		
		<li><a href="<?php echo site_url('admin/groups')?>"><?php echo $this->locale->__("Group management", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="dashboard"><?php echo $this->locale->__("Dashboard", $this->template['module'])?></h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<div class="row">

	<div class="left">
		<h2><?php echo $this->locale->__("System informations", $this->template['module'])?></h2>
		<dl>
			<dt><?php echo $this->locale->__("Currently running:", $this->template['module'])?></dt>
			<dd class="bold">CI-CMS <?php echo  $this->system->version ?></dd>
<?php if ( $this->system->version < $this->latest_version ): ?>
			<dt><?php echo $this->locale->__("Latest version:", $this->template['module'])?></dt>
			<dd class="red">CI CMS <?php echo $this->latest_version?></dd>
			<dt><?php echo $this->locale->__("Get new version:", $this->template['module'])?></dt>
			<dd class="bold"><a href="http://code.google.com/p/ci-cms/downloads/list" rel="external"><?php echo $this->locale->__("Upgrade Now!", $this->template['module'])?></a></dd>
<?php endif;?>
			<dt><?php echo $this->locale->__("Site name:", $this->template['module'])?></dt>
			<dd><?php echo $this->system->site_name?></dd>
			<dt><?php echo $this->locale->__("Site adress:", $this->template['module'])?></dt>
			<dd><a href="<?php echo base_url()?>"><?php echo base_url()?></a></dd>
			<dt><?php echo $this->locale->__("Staff:", $this->template['module'])?></dt>
			<dd><?php echo $this->administration->no_active_users()?></dd>
			<dt><?php echo $this->locale->__("Database size:", $this->template['module'])?></dt>
			<dd><?php echo formatfilesize($this->administration->db_size())?></dd>
			<dt>C<?php echo $this->locale->__("ache size:", $this->template['module'])?></dt>
			<dd><?php echo recursive_directory_size($this->config->item('cache_path'), TRUE);?></dd>
		</dl>
	</div>
	
	<div class="right">
		<h2><?php echo $this->locale->__("Latest News", $this->template['module'])?></h2>
		<ul>
<?php $i = 0; if (CICMS_VERSION < $this->latest_version): $k = 8; else: $k = 6; endif;?>
<?php foreach ($cicms_news as $news):?>
<?php $i ++; if ($i > $k) continue;?>
			<li><a href="<?php echo $news->get_link()?>" rel="external"><?php echo $news->get_title()?></a></li>
<?php endforeach;?>
		</ul>
	</div>	
</div>

<br class="clearfloat" />

</div>
<!-- [Content] end -->
