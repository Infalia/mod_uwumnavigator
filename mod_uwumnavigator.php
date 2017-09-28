<?php

/**
 * @version     1.0.1
 * @package     mod_uwumnavigator
 * @copyright   Copyright (C) 2016-2017. All rights reserved. Infalia Private Company
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
$token = $app->getUserState('uwum_access_token', null); //user state is set on plg_SL_uwum

//TODO: get host and arguments from module options
$prms = array(
	'client_id=' . $params->get('client_id'),
	'login_url=' . $params->get('login_url'),
	'format=html'
);
$prms = implode('&', $prms);
//$host = "https://wegovnow.liquidfeedback.com/api/1/navigation?client_id=wegovnow.infalia.com";
$host = $params->get('navigation_url') . '?' . $prms;

//$navbar = ModUwumnavigatorHelper::makeNavigation($host, $token);
$session_url = $params->get('session_url');
$login_url = $params->get('login_url');
$navbar = ModUwumnavigatorHelper::getHTMLNavigation($host, $token);

//get default layout
require JModuleHelper::getLayoutPath('mod_uwumnavigator', 'default');
