<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.8">
	<meta name="format-detection" content="telephone=no">
	<title>Контакты</title>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="stylesheet" type="text/css" href="./css/material.min.css" media="not print">
	<link rel="stylesheet" type="text/css" href="./css/style_contacts.css">
	<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./css/noty.css">
	<style type="text/css">
		.mdl-card {
			max-width: 90em;
			width:100%;
			justify-content: center;
		}
	</style>
</head>
<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<header class="mdl-layout__header">
			<button class="mdl-layout-icon mdl-button mdl-js-button mdl-button--icon" id="back" onclick="back();" style="display: none;" title='Назад'>
				<i class="material-icons">arrow_back</i>
			</button>
			<div class="mdl-layout__header-row">
				<span class="mdl-layout__title mdl-layout--large-screen-only">Контакты</span>
				<span class="mdl-layout--small-screen-only mdl-typography--caption printHeader">Контакты</span>
				<div class="mdl-layout-spacer"></div>
				<input type="checkbox" name="liveSearch" id="liveSearch" checked="true" hidden>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right printHide">
					<label class="mdl-button mdl-js-button mdl-button--icon" for="globalSearch" title='Поиск'>
						<i class="material-icons">search</i>
					</label>
					<div class="mdl-textfield__expandable-holder">
						<input class="mdl-textfield__input" type="text" id='globalSearch'>
					</div>
				</div>
			</div>
		</header>
		<main class="mdl-layout__content" id='ref'>
			<div class="page-content">
				<div class="mdl-layout-spacer"></div>
				<div class="content mdl-grid" id=indexContent>
					<?php
					include_once './functions/safemysql.class.php';	
					include_once './functions/params.php';
					$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
					$table = "organizations";
					?>
					<div id="myList-nav" class="printHide"></div>
					<ul class="mdl-list" id="myList">
						<?php 
						$organizations =  $db->query("SELECT * FROM ?n ORDER BY name ASC",$table);
						foreach ($organizations as $organization) {
							echo "<li class='sblock mdl-list__item liho' onclick=location.href='./pages/contacts.php?id=".$organization['id']."'>".$organization['name']."</li>";
						}
						$$table->free();
						$db = NULL;
						?>
					</ul>
				</div>
				<div class="mdl-grid mdl-card" id="ajaxContent"></div>
				<div class="mdl-layout-spacer"></div>
			</div>
		</main>
	</div>
	<script type="text/javascript" src="./js/material.min.js" defer></script>
	<script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="./js/jquery.mark.min.js"></script>
	<script type="text/javascript" src="./js/listnav.min-2.1.js"></script>
	<script type="text/javascript" src="./js/noty.min.js"></script>
	<script type="text/javascript">
		function markSearch() {
			var term = $("#fixed-header-drawer-exp").val(),
			$context = $(".sblock");
			$('.mdl-list').unmark();
			$context.show();
			if (term) {			
				$context.mark(term, {
					done: function() {
						$context.not(":has(mark)").hide();
					}
				});
			}
		}
		function search(page,limit) {
			var value = $('#globalSearch').val(),
			content = $('#ajaxContent');
			if (value.length > 0) {
				$('#indexContent').hide();
				$('#ajaxContent').show();
				$('#back').show();
				$.ajax({
					url:"./modules/search.php",
					type: 'POST',
					data: {
						input: value,
						page: page,
						limit: limit,
					},
					success: function(data){
						content.html(data);
						componentHandler.upgradeAllRegistered();
						if (page==0) $('.mdl-layout__content').scrollTop(0); else $('.mdl-layout__content').scrollTop(62);
						$('.sfield').mark(value);
					}
				});
			}
			else {
				$('#indexContent').show();
				$('#ajaxContent').hide();
				$('#back').hide();
			}
		}
		function back() {
			$('#back').hide();
			$('#ajaxContent').hide();
			$('#indexContent').show();
			$('#globalSearch')[0].parentElement.parentElement.MaterialTextfield.change("");
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
			return function(callback, ms){
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
		$(function() {
			var $input = $('#globalSearch'),
			page = 0,
			limit = 15,
			delay = 200;
			$input.on('keyup',function(event){
				if ($('#liveSearch').is(':checked')) {
					fnDelay(function() {
						search(page,limit);
					}, delay);
				}
				else { 
					if (event.keyCode==13) {
						search(page,limit);
					}  
				}
			});
		});
		$('#myList').listnav();
	</script>
</body>
</html>