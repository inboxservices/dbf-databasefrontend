<?
// datenbank-editing-admin mit generischer struktur
// single-file version ( db.php )
// v 0.996b
// dbadmin (c) 2011/02 klaus oblasser
// mail: ls@ls.to
// bereich: libs

// variablen leeren
$kkeys="";
$kvals="";
$kboth="";
$b_update="";
$b_insert="";
$beschriftung1="";
$beschriftung="";


 // tabellen der 1sten.datenbank auslesen und in select-box und links verfuegbar machen.
 $conn2=mysql_connect("$host", "$user", "$password");

if ( $dbmore == 1 )
{
 // beginn datenbank finden
 $db_liste = mysql_query("SHOW DATABASES;", $conn2);
 $men1 = mysql_num_rows($db_liste);
 while (($db_row = mysql_fetch_row($db_liste)))
 {
  $dbs[] = $db_row[0];
  $databaselast = $db_row[0];
  $db_options = $db_options . "<option value='$databaselast'> db: $databaselast </option>\n";
 }
}

 // mit irgendeiner datenbank beginnen. mir egal welche.
 if ( $database != "") { $database=$db; }
 else { $database=$databaselast; }

 $debug_status = $debug_status . "datenbanken: $men1 $database <br>";

 // ende datenbank finden

 if(!mysql_select_db($database,$conn2)){echo ("Cannot select database ($database).\n");}
   $tables = array();

   $result = mysql_query("SHOW TABLES FROM $database;", $conn2);
   $men = mysql_num_rows($result);

 if ( $men == 0 )
 {
  $debug_status = $debug_status . "datenbank hat keine tabellen<br>";
  $datenbankleer = 1;
 }
 else
 {
   $debug_status = $debug_status . "tabellen: $men <br>";

   while (($row = mysql_fetch_row($result)))
   {
    $rowholen = $row[0];
    $tables[] = $rowholen;
    $rowloast = $rowholen;
    $debug_status = $debug_status . "tabelle: $rowloast <br>";
    $table_links = $table_links . "<a href='?t=$rowloast'> tabelle: $rowloast</a><br>";
    $table_options = $table_options . "<option value='$rowloast'>t: $rowloast </option>\n";

    // mit irgendeiner tabelle beginnen. mir egal welche.
    if ( $t != "") { $table=$t; }
    else
    {
     $t=$rowloast; 
     $table=$rowloast;
    }

    if ( ( $zeigeallefelder[$rowloast] == "0" ) OR ( $zeigeallefelder[$rowloast] == "1" ) )
    {
     // noop
     // irgendwie klappte "!=" nicht. somit halt einmal loop.
    }
    else
    {
     $zeigeallefelder[$rowloast] = 1;
     $zeigefelder[$rowloast] = array("0", "1", "2", "3", "4");
     $debug_status = $debug_status . " $rowloast -- : $zeigeallefelder[$rowloast] <br>";
    }
   } 
 }


  if ( $tmore == 1 )
  {

    $tablenauswahl = "<form method='post' action=''><select id='t' name='t' style='background:#9BCCCA; font-size:8pt'>
	     <option value='$t'>t: $t </option>
	     <option value=''>------</option>
	     $table_options </select>
	     <input type='Submit' name='go' value='go' style='background:#9BCCCA; font-size:8pt'>
	    <input type='hidden' name='db' value='$db'>
	     </form>";
  }

  if ( $dbmore == 1 )
  {

    $databaseauswahl = "<form method='post' action=''><select id='db' name='db' style='background:#9BCCCA; font-size:8pt'>
	     <option value='$db'> db: $db </option>
	     <option value=''>------</option>
	     $db_options </select>
	     <input type='Submit' name='go' value='go' style='background:#9BCCCA; font-size:8pt'>
	     <input type='hidden' name='datenbankleer' value='1'>
	     </form>";
  }

if ( $datenbankleer != 1 )
{

 // felder beschriften und die benoetigten eingabefelder generieren 
 if ( $wassql == "freitext" )
 {
  $sql2=$sqlstatement;
 }
 else
 {
  //  $sql2="select * from $table";
  $sql2="select * from $t";
 }

 if ( $ursql != "" )
 {
  $sql2 = urldecode($ursql);
  // echo("------- $sql2 ---------");
 }

 // $debug_status = $debug_status . " $databaseauswahl  <br>";
 // $debug_status = $debug_status . " $tablenauswahl  <br>";
 $debug_status = $debug_status . " sql-staetement1: $sql2  <br>";

 $result2 = mysql_query($sql2,$conn2) or die(mysql_error());
 $rowcount=mysql_num_rows($result2);
 $y=mysql_num_fields($result2);

 $debug_status = $debug_status . " ---- $y ----   <br>";

 $sp_1=0;
 for ($x=0; $x<$y; $x++)
 {

  $sp_1++;
  if ( ( in_array($x, $zeigefelder[$t])  OR $zeigeallefelder[$t] == 1 ) OR ( $singleeintrag == 1 ) )
  {
  
   $kkeyfuell = mysql_field_name($result2, $x);
   if ( $x == "0" )
   {
    $kkeys = "`" . $kkeyfuell . "`" ;
    $kvals = "'" . mysql_real_escape_string( $$kkeyfuell ) . "'";
    $kboth = "`" . $kkeyfuell . "`='" . mysql_real_escape_string( $$kkeyfuell ) . "'";
    $ssuche="Select * FROM $table where `" . $kkeyfuell . "` LIKE '%$sqlsuche%'";
   }
   else
   {
    $kkeys = $kkeys . ",`" . $kkeyfuell . "`";
    $kvals = $kvals . "," . "'" . mysql_real_escape_string($$kkeyfuell) . "'";
    $kboth = $kboth . ",`" .  $kkeyfuell . "`='" . mysql_real_escape_string($$kkeyfuell) . "'";
    $ssuche= $ssuche .  " OR `" . $kkeyfuell . "` LIKE '%$sqlsuche%'";
   }
    $k[$x] = $kkeyfuell;
   
   if ( ( in_array($x, $zeigefelder[$t])  OR $zeigeallefelder[$t] == 1 ) )
   {
    if ( $x == 0 ) { $xs = "key!"; } else { $xs = ""; }
    $feldwx = $feldw[$db][$t][$kkeyfuell]; if ( $feldwx =="" ){ $feldwx=$feldwy; }
    $beschriftung = $beschriftung . "<td id='".$kkeyfuell."' style='width: " . $feldwx . "px;'> $kkeyfuell ($x $xs) </td>"; // beschriften

    $feldzx = $feldz[$db][$t][$kkeyfuell]; if ( $feldzx =="" ){ $feldzx=$feldzy; }
    $b_insert = $b_insert . "<td><font face=arial size=1>
    <input type='Text' id='" . $kkeyfuell . "_0' name='$kkeyfuell' value='' size='$feldzx' style='background:lemonchiffon; font-size:8pt'></td>";

   }

  }
 }
 $debug_status = $debug_status . " ---- $kboth ----   <br>";

} // datenbank leer

// echo($debug_status);

?>

