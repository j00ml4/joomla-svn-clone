<?php
define( 'VALID_PHP_VERSION', '5.2.9' );

if ( version_compare(phpversion(), VALID_PHP_VERSION, '<') === true )
{
	$version = VALID_PHP_VERSION;
	
    echo  <<<HTML
		<div style="font:12px/1.35em arial, helvetica, sans-serif;">
			<div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
				<h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">
					Invalid PHP version.
				</h3>
			</div>
			
			<p>
				For God sake, upgrade your PHP. It would be {$version}!
			</p>
		</div>
HTML;
    exit;
}

// Set flag that this is a parent file.
define('_JEXEC', 1);
define('JPATH_BASE', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
require_once JPATH_BASE.DS.'includes'.DS.'framework.php';

jimport('joomla.error.profiler');
$profiler = JProfiler::getInstance('Flickr Migration');
$profiler->mark('start');
/**
 * NoixFLAPP
 * 
 * Knowledge instances
 */

$flickr = new JKnowledge('flickr');
$joomla = new JKnowledge('joomla');

/**
 * Setting category id, client id, and type(custom banner or image) 
 * 
 * @var Array
 */
$configBannerParams = array('catid' => 30,'cid' => 1, 'type'=> 1);

/**
 * Assign a Flickr application and get specific photo info
 */
$flickrData = array(
	'photo_info from photoset "72157613071364911"'
);

/**
 * Creating a bridge to this(Joomla) application and specific an "Format"
 * 
 * 
 * @var unknown_type
 */
$bridge = $joomla->createBridge('banner', $configBannerParams);

$bridge->from( $flickr,	$flickrData);

if( $bridge->transport() ){
	$profiler->mark('finish');
	echo 'Flickr data migrated!';
	echo '<pre>';
	foreach($profiler->getBuffer() as $msg){
		echo $msg;
		echo '<br>';
	}	
}
else{
	echo '#fail transporting, dont do this';
}