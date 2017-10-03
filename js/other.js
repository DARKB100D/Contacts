function delobj(id,idt)	{
	var content = document.getElementById("delContent");
	var line = []
	var text = '';
	for (var i = id.length - 1; i >= 0; i--) {
		line[i] = document.getElementById('l'+id[i]);
		text += '\n"' + $(("#l"+id[i])).find(".a").text()+'"';
	}
	content.innerHTML='Вы действительно хотите удалить:<pre>'+ text + '?</pre>';
	delDialog.showModal();
	$('#delDialog .delete').one('click', function() {
		$.ajax({
			url:"../modules/crud.php",
			type: 'POST',
			data: {
				idt: idt,
				delete: id
			},
			success: function(data){
				for (var i = line.length - 1; i >= 0; i--) {
					line[i].style.display = "none";
					line[i].remove();
				}
				updateHeaderSelect();
				var notification = document.querySelector('.mdl-js-snackbar');
				notification.MaterialSnackbar.showSnackbar({
					message: 'Запись успешно удалена!'
				});
				delDialog.close();
			}
		});
	});
}
$(function() {
	var $input = $("#fixed-header-drawer-exp"),
	$context = $(".sblock");
	$input.on("input", function() {
		var term = $(this).val();
		$context.show().unmark();
		if (term) {
			$context.mark(term, {
				done: function() {
					$context.not(":has(mark)").hide();
				}
			});
		}
	});
});
var dialog = document.querySelector('dialog');
if (! dialog.showModal) {
	dialogPolyfill.registerDialog(dialog);
}

var delDialog = document.querySelector('#delDialog');
if (! delDialog.showModal) {
	dialogPolyfill.registerDialog(delDialog);
}
delDialog.querySelector('.close').addEventListener('click', function() {
	delDialog.close();
});