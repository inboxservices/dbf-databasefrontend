<?
// error_reporting(E_ALL);
error_reporting(E_ERROR|E_WARNING);

// datenbank-editing-admin mit generischer struktur
// single-file version ( db.php )
// v 1.01b
// dbadmin (c) 2011/02 klaus oblasser
// mail: ls@ls.to

// #A gundconfig
// zugang per htaccessabfrage

$authlogin = 1;
$_user_ = 'dbf';
$_password_ = 'dbf';

// zugangsdaten zur db

 // direkte zugangsdaten mysql
$host="localhost";
$user="";
$password="";
$database="";

$dbmore = 1; // weitere dbs anzeigen ?

// erste tabelle (ansonsten sucht es sich eine aus.)

//$table="letter"; // nur eintragen, wenn dbmore = 0!!
$tmore = 1; // weitere tabellen anzeigen ?

//$wassqlstate="SELECT * from $table where `$k[0]` LIKE \"% %\"";
$wassqlstate_start="Select * FROM $t where ` ` LIKE \"% %\"";

// debugstatus (1=on 0=off)
$debug = 1;

// feldbreiten: td width=feldw
$feldwy = 10; // feldbreiten

// variablenlaengen name=x size=feldz
$feldzy = 20; // variablenlaengen

$abnunforschleife = 50;

// #B spezialwerte je tabelle

// sortier/ausgabe (for oder while)
$sqlsort['categories'] = "0"; // while
$sqlsort['ausfall'] = "1"; // for
$sortorder['products'] = "order by `products_model` DESC";


// beispiel:
// tabellen
$zeigeallefelder['letter'] = "1"; // alle felder ein = 1
$zeigefelder['letter'] = array("0", "2", "3");


$f = fopen ("dbf.conf.html.txt", "r");
$ln= 0;
while ($line= fgets ($f))
{
 $line;
 list($db_1, $t_1, $feld_1, $size_1) = explode(":", $line);
 $feldz[$db_1][$t_1][$feld_1] = $size_1;
}
fclose ($f);

// config ende
// suchfelduebergabe
if ( $sqlstatement == "" ){ $wassqlstate = $wassqlstate_start; }
else{ $wassqlstate = $sqlstatement; }
if ( $ursql != "" ) { $wassqlstate = urldecode($ursql); }

if ( !$t ) { $wassqlstate = ""; }

?>
