<?php
/**
 * @version     1.0.1
 * @package     mod_uwumnavigator
 * @copyright   Copyright (C) 2016-2017. All rights reserved. Infalia Private Company
 * @license     MIT; see LICENSE
 * @author      Ioannis Tsampoulatidis <itsam@infalia.com> - https://github.com/infalia
 */

defined('_JEXEC') or die;
echo $navbar;
?>
<!--<button id="cors">Test CORS with JS</button>-->
<script>

    function checkWeGovNowSession(callback) {
        var xhr = new XMLHttpRequest();
        //var url = "https://wegovnow.liquidfeedback.com/api/1/session";
        var url = "<?php echo $session_url;?>";
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

    function setUwumState(is_logged, member_id){
        var token = '<?php echo JSession::getFormToken(); ?>';
        jQuery.ajax({
            'async': true,
            'global': false,
            'url': "index.php?option=com_imc&task=uwum.check&format=json&is_logged=" + is_logged + "&member_id=" + member_id + "&" + token + "=1",
            'dataType': "json",
            'success': function (data) {
                var json = data;
                console.log('UWUM controller returns...');
                console.log(json);
                if(json.data.action === 'logout'){
                    window.location.reload();
                }
                if(json.data.action === 'login'){
                    //window.location = 'https://wegovnow.infalia.com/component/slogin/provider/uwum/auth';
                    window.location = "<?php echo $login_url;?>";
                }
            },
            'error': function (error) {
                console.log('MOD_UWUMnavigator error - Cannot login/logout automatically');
                console.log(error);
            }
        });
    }



    jQuery(document).ready(function($) {

        //$( "#cors" ).click(function() {

        checkWeGovNowSession(function(result) {
            if (result === undefined) { // note === to distinguish undefined from null
                console.log("UWUM: Error during request")
                setUwumState(false, 0);
                //createCookie('imc_uwum_login','false',2);
            } else if (result) {
                console.log("UWUM: Web browser claims that a user with the following ID is logged in: " + result);
                setUwumState(true, result);
                //createCookie('imc_uwum_login','true',2);
            } else {
                console.log("UWUM: Web browser claims that no user is logged in.");
                setUwumState(false, 0);
                //createCookie('imc_uwum_login','false',2);
            }
        });
        //});
    });
</script>
