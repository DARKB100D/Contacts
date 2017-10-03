<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Список организаций</title>
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" href="./css/font-awesome.min.css">
	</head>
	<body>
		<div id="content">
			<?php
				include '../functions/safemysql.class.php';
				include '../functions/params.php';
				$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
				?>

				<form>
					<?php if(isset($_POST[])){
						echo '<input type="hiden" name="idWorker" value='.$;
					}
					else {
					echo '	Имя:	<input type="text" name="workerName">
							Фамилия:	<input type="text" name="workerSurname">
							Отчество: <input type="text" name="workerMiddlename">';
					}
					?>
					<input type="hiden" name="idOrganization" value="<?php ?>">
					Организация: <input type="text" name="organizationName">
					<input type="hiden" name="idDepartment" value="<?php ?>">
					Отдел:		<input type="text" name="departmentName">
					<table>
						<tr>
							<th>
								
							</th>
						</tr>
						<tr>
							<button>Добавить</button>
						</tr>
						<?php 
						$contacts = $db->query("",$);
						foreach ($contacts as $contact) {
							echo '<tr>';
							echo '<td>';
							$types $db->query("",$);
							foreach ($types as $type) {
								
							}
							echo '</td>';
							echo '</tr>';
						}
						?>
					</table>
					<input type="hiden" name="idContact" value="<?php ?>">
					<input type="text" name="contactValue">
					<input type="hiden" name="idType" value="<?php ?>">
					<input type="text" name="typeName">

					<button type="submit" value="Сохранить"></button>
					<button type="submit" value="Удалить пользователя"></button>
				</form>>

				<?php



				$table = ;
				$fields = ;

				$db->query("INSERT INTO ?n ",$);
				$db = NULL;
				?>
		</div>
		<script type="text/javascript" src="./js/script.js"></script>
	</body>
</html>