<?php


	function viewTree ($arrTree,$idParent = 0, $inWork = 0)
	{
		global $db;
		//Условия выхода из рекурсии
		if(empty($arrTree[$idParent])) {
		return;
		}
		if ($idParent == 0)
		{
			echo '<table width="100%" class="file">';
			$na=$db->getRow("select * from config2 where layer=?i",$arrTree[$idParent][0]['layer']);
			$ti=$db->getRow('select * from tables where tableName=?s',$na['tableName']);
		}
		else
		{
			echo '<tr><td colspan="4">';
			echo '<table width="100%" style="display:none" class="file T' . $idParent . '">';
			//print_r($arrTree[$idParent][0]['layer']);
			if(isset($arrTree[$idParent][0]['layer'])) {
			$na=$db->getRow("select * from config2 where layer=?i",$arrTree[$idParent][0]['layer']);
			//print_r($na);
			$ti=$db->getRow('select * from tables where tableName=?s',$na['tableName']);
			//echo '$ti';
			//print_r($ti);

			echo '<tr><td width = "35px"><button onclick="generation('.$ti['id'].','.(1+substr($arrTree[$idParent][0]['id'], strlen($arrTree[$idParent][0]['layer']))).')"><i class="fa fa-plus" aria-hidden="true"></i></button></td><td colspan="2">Добавить</td></tr>';//не работает
			} 


		}
			for ($i=0; $i < count($arrTree[$idParent]); $i++) { 
			 	echo '<tr>';
			 	
			 	if ($arrTree[$idParent][$i]['type'] == 0)
			 	{	
					echo ('<td width = "46px"><button onClick="show_table(\'T' . $arrTree[$idParent][$i]['id'] . '\')" id ="BT' . $arrTree[$idParent][$i]['id'] . '"><i class="fa fa-plus-square-o fa-lg" aria-hidden="true"></i></button></td>');
					echo "<td>" . $arrTree[$idParent][$i]['title'] . "</td>";

					echo "<td width = '35px'><button id='edit' onclick='generation(".$ti['id'].",".(1+substr($arrTree[$idParent][0]['id'], strlen($arrTree[$idParent][0]['layer']))).",".substr($arrTree[$idParent][$i]['id'], strlen($arrTree[$idParent][$i]['layer'])).")'><i class='fa fa-pencil' aria-hidden='true'></i></button>"; //требует проверки

					echo '
					<td width = "35px">
						<form method="POST">
							<input type="hidden" name="delete" value='.substr($arrTree[$idParent][$i]['id'], strlen($arrTree[$idParent][$i]['layer'])).'>
							<input type="hidden" name="idt" value="'.$ti['id'].'">
							<button type="submit"><i class="fa fa-times" aria-hidden="true"></i></button>
						</form>
					</td>';
				}
				else
				{	
					echo ('<td width = "45px"><button><i class="fa fa-file-o fa-lg" aria-hidden="true"></i></button></td>');
					echo "<td>" . $arrTree[$idParent][$i]['title'] . "</td>";

					echo ('<td width = "35px"><button onClick=\'location.href="../functions/download.php?id=' . substr($arrTree[$idParent][$i]['id'], strlen($arrTree[$idParent][$i]['layer'])) . '"\'><i class="fa fa-download fa-lg" aria-hidden="true"></i></button></td>');
				}
				

				echo '</tr>';
				
				viewTree($arrTree, $arrTree[$idParent][$i]['id']);
			}
			echo '</table>';
		if (!($idParent == 0)) {
			echo "</td></tr>";
		}
	}


	function buildTree ($config)
	{
		global $db;

		$qr_result = $db->getAll("select * from ?n",$config);
		$arrTree = array();
		foreach ($qr_result as $data) {

			$result{$data['tableName']} = $db->getAll("select * from ?n",$data['tableName']);
			foreach ($result{$data['tableName']} as $data{$data['tableName']}) {
				if (isset($data{$data['tableName']}[$data['idParentColName']])) {
					if(empty($arrTree[($data['layer']-1) . $data{$data['tableName']}[$data['idParentColName']]]))
					{
						$arrTree[($data['layer']-1) . $data{$data['tableName']}[$data['idParentColName']]] = array();
					}
					$arrTree[($data['layer']-1) . $data{$data['tableName']}[$data['idParentColName']]][] = [
					"id"		=>	$data['layer'] . $data{$data['tableName']}[$data['idColName']],
					"title" 	=>	$data{$data['tableName']}[$data['showColName']],
					"idParent"	=>	($data['layer']-1) . $data{$data['tableName']}[$data['idParentColName']],
					"layer"		=>	$data['layer'],
					"type"		=>	$data['type']
					];
				}
				else{
					if(empty($arrTree[0]))
					{
						$arrTree[0] = array();
					}
					$arrTree[0][] = [
					"id"		=>	$data['layer'] . $data{$data['tableName']}[$data['idColName']],
					"title" 	=>	$data{$data['tableName']}[$data['showColName']],
					"layer"		=>	$data['layer'],
					"type"		=>	$data['type']
					];
				} 
					

			}
		}
		return $arrTree;
	}
?>