<?php

/**
 * @version     1.0.0
 * @package     mod_uwumnavigator
 * @copyright   Copyright (C) 2016. All rights reserved. Infalia PC
 * @license     MIT; see LICENSE
 * @author      Ioannis Tsampoulatidis <itsam@infalia.com> - https://github.com/infalia
 */
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

//add js
$doc = JFactory::getDocument();
$doc->addScript(JURI::base() . '/modules/mod_uwumnavigator/assets/js/uwum_nav.js');

//get token from state if any
$app =JFactory::getApplication();
$token = $app->getUserState('uwum_access_token', null);

//echo $_COOKIE['imc_uwum_login'];

if (isset($_COOKIE['imc_uwum_login']) && $_COOKIE['imc_uwum_login'] == 'false' && !JFactory::getUser()->guest)
{
	//ModUwumnavigatorHelper::quickLogout();
	echo "Should locally logout automatically";
}

if (isset($_COOKIE['imc_uwum_login']) && $_COOKIE['imc_uwum_login'] == 'true' && JFactory::getUser()->guest)
{
	//ModUwumnavigatorHelper::quickLogin();
	echo "Should locally login automatically";
}


//$foo = ModUwumnavigatorHelper::checkCORS();
//print_r($foo);

//TODO: get host and arguments from module options
$host = "https://wegovnow.liquidfeedback.com/api/1/navigation?client_id=wegovnow.infalia.com";
$navbar = ModUwumnavigatorHelper::makeNavigation($host, $token);

//get default layout
require JModuleHelper::getLayoutPath('mod_uwumnavigator', 'default');
