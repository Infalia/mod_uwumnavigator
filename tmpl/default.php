<?php
/**
 * @version     1.0.0
 * @package     mod_uwumnavigator
 * @copyright   Copyright (C) 2016. All rights reserved. Infalia PC
 * @license     MIT; see LICENSE
 * @author      Ioannis Tsampoulatidis <itsam@infalia.com> - https://github.com/infalia
 */

defined('_JEXEC') or die;
echo $navbar;
?>


<p><!--<button id="cors">Test CORS with JS</button>-->
Token: <?php echo (is_null($token) ? 'it is null' : $token); ?>
</p>
