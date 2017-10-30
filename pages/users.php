<?php include '../modules/check.php';?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Администраторы</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons"> -->
	<!-- <link rel="stylesheet" type="text/css" href="../css/material.min.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700"> -->
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
			$tables = array("tables","fields");
			$fields = $db->query("SELECT * FROM ?n WHERE idTable=5 AND type=0",$tables[1]);
			?>
			<div class="mdl-layout__header-row" id='mainHeader'>
				<span class="mdl-layout__title">Администраторы</span>
				<div class="mdl-layout-spacer"></div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right">
					<label class="mdl-button mdl-js-button mdl-button--icon" for="fixed-header-drawer-exp">
						<i class="material-icons">search</i>
					</label>
					<div class="mdl-textfield__expandable-holder">
						<input class="mdl-textfield__input" type="text" name="sample"
						id="fixed-header-drawer-exp">
					</div>
				</div>
			</div>
			<div class="mdl-layout__header-row" id='selectHeader' style="display: none" >
				<span class="mdl-layout__title  ">Выбрано: <span id="counter"></span></span>
				<div class="mdl-layout-spacer"></div>
				<button class='mdl-layout-icon mdl-button mdl-js-button mdl-button--icon' title='Удалить выбранные' onclick="delobj(getChecked(), 5)"><i class='material-icons'>delete</i></button>
			</div>
		</header>
		<main class="mdl-layout__content">
			<div class="page-content">
				<?php 
				include './sidebar.php'; 
				?>
				<div class="mdl-grid">
					<div class="mdl-layout-spacer"></div>
					<table class="mdl-cell mdl-cell--middle mdl-cell--8-col mdl-data-table mdl-js-data-table mdl-shadow--2dp ">
						<thead>
							<tr>
								<th>
									<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-data-table__select input" for="table-header">
										<input type="checkbox" id="table-header" class="mdl-checkbox__input" />
									</label>
								</th>
								<?php
								foreach ($fields as $field) {
									echo "<th class='mdl-data-table__cell--non-numeric'>";
									echo $field['fieldShowName'];
									echo "</th>";	
								}
								?>
								<th class='mdl-data-table__cell--non-numeric'></th>
							</tr>
						</thead>
						<tbody id="ref">
							<?php
							$users = $db->getAll("SELECT * FROM users");
							foreach ($users as $user) {
								echo "<tr class='sblock mdl-grid--field' id=l".$user['id'].">";
								echo '<td>';
								echo '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-data-table__select" for="'.$user['id'].'">';
								echo '<input type="checkbox" id="'.$user['id'].'" class="mdl-checkbox__input" />';
								echo '</label>';
								echo '</td>';
								foreach ($fields as $field) {
									echo "<td class='mdl-data-table__cell--non-numeric sfield' onclick='generation(5,0,".$user['id'].")'><span class='a'>".$user[$field['fieldName']]."</span></td>";
								}
								echo "<td><button class='mdl-button mdl-js-button mdl-button--icon deleteButton' title='Удалить' onclick='delobj([".$user['id']."], 5)'><i class='material-icons'>delete</i></button></td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
					<div class="mdl-layout-spacer"></div>
				</div>
				<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect  mdl-button--colored app-fab--absolute mdl-shadow--6dp"  id="addButton">
					<i class="material-icons">add</i>
				</button>
				<dialog class="mdl-dialog" id="widget-content"></dialog>
				<dialog class="mdl-dialog" id="delDialog">
					<div class="mdl-dialog__title">Удалить?</div>
					<div class="mdl-dialog__content" id="delContent"></div>
					<div class="mdl-dialog__actions">
						<button type="button" class="mdl-button delete">Удалить</button>
						<button type="button" class="mdl-button close">Отмена</button>
					</div>
				</dialog>
				<div aria-live="assertive" aria-atomic="true" aria-relevant="text" class="mdl-snackbar mdl-js-snackbar">
					<div class="mdl-snackbar__text"></div>
					<button type="button" class="mdl-snackbar__action"></button>
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
<?php
$db = NULL;
?>
<!-- <script type="text/javascript" src="../js/material.min.js" defer></script>	
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/dialog-polyfill.min.js"></script>
<script type="text/javascript" src="../js/jquery.mark.min.js"></script>
<script type="text/javascript" src="../js/sidebar.js"></script>
<script type="text/javascript" src="../js/checkbox.js"></script>
<script type="text/javascript" src="../js/filter.js"></script>
<script type="text/javascript" src="../js/other.js"></script> -->
<script type="text/javascript" src="../js/one.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$("#addButton").click(function() {
			generation(5);
		});
	});
	function generation(idt,val,id){
		var content = document.getElementById("widget-content");
		$.ajax({
			url:"../modules/dialogGenerator.php",
			type: 'POST',
			data: {
				idt: idt,
				id: id
			},
			success: function(data){
				content.innerHTML=data;
				componentHandler.upgradeAllRegistered();
				var focuselement = document.getElementById('onf');
				focuselement.focus();
				focuselement.removeAttribute('id');
				$.getScript("../js/dialog.js");
			}
		});
		dialog.showModal();
	}
</script>
</body>
</html>