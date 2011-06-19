<?
###################################################################
##
## dbf v 1.1
## mysql DatenBank-Frontend mit generischer struktur
## (c) 2011/02 klaus oblasser
## mail: dbf@ls.to
##
###################################################################

echo("<font face=arial size=1><br><i> $infotext </i><br>");

$conn2=mysql_connect("$host", "$user", "$password");
if(!mysql_select_db($database,$conn2)){echo ("Cannot select database.\n");}

$aktuell = $akt;

$sql2="select * from $table " . $sortorder[$db][$t];

if ( $single == 1 )
{
 $sql2="select * from $table where $kprimary";
}

if ( $wassql == "suche" )
{
 $sql2= "Select * FROM $table where" . $ssuche;
}

if ( $wassql == "freitext" )
{
 $sql2=$sqlstatement;
}

$debug_status = $debug_status . " :: $wassql :: $sql2<br>";

if(!($result2=mysql_query($sql2,$conn2)))
  {
  echo (" Error selecting data . \n");
  }
  else
  {
  
     echo "<ul><table bgcolor='#cdcdcd' border='0'>";
     echo "<tr bgcolor='#ababab'><td> </td><td bgcolor='#efefef'> UPD </td><td> DEL </td><td bgcolor='#efefef'> SHOW </td> $beschriftung </tr>";

    $y=mysql_num_fields($result2);




   $meng=mysql_num_rows($result2);
   //
   if ( $was2 == "n" )
   {
    $akt = $akt + 12;
   }
   if ( $was2 == "p" )
   {
    $akt = $akt - 12;
   }
   if ( $akt > $meng OR $akt < 0 )
   {
    $akt = 0;
    
   }
   $akt1 = $akt+1;
   $akt2 = $akt+12;
   if ( $akt2 > $meng )
   {
    $akt2 = $meng;
   }

   if ( !$akt ) { $akt=0; }
   $aktbis = $akt+12;
   if ( $meng < $aktbis ){ $aktbis=$meng; }
   $aktvon = $akt+1;
   echo("<font face=arial size=1><br><i> $aktvon - $aktbis von  $meng </i><br>
   <input type='hidden' name'menginfo' id='menginfo' value='12'>  ");

   if ($meng == 0 )
   {
    // nopp;
   }
   elseif(mysql_data_seek($result2, $akt))
   {
    for ( $satz = 1 ; $satz <= 12; $satz++ )
    {
     if(($data2=mysql_fetch_row($result2)))
     {


    // show-schleife!
    if ( ( $aktuell > 0 ) AND ( $schritt == "zeig") ) { $akt = $aktuell; }

    $b_update = "";
    $b_update2 = "";
    $b_primaryupdate = "";
   // bestuecken der zeilen
   // fuer "update" muss dies hier erfolgen, da die data[]-variablen nur hier gefunden werden.
   // man koennt dies als funktion in die library rausgeben.
   
   {

    for ($x=0; $x<$y; $x++)
    {
     $kkeyfuell = mysql_field_name($result2, $x);
     $feldwx = $feldw[$db][$t][$kkeyfuell]; if ( $feldwx =="" ){ $feldwx=$feldwy; }
     $feldzx = $feldz[$db][$t][$kkeyfuell]; if ( $feldzx =="" ){ $feldzx=$feldzy; }

//     $debug_status = $debug_status . " :: feldwz -- : $db : $t : $x :: $feldwz -- <br>";


     // singleeintrag
     //if ( $single == 1)
     {
      $t_type  = mysql_field_type($result2, $x);
      $t_len   = mysql_field_len($result2, $x);
      $t_flags = mysql_field_flags($result2, $x);

      $data2x = htmlspecialchars( $data2[$x] , ENT_QUOTES );


      // primary_keys sammeln begin
      if ( mysql_fetch_field($result2, $x)->primary_key == "1")
      {
       $b_primaryupdate = $b_primaryupdate . "<input type='hidden' name='pkey[" . $kkeyfuell . "]' value='$data2x'>\n";
      }
      // primary_keys sammeln end


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
       $b_update = $b_update . "<td id='bu_".$x."_".$satz."'> <font face=arial size=1>
       <input id='" . $kkeyfuell . "_" . $satz . "' type='Text' name='$kkeyfuell' value='$data2x' size='$feldzx' style='background:lemonchiffon; font-size:8pt'></td>";
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
     <input type='hidden' name='akt' value='" . $akt . "'>
    " . $b_update . "
    " . $b_primaryupdate . "
    <input type='hidden' name='show' value='$data2[0]'>
    <input type='hidden' name='t' value='$t'>
    <input type='hidden' name='db' value='$db'>
    </form> ";

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
        <input type='hidden' name='akt' value='". $akt . "'>
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
    <input type='hidden' name='t' value='$t'>
    <input type='hidden' name='db' value='$db'>
    </form></table>";
    }
   }
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
    <td colspan='3'><font face=arial size=1>sichtbarkeit:</td>
        " . $b_sicht ."
    <input type='hidden' name='t' value='$t'>
    <input type='hidden' name='db' value='$db'>
    </tr></form> </table>";

    if ( $single == 1)
    {
     echo("$b_update_single");
    }
    
   echo("</table>");
    

// vor zurueck
echo("<div id='vorzurueck'><div class='Normal-P'><table><tr>");

if ( $akt != 0 )
{
 echo("<td><form action='' method='post'>
 <input type='submit' name='zurueck' value='zurueck'>
 <input type='hidden' name='akt' value='$akt'>
 <input type='hidden' name='was2' value='p'> 
 <input type='hidden' name='t' value='$t'>
 <input type='hidden' name='db' value='$db'></form></td>");
 // echo("<td><<span class='Normal-C' align='left'><a href='?akt=$akt&was2=p&t=$t' class='links'> Zurueck</A></span></td><td width=10%> &nbsp; </td>");

}
 if ( $akt2 != $meng )
{
 echo("<td><form action='' method='post'>
 <input type='submit' name='weiter' value='weiter'>
 <input type='hidden' name='akt' value='$akt'>
 <input type='hidden' name='was2' value='n'>
 <input type='hidden' name='t' value='$t'>
 <input type='hidden' name='db' value='$db'></form></td>");
 // echo("<td><span class='Normal-C' align='left'><a href='?akt=$akt&was2=n&t=$t' class='links'> Weiter</A></span></td>");

}
echo("</tr></table></div></div>");

mysql_close($conn2);

}

?>
