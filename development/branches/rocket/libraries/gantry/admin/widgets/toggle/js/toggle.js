/*=
	name: Switch
	version: 0.1
	description: On-off iPhone style switch button.
	license: MooTools MIT-Style License (http://mootools.net/license.txt)
	copyright: Valerio Proietti (http://mad4milk.net)
	authors: Valerio Proietti (http://mad4milk.net)
	requires: MooTools 1.11, Touch 0.1+ (http://github.com/kamicane/mootools-touch)
	notes: ported to MooTools 1.11 with some modifications by Djamil Legato (w00fzIT [at] gmail.com)
=*/

var Toggle = new Class({
	options: {
		'class': '.iphone-checkbox',
		'radius': 3,
		'duration': 250,
		'transition': Fx.Transitions.Sine.easeInOut,
		'focus': false
	},
	initialize: function(el, options) {
		this.setOptions(options);
		
		this.check = document.id(el) || false;
		
		if (!this.check) return false;

		this.container = new Element('div', {'class': 'rok-iphone-container'});
		this.sides = new Element('div', {'class': 'rok-iphone-sides'}).inject(this.container);
		this.wrapper = new Element('div', {'class': 'rok-iphone-wrapper'}).inject(this.sides);
		this.switcher = new Element('div', {'class': 'rok-iphone-switch'}).inject(this.sides.getFirst());
		this.button = new Element('div', {'class': 'rok-iphone-button'}).inject(this.sides);
		
		if (this.check.getParent().get('tag') == 'label') {
			this.container.inject(this.check.getParent(), 'after');
			this.check.getParent().inject(this.sides.getFirst()).setStyles({'position': 'absolute', 'left': '-100000px', 'top': 0});
		} else {
			this.container.inject(this.check, 'after');
			this.check.inject(this.sides.getFirst()).setStyles({'position': 'absolute', 'left': '-100000px', 'top': 0});
		}
		
		var self = this;
		this.check.addEvents({
			'attach': this.attach.bind(this),
			'detach': this.detach.bind(this),
			'set': function(value) {self.change(value, true);}
		});
		
		this.height = this.sides.getStyle('height').toInt();
		this.focused = false;
				
		var buttonWidth = this.button.getStyle('width').toInt(), fullWidth = this.sides.getStyle('width').toInt();
		this.min = this.options.radius;
		this.max = fullWidth - buttonWidth - this.min;
		this.width = fullWidth - buttonWidth;
		this.height = this.height;
		this.half = this.width / 2;
		
		this.steps = this.options.duration / this.width;
		
		this.state = !!(this.check.checked);
		this.change(this.state, true);

		this.fx = new Fx({duration: this.options.duration, transition: this.options.transition, 'link': 'cacel'});
		this.fx.set = function(now) {
			if (!$chk(now)) now = this.fx.now;
			this.update(now);
		}.bind(this);
		this.fx.increase = this.fx.set;
		
		this.drag = new Touch(this.button);
		var cancel = function() {
			if (!this.animating) this.toggle();
		}.bind(this);
		
		this.drag.addEvent('start', function(x) {
			this.check.focus();
			this.position = this.button.offsetLeft;
		}.bind(this));
		
		this.drag.addEvent('move', function(x) {
			this.update(this.position + x);
		}.bind(this));
		
		this.drag.addEvent('end', function(x) {
			var left = this.button.offsetLeft;
			
			var status = (left > this.half) ? true : false;
			this.change(status);
		}.bind(this));
		
		this.drag.addEvent('cancel', cancel);
		this.switchButton = new Touch(this.switcher);
		this.switchButton.addEvent('cancel', cancel);
		this.switchButton.addEvent('start', function(e){
			this.check.focus();
		}.bind(this));
		
		return this;
	},
	
	attach: function() {
		this.container.removeClass('disabled');
		this.drag.attach();
		this.switchButton.attach();
	},
	
	detach: function() {
		this.container.addClass('disabled');
		this.drag.detach();
		this.switchButton.detach();
	},
	
	update: function(x) {
		if (x < this.min) x = 0;
		else if (x > this.max) x = this.width;
		
		this.switcher.style.left = x - this.width + 'px';
		this.button.style.left = x + 'px';
		this.updateSides(x);
	},
	
	updateSides: function(x) {
		var pos = '0 0';
		var height = -this.height;
		
		var coords = {
			'off': '0 ' + ((this.focused && this.options.focus) ? (height * 6) : (height * 3)),
			'on': '0 ' + ((this.focused && this.options.focus) ? (height * 5) : (height * 2))
		};

		if (x == 0) pos = coords.off + 'px';
		else if (x == this.width) pos = coords.on + 'px';
		else pos = '0 ' + (height * 4) + 'px';

		this.sides.style.backgroundPosition = pos;
	},
	
	toggle: function() {
		this.change((this.button.offsetLeft > this.half) ? false : true);
	},
	
	change: function(state, noAnim) {
		if (typeof state == 'string') state = state.toInt();
		if (this.animating) return this;
		
		if (noAnim) this.set(state);
		else this.animate(state);
		
		this.check.checked = state;
		this.check.value = (!state) ? 0 : 1;
		this.state = state;
		this.check.fireEvent('onChange', state);
		this.fireEvent('onChange', state);
		
		return this;
	},
	
	set: function(state) {
		if (typeof state == 'string') state = state.toInt();
		this.update(state ? this.width : 0);
	},
	
	animate: function(state) {
		this.animating = true;
		var from = this.button.offsetLeft, to = (state) ? this.width : 0;
		
		this.fx.options.duration = Math.abs(from - to) * this.steps;
		
		this.drag.detach();
		
		this.fx.cancel().start(from, to).chain(function() {
			this.drag.attach();
			this.animating = false;
		}.bind(this));
	}
});

Toggle.implement(new Options);
Toggle.implement(new Events);
