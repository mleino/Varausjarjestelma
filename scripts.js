function showlogin(){
    document.getElementById('logd1').style.display='none';
    document.getElementById('logd2').style.display='block';
    document.getElementById('logd3').style.display='none';}
function lopeta(){
    document.getElementById('logd1').style.display='block';
    document.getElementById('logd2').style.display='none';
    document.getElementById('logd3').style.display='none';
    $.ajax({
	    type: "POST",
		url: "logout.php",
		data: "",
		}).done(function( msg ) {
		$.jGrowl("<img src='kuvat/lukko.png' alt='' style='float:left;' /> Olet nyt uloskirjautunut");
		$("#thelock").fadeOut("slow",function(){
		$("#thelock").attr('src','kuvat/lukko.png');
		$("#thelock").fadeIn("slow");
		});
		});}
function kirjaudu(){
    $user = $("#user").val();
    $pass = $("#pass").val();
	$("#user").attr("disabled","disabled");
	$("#pass").attr("disabled","disabled");
    $.ajax({
	    type: 'POST',
		url: 'login.php',
		data: {user: $user,
		       pass: $pass
		    },
		statusCode: {
		401: function() {
		$.jGrowl("Tunnus ei täsmää salasanan kanssa", { header: 'Kirjautuminen epäonnistui'});
		$("#user").attr("disabled","");
		$("#pass").attr("disabled","");
		}
		},
		success: function (msg){
		$("#user").attr("disabled","");
		$("#pass").attr("disabled","");
		$("#logd1").hide();
		$("#logd2").hide();
		$("#logd3").show();
		$("#theuser").html(msg);
		$("#thelock").fadeOut("slow",function(){
		$("#thelock").attr('src','kuvat/lukko2.png');
		$("#thelock").fadeIn("slow");
		});
		$("#user").val('');
		$("#pass").val('');
		$.jGrowl("<img src='kuvat/lukko2.png' alt='' style='float:left;' /> <b>" + msg + "</b><br />Tervetuloa varausjärjestelmään, " + msg + "!");
		}
		})
}
function lkr(lmw,lmc){
    document.location='?week=' + lmw + '&class=' + lmc;
}
function nonetwork(){
    document.getElementById('pimennys').style.display='block';
}
function vrdat(var1,var2,var3,var4,var5,var6){
    document.getElementById('vrdat').innerHTML="<h3><img src=\"kuvat/reika.png\" /> Tietoja varauksesta</h3><table cellpadding=\"3\" cellspacing=\"0\"><tr><td><b>Varaaja</b></td><td>" + var1 + "</td></tr><tr><td><b>Luokka</b></td><td>" + var2 + "</td></tr><tr><td><b>Päivämäärä</b></td><td>" + var3 + "</td></tr><tr><td><b>Tunti</b></td><td>" + var4 + "</td></tr></table><img src=\"kuvat/stamp.png\" alt=\"Approved\" class=\"leima\" /><br /><br /><a href=\"javascript:dialogi('varausboxi2',false)\">[X] Sulje</a>";
}

function vbox(bd,bl,siemen,bp){
$('#infobox').show();
$('#form').attr('action', 'book.php?bd='+bd+'&bl='+bl+'&bp='+bp);
switch(bd){
case '0':
rpv="Maanantai";break;
case '1':
rpv="Tiistai";break;
case '2':
rpv="Keskiviikko";break;
case '3':
rpv="Torstai";break;
case '4':
rpv="Perjantai";break;
case '5':
rpv="Lauantai";break;
default:
rpv="Sunnuntai";break;
}
$('#boxv').text(rpv + ', ' + bl + '. tunti');
$('#infobox').css("top",($('#b'+siemen).offset().top+16) + 'px');
$('#infobox').css("left",($('#b'+siemen).offset().left-105) + 'px');
$('#infobox').animate({"top":($('#b'+siemen).offset().top+4) + 'px'},"fast");

}
$(document).ready(function() {
$("#wait").hide();
$("#lukkari").animate({"opacity":"1"},"slow");
});

function savebook(){
$("#saveyes").hide();
$("#saveno").hide();
$("#hload").show();
$.ajax({
type: "POST",
url: "savebook.php",
data: "",
}).done(function( msg ) {
$.jGrowl("Varaus tallennettu!");
$("#hload").hide();
$("#saveyes").show();
$("#saveno").show();
$('#infobox').fadeOut('fast');});}
function dialogi(dlgnm,dlgval){
if(dlgval==true){
$('#' + dlgnm).show();
$('#fade').fadeIn('normal');
$('#' + dlgnm).animate({'left':'30%'},'normal');
}else{
$('#fade').fadeOut('normal');
$('#' + dlgnm).animate({'left':'-30%'},'normal',function(){
$('#' + dlgnm).hide();
});
}}

$('#dialog').dialog({show:"fade",hide:"fade",autoOpen:false,width:400,draggable:true,buttons: {'35 min': function() { $( this ).dialog( 'close' );$.jGrowl('Varaus tallennettu!<br /><br /><small>Varauksen tunniste: #01121101</small>');},'75 min': function() { $( this ).dialog( 'close' );}}});