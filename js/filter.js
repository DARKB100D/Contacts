var listCountries = $.masksSort($.masksLoad("../data/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
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
var maskState = ''; //1-short | 2-large | ''-nostage 
function stateChange(inp,state) {
	if (state !== maskState) {
		$(inp).inputmask("remove");
		$(inp).inputmasks("remove");
		if (state == 2) {
			$(inp).inputmasks($.extend(true, {}, maskOpts, {
				list: listCountries
			}));
			maskState = 2;
		}
		if (state == 1) {
			$(inp).inputmask("#-##{*}", maskOpts.inputmask);
			maskState = 1;
		}
		// console.log('maskState 2 = '+maskState);
	}
}
function phoneCheck(inp) {
	
	// console.log('value = '+value.length);
	if(inp.value) {
		var value = inp.value.replace(/[^\d]*/g, '');
		if (value.substring(0,1) == "0") {
			value="+38"+value;
			inp.value = value;
		}
		if (value.length > 3) {
			stateChange(inp,2);
		}
		else {
			// else{
				stateChange(inp,1);
			// }
		}
	}
	else {
		$(inp).inputmask("remove");
		$(inp).inputmasks("remove");
		maskState = '';
	} 
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
function card_giftcardCheck(){
}