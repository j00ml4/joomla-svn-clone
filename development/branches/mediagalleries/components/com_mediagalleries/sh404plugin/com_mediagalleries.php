<?php
/**
 * sh404SEF support for com_Autopecas.
 * Author : OSSchools and 3DEN Open Software
 * 
 * 
 * {shSourceVersionTag: Version x - 2007-09-20}
 * 
 * This is a sample sh404SEF native plugin file
 *    
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
// ------------------  standard plugin configurantion var - please change ------------------------
$com = substr($option, 4);
$com_table = '#__'.$com;
$defalt_view = 'mediagalleries';

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig;  
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
// There is no language to load in the plugin
//$shLangIso = shLoadPluginLanguage( $option, $shLangIso, '_SEF_SAMPLE_TEXT_STRING');
// ------------------  load language file - adjust as needed ----------------------------------------


// start by inserting the menu element title (just an idea, this is not required at all)
$view = isset($view) ? @$view: null;
$Itemid = isset($Itemid) ? @$Itemid : null;

switch ($view) {
	// no SEF
	case 'someact':
		$dosef = false;
		break;
	
	/*
	 * Generic
	 */	
	default:
		// View + LAYOUT
		if($view){
			$title[] = $view;
			
			if(isset($layout)){
				$title[] = $layout;
	        	shRemoveFromGETVarsList('layout');         						
			}

		}// CONTROLER + Task
		elseif(isset($controller)){
			$title[] = $controller;
	        shRemoveFromGETVarsList('controller');         
			
			if(isset($task)){
				$title[] = $task;
	        	shRemoveFromGETVarsList('task');         						
			}												
		}
		
		// Section
		if(isset($section)){
			$title[] = $section;
        	shRemoveFromGETVarsList('section');         										
		}
				
		// Category
		if(isset($catid)){
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT alias FROM #__categories WHERE id='.(int)$catid);
			$alias = $db->loadResult();
			$title[] = $alias;
        	shRemoveFromGETVarsList('catid');         										
		}		
		
		// ID
		if(isset($id)){
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT title FROM '.$com_table.' WHERE id='.(int)$id);
			$alias = $db->loadResult();
			$title[] = $id;//Id of the item
			!empty($alias) && $title[] = $alias;// Alias of the item Most impotant part
        	shRemoveFromGETVarsList('id');         										
		}			

		// Page
		if(isset($page)){
			$title[] = $page;
        	shRemoveFromGETVarsList('page');         										
		}	
		break;	

}

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('view');      
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (!empty($Itemid)) shRemoveFromGETVarsList('Itemid');
if (!empty($limit)){
	$title[] = 'num.'.$limit;
	shRemoveFromGETVarsList('limit');
} 
if(isset($limitstart)){
	$title[] = 'page.'.$limitstart;	
	shRemoveFromGETVarsList('limitstart'); // limitstart can be zero
} 
 
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}      
// ------------------  standard plugin finalize function - don't change ---------------------------

?>