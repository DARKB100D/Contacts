<?php
if($_SERVER['REQUEST_METHOD']=='POST')
{
	include_once '../functions/safemysql.class.php';
	include_once '../functions/params.php';
	include_once '../functions/log.php';
	$idTable = $_POST['idt'];
	if (!empty($_POST['val'])) $val = $_POST['val'];
	$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
	$datacol = $db->getRow("SELECT * FROM fields WHERE idTable=?i and type=1 ",$idTable);
	$col = $datacol['fieldName'];
	$tables = array("tables","fields");
	$table = $db->getRow("SELECT * FROM ?n WHERE id=?i",$tables[0],$idTable);
	$fields = $db->getAll("SELECT * FROM ?n WHERE idTable=?i",$tables[1],$idTable);	
	if (!empty($_POST['delete'])) {
		if(flog('DELETE')) {
			$ids = implode(',', $_POST['delete']);
			$db->query("DELETE FROM ?n WHERE id IN ($ids)", $table['tableName']);
		}
	} 
	else {
		include_once './checkcopy.php';
		foreach ($fields as $field) {
			if ($field['type']==0 or $field['type']==2) $d[$field['fieldName']] = $_POST[$field['fieldName']];
			if ($field['type']==3) {
				$d[$field['fieldName']] = password_hash($_POST[$field['fieldName']], PASSWORD_DEFAULT);
				$d['hash'] = 0;
			}
		}
		if (!(empty($col) or empty($val))) $d[$col] = $val;
		if (!empty($_POST['id'])) { 
			$d['id'] = $_POST['id'];
			if(flog('UPDATE')){
				if($db->query("UPDATE ?n SET ?u WHERE id = ?i", $table['tableName'], $d, $_POST['id'])){
					$js = array('message' => 'Информация обновлена!');
				}
			}
		} 
		else { 
			if($db->query("INSERT INTO ?n SET ?u", $table['tableName'], $d)) { 
				$returnId = $db->insertId();
				$js = array('message' => 'Добавлено в базу данных!','id'=>$returnId);
			}
		}
	}
	$db = NULL;
	if (isset($js)) echo json_encode($js);
}
?>