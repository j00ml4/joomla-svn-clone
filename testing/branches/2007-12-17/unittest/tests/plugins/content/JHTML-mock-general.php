<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

/**
 * Mock of JHTML for testing the mail cloaking plugin.
 */
class JHTML {
	/**
	 * Simple stub for email.cloak.
	 *
	 * @return string The passed parameters as text wrapped in square brackets,
	 * with all @ signs replaced with " at " to prevent unterminated looping when the
	 * results are included in the subject text.
	 */
	function _($type, $mail, $mailto = 1, $text = '', $email = 1) {
		$mail = str_replace('@', ' at ', $mail);
		$text = str_replace('@', ' at ', $text);
		return '[mail=' . $mail . ' mailto=' . $mailto
			. ' text=' . $text . ' email=' . $email . ']';
	}

}
?>
