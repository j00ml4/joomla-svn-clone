<?php
/**
 * @version		$Id: default.php 20974 2011-03-16 14:14:03Z chdemko $
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the JavaScript behaviors.
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('script', 'installation/template/js/installation.js', true, false, false, false);
?>

<div id="stepbar">
	<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
	</div>
	<div class="m">
		<?php echo JHtml::_('installation.stepbar', 4); ?>
		<div class="box"></div>
	</div>
	<div class="b">
		<div class="b">
			<div class="b"></div>
		</div>
	</div>
</div>

<form action="index.php" method="post" id="adminForm" class="form-validate">
<div id="right">
	<div id="rightpad">
		<div id="step">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<div class="far-right">
<?php if ($this->document->direction == 'ltr') : ?>
					<div class="button1-right"><div class="prev"><a href="index.php?view=license" rel="prev" title="<?php echo JText::_('JPrevious'); ?>"><?php echo JText::_('JPrevious'); ?></a></div></div>
					<div class="button1-left"><div class="next"><a href="#" onclick="Install.submitform('setup.database');" rel="next" title="<?php echo JText::_('JNext'); ?>"><?php echo JText::_('JNext'); ?></a></div></div>
<?php elseif ($this->document->direction == 'rtl') : ?>
					<div class="button1-right"><div class="prev"><a href="#" onclick="Install.submitform('setup.database');" rel="next" title="<?php echo JText::_('JNext'); ?>"><?php echo JText::_('JNext'); ?></a></div></div>
					<div class="button1-left"><div class="next"><a href="index.php?view=license" rel="prev" title="<?php echo JText::_('JPrevious'); ?>"><?php echo JText::_('JPrevious'); ?></a></div></div>
<?php endif; ?>
				</div>
				<span class="step"><?php echo JText::_('INSTL_DATABASE'); ?></span>
			</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
		</div>
		<div id="installer">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<h2><?php echo JText::_('INSTL_DATABASE_TITLE'); ?></h2>
				<div class="install-text">
						<?php echo JText::_('INSTL_DATABASE_DESC'); ?>
				</div>
				<div class="install-body">
					<div class="t">
						<div class="t">
							<div class="t"></div>
						</div>
					</div>
					<div class="m">
						<h3 class="title-smenu" title="<?php echo JText::_('Basic'); ?>">
							<?php echo JText::_('INSTL_BASIC_SETTINGS'); ?>
						</h3>
						<div class="section-smenu">
							<table class="content2 db-table">
								<tr>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $this->form->getLabel('db_type'); ?>
												<br />
						<select class="inputbox required" name="jform[db_type]" id="jform_db_type">
						<option value="mysql">MySQL</option>
						<option value="mysqli">MySQLi</option>
						<option value="oracle">Oracle</option>
						<option value="sqlsrv">SQL Server 2008</option>
						<option value="sqlazure" selected="selected">SQL Azure</option>
						</select>
														</td>
									<td>
										<em>
										<?php echo JText::_('INSTL_DATABASE_TYPE_DESC'); ?>
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $this->form->getLabel('db_host'); ?>
										<br />
										<input type="text" class="inputbox required" value="<?php echo azure_getconfig('host');?>" id="jform_db_host" name="jform[db_host]">
									</td>
									<td>
										<em>
										<?php echo JText::_('INSTL_DATABASE_HOST_DESC'); ?>
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $this->form->getLabel('db_user'); ?>
										<br />
										<input type="text" class="inputbox required" value="<?php echo azure_getconfig('user');?>" id="jform_db_user" name="jform[db_user]">
									</td>
									<td>
										<em>
										<?php echo JText::_('INSTL_DATABASE_USER_DESC'); ?>
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $this->form->getLabel('db_pass'); ?>
										<br />
										<input type="password" class="inputbox" value="<?php echo azure_getconfig('password');?>" id="jform_db_pass" name="jform[db_pass]">
									</td>
									<td>
										<em>
										<?php echo JText::_('INSTL_DATABASE_PASSWORD_DESC'); ?>
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $this->form->getLabel('db_name'); ?>
										<br />
										<input type="text" class="inputbox required" value="<?php echo azure_getconfig('db');?>" id="jform_db_name" name="jform[db_name]">
									</td>
									<td>
										<em>
										<?php echo JText::_('INSTL_DATABASE_NAME_DESC'); ?>
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $this->form->getLabel('db_prefix'); ?>
										<br />
										<input type="text" class="inputbox required" value="<?php echo azure_getconfig('dbprefix');?>" id="jform_db_prefix" name="jform[db_prefix]">
									</td>
									<td>
										<em>
										<?php echo JText::_('INSTL_DATABASE_PREFIX_DESC'); ?>
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $this->form->getLabel('db_old'); ?>
										<br />
										<?php echo $this->form->getInput('db_old'); ?>
									</td>
									<td>
										<em>
										<?php echo JText::_('INSTL_DATABASE_OLD_PROCESS_DESC'); ?>
										</em>
									</td>
								</tr>																
							</table>
						</div>
						<div class="clr"></div>
					</div>
					<div class="b">
						<div class="b">
							<div class="b"></div>
						</div>
					</div>
					<div class="clr"></div>
				</div>
				<div class="clr"></div>
			</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</div>
<div class="clr"></div>
</form>
