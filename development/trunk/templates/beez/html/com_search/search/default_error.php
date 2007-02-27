<?php 
defined('_JEXEC') or die('Restricted access');
/**
 * @version $Id$
 * @author Design & Accessible Team ( Angie Radtke / Robert Deutz ) 
 * @package Joomla
 * @subpackage Accessible-Template-Beez
 * @copyright Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

/*
 *
 * Get the template parameters
 *
 */
$filename = JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'params.ini';
if ($content = @ file_get_contents($filename)) {
        $templateParams = new JParameter($content);
} else {
        $templateParams = null;
}
/*
 * hope to get a better solution very soon
 */

$hlevel = $templateParams->get('headerLevelComponent', '2');
$ptlevel = $templateParams->get('pageTitleHeaderLevel', '1');
 
echo '<h'.$hlevel .' class="error'. $this->params->get( 'pageclass_sfx' ).'">'.JText::_('Error').'</h'.$hlevel .'>';
echo '<div class="error'. $this->params->get( 'pageclass_sfx' ).'"><p>';
echo $this->error;
echo '</p></div>';
