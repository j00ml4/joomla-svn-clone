<?php
/**
 * @version $Id$
 * @package Joomla
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Joomla! Page Cache Plugin
 *
 * @package Joomla
 * @subpackage System
 */
class plgSystemUalog extends JPlugin
{
    function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function getAutoIncrement($table)
    {
        $db = &JFactory::getDBO();

        $query = "SHOW TABLE STATUS LIKE '".$db->replacePrefix($table)."'";
	       $db->setQuery($query);
	       $result = $db->loadAssocList();

	$i = $result[0]['Auto_increment'];

        return $i;
    }

    function getItemTitle($id, $table, $field = 'title')
    {
        $db = &JFactory::getDBO();
        $id = intval($id);
        $query = "SELECT $field FROM $table WHERE id = $id";
               $db->setQuery($query);
               $title = $db->loadResult();

        return $title;
    }

    function logActivity($option, $task, $id, $uid, $link, $item, $txt)
    {
        $db     = &JFactory::getDBO();
        $option = $db->Quote($option);
        $task   = $db->Quote($task);
        $id     = $db->Quote($id);
        $uid    = $db->Quote($uid);
        $link   = $db->Quote($link);
        $item   = $db->Quote($item);
        $txt    = $db->Quote($txt);


        $query = "INSERT INTO #__user_log VALUES(NULL,$option,$task,$id,$uid,$item,$link,$txt,NOW())";
               $db->setQuery($query);
               $db->query();
    }

