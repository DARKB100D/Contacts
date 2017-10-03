<?php include '../modules/check.php';?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Лог</title>
	<meta name="viewport" content="width=device-width, initial-scale=0.3">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<!-- <link rel="stylesheet" type="text/css" href="../css/material.min.css"> -->
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<!-- <link rel="stylesheet" type="text/css" href="../css/style.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="../css/dialog-polyfill.min.css"> -->
	<link rel="stylesheet" type="text/css" href="../css/style.min.css">
	<style type="text/css">
	.deleteButton {
		position: absolute;
		color: white;
		top: 7px;
	}
	.deleteButton {
		right: 10px
	}
</style>
</head>
<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<header class="mdl-layout__header">
			<?php
			include_once '../functions/safemysql.class.php';
			include_once '../functions/params.php';
			$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
			$fieldsNames=array('Время','Действие','Пользователь');
			?>
			<div class="mdl-layout__header-row" id='mainHeader'>
				<span class="mdl-layout__title">Действия</span>
				<div class="mdl-layout-spacer"></div>
				<button class='mdl-layout-icon mdl-button mdl-js-button mdl-button--icon' title='Очистить' onclick="clearLog()"><i class="material-icons">clear_all</i></button>
			</div>
			<div class="mdl-layout__header-row" id='selectHeader' style="display: none" >
				<span class="mdl-layout__title  ">Выбрано: <span id="counter"></span></span>
				<div class="mdl-layout-spacer"></div>
				<button class='mdl-layout-icon mdl-button mdl-js-button mdl-button--icon' title='Отменить выбранные действия' onclick="restore(getChecked(),this)"><i class='material-icons'>restore</i></button>
			</div>
		</header>
		<main class="mdl-layout__content">
			<div class="page-content">
				<?php
				include './sidebar.php'; 				
				$log = $db->getAll("SELECT * FROM log ORDER BY `time` DESC");
				?>
				<div class="mdl-grid mdl-grid--no-spacing">
					<table class="mdl-cell mdl-cell--middle mdl-cell--12-col mdl-data-table mdl-js-data-table mdl-shadow--2dp ">
						<thead>
							<tr>
								<th>
									<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-data-table__select input" for="table-header">
										<input type="checkbox" id="table-header" class="mdl-checkbox__input" />
									</label>
								</th>
								<?php
								foreach ($fieldsNames as $fieldsName) {
									echo "<th class='mdl-data-table__cell--non-numeric'>";
									echo $fieldsName;
									echo "</th>";	
								}
								?>
								<th class='mdl-data-table__cell--non-numeric'></th>
							</tr>
						</thead>
						<tbody id="ref">
							<?php
							$users = $db->getAll("SELECT * FROM users");
							foreach ($log as $row) {
								echo "<tr class='sblock mdl-grid--field' id=l".$row['id'].">";
								echo '<td>';
								echo '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-data-table__select" for="'.$row['id'].'">';
								echo '<input type="checkbox" id="'.$row['id'].'" class="mdl-checkbox__input" />';
								echo '</label>';
								echo '</td>';
								echo "<td  class='mdl-data-table__cell--non-numeric sfield'>".$row['time']."</td>";
								echo "<td class='mdl-data-table__cell--non-numeric sfield'>".$row['operation']." ".$row['h2']."</td>";
								// echo "<td class='mdl-data-table__cell--non-numeric sfield'>".$row['operation']."</td>";
								// echo "<td class='mdl-data-table__cell--non-numeric sfield'>".$row['h2']."</td>";
								foreach ($users as $user) {
									if($user['id'] == $row['idUser']) echo "<td class='mdl-data-table__cell--non-numeric sfield'>".$user['login']."</td>";
								}
								echo "<td><button  class='mdl-button mdl-js-button mdl-button--icon deleteButton' title='Отменить действие' onclick='restore([".$row['id']."],this)'><i class='material-icons'>restore</i></button></td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
				<!-- for sidebar.php -->
			</div>
		</div>
		<!-- for sidebar.php -->
	</div>
</main>
<div aria-live="assertive" aria-atomic="true" aria-relevant="text" class="mdl-snackbar mdl-js-snackbar">
	<div class="mdl-snackbar__text"></div>
	<button type="button" class="mdl-snackbar__action"></button>
</div>
<dialog class="mdl-dialog" id="delDialog">
	<!-- <div class="mdl-dialog__title">Восстановить?</div> -->
	<div class="mdl-dialog__content" id="delContent"></div>
	<div class="mdl-dialog__actions">
		<button type="button" class="mdl-button delete">Да</button>
		<button type="button" class="mdl-button close">Нет</button>
	</div>
</dialog>
<?php
$db = NULL;
?>
<!-- <script type="text/javascript" src="../js/material.min.js" defer></script>	
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/dialog-polyfill.min.js"></script>
<script type="text/javascript" src="../js/jquery.mark.min.js"></script>
<script type="text/javascript" src="../js/sidebar.js"></script>
<script type="text/javascript" src="../js/checkbox.js"></script> -->
<!-- <script type="text/javascript" src="../js/filter.js"></script> -->
<!-- <script type="text/javascript" src="../js/other.js"></script> -->
<script type="text/javascript" src="../js/one.min.js"></script>

<script type="text/javascript">
	function clearLog() {
		$.ajax({
			url: "../modules/clearLog.php",
			type:'POST',
			success: function(data){
				var notification = document.querySelector('.mdl-js-snackbar');
				notification.MaterialSnackbar.showSnackbar(
				{
					message: JSON.parse(data)['message']
				}
				);
				console.log(JSON.parse(data)['message']);
			}
		});
	}
	function restore(id,th) {
		line = [];
		for (var i = 0; i < id.length; i++) {
			line[i]=document.getElementById('l'+id[i]);
		}
			// if(confirm(th.title + "?")==true) {
			// 	$.ajax({
			// 		url: "../modules/restore.php",
			// 		type:'POST',
			// 		data: {
			// 			id:id
			// 		},
			// 		success: function(data){
			// 			for (var i = 0; i < line.length; i++) {
			// 				line[i].remove();	
			// 			}
			// 			updateHeaderSelect();
			// 			var notification = document.querySelector('.mdl-js-snackbar');
			// 			notification.MaterialSnackbar.showSnackbar(
			// 			{
			// 				message: 'Запись успешно восстановлена!'
			// 			}
			// 			);
			// 		}
			// 	});
			// }
			var content = document.getElementById("delContent");
			content.innerHTML=th.title + "?";
			delDialog.showModal();
			$('#delDialog .delete').one('click', function() {
				$.ajax({
					url: "../modules/restore.php",
					type:'POST',
					data: {
						id:id
					},
					success: function(data){
						for (var i = 0; i < line.length; i++) {
							line[i].remove();	
						}
						updateHeaderSelect();
						delDialog.close();
						var notification = document.querySelector('.mdl-js-snackbar');
						notification.MaterialSnackbar.showSnackbar(
						{
							message: 'Запись успешно восстановлена!'
						}
						);
					}
				});
			});
		}
	</script>
</body>
</html>