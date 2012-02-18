<h1>Varaus</h1>
<table>
<tr><td>Ajankohta</td><td><?=date("j.n.Y",strtotime("+".$_POST['pvmlist']." days"))?>; <?=$_POST['tlist']?>. tunti</td></tr>
<tr><td>Luokka</td><td>Luokka <?=$_POST['llist']?></td></tr>
<tr><td>Varaaja</td><td><input /></td></tr>
</table>