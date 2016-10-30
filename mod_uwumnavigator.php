<?php

/**
 * @version     1.0.0
 * @package     mod_uwumnavigator
 * @copyright   Copyright (C) 2016. All rights reserved. Infalia PC
 * @license     MIT; see LICENSE
 * @author      Ioannis Tsampoulatidis <itsam@infalia.com> - https://github.com/infalia
 */
defined('_JEXEC') or die;


$jinput = JFactory::getApplication()->input;
$option = $jinput->get('option', null);
$view = $jinput->get('view', null);

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$doc = JFactory::getDocument();
$doc->addScript(JURI::base() . '/modules/mod_uwumnavigator/assets/js/uwum_nav.js');

require JModuleHelper::getLayoutPath('mod_uwumnavigator', 'default');
