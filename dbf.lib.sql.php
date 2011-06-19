<?
###################################################################
##
## dbf v 1.1
## mysql DatenBank-Frontend mit generischer struktur
## (c) 2011/02 klaus oblasser
## mail: dbf@ls.to
##
###################################################################


if ( $schritt != "" )
{
// $sql["sdelete"]="DELETE FROM $table where `" . $k[0] . "`='$show'"; // loeschen
// $sql["supdate"]="UPDATE $table SET $kboth where `". $k[0] . "`='$show'"; // update
// $sql["zeig"]="SELECT * FROM $table where `" . $k[0] . "`='$show'"; // zeig

 $sql["sdelete"]="DELETE FROM $table where $kprimary"; // loeschen
 $sql["supdate"]="UPDATE $table SET $kboth where $kprimary"; // update
 $sql["zeig"]="SELECT * FROM $table where $kprimary"; // zeig

 $sql["sinsert"]="INSERT INTO $table ( $kkeys ) VALUES ( $kvals )"; // insert
 $brin["sdelete"]="geloescht"; // loeschen
 $brin["supdate"]="geaendert"; // update
 $brin["sinsert"]="eingetragen"; // insert
 $brin["zeig"]="angezeigt"; // insert

 $sql = $sql["$schritt"];
 $brin = $brin["$schritt"];

 $conn=mysql_connect("$host", "$user", "$password");
 if(!mysql_select_db($database,$conn)){echo ("Cannot select database.\n");}

 if(!($result=mysql_query($sql,$conn)))
 {
  $myer = mysql_error($conn);
  $infotext = ("<br>Status: <font size='-5'>Daten wurden <b> nicht </b> $brin. Ein Fehler is aufgetreten.<br><b> $myer</b><br>");
 }
 else
 {
  $infotext = ("<br>Status: <font size='-5'>Eintragung $brin.<br>");
 }

  $debug_status = $debug_status . "iud: $sql <br> $myer";

 mysql_close($conn);
}

?>

