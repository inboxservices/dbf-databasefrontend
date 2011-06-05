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

function process()
{
	// 0 - uninitialized
	// 1 - loading
	// 2 - loaded
	// 3 - interactice
	// 4 - complete

	if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	{
		name = encodeURIComponent(document.getElementById("myName").value);
		xmlHttp.open("GET", "test.php?name="+name, true);
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send(null);
	} else
		setTimeout('process()', 1000);
}

function handleServerResponse()
{
	if(xmlHttp.readyState == 4)
	{
		if(xmlHttp.status == 200)
		{
			xmlResponse = xmlHttp.responseText;
//			xmlDocumentElement = xmlResponse.documentElement;
//			helloMessage = xmlDocumentElement.firstChild.data;
/*			document.getElementById("divMessage").innerHTML = '' + xmlResponse + '';
			setTimeout('process()', 1000);*/
		} else {
			alert("There was a problem accessing the server: " + xmlHttp.statusText);
		}
	}
}
