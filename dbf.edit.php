<?
###################################################################
##
## dbf v 1.1
## mysql DatenBank-Frontend mit generischer struktur
## (c) 2011/02 klaus oblasser
## mail: dbf@ls.to
##
###################################################################

$zeileausgabe=1;

echo("<font face=arial size=1><br><i> $infotext </i><br>");

$conn2=mysql_connect("$host", "$user", "$password");
if(!mysql_select_db($database,$conn2)){echo ("Cannot select database.\n");}

$sql2="select * from $table " . $sortorder[$t];

if ( $single == 1 )
{
 $sql2="select * from $table where $kprimary";
}
else
{

 if ( $wassql == "suche" )
 {
  $sql2= "Select * FROM $table where " . $ssuche;
  $wassql = 1;
 }

 if ( $wassql == "freitext" )
 {
  $sql2=$sqlstatement;
  $wassql = 1;
 }

 if ( $ursql != "" )
 {
  $sql2 = urldecode($ursql);
  // echo("------- $sql2 ---------");
 }

}
$ursql = urlencode($sql2);

$debug_status = $debug_status . " :: $wassql :: $sql2<br>";
if(!($result2=mysql_query($sql2,$conn2)))
  {
  echo (" Error selecting data from mail-table. \n");
  }
  else
  {
  
     echo "<ul><table bgcolor='#cdcdcd'>";
     echo "<tr bgcolor='#ababab'><td></td><td bgcolor='#efefef'> UPD </td><td> DEL </td><td bgcolor='#efefef'> SHOW </td> $beschriftung </tr>";

    $y=mysql_num_fields($result2);
    $meng=mysql_num_rows($result2);

    echo("<font face=arial size=1><br><i>1 bis $meng von $meng </i><br>
    <input type='hidden' id='menginfo'  name='menginfo' value='$meng'>
    ");

   $sp_tr=0;
   while(($data2=mysql_fetch_row($result2)))
   {
    $b_update = "";
    $b_update2 = "";
    $b_primaryupdate = "";
    $sp_tr++;

   // bestuecken der zeilen
   // fuer "update" muss dies hier erfolgen, da die data[]-variablen nur hier gefunden werden.
   // man koennt dies als funktion in die library rausgeben.
   
   {

    for ($x=0; $x<$y; $x++)
    {
     $kkeyfuell = mysql_field_name($result2, $x);
     $feldwx = $feldw[$db][$t][$kkeyfuell]; if ( $feldwx =="" ){ $feldwx=$feldwy; }
     $feldzx = $feldz[$db][$t][$kkeyfuell]; if ( $feldzx =="" ){ $feldzx=$feldzy; }

     // primary_keys sammeln begin
     if ( mysql_fetch_field($result2, $x)->primary_key == "1")
     {
      $b_primaryupdate = $b_primaryupdate . "<input type='hidden' name='pkey[" . $kkeyfuell . "]' value='$data2x'>\n";
     }
     // primary_keys sammeln end

     // singleeintrag
     //if ( $single == 1)
     {
      $t_type  = mysql_field_type($result2, $x);
      $t_len   = mysql_field_len($result2, $x);
      $t_flags = mysql_field_flags($result2, $x);

      $data2x = htmlspecialchars( $data2[$x] , ENT_QUOTES );
      if ( ($t_type == "blob") )
      {
       $b_update2 = $b_update2 . "<tr><td><font face=arial size=1>$kkeyfuell </td><td> <textarea name='$kkeyfuell' rows='6' cols='85' style='background:lemonchiffon; font-size:8pt'>$data2x</textarea></td><td>$x : [$t_type] </td><td> [$t_len] </td><td> [$t_flags]</td></tr>";
 
      }
      else
      {
       $b_update2 = $b_update2 . "<tr><td><font face=arial size=1>$kkeyfuell </td><td> <input type='Text' name='$kkeyfuell' value='$data2x' size='100' style='background:lemonchiffon; font-size:8pt'></td><td>$x : [$t_type] </td><td> [$t_len] </td><td> [$t_flags]</td></tr>";
      }

     }
     // uebersichtseintrag
     if ( in_array($x, $zeigefelder[$t]) OR $zeigeallefelder[$t] == 1 )
     {
      if ( $feldz[$db][$t][$x] == 1)
      {
       // $debug_status = $debug_status . "feld " . $x . " unsichtbar:" . $feldz[$db][$t][$x] . "<br>";
      }
      else
      {
       $b_update = $b_update . "<td  id='bu_".$x."_".$sp_tr."'><font face=arial size=1>
       <input id='" . $kkeyfuell . "_" . $sp_tr . "' type='Text' name='$kkeyfuell' value='$data2x' size='$feldzx' style='background:lemonchiffon; font-size:8pt'></td>";
      }
     }
    }
   }

    // formular mit update-feldern zusammenbauen
    echo "
    <form action='' method='POST'><tr>
     <td>		     <input type='submit' name='add' value='go'></td>
     <td bgcolor='#efefef'>  <input type='radio' name='schritt' value='supdate'></td>
     <td>		     <input type='radio' name='schritt' value='sdelete'></td>
     <td bgcolor='#efefef'>  <input type='radio' name='schritt' value='zeig' checked></td>
    " . $b_update . "
    " . $b_primaryupdate . "
    <input type='hidden' name='show' value='$data2[0]'>
    <input type='hidden' name='ursql' value='$ursql'>
    <input type='hidden' name='wassql' value='$wassql'>
    <input type='hidden' name='t' value='$t'>
    <input type='hidden' name='db' value='$db'>
    </tr></form> \n";

    $zeileausgabe++;
    if ( $zeileausgabe % 19 == 0 )
    {
     $zeileausgabe = 0;
     echo("<tr bgcolor='#ababab'><td></td><td bgcolor='#efefef'> UPD </td><td> DEL </td><td bgcolor='#efefef'> SHOW </td> $beschriftung </tr>");
    }


    $b_update_single = "<br><br><br>
    <table border='0' bgcolor='#cdcdcd'><form action='' method='POST'>
    <tr>
      <td>
      </td>
      <td>
       <table><tr>
        <td>insert<input type='radio' name='schritt' value='sinsert' checked></td>
        <td>update<input type='radio' name='schritt' value='supdate'></td>
        <td>delete<input type='radio' name='schritt' value='sdelete'></td>
        <td> <input type='submit' name='add' value='go'></td>
       </tr></table>
      </td>
      <td>type</td>
      <td>len</td>
      <td>flags</td>
     </tr>
    " . $b_update2 . "
    " . $b_primaryupdate . "
    <input type='hidden' name='show' value='$data2[0]'></td>
    <input type='hidden' name='singleeintrag' value='1'></td>
    <input type='hidden' name='ursql' value='$ursql'>
    <input type='hidden' name='wassql' value='$wassql'>
    <input type='hidden' name='t' value='$t'></tr>
    <input type='hidden' name='db' value='$db'></tr>
    </form></table>";
   }
    if ( $zeileausgabe > 10 )
    {
     echo("<tr bgcolor='#ababab'><td></td><td bgcolor='#efefef'> UPD </td><td> DEL </td><td bgcolor='#efefef'> SHOW </td> $beschriftung </tr>");
    }
    // formular fuer einfuegen zusammenbauen
    echo "
    <form action='' method='POST'>
    <tr>
    <td colspan='2'><font face=arial size=1><input type='Submit' name='hinzufuegen' value='new'></td>
   " . $b_insert ."
    <input type='hidden' name='t' value='$t'>
    <input type='hidden' name='db' value='$db'>
    <input type='hidden' name='schritt' value='sinsert'>
    </tr></form>";

    echo "
    <form action='' method='POST'>
    <tr>
    <td colspan='3'><font face=arial size=1>sichtbarkeit: </td>
   " . $b_sicht ."
    <input type='hidden' name='t' value='$t'>
    <input type='hidden' name='db' value='$db'>
    </tr></form></table> ";


        if ( $single == 1)
        {
         echo("$b_update_single");
        }
    
    
  mysql_close($conn2);

}

?>
