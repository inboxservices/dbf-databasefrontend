<?
$csvzaehler=0;

if ( $csvausgabe == "0" )
{

$debug_status = $debug_status . "eingabe: $csvausgabe ";

echo("<ul><table><form action='' method='POST'>
<tr><td><b><i> csv-EXPORTER </i></b></td>	<td> </td></tr>
<tr><td> </td>	<td> </td></tr>
<tr><td> sql:	</td><td colspan='2'>	<input type='Text' name='sqlcsv' value='select * from $t' size='60' maxlength='255' style='background:#9BCCCA; font-size:8pt'>	</td></tr>
<tr><td><b> Ausgabe-Format </b></td>	<td> </td></tr>
<tr><td></td><td> feld-begrenzer:	</td><td width='100'>	<input type='Text' name='csvbegrenzer' value='|' size='5' maxlength='5' style='background:#9BCCCA; font-size:8pt'>	</td></tr>
<tr><td></td><td> zeilenende:		</td><td width='100'>	<input type='Text' name='csvende' value='\\n' size='5' maxlength='5' style='background:#9BCCCA; font-size:8pt'>	</td></tr>
<tr><td>	</td>			<td colspan='2'><input type='Submit' name='go' value='exportieren' style='background:#9BCCCA; font-size:8pt'>	</td></tr>
<tr><td>	</td>			<td colspan='2'><input type='hidden' name='db' value='$db'><input type='hidden' name='t' value='$t'>	</td></tr>
<tr><td>	</td>			<td colspan='2'><input type='hidden' name='csvebene' value='1'>	</td></tr>
<tr><td><b> Ausgabe-Art </b></td>	<td> </td></tr>
<tr><td></td><td width='100'>hier anzeigen</td>	<td><input type='radio' name='csvausgabe' value='1' checked>	</td></tr>
<tr><td></td><td width='100'>senden (downloaden)</td>	<td><input type='radio' name='csvausgabe' value='2'>	</td></tr>
</form></table></ul>");

}
else
{

$datum = time();

if ( $csvausgabe == "2" )
{
$debug_status = $debug_status . "eingabe: $csvausgabe ";

 header('Content-Type: text/x-csv');
 header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
 header('Content-Disposition: attachment; filename=export.mysql.' . $database . '.' . $t . '.' . $datum . '.csv');
 header('Pragma: no-cache');

}
else
{
$debug_status = $debug_status . "eingabe: $csvausgabe ";
 
}

$conn2=mysql_connect("$host", "$user", "$password");
if(!mysql_select_db($database,$conn2)){echo ("Cannot select database.\n");}

if(!($result=mysql_query($sqlcsv,$conn2)))
{
 echo (" Error selecting data from mail-table. \n");
}
 else
{
 $csv = "";
 while(($data=mysql_fetch_row($result)))
 {
  $b_csv ="";
  $c_csv ="";
  
  $y = mysql_num_fields($result);
  for ($x=0; $x<$y; $x++)
  {
   $kkeyfuell = mysql_field_name($result, $x);
   $c_csv = $c_csv . " $kkeyfuell $csvbegrenzer ";
   $b_csv = $b_csv . " $data[$x] $csvbegrenzer ";
  }
   $csvzaehler++;
   $searchcsv = array("\n", "\r\n", "<br>", "\r");
   $b_csv = str_replace($searchcsv, '', $b_csv);

   $csv = $csv ."$csvzaehler: $csvbegrenzer " . $b_csv . " $csvende \n";
  }
}    

$csv = "0: " .$csvbegrenzer . $c_csv . $csvende . "\n" . $csv;

mysql_close($conn2);

echo $csv;

}
?>