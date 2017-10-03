<?php
$data{$table['tableName']} = (empty($col) or empty($val)) ? $db->query("SELECT * FROM ?n",$table['tableName']) : $db->query("SELECT * FROM ?n WHERE ?n=?i",$table['tableName'],$col,$val);
foreach ($data{$table['tableName']} as $value{$table['tableName']}) {
	if ($idTable == 1) {
		if ($value{$table['tableName']}['name']==$_POST['name'] && $value{$table['tableName']}['id']!=$_POST['id']) {
			$js = array('error' => 'Организация с таким именем существует!',
				'id' => $value{$table['tableName']}['id']);
			echo json_encode($js);
			exit();
		} 
	}
	if ($idTable == 2) {
		if ($value{$table['tableName']}['name']==$_POST['name'] && $value{$table['tableName']}[$col]==$val && $value{$table['tableName']}['id']!=$_POST['id']) {
			$js = array('error' => 'Отдел с таким именем существует!','id' => $value{$table['tableName']}['id']);
			echo json_encode($js);
			exit();
		} 
	}
	if ($idTable == 3) {
		if ($value{$table['tableName']}['name']==$_POST['name'] && $value{$table['tableName']}['surname']==$_POST['surname'] && $value{$table['tableName']}['middlename']==$_POST['middlename'] && $value{$table['tableName']}[$col]==$val && $value{$table['tableName']}['id']!=$_POST['id']) {
			$js = array('error' => 'Сотрудник существует!','id' => $value{$table['tableName']}['id']);
			echo json_encode($js);
			exit();
		} 
	}
	if ($idTable == 4) {
		if ($value{$table['tableName']}['value']==$_POST['value'] && $value{$table['tableName']}['idType']==$_POST['idType'] && $value{$table['tableName']}['id']!=$_POST['id']) {
			$js = array('error' => 'Контактная информация существует!','id' => $value{$table['tableName']}['id']);
			echo json_encode($js);
			exit();
		} 
	}
	if ($idTable == 5) {
		if ($value{$table['tableName']}['login']==$_POST['login'] && $value{$table['tableName']}['id']!=$_POST['id']) {
			$js = array('error' => 'Логин занят!','id' => $value{$table['tableName']}['id']);
			echo json_encode($js);
			exit();
		} 
	}
}
?>