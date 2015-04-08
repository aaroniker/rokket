$(document).ready(function() {
	
	function ajaxd() { 
		$.ajax({
			type: "POST",
			url: "index.php?page=console",
			data: "",
			success: function(data) {
				$("#terminal").append(data);
				ajaxd();
			}
		});
	}
	ajaxd();

});