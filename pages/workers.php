<?php
include_once '../functions/safemysql.class.php';	
include_once '../functions/params.php';
include_once '../functions/phones.php';
$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
$table = array("organizations","departments","workers","contacts","types");
$val = $_GET['val'];
if(!empty($_GET['id'])) {
	$id = $_GET['id'];
	$worker = $db->getRow("SELECT * FROM ?n WHERE id=?i", $table[2], $id);
}
$previdt=3;
$prevTable=$db->getRow("SELECT * FROM tables WHERE id=?i",$previdt);
$prevdatacol = $db->getRow("SELECT * FROM fields WHERE idTable=?i and type=1 ",$prevTable['id']);
$prevCol = $prevdatacol['fieldName'];
$prevHref = "?idt=".$previdt."&val=".$val;
?>
<style type="text/css">
.deleteButton {
	position: absolute;
	right: 40px;
	top:30px;
	color: white;
}
.newRowButton {
	position: absolute;
	right: 15px;
	bottom: 35px;
}
</style>
<?php 
$department = $db->getRow("SELECT * FROM departments WHERE id=?i",$val);
$organization = $db->getRow("SELECT * FROM organizations WHERE id=?i",$department['idOrganization']);
?>
<div class="mdl-dialog__title"></div>
<div class="">
	<form class="mdl-grid <?php if(!isset($id)) echo 'edited'; ?>" id="workerForm" name="workerForm" onsubmit="return false;" method="post">
		<div class="mdl-cell">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
				<input class="mdl-textfield__input" type="text" id="onf" name="surname" value="<?php if(isset($worker)) echo $worker['surname']?>" onkeyup="checkEdit(this)">
				<label class="mdl-textfield__label" for="surname">Фамилия</label>
			</div>
		</div>
		<div class="mdl-cell">
			<div class="mdl-textfield mdl-js-textfield  mdl-textfield--floating-label mdl-textfield--full-width">
				<input class="mdl-textfield__input" type="text" id="name" name="name" value="<?php if(isset($worker)) echo $worker['name']?>" onkeyup="checkEdit(this)">
				<label class="mdl-textfield__label" for="name">Имя</label>
			</div>
		</div>
		<div class="mdl-cell">
			<div class="mdl-textfield mdl-js-textfield  mdl-textfield--floating-label mdl-textfield--full-width">
				<input class="mdl-textfield__input" type="text" id="middlename" name="middlename" value="<?php if(isset($worker)) echo $worker['middlename']?>" onkeyup="checkEdit(this)">
				<label class="mdl-textfield__label" for="middlename">Отчество</label>
			</div>
		</div>
		<div class="mdl-cell">
			<div class="mdl-textfield mdl-js-textfield  mdl-textfield--floating-label mdl-textfield--full-width">
				<input class="mdl-textfield__input" type="text" id="role" name="role" value="<?php if(isset($worker)) echo $worker['role'] ?>" onkeyup="checkEdit(this)">
				<label class="mdl-textfield__label" for="role">Должность</label>
			</div>
		</div>

		<div class="mdl-cell">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
				<input disabled class="mdl-textfield__input" type="text" id="organization" value="<?php echo $organization['name']?>">
				<label class="mdl-textfield__label" for="organization">Организация</label>
			</div>
		</div>
		<div class="mdl-cell">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
				<input disabled class="mdl-textfield__input" type="text" id="department" value="<?php echo $department['name']?>">
				<label class="mdl-textfield__label" for="department">Отдел</label>
			</div>
		</div>
		<input hidden type="text" name="idt" value="3">
		<input hidden type="text" name="id" value="<?php if(isset($id)) echo $id?>">
		<input hidden type="text" name="val" value="<?php echo $val?>">
	</form> 
	<?php 
	if(isset($worker)) $contacts = $db->query("SELECT * FROM ?n WHERE idWorker=?i",$table[3],$worker['id']);
	$types = $db->query("SELECT * FROM ?n",$table[4]);
	foreach ($types as $type) {
		echo "<div class='mdl-grid mdl-grid--type mdl-grid--no-spacing'>";
		$icon = true;
		$typeisshow = false;
		if(isset($contacts)){
			foreach ($contacts as $contact) {
				if ($contact['idType']==2) {
					$conValue = phone($contact['value']);
					$conValue = (strlen($conValue) > 5) ? "+".$conValue : $conValue;
				} else {
					$conValue = $contact['value'];
				}
				if ($type['id']==$contact['idType']) {
					echo "<form class='mdl-grid mdl-grid--no-spacing contactForm' onsubmit='return false;'>";
					if($icon==true) { 
						echo "<div class='mdl-cell mdl-cell--2-col mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-typography--text-center mdl-cell--middle'><i class='material-icons' style=color:grey>".$type['icon'].'</i></div>';

						echo "<div class='mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone mdl-typography--text-center'>";
						print '<div class="mdl-grid mdl-grid--field">';
						print '<div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--4-col-phone">';
						print '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">';
						print '<input class="mdl-textfield__input" type="'.$type['icon'].'" id="'.$contact['id'].'" name="value" value="'.$conValue.'" onkeydown="'.$type['icon'].'Check(this)" onfocus="'.$type['icon'].'Check(this)" onblur="checkEmpty(this)" >';
						print '<label class="mdl-textfield__label" for="'.$contact['id'].'">'.$type['name'].'</label>';
						print '</div>';
						print '</div>';
						$icon = false;
					}
					else {
						echo "<div class='mdl-cell mdl-cell--2-col mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-typography--text-center mdl-cell--middle'></div>";

						echo "<div class='mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone mdl-typography--text-center'>";
						print '<div class="mdl-grid mdl-grid--field">';
						print '<div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--4-col-phone">';
						print '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label  mdl-textfield--full-width">';
						print '<input class="mdl-textfield__input" type="'.$type['icon'].'" name="value" id="'.$contact['id'].'" value="'.$conValue.'" onblur="checkEmpty(this)" onfocus="'.$type['icon'].'Check(this)" onkeydown="'.$type['icon'].'Check(this)">';
						print '<label class="mdl-textfield__label" for="'.$contact['id'].'">'.$type['name'].'</label>';
						print '</div>';
						print '</div>';											
					}
					print '<div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--4-col-phone">';
					print '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">';
					print '<input class="mdl-textfield__input" type="text" id="'.$contact['id'].'" name="type" value="'.$contact['type'].'" onblur="checkEdit(this)" >';
					print '<label class="mdl-textfield__label" for="'.$contact['id'].'">Примечание</label>';
					echo "<input hidden name='idType' value='".$type['id']."'>";
					echo "<input hidden name='id' value='".$contact['id']."'>";
					print '</div>';
					print '</div>';
					print '<button class="mdl-button mdl-js-button mdl-button--icon deleteButton" onclick="deleteButton(this)"><i class="material-icons">delete</i></button>';
					print '</div>';
					echo "</div>";
					echo "</form>";
					$typeisshow = true;
				}									
			}
		}
		if (!$typeisshow) {
			print '<form class="mdl-grid mdl-grid--no-spacing contactForm" onsubmit="return false;">';
			echo "<div class='mdl-cell mdl-cell--2-col mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-typography--text-center mdl-cell--middle'><i class='material-icons' style=color:grey>".$type['icon']."</i></div>";
			echo "<div class='mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone mdl-typography--text-center'>";
			print '<div class="mdl-grid mdl-grid--field">';
			print '<div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--4-col-phone">';
			print '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label  mdl-textfield--full-width">';
			print '<input class="mdl-textfield__input" name="value" type="'.$type['icon'].'" onkeydown="'.$type['icon'].'Check(this)" onblur="checkEmpty(this)">';
			print '<label class="mdl-textfield__label">'.$type['name'].'</label>';
			print '</div>';
			print '</div>';
			print '<div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--4-col-phone">';
			print '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label  mdl-textfield--full-width">';
			print '<input class="mdl-textfield__input" name="type" type="text">';
			print '<label class="mdl-textfield__label">Примечание</label>';
			echo "<input hidden name='idType' value='".$type['id']."'>";
			echo "<input hidden name='id' value=''>";
			print '</div>';
			print '</div>';
			print '<button class="mdl-button mdl-js-button mdl-button--icon deleteButton" onclick=deleteButton(this)><i class="material-icons">delete</i></button>';
			print '</div>';
			print '</div>';
			echo "</form>";
		}
		print '<div class="mdl-grid mdl-grid--no-spacing" id="newRow'.$type['id'].'"></div>';
		print '<button class="mdl-button mdl-js-button mdl-button--colored mdl-button--icon newRowButton" id='.$type['id'].'><i class="material-icons">add</i></button>';
		echo "</div>";
	}
	?>
</div>
<div class="mdl-dialog__actions">
	<button disabled id="submitForm" class="mdl-button" onclick="submitForms(<?php if(!empty($id)) echo $id ?>)">Сохранить</button>
	<button type="button" class="mdl-button close">Отмена</button>
</div>
<?php 
foreach ($table as $tablename) {
	if(isset($$tablename)) $$tablename->free();
}
$db = NULL;
?>