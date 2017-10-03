<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Контактная информация</title>
		<script type="text/javascript" src="./js/jquery-1.11.3.js"></script>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/roboto.css">		
		<link rel="stylesheet" href="../css/font-awesome.min.css">
	</head>
	<body>
	<div class="sidebar">
		<button onclick="location.href='../index.php'"><i class="fa fa-angle-left fa-4x" aria-hidden="true"></i></button>
	</div>
	<div id="content">
		<?php
			include_once '../functions/safemysql.class.php';	
			include_once '../functions/params.php';
			$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
			$table = array("organizations","departments","workers","contacts","types");
			
			if(isset($_GET['id'])){
				$idOrganization = $_GET['id'];
				$organizations = $db ->query("SELECT * FROM ?n WHERE id=?i",$table[0],$idOrganization);
				$rowCount = $organizations->num_rows;

				if ($rowCount >= 1) { ?>
					<?php  $organization = $organizations->fetch_array()?>
					<h1 align="center">Контактная информация <?php echo $organization['name']; ?></h1>
					<input type="text" id="searchInput" onkeyup="search()" placeholder="Поиск..." title="Поиск" class="form-control">
				
					<?php 
						$departments = $db ->query("SELECT * FROM ?n WHERE idOrganization=?i or idOrganization = 0 ORDER BY name ASC",$table[1],$idOrganization);
						foreach ($departments as $depatment) {
									echo "<h2>".$depatment['name']."</h2>";
							 ?>
							
							<table id="table" class="table" align="center">
								<tr class="header">
									<th style="width:60%;" onclick="sortTable(0)">ФИО</th>
									<th style="width:40%;" onclick="sortTable(1)">Контактная информация</th>
								</tr>
							

								<?php 
								$workers = $db->query("SELECT * FROM ?n WHERE idOrganization=?i AND idDepartment=?i  ORDER BY surname ASC", $table[2], $idOrganization, $depatment['id']);
								
								foreach ($workers as $worker) { ?>
								<tr class="tr">
									<td><?php echo $worker['surname']." ".$worker['name']." ".$worker['middlename'];?></td>
										<?php $contacts = $db->query("SELECT * FROM ?n WHERE idWorker=?i",$table[3],$worker['id']);
										?>
									<td>
										<table align="left" >
											<?php foreach ($contacts as $contact) { 
													$types = $db->query("SELECT * FROM ?n WHERE id=?i",$table[4],$contact['idType']);
														foreach ($types as $type ) {
													?>
														<tr>
															<td><?php echo $type['name'].": </td><td><input align=right id=i".$contact['id']." value=".$contact['value']." ></td><td><button id='i-have-a-tooltip' data-description='copy' onclick=copy(".$contact['id'].") id=b".$contact['id'].">"?> 
																<i class="fa fa-clone fa-lg" aria-hidden="true"></i></button>
															</td>
														</tr>
											<?php }}?>
										</table>
									</td>
								<?php }?>
							</table>
						<?php }?>




				<?php
					
				}
				else {
					echo "<h2>Организации с таким id не существует!</h2>";
					header("Location: ../index.php");
				}
			}
			else {
				echo "<h2>Невозможно отобразить страницу!</h2>";
				header("Location: ../index.php");
				
			}

			foreach ($table as $tablename) {
				if(isset($$tablename)) $$tablename->free();
			}
			$db = NULL;
		?>
		</div>
		<script type="text/javascript" src="../js/script.js"></script>
	</body>
</html>