<?php
if ($this->session->flashdata('alert') !== FALSE) {
	$alert = $this->session->flashdata('alert');
	?>
	<div class="alert_type_<?php echo $alert['type']; ?>">
		<?php echo $alert['msg']; ?>
	</div>
<?php } ?>
<?php
if (isset($record->id)) {
	include 'tinymce_init.php';
	?>
	<style type="text/css">
		#mce-modal-block.mce-in {
		    opacity: 0;
		}
		td {
			vertical-align: middle;
		}
		#uniform-undefined {
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
	<form id="form1" action="<?php echo base_url('admin/links/update/'.$record->id) ?>" method="post">
		<table class="form-tab width-550">
			<tr>
				<th colspan="2" id="form_title"><?php echo $record->temporary == 'N' ? __('Edit record') : __('Create new record') ?></th>
			</tr>
			<tr>
				<td><?php echo __('Title') ?> (LV)*</td>
				<td><input type="text" name="title" value="<?php echo outH($record->title) ?>" class="white_w width-650  main_input" /></td>
			</tr>
			<tr>
				<td><?php echo __('Link') ?> (LV)*</td>
				<td><input type="text" name="link" value="<?php echo outH($record->link) ?>" class="white_w width-650  main_input" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<button class="red_btn" type="button" onclick="window.location.href='<?php echo base_url('admin/links') ?>'"><?php echo __('Close') ?></button>
					<button class="orange_btn" type="reset"><?php echo __('Reset') ?></button>
					<button class="green_btn" type="submit" name="close" value="1"><?php echo __('Accept and close') ?></button>
					<button class="green_btn" type="submit" name="close" value="0"><?php echo __('Accept') ?></button>
				</td>
			</tr>
		</table>
	</form>
<?php } ?>

<style type="text/css">
	#data_tab {
		width: 100%;
		min-width: 600px;
	}
	.ui-state-disabled{
		opacity: 0.1 !important;
	}
</style>

<?php if ($user_type != 'user') { ?>	
    <button class="orange_btn" type="button" onclick="document.location.href='<?php echo base_url('admin/links/add') ?>'"><?php echo __('Create new record') ?></button>
<?php } ?>
<style type="text/css">
	.handle {
		width: 10px;
	}
	#data_tab tr:hover td.handle {
		background: transparent url('/images/admin/sys_updown.png') no-repeat center center;
	}
</style>
<div id="containment" style="padding-bottom: 2px;">
	<table id="data_tab" class="orange_table">
		<thead>
			<tr>
                            <?php if($user_type != 'user') { ?>
				<th><?php echo __('Title') ?></th>
				<th width="50"><?php echo __('Edit') ?></th>
				<th width="50"><?php echo __('Delete') ?></th>
                            <?php } else { ?>
                                <th><?php echo __('Title') ?></th>
				<th><?php echo __('Link') ?></th>
                            <?php } ?>
			</tr>
		</thead>
		<tbody id="sortable">
			<?php
                        if(isset($links_list['record_arr'])) {
                            foreach ($links_list['record_arr'] as $item) {
                                
                                if($user_type != 'user') {
                                    echo Admin_links::show_row($item);
                                } else {
                                    echo Admin_links::show_row_user($item);
                                }
                            }
                            
                        }
			?>
		</tbody>
	</table>
	<br />
</div>