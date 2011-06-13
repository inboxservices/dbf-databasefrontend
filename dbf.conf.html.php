<?
###################################################################
##
## dbf v 1.1
## mysql DatenBank-Frontend mit generischer struktur
## (c) 2011/02 klaus oblasser
## mail: dbf@ls.to
##
###################################################################

$f = fopen ("dbf.conf.html.txt", "r");
$ln= 0;
while ($line= fgets ($f))
{
 list($db, $t, $feld, $size) = explode(":", $line);
 if ( $size != "" )
 {
  $feldz[$db][$t][$feld] = $size;
 }
}
fclose ($f);

// neue werte definieren
 $t_0 = $_POST['t'];
 $db_0 = $_POST['db'];
 $feld_0 = $_POST['feld'];
 $size_0 = $_POST['size'];
 $reset = $_POST['reset'];


 if ( $reset == 1 )
 {
  for ( $satz = 0 ; $satz <= $feld_0; $satz++ )
  {
   echo(" $feld ");
   $feldz[$db_0][$t_0][$satz] = 2 . "\n";
  }
 }
 else
 {
  $feldz[$db_0][$t_0][$feld_0] = $size_0 . "\n";
 }

// werte schreiben
$f = fopen ("dbf.conf.html.txt", "w");

while (list($db_2, $args1) = each($feldz))
{
 while (list($t_2, $args2) = each($args1))
 {
  while (list($feld_2, $size_2) = each($args2))
  {
   if ( $size_2 != "" )
   {
    $size_2 = str_replace("\n", "", $size_2);
    $size_2 = substr($size_2, 0, 4) . "\n";
    fputs ($f, "$db_2:$t_2:$feld_2:$size_2" );
   }
  }
 }
}

fputs ($f, "\n" );
fclose ($f);

?>
