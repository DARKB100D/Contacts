function logout()
{
	if(confirm("Вы действительно хотите выйти?")==true) {
		deleteCookie("id");
		deleteCookie("hash");
		document.location.href = "./auth.php";
	}
}
function deleteCookie(name) {
var date = new Date(); // Берём текущую дату
date.setTime(date.getTime() - 1); // Возвращаемся в "прошлое"
document.cookie = name += "=; path=/; expires=" + date.toGMTString(); // Устанавливаем cookie пустое значение и срок действия до прошедшего уже времени
}
