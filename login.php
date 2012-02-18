<?php
session_start();
include_once("class.php");
$hlo = new kayttaja();
$varaaja = $hlo -> kirjaudusisaan($_POST['user'], sha1($_POST['pass']));
if ((empty($varaaja)) || ($varaaja=="")){
header('HTTP/1.1 401 Unauthorized');
}else{
echo $varaaja[0] -> name; 
$_SESSION['userid'] =  $varaaja[0] -> userid;
$_SESSION['name'] =  $varaaja[0] -> name;}
?>