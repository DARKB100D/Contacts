<?php
if (isset($_POST['idt'])) {
	include_once '../functions/safemysql.class.php';
	include_once '../functions/params.php';
	$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
	$tables = array("tables","fields","types");
	$table = $db->getRow("SELECT * FROM ?n WHERE id=?i",$tables[0],$_POST['idt']);
	$fields = $db->query("SELECT * FROM ?n WHERE idTable=?i",$tables[1],$_POST['idt']);
	if (isset($_POST['id'])) $data{$table['tableName']} = $db->getRow("SELECT * FROM ?n WHERE id=?i",$table['tableName'],$_POST['id']);
	?>
	<form id="form" method="post">	
		<div class="mdl-dialog__title"></div>
		<div class="mdl-dialog__content">
			<?php
			foreach ($fields as $field)	{
				if($field['type']==0) {
					if (isset($_POST['id'])) {
						echo '
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
							<input class="mdl-textfield__input" type="text" name="'.$field['fieldName'].'" value="'.htmlspecialchars($data{$table['tableName']}[$field['fieldName']]).'" onkeyup="names(this)" id="onf">
							<label class="mdl-textfield__label" for="'.$field['fieldName'].'">'.$field['fieldShowName'].'</label>
						</div>';
					}
					else {
						echo '
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
							<input class="mdl-textfield__input" type="text" name="'.$field['fieldName'].'" value="" onkeyup="names(this)" id="onf" >
							<label class="mdl-textfield__label" for="surname">'.$field['fieldShowName'].'</label>
						</div>';
					}
				}
				if($field['type']==3) {
					echo '
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
						<input class="mdl-textfield__input" type="password" name="'.$field['fieldName'].'" value="" onkeyup="names(this)" id="onf" >
						<label class="mdl-textfield__label" for="surname">'.$field['fieldShowName'].'</label>
					</div>';
				}
			}
			echo "<input type='hidden' name='idt' value=".$_POST['idt'].">";
			if (isset($_POST['id'])) echo "<input type='hidden' name='id' value=".$_POST['id'].">";
			if (isset($_POST['val'])) echo "<input type='hidden' name='val' value=".$_POST['val'].">";
			?>
		</div>
		<div class="mdl-dialog__actions">
			<button disabled id="submitForm" class="mdl-button" type="submit">Сохранить</button>
			<button type="button" class="mdl-button close">Отмена</button>
		</div>
	</form>
	<?php
	$fields->free();
	$db = NULL; 
}
?>