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

function showResponse2(response, statusText, xhr, $form) {
	$('.f_error').removeClass('f_error');
	$('#info_block').text('');
	
	if (response.status == 'ok') {
		document.location.href = document.location.href;
	} else {
		if (typeof response.error != 'undefined') {
			var first_msg = '';
			$.each(response.error, function(index, value) {
				var el = $('*[name=' + index + ']');
				el.addClass('f_error');
				if (first_msg == '') first_msg = value;
				$('#info_block').text(first_msg);
			});
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