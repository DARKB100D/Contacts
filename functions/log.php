<?php
$log="";
$way="";
function export_csv(
					$table, 		// Имя таблицы для экспорта
					//$afields, 		// Массив строк - имен полей таблицы
					$filename, 	 	// Имя CSV файла для сохранения информации
					$col,
					$val,			
					$delim=',', 		// Разделитель полей в CSV файле
					$enclosed='"', 	 	// Кавычки для содержимого полей
					$escaped='/', 	 	// Ставится перед специальными символами
					$lineend='\\r\\n'){  	// Чем заканчивать строку в файле CSV
	global $db;
	$q_export = "SELECT *".
	"   INTO OUTFILE '".$_SERVER['DOCUMENT_ROOT'].$filename."' ".
	"FIELDS TERMINATED BY '".$delim."' ENCLOSED BY '".$enclosed."' ".
	"    ESCAPED BY '".$escaped."' ".
	"LINES TERMINATED BY '".$lineend."' ".
	"FROM ".$table." WHERE ".$col." IN (".$val.")";
	if(file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) 
		unlink($_SERVER['DOCUMENT_ROOT'].$filename); 

	return $db->query($q_export);
}

function nextLog($id,$idt)
{
	if ($idt < 4) {
		global $db, $log, $way;
		$nextidt=($idt+1);
		$nextTable=$db->getRow("SELECT * FROM tables WHERE id=?i",$nextidt);
		$nextdatacol = $db->getAll("SELECT * FROM fields WHERE idTable=?i",$nextTable['id']);
		foreach ($nextdatacol as $key => $valuenextdatacol) {
			if ($valuenextdatacol['type']==1) {
				$nextCol = $valuenextdatacol['fieldName'];
			}
		}
		$query = "SELECT id FROM ?n WHERE ?n IN ($id)";
		$nextdataVal = $db->getCol($query,$nextTable['tableName'],$nextCol);
		if(!empty($nextdataVal)) { 
			$ids = implode(",", $nextdataVal);
			export_csv($nextTable['tableName'],$way.$log."_".$nextTable['tableName'].".csv",$nextCol,$id);			
			nextLog($ids,$nextidt);	
		}
	}
	return true;
}

function prevLog($id,$idt){
	if ($idt > 1) {
		global $db, $log, $way;
		$previdt=($idt-1);
		$prevTable=$db->getRow("SELECT * FROM tables WHERE id=?i",$previdt);
		$prevdatacol = $db->getAll("SELECT * FROM fields WHERE idTable=?i",$prevTable['id']);
		foreach ($prevdatacol as $key => $valueprevdatacol) {
			if ($valueprevdatacol['type']==1) {
				$prevCol = $valueprevdatacol['fieldName'];
			}
		}
		$prevdataVal = $db->getRow("SELECT * FROM ?n WHERE id IN ($id)",$prevTable['tableName']);
		$prevVal = $prevdataVal[$prevCol];
		export_csv($prevTable['tableName'],$way.$log."_".$prevTable['tableName'].".csv",'id',$id);
		prevlog($prevVal,$previdt);
	}
	return true;
}

function flog($operation) {
	global $db, $table, $log, $way;
	$tablesToLog = array("organizations","departments","workers","contacts");//or select from tables
	$id = (!empty($_POST['id'])) ? $_POST['id'] : implode(',', $_POST['delete']);
	if($table['id']>4) return 1; //for users.php
	if (!empty($operation)) {
		$time = date("Y-m-d H:i:s");
		$way="/log/".date("Y-m-d")."/";
		$h2=[];
		$h1=($table['id']>1) ? way(($table['id']+1),$h2,$id)."/ ".$table['tableShowName'] : $table['tableShowName'];
		$logs = array(
			"operation" => $operation,
			"way" => $way,
			"time" =>$time,
			"idTable" =>$table['id'],
			"h2"=> $h1,
			"idUser"=>$_COOKIE['id']
		);
		$db->query("INSERT INTO log SET ?u",$logs);
		$log = $db->insertId();
		umask(0);
		@mkdir($_SERVER['DOCUMENT_ROOT'].$way, 0777, true);
		nextLog($id,$table['id']);
		prevLog($id,($table['id']+1));
		return 1;
	} else {
		return 0;
	}
}

function way($idt,$way,$val) {
	global $db;
	$tmp = "";
	if ($idt>1) {
		$previdt=($idt-1);
		$prevTable=$db->getRow("SELECT * FROM tables WHERE id=?i",$previdt);
		$prevdatacol = $db->getAll("SELECT * FROM fields WHERE idTable=?i",$prevTable['id']);
		$d=array();
		foreach ($prevdatacol as $key => $valueprevdatacol) {
			if ($valueprevdatacol['type']==1) {
				$prevCol = $valueprevdatacol['fieldName'];
			}
			else {
				if ($valueprevdatacol['type']!=2) $d[$key]=$valueprevdatacol['fieldName'];
			}
		}
		$prevdataVal = $db->getRow("SELECT * FROM ?n WHERE id IN ($val)",$prevTable['tableName']);
		$prevVal = $prevdataVal[$prevCol];
		$fio = "";  
		foreach ($d as $dvalue) {
			$fio .= $prevdataVal[$dvalue]." ";
		}
		$way[] = $fio;
		$tmp .= way($previdt,$way,$prevVal);
	}
	else {
		for ($i=(count($way)-1); $i>1; $i--) { 
			$tmp .= $way[$i]."/ ";
		}
		$tmp .= $way[$i];
	}
	return $tmp; 
}

function removeDirectory($dir) {
	if ($objs = glob($dir."/*")) {
		foreach($objs as $obj) {
			is_dir($obj) ? removeDirectory($obj) : unlink($obj);
		}
	}
	rmdir($dir);
}
?>