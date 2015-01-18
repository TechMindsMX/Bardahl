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
// **************************************************************

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
class plgVisformsSpambotCheckHelpers {

    // Determine whether curl is available or not

	/**
	 * Method to check if cUrl is available on sytem.
	 *
	 * @return  boolean true/false
	 *
	 * @since   1.6
	 */
    public static function isCUrlAvailable() {
        $extension = 'curl';
        if (extension_loaded($extension)) {
            return true;
        }
        else {
            return false;
        }
    }

	/**
	 * Method to Determine if a URL is online or not
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */

    public static function isURLOnline($sSiteToCheck) {
        // check, if curl is available
        if (self::isCUrlAvailable()) {
            // check if url is online
            $curl = @curl_init($sSiteToCheck);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_FAILONERROR, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            @curl_exec($curl);
            if (curl_errno($curl) != 0) {
                return false;
            }
            else {
                    return true;
                }
            curl_close($curl);
        }
        else {
            //curl is not loaded, this won't work
            return false;
        }
    }


    // Gets a URL's content
    //
	/**
	 * Method to Gets a URL's content
	 * If file_get_contents() is available, use that, otherwise use cURL
	 *
	 * @return  String Url Content or Error Message string on failure
	 *
	 * @since   1.6
	 */
    public static function getURL($sURL)
    {
        if(self::isURLOnline($sURL) == false)
        {
            $sURLTemp = 'Unable to connect to server';
            return $sURLTemp;
        }
        else
        {
            if(function_exists('file_get_contents') && ini_get('allow_url_fopen') == true)
            {
                // Use file_get_contents
                $sURLTemp = @file_get_contents($sURL);
            }
            else
            {
                // Use cURL (if available)
                if (self::isCUrlAvailable()) {
                    $curl = @curl_init();
                    curl_setopt($curl, CURLOPT_URL, $sURL);
                    curl_setopt($curl, CURLOPT_VERBOSE, 1);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    $sURLTemp = @curl_exec($curl);
                    curl_close($curl);
                }
                else {
                    $sURLTemp = 'Unable to connect to server';
                    return $sURLTemp;
                }
            }
            return $sURLTemp;
        }
        //echo 'DEBUG: $sURLTemp: '.$sURLTemp.'<br/>';
    }


	/**
	 * Method to Determines if passed IP is valid
	 * Thanks to Mike @ BotScout (botscout.com) for this function
	 * http://botscout.com/forum/index.php/topic,2.msg128.html#msg
	 *
	 * @param string $ip	Ip Adress
	 * @return  boolean (true if IP is valid)
	 *
	 * @since   1.6
	 */
    public static function IsvalidIP($ip)
    {
        if(preg_match("'\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b'", $ip)){
            return true;
        }else{
            return false;
        }
    }


	/**
	 * Method to Log spammer to database (if enabled)
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
    // Usage example:
    // ---------------
    // LogSpammerToDB('test@test.com', '12.12.12.12', 'ProjectHoneyPot', '127.41.11.5', 'ThreatScore=11, DaysSinceLastActivity=41', $plgParams)
    //
    public static function LogSpammerToDB($sEmail, $sIP, $sEngine, $sRequest, $sRawReturn, $sParsedReturn, &$plgParams )
    {
        if (!$plgParams->get('spbot_log_to_db', 0))	{
            // -> save spambots to DB?
            return false;
        }

        self::CreateSpambotTableIfNotExist();	

        // Change empty vars to "NULL"
        if($sEmail==''){$sEmail='NULL';}
        if($sIP==''){$sIP='NULL';}

        // Trim anything that could screw up SQL
        $sEmail = str_replace(array("0x", ",", "%", "'","\r\n", "\r", "\n"), "", $sEmail);

        $sIP = str_replace(array("0x", ",", "%", "'","\r\n", "\r", "\n"), "", $sIP);

        // add DB record
        $db = JFactory::getDbo();	
        $sDate = gmdate("Y-m-d H:i:s", time());

        $strSQL = "INSERT INTO `#__visforms_spambot_attempts` ( `email`, `ip`, `engine`, `request`, `raw_return`, `parsed_return`, `attempt_date`) "
                        . "VALUES({$db->quote($sEmail)}, {$db->quote($sIP)}, {$db->quote($sEngine)}, "
                        .       " {$db->quote($sRequest)}, {$db->quote($sRawReturn)}, {$db->quote($sParsedReturn)}, {$db->quote($sDate)})";
        $db->SetQuery($strSQL);
        $db->execute();
        return true;
    }

	/**
	 * Method to Create log table for if it doesn't exist.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
    public static function CreateSpambotTableIfNotExist()
    {
        static $alreadyCalled = 0;		
        if ($alreadyCalled == 0)
        {
            // We werent yet called in this seasson.
            // Let's do the check if our table exists now.		
            $alreadyCalled = 1;

			$db = JFactory::getDbo();
			$tnfull = $db->getPrefix(). '_visforms_spambot_attempts';
			$tablesAllowed = $db->getTableList();

			if (!in_array($tnfull, $tablesAllowed))
            {	// Table doens't exist yet. Let's create it.
            $strSQL = "CREATE TABLE IF NOT EXISTS `#__visforms_spambot_attempts`	(`id` int NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`),`email` varchar(255), `ip` varchar(15)," .
                "`engine` varchar(255), `request` varchar(255), `raw_return` varchar(255), `parsed_return` varchar(255), `attempt_date` varchar(255))ENGINE=MyISAM DEFAULT CHARSET=utf8";
                $db->SetQuery($strSQL);
                $db->execute();
            }
        }
    }
}
?>