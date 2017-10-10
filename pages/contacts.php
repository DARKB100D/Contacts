<?php
include_once '../functions/safemysql.class.php';	
include_once '../functions/params.php';
include_once '../functions/phones.php';
$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
$table = array("organizations","departments","workers","contacts","types");
if(isset($_GET['id'])){
	$idOrganization = $_GET['id'];
	$organizations = $db ->query("SELECT * FROM ?n WHERE id=?i",$table[0],$idOrganization);
	$rowCount = $organizations->num_rows;
	if ($rowCount >= 1) { 
		$organization = $organizations->fetch_array()
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=0.8">
			<title><?php echo $organization['name']; ?></title>
			<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
			<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
			<link rel="stylesheet" type="text/css" href="../css/material.min.css" media="not print">
			<link rel="stylesheet" type="text/css" href="../css/style_contacts.css">
			<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
			<link rel="stylesheet" type="text/css" href="../css/noty.css">
			<style type="text/css">
			.mdl-card {
				max-width: 90em;
				width:100%;
				min-height: auto;
			}
		</style>
	</head>
	<body>
		<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
			<header class="mdl-layout__header">
				<button class="mdl-layout-icon mdl-button mdl-js-button mdl-button--icon printHide" onclick="location.href='../index.php'" title='Назад'>
					<i class="material-icons">arrow_back</i>
				</button>
				<div class="mdl-layout__header-row">
					<span class="mdl-layout__title mdl-layout--large-screen-only printHide"><?php echo $organization['name']; ?></span>
					<span class="mdl-layout--small-screen-only mdl-typography--caption printHeader"><?php echo $organization['name']; ?></span>
					<div class="mdl-layout-spacer"></div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right printHide">
						<label class="mdl-button mdl-js-button mdl-button--icon" for="fixed-header-drawer-exp" title='Поиск'>
							<i class="material-icons">search</i>
						</label>
						<div class="mdl-textfield__expandable-holder">
							<input class="mdl-textfield__input" type="text" name="sample" id="fixed-header-drawer-exp">
						</div>
					</div>
				</div>
			</header>
			<main class="mdl-layout__content" id='ref' >
				<div class="page-content">
					<div aria-live="assertive" aria-atomic="true" aria-relevant="text" class="mdl-snackbar mdl-js-snackbar">
						<div class="mdl-snackbar__text"></div>
						<button type="button" class="mdl-snackbar__action"></button>
					</div>
					<div class="mdl-grid mdl-card">
						<div class="mdl-cell mdl-cell--12-col printHide"><button class="mdl-button mdl-js-button" onclick='showall(1);' id='showall'>Показать все</button></div>
						<?php 
						$departments = $db ->query("SELECT * FROM ?n WHERE idOrganization=?i or idOrganization = 0 ORDER BY name ASC",$table[1],$idOrganization);
						foreach ($departments as $department) {
							?>
							<table class="table department printNoBreak" align="center">
								<tr class="header hd sfield liho" id=<?php echo $department['id']?> onclick='showhide(this.id)'>
									<th colspan="2" ><?php echo $department['name'];?>&nbsp;<i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i></th>
								</tr>
								<?php 
								$workers = $db->query("SELECT * FROM ?n WHERE idDepartment=?i  ORDER BY surname ASC", $table[2], $department['id']);
								foreach ($workers as $worker) { ?>
								<tr class="sblock d<?php echo $department['id']?>" style='display: none;'>
									<td class='sfield' width="65%"><?php  echo "<i class=role>".$worker['role']."</i>"; if(!empty($worker['role']) && (!empty($worker['name']) or !empty($worker['surname']) or !empty($worker['middlename']))){echo "<br style='line-height: 40px;'>";} echo "<b>".$worker['surname']."</b> ".$worker['name']." ".$worker['middlename'];?></td>
									<?php $contacts = $db->query("SELECT * FROM ?n WHERE idWorker=?i",$table[3],$worker['id']);
									?>
									<td width="35%">
										<table class="contactTable">
											<?php
											$types = $db->query("SELECT * FROM ?n",$table[4]);
											foreach ($types as $type) {
												$yes = 0;
												foreach ($contacts as $contact) {
													if ($type['id']==$contact['idType']) {
														$yes = 1;
													}
												}
												if ($yes==1) {
													echo "<tr>";
													echo "<td width='1em'>";
													echo $type['old_icon'];
													echo "</td>";
													echo "<td>";
													echo "<table class='bordertable'>";
													foreach ($contacts as $contact) {
														if ($type['id']==$contact['idType']) {
															echo "<tr>";
															echo "<td  class='sfield' onclick=copy(".$contact['id'].") id=b".$contact['id']."><input readonly class='printHide' id=i".$contact['id']." value='".phone($contact['value'])."'><span>".phone($contact['value'])."</span>";
															if (!empty($contact['type'])) echo " (".$contact['type'].")";
															echo "</td>";
															echo "</tr>";
														}
													}
													echo "</table>";
													echo "</td>";
													echo "</tr>";
												}
											}
											?>
										</table>
									</td>
								</tr>
								<?php }?>
							</table>
							<?php }?>
						</div>
						<?php }
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
			</main>
		</div>
		<script type="text/javascript" src="../js/material.min.js" defer></script>
		<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="../js/jquery.mark.min.js"></script>
		<script type="text/javascript" src="../js/noty.min.js"></script>
		<script type="text/javascript">
			function showhide (id) {
				var header = $('#'+id),
				objects = header.siblings();
				changeVisible(changeAngle(header),objects);
			}
			function changeVisible (hide, objects) {
				if (hide) objects.show(); else objects.hide();
			}
			function changeAngle (header) {
				obj = header.find('i');
				if (obj.attr('class') == "fa fa-chevron-down") { 
					obj.attr('class',"fa fa-chevron-right");
					header.addClass("hd");
					header.parent().parent().addClass("printNoBreak");
					return 0;
				}
				else {
					obj.attr('class',"fa fa-chevron-down");
					header.removeClass("hd");
					header.parent().parent().removeClass("printNoBreak");
					return 1;
				}	
			}
			function showall(hide) {
				var tables = $('.table'), 
				headers = $('.header'),
				blocks = $('.sblock'),
				button = $("#showall");
				if(hide==1){
					tables.removeClass("printNoBreak");
					headers.removeClass("hd");
					headers.find('i').attr('class',"fa fa-chevron-down");
					blocks.show();
					button.attr("onclick","showall(0)");
					button.text('Скрыть все');
				}
				else {
					tables.addClass('printNoBreak');
					headers.addClass("hd");
					headers.find('i').attr('class',"fa fa-chevron-right");						
					blocks.hide();
					button.attr("onclick","showall(1)");
					button.text('Показать все');				
				}
			}
			function markSearch() {
				var term = $("#fixed-header-drawer-exp").val(),
				$departments = $(".department"),
				button = $("#showall");
				$departments.show().unmark();
				$departments.removeClass("printNoBreak");
				$(".sblock").show().unmark();
				$(".hd").find('i').attr('class',"fa fa-minus-square-o");
				$(".hd").removeClass("hd");
				$.each($departments, function() {
					var $blocks = $(this).find($(".sblock")),
					$department = this;
					$.each($blocks, function() {
						$block = this;
						$fields = $($department).find($(".sfield"));
						if (term) {
							$fields.mark(term, {
								done: function() {
									$($block).not(":has(mark)").hide();
								}
							});
						}
					});
					if (term) {
						$($department).not(":has(mark)").hide().addClass('printNoBreak'); 
						button.attr("disabled","true"); 
					} 
					else {
						button.prop("disabled", false);
						button.attr("onclick","showall(0)");
						button.text('Скрыть все');
					}
					header = $($department).find('.header');
					if  (header.siblings().has(":visible").length > 0) {
						header.find('i').attr('class',"fa fa-chevron-down");
					} 
					else {
						header.find('i').attr('class',"fa fa-chevron-right");
					}
				});
			}
			function copy(content) {
				var input =  document.getElementById('i'+content),
				button = document.getElementById('b'+content);
				input.select();
				document.execCommand("copy");
				new Noty({
					text: 'Скопировано в буфер обмена: <br><b style="color:yellow"">'+input.value+'</b>',
					timeout: 1000,
					progressBar:false,
					theme:'metroui',
					type:'success'
				}).show();
			}

			fnDelay = (function(){
				var timer = 0;
				return function(callback, ms) {
					clearTimeout(timer);
					timer = setTimeout(callback, ms);
				};
			})();
			$(function() {
				var $input = $("#fixed-header-drawer-exp");
				$input.on("input", function() {
					fnDelay(function() {
						markSearch();
					}, 200);
				});
			}); 
		</script>
	</body>
	</html>