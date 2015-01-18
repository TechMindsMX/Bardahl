<?php

function insertCaptcha($inputname)
{
    // uprava definice cesty ke captcha/php - uziva se $mosConfig_live_site 
    // abych mohl funkci volat i z jinych komponent
    // na konci definice je "/ >" misto jen ">" - nezustava pak neuzavreny tag input 

    global $mosConfig_live_site ;
    $refid = md5(mktime() * rand());
    $insertstr = "<img src=\"". $mosConfig_live_site . "/components/com_comment/joscomment/captcha.php?refid=" . $refid . "\" alt=\"Security Image\"></img>\n
   	<input type=\"hidden\" name=\"" . $inputname . "\" value=\"" . $refid . "\"/ >";
    return $insertstr;
}

function checkCaptcha($referenceid, $enteredvalue)
{
    global $database;
    $referenceid = mysql_escape_string($referenceid);
    $enteredvalue = mysql_escape_string($enteredvalue);
    $tempQuery = $database->SetQuery("SELECT ID FROM #__comment_captcha WHERE
   referenceid='" . $referenceid . "' AND hiddentext='" . $enteredvalue . "'");
    if ($database->loadResult() != 0) {
        return true;
    } else {
        return false;
    }
}

function captchaResult()
{
    $security_try = decodeData("security_try");
    $checkSecurity = false;
    if ($security_try) {
        $security_refid = decodeData("security_refid");
        $checkSecurity = checkCaptcha($security_refid, $security_try);
    }
    return $checkSecurity;
}

?>