<?php if(!empty($data)) { ?>
	
	<style type="text/css">
		#diplay_table{
			width: 100%;
			border:1px solid black;
			font-weight:bold;
			border-collapse: collapse;
			background-color:#4d4d4d;
			color:white;
			font-size:20px;
			text-align:center;
		}
		#diplay_table td, 
		#diplay_table th { 
		  border: 2px solid white;
		  padding: 5px; 
		}
		
	</style>
	
	<form id="form1" action="<?php echo base_url('admin') ?>" method="post" onsubmit="return checkEntries();">
		<table id="diplay_table" >
			<tr>
				<th><?php echo __('Total Calls');?></th>
				<th><?php echo __('Total Converted');?></th>
				<th><?php echo __('Total In progress');?></th>
			</tr>
			<tr>
			<tr>
				<td><?php echo $data['all']?></td>
				<td><?php echo $data['converted']?></td>
				<td><?php echo $data['in_progress']?></td>
			</tr>
		</table>
		<table class="form-tab width-650">
			<tr>
				<td><label for="created_at_from"><?php echo __('Date from') ?></label></td>
				<td>
					<input type="text" name="created_at_from" id="created_at_from" value="<?php echo isset($_POST['created_at_from']) && !empty($_POST['created_at_from']) ? $_POST['created_at_from'] : date('Y-m-d'); ?>" class="datepicker width-250 main_input" />
				</td>
				
				<td><label for="created_at_to"><?php echo __('Date till') ?></label></td>
				<td>
					<input type="text" name="created_at_to" id="created_at_to" value="<?php echo isset($_POST['created_at_from']) && !empty($_POST['created_at_from']) ? $_POST['created_at_from'] : date('Y-m-d') ?>" class="datepicker width-250 main_input" />
				</td>
				
				<td><button class="orange_btn" type="submit" name="submit" value="1" ><?php echo __('Submit') ?></button></td>
			</tr>
		</table>
	</form>
	
	<script type="text/javascript">
		$(function() {
			//$('.progress', parent.document).hide();
			$('.datepicker').datepicker({
				firstDay: 1,
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
			});
		})
	</script>
	
<?php } else  { ?>
	
	<style type="text/css">
		#home_info_div {
			margin-top: 100px;
		}
		#home_info_div h2 {
			text-align: center;
			font-size: 20px;
			padding: 10px 0;
		}
		#home_info_div table {
			margin: 0px auto;
		}
		#home_info_div table td {
			padding: 7px;
		}
	</style>
	<script type="text/javascript">
		$(function() {
			$('.progress', parent.document).hide();
		})
	</script>
	<div id="home_info_div">
		<h2>Welcome</h2>
		<table>
			<tr>
				<td class="c1"><?php echo __('Name, Surname'); ?></td>
				<td class="c2"><?php echo $user_data->name_surname; ?></td>
			</tr>
			<tr>
				<td class="c1"><?php echo __('Type'); ?></td>
				<td class="c2"><?php echo $user_data->type == 'user' ? "publisher" : $user_data->type; ?></td>
			</tr>
			<tr>
				<td class="c1"><?php echo __('Last login'); ?></td>
				<td class="c2"><?php echo date('d.m.Y H:i', strtotime($user_data->last_login)); ?></td>
			</tr>
			
		</table>
	</div>

<?php } ?>