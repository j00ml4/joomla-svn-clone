<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2008 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* More details in glossary.php
*/

class listCpanelHTML extends cmsapiAdminHTML {

	private function display ($service) {
		return <<<ONE_SERVICE

		<div class="cmsapicpitem" style="height:68px; width:81px; padding:5px; border:1px solid #999; margin:2px; float:left">
			<a href="index2.php?option=com_glossary&act={$service[1]}">
				<img style="border:0" src="../components/com_glossary/images/admin/{$service[2]}" height="24" width="24" alt="" />
				<div>
					{$service[0]}
				</div>
			</a>
		<!-- End of cmsapicpitem-->
		</div>

ONE_SERVICE;

	}

	public function view () {

		$basic = array (
			array(_GLOSSARY_CPANEL_MANAGE_GLOSSARIES, 'glossaries', 'categories.png'),
			array(_GLOSSARY_CPANEL_MANAGE_ENTRIES, 'entries', 'addedit.png'),
			array(_GLOSSARY_CPANEL_CONFIG, 'config', 'config.png'),
			array(_GLOSSARY_CPANEL_ABOUT, 'about', 'user.png'),
		);

		$controls = '';
		foreach ($basic as $service) $controls .= $this->display($service);

		echo <<<CONTROL_PANEL

		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
   		<tr>
			<td width="75%" colspan="3">
			<div class="title header">
				<img src="../components/com_glossary/images/glosslogo.png" alt="" />
				<span class="sectionname">
					{$this->show(_GLOSSARY_COMPONENT_TITLE)} - {$this->show(_GLOSSARY_CPANEL)}
				</span>
			</div>
			</td>
			<td width="25%">
			</td>
    	</tr>
		</table>
		<div id="cmsapicpbasic" style="width:640px; padding:10px;">
			<h3 style="float:left; width:150px">
				{$this->show(_GLOSSARY_SELECT)}
			</h3>
			$controls
		<!-- End of cmsapicpbasic -->
		</div>

CONTROL_PANEL;

	}
}