<?php include '../modules/check.php';?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.8">
	<title>Список организаций</title>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<!-- <link rel="stylesheet" type="text/css" href="../css/material.min.css"> -->
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<!-- <link rel="stylesheet" type="text/css" href="../css/style.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="../css/dialog-polyfill.min.css"> -->
	<link rel="stylesheet" type="text/css" href="../css/style.min.css">
	<style type="text/css">
	.delButton, .editButton {
		position: absolute;
		color: white;
		top: 7px;
	}
	.delButton {
		right: 40px;

	}
	.editButton {
		right: 5px
	}
</style>
</head>
<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<header class="mdl-layout__header" id="mainHeader">
			<?php
			include_once '../functions/safemysql.class.php';
			include_once '../functions/params.php';
			$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
			$idTable = (!(empty($_GET['idt']))) ? $_GET['idt'] : "1" ;
			$datacol = $db->getRow("SELECT * FROM fields WHERE idTable=?i and type=1 ",$idTable);
			$col = $datacol['fieldName'];
			$val = (!(empty($_GET['val']))) ? $_GET['val'] : "" ;
			$tables = array("tables","fields");
			$sortFields = array('','name','name','surname','idType');
			$table = $db->getRow("SELECT * FROM ?n WHERE id=?i",$tables[0],$idTable);
			$fields = $db->query("SELECT * FROM ?n WHERE idTable=?i",$tables[1],$idTable);
			$data{$table['tableName']} = (empty($col) or empty($val)) ? $db->query("SELECT * FROM ?n ORDER BY ?n ASC",$table['tableName'],$sortFields[$idTable]) : $db->query("SELECT * FROM ?n where ?n=?i ORDER BY ?n ASC",$table['tableName'],$col,$val,$sortFields[$idTable]);
			$nextidt=($idTable+1);
			if ($nextidt==5) {
				$types = $db->getAll("SELECT * FROM types");					
			}
			?>
			<?php 
			$idt=$idTable;
			if ($idt>1) {
				$previdt=($idt-1);
				$prevTable=$db->getRow("SELECT * FROM tables WHERE id=?i",$previdt);
				$prevdatacol = $db->getRow("SELECT * FROM fields WHERE idTable=?i and type=1 ",$prevTable['id']);
				$prevCol = $prevdatacol['fieldName'];
				$worker=$db->getRow("SELECT * FROM ?n WHERE id=?i",$prevTable['tableName'],$val);
				if ($idt>2) {
					$prevVal = $worker[$prevCol];
					$prevHref = "?idt=".$previdt."&val=".$prevVal;
				}
				else { 
					$prevHref = ($idt==2) ? "showpage.php" : "";
				}
				$HTitle = $worker['name'];
			}
			else {
				$HTitle = "Организации";
				$prevHref = ($idt==2) ? "showpage.php" : "";
			}
			if (!empty($prevHref)) echo "<button class='mdl-layout-icon mdl-button mdl-js-button mdl-button--icon' title='Назад' onclick=location.href='".$prevHref."'><i class='material-icons'>arrow_back</i></button>"?>
			<div class="mdl-layout__header-row">
				<span class="mdl-layout__title  mdl-layout--large-screen-only"><?php if(isset($HTitle)) echo $HTitle;?></span>
				<span class="mdl-layout--small-screen-only mdl-typography--caption"><?php if(isset($HTitle)) echo $HTitle;?></span>
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
		</header>
		<header class="mdl-layout__header" id='selectHeader' style="display: none">
			<button class='mdl-layout-icon mdl-button mdl-js-button mdl-button--icon' title='Назад' onclick="$('#mainHeader').show(); $('#selectHeader').hide(); for (var i = 0, length = boxes.length; i < length; i++) {boxes[i].MaterialCheckbox.uncheck();}"><i class='material-icons'>arrow_back</i></button>
			<div class="mdl-layout__header-row">
				<span class="mdl-layout__title ">Выбрано: <span id="counter"></span></span>
				<div class="mdl-layout-spacer"></div>
				<button class='mdl-layout-icon mdl-button mdl-js-button mdl-button--icon' title='Удалить выбранные' onclick="delobj(getChecked(),<?php echo $idTable?>)"><i class='material-icons'>delete</i></button>
			</div>
		</header>
		<main class="mdl-layout__content">
			<div class="page-content">
				<?php
				include './sidebar.php'; 
				?>
				<div class="mdl-grid">
					<div class="mdl-layout-spacer"></div>
					<div class="mdl-cell mdl-cell--middle mdl-cell--8-col" >
						<table width=100% class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
							<thead>
								<tr>
									<th>
										<label class="mdl-checkbox mdl-js-checkbox mdl-data-table__select input" for="table-header">
											<input type="checkbox" id="table-header" class="mdl-checkbox__input" aria-label="Выбрать все">
										</label>
									</th>
									<?php 
									foreach ($fields as $field)	{
										if($field['type']==0 or $field['type']==2) echo "<th class='mdl-data-table__cell--non-numeric'>".$field['fieldShowName']."</th>";
									}
									?>
									<th class='mdl-data-table__cell--non-numeric'></th>
								</tr>
							</thead>
							<tbody id="ref">
								<?php
								foreach ($data{$table['tableName']} as $value{$table['tableName']}) {
									echo "<tr class='sblock mdl-grid--field' id=l".$value{$table['tableName']}['id'].">";
									?>
									<td>
										<label class="mdl-checkbox mdl-js-checkbox mdl-data-table__select" for="<?php echo $value{$table['tableName']}['id'] ?>">
											<input type="checkbox" id="<?php echo $value{$table['tableName']}['id'] ?>" class="mdl-checkbox__input" aria-label="Выбрать">
										</label>
									</td>
									<?php
									foreach ($fields as $field)	{
										$sval = (empty($value{$table['tableName']}[$field['fieldName']])) ? "-" : $value{$table['tableName']}[$field['fieldName']];
										if($field['type']==0) { 
											if ($nextidt>3) {
												$nextHref = "onclick='generation(".$idTable.","; 
												if(!empty($val)) {
													$nextHref .= $val;
												}
												else{
													$nextHref .= "0";
												} 
												$nextHref .= ",".$value{$table['tableName']}['id'].")'";
											}
											else {
												$nextHref = "onclick=location.href='./showpage.php?idt=".$nextidt."&val=".$value{$table['tableName']}['id']."'";
											}
											echo "<td class='mdl-data-table__cell--non-numeric sfield' $nextHref><span class='a'>$sval</span></td>";
										} elseif ($field['type']==2){
											echo "<td>";
											$nextHref = "<a>";
											foreach ($types as $type) {
												if ($type['id']==$value{$table['tableName']}['idType']){
													$sval = $type['icon'];
												}
											}
											echo $nextHref;

											echo $sval."</a></td>";
										}
									}
									echo "<td><button class='mdl-button mdl-js-button mdl-button--icon editButton' title='Изменить' onclick='generation(".$idTable.","; if(!empty($val)) {echo $val;}else{echo "0";} echo ",".$value{$table['tableName']}['id'].")'><i class='material-icons'>edit</i></button>";
									?>
									<button class="delButton mdl-button mdl-js-button mdl-button--icon" title='Удалить' onclick="delobj(<?php echo "[".$value{$table['tableName']}['id']."], ".$idTable ?>)"><i class="material-icons">delete</i></button>
									<?php
									echo "</td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
					<div class="mdl-layout-spacer"></div>
				</div>
			</main>
			<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect  mdl-button--colored app-fab--absolute mdl-shadow--6dp"  id="addButton">
				<i class="material-icons">add</i>
			</button>
			<div aria-live="assertive" aria-atomic="true" aria-relevant="text" class="mdl-snackbar mdl-js-snackbar">
				<div class="mdl-snackbar__text"></div>
				<button type="button" class="mdl-snackbar__action"></button>
			</div>
			<?php
			$db = NULL;
			?>
		</div>
		<!-- for sidebar.php -->
	</div>
</div>
<!-- for sidebar.php -->
</main>
</div>
<dialog class="mdl-dialog" id="widget-content"></dialog>
<dialog class="mdl-dialog" id="delDialog">
	<div class="mdl-dialog__title">Удалить?</div>
	<div class="mdl-dialog__content" id="delContent"></div>
	<div class="mdl-dialog__actions">
		<button type="button" class="mdl-button delete">Удалить</button>
		<button type="button" class="mdl-button close">Отмена</button>
	</div>
</dialog>
<script type="text/javascript" src="../js/material.min.js" defer></script>	
<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/dialog-polyfill.min.js"></script>
<script type="text/javascript" src="../js/jquery.mark.min.js"></script>
<script type="text/javascript" src="../js/sidebar.js"></script>
<script type="text/javascript" src="../js/checkbox.js"></script>
<script type="text/javascript" src="../js/jquery.inputmask-multi.min.js"></script>
<script type="text/javascript" src="../js/jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="../js/filter.js"></script>
<script type="text/javascript" src="../js/other.js"></script>
<!-- <script type="text/javascript" src="../js/one.min.js"></script> -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#addButton").click(function() {
			generation(<?php echo $idTable.', '.$val; ?>);
		});
	});
	function generation(idt,val,id){
		var content = document.getElementById("widget-content");
		if(idt<3){
			$.ajax({
				url:"../modules/dialogGenerator.php",
				type: 'POST',
				data: {
					idt: idt,
					id: id,
					val: val
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
		}
		else {
			$.ajax({
				url:"../modules/workers.php",
				type: 'GET',
				data: {
					val: val,
					id: id
				},
				success: function(data){
					content.innerHTML=data;
					componentHandler.upgradeAllRegistered();
					var focuselement = document.getElementById('onf');
					focuselement.focus();
					focuselement.removeAttribute('id');
					$.getScript("../js/workers.js");
				}
			});
		}
		dialog.showModal();
	}
</script>
</body>
</html>