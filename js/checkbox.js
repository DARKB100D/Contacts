var table = document.querySelector('table');
var headerCheckbox = table.querySelector('thead .mdl-data-table__select input');
var boxes = table.querySelectorAll('tbody .mdl-data-table__select');
var headerCheckHandler = function(event) {
	$(".sblock").show().unmark();
	if (event.target.checked) {
		for (var i = 0, length = boxes.length; i < length; i++) {
			boxes[i].MaterialCheckbox.check();
		}
	} else {
		for (var i = 0, length = boxes.length; i < length; i++) {
			boxes[i].MaterialCheckbox.uncheck();
		}
	}
};
headerCheckbox.addEventListener('change', headerCheckHandler);

function updateHeaderSelect() {
	boxes = table.querySelectorAll('tbody .mdl-data-table__select');
	if($(boxes).find(':checked').length > 0) {
		$('#mainHeader').hide();
		$('#counter').text($(boxes).find(':checked').length);
		$('#selectHeader').show();
	}
	else {
		$('#mainHeader').show();
		$('#selectHeader').hide();
	}
	if($(boxes).find(':checked').length == $(boxes).length ) {
		document.querySelector('thead .mdl-data-table__select').MaterialCheckbox.check();
	}else{
		document.querySelector('thead .mdl-data-table__select').MaterialCheckbox.uncheck();
	}
}
$('.mdl-data-table__select').on( "change", updateHeaderSelect);

function getChecked() {
	var idArr = $("tbody .mdl-checkbox__input:checked").map(function(i, el) { return $(el).attr("id"); }).get();
	return idArr;
}