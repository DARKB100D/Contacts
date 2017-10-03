<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>
<?php
					include_once './functions/safemysql.class.php';	
					include_once '/functions/params.php';
					$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
					$table = "organizations";
					$table2="departments";
				?>
				<?php 
				$organizations =  $db->query("SELECT * FROM ?n ORDER BY name ASC",$table);
					echo "<select id=organizations>";
					foreach ($organizations as $organization) {
						echo "<option>".$organization['name']."</option>";
					}
					echo "</select>";
				$departments =  $db->query("SELECT * FROM ?n ORDER BY name ASC",$table2);
					echo "<select id=departments>";
					foreach ($departments as $department) {
						echo "<option>".$department['name']."</option>";
					}
					echo "</select>";
					$$table->free();
					$db = NULL;
					$a = "";
					if (!(empty($a))) {
						echo "string";
					}
?>
<script type="text/javascript">	
</script>
</body>
</html>