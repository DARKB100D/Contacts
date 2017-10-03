<?php
include_once '../functions/safemysql.class.php';
include_once '../functions/params.php';
$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
if (isset($_POST['id'])) {
	foreach ($_POST['id'] as $id) {
		$log=$db->getRow("SELECT * FROM log WHERE id=?i",$id);
		$tables = $db->getAll("SELECT * FROM tables");
		foreach ($tables as $tvalue) {
			if ($tvalue['id']==$log['idTable']) $table = $tvalue;
		}
		foreach ($tables as $val) {
			import_csv($val['tableName'],$log['way'].$log['id']."_".$val['tableName'].".csv");
		}
		$db->query("DELETE FROM log WHERE id=?i",$log['id']);
	}
}

function import_csv(
		$table, 		// Имя таблицы для импорта
		// $afields, 		// Массив строк - имен полей таблицы
		$filename, 	 	// Имя CSV файла, откуда берется информация (путь от корня web-сервера)
		$delim=',',  		// Разделитель полей в CSV файле
		$enclosed='"',  	// Кавычки для содержимого полей
		$escaped='/', 	 	// Ставится перед специальными символами
		$lineend='\\r\\n',   	// Чем заканчивается строка в файле CSV
		$hasheader=FALSE){  	// Пропускать ли заголовок CSV
	global $db;
	$ok = FALSE;
	$db->query("CREATE TEMPORARY TABLE temporary_table LIKE ?n",$table);
	if($hasheader) $ignore = "IGNORE 1 LINES ";
	else $ignore = "";
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) {
		$columns = $db->query("SHOW COLUMNS FROM ?n",$table);
		$fields = [];
		$afields = [];
		foreach ($columns as $value) {
			$fields[]="`".$table."`.`".$value['Field']."`=`temporary_table`.`".$value['Field']."`";
			$afields[]=$value['Field'];
		}
		$q_load = 
		"LOAD DATA INFILE '".
		$_SERVER['DOCUMENT_ROOT'].$filename."' REPLACE INTO TABLE temporary_table ".
		"CHARACTER SET UTF8 
		FIELDS TERMINATED BY '".$delim."' 
		ENCLOSED BY '".$enclosed."' ".
		"ESCAPED BY '".$escaped."' ".
		"LINES TERMINATED BY '".$lineend."' ".
		$ignore.
		"(".implode(',', $afields).")";

		$q_insert = "INSERT INTO ?n SELECT * FROM temporary_table ON DUPLICATE KEY UPDATE ".implode(',', $fields);
		// $q_insert = "INSERT INTO ?n (".implode(',', $afields).") SELECT * FROM temporary_table ON DUPLICATE KEY UPDATE ".implode(',', $fields); 
		if($db->query($q_load) && $db->query($q_insert,$table)) {
			unlink($_SERVER['DOCUMENT_ROOT'].$filename);
			$ok=true;
		}
	}
	$db->query("DROP TEMPORARY TABLE temporary_table");
	return $ok;				
}			
?>
<?php
$db = NULL;
?>
