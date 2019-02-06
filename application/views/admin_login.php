<style type="text/css">
	.error_bubble {
		display: none;
	}
	
	html, body {
		height: 100%;
	}
	#login_main {
		min-height: 100%;
	}
	#login_footer {
		height: 90px;
		line-height: 90px;
		text-align: center;
		font-size: 12px;
		color: #d27440;
		margin-top: -90px;
	}
	#login_main .header {
		font-size: 53px;
		height: 100px;
		text-align: center;
		color: #aaa;
		padding: 30px;
	}
	#login_main .header span {
		font-size: 17px;
		font-weight: bold;
		display: block;
		color: #4d4d4d;
		text-transform: uppercase;
		margin-top: 10px;
	}
	#login_main .line {
		height: 58px;
		background: #4d4d4d url('/images/admin/r_bg.png') no-repeat center 7px;
		color: #fff;
		text-align: center;
		font-size: 11px;
		line-height: 85px;
	}
	#login_main .line a {
		color: #fff;
		text-decoration: none;
	}
	#form1 {
		display: block;
		width: 290px;
		height: 220px;
		background-color: #4d4d4d;
		margin: 35px auto 0 auto;
		-moz-border-radius: 5px;
		border-radius: 5px;
		padding: 30px 40px;
	}
	#form1 label {
		display: block;
		padding: 0 0 5px 0;
		color: #fff;
		font-size: 14px;
	}
	#form1 input[type="text"], #form1 input[type="password"] {
		width: 270px;
		padding: 10px;
		margin-bottom: 20px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		border: none;
	}
	#form1 button {
		margin-top: 10px;
	}
	#login_main .info {
		color: #6b6b6b;
		line-height: 20px;
		margin: 20px auto;
		text-align: center;
		padding-bottom: 90px;
	}
	#login_main .info span {
		margin-top: 5px;
		display: block;
		font-weight: bold;
	}
</style>
<script type="text/javascript">
	$(function() {
		$('input[name=email]').focus();
		
		$('#form1').ajaxForm({
			dataType: 'json',
			beforeSubmit: function() {
				$('.progress').show();
			},
			success: showResponse,
			error: function() {
				alert('Ajax error!');
			}
		});
	});
</script>
<div id="login_main">
	<form id="form1" action="<?php echo base_url('admin/ajax_login') ?>" method="post">
		<label for="email"><?php echo __('E-mail'); ?></label>
		<input type="text" name="email" id="email" />
		<label for="password"><?php echo __('Password'); ?></label>
		<input type="password" name="password" id="password" />
		<div id="info_block" style="display: none;"></div>
		<button type="submit" class="button blown">
			<span><?php echo __('Login') ?></span>
		</button>
	</form>
</div>