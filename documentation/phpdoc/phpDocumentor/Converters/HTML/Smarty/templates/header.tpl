<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
                <!-- template designed by Marco Von Ballmoos -->
		<!-- template designed adapted for Joomla! by Chris Davenport -->
		<meta name="description" content="Joomla! API Reference - Application Programming Interface for Joomla!">
		<meta name="keywords" content="Joomla, joomla, develop, development, JDN, API">
		<meta name="Generator" content="Joomla! - Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.">
		<meta name="robots" content="index, follow">
		<!-- base href="http://api.joomla.org/" -->
		<link rel="shortcut icon" href="{$subdir}media/images/favicon.ico">
                <title>{$title}</title>
                <link rel="stylesheet" type="text/css" href="{$subdir}media/template_css.css" />
                <link rel="stylesheet" type="text/css" href="{$subdir}media/grey.css" />
                <link rel="stylesheet" type="text/css" href="{$subdir}media/stylesheet.css" />
 		<script type="text/javascript" language="javascript" src="{$subdir}media/md_stylechanger.js"></script>
		<script type="text/javascript" language="javascript" src="{$subdir}media/lib/classTree.js"></script>
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
                        <script language="javascript" type="text/javascript">
                                var imgPlus = new Image();
                                var imgMinus = new Image();
                                imgPlus.src = "{$subdir}media/images/plus.png";
                                imgMinus.src = "{$subdir}media/images/minus.png";

                                function showNode(Node){ldelim}
                                                        switch(navigator.family){ldelim}
                                                                case 'nn4':
                                                                        // Nav 4.x code fork...
                                                        var oTable = document.layers["span" + Node];
                                                        var oImg = document.layers["img" + Node];
                                                                        break;
                                                                case 'ie4':
                                                                        // IE 4/5 code fork...
                                                        var oTable = document.all["span" + Node];
                                                        var oImg = document.all["img" + Node];
                                                                        break;
                                                                case 'gecko':
                                                                        // Standards Compliant code fork...
                                                        var oTable = document.getElementById("span" + Node);
                                                        var oImg = document.getElementById("img" + Node);
                                                                        break;
                                                        {rdelim}
                                        oImg.src = imgMinus.src;
                                        oTable.style.display = "block";
                                {rdelim}

                                function hideNode(Node){ldelim}
                                                        switch(navigator.family){ldelim}
                                                                case 'nn4':
                                                                        // Nav 4.x code fork...
                                                        var oTable = document.layers["span" + Node];
                                                        var oImg = document.layers["img" + Node];
                                                                        break;
                                                                case 'ie4':
                                                                        // IE 4/5 code fork...
                                                        var oTable = document.all["span" + Node];
                                                        var oImg = document.all["img" + Node];
                                                                        break;
                                                                case 'gecko':
                                                                        // Standards Compliant code fork...
                                                        var oTable = document.getElementById("span" + Node);
                                                        var oImg = document.getElementById("img" + Node);
                                                                        break;
                                                        {rdelim}
                                        oImg.src = imgPlus.src;
                                        oTable.style.display = "none";
                                {rdelim}

                                function nodeIsVisible(Node){ldelim}
                                                        switch(navigator.family){ldelim}
                                                                case 'nn4':
                                                                        // Nav 4.x code fork...
                                                        var oTable = document.layers["span" + Node];
                                                                        break;
                                                                case 'ie4':
                                                                        // IE 4/5 code fork...
                                                        var oTable = document.all["span" + Node];
                                                                        break;
                                                                case 'gecko':
                                                                        // Standards Compliant code fork...
                                                        var oTable = document.getElementById("span" + Node);
                                                                        break;
                                                        {rdelim}
                                        return (oTable && oTable.style.display == "block");
                                {rdelim}

                                function toggleNodeVisibility(Node){ldelim}
                                        if (nodeIsVisible(Node)){ldelim}
                                                hideNode(Node);
                                        {rdelim}else{ldelim}
                                                showNode(Node);
                                        {rdelim}
                                {rdelim}
                        </script>
         </head>

<body id="page_bg" class="grey">
<a id="corner" href="http://www.joomla.org/content/view/689/79/" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='media/images/joomla_donate.png',sizingMethod='scale');">Support Joomla!</a>
<a name="up" id="up"></a>

