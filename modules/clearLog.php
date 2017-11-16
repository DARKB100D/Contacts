<?php
include_once '../functions/safemysql.class.php';
include_once '../functions/params.php';
include_once '../functions/log.php';
$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
$daysLimit = 14;
$db -> query("LOCK TABLES log WRITE");
$oldRows = $db -> getAll("SELECT * FROM log WHERE `time` < DATE_SUB(NOW(), INTERVAL ?i DAY)", $daysLimit);
if (!empty($oldRows)) {
	foreach ($oldRows as $oldRow) {
		removeDirectory($_SERVER['DOCUMENT_ROOT'].substr($oldRow['way'], 0,-1))
	}
	$db -> query("DELETE FROM log WHERE `time` < DATE_SUB(NOW(), INTERVAL ?i DAY)", $daysLimit);
	$db -> query("OPTIMIZE TABLE log");
	$db -> query("UNLOCK TABLES");
	$db = NULL;
	echo json_encode(array('message' => 'Записи старше '.$daysLimit.' дней были удалены!'));
}
else {
	echo json_encode(array('message' => 'Записи старше '.$daysLimit.' дней не найдены!'));
}
?>