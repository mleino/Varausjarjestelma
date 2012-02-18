<?php
if((!isset($_GET['week'])) OR (!isset($_GET['class']))){
header("Location: ?week=0&class=1&bid=".$_GET['id']);
}
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
  <meta name="generator" content="Notepad++" />
  <meta name="author" content="Petja Touru" />
  <meta http-equiv="Content-Language" content="fi" />
  <meta name="description" content="ATK-luokanvarausjärjestelmä" />
  <meta name="keywords" content="atk,luokka,varaus,varausjärjestelmä,resurssi" />
  <meta name="copyright" content="Petja Touru" />
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="styles.css" />
  <link type="text/css" href="jquery/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
  <link type="text/css" href="jquery/jquery.jgrowl.css" rel="stylesheet" />
  <script type="text/javascript" src="jquery/jquery-1.6.2.min.js"></script>
  <script type="text/javascript" src="jquery/jquery-ui-1.8.16.custom.min.js"></script>
  <script type="text/javascript" src="jquery/jquery.jgrowl.js"></script>
  <link rel="SHORTCUT ICON" href="kuvat/favicon.png" />

  <title>Varausjärjestelmä - Kv <?=date("W")+$_GET['week']?></title>
  <style type="text/css">
/*<![CDATA[*/
  td.c1 {width:150px; text-align:right;}
  /*]]>*/
  </style>
</head>

<?php
if(isset($_POST['len'])){
print "<script>$(document).ready(function() "."{"."$.jGrowl('".$_POST['len']."uutin varaus tehty!');"."}".");</script>";
}

