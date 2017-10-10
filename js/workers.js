var delArray=[];

function validate(form){
	if($(form).find('.is-invalid').length>0) {
		return false;
	}
	else {
		return true;
	}
}
function checkEdit (th){
	$(th).parents('form').addClass('edited');
}
function checkEmpty (th) {
	checkEdit(th);
	if (th.value=="") {
		var rem=$(th).parent().parent();
		deleteButton(rem);
	}
}
var dialog = document.querySelector('dialog');
if (! dialog.showModal) {
	dialogPolyfill.registerDialog(dialog);
}
dialog.querySelector('.close').addEventListener('click', function() {
	dialog.close();
	$("#submitForm").attr("disabled", true);
});

$(document).ready(function(){
	$(".newRowButton").click(function(){
		newRow(this.id);
	});
	$("form").keydown(function(){
		$("#submitForm").removeAttr("disabled");
	});
});

function deleteButton(th){
	var contactrow = $(th).parent().parent().parent();
	var contacts = $(th).parent().parent().parent().parent().find('.mdl-grid');
	var del = $(th).parents('form').serializeArray();
	if(contacts.length>4) {
		if(contactrow.index()){
			contactrow.remove(); 
		} else {
			var text = contactrow.parent().find('.mdl-grid:eq(0)').find('.mdl-cell:eq(0)').html();
			contactrow.parent().find('.mdl-grid:eq(2)').find('.mdl-cell:eq(0)').html(text);
			contactrow.remove(); 
		}
	} 
	else {
		var parent = $(th).parent();
		var input = parent.find('input');
		for (var i = input.length - 1; i >= 0; i--) {
			input[i].parentNode.MaterialTextfield.change('');
		}
	}

	if(del[3]['value']!=="" && del[0]['value']!=="") {
		window.delArray.push(del[3]['value']);
	}
	$("#submitForm").removeAttr("disabled");
}

function newRow(id){
	var content = document.getElementById("newRow"+id);
	$.ajax({
		url:"../modules/rowGenerator.php",
		type: 'POST',
		data: {
			id: id
		},
		success: function(data){
			$(data).insertBefore(content);
			componentHandler.upgradeAllRegistered();
			var focuselement = document.getElementById('onf');
			focuselement.focus();
			focuselement.removeAttribute('id');
		}
	});
}

function submitForms(idWorker) {
	if (idWorker===undefined) {
		if (validate('#workerForm.edited') && validate('.contactForm.edited')){
			var workerFormData = $('#workerForm.edited').serialize();
			if (workerFormData !== "") {
				$.ajax({
					dataType: "json",
					url:"../modules/crud.php",
					type: 'POST',
					data: workerFormData,
					success: function(data) {
						$.each(data, function(key, val) {
							if(key == 'message') {
								id = data['id'];
								var contactFormData = $('.contactForm.edited').serializeArray();									
								massToAjax={};
								for(var i=0; i<contactFormData.length; i+=4) {
									massToAjax[i]={};
									for(var j=0; j<4; j++) {
										massToAjax[i][contactFormData[i+j]['name']] = contactFormData[i+j]['value'];
									}
									massToAjax[i]['val']=id;
									massToAjax[i]['idt']=4;
								}								
								for(var i=0; i<contactFormData.length; i+=4) {
									if(massToAjax[i]['value']!=="")
									{									
										$.ajax({
											dataType: "json",
											url:"../modules/crud.php",
											type: 'POST',
											data: massToAjax[i],
											success: function(data) {
												$.each(data, function(key, val) {
													if(key == 'message'){
														var notification = document.querySelector('.mdl-js-snackbar');
														notification.MaterialSnackbar.showSnackbar(
														{
															message: val
														}
														);
													}
												});
											}
										});
									}
								}
								var notification = document.querySelector('.mdl-js-snackbar');
								notification.MaterialSnackbar.showSnackbar(
								{
									message: val
								}
								);

							}
						});
					}
				});
			}
		}
	}
	else {
		id=idWorker;				
		if (validate('#workerForm.edited') && validate('.contactForm.edited')) {
			var workerFormData = $('#workerForm.edited').serialize();
			if (workerFormData !== ""){
				$.ajax({
					dataType: "json",
					url:"../modules/crud.php",
					type: 'POST',
					data: workerFormData,
					success: function(data) {
						$.each(data, function(key, val) {
							if(key == 'message') {
								var notification = document.querySelector('.mdl-js-snackbar');
								notification.MaterialSnackbar.showSnackbar(
								{
									message: val
								}
								);
							}
						});
					}
				});
			}
			var contactFormData = $('.contactForm.edited').serializeArray();				
			massToAjax={};
			for(var i=0; i<contactFormData.length; i+=4) {
				massToAjax[i]={};
				for(var j=0; j<4; j++) {
					massToAjax[i][contactFormData[i+j]['name']] = contactFormData[i+j]['value'];
				}
				massToAjax[i]['val']=id;
				massToAjax[i]['idt']=4;
			}
			for(var i=0; i<contactFormData.length; i+=4) {
				if(massToAjax[i]['value']!=="")
				{
					$.ajax({
						dataType: "json",
						url:"../modules/crud.php",
						type: 'POST',
						data: massToAjax[i],
						success: function(data) {
							$.each(data, function(key, val) {
								if(key == 'message'){
									var notification = document.querySelector('.mdl-js-snackbar');
									notification.MaterialSnackbar.showSnackbar(
									{
										message: val
									}
									);
								}
							});
						}
					});
				}
			}	
		}
	}
	if (typeof delArray !== 'undefined' && delArray.length > 0) {
		$.ajax({
			dataType: "json",
			url:"../modules/crud.php",
			type: 'POST',
			data: {
				delete:delArray,
				idt:4
			},
			success: function(data) {
				$.each(data, function(key, val) {
					if(key == 'message'){
						var notification = document.querySelector('.mdl-js-snackbar');
						notification.MaterialSnackbar.showSnackbar(
						{
							message: val
						}
						);
					}
				});
			}
		});
	}
	dialog.close();
	setTimeout('location.reload()', 1000);
}