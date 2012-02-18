<?php
class MySQL_naama{
  var $host_name ='';
  var $user_name = '';
  var $password = '';
  var $db_name = '';
  var $conn_id = 0;  //Not actually a variable but an object or something
  var $errstr = '';
  var $halt_on_error = 1;
  var $query_pieces = array ( );
  var $result_id = 0;
  var $num_rows = 0;
  var $row = array ( );

  function connect(){
    $this -> errno = 0;   #Tyhj‰‰ virhemuuttuja
			    $this -> errstr = '';
    if ($this -> conn_id == 0){    // Yhdist‰ tietokantaan, jollei ole jo yhteydess‰
      try {
	
        $this -> conn_id = new PDO("mysql:host=".$this -> host_name . 
				   ";dbname=".$this -> db_name .
				   "", $this -> user_name, $this -> password);
      }
      catch(PDOException $e){
        $this -> error( $e -> getMessage() );
      }
      return ($this -> conn_id);
      
    }
  }
  function disconnect(){
    if ($this -> conn_id != 0){
      $this -> conn_id = null;
    }
  }
  function error($msg){
    if (!$this -> halt_on_error)
      return;
    $msg .= "\n";
    
    $this -> errstr = $msg;
    echo "X1: VIRHE!" .  $this -> errstr . "</br>";
    
    die (nl2br (htmlspecialchars ($msg)) );
  }} 


class varaus extends MySQL_naama{
 var $host_name ='localhost';
 var $user_name = '2957_HuhdinKoulu';
 var $password = 'Koulu1';
 var $db_name = '2957_Koulu';  

 var $pvm;
 var $tunti;
 var $varaaja;
 var $luokka;
 var $varausID;
 var $viikko;

