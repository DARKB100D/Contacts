<?php
function phoneBlock($number){
	$add='';
	if (strlen($number)%2)
	{
		$add = $number[ 0];
		$add .= (strlen($number)<=5? "-": "");
		$number = substr($number, 1, strlen($number)-1);
	}
	return $add.implode("-", str_split($number, 2));
}
function phone($number){
	if (strlen($number)<=7) {
		return phoneBlock($number);
	}
	else {
		return $number;
	}
}
?>