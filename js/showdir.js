function show_table(id) {
	var table = document.getElementsByClassName(id);
		change_display(table[0]);
	var button = document.getElementById("B" + id);
		chengeAngle(button);
}

function change_display(obj){
	if (obj.style.display == "none") obj.style.display = ""; 
			else obj.style.display = "none";
}
	
function chengeAngle(obj){
	if(obj.innerHTML == '<i class="fa fa-minus-square-o fa-lg" aria-hidden="true"></i>') obj.innerHTML = '<i class="fa fa-plus-square-o fa-lg" aria-hidden="true"></i>';
	else obj.innerHTML = '<i class="fa fa-minus-square-o fa-lg" aria-hidden="true"></i>';
}

function openWindow(){
	var newWin = window.open("./uploadpage.php", "Загрузить", "width=1200,height=700");
	//newWin.resizeTo("50","70");
	//newWin.document.close();
	//newWin.focus();
	//newWin.document.location.href = "./uploadpage.php";

}