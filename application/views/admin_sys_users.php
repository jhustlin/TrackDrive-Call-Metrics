<?php
if ($this->session->flashdata('alert') !== FALSE) {
	$alert = $this->session->flashdata('alert');
	?>
	<div class="alert_type_<?php echo $alert['type']; ?>">
		<?php echo $alert['msg']; ?>
	</div>
<?php } ?>
<?php if (isset($user->id)) { ?>
	<style type="text/css">
		#username-exsists {
			display: none;
		}
		td {
			vertical-align: middle;
		}
		#uniform-sys_lang {
			margin-bottom:0px!important;
		}
	</style>
	<script type="text/javascript">
		$(function() {
			// form
			$('#form1').ajaxForm({
				dataType: 'json',
				beforeSubmit: function() {
					$('.progress', parent.document).show();
				},
				data: {
					action: 'update'
				},
				success: showResponse,
				error: function(xhr, textStatus, errorThrown) {
					alert("in ajaxForm error");
				}
			});
		});
	</script>
<script type="text/javascript">
	$(function(){
	    $("select, input[type='checkbox']").uniform();
	});
</script>
	
	<form id="form1" action="<?php echo base_url('admin/sys_users/update/'.$user->id) ?>" method="post">
		<table class="form-tab width-550">
			<tr>
				<th colspan="2" id="form_title"><?php echo $user->temporary == 'N' ? __('Edit user') : __('Create new user') ?></th>
			</tr>
			<?php if (!isset($user->id) || $this->session->userdata('admin_user')->id != $user->id) { ?>
				<tr>
					<td><label for="type"><?php echo __('Type') ?></label></td>
					<td>
						<select name="type" id="type" size="1" class="width-100">
							<option value="user"<?php if (isset($user->type) && $user->type == 'user') echo ' selected="selected"' ?>>user</option>
							<option value="admin"<?php if (isset($user->type) && $user->type == 'admin') echo ' selected="selected"' ?>>admin</option>
						</select>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td width="200"><label for="name_surname"><?php echo __('Name Surname') ?></label></td>
				<td width="350"><input type="text" name="name_surname" id="name_surname" value="<?php if (isset($user->name_surname)) echo outH($user->name_surname) ?>" class="width-350 main_input" /></td>
			</tr>
			<tr>
				<td><label for="email"><?php echo __('E-mail') ?>*</label></td>
				<td><input type="text" name="email" id="email" value="<?php if (isset($user->email)) echo outH($user->email) ?>" class="width-350 main_input" /></td>
			</tr>
			<tr>
				<td><label for="password"><?php echo __('Password') ?></label><?php if ($user->temporary == 'Y') echo '*' ?></td>
				<td><input type="password" name="password" id="password" autocomplete="off" class="width-200 main_input" /></td>
			</tr>
            <tr>
				<td><label for="traffic_source"><?php echo __('Traffic Source') ?>*</label></td>
				<td><input type="text" name="traffic_source" id="traffic_source" value="<?php if (isset($user->traffic_source)) echo outH($user->traffic_source) ?>" class="width-350 main_input" /></td>
			</tr>
			<tr>
				<td><label for="company_subdomain"><?php echo __('Company Subdomain') ?>*</label></td>
				<td><input type="text" name="company_subdomain" id="company_subdomain" value="<?php if (isset($user->company_subdomain)) echo outH($user->company_subdomain) ?>" class="width-350 main_input" /></td>
			</tr>
			<tr>
				<td><label for="sys_lang"><?php echo __('Adm. language') ?></label>:</td>
				<td>
					<select name="sys_lang" id="sys_lang" size="1" class="width-120" />
						<option value="en"<?php if (isset($user->sys_lang) && $user->sys_lang == 'en') echo ' selected="selected"' ?>>en - english</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo __('Access') ?></td>
				<td>
					<div style="height: 200px; overflow: auto; border: solid 1px #aaa; background-color: #fff; padding: 2px;margin-top:10px;">
						<?php foreach (explode(',', ALL_MODULES) as $module) { ?>
							<div class="module" >
								<label>
									<input type="checkbox" name="module[]" value="<?php echo $module; ?>"<?php if (in_array($module, explode(',', $user->access))) echo ' checked="checked"' ?> />
									<?php echo __('module_'.$module) ?>
								</label>
							</div>
							<br style="clear:both;" />
						<?php } ?>
					</div>
				</td>
			</tr>
			<tr>
                            <td colspan="2" align="right">
                                    <button class="red_btn"  type="button" onclick="window.location.href='<?php echo base_url('admin/sys_users') ?>'"><?php echo __('Close') ?></button>
                                    <button class="orange_btn"  type="reset"><?php echo __('Reset') ?></button>
                                    <button class="green_btn"  type="submit" name="close" value="1"><?php echo __('Accept and close') ?></button>
                                    <button class="green_btn" type="submit" name="close" value="0"><?php echo __('Accept') ?></button>
                            </td>
			</tr>
		</table>
	</form>
<?php } ?>
<button class="orange_btn" type="button" onclick="document.location.href='<?php echo base_url('admin/sys_users/add') ?>'"><?php echo __('Create new user') ?></button>
<table class="data_tab orange_table" width="100%">
	<tr class="header">
		<th class="width-50"><?php echo __('Type') ?></th>
		<th><?php echo __('Name Surname') ?></th>
		<th ><?php echo __('E-mail') ?></th>
		<th><?php echo __('Access') ?></th>
		<th class="width-150 center"><?php echo __('Last login') ?></th>
		<th class="width-50 center"><?php echo __('Edit') ?></th>
		<th class="width-50 center"><?php echo __('Delete') ?></th>
	</tr>
	<?php foreach($user_list as $id => $user){
		if ($user->temporary == 'N') {
			?>
			<tr>
				<td class="center"><?php echo $user->type ?></td>
				<td>
                                    <?php echo $user->name_surname ?>
                                    <?php
                                        if(!empty($user->traffic_source)) {
                                            echo '<br/>'.__('Traffic SourceID').': '.$user->traffic_source;
                                        }
                                    ?>
                                </td>
				<td><?php echo $user->email ?></td>
				<td class="center" ><?php echo ($user->access != '' ? count(explode(',', $user->access)) : 0).' / '.count(explode(',', ALL_MODULES)) ?></td>
				<td class="center" ><?php echo $user->last_login != '0000-00-00 00:00:00' ? convert_date($user->last_login, 'd.m.Y H:i') : '-'; ?></td>
				<td class="center" ><a href="<?php echo base_url('admin/sys_users/'.$id) ?>"><img src="/images/admin/sys_edit.gif" border="0" height="17" alt="edit" /></a></td>
				<td class="center" ><?php if ($this->session->userdata('admin_user')->id != $id) { ?><a href="javascript:void(0);" onclick="confirm2('<?php echo __('Delete') ?>', '<?php echo __('Do you want to delete user') ?> &quot;<?php echo $user->email ?>&quot;?', '<?php echo base_url('admin/sys_users/delete/'.$id) ?>')"><img src="/images/admin/sys_delete.gif" border="0" height="17" alt="del" /></a><?php } else echo '-' ?></td>
			</tr>
	<?php } } ?>
</table>