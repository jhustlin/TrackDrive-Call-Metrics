function alert2(msg) {
	$("#dialog", parent.document).html(msg);
	$("#dialog", parent.document).dialog({
		modal : true,
		draggable: false,
		title: parent.language['Alert'],
		width: 300,
		position: [jQuery($(window.parent.document)).width() / 2 - 150, 200],
		buttons : [
			{
				text : parent.language['ok'],
				click : function() {
					$(this).dialog("close");
				}
			}
		]
	});
}
function confirm2(title, msg, url) {
	$("#dialog", parent.document).html(msg);
	$("#dialog", parent.document).dialog({
		modal : true,
		title: title,
		draggable: false,
		width: 300,
		position: [jQuery($(window.parent.document)).width() / 2 - 150, 200],
		dialogClass: 'dialog2v',
		buttons : [
			{
				tabIndex: -1,
				text : parent.language['ok'],
				click : function() {
					window.location.href = url;
					$(this).dialog("close");
				}
			},
			{
				text : parent.language['cancel'],
				click : function() {
					$(this).dialog("close");
				}
			}
		]
	});
}

function showResponse(response, statusText, xhr, $form) {
	$('.f_error').removeClass('f_error');
	$('.error_bubble').remove();
	
	if (response.status == 'ok') {
		document.location.href = document.location.href;
	} else {
		if (typeof response.error != 'undefined') {
			$.each(response.error, function(index, value) {
				var el = $('*[name=' + index + ']');
				el.addClass('f_error').after('<div class="error_bubble" id="bubble_' + index + '">' + value + '</div>');
			});
			positingBubbles();
		}
	}
	if (typeof response.run != 'undefined') {
		$.each(response.run, function(index, value) {
			eval(value);
		});
	}
	if (typeof response.alert != 'undefined') alert(response.alert);
	$('.progress', parent.document).hide();
}


function positingBubbles() {
	$.each($('.f_error'), function(i) {
		var el = $(this);
		var position = el.position();
		$('#bubble_' + el.attr('name')).css({
			"top": (position.top -20) + "px",
			"left": (position.left - 10 + el.width()) + "px",
		});
	});
}

$(window).resize(function() {
	positingBubbles();
});

$(function() {
	
	$('.form_validate').submit(function(){
		var valid = true;
		var form = $(this);
		$('*', form).removeClass('error');
		$('*.validate', form).each(function(i) {
			var element = $(this);
			if ((element.is('input') || element.is('select')) && element.val() == '') {
				element.addClass('error');
				valid = false;
			}
		});
		if (valid == false) {
			alert2(language['Field cannot be empty!']);
		} else {
			form.submit();
		}
		return false;
	});
	
	$('.progress', parent.document).hide();
	
});




var default_iframe = '<iframe src="http://' + http_host + '/admin/home" id="iframe_home" class="iframe" sandbox="allow-same-origin"></iframe>';
var activ_iframe = '';
var language = new Object;

function setTab(id, title) {
	activ_iframe = id;
	$('#iframe_holder .iframe').hide();
	$('#tab_holder div').removeClass('active');
	$('#main_left .content li a').removeClass('active');
	//
	if ($('#tab_' + id).length <= 0) {
		$('.progress', parent.document).show();
		// append
		var tab = '<div class="tab active" id="tab_' + id + '">\
						<span onclick="setTab(\'' + id + '\', \'\');">' + title + '</span>\
						<img src="/images/admin/iframe_close.png" class="iframe_close" onclick="closeTab(\'' + id + '\')" alt="x" />\
						<div class="clearer"></div>\
					</div>';
		var iframe = '<iframe src="http://' + http_host + '/admin/' + id + '" name="iframe_' + id + '" id="iframe_' + id + '" class="iframe"></iframe>';
		$('#tab_holder').append(tab);
		$('#iframe_holder').append(iframe);
	} else {
		$('#iframe_' + id).show();
		$('#tab_' + id).addClass('active');
	}
	$('#' + id).addClass('active');
	$('#tab_holder .refresh_but').show();
	resizeIFrame();
	
	$('#main_menu li').each(function(index) {
		if ($(this).children('a').hasClass('active')) {
			var yp = $(this).position().top - 265;
			$('#main_menu_right').scrollTop(yp);
		}
	});
}

function closeTab(id) {
	// remove
	$('#tab_' + id).remove();
	$('#iframe_' + id).remove();
	// activate last
	var first_id = $('#tab_holder div.tab').last().attr('id');
	if (first_id != undefined) {
		var cur_id = first_id.substring(4);
		setTab(cur_id, '');
	} else {
		activ_iframe = '';
		$('#iframe_home').show();
		$('#tab_holder .refresh_but').hide();
		$('#main_left .content li a').removeClass('active');
		
		$('#main_menu_right').scrollTop(0);
	}
}
/*
function refreshIFrame() {
	if (activ_iframe != '') {
		$('.progress', parent.document).show();
		$('#iframe_' + activ_iframe).attr('src', $('#iframe_' + activ_iframe).attr('src'));
	}
}
*/
function refreshIFrame() {
	if (activ_iframe != '') {
		$('.progress', parent.document).show();
		var location = $('#iframe_' + activ_iframe).contents().get(0).location.href;
		$('#iframe_' + activ_iframe).attr('src', location);
	}
}

function resizeIFrame() {
	var height = $(window).height();
	$('.iframe').css('height', height - 100 - $('#tab_holder').height());
	$('#main_menu_right').css('height', height - 220 - $('#tab_holder').height());
}

$(function() {
	$('#menu_toggle').click(function () {
		$('#main_content').toggleClass('main_hidden');
	});
	$('#user_toggle').click(function () {
		$('#user_toggle ul').toggle();
	});
	$('#main_menu a').click(function () {
		setTab($(this).attr('id'), $(this).text());
	});
	$('#tab_holder .refresh_but').click(function () {
		refreshIFrame();
	});
	
	// language select
	$('#language_select').change(function(){
		
		$.ajax({
			url: '/admin/ajax_content_language',
			type: 'POST',
			data: 'language=' + $(this).val(),
			dataType: 'json',
			error: function (data) {
				alert('Ajax error!');
			},
			success: function (response) {
				if (response == 'ok') {
					
					var first_id = $('#tab_holder div.tab').last().attr('id');
					if (first_id != undefined) $('.progress', parent.document).show();
	
					$('#iframe_holder .iframe').each(function(i) {
						$(this).attr('src', $(this).attr('src'));
					});
				}
			}
		});

	});
	
	$('#iframe_holder').html(default_iframe);
	
	resizeIFrame();
	$( window ).resize(function() {
		resizeIFrame();
	});
	
});
