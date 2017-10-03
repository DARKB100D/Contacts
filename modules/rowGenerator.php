<?php
if (isset($_POST['id'])) {
	include_once '../functions/safemysql.class.php';
	include_once '../functions/params.php';
	$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
	$type = $db->getRow("SELECT * FROM types WHERE id=?i", $_POST['id']);
	print '<form class="mdl-grid mdl-grid--no-spacing contactForm" onsubmit="return false;">';
	echo "<div class='mdl-cell mdl-cell--2-col mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-typography--text-center mdl-cell--middle'></div>";
	echo "<div class='mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone mdl-typography--text-center'>";
	print '<div class="mdl-grid mdl-grid--field">';
	print '<div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--4-col-phone">';
	print '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">';
	print '<input class="mdl-textfield__input" type="'.$type['icon'].'" onblur="checkEmpty(this)" name="value" id="onf" onkeyup="'.$type['icon'].'Check(this)" ">';
	print '<label class="mdl-textfield__label">'.$type['name'].'</label>';
	print '</div>';
	print '</div>';
	print '<div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--4-col-phone">';
	print '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">';
	print '<input class="mdl-textfield__input" type="text" name="type">';
	print '<label class="mdl-textfield__label">Примечание</label>';
	echo "<input hidden name='idType' value='".$type['id']."'>";
	echo "<input hidden name='id' value=''>";
	print '</div>';
	print '</div>';
	print '<button class="mdl-button mdl-js-button mdl-button--icon deleteButton" onclick=deleteButton(this)><i class="material-icons">delete</i></button>';
	print '</div>';
	print '</div>';
	echo "</form>";
	$db = NULL;
}
?>