  //
  //
  //
  //
     // function __construct(){
     //}
  //
  //
  //
  //
 function viikonvaraukset(){
   if ( empty( $this -> conn_id )  ) // Not connected
      $this -> connect();

    try{
      switch($this -> luokka ){
	Case 1:
	$lform="40";
	break;
	
	Case 2:
	$lform="23";
	break;
	
	Case 3:
	$lform="KS";
	break;
      }
	
      //Melkein toimiva. 
      //$sql = $this -> conn_id -> prepare("SELECT `luokka`, `syy`, `tunti`, `varaaja`, `lisatty`, `tunninosa`, DATE_FORMAT(`pvm`, '%d.%c.') AS `pvm` FROM `varaukset` WHERE `luokka` = '".$lform."' ORDER BY `tunti`, `pvm`"); //Hmm... eik‰ ASC- ja DESC-funktioista olisi hyˆty‰?
      //      

      $sql = $this -> conn_id -> prepare("SELECT varausID, luokka, syy, tunti, varaaja, lisatty, tunninosa, DATE_FORMAT(pvm, '%d.%c.') AS pvm FROM varaukset 
           WHERE `pvm` BETWEEN :pvmalku AND :pvmloppu AND `luokka` = :luokka ORDER BY tunti, pvm");
      
      
      $alkupvm = $this -> viikonpaivamaara(1, $this -> viikko);
      //      echo "XXX" . $alkupvm .  date(Y) . "</p><p>"; 
      //echo "XXX" . date("Y-m-d", strtotime( $alkupvm . date(Y) )); 
      //echo "YYY" . strtotime( $alkupvm . date(Y) )  ; 

      


      $sql->setFetchMode(PDO::FETCH_INTO, new varaus);
      
      $sql->execute( array( ':pvmalku'  => date("Y-m-d", strtotime( $alkupvm . date(Y) )), 
			    ':pvmloppu' => date("Y-m-d", strtotime("+" . ($this -> viikko+1) ." weeks")), 
			    ':luokka'   => 40 ) );

      
      while ($object = $sql->fetch()) {
	$result[] = clone $object;
      }

    } catch (PDOException $e) {
      $this -> error ( $e -> getMessage() );
    }
    
    //echo '<pre>';
    //print_r($result); 
    //echo '</pre>';

    if (!empty($result)){  
      return $result;
    }
    
  }

  //
  //
  //
  //
 function paiva_ja_nimi($offset, $viikko){
   // Metodi palauttaa p‰iv‰m‰‰r‰n ja paivan nimen, $offset on 
   // 0=maanantai, 1=tiistai, 2=keskiviikko jne. 

   //echo date("Y-m-d", strtotime(date("Y").'W'.date('W')."1")) . "<br/>";
   //echo date("Y-m-d", strtotime(date("Y").'W'.date('W')."7")) . "<br/>";


  $vp=array("Ma","Ti","Ke","To","Pe");

  return $vp[$offset] . " " . $this -> viikonpaivamaara($offset + 1, $viikko);

 }
 //
 // 
 // 
 function viikonpaivamaara($day, $viikko){
   // Palauttaa stringin, jossa on viikon p‰iv‰. 
   // Argumentti $day kertoo p‰iv‰t
   // 1=maanantai, 2=tiistai, 3=keskiviikko jne. 

   return date("d.n.", strtotime(date("Y").'W'.date('W').$day." +".($viikko)." weeks"));

 }
  //
  //
  //
  //
  function haetunti($ajoitus){
  $hh=date("H",$ajoitus);
  $mm=date("i",$ajoitus);
  switch($hh){
		Case "8":
		return ($mm<10) ? false : "1"; break;
		Case "9":
		return ($mm<40) ? "1" : "2"; break;
		Case "10":
		return ($mm<55) ? "2" : false; break;
		Case "11":
		return ($mm<30) ? false : "3"; break;
		Case "13":
		return "4";break;
		Case "14":
		return ($mm<30) ? "4" : "5"; break;
		Case "15":
		return ($mm<45) ? "5" : false; break;
  }}
  //
  //
  //
  //
  function tulostavaraus($viikko, $d, $t, $tunninosa, &$varaus){
   // argumentit:
   //  $d: p‰iv‰n numero 0,1,2, jne
   //  $t: tunnin numero
   //  &$varaus: viikon varaukset aikaj‰rjestyksess‰: ensin tunnit, sitten p‰iv‰t. 
   //  Tarkista ORDER BY MySQL-SELECT-k‰skyst‰.
   //

   $pvm =  $this -> viikonpaivamaara($d + 1, $viikko);

   if(count($varaus)>0){
   foreach ($varaus as $item){

     //     echo "<p>AAA" . $item -> pvm . "BBB" . $pvm . "</p>" ;

	if ( strcmp( $pvm ,$item -> pvm)==0  ){ //Tarkista onko p‰iv‰ oikea


		if ( $t == $item -> tunti ){
            if ( $tunninosa == $item -> tunninosa ){
				$var1 = $item->varaaja;
				$var2 = $item->luokka;
				$var3 = date("j.n.",strtotime($item->pvm));
				$var4 = date("j.n.Y \k\l\o H:i",strtotime($item->lisatty));
				$var5 = $item->syy;
				$var6 = strtotime($item->pvm);
				$var7 = $item->varausID;
				//T‰h‰n voisi laittaa lopetusehdon; BREAK 
				//Lis‰ksi pit‰isi poistaa jo k‰ydyt.
				}	
			}
		}
	}
	}
   
   $eisaatavilla = "<div class=\"napsu old osa".$tunninosa."\" title=\"Ei varattavissa\"></div>";
   
   // Tarkista tunti ja merkkaa ei saatavilla olevaksi ...
   $siemen=rand(1000000,9999999);
   
      if( ( ( $d >= date("w")-1) && $viikko == 0 ) || $viikko > 0   ){
	//Jos ei harmaata, eli tulostetaan oikeata. 
   //if( $viikko > 0  ){
   if (empty($var1)){
     $var = "<div class=\"napsu osa".$tunninosa."\" id=\"b".$siemen."\" onclick=\"vbox('".$d."','".$t."','".$siemen."','".$tunninosa."');\" title=\"Varaa tunti t‰h‰n klikkaamalla\"></div>";
   }else{
     $var = "<div class=\"napsu noava osa".$tunninosa."\" title=\"".$var2." varattu hlˆ:lle ".$var1."; ".$t.". tunnin ".(($tunninosa=="1")?"ensimm‰isell‰":"toisella")." puoliskolla\"><span>".$var1."</span><div class=\"close\" onclick=\"if(confirm('Poista varaus?')==true){window.location='http://petjatouru.com';}\">x</div></div>";
   }
   }else{
	 $var=$eisaatavilla;
   }

   return  $var;
 }
  //
  //
  //
  //
  function paivat($pp) {
      //perjantai = 5, lauantai = 6, sunnuntai = 0, maanantai = 1 jne.
      if(date("w")==6 OR date("w")==0){
        $vp2=$vp[date("w",strtotime("next monday +".$pp." days"))];
        return ucfirst(substr($vp2,0,2))." ".date("j.n.",strtotime("next monday +".$pp." days"));
      }else{
        $vp2=$vp[date("w",strtotime("next monday -1 week +".$pp." days"))];
        return ucfirst(substr($vp2,0,2))." ".date("j.n.",strtotime("next monday -1 week +".$pp." days"));
      }
    }
  //
  //
  //
  //
  private function nppv($pp) {
    if(date("w")==6 OR date("w")==0){
       return date("Y-m-d",strtotime("next monday +".$pp." days"));
    }else{
       return date("Y-m-d",strtotime("next monday -1 week +".$pp." days"));
    }
  }


  function tulostakaikki( $arr ){

    echo "\n"; 
    foreach ($arr as $item){
      echo '<li>' . $item -> pvm  . " X " . $item -> tunti . ". tunti "  . $item -> varaaja . '</li>' . "\n";
    }  

  }    
}
class kayttaja extends varaus{
	var $userid;
	var $name;
	var $lastlogin;
function kirjaudusisaan($user, $pwd){
    if ( empty( $this -> conn_id ) )
	$this -> connect();
	try{
	$sql = $this -> conn_id -> prepare("SELECT `userid`, `name`, `lastlogin` FROM `kayttajat` WHERE `userid` = :userid AND `password` = :password LIMIT 1");
	$sql->setFetchMode(PDO::FETCH_INTO, new kayttaja);
	$sql->execute( array( ':userid' => $user,
	':password' => $pwd ));
	$result = $sql -> fetchAll();
	} catch (PDOException $e) {
	$this -> error ( $e -> getMessage() );
	}
	return $result;
	}}
?>