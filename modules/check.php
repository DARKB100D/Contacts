<?php
include_once '../functions/safemysql.class.php';
include_once '../functions/params.php';
$db = new SafeMysql(array('user'=>$user, 'pass'=>$pass, 'db'=>$base, 'charset'=>'utf8'));
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{   
	$userdata = $db->getRow("SELECT * FROM users WHERE id=?i", $_COOKIE['id']);
	if(($userdata['hash'] !== $_COOKIE['hash']) or ($userdata['id'] !== $_COOKIE['id']))
	{
		setcookie("id", "", time() - 3600*24*30*12, "/");
		setcookie("hash", "", time() - 3600*24*30*12, "/");
		exit(header('location:../pages/auth.php'));
	}
}
else
{
	exit(header('location:../pages/auth.php'));
}
$db = NULL; 
?>