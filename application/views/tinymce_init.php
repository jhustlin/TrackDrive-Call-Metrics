<script type="text/javascript">
	var field_name = '';
	var base = '<?php echo $_SERVER['SERVER_NAME']; ?>';
	$(function() {
		tinymce.init({
			selector: 'textarea.tinyMCE',
			document_base_url: 'http://' + base + '/',
			skin: 'lightgray',
			
			// content_css : "< ?php// echo WEBROOT ?>css/style_el.css?myParam=myValue&bogus=" + new Date().getTime(),

			style_formats: [
				{title: 'Bold text', inline: 'b'},
				{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
				{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
				{title: 'Example 1', inline: 'span', classes: 'example1'},
				{title: 'Example 2', inline: 'span', classes: 'example2'},
				{title: 'Table styles'},
				{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],

			language: '<?php echo $this -> session -> userdata('admin_user') -> sys_lang; ?>',

			file_browser_callback: file_picker,
		
			plugins: ["contextmenu paste table textcolor link image code media insertdatetime charmap lists contextmenu"],
		
			theme: "modern",
			//menubar: false,
			toolbar1: "fontselect,fontsizeselect,formatselect,styleselect,|,forecolor,backcolor,|,bold,italic,underline",
			toolbar2: "alignleft,aligncenter,alignright,alignjustify,|,removeformat,|,bullist,numlist,|,link,unlink,|,image,|,media,|,hr",
			width: 800,
			//height: 500,
			//media_strict: false,
			//forced_root_block: false,
		
			resize: false,
		
			fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
		
			image_advtab: true,

			link_list: '/admin/file_list',
			image_list: '/admin/file_list?type=img',
			
			setup: function(ed) {
    			ed.on('change', function(e) {
					ed.getElement().value = ed.getContent();
				});
			}
			
		});
	});
	function file_picker(field_n, url, type, win) {
		field_name = field_n;
		tinyMCE.activeEditor.windowManager.open({
			url : '/js/tinymce/file_picker.html',
			width : 700,
			height : 400,
			title : '<?php echo __('File picker') ?>',
		}, {
			window : win,
			input : field_name
		});
	}
</script>