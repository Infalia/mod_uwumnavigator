
jQuery(document).ready(function($) {

	//$( "#cors" ).click(function() {
		checkWeGovNowSession(function(result) {
		  if (result === undefined) { // note === to distinguish undefined from null 
		    //window.alert("Error during request")
              createCookie('imc_uwum_login','false',2);
		  } else if (result) {
		    //window.alert("Web browser claims that a user with the following ID is logged in: " + result);
              createCookie('imc_uwum_login','true',2);
		  } else {
		    //window.alert("Web browser claims that no user is logged in.");
              createCookie('imc_uwum_login','false',2);
		  }
		});
	//});

});


function checkWeGovNowSession(callback) {
  var xhr = new XMLHttpRequest();
  var url = "https://wegovnow.liquidfeedback.com/api/1/session";
  xhr.open("POST", url, true);
  xhr.withCredentials = true; // sends UWUM cookies to UWUM (important)
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        var r = JSON.parse(xhr.responseText);
        callback(r.member_id);
      } else {
        // some error occured, add error handling
        callback(undefined);
      }
    }
  }
  xhr.send();
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}