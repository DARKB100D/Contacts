<?php 
include_once '../functions/safemysql.class.php';	
include_once '../functions/params.php';
include_once '../functions/phoneFormat.php';
$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
$table = array("organizations","departments","workers","contacts","types");
$input = substr($_POST['input'], 0, 64);
$input = preg_replace("/[^\w\x7F-\xFF\s]/", "", $input);
$limit = ($_POST['page']==0) ? "LIMIT ".$_POST['limit'] : "LIMIT ". $_POST['page']*$_POST['limit']. ", ".$_POST['limit'];
$query = "SELECT `id` FROM search WHERE `text` LIKE '%".$input."%' GROUP BY `id` ".$limit;
$result = $db->getCol($query);
$query = "SELECT `id` FROM search WHERE `text` LIKE '%".$input."%' GROUP BY `id`";
$sresult = $db->getCol($query);
if(!empty($result)){
	$ids = implode(",", $result);
	$results_amount = count($sresult); 
	echo "<div class='mdl-cell--12-col mdl-shadow--2dp mdl-cell--middle results printHide'><span>Найдено ".$results_amount." результатов: </span></div>";
	echo '<table class="table department mdl-shadow--2dp" align="center mdl-cell--12-col">';
	$workers = $db->query("SELECT * FROM ?n WHERE id IN ($ids) ORDER BY surname ASC", $table[2]);
	foreach ($workers as $worker) { ?>
	<tr class="sblock">
		<td class='sfield' width="60%"><?php  echo "<i class=role>".$worker['role']."</i>"; if(!empty($worker['role']) && (!empty($worker['name']) or !empty($worker['surname']) or !empty($worker['middlename']))){echo "<br style='line-height: 40px;'>";} echo "<b>".$worker['surname']."</b> ".$worker['name']." ".$worker['middlename'];?></td>
		<?php $contacts = $db->query("SELECT * FROM ?n WHERE idWorker=?i",$table[3],$worker['id']);
		$department = $db->getRow("SELECT * FROM ?n WHERE id=?i",$table[1],$worker['idDepartment']);
		$organization = $db->getRow("SELECT * FROM ?n WHERE id=?i",$table[0],$department['idOrganization']);
		?>
		<td width="40%">
			<table class="contactTable">
				<?php
				$types = $db->query("SELECT * FROM ?n",$table[4]);
				echo "<tr>";
				echo "<td width='1em' style='text-align: right;'>";
				echo '<i class="fa fa-building-o" aria-hidden="true"></i>';
				echo "</td>";
				echo "<td>";
				echo "<table class='bordertable'>";
				echo "<tr>";
				echo "<td class='sfield'><span>".$organization['name']."</span>";
				if (!empty($department['name'])) echo " (".$department['name'].")";
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "</td>";
				echo "</tr>";
				foreach ($types as $type) {
					$yes = 0;
					foreach ($contacts as $contact) {
						if ($type['id']==$contact['idType']) {
							$yes = 1;
						}
					}
					if ($yes==1) {
						echo "<tr>";
						echo "<td width='1em' style='text-align: right;'>";
						echo $type['old_icon'];
						echo "</td>";
						echo "<td>";
						echo "<table class='bordertable'>";
						foreach ($contacts as $contact) {
							if ($type['id']==$contact['idType']) {
								echo "<tr>";
								echo "<td  class='sfield' onclick=copy(".$contact['id'].") id=b".$contact['id']."><input readonly id=i".$contact['id']." value='".phone($contact['value'])."'><span>".phone($contact['value'])."</span>";
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
<?php
echo "<div class='mdl-cell--12-col mdl-shadow--4dp pages printHide'><div class='mdl-grid' style='
justify-content: center;
'>";
if ($_POST['page']>0) echo '<button class="mdl-button mdl-cell mdl-cell--1-col-phone mdl-cell--3-col-tablet" onclick="search('.($_POST['page']-1).','.$_POST['limit'].')" title="Предыдущая"><i class="fa fa-chevron-left fa-lg" aria-hidden="true"></i></button>'; else echo '<div class="mdl-cell mdl-cell--1-col-phone mdl-cell--3-col-tablet"></div>';
echo '<div class="mdl-cell mdl-cell--middle mdl-typography--text-center mdl-cell--2-col-phone mdl-cell--2-col-tablet">Стр. '.($_POST['page']+1).'</div>';
if (1<$results_amount/($_POST['limit']*($_POST['page']+1))) echo '<button class="mdl-button mdl-cell mdl-cell--1-col-phone mdl-cell--3-col-tablet" onclick="search('.($_POST['page']+1).','.$_POST['limit'].')" title="Следующая"><i class="fa fa-chevron-right fa-lg" aria-hidden="true"></i></button>'; else echo '<div class="mdl-cell mdl-cell--1-col-phone mdl-cell--3-col-tablet"></div>';
echo "</div></div>";
}

else {
	echo "По вашему запросу ничего не найдено :(";
}
?>