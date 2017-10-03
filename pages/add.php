<meta charset="utf-8">
	<?php
		
	?>

<?php

	if (isset($_POST['idt'])&&isset($_POST['idp'])) {
		
		include_once '../functions/safemysql.class.php';
		include_once '../functions/params.php';
		$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
		//$idTable = $_POST['idt'];
		//$id = $_POST['id'];
		//$action = "gen";// save/gen
		//$request ="";
		$tables = array("tables","fields");
		$table = $db->getRow("SELECT * from ?n WHERE id=?i",$tables[0],$_POST['idt']);
		$fields = $db->query("SELECT * FROM ?n WHERE idTable=?i",$tables[1],$_POST['idt']);
		echo "<table width=100%>";

		if (isset($_POST['id'])) $data{$table['tableName']} = $db->getRow("SELECT * FROM ?n WHERE id=?i",$table['tableName'],$_POST['id']);
			
			foreach ($fields as $field)	{
				if($field['type']==0){
					echo "<tr>";
					echo "<td>".$field['fieldShowName']."</td>";
					if (isset($_POST['id'])) {
						echo "<td><input name=".$field['fieldName']." value=".$data{$table['tableName']}[$field['fieldName']]."></td>";
					}
					else {
						echo "<td><input name=".$field['fieldName']."></td>";
					}
					echo "</tr>";
				}
				

			}
			echo "<td><input type='hidden' name='idp' value=".$_POST['idp']."></td>";
			echo "<td><input type='hidden' name='idt' value=".$_POST['idt']."></td>";
			if (isset($_POST['id'])) echo "<td><input type='hidden' name='id' value=".$_POST['id']."></td>";	
			
			echo "</table>";
	}

?>




				<?php
				
				// if (isset($_POST['idWorker'])) {
				// 	$workers = $db->query("SELECT",$);
				// 		$organizations = $db->query("SELECT",$);
				// 		$departments = $db->query("SELECT",$);
				// 		$contacts = $db->query("SELECT",$);
				// 			$types = $db->query("SELECT",$);

				// 		$contacts = $db->query("INSERT",$);
				// 		if () {
				// 			$types = $db->query("INSERT",$);							
				// 		}
				// }

				// elseif (isset($_POST['idOrganization'])) {
				// 	$organizations = $db->query("SELECT",$);
				// 	$departments = $db->query("SELECT",$);
				// 	$workers = $db->query("SELECT",$);

				// 	$departments = $db->query("INSERT",$);
				// 	$workers = $db->query("INSERT",$);

					
				// }

				// if (isset($_POST[''])) {
					
				// }

				?>
				<!-- <form> -->
					<?php 
					// if(isset($_POST[])){
					// 	echo '<input type="hiden" name="idWorker" value='.$;
					// }
					// else {
					// echo '	Имя:	<input type="text" name="workerName">
					// 		Фамилия:	<input type="text" name="workerSurname">
					// 		Отчество: <input type="text" name="workerMiddlename">';
					// }
					?>
					<!-- <input type="hiden" name="idOrganization" value="<?php ?>">
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
						</tr> -->
						<?php 
						// $contacts = $db->query("",$);
						// foreach ($contacts as $contact) {
						// 	echo '<tr>';
						// 	echo '<td>';
						// 	$types $db->query("",$);
						// 	foreach ($types as $type) {
								
						// 	}
						// 	echo '</td>';
						// 	echo '</tr>';
						// }
						?>
<!-- 					</table>
					<input type="hiden" name="idContact" value="<?php ?>">
					<input type="text" name="contactValue">
					<input type="hiden" name="idType" value="<?php ?>">
					<input type="text" name="typeName">

					<button type="submit" value="Сохранить"></button>
					<button type="submit" value="Удалить пользователя"></button>
				</form> -->

				<?php
				$db = NULL;
				?>
		</div>
		<script type="text/javascript" src="../js/script.js"></script>
	</body>
</html>