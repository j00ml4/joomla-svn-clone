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
     * Method to get list of tasks
     * @param $params JRegistry object with parameters for the output
     * @return List of tasks as an array of objects
     */
    public static function getTasks($params) {
    	$db = JFactory::getDbo();
    	$q = $db->getQuery(true);
    	
    	$q->select('t.title, t.id');
    	$q->from('`#__project_tasks` AS t');
    	
    	// filter by project id
    	$project_id = (int)$params->get('project.id',0);
    	if($project_id)
	    	$q->where('t.`project_id` = '.$project_id);
	    	
	    // filter by type
	    $type = (int)$params->get('task.type',3);
	    $q->where('t.`type` = '.$type);
	    
	    // filter by state
	    $state = $params->get('state',false);
	    if($state !== false)
	    {
	    	if(is_int($state))
				$q->where('t.`state` = '.$state);
			else
				$q->where('t.`state` '.$state);
	    }
	    
	    // order and limit
	    $ord = $params->get('order.list','t.`ordering`');
	    $start = (int)$params->get('limit.start',0);
	    $limit = (int)$params->get('limit.limit',5);
	    if($ord)
	    	$q->order($ord.' '.$params->get('order.dir','ASC').' LIMIT '.$start.','.$limit);
	    else
	    	$q->order('LIMIT '.$start.','.$limit);
	    
	    $db->setQuery($q);
	    return $db->loadObjectList();
    }
    
     /**
     * Method to get list of documents
     * @param $params JRegistry object with parameters for the output
     * @return List of tasks as an array of objects
     */
    public static function getDocuments($params) {
    	$db = JFactory::getDbo();
    	$q = $db->getQuery(true);
    	
    	$q->select('c.title, c.id');
    	$q->from('`#__project_contents` AS ct');
    	$q->join('left','`#__content` AS c ON c.`id`= ct.`content_id`');
    	
    	// filter by project id
    	$project_id = (int)$params->get('project.id',0);
    	if($project_id)
	    	$q->where('ct.`project_id` = '.$project_id);
	    		    
	    // filter by state
	    $state = $params->get('state',false);
	    if($state !== false)
	    {
	    	if(is_int($state))
				$q->where('c.`state` = '.$state);
			else
				$q->where('c.`state` '.$state);
	    }
	    
	    // order and limit
	    $ord = $params->get('order.list','c.`modified`, c.`created`');
	    $start = (int)$params->get('limit.start',0);
	    $limit = (int)$params->get('limit.limit',5);
	    if($ord)
	    	$q->order($ord.' '.$params->get('order.dir','ASC').' LIMIT '.$start.','.$limit);
	    else
	    	$q->order('LIMIT '.$start.','.$limit);
	    
	    $db->setQuery($q);
	    
	    return $db->loadObjectList();
    }

     /**
     * Method to get list of members
     * @param $params JRegistry object with parameters for the output
     * @return List of tasks as an array of objects
     */
    public static function getMembers($params) {
    	$db = JFactory::getDbo();
    	$q = $db->getQuery(true);
    	
    	$q->select('u.`name`, u.`id`, ug.`title` AS `role`');
    	$q->from('`#__project_members` AS m');
    	$q->join('left','`#__users` AS u ON u.`id`= m.`user_id`');
    	$q->join('left','`#__usergroups` AS ug ON ug.`id`= m.`group_id`');
    	
    	// filter by project id
    	$project_id = (int)$params->get('project.id',0);
    	if($project_id)
	    	$q->where('m.`project_id` = '.$project_id);
	    
    	// filter by usergroup
    	$ug_id = (int)$params->get('usergroup.id',0);
    	if($usergroup)
	    	$q->where('m.`group_id` = '.$ug_id);

    	// order and limit
	    $ord = $params->get('order.list','u.`name`');
	    $start = (int)$params->get('limit.start',0);
	    $limit = (int)$params->get('limit.limit',5);
	    if($ord)
	    	$q->order($ord.' '.$params->get('order.dir','ASC').' LIMIT '.$start.','.$limit);
	    else
	    	$q->order('LIMIT '.$start.','.$limit);
	    
	    $db->setQuery($q);
	    return $db->loadObjectList();
    }
    
    /**
     * Method to get pre-defined links
     * @param $key
     * @param $append
     * 
     * return Routed link
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
				
            	'task' => 'index.php?option=com_projects&view=task&type=2&id=',
            	'ticket' => 'index.php?option=com_projects&view=task&type=3&id=',
            	'task.view.task' => 'index.php?option=com_projects&view=task&layout=view&type=2&id=',
            	'task.view.ticket' => 'index.php?option=com_projects&view=task&layout=view&type=3&id=',
            	'task.edit' => 'index.php?option=com_projects&view=task&layout=edit&id=',

            	'tasks' => 'index.php?option=com_projects&view=tasks&id=',
            	'tasks.task' => 'index.php?option=com_projects&view=tasks&type=2&id=',
            	'tasks.ticket' => 'index.php?option=com_projects&view=tasks&type=3&id=',

            	'documents' => 'index.php?option=com_projects&view=documents&id=',
             	'document' => 'index.php?option=com_projects&view=document&id=',
              );
        }
        return JRoute::_($links[strtolower($key)].$append);
    }
}

?>
