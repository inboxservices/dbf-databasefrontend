<?

// leoschen / updaten und neu eintragen
// sql-staetements aus obigen daten generieren
// und abfrage durchfuehren.

if ( $schritt != "" )
{
 $sql["sdelete"]="DELETE FROM $table where `" . $k[0] . "`='$show'"; // loeschen
 $sql["supdate"]="UPDATE $table SET $kboth where `". $k[0] . "`='$show'"; // update
 $sql["sinsert"]="INSERT INTO $table ( $kkeys ) VALUES ( $kvals )"; // insert
 $sql["zeig"]="SELECT * FROM $table where `" . $k[0] . "`='$show'"; // insert
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
  $infotext = ("<br>Status: <font size='-5'>Daten wurden <b> nicht </b> $brin. Ein Fehler is aufgetreten.<br>");
 }
 else
 {
  $infotext = ("<br>Status: <font size='-5'>Eintragung $brin.<br>");
 }

  $debug_status = $debug_status . "iud: $sql <br> $myer";

 mysql_close($conn);
}

?>
