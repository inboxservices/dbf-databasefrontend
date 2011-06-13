// ###################################################################
// ##
// ## dbf v 1.1
// ## mysql DatenBank-Frontend mit generischer struktur
// ## (c) 2011/02 klaus oblasser
// ## mail: dbf@ls.to
// ##
// ###################################################################

var xmlHttp = createXmlHttpRequestObject();

function createXmlHttpRequestObject()
{
	var xmlHttp;

	// IE
	if(window.ActiveXObject)
	{
		try
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {
			xmlHttp = false;
		}

	} else {
		// other
		try
		{
			xmlHttp = new XMLHttpRequest();
		} catch (e) {
			xmlHttp = false;
		}
	}

	if(!xmlHttp)
		alert("Error creating the XMLHttpRequest object.");
	else
		return xmlHttp;
}

function handleServerResponse()
{
	if(xmlHttp.readyState == 4)
	{
		if(xmlHttp.status == 200)
		{
			xmlResponse = xmlHttp.responseText;
		} else {
			alert("There was a problem accessing the server: " + xmlHttp.statusText);
		}
	}
}

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
  var posten = 0;
  var zahl = document.getElementById('menginfo').value;
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
    
    if ( posten == 0 )
    {
     xmlHttp.onreadystatechange = handleServerResponse;
     xmlHttp.open("POST", "dbf.conf.html.php", true); 

     xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     xmlHttp.setRequestHeader("Content-length", param.length);
     xmlHttp.setRequestHeader("Connection", "close");

     // make the server request
     xmlHttp.send(param);
     posten = 1;
    }
    
   }
  }
 }
}

function sichtbar( id, x , feldid )
{
 var posten = 0;
 var db = document.getElementById('db').value;
 var t = document.getElementById('t').value;
 var was = 2;
 if ( document.getElementById( id ).checked == true )
 {
  // 1 = unsichtbar
  was = 1;
 }

 // anfang // bestehenden tablellenteil temporaer verstecken
 var zahl = document.getElementById('menginfo').value;

 for ( var i = 0; i <= zahl; i++ )
 {
  elemid = feldid + "_" + i;
  document.getElementById( elemid ).style.visibility = "hidden";
  document.getElementById( elemid ).style.display = "none";
 }
  document.getElementById( feldid ).style.visibility = "hidden";
  document.getElementById( feldid ).style.display = "none";
  document.getElementById( id ).style.visibility = "hidden";
  document.getElementById( id ).style.display = "none";

  document.getElementById( 'bi_' + x ).style.display = "none";
  document.getElementById( 'bs_' + x ).style.display = "none";

 for ( var ii = 1; ii <= zahl; ii++ )
 {
 // document.write( 'bu_' + x + '_' + ii );
  document.getElementById( 'bu_' + x + '_' + ii ).style.display = "none";
 }


//  document.getElementById( elemid ).style.width = 0;
// document.getElementById( feldid ).style.width = 0;

 // ende // bestehenden tablellenteil temporaer verstecken

 param='db='+db+'&t='+t+'&size='+was+'&feld='+x;
    
 if ( posten == 0 )
 {
  xmlHttp.onreadystatechange = handleServerResponse;
  xmlHttp.open("POST", "dbf.conf.html.php", true); 

  xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlHttp.setRequestHeader("Content-length", param.length);
  xmlHttp.setRequestHeader("Connection", "close");

  // make the server request
  xmlHttp.send(param);
  posten = 1;
 }
}

function sichtbarall()
{
 if ( document.getElementById('sichtbaralles').checked == true )
 {
  var posten = 0;
  var db = document.getElementById('db').value;
  var t = document.getElementById('t').value;
  var was = 2;
  var feld = document.getElementById('feldanzahlzeiger').value;

  param='db='+db+'&t='+t+'&size='+was+'&feld='+feld+'&reset=1';

  if ( posten == 0 )
  {
   xmlHttp.onreadystatechange = handleServerResponse;
   xmlHttp.open("POST", "dbf.conf.html.php", true); 

   xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   xmlHttp.setRequestHeader("Content-length", param.length);
   xmlHttp.setRequestHeader("Connection", "close");

   // make the server request
   xmlHttp.send(param);
   posten = 1;
  }
 }
 window.location.reload();
}

function schleifenart()
{
 var posten = 0;
 var db = document.getElementById('db').value;
 var t = document.getElementById('t').value;
// var was = 'f';
// if ( document.getElementById('schleifenartforwhile').checked == true )
// {
  // w = while-schleife
  // f = for-schleife
//  was = 'w';
// }
 was = document.getElementById('schleifenartforwhile').value;

 param='db='+db+'&t='+t+'&size='+was+'&feld=schleifenart_forwhile';
    
 if ( posten == 0 )
 {
  xmlHttp.onreadystatechange = handleServerResponse;
  xmlHttp.open("POST", "dbf.conf.html.php", true); 

  xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlHttp.setRequestHeader("Content-length", param.length);
  xmlHttp.setRequestHeader("Connection", "close");

  // make the server request
  xmlHttp.send(param);
  posten = 1;
 }
}


document.onmousedown = handleMouseDown;
document.onmouseup = handleMouseUp;