<div class="center">
        <div id="wrapper">
                <div id="wrapper_r">
                        <div id="header">
                                <div id="header_l">
                                        <div id="header_r">
						<div id="css_buttons">
							<a href="header.html" title="Increase size" onclick="changeFontSize(1);return false;"><img src="{$subdir}media/images/jos_css_larger.png" alt="larger" border="0"></a><a href="header.html" title="Decrease size" onclick="changeFontSize(-1);return false;"><img src="{$subdir}media/images/jos_css_smaller.png" alt="smaller" border="0"></a><a href="header.html" title="Revert styles to default" onclick="revertStyles(); return false;"><img src="{$subdir}media/images/jos_css_reset.png" alt="reset" border="0"></a>
						</div>
                                        </div>
                                </div>
                        </div>
                        <div id="tabarea">
                                <div id="tabarea_l">
                                        <div id="tabarea_r">
                                                <div id="tabmenu">
                                <ul id="mainlevel"><li class="red"><a href="http://www.joomla.org">Main</a></li>
				<li class="purple"><a href="http://www.joomla.org/content/blogcategory/0/33/">News</a></li>
				<li class="blue"><a href="Http://help.joomla.org">Help</a></li>
				<li class="green"><a href="http://forum.joomla.org">Forum</a></li>
				<li class="yellow"><a href="http://extensions.joomla.org/">Extensions</a></li>
				<li class="grey"><a href="http://dev.joomla.org">Developers</a></li>
				</ul>
						</div>
                                                <div id="greymenu">
						</div>
                                        </div>
                                </div>
                        </div>

			<div id="whitebox">
				<div id="whitebox_t">
					<div id="whitebox_tl">
						<div id="whitebox_tr"></div>
					</div>
				</div>
				<div id="whitebox_m">
					<div id="area">
						<div id="leftcolumn">

