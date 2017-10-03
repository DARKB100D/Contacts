dialog.querySelector('.close').addEventListener('click', function() {
	dialog.close();
	$("#submitForm").attr("disabled", true);
});
$(document).ready(function() {
	$("#form").keydown(function(){
		$("#submitForm").removeAttr("disabled");
	});
	$("#form").submit(function() {
		var form_data = $(this).serialize();
		$.ajax({
			dataType: "json",
			url:"../modules/crud.php",
			type: 'POST',
			data: form_data,
			success: function(data) {
				$.each(data, function(key, val) {
					if(key == 'message'){
						var notification = document.querySelector('.mdl-js-snackbar');
						notification.MaterialSnackbar.showSnackbar(
						{
							message: val
						}
						);
						var dialog = document.querySelector('dialog');
						dialog.close();
						setTimeout('location.reload()', 500);
					}
					if(key == 'error') {
						var notification = document.querySelector('.mdl-js-snackbar');
						notification.MaterialSnackbar.showSnackbar(
						{
							message: val
						}
						);
						var dialog = document.querySelector('dialog');
						dialog.close();
						$.each(data, function(key, val) {
							if(key == 'id'){
								var line = document.getElementById('l'+val);
								line.querySelector('.mdl-data-table__select').MaterialCheckbox.check()
							}
						});
						updateHeaderSelect();
					}
				});
			}
		});
		event.preventDefault();
	});
});