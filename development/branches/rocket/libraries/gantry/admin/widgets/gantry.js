/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */ 

var Gantry = {
	init: function() {
		Gantry.cookie = Cookie.read('gantry-admin');
		Gantry.cleanance();
		Gantry.slides();
		Gantry.inputs();
		Gantry.menu();
		Gantry.Overlay = new Gantry.Layer();
	},
	
	load: function() {
		Gantry.tips();
	},
	
	inputs: function() {
		var inputs = $$('.text-short, .text-medium, .text-long, .text-color');
		inputs.addEvents({
			'attach': function() {
				this.removeClass('disabled').removeProperty('disabled');
			},
			
			'detach': function() {
				this.addClass('disabled').setProperty('disabled', 'disabled');
			},
			
			'set': function(value) {
				this.value = value;
			},
			
			'keyup': function() {
				if (Gantry.MenuItemHead) {
					var cache = Gantry.MenuItemHead.Cache[Gantry.Selection];
					if (!cache) cache = new Hash({});
					cache.set(this.id.replace('params', ''), this.value);
				}
			}
		});
	},
	
	menu: function() {
		var menu = document.id('paramsmenu-type');
		if (!menu) return;
		
		var options = menu.getElements('option').getProperty('value');
		var selected = menu.getProperty('value');
		var allgroups = [];
		menu.addEvent('change', function() {
			var groups = $$('.group-' + this.value);
			$$(allgroups).setStyle('display', 'none');
			groups.setStyle('display', 'block');
			var id = menu.getParent().getParent().getParent().getParent().getParent().getParent().getParent().getParent().id;
			Gantry.adjustSlides(id);
			if (Gantry.MenuItemHead) Gantry.MenuItemHead.adjustSizes();
		});
		
		options.each(function(option, i) {
			var groups = $$('.group-' + option);
			allgroups.combine(groups);
			//if (option == selected) groups.setStyle('display', 'block');
			//else groups.setStyle('display', 'none');
		});
		
		var id = menu.getParent().getParent().getParent().getParent().getParent().getParent().getParent().getParent().id;
		Gantry.adjustSlides(id);
		
		menu.fireEvent('change', null, 10);
		
	},
	
	adjustSlides: function(id) {
		Gantry.sliders[id].show();
		if (Cookie.read('gantry-admin') && !Cookie.read('gantry-admin').contains(id)) Gantry.sliders[id].hide();
	},
	
	tips: function() {
		var tips = $$('.hasTip');
		Gantry.Tips.init(tips);
	},
	
	cleanance: function() {
		var empties = $$('table.paramlist tr'), tips = $$('.hasTip');
		empties.each(function(empty) {
			var children = empty.getChildren();
			if (children.length < 2) empty.dispose();
			else if(!children[1].innerHTML.length) empty.dispose();
		});

		var diagnostic = document.id('diagnostic');
		
		if (diagnostic) {
			diagnostic.getParent().set('colspan', 2).set('id', 'diagnostic-wrapper').getPrevious().dispose();
		}
	},
	
	slides: function() {
		var titles = $$('.g-title');
		Gantry.sliders = {};
		titles.each(function(title, i) {
			var next = title.getNext(), rel = title.getProperty('rel');
			
			if (next.hasClass('g-inner')) {
				var slide = new Fx.Slide(next, {
					duration: 400 + ((next.offsetHeight / 400).toInt() * 200), 
					wait: false,
					onStart: function() {
						if (this.open) {
							this.wrapper.setStyle('overflow', 'hidden');
							title.getElement('.arrow').removeClass('active');
						} else {
							title.getElement('.arrow').addClass('active');
						}
					},
					onComplete: function() {
						if (this.open) {
							this.wrapper.setStyle('overflow', 'visible');
							Gantry.addToCookie(next.id);
						}
						else {
							title.getElement('.arrow').removeClass('active');
							Gantry.removeFromCookie(next.id);
						}
					}
				});
				Gantry.sliders[next.id] = slide;
				if (Cookie.read('gantry-admin') != false) {
					var open = Gantry.getCookieArray().indexOf(next.id);
					if (open != -1) {
						slide.wrapper.setStyle('overflow', 'visible');
						slide.show.delay(1, slide, '');
						title.getElement('.arrow').addClass('active');
					}
					else slide.hide();
				} else if (GantrySlideList.indexOf(rel) != -1) {
					slide.show().show();
					title.getElement('.arrow').addClass('active');
				} else {
					slide.hide();
				}
			};
			
			title.addEvent('click', function() { slide.toggle(); });
		});
		
		if (Cookie.read('gantry-admin') == false) {
			titles.each(function(title, i) {
				var id = title.getNext().getFirst().id;
				if (Gantry.sliders[id].open) Gantry.addToCookie(id);
			});
		}
	},
	
	getCookieArray: function() {
		var cookie = Cookie.read('gantry-admin');
		if (!cookie) return "";

		return cookie.replace(" ", "").split(",");	
	},
	
	addToCookie: function(id) {
		var cookie;
		if (!Gantry.cookie) Gantry.cookie = Cookie.write('gantry-admin', id + ',', {duration: 365});
		else {
			if (Cookie.read('gantry-admin') == '-empty-') Cookie.write('gantry-admin', '', {duration: 365});
			if (Gantry.getCookieArray().indexOf(id) == -1) {
				cookie = Cookie.read('gantry-admin');
				Cookie.write('gantry-admin', cookie + ',' + id, {duration: 365});
			}
		}
		
		cookie = Cookie.read('gantry-admin');
		if (cookie.substr(-1) == ',') Cookie.write('gantry-admin', cookie.substr(0, cookie.length - 1), {duration: 365});
	},
	
	removeFromCookie: function(id) {
		if (!Gantry.cookie) return;
		
		var cookie = Gantry.getCookieArray();
		if (cookie.indexOf(id) != -1) {
			cookie = cookie.erase(id);
			Cookie.write('gantry-admin', cookie.join(','), {duration: 365});
		}
		
		if (!Cookie.read('gantry-admin').length) Cookie.write('gantry-admin', '-empty-', {duration: 365});
		
	}
};

