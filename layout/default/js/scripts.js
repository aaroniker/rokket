$(document).ready(function() {
	
	sweetAlertInitialize();
	
	$('table tbody tr').click(function() {
		$(this).toggleClass('active');
		$(this).find('input[type=checkbox]').prop("checked", !$(this).find('input[type=checkbox]').prop("checked"));
	});
	
	$('table tbody input[type=checkbox]').change(function() {
		$(this).parent().parent().toggleClass('active');
	});
	
	$('table thead input[type=checkbox]').change(function() {
		var table = $(this).parent().parent().parent().parent();
		var state = $(this).prop("checked");
		
		if(state) {
			table.children('tbody').children('tr').each(function() {
				$(this).addClass('active');
				$(this).find('input[type=checkbox]').prop("checked", state);
			});
		} else {
			table.children('tbody').children('tr').each(function() {
				$(this).removeClass('active');
				$(this).find('input[type=checkbox]').prop("checked", state);
			});
		}
			
	});
	
	$('.tabs').tabs({
		show: {
			effect: "fade",
			duration: 300
		},
		activate: function(event, ui) {
            window.location.hash = ui.newPanel.attr('id');
        }
	});

	setTimeout(function() {
		$('.message').slideUp(200);
	}, 3000 );
	
	$("#rights > ul > li").draggable({
		appendTo: "body",
		helper: "clone"
	});
	
	$("#rights ul.box").droppable({
		activeClass: "helperclass",
		hoverClass: "ui-state-hover",
		accept: ":not(.ui-sortable-helper)",
		drop: function(event, ui) {
			$(this).find(".placeholder").remove();
			$("<li></li>").html(ui.draggable.text() + '<span class="close">x</span>').appendTo(this).data('action', ui.draggable.data('action'));
		},
		accept: function (elm) {
        	if ($(this).find('li[data-action="' + elm.data('action') + '"]').length == 0)
        	    return true;
        	return false;
    	}
	});
	
	$("#rights").on('click', '.close', function () {
		$(this).parent().slideUp(200, function() { $(this).remove(); } );
	});
	
	$('#userForm').submit(function() {
		var result = {};
		$('#rights ul.box').each(function() {
			var type = $(this).data('type');
        	var elements = [];
        	$(this).find('li').each(function () {
        	    elements.push($(this).data('action'));
        	});
        	result[type] = elements
		});
		$("#inputRights").val(JSON.stringify(result));
	});
	
});