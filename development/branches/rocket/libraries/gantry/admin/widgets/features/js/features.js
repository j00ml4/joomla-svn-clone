/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */

var Features = {
	init: function() {
		Features.el = document.id('features-sort');
		Features.hidden = document.id('paramsfeatures-order');
		
		if (!Features.el) return;
		
		Features.hidden.addEvent('set', function(value) {
			var vals = value.split(',');
			var els = Features.sortables.elements;
			var curs = [];
			els.each(function(li) {
				curs.push(li.get('text'));
			});
			
			Features.hidden.value = value;
			
			var reordered = [];
			
			vals.each(function(val, i) {
				reordered[i] = els[curs.indexOf(val)];
			});

			var ul = Features.el.getElement('ul');
			reordered.reverse().each(function(el) {
				if (document.id(el)) el.inject(ul, 'top');
			});
			
		});
		
		Features.sortables = new Sortables(Features.el.getElement('ul'), {
			ghost: false,
			onStart: function(el) {
				el.addClass('active').setStyle('opacity', 0.6);
			},
			onComplete: function(el) {
				Features.hidden.setProperty('value', this.serialize(Features.serialize).join(','));
				el.removeClass('active').setStyle('opacity', 1);
				if (Gantry.MenuItemHead) {
					var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
					if (!cache) cache = new Hash({});
					cache.set('features-order', Features.hidden.value.toString());
				}
			}
		});
	},
	
	serialize: function(a, b, c) {
		return a.get('text');
	}
	
};

window.addEvent('domready', Features.init);