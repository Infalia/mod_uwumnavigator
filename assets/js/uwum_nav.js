
jQuery(document).ready(function($) {

	$( "#cors" ).click(function() {
		checkWeGovNowSession(function(result) {
		  if (result === undefined) { // note === to distinguish undefined from null 
		    window.alert("Error during request")
		  } else if (result) {
		    window.alert("Web browser claims that a user with the following ID is logged in: " + result); 
		  } else {
		    window.alert("Web browser claims that no user is logged in.");
		  }
		});
	});

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

