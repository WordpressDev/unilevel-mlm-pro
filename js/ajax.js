function update_payment_status(urlpath, id, status) 
{ 
	var xmlhttp;
	document.getElementById("resultmsg_"+id).innerHTML= '<img src="'+ urlpath +'/images/ajax-loader.gif" class="loadImage" />';
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
	 document.getElementById("resultmsg_"+id).innerHTML=xmlhttp.responseText;
	}
	} 
	xmlhttp.open("GET", urlpath+'/ajaxFunction.php?do=statuschange'+'&userId='+id +'&status='+ status,true);
	xmlhttp.send();
}

function triggered() {
if ((xmlhttp.readyState == 4) (xmlhttp.status == 200)) {

document.getElementById("ajaxlink").innerHTML = xmlhttp.responseText;
}
}

function setePinUser(urlpath, user_id, epin) 
{         
        var xmlhttp;
	document.getElementById("epinmsg_"+user_id).innerHTML= '<img src="'+ urlpath +'/images/ajax-loader.gif" class="loadImage" />';
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
	 document.getElementById("epinmsg_"+user_id).innerHTML=xmlhttp.responseText;
	 window.setTimeout(function(){location.reload()},500)
	}
	} 
	xmlhttp.open("GET", urlpath+'/epinUpdate.php?user_id='+user_id+'&epin='+epin,true);
	xmlhttp.send();
}