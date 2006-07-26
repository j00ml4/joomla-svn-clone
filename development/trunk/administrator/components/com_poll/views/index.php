<?php
/**
* @version $Id: vw_global.php 3692 2006-05-27 05:07:39Z eddieajau $
* @package Joomla
* @subpackage Config
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Config
*/
class JPollView
{
	function showPolls( &$rows, &$pageNav, $option, &$lists )
	{
		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php?option=com_poll" method="post" name="adminForm">

		<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $lists['state'];
				?>
			</td>
		</tr>
		</table>

		<div id="tablecell">
			<table class="adminlist">
			<thead>
				<tr>
					<th width="5">
						<?php echo JText::_( 'NUM' ); ?>
					</th>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
					</th>
					<th  class="title">
						<?php mosCommonHTML::tableOrdering( 'Poll Title', 'm.title', $lists ); ?>
					</th>
					<th width="8%" align="center">
						<?php mosCommonHTML::tableOrdering( 'Published', 'm.published', $lists ); ?>
					</th>
					<th width="3%" nowrap="nowrap">
						<?php mosCommonHTML::tableOrdering( 'ID', 'm.id', $lists ); ?>
					</th>
					<th width="8%" align="center">
						<?php mosCommonHTML::tableOrdering( 'Votes', 'm.voters', $lists ); ?>
					</th>
					<th width="8%" align="center">
						<?php mosCommonHTML::tableOrdering( 'Options', 'numoptions', $lists ); ?>
					</th>
					<th width="10%" align="center">
						<?php mosCommonHTML::tableOrdering( 'Lag', 'm.lag', $lists ); ?>
					</th>
				</tr>
			</thead>
			<?php
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];

				$link 		= ampReplace( 'index2.php?option=com_poll&task=edit&hidemainmenu=1&cid[]='. $row->id );

