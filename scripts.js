function showlogin(){
    $('#logd1').hide();
    $('#logd2').show();
    $('#logd3').hide()}
function lopeta(){
    $('#logd1').show();
    $('#logd2').hide();
    $('#logd3').hide();
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
    $('#pimennys').show();
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
$("#saveyes").click(function(){
// savebook():n toiminnallisuus tähän...
});
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