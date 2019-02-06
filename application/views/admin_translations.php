<style type="text/css">
	table td {
		padding: 2px;
	}
	.active_trl{
		cursor: pointer !important;
	}
	.active_trl:hover {
		background-color: #ccc;
	}
	table tr:hover td {
		background-color: #eee;
	}
</style>
<script type="text/javascript">
	$(function() {
		
		$('.active_trl').click(function() {
			var this_tr_td = $(this);
			$('#dialog', parent.document).html('<textarea id="translation" rows="5" cols="50">' + $(this).html() + '</textarea>');
			$('#dialog', parent.document).dialog({
				modal : true,
				draggable: false,
				title: this_tr_td.attr('language') + ' -> ' + this_tr_td.attr('keyword'),
				width: 380,
				position: [jQuery($(window.parent.document)).width() / 2 - 180, 200],
				dialogClass: 'dialog2v',
				buttons : [
					{
						text : '<?php echo __('Close') ?>',
						click : function() {
							$(this).dialog("close");
						}
					},
					{
						text : '<?php echo __('Accept') ?>',
						click : function() {
							make_post(this_tr_td.attr('id'), $('#translation', parent.document).val());
							$(this).dialog("close");
						}
					}
				]
			});
	 	});
	 	
		$('#change_type').change(function(){
			$('.progress', parent.document).show();
			document.location.href = '<?php echo base_url('admin/translations?change_type=') ?>' + $(this).val();
		});
		$('.progress', parent.document).hide();
	});
	
	function make_post(id, translation) {
		$('#' + id).html('<img src="/images/admin/indicator-white.gif" height="10" alt="..." />');
		$.post('<?php echo base_url('admin/translations/translate') ?>', {
			translation: translation,
			id: id
		},function(data){
			$("#" + id).html(data);
		});
	}
</script>
<table width="100%" class="orange_table">
	<tr class="header">
		<th class="width-300"><?php echo __('Keyword') ?></th>
		<th><?php echo strtoupper($this->session->userdata('admin_user')->content_lang) ?></th>
		<?php if ($this->session->userdata('developer') == 'Y') echo '<th style="width: 50px">&nbsp;</th>' ?>
	</tr>
	<?php foreach ($translations as $keyword => $tr_data) { ?>
		<tr>
			<td class="txt_left" nowrap="nowrap"><?php echo $keyword ?></td>
			<td
				class="active_trl a_left"
				keyword="<?php echo $keyword; ?>"
				language="<?php echo $this->session->userdata('admin_user')->content_lang; ?>"
				id="<?php echo $tr_data[$this->session->userdata('admin_user')->content_lang]['id'] ?>"><?php echo $tr_data[$this->session->userdata('admin_user')->content_lang]['translation'] ?></td>
			<?php if ($this->session->userdata('developer') == 'Y') echo '<td align="center"><a href="'.base_url('admin/translations/').'?del='.$tr_data[$this->session->userdata('admin_user')->content_lang]['id'].'">del</a></td>' ?>
		</tr>
	<?php } ?>
</table>