if(!$_SESSION['userid']==""){
print "
<style>
#logd1{display:none;}#logd3{display:block;}
</style>
";}

include_once('class.php');
?>




<?php


// Tätä ehkä voisi yksinkertaistaa. En osaa, vielä.
// -varaus kuuluu muualle kuin sinne missä se nyt on.
//

$vara = new varaus(); //olio

//
// Haetaan get-parametrit!
//
    
$vara -> viikko = $_GET['week'];
if (!isset( $vara -> viikko )){
  $vara -> viikko = 0; 
}

$vara -> luokka = $_GET['class'];
if (!isset( $vara -> luokka )){
  $vara -> luokka = 1; 
}


$varaukset = $vara -> viikonvaraukset(); //Taulukko olioista. 

if((isset($_GET['bid'])) && (!$_GET['bid']=="")){
print "<script>$(document).ready(function(){vrdat('PETJA','40','31.1.','2','31.1.2012 klo 17:43','','2',document.title,'1');});</script>";
}

?>

<body>
<div id="wait">Ole hyvä ja kytke JavaScript päälle käyttääksesi varausjärjestelmää</div>

  <!-- On vapaana, ei varattu -->
  <div id="dialog" title="Varaa luokka">
Valitse varauksen pituus<br /><br />
<span class="sivuhuom"><b>Huom!</b> Joku toinen on saattanut jo varata osan tunnista itselleen.</span>
  </div>
  
  
  <!-- On varattu, ei vapaana -->
    <div id="varausboxi2" class="white_content">
	<div id="vrdat">
	</div>
  </div>

  <div id="fade" class="black_overlay"></div>
 
  <div class="bar bar1"><span>Varaus on tallennettu!</span></div>

  <div id="otsake">
  <div id="tayte"></div>
    <table>
      <tr>
        <td>
          <h1>Varausjärjestelmä</h1>
          <h2 id="ylos">www-käyttöliitymä koululuokkien varaamiseen</h2>
        </td>
        <td class="c1">
          <div id="paiva">
            <?php
            $kuut=array("Joulu","Tammi","Helmi","Maalis","Huhti","Touko","Kesä","Heinä","Elo","Syys","Loka","Marras");
            print "Viikko<br /><span id=\"vuosi\">".(date("W")+$_GET['week'])."</span>";
            ?>
          </div>
        </td>
      </tr>
    </table>
	</div>
    <script src="scripts.js" type="text/javascript"></script>

    <?php
    function nappi($vrs,$vv,$vj,$i2){
      $tok="<div class=\"napsu noava\">".$vj[1]."<div>X</div></div>";
      $tno="<div class=\"napsu\" onclick=\"document.location='?p=2&v=".$vrs."';\">&nbsp;</div>";
      if($vv==1){
        if($i2==$vj[2]){
          return $tok;
        }else{
          return $tno;
        }}else{
          return $tno;
        }}
    ?>
	
	
		<table cellspacing="0" cellpadding="5" class="loginarea" id="loginarea"><tr>
	<td id="lukin"><img id="thelock" src="kuvat/lukko.png" alt="" /></td>
	<td id="logds">
	<div id="logd1">
	<a href="javascript:showlogin();" style="color:#000;font-weight:bold;">Kirjaudu sisään</a>
	</div>
	<div id="logd2">
	<form action="#" method="post" onsubmit="kirjaudu();return false;">
	<input id="user" autocomplete="off" type="text" placeholder="Tunnus" /> 
	<input id="pass" autocomplete="off" type="password" placeholder="Salasana" /> 
	<input id="logbut" type="submit" value="Kirjaudu" />
	</form>
	</div>
	<div id="logd3">
	<span id="theuser"><?=$_SESSION['name']?></span><br />
	<a href="sets.php">Asetukset</a> &nbsp; <a href="javascript:;" id="myb">Omat varaukset <img src="kuvat/rr.png" alt="V" /></a> &nbsp; <a href="logout.php" onclick="lopeta();return false;">Ulos</a>
	</div>
	</td>
	</tr></table>
	<br /><br />
    <div class="rapido">
    <table id="lukkari">
        <tr valign="top"><td rowspan="6">
		<div class="menu"><b><img src="kuvat/kalenteri.png" class="mi" /> Viikko <?=date("W")+$_GET['week']?></b></div>
		<div class="menu"><img src="kuvat/mitta.png" class="mi" /> Älykäs varaus</div>
		</td><td></td><td><b>
        <?php 
          for ($d=0; $d<=4; $d++){
	    echo $vara -> paiva_ja_nimi($d, $vara -> viikko ) . "</b></td><td><b>";
          }
        ?>
        </b></td></tr>



        <?php
        for($t=1;$t<=5;$t++){
          echo "\n<tr><td><b>".$t."</b></td><td>";
	  for($d=0;$d<=4;$d++){
              
	    echo $vara -> tulostavaraus($vara -> viikko, $d, $t, 1, $varaukset);
	    echo $vara -> tulostavaraus($vara -> viikko, $d, $t, 2, $varaukset);
	       echo "</td><td>";
		       }
        print "</tr>";
        }
        ?>
        </table><br />
        <a rel="nofollow" href="?class=<?php print $vara -> luokka ?>&week=<?php print ($vara -> viikko) -1 ?>">&lsaquo; Edellinen viikko</a> <span style="float:right"><a rel="nofollow" href="?class=<?php print $vara -> luokka ?>&week=<?php print ($vara -> viikko) +1 ?>">Seuraava viikko &rsaquo;</a></span>
		
		    <div style="text-align:center">
			Viikko <?=date("W")+$_GET['week']?>
    <?php
	/*
	Siirretään jonnekin muualle....
	
	switch($_GET['class']){
	Case "1":default:
		print "<select id=\"lkr\" class=\"mcl\" onchange=\"lkr(".$_GET['week'].",this.value);\"><option value=\"1\" selected=\"selected\">Luokka 40</option><option value=\"2\">Luokka 23</option><option value=\"3\">Kielistudio</option></select><br /><br />";
		break;
	Case "2":
		print "<select id=\"lkr\" class=\"mcl\" onchange=\"lkr(".$_GET['week'].",this.value);\"><option value=\"1\">Luokka 40</option><option value=\"2\" selected=\"selected\">Luokka 23</option><option value=\"3\">Kielistudio</option></select><br /><br />";
		break;
	Case "3":
		print "<select id=\"lkr\" class=\"mcl\" onchange=\"lkr(".$_GET['week'].",this.value);\"><option value=\"1\" selected=\"selected\">Luokka 40</option><option value=\"2\">Luokka 23</option><option value=\"3\" selected=\"selected\">Kielistudio</option></select><br /><br />";
		break;
	}
	*/
    ?>
	</div>
	<table><tr><td><img src="kuvat/cc.png" alt="CC" /></td><td>
   <small><b>&copy; 2011-<?=date("Y")?> Markku Leino & Petja Touru</b><br />Ohjelmistoa koskee <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.fi">Creative Commons BY-NC-SA</a> -lisenssi</small>
	</td></tr></table>	
		
    </div>
  </div>

  <div id="pimennys">
    <h1>Yhteys keskeytynyt</h1>Tarkista Internet-yhteytesi ja paina sitten F5<br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />

    <div id="nonetwork" class="sprites"></div>
  </div>
  <div id="infobox">
  <form action="" method="post" id="form" onsubmit="$('#infobox').fadeOut('fast');">
  <span style="float:right"><img id="sulje" src="kuvat/sulje.png" alt="Sulje" onclick="$('#infobox').fadeOut('fast');" /></span>
  <span id="boxv">Varaa luokka</span><br /><br />
  Haluatko varmasti varata luokan itsellesi valittuna ajankohtana?
  <div id="tayte1"></div>
  <input type="button" value="Kyllä" id="saveyes" /> <input type="button" value="En" onclick="$('#infobox').fadeOut('fast');" id="saveno" /> <img src="kuvat/hload.png" alt="Ladataan" id="hload" /><br />
  </form>
  </div>
</body>
</html>
