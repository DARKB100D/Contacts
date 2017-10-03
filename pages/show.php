<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Управление</title>
		<script type="text/javascript" src="../js/jquery-1.11.3.js"></script>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/roboto.css">		
		<link rel="stylesheet" href="../css/font-awesome.min.css">

	  	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript">
			function generation(idt,idp,id){
				var content = document.getElementById("widget-content");
				// var next = document.getElementById("next");
				// content.innerHTML="<input>";
				// next.onclick="alert();"; 
				$.ajax({
					url:"./add.php",
					type: 'POST',
					//dataType: 'json',
					data: {
						idt: idt,
						idp: idp,
						id: id
						},
					success: function(data){
						content.innerHTML=data;
					}
				});
				$( "#dialog" ).dialog();
			}
		</script>

	</head>
	<body>
		<div class="sidebar">
			<?php 







			?>
		</div>
		<div id="content">
			<?php
				include '../functions/safemysql.class.php';
				include '../functions/params.php';
				$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
				//$idTable = (isset($_GET['idt'])) ? $_GET['idt'] : "1" ;
				//$col = (isset($_GET['col'])) ? $_GET['col'] : "" ;
				//$val = (isset($_GET['val'])) ? $_GET['val'] : "" ;
				//$idTable = 3;//2; //$_GET[idt];
				//$col = "idDepartment";//"idOrganization";//$_GET[col];
				//$val = 2;//2;//$_GET[val];
				//$nextidt="";
				//$nextcol="";
				//$nextval="";
				// $tables = array("tables","fields");
				// $table = $db->getRow("SELECT * FROM ?n WHERE id=?i",$tables[0],$idTable);
				// $fields = $db->query("SELECT * FROM ?n WHERE idTable=?i",$tables[1],$idTable);
				// echo "<h2>".$table['tableShowName']."</h2>"; 
			?>
			<?php
				if($_SERVER['REQUEST_METHOD']=='POST')
				{	
					$table = $db->getRow("SELECT * FROM tables WHERE id=?i",$_POST['idt']);
					$fields = $db->query("SELECT * FROM fields WHERE idTable=?i",$_POST['idt']);
				    if (isset($_POST['delete']))
				    {
				        $db->query("DELETE FROM ?n WHERE id=?i", $table['tableName'], $_POST['delete']);
				    }
				    else {
				    	foreach ($fields as $field) {

							
							$d[$field['fieldName']] = ($field['type']==0) ? $_POST[$field['fieldName']] : $_POST['idp'] ;
						}
					print_r($d);	
					    if (isset($_POST['id'])) { 
					    	$d['id'] = $_POST['id'];
					        $db->query("UPDATE ?n SET ?u WHERE id = ?i", $table['tableName'], $d, $_POST['id']);
						} else { 
					        $db->query("INSERT INTO ?n SET ?u", $table['tableName'], $d);
					    }
				    } 
				    header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);  
				    exit;  
				}
			?>
		<!-- <button id="addButton">Добавить</button>
		<table width="100%">
		<tr> -->
		<?php 
			// $data{$table['tableName']} = (!((empty($col))&&(empty($val)))) ? $db->query("SELECT * FROM ?n where ?n=?i",$table['tableName'],$col,$val) : $db->getAll("SELECT * FROM ?n",$table['tableName']) ;
			// foreach ($fields as $field)	{
			// 	if($field['type']==0) echo "<th align=left>".$field['fieldShowName']."</th>";
			// }
		?>
		<!-- </tr> -->
		<?php
			// foreach ($data{$table['tableName']} as $value{$table['tableName']}) {
			// 	echo "<tr>";
			// 	foreach ($fields as $field)	{
			// 		if($field['type']==0) echo "<td><a onclick=location.href='./show.php?idt=".$nextidt."&col=".$nextcol."&val=".$nextval."'>".$value{$table['tableName']}[$field['fieldName']]."</a></td>";
					
			// 	}
			// 	echo "<td><button id='edit' onclick='generation(".$idTable.",".$value{$table['tableName']}['id'].")'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
				?>
	<!-- 			<td>
				<form method="POST">
					<input type="hidden" name="delete" value=<?php #echo $value{$table['tableName']}['id']; ?>>
					<input type="hidden" name="tableName" value=<?php #echo $table['tableName'];?>>
					<button type="submit"><i class="fa fa-times" aria-hidden="true"></i></button>
				</form>
				</td> -->
				<?php

			// 	echo "</tr>";
			// }
		?>
		<!-- </table> -->
		
				<?php
					 include "../functions/showdir.php";
					
					$rresult = buildTree("config2");
					print_r($rresult);
					
					viewTree($rresult);
					

				?>
				<script src="../js/showdir.js"></script>
		

		</div>
		<div id="dialog" title= <?php echo "Добавить";?> style="display: none;">
			<form method="POST">
					<div id="widget-content">

					</div>
					
					<button id="save" type="submit"><i class="fa fa-check" aria-hidden="true"> Готово</i></button>
					<!-- <button id="next" type="submit">-></button>  -->
			</form>
			
		</div>

		<?php
			$db = NULL;
		?>
		<script>
			$(document).ready(function(){
			    $("#addButton").click(function(){
			    	//generation(<?php #echo $idTable; ?>);
			        
			    });
			    $("#next").click(function(){
					   
			    });
			});
		</script>
		<script type="text/javascript" src="../js/script.js"></script>
		
	</body>
</html>