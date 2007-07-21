<?php
/**
 * Helper class for /joomla/factory.php
 *
 * @package 	Joomla
 * @subpackage 	UnitTest
 * @version    $Id$
 */

class JFactoryTestHelper
{

	/**
	 * Helper used with TestOfJFactory::testGetMailer_smpt() to
	 * provide the required credentials for the SMTP mailer.
	 */
	function GetMailer_smpt($auth = false)
	{
    	$config =& JFactory::getConfig();
    	$config->setValue( 'config.mailer', 'smtp' );

    	// get credentials form TestConfiguration.php
		$cfg = ($auth == false)
			 ? UnitTestHelper::makeCfg(JUT_FACTORY_SMTP_NOAUTH, false, true)
			 : UnitTestHelper::makeCfg(JUT_FACTORY_SMTP_AUTH,   false, true);

    	$config->setValue( 'config.smtpuser', $cfg['user'] );
    	$config->setValue( 'config.smtppass', $cfg['pass'] );
    	$config->setValue( 'config.smtphost', $cfg['host'] );

    	if ($auth) $config->setValue( 'config.smtpauth', $cfg['smtpauth'] );

   	}

	/**
	 * Helper used with TestOfJFactory::testGetDBO() to provide
	 * the required credentials for a database connection.
	 */
	function GetDBO()
	{
		$config =& JFactory::getConfig();
		$config = UnitTestHelper::makeCfg(JUNITTEST_DATABASE_MYSQL4, true);

//$config  =& JFactory::getConfig();
//$context =& SimpleTest::getContext();
//$context->_reporter->dumpCode($config, __FUNCTION__);

	}

}
