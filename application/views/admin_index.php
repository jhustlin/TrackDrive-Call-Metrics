<script type="text/javascript">
	$(function() {
		$.ajax({
			url: 'http://' + http_host + '/admin/ajax_translations',
			dataType: 'script',
			cache: true,
			async: false
		});
	});
</script>

<div id="main_header">
    <img src="/images/admin/logo.png" id="logo"/>
    <?php if(!empty($this->session->userdata('admin_user')->access)) { ?>
	<div id="menu_toggle"><i class="fa fa-bars"></i></div>
    <?php } ?>
	<div id="user_toggle">
		<div><?php echo $this->session->userdata('admin_user')->name_surname; ?> <i class="fa fa-caret-down" aria-hidden="true"></i></div>
		<ul>
			<li><a href="/admin/logout">exit</a></li>
		</ul>
	</div>
	<?php if ($this->session->userdata('developer') == 'Y') echo '<span style="color: #ff0000; position: absolute; left: 0;"> Developer</span>'; ?>
</div>
<div id="main_content">
    <?php if(!empty($this->session->userdata('admin_user')->access)) { ?>
	<div id="main_left">
		<div class="content">
			<div style="height: 400px; overflow-y: auto;" id="main_menu_right">
				<ul id="main_menu">
					<?php
					 foreach (explode(',', ALL_MODULES) as $module) if (in_array($module, explode(',', $this->session->userdata('admin_user')->access))) echo '<li><a href="javascript:void(0)" id="'.$module.'">'.__('module_'.$module).'</a></li>';
					?>
				</ul>
			</div>
		</div>
	</div>
    <?php } ?>
	<div id="main_right">
		<div id="tab_holder">
			<a href="javascript:void(0)" class="refresh_but"><img src="/images/admin/refresh_icon.png" /></a>
		</div>
		<div id="iframe_holder"></div>
	</div>
</div>