Gantry.Tips = {
	init: function(tips) {
		Gantry.Tips.clearTips(tips);
		Gantry.Tips.doTips(tips);
	},
	
	clearTips: function(tips) {		
		tips.each(function(tip) {
			tip.removeEvents('mouseenter');
			tip.removeEvents('mousemove');
			tip.removeEvents('mouseleave');
			tip.removeEvents('trash');
		});
	},
	
	doTips: function(tips) {
		Gantry.Tips.tip = {};
		Gantry.Tips.tip.wrapper = new Element('div', {'class': 'gantry-tip-wrapper'}).inject(document.body);
		Gantry.Tips.tip.title =  new Element('div', {'class': 'gantry-tip-title'}).inject(Gantry.Tips.tip.wrapper);
		Gantry.Tips.tip.text =  new Element('div', {'class': 'gantry-tip-text'}).inject(Gantry.Tips.tip.wrapper);
		
		Gantry.Tips.tip.fx = new Fx.Tween(Gantry.Tips.tip.wrapper, {wait: false, duration: 300}).set('opacity', 0);
		
		tips.each(function(tip) {
			var td = tip.getParent().getParent();
			var position = td.getPosition();
			
			td.tooltip = [tip.title.split("::")[0], tip.title.split("::")[1]];
			tip.removeProperty('title');
			
			td.addEvents({
				'mouseenter': function() {
					Gantry.Tips.show(this, tip);
				},
				'mouseleave': function() {
					Gantry.Tips.hide(this);					
				}
			});
		});
	},
	
	show: function(element, tip) {
		if (element) {
			Gantry.Tips.repositionTip(element);
			Gantry.Tips.tip.title.set('text', element.tooltip[0]);
			Gantry.Tips.tip.text.set('text', element.tooltip[1]);
		};
		Gantry.Tips.tip.fx.start('opacity', 1);
	},
	
	hide: function(element) {
		if (element) Gantry.Tips.repositionTip(element);
		Gantry.Tips.tip.fx.start('opacity', 0);		
	},
	
	repositionTip: function(element) {
		var position = element.getCoordinates();
		Gantry.Tips.tip.wrapper.setStyles({
			'top': position.top,
			'left': position.left - Gantry.Tips.tip.wrapper.getStyle('width').toInt()
		});
	}
};

Gantry.Layer = new Class({
	Implements: [Events, Options],
	options: {
		duration: 200,
		opacity: 0.8 
	},
	
	initialize: function(options) {
		var self = this;
		
		this.setOptions(options);
		
		this.id = new Element('div', {id: 'gantry-layer'}).inject(document.body);
		this.fx = new Fx.Tween(this.id, {
			'duration': this.options.duration,
			'wait': false,
			'onComplete': function() {
				if (!this.to[0].value) {
					self.open = false;
				} else {
					self.open = true;
					self.fireEvent('show');
				}
			}
		}).set('opacity', 0);
		this.open = false;
		
	},
	
	show: function() {
		this.calcSizes();
		this.fx.start('opacity', this.options.opacity);
	},
	
	hide: function() {
		this.fireEvent('hide');
		this.fx.start('opacity', 0);
	},
	
	toggle: function() {
		this[this.open ? 'hide' : 'show']();
	},
	
	calcSizes: function() {
		this.id.setStyles({
			'width': window.getScrollSize().x,
			'height': window.getScrollSize().y
		});
	}
});

window.addEvent('domready', Gantry.init);
window.addEvent('load', Gantry.load);
var Tips = new Class({});