<?php
/**
 * jUpgrade
 *
 * @author      Matias Aguirre
 * @email       maguirre@matware.com.ar
 * @url         http://www.matware.com.ar
 * @license     GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

$version = "v{$this->version}";

$document = &JFactory::getDocument();
$document->addScript('components/com_jupgrade/js/functions.js' );
$document->addScript('components/com_jupgrade/js/mtwProgressBar.js' );
?>
<link rel="stylesheet" type="text/css" href="components/com_jupgrade/css/jupgrade.css" />
<!--
			MOOTOOLS


<script type="text/javascript" src="../media/system/js/mootools.js"></script>
-->
<script type="text/javascript">

window.addEvent('domready', function() {

	$('download').setStyle('display', 'none');
	$('decompress').setStyle('display', 'none');
	$('migration').setStyle('display', 'none');
	$('install').setStyle('display', 'none');
	$('done').setStyle('display', 'none');

  $('update').addEvent('click', download);

});

var progress = function(event)  {

  var a = new Ajax( 'components/com_jupgrade/includes/getfilesize.php', {
    method: 'get',
    onComplete: function( msg ) {
        //alert(msg);
				var ex = explode(',', msg);

        currBytes = document.getElementById('currBytes');
        totalBytes = document.getElementById('totalBytes');
        currBytes.innerHTML = ex[1];
        totalBytes.innerHTML = ex[2];

				if(ex[1] < ex[2]){
          pb1.set(ex[0].toInt());
				}else if(ex[1] == ex[2]){
          pb1.set(ex[0].toInt());
          //$clear(progressID);
					return false;
				}
    }
  }).request();

};

function download(event){

  var mySlideUpdate = new Fx.Slide('update');
  mySlideUpdate.toggle();

  var mySlideDownload = new Fx.Slide('download');
  mySlideDownload.hide();
  $('download').setStyle('display', 'block');
  mySlideDownload.toggle();

	pb1 = new mtwProgressBar();

  var a = new Ajax( 'components/com_jupgrade/includes/download.php', {
    method: 'get',
    onRequest: function( response ) {	
			//alert(response);		
      var progressID = progress.periodical(100);
    },
    onComplete: function( response ) {
			//alert(response);
      //alert('finish');
			$('perc').setStyle('background-image', 'url(components/com_jupgrade/images/progress-bar-finish.png)');
      decompress();
    }
  }).request();

};


function decompress(event){

  var mySlideDecompress = new Fx.Slide('decompress');
  mySlideDecompress.hide();
  $('decompress').setStyle('display', 'block');
  mySlideDecompress.toggle();

  var d = new Ajax( 'components/com_jupgrade/includes/decompress.php', {
    method: 'get',
    onComplete: function( response ) {
       //var resp = Json.evaluate( response );
       //alert('finish');
      // Other code to execute when the request completes.
      //$('ajax-container').removeClass('ajax-loading').setHTML( output );
    }
  }).request();

};

</script>

<table width="100%">
<tr>
	<td width="100%" valign="top" align="center">
		<div id="update">
			<img src="components/com_jupgrade/images/update.png" align="middle" border="0"/><br />
			<h2><?php echo JText::_( 'START UPGRADE' ); ?></h2>
		</div>
		<div id="download">
			<p class="text"><?php echo JText::_( 'Downloading Joomla 1.6...' ); ?></p>
			<div id="pb1"></div>
			<div id="downloadtext">
        <i><small><b><span id="currBytes">0</span></b> bytes / <b>
        <span id="totalBytes">0</span></b> bytes</small></i>
      </div>
    </div>
		<div id="decompress">
			<p class="text"><?php echo JText::_( 'Decompressing package...' ); ?></p>
			<div id="pb2"></div>
		</div>
		<div id="install">
			<p class="text"><?php echo JText::_( 'Installing Joomla 1.6...' ); ?></p>
			<div id="pb3"></div>
		</div>
		<div id="migration">
			<p class="text"><?php echo JText::_( 'Upgrade progress...' ); ?></p>
			<div id="pb4"></div>
			<div><i><small><span id="status"><?php echo JText::_( 'Migrating Users...' ); ?></span></i></small></div>
		</div>
		<div id="done">
			<h2><?php echo JText::_( 'Joomla 1.6 Upgrade Finished!' ); ?></h2>
			<p class="text">
				<?php echo JText::_( 'You can check your new site here: ' ); ?>
				<a href="<?php echo JURI::root(); ?>jupgrade/" target="_blank"><?php echo JText::_( 'Site' ); ?></a> and
				<a href="<?php echo JURI::root(); ?>jupgrade/administrator/" target="_blank"><?php echo JText::_( 'Administrator' ); ?></a>
			</p>
		</div>
   </tr>
</table>
<form action="index.php" method="post" name="adminForm">
<input type="hidden" name="option" value="com_cpanel" />
<input type="hidden" name="task" value="" />
</form>

