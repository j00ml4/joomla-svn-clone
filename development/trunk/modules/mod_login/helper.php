<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modLoginHelper
{
	function renderLogoutForm(&$params, $return)
	{
		global $mainframe;

		$user =& $mainframe->getUser();

		?><form action="index.php" method="post" name="login"><?php
		if ($params->get('greeting')) : ?>
			<div><?php echo sprintf( JText::_( 'HINAME' ), $user->get('name') ); ?></div>
		<?php endif; ?>
		<div align="center">
			<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
		</div>

		<input type="hidden" name="option" value="com_login" />
		<input type="hidden" name="task" value="logout" />
		<input type="hidden" name="return" value="<?php echo sefRelToAbs( $return); ?>" />
		</form>
		<?php
	}

	function renderLoginForm(&$params, $return)
	{
		global $mainframe;

		?>
		<form action="index.php" method="post" name="login" >

		<?php echo $params->get('pretext'); ?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<label for="mod_login_username"><?php echo JText::_( 'Username' ); ?></label>
				<br />
				<input name="username" id="mod_login_username" type="text" class="inputbox" alt="<?php echo JText::_( 'Username' ); ?>" size="10" />
				<br />
				<label for="mod_login_password"><?php echo JText::_( 'Password' ); ?></label>
				<br />
				<input type="password" id="mod_login_password" name="passwd" class="inputbox" size="10" alt="<?php echo JText::_( 'Password' ); ?>" />
				<br />
				<input type="checkbox" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="<?php echo JText::_( 'Remember me' ); ?>" />
				<label for="mod_login_remember"><?php echo JText::_( 'Remember me' ); ?></label>
				<br />
				<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGIN'); ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword' ); ?>">
					<?php echo JText::_( 'Lost Password?'); ?>
				</a>
			</td>
		</tr>
		<?php

		if ($mainframe->getCfg('allowUserRegistration')) : ?>
		<tr>
			<td>
				<?php echo JText::_( 'No account yet?'); ?>
				<a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=register' ); ?>">
					<?php echo JText::_( 'Register'); ?>
				</a>
			</td>
		</tr>
		<?php endif; ?>
		</table>
		<?php echo $params->get('posttext'); ?>

		<input type="hidden" name="option" value="com_login" />
		<input type="hidden" name="task" value="login" />
		<input type="hidden" name="return" value="<?php echo sefRelToAbs($return ); ?>" />
		</form>
		<?php
	}

	function getReturnURL()
	{
		// url of current page that user will be returned to after login
		$url = JArrayHelper::getValue($_SERVER, 'REQUEST_URI', null);

		// if return link does not contain https:// & http:// and to url
		if (strpos($url, 'http:') !== 0 && strpos($url, 'https:') !== 0)
		{
			$url = JArrayHelper::getValue($_SERVER, 'HTTP_HOST', null).$url;

			// check if link is https://
			if (isset ($_SERVER['HTTPS']) && (!empty ($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')) {
				$return = 'https://'.$url;
			}
			else
			{
				// normal http:// link
				$return = 'http://'.$url;
			}
		}
		else
		{
			$return = $url;
		}

		// converts & to &amp; for xtml compliance
		$return = str_replace('&', '&amp;', $return);
	}

	function getType()
	{
		global $mainframe;
		$user = & $mainframe->getUser();
	    return ($user->get('id')) ? 'logout' : 'login';
	}
}