				$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
				$published 	= mosCommonHTML::PublishedProcessing( $row, $i );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $pageNav->rowNumber( $i ); ?>
					</td>
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit Poll' ); ?>">
							<?php echo $row->title; ?></a>
					</td>
					<td align="center">
						<?php echo $published;?>
					</td>
					<td align="center">
						<?php echo $row->id; ?>
					</td>
					<td align="center">
						<?php echo $row->voters; ?>
					</td>
					<td align="center">
						<?php echo $row->numoptions; ?>
					</td>
					<td align="center">
						<?php echo $row->lag; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tfoot>
				<td colspan="8">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tfoot>
			</table>
		</div>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="" />
		</form>
		<?php
	}


	function editPoll( &$row, &$options, &$lists )
	{
		mosMakeHtmlSafe( $row, ENT_QUOTES );
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton)
		{
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.title.value == "") {
				alert( "<?php echo JText::_( 'Poll must have a title', true ); ?>" );
			} else if( isNaN( parseInt( form.lag.value ) ) ) {
				alert( "<?php echo JText::_( 'Poll must have a non-zero lag time', true ); ?>" );
			//} else if (form.menu.options.value == ""){
			//	alert( "Poll must have pages." );
			//} else if (form.adminForm.textfieldcheck.value == 0){
			//	alert( "Poll must have options." );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">

		<div class="col50">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

				<table class="admintable">
				<tr>
					<td width="110" class="key">
						<label for="title">
							<?php echo JText::_( 'Title' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="title" id="title" size="60" value="<?php echo $row->title; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="lag">
							<?php echo JText::_( 'Lag' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="lag" id="lag" size="10" value="<?php echo $row->lag; ?>" />
						<?php echo JText::_( '(seconds between votes)' ); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" class="key">
						<label for="selections">
							<?php echo JText::_( 'Menu Item Link(s)' ); ?>:
						</label>
					</td>
					<td>
						<?php echo $lists['select']; ?>
					</td>
				</tr>
				</table>
			</fieldset>
		</div>

		<div class="col50">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Options' ); ?></legend>

				<table class="admintable">
				<?php
				for ($i=0, $n=count( $options ); $i < $n; $i++ ) {
					?>
					<tr>
						<td class="key">
							<label for="polloption<?php echo $options[$i]->id; ?>">
								<?php echo JText::_( 'Option' ); ?> <?php echo ($i+1); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="polloption[<?php echo $options[$i]->id; ?>]" id="polloption<?php echo $options[$i]->id; ?>" value="<?php echo stripslashes($options[$i]->text); ?>" size="60" />
						</td>
					</tr>
					<?php
				}
				for (; $i < 12; $i++) {
					?>
					<tr>
						<td class="key">
							<label for="polloption<?php echo $i + 1; ?>">
								<?php echo JText::_( 'Option' ); ?> <?php echo $i + 1; ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="polloption[]" id="polloption<?php echo $i + 1; ?>" value="" size="60" />
						</td>
					</tr>
					<?php
				}
				?>
				</table>
			</fieldset>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_poll" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="textfieldcheck" value="<?php echo $n; ?>" />
		</form>
		<?php
	}

	function previewPoll($title, $options)
	{
		?>
		<form>
		<table align="center" width="90%" cellspacing="2" cellpadding="2" border="0" >
		<tr>
			<td class="moduleheading" colspan="2"><?php echo $title; ?></td>
		</tr>
		<?php foreach ($options as $text)
		{
			if ($text <> "")
			{?>
			<tr>
				<td valign="top" height="30"><input type="radio" name="poll" value="<?php echo $text; ?>"></td>
				<td class="poll" width="100%" valign="top"><?php echo $text; ?></td>
			</tr>
			<?php }
		} ?>
		<tr>
			<td valign="middle" height="50" colspan="2" align="center"><input type="button" name="submit" value="<?php echo JText::_( 'Vote' ); ?>">&nbsp;&nbsp;<input type="button" name="result" value="<?php echo JText::_( 'Results' ); ?>"></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><a href="#" onClick="window.close()"><?php echo JText::_( 'Close' ); ?></a></td>
		</tr>
		</table>
		</form>
		<?php
	}

/*
	function showConfig( &$row, &$lists )
	{
		global $mainframe;

		$document =& $mainframe->getDocument();
		$document->addScript($mainframe->getBaseURL().'components/com_config/assets/switcher.js');

		$contents = '';
		ob_start();
			require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'navigation.html');
		$contents = ob_get_contents();
		ob_end_clean();

		$document->set('module', 'submenu', $contents);
		require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'writeable.html');
		mosCommonHTML::loadOverlib();

		$tabs = new mosTabs(1);
		?>
		<form action="index2.php" method="post" name="adminForm">

		<div id="config-document">
			<div id="page-site">
				<table class="noshow">
					<tr>
						<td with="65%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_site.html'); ?>
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_metadata.html'); ?>
						</td>
						<td width="35%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_debug.html'); ?>
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_statistics.html'); ?>
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_seo.html'); ?>
						</td>
					</tr>
				</table>
			</div>

			<div id="page-user">
				<table class="noshow">
					<tr>
						<td with="50%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_register.html'); ?>
						</td>
						<td with="50%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_user.html'); ?>
						</td>
					</tr>
				</table>

			</div>

			<div id="page-content">
				<table class="noshow">
					<tr>
						<td with="50%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_content.html'); ?>
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_feeds.html'); ?>
						</td>
						<td width="50%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_content2.html'); ?>
						</td>
					</tr>
				</table>
			</div>

			<div id="page-server">
				<table class="noshow">
					<tr>
						<td with="60%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_server.html'); ?>
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_locale.html'); ?>
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_cache.html'); ?>
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_ftp.html'); ?>
						</td>
						<td width="40%">
							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_database.html'); ?>

							<?php require_once(JPATH_COM_CONFIG.DS.'tmpl'.DS.'config_mail.html'); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="c" value="global" />
		<input type="hidden" name="option" value="com_config" />
		<input type="hidden" name="secret" value="<?php echo $row->secret; ?>" />
		<input type="hidden" name="multilingual_support" value="<?php echo $row->multilingual_support; ?>" />
	  	<input type="hidden" name="lang_site" value="<?php echo $row->lang_site; ?>" />
	  	<input type="hidden" name="lang_administrator" value="<?php echo $row->lang_administrator; ?>" />
	  	<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
*/
}
?>
