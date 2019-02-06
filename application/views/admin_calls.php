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
	
	<form id="form1" action="<?php echo base_url('admin/admin_calls') ?>" method="post" onsubmit="return checkEntries();">
		<table id="diplay_table" >
			<tr>
                                <th><?php echo __('Nummber');?></th>
				<th><?php echo __('Total In progress');?></th>
				<th><?php echo __('Total Converted');?></th>
				<th><?php echo __('Total Calls');?></th>
			</tr>
                        
                        <?php if(!empty($data['nummbers'])) { foreach($data['nummbers'] as $item) { ?>
                            <tr>
                                <td><?php echo $item['title'] ?></td>
                                <td><?php echo $item['in_progress']?></td>
                                <td><?php echo $item['converted']?></td>
                                <td><?php echo $item['all']?></td>
                            </tr>
                        <?php }}  ?>
                            
                            
			<tr>
                            <td></td>
                            <td><?php echo $data['in_progress']?></td>
                            <td><?php echo $data['converted']?></td>
                            <td><?php echo $data['all']?></td>
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
					<input type="text" name="created_at_to" id="created_at_to" value="<?php echo isset($_POST['created_at_to']) && !empty($_POST['created_at_to']) ? $_POST['created_at_to'] : date('Y-m-d') ?>" class="datepicker width-250 main_input" />
				</td>
				
				<td><button class="orange_btn" type="submit" name="submit" value="1" ><?php echo __('Submit') ?></button></td>
			</tr>
		</table>
	</form>
	
	<script type="text/javascript">
		$(function() {
			$( ".orange_btn" ).click(function() {
				$('.progress', parent.document).show();
			});
			
			
			$('.progress', parent.document).hide();
			$('.datepicker').datepicker({
				firstDay: 1,
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
			});
		})
	</script>