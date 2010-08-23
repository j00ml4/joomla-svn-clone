<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class ProjectsHelper {
    /**
     * Method to determine if a user is member of a project
     *
     * @param $project_id ID of a project
     * @param $user_id ID of a user
     *
     * @return False in case the user is not a member of the project or ID of user group
     * @since	1.6
     */
    public function isMember($project_id=0, $user_id=0) {
        if (!((int) $project_id)) {
            return true;
        }

        if (!((int) $user_id)) {
            return false;
        }

        // Check if is member
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(project_id)');
        $query->from('#__project_members AS a');
        $query->where('a.project_id = ' . (int) $project_id . ' AND a.user_id=' . (int) $user_id);
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @param	int		The category ID.
     * @return	JObject
     * @since	1.6
     */
    public static function getActions($portfolio_id=0, $project_id=0, $record=null) {
        $user = JFactory::getUser();
        $assets = new JObject;
        $is_member = self::isMember($project_id, $user->id);
        $assetName = empty($portfolio_id) ?
                'com_projects' :
                'com_projects.category.' . (int) $portfolio_id;

        // is owner
        if ($record instanceof JObject) {
            $assets->set('is.owner',
                    (
                    $user->id == $record->get('created_by') ||
                    $user->id == $record->get('created_user_id')
                    )
            );
        }

        // acctions
        $resources = array(
            'task',
            'ticket',
            'document'
        );
        $actions = array(
            '.create',
            '.edit',
            '.delete'
        );

        $assets->set('is.member', $is_member);
        foreach ($resources as $resource) {
            // Actions
            foreach ($actions as $action) {
                $assets->set($resource . $action,
                        ($is_member && $user->authorise($action, $assetName))
                );
            }

            // View
            $assets->set($resource . '.view',
                    (
                    $is_member &&
                    $assets->get($resource . '.create') ||
                    $assets->get($resource . '.edit') ||
                    $assets->get($resource . '.delete')
                    )
            );
        }

        // More Actions
        $actions = array(
            'core.create',
            'core.edit',
            'core.edit.state',
            'core.delete'
        );
        foreach ($actions as $action) {
            $assets->set($action, $user->authorise($action, $assetName));
        }

        return $assets;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $action
     * @param unknown_type $portfolio_id
     * @param unknown_type $project_id
     */
    public static function canDo($action, $portfolio_id=0, $project_id=0, $record=null) {
        static $assets;
        if (empty($assets)) {
            $assets = self::getActions($portfolio_id, $project_id, $record);
        }

        return $assets->get($action, false);
    }

    /** i don t know if we need this function..
     * Resets breadcrumb and adds "Projects" link as first
     *
     * @return Reference to breadcrumb object
     * @since	1.6
     */
    public function &resetPathway() {
        $app = &JFactory::getApplication();
        $bc = &$app->getPathway();
        return $bc;
    }
    
    /**
     * Method to get singular or plural version of text based on number of items
     *
     * @param $text Base of translation text
     * @param $num  Number of items
     * 
     * @return Translation string for JText::_() method
     */
    public static function textPlural($text, $num) {
    	return $num > 1 ? $text.'_PLURAL' : $text;
    }
    

    /**
     * Get links
     *
     * Method to get pre-defined links
     * @param $key
     * @param $append
     */
    public static function getLink($key, $append='') {
        static $links;
        if (empty($links)) {
            $links = array(
                'form' => JFilterOutput::ampReplace(JFactory::getURI()->toString()),

            	'portfolios' => 'index.php?option=com_projects&view=portfolios&id=',
                'projects' => 'index.php?option=com_projects&view=projects&id=',
                'project' => 'index.php?option=com_projects&view=project&id=',

            	'members' => 'index.php?option=com_projects&view=members&type=list&id=',
                'members.assign' => 'index.php?option=com_projects&view=members&type=assign&id=',
                'members.unassign' => 'index.php?option=com_projects&view=members&type=delete&id=',

            	'task.view' => 'index.php?option=com_projects&view=task&layout=view&id=',
                'task.edit' => 'index.php?option=com_projects&view=task&layout=edit&id=',

            	'tasks' => 'index.php?option=com_projects&view=tasks&id=',
            	'tasks.task' => 'index.php?option=com_projects&view=tasks&type=2&id=',
            	'tasks.ticket' => 'index.php?option=com_projects&view=tasks&type=3&id=',

            	'documents' => 'index.php?option=com_projects&view=documents&id=',
             	'document' => 'index.php?option=com_projects&view=document&id=',
                        );
        }
        return JRoute::_($links[$key].$append);
    }
}

?>
