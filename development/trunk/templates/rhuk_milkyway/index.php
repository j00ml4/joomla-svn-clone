<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$lang =& $mainframe->getLanguage();

// xml prolog
echo '<?xml version="1.0" encoding="utf-8"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<jdoc:placeholder type="head" />
<link href="templates/{TEMPLATE}/css/template.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
<link href="templates/{TEMPLATE}/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->
<?php if ($lang->isRTL()){ ?>
<link href="templates/{TEMPLATE}/css/template_rtl.css" rel="stylesheet" type="text/css" />
<?php } ?>
</head>
<body id="page_bg">
<a name="up" id="up"></a>

<div class="center" align="center">
	<div id="wrapper">
		<div id="wrapper_r">
			<div id="header">
				<div id="header_l">
					<div id="header_r">
						<div id="logo"></div>
						<jdoc:placeholder type="modules" position="top" style="-1" />
					</div>
				</div>
			</div>
			<div id="tabarea">
				<div id="tabarea_l">
					<div id="tabarea_r">
						<div id="tabmenu">
			  	    	<table cellpadding="0" cellspacing="0" class="pill">
	    				    <tr>
	    				      <td class="pill_l">&nbsp;</td>
	    				      <td class="pill_m">
	    				        <div id="pillmenu">
	    				        	<jdoc:placeholder type="modules" position="user3" style="-1" />
	    				        </div>
	    				      </td>
	    				      <td class="pill_r">&nbsp;</td>
	    				    </tr>
	    				  </table>
						</div>
					</div>
				</div>
			</div>
			<div id="search">
				<jdoc:placeholder type="modules" position="user4" style="-1" />
			</div>
			<div id="pathway">
				<jdoc:placeholder type="module" name="breadcrumbs" style="-1" />
			</div>
			<div class="clr"></div>
			<div id="whitebox">
				<div id="whitebox_t">
					<div id="whitebox_tl">
						<div id="whitebox_tr"></div>
					</div>
				</div>
				<div id="whitebox_m">
					<div id="area">
						<div id="leftcolumn">
							<jdoc:placeholder type="modules" position="left" style="-3" />
						</div>
						<div id="maincolumn">
							<?php if(mosCountModules('user1') || mosCountModules('user2')) { ?>
							<table class="nopad user1user2">
								<tr valign="top">
									<?php if(mosCountModules('user1')) { ?>
									<td>
										<jdoc:placeholder type="modules" position="user1" style="-2" />
									</td>
									<?php } ?>
									<?php if(mosCountModules('user1') && mosCountModules('user2')) { ?>
									<td class="greyline">&nbsp;</td>
									<?php } ?>
									<?php if(mosCountModules('user2')) { ?>
									<td>
										<jdoc:placeholder type="modules" position="user2" style="-2" />
									</td>
									<?php } ?>
								</tr>
							</table>
							<div id="maindivider"></div>
							<?php } ?>
							<table class="nopad">
								<tr valign="top">
									<td>
										<jdoc:placeholder type="component" />
									</td>
									<?php if(mosCountModules('right') && $task != 'edit' ) { ?>
									<td class="greyline">&nbsp;</td>
									<td width="170">
										<jdoc:placeholder type="modules" position="right" style="-2"/>
									</td>
									<?php } ?>
								</tr>
							</table>

						</div>
						<div class="clr"></div>
					</div>
					<div class="clr"></div>
				</div>
				<div id="whitebox_b">
					<div id="whitebox_bl">
						<div id="whitebox_br"></div>
					</div>
				</div>
			</div>
			<div id="footerspacer"></div>
		</div>
		<div id="footer">
			<div id="footer_l">
				<div id="footer_r">
					<jdoc:placeholder type="modules" position="footer" style="-1" />
				</div>
			</div>
		</div>
	</div>
</div>
<jdoc:placeholder type="modules" position="debug" style="-1"/>
</body>
</html>
