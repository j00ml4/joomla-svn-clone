<?php
/**
* @version		$Id: router.php 10752 2008-08-23 01:53:31Z eddieajau $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
//TODO : Modify router.php
function MediagalleriesBuildRoute( &$query )
{
	$segments = array();

	// Page View
	if(isset($query['view']))
	{
		$segments[] = $query['view'];
	    unset( $query['view'] );
		
		// Task
		if(isset($query['layout'])){   
	   		$segments[] = $query['layout'];
			unset($query['layout']);
		}else{
			$segments[] = 'default';
		}			
		
	} // Controler
	elseif(isset($query['controller'])){   		
		$segments[] = $query['controller'];
		unset($query['controller']);
		
		// Task
		if(isset($query['task'])){   
	   		$segments[] = $query['task'];
			unset($query['task']);
		}else{
			$segments[] = 'default';
		}
	}
	
	// Controller
	
	// Item ID
	if(isset($query['id']))
	{	
		$id = $query['id'];			
	    $segments[] = $id;
		unset( $query['id'] );
		// Alias
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT alias FROM #__mediagalleries WHERE id='.(int)$id);// Alias from your table
		$alias = $db->loadResult();
		if(!empty($alias)) $segments[] = $alias;
	}

	// Otheer
	$access_denied = array('option', 'limit', 'limitstart', 'start', 'Itemid');
	foreach($query as $k => $val){
		if(!in_array($k, $access_denied)){
			$segments[] = $k.','.$val;
	   		unset( $query[$k] );
		}
	}
	return $segments;
}

function MediagalleriesParseRoute( $segments )
{
	$vars = array();
	
	// Every other thinks
	foreach($segments as $k => $value ){
		$option = explode(',', $value);		
		if(count($option)==2){ 
			$vars[ $option[0] ] = $option[1]; 
			unset($segments[$k]);
		} 			
	}	
	
	// Controller + View
	if(isset($segments[0]) ){
		$vars['view'] = $segments[0];
		$vars['controller'] = $segments[0];
		unset($segments[0]);
	}
	
	// Layout + Task
	if(isset($segments[1])){
		$vars['layout'] = $segments[1];
		$vars['task'] = $segments[1]; 
		unset($segments[1]);	
	}
	
	// ID + ALIAS
	if(isset($segments[2]) && is_numeric($segments[2])){
   		$vars['id'] = $segments[2];
	   	unset($segments[2]);
		unset($segments[3]);
	 }

	return $vars;
}
