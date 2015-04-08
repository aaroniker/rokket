$(document).ready(function() {
	
	function ajaxd() { 
		$.ajax({
			type: "POST",
			url: "index.php",
			data: { page: "console" },
			success: function(data) {
				$("#terminal").append(data);
				ajaxd();
			}
		});
	}
	ajaxd();

});