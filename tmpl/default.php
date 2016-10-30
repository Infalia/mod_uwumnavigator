<?php
/**
 * @version     1.0.0
 * @package     mod_uwumnavigator
 * @copyright   Copyright (C) 2016. All rights reserved. Infalia PC
 * @license     MIT; see LICENSE
 * @author      Ioannis Tsampoulatidis <itsam@infalia.com> - https://github.com/infalia
 */

defined('_JEXEC') or die;
$app =JFactory::getApplication();
$token = $app->getUserState('uwum_access_token');
?>

<p>Hello I am UWUM CORS tester: <button id="cors">Test UWUM through CORS</button></p>
<p>Token is: <?php echo $token; ?>
