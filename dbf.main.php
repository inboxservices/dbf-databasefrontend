<?
// datenbank-editing-admin mit generischer struktur
// single-file version ( db.php )
// v 0.996b
// dbadmin (c) 2011/02 klaus oblasser
// mail: ls@ls.to

include("dbf.vars.php");

session_start();

while ( list ($key,$val) = each ( $_REQUEST ) )
{
 $$key = "$val";
 $debug_status = $debug_status . "key-val: $key - $val <br>";

 if ( !$t ) {$datenbankleer=1;}
}

include("dbf.conf.php");

if ( $db != "") { $database=$db; }
else { $db=$database; }

if ( $t != "") { $table=$t; }
else { $t=$table; }


if ( $schritt == "zeig" ) { $single = 1; }

$debug_status = $debug_status . "ausgewaehlte tabelle: $table t: $t <br>\n";

if ( $authlogin == 1 )
{
 include("dbf.auth.php");
}
include("dbf.lib.field.php");

if ( $datenbankleer != 1 )
{
  include("dbf.lib.sql.php");
}

  if ( $csvausgabe != 2 )
  {
   include("dbf.html.header.php");
  }

  if ( $csvebene == 1 )
  {
   include("dbf.csv.php");
  }
  else
  {
  // sqlsort

   if ( $datenbankleer != 1 )
   {

    if ( $rowcount > $abnunforschleife AND isset($sqlsort[$t])===false ) { $sqlsort[$t] = 1; }

    if ( ( $sqlsort[$t] == 1 ) AND ( !$wassql ) )
    {
     include("dbf.edit.for.php");
     $debug_status = $debug_status . "sort: for - $t <br>\n";
    }
    else
    {
     $debug_status = $debug_status . "sort: while - $t <br>\n";
     include("dbf.edit.php");
    }

   }
  }

 $debug_status = $debug_status . "db:tablle: $datenbankleer <br>\n";

if ( ( $debug == 1 ) AND ( $csvausgabe != 2 ) )
{
 echo("<br><br><hr width='95%'><center> d.e.b.u.g = on </center><hr width='95%'><br>
 <ul><table bgcolor='#efefef'><tr><td><b> status </b></td></tr><tr><td> $debug_status </td></tr></table></ul>");
}

if ( $csvausgabe != 2 )
{
 include("dbf.html.footer.php");
}
?>
