var listCountries = $.masksSort($.masksLoad("../data/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
// var listRU = $.masksSort($.masksLoad("http://cdn.rawgit.com/andr-04/inputmask-multi/master/data/phones-ru.json"), ['#'], /[0-9]|#/, "mask");
var maskOpts = {
	inputmask: {
		definitions: {
			'#': {
				validator: "[0-9]",
				cardinality: 1
			}
		},
		showMaskOnHover: false,
		autoUnmask: true,
		clearMaskOnLostFocus: false
	},
	match: /[0-9]/,
	replace: '#',
	listKey: "mask"
};

var maskChangeWorld = function(maskObj, determined) {
	if (determined) {
		var hint = maskObj.name_ru;
		if (maskObj.desc_ru && maskObj.desc_ru != "") {
			hint += " (" + maskObj.desc_ru + ")";
		}
	}
}

function phoneCheck(inp) {
	value = inp.value;
	// if (inp!==undefined) 
	$(inp).inputmask("remove");
	if(value) {
		value = value.replace(/[^\d]*/g, '');
		if (value.substring(0,1) == "0") {
			value="+38"+value;
			inp.value = value;
			$(inp).inputmasks($.extend(true, {}, maskOpts, {
				list: listCountries,
				onMaskChange: maskChangeWorld
			}));
		}
		else {		
			if (value.length <= 3) {
				$(inp).inputmask("[#-##{*}", maskOpts.inputmask);
			}
			else {
				if (value.length > 4){
					$(inp).inputmasks($.extend(true, {}, maskOpts, {
						list: listCountries,
						onMaskChange: maskChangeWorld
					}));
				}
				else {
					$(inp).inputmask("[##-##{*}", maskOpts.inputmask);
				}
			} 
		}
		// if (value.length > 4){
		// 	$(inp).inputmasks($.extend(true, {}, maskOpts, {
		// 		list: listCountries,
		// 		onMaskChange: maskChangeWorld
		// 	}));
		// } 
		// else {
		// 	if (value.length < 3) {
		// 		$(inp).inputmask("[#-##", maskOpts.inputmask);
		// 	} 
		// 	else {
		// 		$(inp).inputmask("[##-##{*}", maskOpts.inputmask);
		// 	}
		// }
	}
	// else {
	// 	$(inp).inputmask("remove");
	// }
}
// function dnr_number(inp){
// 	var arr = ["050","095","066","099","071","062",];
// 	for (var i = arr.length - 1; i >= 0; i--) {
// 		if (value.substring(0,3) == arr[i]) return true;
// 	}
// }
function emailCheck(inp) {
	inp.value = inp.value.replace(/\s*/g,'');
	// validate(inp);
	// function validateEmail(email) {
	// 	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	// 	return re.test(email);
	// }
	// function validate(inp) {
	// 	var email = inp.value;
	// 	if (validateEmail(email)) {
	// 		// $("#result").text(email + " is valid :)");
	// 		// $(inp).css("border-color", "green");
	// 		$(inp).css("color", "");
	// 		$("#submitForm").removeAttr("disabled");
	// 	} else {
	// 		// $("#result").text(email + " is not valid :(");
	// 		// $(inp).css("border-color", "red");
	// 		$(inp).css("color", "red");
	// 		$("#submitForm").attr("disabled", true);
	// 	}
	// 	return false;
	// }
	// $("#validate").bind("click", validate);
}
function names(inp) {
	// inp.value = inp.value.replace(/\s*/g,'')
	// .replace(/@"#!\*\|\\%*/g,'');
}
function location_onCheck() {
	
}