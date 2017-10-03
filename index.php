<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Список организаций</title>
		<script type="text/javascript" src="./js/jquery-1.11.3.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/roboto.css">		
		<link rel="stylesheet" href="./css/font-awesome.min.css">
	</head>
	<body>
	<div id="content">
		<h2 align="center">Список организаций:</h2>
				<?php
					include_once './functions/safemysql.class.php';	
					include_once '/functions/params.php';
					$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
					$table = "organizations";
				?>
				<?php 
				$organizations =  $db->query("SELECT * FROM ?n ORDER BY name ASC",$table);
					foreach ($organizations as $organization) {
						echo "<button onclick=location.href='./pages/contacts.php?id=".$organization['id']."'>".$organization['name']."</button>";
					}
					$$table->free();
					$db = NULL;
				?>
		</div>
		<script type="text/javascript" src="./js/script.js"></script>
	</body>
</html>