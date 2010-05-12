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
 * @package     gantry
 * @subpackage  admin.elements
 */
class JFormFieldSlider extends JFormField {
	

	protected function getInput()
	{
		global $gantry;
		$output = '';
		$document =& JFactory::getDocument();

		$this->template = end(explode(DS, $gantry->templatePath));

        $class = $this->element['class'] ? $this->element['class'] : '';
        $name = $this->element['name'] ? $this->element['name']:'';
        $node = $this->element;
        $control_name = $this->name;

		if (!defined('GANTRY_CSS')) {
			$document->addStyleSheet($gantry->gantryUrl.'/admin/widgets/gantry.css');
			define('GANTRY_CSS', 1);
		}
        if (!defined('GANTRY_POSITIONS')) {
            $document->addScript($gantry->gantryUrl.'/admin/widgets/slider/js/slider.js');
			if (!defined('GANTRY_SLIDER')) define('GANTRY_SLIDER', 1);
        }

		$this->children = array();
		
		foreach($node->children() as $children) {
			$this->children[] = $children->data();
		}
		
		$scriptinit = $this->sliderInit($name);		
		$document->addScriptDeclaration($scriptinit);
		
		$output = '
		<div class="wrapper">
		<div id="'.$this->element['name'].'" class="'.$class.'">
			<!--<div class="note">
				Internet Explorer 6 supports only the <strong>Low Quality</strong> setting.
			</div>-->
			<div class="slider">
			    <div class="slider2"></div>
				<div class="knob"></div>
			</div>
			<input type="hidden" id="'.$this->id.'" class="slider" name="'.$this->name.'" value="'.$this->value.'" />
		</div>
		</div>
		';
		
		return $output;
	}
	
	function sliderInit($name) {
		$name2 = str_replace("-", "_", $this->element['name']);
		$steps = count($this->children);
		$current = array_search($this->value, $this->children);
		if ($current === false) $current = 0;
		
		$slider = "document.id('".$this->element['name']."').getElement('.slider')";
		$knob = "document.id('".$this->element['name']."').getElement('.knob')";
		$hidden = "document.id('$this->id')";
		$children = '[\'' . implode("', '", $this->children) . '\']';
		
		$js = "
		window.addEvent('domready', function() {
			$hidden.addEvents({
				'set': function(value) {
					var slider = window.slider$name2;
					var index = slider.list.indexOf(value);

					slider.set(index).fireEvent('onComplete');
				}
			});
			window.slider$name2 = new RokSlider($slider, $knob, {
				steps: 2,
				snap: true,
				initialize: function() {
					this.hiddenEl = $hidden;
				},
				onComplete: function() {
					this.knob.removeClass('down');
					
					if (Gantry.MenuItemHead) {
						var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
						if (!cache) cache = new Hash({});
						cache.set('".$this->element['name']."', this.list[this.step]);
					}
				},
				onDrag: function(now) {
					this.element.getFirst().setStyle('width', now + 10);
				},
				onChange: function(step) {
					$hidden.setProperty('value', this.list[step]);
				},
				onTick: function(position) {
					if(this.options.snap) position = this.toPosition(this.step);
					this.knob.setStyle(this.property, position);
					this.fireEvent('onDrag', position);
				}
			});
			window.slider$name2.list = $children;
			window.slider$name2.set($current);
			
			$knob.addEvents({
				'mousedown': function() {this.addClass('down');},
				'mouseup': function() {this.removeClass('down');}
			});
		});
		";
		
		return $js;
	}
}

?>