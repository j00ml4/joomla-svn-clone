<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
                <!-- template designed by Marco Von Ballmoos -->
		<!-- template design adapted for Joomla! by Chris Davenport -->
		<meta name="description" content="Joomla! API Reference - Application Programming Interface for Joomla!" />
		<meta name="keywords" content="Joomla, joomla, develop, developer, development, JDN, API" />
		<meta name="Generator" content="Joomla! - Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved." />
		<meta name="robots" content="index, follow" />
		<!-- base href="http://api.joomla.org/" -->
		<link rel="shortcut icon" href="{$subdir}media/images/favicon.ico" />
                <title>{$title}</title>
                <link rel="stylesheet" type="text/css" href="{$subdir}media/template_css.css" />
                <link rel="stylesheet" type="text/css" href="{$subdir}media/grey.css" />
                <link rel="stylesheet" type="text/css" href="{$subdir}media/stylesheet.css" />
                <link rel="stylesheet" type="text/css" href="{$subdir}media/xtree.css" />
 		<script type="text/javascript" language="javascript" src="{$subdir}media/md_stylechanger.js"></script>
		<script type="text/javascript" language="javascript" src="{$subdir}media/lib/xtree.js"></script>
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
							<a href="#" title="Increase size" onclick="changeFontSize(1);return false;"><img src="{$subdir}media/images/jos_css_larger.png" alt="larger" border="0" /></a><a href="header.html" title="Decrease size" onclick="changeFontSize(-1);return false;"><img src="{$subdir}media/images/jos_css_smaller.png" alt="smaller" border="0" /></a><a href="header.html" title="Revert styles to default" onclick="revertStyles(); return false;"><img src="{$subdir}media/images/jos_css_reset.png" alt="reset" border="0" /></a>
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
				<li class="orange"><a href="http://shop.joomla.org/">Shop</a></li>
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

                                                        <div class="module-grey">
                                                                <div>
                                                                        <div>
                                                                                <div>
                                                                                        <h3>Joomla! 1.5 Documentation</h3>

							<ul>
								<li><a href="{$subdir}index.html">Home</a></li>
                                                                <li><a href="http://dev.joomla.org/component/option,com_jd-wiki/Itemid,32/">API reference wiki</a></li>
								<li><a href="http://joomlacode.org/gf/project/joomla/scmsvn/?action=browse&path=%2Fdevelopment%2Ftrunk%2F">SVN repository</a></li>
							</ul>
 										</div>
									</div>
								</div>
							</div>

							<div class="module-grey">
								<div>
									<div>
										<div>
											<h3 style="border-bottom: 0">Packages</h3>
					<form action="index.html">
 						<select class="package-selector" onchange="location=this[selectedIndex].value">
                                                        <option value="">Select a package...</option>
 							{section name=packagelist loop=$packageindex}
 							<option value="{$subdir}{$packageindex[packagelist].link}" {if $packageindex[packagelist].title == $info.0.package}selected="selected"{/if}>{$packageindex[packagelist].title}</option>
							{/section}
						</select>
					</form>
 

										</div>
									</div>
								</div>
							</div>

{if $info.0.package != ""}
                                                        <div class="module-grey">
                                                                <div>
                                                                        <div>
                                                                                <div>
											<h3>Package: {$info.0.package}</h3>


<div class="tree">
<script type="text/javascript" language="javascript" src="{$menu}"></script>
</div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

{/if}
                                                        <div class="module-grey">
                                                                <div>
                                                                        <div>
                                                                                <div>
                                                                                        <h3>Other documents</h3>

                                                        <ul>
                                                                <li><a href="{$subdir}ric_CHANGELOG.php.html">Changelog</a></li>
                                                                <li><a href="{$subdir}ric_TODO.php.html">To do</a></li>
						                {if $hastodos}
                                                                <li><a href="{$subdir}{$todolink}">To do (2)</a></li>
 								{/if}
                                                                <li><a href="{$subdir}elementindex.html">Element index (all)</a></li>
                                                                <li><a href="{$subdir}errors.html">Error log</a></li>
                                                                <li><a href="{$subdir}ric_INSTALL.php.html">Install</a></li>
                                                                <li><a href="{$subdir}ric_COPYRIGHT.php.html">Copyright</a></li>
                                                                <li><a href="{$subdir}ric_LICENSE.php.html">License</a></li>
                                                        </ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>


							<div class="module-grey">
								<div>
									<div>
										<div>
											<h3>Developer Network License</h3>
The Joomla! Developer Network content is &copy; copyright 2007 by the individual contributors and can be used in accordance with the <a href="http://creativecommons.org/licenses/by-nc-sa/2.5/">Creative Commons License, Attribution- NonCommercial- ShareAlike 2.5</a>.  Some parts of this website may be subject to other licenses.
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

						</div>

						<div id="maincolumn">