<div class="tree">
<script language="Javascript">
if (document.getElementById) {
{section name=p loop=$info}
        {if $info[p].subpackage == ""}
                var tree = new WebFXTree('<span class="package">{$info.0.package}</span>');
                tree.setBehavior('classic');
                tree.openIcon = 'media/images/package.png';
                tree.icon = 'media/images/package.png';

                {if $hastodos}
                        var todos = new WebFXTreeItem('To-do List', '{$todolink}');
                        todos.openIcon = 'media/images/Index.png';
                        todos.icon = 'media/images/Index.png';
                        tree.add(todos);
                {/if}

                var class_trees = new WebFXTreeItem('Class trees', '{$classtreepage}.html');
                class_trees.openIcon = 'media/images/Index.png';
                class_trees.icon = 'media/images/Index.png';
                tree.add(class_trees);

                var elements = new WebFXTreeItem('Index of elements', '{$elementindex}.html');
                elements.openIcon = 'media/images/Index.png';
                elements.icon = 'media/images/Index.png';
                tree.add(elements);

                var parent_node;

                 {if $info[p].tutorials}
                        var tree_tutorial = new WebFXTreeItem('Tutorial(s)/Manual(s)', '');
                        tree_tutorial.openIcon = 'media/images/tutorial_folder.png';
                        tree_tutorial.icon = 'media/images/tutorial_folder.png';
                        tree.add(tree_tutorial);

                        {if $info[p].tutorials.pkg}
                                var tree_inner_tutorial = new WebFXTreeItem('Package-level', '');
                                tree_inner_tutorial.openIcon = 'media/images/package_folder.png';
                                tree_inner_tutorial.icon = 'media/images/package_folder.png';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.pkg}
                                        {$info[p].tutorials.pkg[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.cls}
                                var tree_inner_tutorial = new WebFXTreeItem('Class-level', '');
                                tree_inner_tutorial.openIcon = 'media/images/class_folder.png';
                                tree_inner_tutorial.icon = 'media/images/class_folder.png';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.cls}
                                        {$info[p].tutorials.cls[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.proc}
                                var tree_inner_tutorial = new WebFXTreeItem('Function-level', '');
                                tree_inner_tutorial.openIcon = 'media/images/function_folder.png';
                                tree_inner_tutorial.icon = 'media/images/function_folder.png';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.proc}
                                        {$info[p].tutorials.proc[ext]}
                                {/section}
                        {/if}
                {/if}

                 {if $info[p].hasinterfaces}
                        var tree_classe = new WebFXTreeItem('Interface(s)', '{$packagedoc|escape:"quotes"}');
                        tree_classe.openIcon = 'media/images/class_folder.png';
                        tree_classe.icon = 'media/images/class_folder.png';

                        {section name=class loop=$info[p].classes}
                            {if $info[p].classes[class].is_interface}
                                var classe = new WebFXTreeItem('{$info[p].classes[class].title|escape:"quotes"}', '{$info[p].classes[class].link|escape:"quotes"}');
                                classe.openIcon = 'media/images/Interface.png';
                                classe.icon = 'media/images/Interface.png';
                                tree_classe.add(classe);
                                {/if}
                        {/section}

                        tree.add(tree_classe);
                {/if}

                {if $info[p].hasclasses}
                        var tree_classe = new WebFXTreeItem('Class(es)', '{$packagedoc|escape:"quotes"}');
                        tree_classe.openIcon = 'media/images/class_folder.png';
                        tree_classe.icon = 'media/images/class_folder.png';

                        {section name=class loop=$info[p].classes}
                            {if $info[p].classes[class].is_class}
                                var classe = new WebFXTreeItem('{$info[p].classes[class].title|escape:"quotes"}', '{$info[p].classes[class].link|escape:"quotes"}');
                                classe.openIcon = 'media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
                                classe.icon = 'media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
                                tree_classe.add(classe);
                                {/if}
                        {/section}

                        tree.add(tree_classe);
                {/if}

                {if $info[p].functions}
                        var tree_function = new WebFXTreeItem('Function(s)', '{$packagedoc|escape:"quotes"}');
                        tree_function.openIcon = 'media/images/function_folder.png';
                        tree_function.icon = 'media/images/function_folder.png';

                        {section name=nonclass loop=$info[p].functions}
                                var fic = new WebFXTreeItem('{$info[p].functions[nonclass].title|escape:"quotes"}', '{$info[p].functions[nonclass].link|escape:"quotes"}');
                                fic.openIcon = 'media/images/Function.png';
                                fic.icon = 'media/images/Function.png';
                                tree_function.add(fic);
                        {/section}

                        tree.add(tree_function);
                {/if}

                {if $info[p].files}
                        var tree_file = new WebFXTreeItem('File(s)', '{$packagedoc|escape:"quotes"}');
                        tree_file.openIcon = 'media/images/folder.png';
                        tree_file.icon = 'media/images/folder.png';

                        {section name=nonclass loop=$info[p].files}
                                var file = new WebFXTreeItem('{$info[p].files[nonclass].title|escape:"quotes"}', '{$info[p].files[nonclass].link|escape:"quotes"}');
                                file.openIcon = 'media/images/Page.png';
                                file.icon = 'media/images/Page.png';
                                tree_file.add(file);
                        {/section}

                        tree.add(tree_file);
                {/if}

        {else}
                {if $info[p].subpackagetutorial}
                        var subpackagetree = new WebFXTreeItem('<span class="sub-package">{$info[p].subpackagetutorialtitle|strip_tags|escape:"quotes"}</span>', '{$info[p].subpackagetutorialnoa}');
                {else}
                        var subpackagetree = new WebFXTreeItem('<span class="sub-package">{$info[p].subpackage}</span>', '{$packagedoc|escape:"quotes"}');
                {/if}

                subpackagetree.openIcon = 'media/images/package.png';
                subpackagetree.icon = 'media/images/package.png';

                {if $info[p].tutorials}
                        var tree_tutorial = new WebFXTreeItem('Tutorial(s)/Manual(s)', '');
                        tree_tutorial.openIcon = 'media/images/tutorial_folder.png';
                        tree_tutorial.icon = 'media/images/tutorial_folder.png';
                        tree.add(tree_tutorial);

                        {if $info[p].tutorials.pkg}
                                var tree_inner_tutorial = new WebFXTreeItem('Package-level', '');
                                tree_inner_tutorial.openIcon = 'media/images/package_folder.png';
                                tree_inner_tutorial.icon = 'media/images/package_folder.png';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.pkg}
                                        {$info[p].tutorials.pkg[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.cls}
                                var tree_inner_tutorial = new WebFXTreeItem('Class-level', '');
                                tree_inner_tutorial.openIcon = 'media/images/class_folder.png';
                                tree_inner_tutorial.icon = 'media/images/class_folder.png';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.cls}
                                        {$info[p].tutorials.cls[ext]}
                                {/section}
                        {/if}

                        {if $info[p].tutorials.proc}
                                var tree_inner_tutorial = new WebFXTreeItem('Function-level', '');
                                tree_inner_tutorial.openIcon = 'media/images/function_folder.png';
                                tree_inner_tutorial.icon = 'media/images/function_folder.png';
                                tree_tutorial.add(tree_inner_tutorial);

                                parent_node = tree_inner_tutorial;
                                {section name=ext loop=$info[p].tutorials.proc}
                                        {$info[p].tutorials.proc[ext]}
                                {/section}
                        {/if}
                {/if}

                {if $info[p].classes}
                        var subpackagetree_classe = new WebFXTreeItem('Class(es)', '{$packagedoc|escape:"quotes"}');
                        subpackagetree_classe.openIcon = 'media/images/class_folder.png';
                        subpackagetree_classe.icon = 'media/images/class_folder.png';

                        {section name=class loop=$info[p].classes}
                                var classe = new WebFXTreeItem('{$info[p].classes[class].title|escape:"quotes"}', '{$info[p].classes[class].link|escape:"quotes"}');
                                classe.openIcon = 'media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
                                classe.icon = 'media/images/{if $info[p].classes[class].abstract}Abstract{/if}{if $info[p].classes[class].access == 'private'}Private{/if}Class.png';
                                subpackagetree_classe.add(classe);
                        {/section}

                        subpackagetree.add(subpackagetree_classe);
                {/if}

                {if $info[p].functions}
                        var subpackagetree_function = new WebFXTreeItem('Function(s)', '{$packagedoc|escape:"quotes"}');
                        subpackagetree_function.openIcon = 'media/images/function_folder.png';
                        subpackagetree_function.icon = 'media/images/function_folder.png';

                        {section name=nonclass loop=$info[p].functions}
                                var fic = new WebFXTreeItem('{$info[p].functions[nonclass].title|escape:"quotes"}', '{$info[p].functions[nonclass].link|escape:"quotes"}');
                                fic.openIcon = 'media/images/Function.png';
                                fic.icon = 'media/images/Function.png';
                                subpackagetree_function.add(fic);
                        {/section}

                        subpackagetree.add(subpackagetree_function);
                {/if}

                {if $info[p].files}
                        var subpackagetree_file = new WebFXTreeItem('File(s)', '{$packagedoc|escape:"quotes"}');
                        subpackagetree_file.openIcon = 'media/images/folder.png';
                        subpackagetree_file.icon = 'media/images/folder.png';

                        {section name=nonclass loop=$info[p].files}
                                var file = new WebFXTreeItem('{$info[p].files[nonclass].title|escape:"quotes"}', '{$info[p].files[nonclass].link|escape:"quotes"}');
                                file.openIcon = 'media/images/Page.png';
                                file.icon = 'media/images/Page.png';
                                subpackagetree_file.add(file);
                        {/section}

                        subpackagetree.add(subpackagetree_file);
                {/if}

          tree.add(subpackagetree);
        {/if}
{/section}

