/**
* @version $Id: $
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Unobtrusive Javascript Tree Manager library
 *
 * Inspired by: Alf Magne Kalleland <www.dhtmlgoodies.com>
 * 
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package		Joomla
 * @subpackage	Menu Manager
 * @since		1.5
 */
JTreeManager = function() { this.constructor.apply(this, arguments);}
JTreeManager.prototype = {

	constructor: function() 
	{	
		var self = this;
		
		this.trees = document.getElementsByClassName('jtree');
		this.cookie = new JCookie();
		this.ajaxObjectArray = new Array();
		this.nodeId = 1;

		// Path to images
		this.imageFolder	= 'images/';

		// Image files
		this.folderImage	= 'folder.gif';
		this.plusImage		= 'plus.gif';
		this.minusImage		= 'minus.gif';

		// Cookie - initially expanded nodes
		this.initExpandedNodes = this.cookie.get('jtree_expandedNodes');

		// AJAX Specific
		this.useAjaxToLoadNodesDynamically = false;
		this.ajaxRequestFile = 'writeNodes.php';

		this.initTree();
	},
	
	registerEvent: function(target,type,args) 
	{
		//use a closure to keep scope
		var self = this;
			
		if (target.addEventListener)   { 
    		target.addEventListener(type,onEvent,true);
		} else if (target.attachEvent) { 
	  		target.attachEvent('on'+type,onEvent);
		} 
		
		function onEvent(e)	{
			e = e||window.event;
			e.element = target;
			return self["on"+type](e, args);
		}
	},

	expandAll: function(treeId)
	{
		var nodes = document.getElementById(treeId).getElementsByTagName('LI');
		for(var no=0;no<nodes.length;no++){
			var subTrees = nodes[no].getElementsByTagName('UL');
			if(subTrees.length>0 && subTrees[0].style.display!='block'){
				this.toggleNode(false,nodes[no].id.replace(/[^0-9]/g,''));
			}			
		}
	},

	collapseAll: function(treeId)
	{
		var nodes = document.getElementById(treeId).getElementsByTagName('LI');
		for(var no=0;no<nodes.length;no++){
			var subTrees = nodes[no].getElementsByTagName('UL');
			if(subTrees.length>0 && subTrees[0].style.display=='block'){
				this.toggleNode(false,nodes[no].id.replace(/[^0-9]/g,''));
			}			
		}		
	},

	toggleNode: function(e,inputId)
	{
		if(inputId){
			if(!document.getElementById('node'+inputId))return;
			thisNode = document.getElementById('node'+inputId).getElementsByTagName('IMG')[0]; 
		} else {
			thisNode = e;
			if(e.tagName=='A')thisNode = e.parentNode.getElementsByTagName('IMG')[0];	
		}
		if(thisNode.style.visibility=='hidden')return;
		var parentNode = thisNode.parentNode;
		inputId = parentNode.id.replace(/[^0-9]/g,'');
		if(thisNode.src.indexOf(this.plusImage)>=0){
			thisNode.src = thisNode.src.replace(this.plusImage,this.minusImage);
			var ul = parentNode.getElementsByTagName('UL')[0];
			ul.style.display='block';
			if(!this.initExpandedNodes)this.initExpandedNodes = ',';
			if(this.initExpandedNodes.indexOf(',' + inputId + ',')<0) this.initExpandedNodes = this.initExpandedNodes + inputId + ',';
			
			if(this.useAjaxToLoadNodesDynamically){	// Using AJAX/XMLHTTP to get data from the server
				var firstLi = ul.getElementsByTagName('LI')[0];
				var parentId = firstLi.getAttribute('parentId');
				if(!parentId)parentId = firstLi.parentId;
				if(parentId){
					ajaxObjectArray[ajaxObjectArray.length] = new sack();
					var ajaxIndex = ajaxObjectArray.length-1;
					ajaxObjectArray[ajaxIndex].requestFile = ajaxRequestFile + '?parentId=' + parentId;					
					ajaxObjectArray[ajaxIndex].onCompletion = function() { getNodeDataFromServer(ajaxIndex,ul.id,parentId); };	// Specify function that will be executed after file has been found					
					ajaxObjectArray[ajaxIndex].runAJAX();		// Execute AJAX function
				}			
			}
		}else{
			thisNode.src = thisNode.src.replace(this.minusImage,this.plusImage);
			parentNode.getElementsByTagName('UL')[0].style.display='none';
			this.initExpandedNodes = this.initExpandedNodes.replace(',' + inputId,'');
		}	
		this.cookie.set('jtree_expandedNodes',this.initExpandedNodes);
		return false;
	},

	addNewNode: function(e)
	{
		if(!okToCreateSubNode)return;
		setTimeout('okToCreateSubNode=true',200);
		contextMenuObj.style.display='none';
		okToCreateSubNode = false;
		source = contextMenuSource;
		while(source.tagName.toLowerCase()!='li')source = source.parentNode;
		
	
		/*
		if (e.target) source = e.target;
			else if (e.srcElement) source = e.srcElement;
			if (source.nodeType == 3) // defeat Safari bug
				source = source.parentNode; */
		//while(source.tagName.toLowerCase()!='li')source = source.parentNode;
		var nameOfNewNode = prompt('Name of new node');
		if(!nameOfNewNode)return;

		uls = source.getElementsByTagName('UL');
		if(uls.length==0){
			var ul = document.createElement('UL');
			source.appendChild(ul);
			
		}else{
			ul = uls[0];
			ul.style.display='block';
		}
		var img = source.getElementsByTagName('IMG');
		img[0].style.visibility='visible';
		var li = document.createElement('LI');
		li.className='dhtmlgoodies_sheet.gif';
		var a = document.createElement('A');
		a.href = '#';
		a.innerHTML = nameOfNewNode;
		li.appendChild(a);
		ul.id = 'newNode' + Math.round(Math.random()*1000000);
		ul.appendChild(li);
		parseSubItems(ul.id);
		saveNewNode(nameOfNewNode,source.getElementsByTagName('A')[0].id);
		
	},

	initTree: function()
	{
		var self = this;

		// Build the tree
		$c(this.trees).each(function(tree){
			// Get an array of all tree nodes
			var nodes = tree.getElementsByTagName('LI');
			this.subcounter	= 0;
			for(var no=0;no<nodes.length;no++){					
				self.nodeId++;
				var subTrees = nodes[no].getElementsByTagName('UL');
				var img = document.createElement('IMG');
				img.src = self.imageFolder + self.plusImage;
				img.onclick = function(){return document.treemanager.toggleNode(this);}
				if(subTrees.length==0) {
					nodes[no].className = 'leaf';
					img.style.visibility='hidden';
				} else {
					nodes[no].className = 'node';
					subTrees[0].id = 'subtree_' + this.subcounter;
					this.subcounter++;
				}
				var aTag = nodes[no].getElementsByTagName('A')[0];
				aTag.onclick = function(){return document.treemanager.toggleNode(this);}
				nodes[no].insertBefore(img,aTag);
				if(!nodes[no].id) nodes[no].id = 'node' + self.nodeId;
			}	
		});  
		if(this.initExpandedNodes){
			var nodes = this.initExpandedNodes.split(',');
			for(var no=0;no<nodes.length;no++){
				if(nodes[no]) this.toggleNode(false,nodes[no]);	
			}			
		}	
	}
}

document.addLoadEvent(function() {
 	document.treemanager = new JTreeManager();
});