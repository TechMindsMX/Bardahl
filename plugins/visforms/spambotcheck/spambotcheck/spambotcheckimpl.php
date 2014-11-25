<?php

/**
 * @copyright	Copyright (C) 2010 vi-solutions. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * author		MysteryFCM, modified by Robert Kuster and Aicha Vack
 */
 
// **************************************************************
// Author: MysteryFCM, modified by Robert Kuster and Aicha Vack.  
// The implementation is heavily based on MysteryFCMs "check_spammers.zip"
// from: http://temerc.com/forums/viewtopic.php?f=71&t=6103&start=0
// I simplified the code, cleaned it up, ported parts of it to 
// Joomla!, and fixed quite some bugs in it. 
// *************************************************************

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'visforms'.DIRECTORY_SEPARATOR.'spambotcheck'.DIRECTORY_SEPARATOR.'spambotcheck'.DIRECTORY_SEPARATOR.'spambotcheckhelpers.php');

class plgVisformsSpambotCheckImpl {
    /**
     * Prepares the parameters and then calls checkSpambotsImpl(...).
     * The later checks an E-Mail and/or IP against online spambot sites.
     *
     * @access	public
     * @param   JParameter   plugin paramters
     * @param   string   email to check
     * @param   string   IP to check

     * @return	string	SPAMBOT_FALSE (not a spambot), SPAMBOT_TRUE (spambot) + description text
     */
    public static function checkSpambots(&$plgParams, $sEmail, $sIP)
    {
        if(!plgVisformsSpambotCheckHelpers::isCUrlAvailable() && !function_exists('file_get_contents'))
        {	// It appears neither the file_get_contents function or cURL are available. Sorry, but this wont work...";
            return "SPAMBOT_FALSE."; 
        }

        if (!$plgParams->get('spbot_check_email', 1)) { $sEmail    =''; }
        if (!$plgParams->get('spbot_check_ip', 1))    { $sIP       =''; }


        // Has an e-mail been passed? 
        if($sEmail !=''){
            $sEmail = str_replace(" ","", $sEmail);		// no spaces
        }

        // Has an IP been passed? If so, check it's valid
        if($sIP !=''){
            if(plgVisformsSpambotCheckHelpers::IsvalidIP($sIP)==false){
                $sIP = ''; // Can't use if it isn't valid
            }
        }	


        if ($sEmail=='' && $sIP=='')
        {	// nothing to check
            return "SPAMBOT_FALSE";	
        }

        // check our whitelists
        $ip_whitelist    = $plgParams->get('spbot_whitelist_ip', '');

        $email_whitelist = $plgParams->get('spbot_whitelist_email', '');
		if ($email_whitelist != "")
		{
        //delete blanks
        $email_whitelist = str_replace(" ","", $email_whitelist);
        //delete ',' at stringend
        while ($email_whitelist[strlen($email_whitelist)-1] == ",")
        //if ($lastChr == ",") 
        {
            $email_whitelist = substr($email_whitelist, 0, strlen($email_whitelist)-1);
        }
		}
        $allow_generic_email_check = $plgParams->get('allow_generic_email_check', false);
        if ($allow_generic_email_check)
        {

            if (!($email_whitelist === ""))
            {
                //split all whitelist emails into an array
                $email_whitelist = explode(',', $email_whitelist);
                $l = count($email_whitelist);

                for ($i = 0; $i < $l; $i++)
                {
                    //check that whitelistentry is a valid email domain-port (@mail.com)
                    $regex = '/\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
                    if (preg_match($regex, $email_whitelist[$i]))
                    {					
                        if(strpos($sEmail, $email_whitelist[$i]) !== FALSE)
                        //e-mail is in whitelist
                        {
                            return "SPAMBOT_FALSE";	
                        }
                    }
                }
            }
        }
        else
        {
			if (!$sEmail == "") {                
            if ((strpos($email_whitelist, $sEmail) !== false)) 
            {	//  email in our whitelist
                return "SPAMBOT_FALSE";	
            }
        }
		}
		if (!$sIP === "") {
        if (strpos($ip_whitelist, $sIP) !==FALSE) 
        {	// IP in our whitelist
            return "SPAMBOT_FALSE";	
        }
		}


        // should always be the case for php>5
        //
        // As of PHP 5.0, the SimpleXML functions are part of the PHP core. 
        // There is no installation needed to use these functions.
        // (http://www.w3schools.com/PHP/php_xml_simplexml.asp)
        $bXMLAvailable = (phpversion() >"5" && class_exists('SimpleXMLElement') == true);


        // ********************************************
        // Okay, lets process the details shall we?
        // ********************************************
        return self::checkSpambotsImpl($sEmail, $sIP, $plgParams, $bXMLAvailable);
    }