document.write(tree);
}
</script>
</div>








							<div class="module-grey">
								<div>
									<div>
										<div>
											<h3>Packages</h3>
							<ul id="mainlevel">
							{section name=packagelist loop=$packageindex}
						        <li><a href="{$subdir}{$packageindex[packagelist].link}" class="mainlevel">{$packageindex[packagelist].title}</a></li>
							{/section}
							</ul>
										</div>
									</div>
								</div>
							</div>

							<div class="module-grey">
								<div>
									<div>
										<div>
											<h3>Miscellaneous</h3>
											<ul id="mainlevel">
											{if $hastodos}
												<li><a href="{$subdir}{$todolink}">Todo List</a></li>
											{/if}
						<li><a href="{$subdir}classtrees_{$package}.html" class="menu">Class trees</a></li>
						<li><a href="{$subdir}elementindex_{$package}.html" class="menu">Element index</a></li>
						<li><a href="{$subdir}elementindex.html" class="menu">Element index (all packages)</a></li>
  											</ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

							{if $tutorials}
                                                        <div class="module-grey">
                                                                <div>
                                                                        <div>
                                                                                <div>
											{if $tutorials.pkg}
											<h3>Package tutorials</h3>
                                                                                        <ul id="mainlevel">
                                                                                        {section name=ext loop=$tutorials.pkg}
                                                                                                <li>{$tutorials.pkg[ext]}</li>
                                                                                        {/section}
                                                                                        </ul>
											{/if}

                                                                                        {if $tutorials.cls}
                                                                                        <h3>Class tutorials</h3>
                                                                                        <ul id="mainlevel">
                                                                                        {section name=ext loop=$tutorials.cls}
                                                                                                <li>{$tutorials.cls[ext]}</li>
                                                                                        {/section}
                                                                                        </ul>
 											{/if}

                                                                                        {if $tutorials.proc}
                                                                                        <h3>Procedural tutorials</h3>
                                                                                        <ul id="mainlevel">
                                                                                        {section name=ext loop=$tutorials.proc}
                                                                                                <li>{$tutorials.proc[ext]}</li>
                                                                                        {/section}
                                                                                        </ul>
											{/if}
 
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
							{/if}

							{if !$noleftindex}{assign var="noleftindex" value=false}{/if}
							{if !$noleftindex}
							{if $compiledfileindex}
							<div class="module-grey">
								<div>
                                                                        <div>
                                                                                <div>
                                                                                        <h3>Files for {$package}</h3>
                                                                                        {eval var=$compiledfileindex}
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
							{/if}

                                                        {if $compiledinterfaceindex}
                                                         <div class="module-grey">
                                                                <div>
                                                                        <div>
                                                                                <div>
                                                                                        <h3>Interfaces for {$package}</h3>
                                                                                        {eval var=$compiledinterfaceindex}
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        {/if}

                                                        {if $compiledclassindex}
                                                         <div class="module-grey">
                                                                <div>
                                                                        <div>
                                                                                <div>
                                                                                        <h3>Classes for {$package}</h3>
                                                                                        {eval var=$compiledclassindex}
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        {/if}
 
							{/if}

						</div>

						<div id="maincolumn">


