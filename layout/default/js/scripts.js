$(document).ready(function() {
	
	$('select').each(function(){
		var $this = $(this), numberOfOptions = $(this).children('option').length;
	  
		$this.addClass('select-hidden'); 
		$this.wrap('<div class="select"></div>');
		$this.after('<div class="select-styled"></div>');
	
		var $styledSelect = $this.next('div.select-styled');
		$styledSelect.text($this.children('option').eq(0).text());
	  
		var $list = $('<ul />', {
			'class': 'select-options'
		}).insertAfter($styledSelect);
	  
		for (var i = 0; i < numberOfOptions; i++) {
			$('<li />', {
				text: $this.children('option').eq(i).text(),
				rel: $this.children('option').eq(i).val()
			}).appendTo($list);
		}
	  
		var $listItems = $list.children('li');
	  
		$styledSelect.click(function(e) {
			e.stopPropagation();
			$('div.select-styled.active').each(function(){
				$(this).removeClass('active').next('ul.select-options').hide();
			});
			$(this).toggleClass('active').next('ul.select-options').toggle();
		});
	  
		$listItems.click(function(e) {
			e.stopPropagation();
			$styledSelect.text($(this).text()).removeClass('active');
			$this.val($(this).attr('rel'));
			$list.hide();
		});
	  
		$(document).click(function() {
			$styledSelect.removeClass('active');
			$list.hide();
		});
	});
	
	$('.circle').each(function(){
	
		cur = $(this);
		var svgObj = cur.find('svg');
		var perObj = cur.find('div');
		
		var color = cur.data('color');
		
		var curWidth = cur.width();
		var center = curWidth / 2;
		var radius = curWidth * 0.8 / 2;
		var start = center - radius;
		
		var per = perObj.text().replace("%","") / 100;
		
		var svg = Snap(svgObj.get(0));
		var arc = svg.path("");
		var circle = svg.circle(curWidth / 2, curWidth / 2, radius);
		
		circle.attr({
			stroke: "#" + color,
			fill: 'none',
			strokeWidth: 8
		});
		
		perObj.text('');
		
		var stat = {
			center: center,
			radius: radius,
			start: start,
			svgObj: svgObj,
			per: per,
			svg: svg,
			arc: arc,
			circle: circle
		};
		
		animate(stat);
	
	});
	
	function animate(stat) {
	
		var endpoint = stat.per * 360;
		
		var colorActive = stat.svgObj.data('color');
		
		Snap.animate(0, endpoint, function(val) {
		
			stat.arc.remove();
			
			var curPer = Math.round(val / 360 * 100);
			
			if(curPer == 100) {
			
				stat.circle.attr({
					stroke: "#" + colorActive
				});
			
			} else {
			
				var d = val;
				var dr = d - 90;
				var radians = Math.PI * (dr) / 180;
				var endx = stat.center + stat.radius * Math.cos(radians);
				var endy = stat.center + stat.radius * Math.sin(radians);
				var largeArc = d > 180 ? 1 : 0;  
				var path = "M"+stat.center+","+stat.start+" A"+stat.radius+","+stat.radius+" 0 "+largeArc+",1 "+endx+","+endy;
				
				stat.arc = stat.svg.path(path);
				
				stat.arc.attr({
					stroke: "#" + colorActive,
					fill: 'none',
					strokeWidth: 8
				});
			
			}
			
			stat.svgObj.prev().html(curPer +'%');
		
		}, 1100, mina.easeinout);
	
	}
	
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
		$('.message.close').slideUp(200);
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