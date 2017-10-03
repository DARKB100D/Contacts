function phoneCheck(inp) {
	inp.value = inp.value.replace(/[^\d]*/g, '');
	// inp.value = inp.value.replace(/[^\d+()-]*/g, '');
}
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