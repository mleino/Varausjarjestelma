<?php
session_start();
$bd=$_GET['bd']+1; //Day
$bl=$_GET['bl']; //Lesson
$bp=$_GET['bp']; //Part

$pvm = date("Y-n-d", strtotime(date("Y").'W'.date('W').$bd));
//die($pvm);

$link = mysql_connect('localhost', '2957_HuhdinKoulu', 'Koulu1');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("2957_Koulu", $link);

$result = mysql_query("INSERT INTO `varaukset` (`luokka`,`tunti`,`tunninosa`,`pvm`,`varaaja`) VALUES ('40','".$bl."','".$bp."','".$pvm."','".$_SESSION['userid']."')");

mysql_close($link);

header("Location: ".$_SERVER['HTTP_REFERER']);
?>