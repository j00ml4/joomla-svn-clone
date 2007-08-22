<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- template designed by Marco Von Ballmoos -->
		<title>{$title}</title>
		<link rel="stylesheet" href="{$subdir}media/template_css.css" />
		<link rel="stylesheet" href="{$subdir}media/grey.css" />
		<link rel="stylesheet" href="{$subdir}media/stylesheet.css" />
		<link rel="stylesheet" href="{$subdir}media/banner.css" />
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
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
					</div>
				</div>
			</div>
			<div id="tabarea">
				<div id="tabarea_l">
					<div id="tabarea_r">
						<div id="tabmenu">
		  	    	<ul id="mainlevel"><li class="red"><a href="Http://www.joomla.org">Main</a></li>
<li class="purple"><a href="http://www.joomla.org/content/blogcategory/0/33/">News</a></li>
<li class="blue"><a href="Http://help.joomla.org">Help</a></li>
<li class="green"><a href="http://forum.joomla.org">Forum</a></li>
<li class="yellow"><a href="http://extensions.joomla.org/">Extensions</a></li>
<li class="grey"><a href="http://dev.joomla.org">Developers</a></li>
</ul>						</div>
						<div id="greymenu">
                                <form>
                                        {if count($ric) >= 1}
                                                {assign var="last_ric_name" value=""}
                                                {section name=ric loop=$ric}
                                                        {if $last_ric_name != ""} | {/if}
                                                        <a href="{$ric[ric].file}" target="right">{$ric[ric].name}</a>
                                                        {assign var="last_ric_name" value=$ric[ric].name}
                                                {/section}
                                        {/if}
                                        {if count($packages) > 1}
                                                <span class="field">Packages</span>
                                                <select class="package-selector" onchange="window.parent.left_bottom.location=this[selectedIndex].value">
                                                {section name=p loop=$packages}
                                                        <option value="{$packages[p].link}">{$packages[p].title}</option>
                                                {/section}
                                                </select>
                                        {/if}
                                </form>
 						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

			</div>
		</div>
	</body>
</html>
