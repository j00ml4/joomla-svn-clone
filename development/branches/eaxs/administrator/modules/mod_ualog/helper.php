<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	mod_ualog
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// no direct access
defined('_JEXEC') or die;

abstract class modUalogHelper
{
    public static function render($params)
    {
        $db = &JFactory::getDBO();
        
        $query = "SELECT l.*, name FROM #__user_log AS l"
               . "\n RIGHT JOIN #__users AS u ON u.id = l.user_id"
               . "\n ORDER BY l.cdate DESC LIMIT 20";
               $db->setQuery($query);
               $rows = $db->loadObjectList();
               
        if(!is_array($rows)) {
            echo JText::_('NOACTIVITY');
            return false;
        }

        foreach($rows AS $row)
        {
            JFilterOutput::objectHTMLSafe($row);

            if(!$row->msg) continue;
            
            $message = strtoupper($row->msg);
            $message = str_replace('{', '', $message);
            $message = str_replace('}', '', $message);
            $message = str_replace(':','_', $message);
            $message = str_replace(' ','',$message);
            $message = JText::_($message);
            $message = str_replace('{name}', $row->name, $message);
            if($row->link) {
                $message = str_replace('{title}', "<a href=\"".JRoute::_($row->link)."\">".$row->item."</a>", $message);
            }
            else {
                $message = str_replace('{title}', $row->item, $message);
            }

            echo "<li>$message<br /><span>".$row->cdate."</span></li>";
        }
    }
}