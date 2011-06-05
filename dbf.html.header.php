<html>
<head>
<style type="text/css">
.splitter { background-color: #ffffff; width: 2px; height: 100%; }
</style>
 <LINK href="dbf.style.css" type="text/css" rel=stylesheet>

<script>
<?
include("dbf.js.php");
?>

function addtext (content)
{
 document.getElementById('sqlstatement').value= document.getElementById('sqlstatement').value + content;
}

function checkRows(textArea)
{
 if (navigator.appName.indexOf("Microsoft Internet Explorer") == 0)
 {
  textArea.style.overflow = 'visible';
  return;
 }

 while ( textArea.rows > 1 && textArea.scrollHeight < textArea.offsetHeight )
 {
  textArea.rows--;
 }

 while (textArea.scrollHeight > textArea.offsetHeight)
 {
  textArea.rows++;
 }
 textArea.rows++;
 return;
}

function handleMouseDown( ev )
{
 posX = ev.screenX;
 elementId = ev.target.id;
}

function handleMouseUp( ev )
{
 posY = ev.screenX;
 elempos = ( posX - posY ) / 6 ;
 elementId = ev.target.id;

 if ( elementId )
 {
  zahl = document.getElementById('menginfo').value;
  for ( var i = 0; i <= zahl; i++ )
  {
   elemid = elementId + "_" + i;
   if ( document.getElementById( elemid ) )
   {
    elemgo = document.getElementById( elemid ).size - elempos;
    document.getElementById( elemid ).size = elemgo;


 db = document.getElementById('db').value;
 t = document.getElementById('t').value;
 size = elemgo;
 feld = elementId;

 param='db='+db+'&t='+t+'&size='+size+'&feld='+feld;

 xmlHttp.onreadystatechange = handleServerResponse;
 xmlHttp.open("POST", "dbf.conf.html.php"+'?'+Math.random(), true); 

 xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 xmlHttp.setRequestHeader("Content-length", param.length);
 xmlHttp.setRequestHeader("Connection", "close");

 // make the server request
 xmlHttp.send(param);


   }
  }
 }
}

document.onmousedown = handleMouseDown;
document.onmouseup = handleMouseUp;

</script>
</head>

<BODY onLoad="checkRows(sqlstatement)" text="#000000" bottomMargin=0 vLink="#000000" link="#000000" bgColor="#ffffff" leftMargin=0 topMargin=0 rightMargin=0 marginwidth="0" marginheight="0" alink="#000000">

 <table width="99%" border="0" bgColor="#cdcdcd">
  <tr>
   <td width="50"> &nbsp;</td>

   <td width="200" rowspan="2">
    <b> &nbsp; &nbsp; <a href=''>dbadmin</a> </b> ( <? echo( "auf: " . $_SERVER['HTTP_HOST'] ); ?> )

    <ul>

<? if ( $dbmore == 0 ) { ?>
     <li> Datenbank: <? echo("$database"); ?> </li>
<? } if ( $tmore == 0 ) { ?>
     <li> Table: <? echo("$table"); ?> </li>
<? } ?>

<? if ( $authlogin == 1 ) { ?>
     <li>user eingeloggt: <i><? echo("$phpau"); ?></i></li>
     <li><a href="?action=logOut"> logout </a></li>
<? } ?>

    </ul>

   </td>

   <td>
       <form action='' method='POST'>
       <textarea id='sqlstatement' name='sqlstatement' cols='50' rows='2' style="background:#9BCCCA; width:100%; overflow:visible; font-size:8pt" onkeyup="checkRows(this)"><? echo("$wassqlstate"); ?></textarea>
       <input type='hidden' name='wassql' value='freitext'>
       <input type='Submit' name='go' value='sql-direct' style="background:#9BCCCA; font-size:8pt; position:absolute; top:5px; right:17px">
       <input type='hidden' name='t' id='t' value=<? echo("$t"); ?>>
       <input type='hidden' name='db' id='db' value=<? echo("$db"); ?>>

	<a href="" onclick="addtext(' order by id desc '); document.getElementById('sqlstatement').focus(); return false;">order</a>
	<a href="" onclick="addtext(' limit 10'); document.getElementById('sqlstatement').focus(); return false;">limit 25</a>

      </form>
   </td>
  </tr>

  <tr>
   <td> &nbsp;</td>
   <td>

     <table><tr><td> 
        <form action='' method='POST'>
         <input type='Text' name='sqlsuche' value="volltextsuche alle felder" size='25' maxlength='255' style="background:#9BCCCA; font-size:8pt">
	 <input type='hidden' name='wassql' value='suche'>
         <input type='hidden' name='t' value=<? echo("$t"); ?>>
         <input type='hidden' name='db' value=<? echo("$db"); ?>>
	 <input type='Submit' name='go' value='suche' style="background:#9BCCCA; font-size:8pt">
	</form>
     </td><td>
        <? echo("$tablenauswahl"); ?>
     </td><td>
        <? if ( $dbmore==1 ) {echo("$databaseauswahl"); } ?>
     </td><td>

        <form action='' method='POST'>
         <input type='hidden' name='t' value=<? echo("$t"); ?>>
         <input type='hidden' name='db' value=<? echo("$db"); ?>>
         <input type='hidden' name='csvausgabe' value='0'>
         <input type='hidden' name='csvebene' value='1'>
         <input type='Submit' name='go' value='csv-exporter' style="background:#9BCCCA; font-size:8pt">
        </form>

     </td></tr></table>

   </td>
  </tr>
 </table>

<br>
