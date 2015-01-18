<?php

/**
 * @version    $version 4.1 Peter Bui  $
 * @copyright    Copyright (C) 2012 PB Web Development. All rights reserved.
 * @license    GNU/GPL, see LICENSE.php
 * Updated    22nd March 2014
 *
 * Twitter: @astroboysoup
 * Blog: http://pbwebdev.com/blog/
 * Email: peter@pbwebdev.com.au
 *
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * DirectMonster by Lunametrics
 * MIT License - https://github.com/lunametrics/directmonster
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemGoogleAnalytics extends JPlugin
{
    function plgGoogleAnalytics(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->_plugin = JPluginHelper::getPlugin('system', 'GoogleAnalytics');
        $this->_params = new JParameter($this->_plugin->params);
    }

    function onAfterRender()
    {
        // Initialise variables
        $type = $this->params->get('type', '');
        $trackerCode = $this->params->get('code', '');
        $domain = $this->params->get('domain', '');
        $verify = $this->params->get('verify', '');
        $directmonster = $this->params->get('directmonster', '');
        $remarketing = $this->params->get('remarketing', '');
        $enhanced = $this->params->get('enhanced', '');

        $ipTracking = $this->params->get('ipTracking', '');
        $multiSub = $this->params->get('multiSub', '');
        $multiTop = $this->params->get('multiTop', '');
        $sampleRate = $this->params->get('sampleRate', '');
        $setCookieTimeout = $this->params->get('setCookieTimeout', '');
        $siteSpeedSampleRate = $this->params->get('siteSpeedSampleRate', '');
        $visitorCookieTimeout = $this->params->get('visitorCookieTimeout', '');

        $javascript = '';

        $app = JFactory::getApplication();

        // skip if admin page
        if ($app->isAdmin()) {
            return;
        }

        //getting body code and storing as buffer
        $buffer = JResponse::getBody();

        //Google Webmaster verification
        $verifyOutput = '';
        if($verify){
            $verifyOutput = '<meta name="google-site-verification" content="' . $verify . '" />';
        }

        // Demographics and Remarketing display advertising. Only available for Asynchronous version of Joomla.
        if ($remarketing)
        {
            //remarketing enabled code
            $remarketingOutput = "ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';";
        } else {
            // standard Google tracking code
            $remarketingOutput = "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
        }

        if ($type == 'asynchronous') {

            if($directmonster){
                $javascript .= '<script src="/plugins/system/GoogleAnalytics/directmonster1.3.3.js" type="text/javascript"></script>
                ';
            }

            if ($enhanced){
                $enhancedOutput = "var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
                _gaq.push(['_require', 'inpage_linkid', pluginUrl]);";
            }

            //embed Google Analytics code
            $javascript .= "<script type=\"text/javascript\">
            ". $enhancedOutput ."
 var _gaq = _gaq || [];
 _gaq.push(['_setAccount', '" . $trackerCode . "']);
";
            if ($ipTracking) {
                $javascript .= " _gaq.push(['_gat._anonymizeIp']);\n";
            }
            if ($multiSub || $multiTop) {
                $javascript .= " _gaq.push(['_setDomainName', '" . $_SERVER['SERVER_NAME'] . "']);\n";
            }
            if ($multiTop) {
                $javascript .= " _gaq.push(['_setAllowLinker', true]);\n";
            }
            if ($sampleRate) {
                $javascript .= " _gaq.push(['_setSampleRate', '" . $sampleRate . "']);\n";
            }
            if ($setCookieTimeout) {
                $javascript .= " _gaq.push(['_setSessionCookieTimeout', '" . $setCookieTimeout . "']);\n";
            }
            if ($siteSpeedSampleRate) {
                $javascript .= " _gaq.push(['_setSiteSpeedSampleRate', '" . $siteSpeedSampleRate . "']);\n";
            }
            if ($visitorCookieTimeout) {
                $javascript .= " _gaq.push(['_setVisitorCookieTimeout', '" . $visitorCookieTimeout . "']);\n";
            }
            $javascript .= "_gaq.push(['_trackPageview']);

 (function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ". $remarketingOutput . "
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 })();
</script>
<!-- Asynchonous Google Analytics Plugin by PB Web Development -->";
        }

        if ($type == 'universal') {
            //embed Google Analytics code

            if($directmonster){
                $javascript .= '<script src="/plugins/system/GoogleAnalytics/directmonster2.0.2.js" type="text/javascript"></script>
                ';
            }

            $javascript .= "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', '" . $trackerCode . "', '" . $domain . "');
  ga('send', 'pageview');
</script>
<!-- Universal Google Analytics Plugin by PB Web Development -->";
        }
        // adding the Google Analytics code in the header before the ending </head> tag and then replacing the buffer
        $buffer = preg_replace("/<\/head>/", "\n\n" . $verifyOutput . "\n\n" . $javascript . "\n\n</head>", $buffer);

        //output the buffer
        JResponse::setBody($buffer);

        return true;
    }
}
?>