<?php
$con = mysql_connect("localhost","2957_HuhdinKoulu","Koulu1");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("2957_Koulu", $con);

$paiva=date("Y-m-d",strtotime($_GET['vrs']));
$luokka=$_GET['l'];
$tunti=$_GET['t'];
$result = mysql_query("SELECT * FROM `ATK` WHERE `PVM`='{$paiva}' AND `luokka`='{$luokka}' AND `tunti`='{$tunti}'");

$row = mysql_fetch_array($result);
switch($row['osat']){
Case "1":Case "2":
if(mysql_num_rows($result)<3){
print "<img src=\"kuvat/tick.png\" class=\"loader\" alt=\"\" /> <b>Vapaa: 40 min</b><br /><br /><input type=\"submit\" value=\"Varaa\" />";
}else{
print "<img src=\"kuvat/close.png\" class=\"loader\" alt=\"\" /> <b>Ei vapaana!</b><br />Vaihda hakuehtoja.<br />";
}
break;
Case "3":
print "<img src=\"kuvat/close.png\" class=\"loader\" alt=\"\" /> <b>Ei vapaana!</b><br />Vaihda hakuehtoja.<br />";
break;
default:
print "<img src=\"kuvat/tick.png\" class=\"loader\" alt=\"\" /> <b>Vapaa: koko tunti</b><br /><br /><input type=\"submit\" value=\"Varaa\" />";
}

?>