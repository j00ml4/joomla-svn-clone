<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Media component
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	Media
 * @since 1.0
 */
class MediaViewMediaList extends JView
{
	protected $baseURL = '';
	protected $images = null;
	protected $documents = null;
	protected $folders = null;
	protected $state = null;
	protected $_tmp_folder = null;
	protected $_tmp_img = null;
	protected $_tmp_doc = null;

	public function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();

		// Do not allow cache
		JResponse::allowCache(false);

		$style = $mainframe->getUserStateFromRequest('media.list.layout', 'layout', 'thumbs', 'word');

		JHtml::_('behavior.mootools');

		$document = &JFactory::getDocument();
		$document->addStyleSheet('components/com_media/assets/medialist-'.$style.'.css');

		$document->addScriptDeclaration("
		window.addEvent('domready', function() {
			$$('a.img-preview').each(function(el) {
				el.addEvent('click', function(e) {
					new Event(e).stop();
					window.top.document.preview.fromElement(el);
				});
			});
		});");

		$this->assign('baseURL', JURI::root());
		$this->assignRef('images', $this->get('images'));
		$this->assignRef('documents', $this->get('documents'));
		$this->assignRef('folders', $this->get('folders'));
		$this->assignRef('state', $this->get('state'));

		parent::display($tpl);
	}

	public function setFolder($index = 0)
	{
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = $this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

	public function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_img = $this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}

	public function setDoc($index = 0)
	{
		if (isset($this->documents[$index])) {
			$this->_tmp_doc = $this->documents[$index];
		} else {
			$this->_tmp_doc = new JObject;
		}
	}
}
