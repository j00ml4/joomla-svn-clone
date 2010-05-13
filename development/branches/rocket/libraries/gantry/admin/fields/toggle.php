<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();


/**
 * Renders a toggle element
 *
 * @package     gantry
 * @subpackage  admin.elements
 */
gantry_import('core.gantryformfield');

class JFormFieldToggle extends GantryFormField {

	public function getGantryInput(){
		global $gantry;
		$hidden = '<input type="hidden" name="'.$this->name.'" value="_" />';
		
		$options = array ();
        $options[] = array('value'=>1,'text'=>'On/Off','id'=>$this->element->name);

		$document =& JFactory::getDocument();

		if (!defined('GANTRY_TOGGLE')) {
			$this->template = end(explode(DS, $gantry->templatePath));
			
            $document->addScript($gantry->gantryUrl.'/admin/widgets/toggle/js/touch.js');
            $document->addScript($gantry->gantryUrl.'/admin/widgets/toggle/js/toggle.js');
            define('GANTRY_TOGGLE',1);
        }


		$document->addScriptDeclaration($this->toggleInit($this->element['name']));
		
		$checked = ($this->value == 0) ? '' : 'checked="checked"';
		
		return "
		<div class='wrapper'>
			<input name='".$this->name."' value='".$this->value."' type='hidden' />
			<input type='checkbox' class='toggle' id='".$this->id."' $checked />
		</div>
		";
    }

	function toggleInit($id) {
		$js = "
			window.addEvent('domready', function() {
				window.toggle".str_replace("-", "", $id)." = new Toggle('".$this->id."', {
					focus: true, 
					onChange: function(state) {
						var value = (state) ? 1 : 0;
						this.container.getPrevious().value = value;
						
						if (Gantry.MenuItemHead) {
							var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
							if (!cache) cache = new Hash({});
							cache.set('".$id."', value.toString()); 
						}
						
						if (this.container.getParent().getParent() != this.container.getParent().getParent().getParent().getFirst()) return;
						
						var nexts = this.container.getParent().getParent().getParent().getChildren();
						
						if (nexts.length) {
							nexts.each(function(chain) {
								var cls = chain.className.split(' '), type = '';
								cls.each(function(val) {
									if (val.contains('chain-')) type = val.replace('chain-', '');
								});
								
								if (['position', 'groupedselection', 'showmax', 'animation', 'dateformats', 'menuids'].contains(type)) {
									var select = chain.getElement('select');
									if (document.id(select).fireEvent('detach')) {
										if (value) select.fireEvent('attach');
										else select.fireEvent('detach');
									}
								}
								if (['text'].contains(type)) {
									var text = chain.getElement('input[type=\"text\"]');
									if (document.id(text).fireEvent('detach')) {
										if (value) text.fireEvent('attach');
										else text.fireEvent('detach');
									}
								}
								if (['toggle'].contains(type) && chain != this.container.getParent().getParent().getParent().getFirst()) {
									var checkbox = chain.getElement('input[type=checkbox]');
									if (checkbox) {
										(function() {
										if (value) checkbox.fireEvent('attach');
										else checkbox.fireEvent('detach');
										}).delay(10);
									}
								}
							}, this);
						}
					}
				});
			});
		";
		
		return $js;
	}
}