    /**
     * This method checks an E-Mail and/or IP against online spambot sites.
     *
     * @access	public
     * @param   JParameter   plugin paramters
     * @param   string   email to check
     * @param   string   IP to check

     * @return	string	SPAMBOT_FALSE (not a spambot), SPAMBOT_TRUE (spambot) + description text
     */
    public static function checkSpambotsImpl($email, $ip, &$plgParams, &$bXMLAvailable)
    {	
        // *********************************************************************************
        // BEGIN CHECK STOPFORUMSPAM
        // *********************************************************************************
        //
        $typetext = '';
        $bCheckStopforumSpam = $plgParams->get('spbot_stopforumspam', 0);
        $bStopforumspam_MaxAllowedFrequency = $plgParams->get('spbot_stopforumspam_max_allowed_frequency', 0);

        if($bCheckStopforumSpam ==1 && $bXMLAvailable == true)
        {	
            // build URL
            $URL = 'http://www.stopforumspam.com/api?';
            if (!$email=='') { $URL .= 'email='.$email."&";  }
            if (!$ip=='')   { $URL .= 'ip='.$ip."&"; 	   }
            $URL = substr($URL, 0, -1);		// remove last "&"
            // call URL & check result 
            $fspamcheck = plgVisformsSpambotCheckHelpers::getURL($URL); 
            if(strpos($fspamcheck, 'rate limit exceeded') !== FALSE)
            {
                // Added due to SFS introducing a query limit
                // http://www.stopforumspam.com/forum/t573-Rate-Limiting

                // this isn't really a spammer - we add the entry neverthless to track the limit-issue of StopForumSpam
                plgVisformsSpambotCheckHelpers::LogSpammerToDB($email, $ip, 'StopForumSpam', $URL, $fspamcheck, 'rate limit exceeded', $plgParams);
            }
            else
            {			
                //Respons starts with <, so it is an XMLfile
                if(strpos($fspamcheck, '<') === 0)
                {
                    $typetext = strpos($fspamcheck, '<');
                    // Read the result into a SimpleXML and investigate it
                    $sfsxml = new SimpleXMLElement($fspamcheck);
                    $parsedResponse = '';

                    // At least one issues (email, ip) should be reported more than MinReportFrequency
                    $frequency_array = array();
                    foreach($sfsxml->frequency as $frequency){ 
                        $frequency_array[] = (int)$frequency;
                    }

                    // $bStopforumspam_MaxAllowedFrequency reached? -> we have a spambot
                    if (max($frequency_array) >= $bStopforumspam_MaxAllowedFrequency)
                    {
                        $cnt = 0;
                        $bMail = false;
                        $bIP = false;
                        foreach($sfsxml->type as $type)
                        {
                            switch((string)$type) {
                                case "email":
                                    if ($sfsxml->appears[$cnt] == "yes") {
                                        $bMail = TRUE;
                                        $parsedResponse .= 'EMail: frequency=' .$sfsxml->frequency[$cnt] .', last_seen=' .$sfsxml->lastseen[$cnt] .'; ';
                                    }
                                    break;
                                case "ip":
                                    if ($sfsxml->appears[$cnt] == "yes") {
                                        $bIP = TRUE;
                                        $parsedResponse .= 'IP: frequency=' .$sfsxml->frequency[$cnt] .', last_seen=' .$sfsxml->lastseen[$cnt] .'; ';
                                    }
                                    break;

                            }
                            $cnt = $cnt + 1;
                        } // END foreach($sfsxml->type as $type)

                        if($bMail || $bIP)
                        {
                            plgVisformsSpambotCheckHelpers::LogSpammerToDB($email, $ip, 'StopForumSpam', $URL, $fspamcheck, $parsedResponse, $plgParams);
                            return 'StopForumSpam (' . $parsedResponse . ') SPAMBOT_TRUE';
                        }
                    } // END if (max($frequency_array[]) >= $bStopforumspam_MaxAllowedFrequency)

                } // END if(strpos($fspamcheck, '<') === 0)
            } // END if(strpos($fspamcheck, 'rate limit exceeded') !== FALSE )		
        } // End If

        // *********************************************************************************
        // BEGIN CHECK SPAM BUSTED
        // *********************************************************************************
        //
        $bCheckSpamBusted = $plgParams->get('spbot_spambusted', 0);
        if($bCheckSpamBusted ==1 && $bXMLAvailable == true)
        {	
            // build URL
            $URL = 'http://www.spambusted.com/api.php?';
            if (!$email=='') { $URL .= 'email='.$email."&";  }
            if (!$ip=='')   { $URL .= 'ip='.$ip."&"; 	   }
            $URL = substr($URL, 0, -1);		// remove last "&"

            // call URL & check result 
            $fspamcheck = plgVisformsSpambotCheckHelpers::getURL($URL);

            if(strpos($fspamcheck, 'Yes') !== FALSE) {
                //We have to deal with a spammer
                plgVisformsSpambotCheckHelpers::LogSpammerToDB($email, $ip, 'SpamBuster', $URL, $fspamcheck, $fspamcheck, $plgParams);
                return 'SpamBuster (' . $fspamcheck . ') SPAMBOT_TRUE';
            }
        }


        // *********************************************************************************
        // BEGIN CHECK DNSBL (domain name service black-list)
        // *********************************************************************************
        //
        if ($ip !='')	// No point checking without an IP here
        {
            // All DNSBL take a reverse IP
            $reverseIP = implode('.',array_reverse(explode('.', $ip)));


            // *********************************************************************************
            // BEGIN CHECK projecthoneypot.org
            // *********************************************************************************	
            //
            $sPHoneyPotApiKey = $plgParams->get('spbot_projecthoneypot_api_key', '');
            $sPHoneyPot_MaxAllowedThreatScore = $plgParams->get('spbot_projecthoneypot_max_allowed_threat_rating', 0);
            if(strlen($sPHoneyPotApiKey) == 12) // Project Honey Pot API Key
            {									// Note: This key is required if you wish to query Projecthoneypot.org
                $lookup = $sPHoneyPotApiKey .'.' .$reverseIP .'.dnsbl.httpbl.org.';
                $lookupResult = gethostbyname($lookup);
                if ($lookup != $lookupResult)
                {	
                    $sTempArr = explode('.',$lookupResult);
                    $sDays = $sTempArr[1];
                    $sThreatScore = $sTempArr[2];
                    $sVisitorType = $sTempArr[3]; // Let's see what PHP says about this IP

                    if ($sThreatScore >= $sPHoneyPot_MaxAllowedThreatScore)
                    {
                        $sphpspambot = true;
                        switch ($sVisitorType) {
                            case "0":
                                $sVisitorType = "Search Engine";
                                $sphpspambot = false;
                                break;
                            case "1":
                                $sVisitorType = "Suspicious";
                                if ($sThreatScore < 25) { $sphpspambot = false; }
                                break;
                            case "2":
                                $sVisitorType = "Harvester";
                                $sphpspambot = true;
                                break;
                            case "3":
                                $sVisitorType = "Suspicious &amp; Harvester";
                                $sphpspambot = true;
                                break;
                            case "4":
                                $sVisitorType = "Comment Spammer";
                                $sphpspambot = true;
                                break;
                            case "5":
                                $sVisitorType = "Suspicious &amp; Comment Spammer";
                                $sphpspambot = true;
                                break;
                            case "6":
                                $sVisitorType = "Harvester &amp; Comment Spammer";
                                $sphpspambot = true;
                                break;
                            case "7":
                                $sVisitorType = "Suspicious &amp; Harvester &amp; Comment Spammer";
                                $sphpspambot = true;
                                break;
                        }
                        // Do an echo if $sphpspambot = true
                        if($sphpspambot == true)
                        {
                            $parsedResponse = 'VisitorType=' .$sVisitorType . ', ThreatScore=' . $sThreatScore .', DaysSinceLastActivity=' .$sDays .'';
                            plgVisformsSpambotCheckHelpers::LogSpammerToDB($email, $ip, 'ProjectHoneyPot', $lookup, $lookupResult, $parsedResponse, $plgParams);
                            return  'ProjectHoneyPot (RawResponse=' .$lookupResult .', ' .$parsedResponse .')' .' SPAMBOT_TRUE';
                        }					
                    } // END if ($sThreatScore >= $sPHoneyPot_MaxAllowedThreatScore)

                } // End if ($lookup != $lookupResult)
            } // End If


            // *********************************************************************************
            // BEGIN CHECK Sorbs.net
            // *********************************************************************************
            //
            $bCheckSorbs = $plgParams->get('spbot_sorbs', 0);	
            if($bCheckSorbs == 1)
            {
                $lookup = $reverseIP.'.l1.spews.dnsbl.sorbs.net.';
                $lookupResult = gethostbyname($lookup);
                if ($lookup != $lookupResult)
                {
                    plgVisformsSpambotCheckHelpers::LogSpammerToDB($email, $ip, 'Sorbs', $lookup, $lookupResult, '', $plgParams);
                    return 'Sorbs (RawResponse=' .$lookupResult .') SPAMBOT_TRUE';
                } // End if ($lookup != $lookupResult)

                $lookup = $reverseIP.'.problems.dnsbl.sorbs.net.';
                $lookupResult = gethostbyname($lookup);
                if ($lookup != $lookupResult)
                {
                    plgVisformsSpambotCheckHelpers::LogSpammerToDB($email, $ip, 'Sorbs', $lookup, $lookupResult, '', $plgParams);
                    return 'Sorbs (RawResponse=' .$lookupResult .') SPAMBOT_TRUE';
                } // End if ($lookup != $lookupResult)
            }


            // *********************************************************************************
            // BEGIN CHECK SpamCop.net
            // *********************************************************************************
            //
            $bCheckSpamCop = $plgParams->get('spbot_spamcop', 0);
            if($bCheckSpamCop == 1)
            {
                $lookup = $reverseIP.'.bl.spamcop.net.';
                $lookupResult = gethostbyname($lookup);
                if ($lookupResult == '127.0.0.2')
                {
                    LogSpammerToDB($email, $ip, 'SpamCop', $lookup, $lookupResult, '', $plgParams);
                    return 'SpamCop (RawResponse=' .$lookupResult .') SPAMBOT_TRUE';
                } // End if ($lookup != $lookupResult)
            }
        } // End if ($ip !='')


        // *********************************************************************************
        // If we got here then we obviously are not a spambot :-)
        // *********************************************************************************	
        return 'SPAMBOT_FALSE';

    } // End function checkSpambots
}

?>