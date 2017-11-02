<?php
include '../functions/safemysql.class.php';
include '../functions/params.php';
$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	$userdata = $db->getRow("SELECT * FROM users WHERE id=?i", $_COOKIE['id']);
	if(($userdata['hash'] !== $_COOKIE['hash']) or ($userdata['id'] !== $_COOKIE['id']))
	{
		setcookie("id", "", time() - 3600*24*30*12, "/");
		setcookie("hash", "", time() - 3600*24*30*12, "/");
		$message='Хм, что-то не получилось';
	}
	else
	{
		exit(header('location:./showpage.php'));
	}
}
// else {$message='Включите куки!';}
if(isset($_POST['submit']))
{
	$data = $db->getRow("SELECT id, password FROM users WHERE login=?s",$_POST['login']);
	if(password_verify($_POST['password'],$data['password']))
	{
		$hash = md5(random_bytes(32));
		$qr_result=$db->query("UPDATE users SET hash=?s WHERE id=?i",$hash,$data['id']);
		setcookie("id", $data['id'], time()+60*60*24*30,"/");
		setcookie("hash", $hash, time()+60*60*24*30,"/");
		exit(header("Location: ./showpage.php"));
	}
	else
	{
		$message='Вы ввели неправильный логин/пароль';
	}
}
$db = NULL;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Вход</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- 	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700"> -->
	<!-- <link rel="stylesheet" type="text/css" href="../css/material.min.css" media="not print"> -->
	<link rel="stylesheet" type="text/css" href="../css/style.min.css"/>
	<style type="text/css">
	.logincard {
		max-width: 350px;
	}
</style>
</head>
<body>
	<div class="mdl-layout mdl-js-layout">
		<main class="mdl-layout__content">
			<div class="page-content">
				<div class="mdl-grid">
					<div class="mdl-layout-spacer"></div>
					<div class="mdl-card mdl-shadow--8dp mdl-cell logincard">
						<form method="post">
							<div class="mdl-grid">
								<div class="mdl-cell mdl-cell--12-col">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
										<input class="mdl-textfield__input" type="text" name="login" value="">
										<label class="mdl-textfield__label" for="login">Имя пользователя</label>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--12-col">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">
										<input class="mdl-textfield__input" type="password" name="password" value="">
										<label class="mdl-textfield__label" for="password">Пароль</label>
									</div>
								</div>
								<?php if (!empty($message)){
									echo '<div class="mdl-cell mdl-cell--12-col mdl-cell--middle mdl-typography--caption mdl-typography--text-center">'.$message.'</div>';
								}
								?>
								<div class="mdl-cell mdl-cell--12-col mdl-typography--text-center mdl-cell--middle">
									<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" name="submit">Войти</button>
								</div>
							</div>
						</form>
					</div>
					<div class="mdl-layout-spacer"></div>
				</div>
			</div>
		</main>
	</div>
</body>
<script type="text/javascript" src="../js/material.min.js" defer></script>
</html>