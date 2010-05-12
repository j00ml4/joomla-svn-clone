/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */

var SelectBox = new Class({
	Implements: [Events],
	initialize: function(context) {
		if (!context) context = document.body;
		this.elements = document.id(context).getElements('.selectbox-wrapper');
		
		this.elements.each(function(element, i) {
			var objs = this.getObjects(element), self = this;
			
			this.updateSizes(element);
			
			this.init(element);
			
			objs.real.addEvent('detach', this.detach.bind(this, objs.element));
			objs.real.addEvent('attach', this.attach.bind(this, objs.element));
			objs.real.addEvents({
				'set': function(value) {
					var list = objs.opts.get('value');
					var index = list.indexOf(value);
					objs.list[index].fireEvent('click');
				}
			});
			
			this.lisEvents(element);
			
		}, this);
	},

	updateSizes: function(element) {
		var objs = this.getObjects(element);
		var sizes = {
			dropdown: objs.dropdown.getSize().x,
			arrow: objs.arrow.getSize().x,
			ul: objs.ul.getSize().y
		};
		var max = objs.ul.getStyle('max-height').toInt();
		var offset = (sizes.ul > max) ? 10 : 0;

		objs.top.setStyle('width', sizes.dropdown + offset);
		objs.dropdown.setStyle('width', sizes.dropdown + sizes.arrow + offset);
		if (offset > 0) objs.ul.setStyle('overflow', 'auto');
	},

	getObjects: function(element) {
		return {
			element: element,
			selected: element.getElement('.selectbox-top .selected span'),
			top: element.getElement('.selectbox-top'),
			dropdown: element.getElement('.selectbox-dropdown'),
			arrow: element.getElement('.arrow'),
			ul: element.getElement('ul'),
			list: element.getElements('li'),
			real: element.getParent().getElement('select'),
			opts: element.getParent().getElement('select').getChildren()
		};
	},
	
	init: function(element) {
		element.addEvents({
			click: this.toggle.bind(this, element),
			disable: this.disable.bind(this, element),
			enable: this.enable.bind(this, element),
			mousedown: this.preventDefault.bindWithEvent(this, element),
			onselectstart: this.preventDefault.bindWithEvent(this, element),
			mouseenter: this.enter.bind(this, element),
			mouseleave: this.leave.bind(this, element)
		}, this);
	
	},
	
	lisEvents: function(element) {
		var objs = this.getObjects(element), self = this;
		var realChildren = objs.real.getChildren();

		objs.list.each(function(el, i) {
			if (realChildren[i].getProperty('disabled')) return;
			el.addEvents({
				'mouseenter': function() {
					objs.list.removeClass('hover');
					this.addClass('hover');
				},
				'mouseleave': function() {
					this.removeClass('hover');
				},
				'click': function() {
					objs.list.removeClass('active');
					this.addClass('active');
					this.fireEvent('select', [objs, i]);
				},
				select: self.select.bind(self)
			});
		});	
	},
	
	attach: function(element) {
		element.addEvent('click', this.toggle.bind(this, element));
		element.stat = 'close';
		element.fireEvent('enable', element);
	},
	
	detach: function(element) {
		element.removeEvents('click');
		element.fireEvent('disable', element);
	},
	
	toggle: function(element) {
		var objs = this.getObjects(element);
		if (element.stat == 'open') return this.hide(objs);
		else if (element.stat == 'close') return this.show(objs);
		
		return this.show(objs);
	},
	
	enter: function(element) {
		var objs = this.getObjects(element);

		$clear(element.timer);
	},
	
	leave: function(element) {
		var objs = this.getObjects(element);

		$clear(element.timer);
		element.timer = this.hide.delay(500, this, objs);
	},
	
	show: function(objs) {
		objs.dropdown.setStyle('visibility', 'visible');
		objs.element.addClass('pushed');
		objs.element.stat = 'open';
	},
	
	hide: function(objs) {
		objs.dropdown.setStyle('visibility', 'hidden');
		objs.element.removeClass('pushed');
		objs.element.stat = 'close';
	},
	
	select: function(objs, index) {
		if (index == -1) return;
		objs.selected.set('html', objs.list[index].innerHTML);
		objs.real.selectedIndex = index;

		if (objs.real.id != 'paramsmenuids' && Gantry.MenuItemHead) {
			var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
			if (!cache) cache = new Hash({});
			cache.set(objs.real.id.replace('params', ''), objs.real.value.toString());
		}
		
		objs.real.fireEvent('change', index);
	},
	
	enable: function(element) {
		element.removeClass('disabled');
	},
	
	disable: function(element) {
		$clear(element.timer);
		this.hide(this.getObjects(element));
		element.addClass('disabled');
	},
	
	preventDefault: function(e, element) {
		e.stop();
		return false;
	}
	
});

window.addEvent('domready', function() {window.selectboxes = new SelectBox();});