    function onAfterInitialise()
    {
        $user = &JFactory::getUser();
        $db   = &JFactory::getDBO();

        $option    = JRequest::getVar('option');
        $task      = JRequest::getVar('task');
        $extension = JRequest::getVar('extension');
        $jform     = JRequest::getVar('jform');
        
        $id     = (int) JRequest::getVar('id');
        $cid    = JRequest::getVar('cid', array());
        $uid    = $user->get('id');
        
        // Do not log guest activity
        if($user->get('guest')) return true;

        // Do not log if no option or task is given
        if(!$option || !$task) return true;

        switch($option)
        {
            // COM_CONTENT START
            case 'com_content':
                switch($task)
                {
                    case 'article.save':
                    case 'article.save2new':
                    case 'article.save2copy':
                    case 'article.apply':
                        $txt = "{name} updated a content article: {title}";
                        if(!$id) {
                            $id  = $this->getAutoIncrement("#__content");
                            $txt = "{name} create a new content article: {title}";
                        }   
                        $link = "index.php?option=com_content&task=article.edit&id=$id";
                        $item = $jform['title'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;

                    case 'articles.trash':
                    case 'articles.publish':
                    case 'articles.unpublish':
                    case 'articles.archive':
                    case 'articles.unarchive':
                        if($task == 'articles.trash')     $txt = "{name} deleted a content article: {title}";
                        if($task == 'articles.publish')   $txt = "{name} enabled a content article: {title}";
                        if($task == 'articles.unpublish') $txt = "{name} disabled a content article: {title}";
                        if($task == 'articles.archive')   $txt = "{name} archived a content article: {title}";
                        if($task == 'articles.unarchive') $txt = "{name} activated a content article: {title}";
                        foreach($cid AS $id)
                        {
                            $link = "";
                            if($task != 'articles.trash' && $task != 'articles.archive') {
                                $link = "index.php?option=com_content&task=article.edit&id=$id";
                            }
                            $item = $this->getItemTitle($id, '#__content');
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                }
                break;
            // COM_CONTENT END
            // COM_CATEGORIES START
            case 'com_categories':
                switch($task)
                {
                    case 'category.save':
                    case 'category.save2new':
                    case 'category.save2copy':
                    case 'category.apply':
                        $txt = "{name} updated a category: {title}";
                        if(!$id) {
                            $id  = $this->getAutoIncrement("#__categories");
                            $txt = "{name} create a new category: {title}";
                        }
                        $link  = "index.php?option=com_categories&task=category.edit&cid[]=$id&extension=$extension";
                        $item  = $jform['title'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;

                    case 'categories.publish':
                    case 'categories.unpublish':
                    case 'categories.trash':
                    case 'categories.archive':
                        if($task == 'categories.publish')   $txt = "{name} enabled a category: {title}";
                        if($task == 'categories.unpublish') $txt = "{name} disabled a category: {title}";
                        if($task == 'categories.trash')     $txt = "{name} deleted a category: {title}";
                        if($task == 'categories.archive')   $txt = "{name} archived a category: {title}";
                        foreach($cid AS $id)
                        {
                            $link = "";
                            if($task != 'categories.trash' && $task != 'categories.archive') {
                                $link = "index.php?option=com_categories&task=category.edit&cid[]=$id&extension=$extension";
                            }
                            $item = $this->getItemTitle($id, '#__categories');
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                }
                break;
            // COM_CATEGORIES END
            // COM_MENUS START
            case 'com_menus':
                switch($task)
                {
                    case 'menu.save':
                    case 'menu.apply':
                    case 'menu.save2new':
                        $menutype = JRequest::getVar('menutype');
                        $menutype = str_replace('_','',$menutype);
                        $menutype = str_replace(' ', '-', $menutype);
                        if($menutype && $jform['title']) {
                            $txt = "{name} updated a menu: {title}";
                            if(!$id) $txt = "{name} created a new menu: {title}";
                            $item = $jform['title'];
                            $link = "index.php?option=com_menus&view=items&menutype=$menutype";
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;

                    case 'menus.delete':
                        foreach($cid AS $id)
                        {
                            $link = "";
                            $txt  = "{name} deleted a menu item: {title}";
                            $item = $this->getItemTitle($id, '#__menu_types');
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                        
                    case 'items.publish':
                    case 'items.unpublish':
                        foreach($cid AS $id)
                        {
                            $query = "SELECT menutype, title FROM #__menu WHERE id = '$id'";
                                   $db->setQuery($query);
                                   $result = $db->loadObject();
                                   
                            $menutype = $result->menutype;
                            $item     = $result->title;
                            $link     = "index.php?option=com_menus&view=items&menutype=$menutype";
                            $txt      = "{name} enabled a menu item: {title}";
                            if($task == 'items.unpublish') $txt = "{name} disabled a menu item: {title}";
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;

                    case 'item.save':
                    case 'item.apply':
                    case 'item.sav2new':
                    case 'item.sav2copy':
                        $txt = "{name} updated a menu item: {title}";
                        if(!$id) $txt = "{txt} created a new menu item: {title}";
                        $link = "index.php?option=com_menus&task=view&menutype=$menutype";
                        $item = $jform['title'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
            // COM_MENUS END
            // COM_MODULES START
            case 'com_modules':
                switch($task)
                {
                    case 'module.save':
                    case 'module.save2new':
                    case 'module.save2copy':
                    case 'module.apply':
                        $link = "index.php?option=com_modules&task=module.edit&id=$id";
                        $txt  = "{name} updated a module: {title}";
                        if(!$id) {
                            $id  = $this->getAutoIncrement("#__modules");
                            $txt = "{name} created a new module: {title}";
                        }
                        $item = $jform['title'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;

                    case 'modules.publish':
                    case 'modules.unpublish':
                    case 'modules.duplicate':
                    case 'modules.trash':
                        if($task == 'modules.publish')   $txt = "{name} enabled a module: {title}";
                        if($task == 'modules.unpublish') $txt = "{name} disabled a module: {title}";
                        if($task == 'modules.duplicate') $txt = "{name} duplicated a module: {title}";
                        if($task == 'modules.trash')     $txt = "{name} deleted a module: {title}";
                        foreach($cid AS $i => $id)
                        {
                            if($task == 'modules.duplicate') $id = $this->getAutoIncrement("#__modules") + $i;
                            $link = "index.php?option=com_modules&task=module.edit&id=$id";
                            if($task == 'modules.trash') $link = "";
                            
                            $query = "SELECT name, element FROM #__extensions WHERE extension_id = '$id'";
                                   $db->setQuery($query);
                                   $result = $db->loadObject();

                            $item = $result->name." ($result->element)";
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                }
                break;
            // COM_MODULES END
            // COM_USERS START
            case 'com_users':
                switch($task)
                {
                    case 'user.save':
                    case 'user.save2new':
                    case 'user.apply':
                        $txt = "{name} updated a user account: {title}";
                        if(!$id) {
                            $id = $this->getAutoIncrement("#__users");
                            $txt = "{name} created a new user account: {title}";
                        }
                        $link = "index.php?option=com_users&task=user.edit&id=$id";
                        $item = $jform['name']."(".$jform['username'].")";
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                        
                    case 'users.activate':
                    case 'users.block':
                    case 'users.delete':
                        if($task == 'users.activate') $txt = "{name} activated a user account: {title}";
                        if($task == 'users.block')    $txt = "{name} blocked a user account: {title}";
                        if($task == 'users.delete')   $txt = "{name} deleted a user account: {title}";
                        foreach($cid AS $id)
                        {
                            $id = (int) $id;
                            $query = "SELECT name, username FROM #__users WHERE id = '$id'";
                                   $db->setQuery($query);
                                   $result = $db->loadObject();
                                   
                            $link = "index.php?option=com_users&task=user.edit&id=$id";
                            if($task == 'users.delete') $link = "";      
                            $item = $result->name." (".$result->username.")";
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                }
                break;   
           // COM_USERS END
           // COM_CONFIG START
           case 'com_config':
                switch($task)
                {
                    case 'application.save':
                    case 'application.apply':
                        $txt  = "{name} changed the global configuration";
                        $item = "";
                        $link = "";
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
            // COM_CONFIG END
            // COM_PLUGINS START
            case 'com_plugins':
                switch($task)
                {
                    case 'plugin.save':
                    case 'plugin.apply':
                        $txt  = "{name} updated a plugin: {title}";
                        $link = "index.php?option=com_plugins&task=plugin.edit&id=$id";
                        $item = $jform['name']." (".$jform['folder'].")";
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;

                    case 'plugins.publish':
                    case 'plugins.unpublish':
                        $txt = "{name} enabled a plugin: {title}";
                        if($task == 'plugins.unpublish') $txt = "{name} disabled a plugin: {title}";
                        foreach($cid AS $id)
                        {
                            $id    = (int) $id;
                            $link  = "index.php?option=com_plugins&task=plugin.edit&id=$id";
                            $query = "SELECT name, element FROM #__extensions WHERE extension_id = '$id'";
                                   $db->setQuery($query);
                                   $result = $db->loadObject();
                                   
                            $item = $result->name." ($result->element)";
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                }
                break;
            // COM_PLUGINS END
            // COM_TEMPLATES START
            case 'com_templates':
                switch($task)
                {
                    case 'styles.sethome':
                    case 'styles.duplicate':
                        $id = (int) $cid[0];
                        $txt = "{name} set the default template style: {title}";
                        if($task == 'styles.duplicate') $txt = "{name} copied a template style: {title}";
                        $link = "index.php?option=com_templates";
                        $item = $this->getItemTitle($id, "#__template_styles", 'template');
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                        
                    case 'style.apply':
                    case 'style.save':
                    case 'style.save2new':
                    case 'style.save2copy':
                        $txt  = "{name} updated a template style: {title}";
                        $link = "index.php?option=com_templates";
                        $item = $jform['template'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;

                    case 'source.save':
                    case 'source.apply':
                        $id    = (int) $jform['extension_id'];
                        $txt   = "{name} changed the source code of a template: {title}";
                        $link  = "index.php?option=com_templates&view=template&id=$id";
                        $query = "SELECT name FROM #__extensions WHERE id = '$id'";
                               $db->setQuery($query);
                               
                        $item = $db->loadResult();  
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
            // COM_TEMPLATES END
            // COM_CHECKIN START
            case 'com_checkin':
                $txt  = "{name} checked-in all items";
                $item = "";
                $link = "";
                $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                break;
            // COM_CHECKIN END
            // COM_CACHE START
            case 'com_cache':
                switch($task)
                {
                    case 'delete':
                    case 'purge':
                        $txt  = "{name} purged the site cache";
                        $item = "";
                        $link = "index.php?option=com_cache";
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
            // COM_CACHE END
            // COM_MESSAGES START
            case 'com_messages':
                switch($task)
                {
                    case 'messages.publish':
                    case 'messages.unpublish':
                    case 'messages.trash':
                        $txt = "{name} marked message as read: {title}";
                        if($task == 'messages.unpublish') $txt = "{name} marked message as unread: {title}";
                        if($task == 'messages.trash') $txt = "{name} deleted a message: {title}";
                        foreach($cid AS $id)
                        {
                            $id = (int) $id;
                            $query = "SELECT message_id,subject FROM #__messages WHERE id = '$id'";
                                   $db->setQuery($query);
                                   $result = $db->loadObject();
                                   
                            $link = "index.php?option=com_messages";
                            if($task == 'messages.trash') $link = "";
                            $item = $result->subject;
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                    
                    case 'message.save':
                        $user_id_to = (int) $jform['user_id_to'];
                        $query = "SELECT name, username FROM #__users WHERE id = '$user_id_to'";
                               $db->setQuery($query);
                               $result = $db->loadObject();

                        $txt  = "{name} sent a message to: {title}";
                        $item = $result->name." ($result->username)";
                        $link = "index.php?option=com_users&task=user.edit&id=$user_id_to";
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
            // COM_MESSAGES END
            // COM_BANNERS START
            case 'com_banners':
                switch($task)
                {
                    case 'banners.publish':
                    case 'banners.unpublish':
                    case 'banners.archive':
                    case 'banners.trash':
                        if($task == 'banners.publish')   $txt = "{name} enabled a banner: {title}";
                        if($task == 'banners.unpublish') $txt = "{name} disabled a banner: {title}";
                        if($task == 'banners.archive')   $txt = "{name} archived a banner: {title}";
                        if($task == 'banners.trash')     $txt = "{name} deleted a banner: {title}";
                        foreach($cid AS $id)
                        {
                            $id   = (int) $id;
                            $item = $this->getItemTitle($id, "#__banners", "name");
                            $link = "index.php?option=com_banners&task=banner.edit&id=$id";
                            if($task == 'banners.trash') $link = "";
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                        
                    case 'banner.save':
                    case 'banner.save2new':
                    case 'banner.save2copy':
                    case 'banner.apply':
                        $txt = "{name} updated a banner: {title}";
                        if(!$id) {
                            $id  = $this->getAutoIncrement('#__banners');
                            $txt = "{name} created a new banner: {title}";
                        }
                        $link = "index.php?option=com_banners&task=banner.edit&id=$id";
                        $item = $jform['name'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                        
                    case 'clients.publish':
                    case 'clients.unpublish':
                    case 'clients.archive':
                    case 'clients.trash':
                        if($task == 'clients.publish')   $txt = "{name} enabled a banner client: {title}";
                        if($task == 'clients.unpublish') $txt = "{name} disabled a banner client: {title}";
                        if($task == 'clients.archive')   $txt = "{name} archived a banner client: {title}";
                        if($task == 'clients.trash')     $txt = "{name} deleted a banner client: {title}";
                        foreach($cid AS $id)
                        {
                            $id   = (int) $id;
                            $item = $this->getItemTitle($id, "#__banner_clients", "name");
                            $link = "index.php?option=com_banners&task=client.edit&id=$id";
                            if($task == 'clients.trash') $link = "";
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                        
                    case 'client.save':
                    case 'client.save2new':
                    case 'client.save2copy':
                    case 'client.apply':
                        $txt = "{name} updated a banner client: {title}";
                        if(!$id) {
                            $id  = $this->getAutoIncrement('#__banner_clients');
                            $txt = "{name} created a new banner client: {title}";
                        }
                        $link = "index.php?option=com_banners&task=client.edit&id=$id";
                        $item = $jform['name'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;    
                }
                break;
            // COM_BANNERS END
            // COM_CONTACT START
            case 'com_contact':
                switch($task)
                {
                    case 'contacts.publish':
                    case 'contacts.unpublish':
                    case 'contacts.archive':
                    case 'contacts.trash':
                        if($task == 'contacts.publish')   $txt = "{name} enabled a contact: {title}";
                        if($task == 'contacts.unpublish') $txt = "{name} disabled a contact: {title}";
                        if($task == 'contacts.archive')   $txt = "{name} archived a contact: {title}";
                        if($task == 'contacts.trash')     $txt = "{name} deleted a contact: {title}";
                        foreach($cid AS $id)
                        {
                            $id = (int) $id;
                            $link = "index.php?option=com_contact&task=contact.edit&cid[]=$id";
                            if($task == 'contacts.trash') $link = "";
                            $item = $this->getItemTitle($id, "#__contact_details", "name");
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                        
                    case 'contact.apply':    
                    case 'contact.save':
                    case 'contact.save2new':
                    case 'contact.save2copy':
                        $txt = "{name} updated a contact: {title}";
                        if(!$id) {
                            $id = $this->getAutoIncrement('#__contact_details');
                            $txt = "{name} created a new contact: {title}";
                        }
                        $link = "index.php?option=com_contact&task=contact.edit&cid[]=$id";
                        $item = $jform['name'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
            // COM_CONTACT END
            // COM_NEWSFEED START
            case 'com_newsfeed':
                switch($task)
                {
                    case 'newsfeeds.publish':
                    case 'newsfeeds.unpublish':
                    case 'newsfeeds.archive':
                    case 'newsfeeds.trash':
                        if($task == 'newsfeed.publish')   $txt = "{name} enabled a newsfeed: {title}";
                        if($task == 'newsfeed.unpublish') $txt = "{name} disabled a newsfeed: {title}";
                        if($task == 'newsfeed.archive')   $txt = "{name} archived a newsfeed: {title}";
                        if($task == 'newsfeed.trash')     $txt = "{name} deleted a newsfeed: {title}";
                        foreach($cid AS $id)
                        {
                            $id = (int) $id;
                            $link = "index.php?option=com_newsfeeds&task=newsfeed.edit&id=$id";
                            if($task == 'newsfeed.trash') $link = "";
                            $item = $this->getItemTitle($id, "#__newsfeeds", "name");
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                        
                    case 'newsfeed.apply':
                    case 'newsfeed.save':
                    case 'newsfeed.save2new':
                        $txt = "{name} updated a newsfeed: {title}";
                        if(!$id) {
                            $txt = "{name} created a newsfeed: {title}";
                            $id  = $this->getAutoIncrement('#__newsfeeds');
                        }
                        $item = $jform['name'];
                        $link = "index.php?option=com_newsfeeds&task=newsfeed.edit&id=$id";
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
            // COM_NEWSFEED END    
            // COM_WEBLINKS START
            case 'com_weblinks':
                switch($task)
                {
                    case 'weblinks.publish':
                    case 'weblinks.unpublish':
                    case 'weblinks.archive':
                    case 'weblinks.trash':
                        if($task == 'weblinks.publish')   $txt = "{name} enabled a weblink: {title}";
                        if($task == 'weblinks.unpublish') $txt = "{name} disabled a weblink: {title}";
                        if($task == 'weblinks.archive')   $txt = "{name} archived a weblink: {title}";
                        if($task == 'weblinks.trash')     $txt = "{name} deleted a weblink: {title}";
                        foreach($cid AS $id)
                        {
                            $id   = (int) $id;
                            $link = "index.php?option=com_weblinks&task=weblink.edit&id=$id";
                            if($task == 'weblinks.trash') $link = "";
                            $item = $this->getItemTitle($id, "#__weblinks");
                            $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        }
                        break;
                        
                    case 'weblink.apply':
                    case 'weblink.save':
                    case 'weblink.save2new':
                    case 'weblink.save2copy':
                        $txt = "{name} updated a weblink: {title}";
                        if(!$id) {
                            $id  = $this->getAutoIncrement('#__weblinks');
                            $txt = "{name} created a new weblink: {title}";
                        }
                        $link = "index.php?option=com_weblinks&task=weblink.edit&id=$id";
                        $item = $jform['title'];
                        $this->logActivity($option, $task, $id, $uid, $link, $item, $txt);
                        break;
                }
                break;
                // COM_WEBLINKS END
        } // LOG END
    } // onAfterInitialise END
}