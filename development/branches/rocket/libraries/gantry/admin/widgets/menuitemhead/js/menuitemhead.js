/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.rockettheme.com/legal/license.php RocketTheme Proprietary Use License
 */ 

Gantry.MenuItemHead = {
		init: function() {
			var mih = document.id('master-bar'), indexing;
			Gantry.Selection = 'defaults';
			if (!mih) return;

			Gantry.MenuItemHead.mih = mih;

			mih.getParent().set('colspan', 2).set('id', 'master-bar-wrapper').getPrevious().empty().dispose();

			var tmp = $$('.adminform').getElement('#master-bar');

			tmp.filter(function(t, i) {
				if (t != null) indexing = i;
			});

			Gantry.MenuItemHead.Table = $$('.adminform')[indexing];
			Gantry.MenuItemHead.Keys = Gantry.MenuItemHead.Table.getElements('.paramlist_key');
			Gantry.MenuItemHead.Params = Gantry.MenuItemHead.Table.getElements('.paramlist_value').slice(2);
			Gantry.MenuItemHead.Toggles = [];
			Gantry.MenuItemHead.Bgs = [];
			Gantry.MenuItemHead.Res = [];
			Gantry.MenuItemHead.Cache = {};
			Gantry.MenuItemHead.Template = $$('input[name=id]');
			if (Gantry.MenuItemHead.Template.length) Gantry.MenuItemHead.Template = Gantry.MenuItemHead.Template[0].value;
			else Gantry.MenuItemHead.Template = false;

			Gantry.MenuItemHead.removeable = [];
			Gantry.MenuItemHead.imapreset = [];
			var imapreset = $$('.im-a-preset');
			Gantry.MenuItemHead.Params.each(function(param, i) {
				var checks = [];
				UnallowedParams.each(function(p) {
					p = 'params' + p;
					if (param.hasChild(document.id(p)) && !checks.contains(i)) {
						Gantry.MenuItemHead.removeable.push(i);
						checks.push(i);
					}
				});

				imapreset.each(function(preset) {
					if (param.hasChild(preset)) Gantry.MenuItemHead.imapreset.push(i);
				});

			});

			var itemsList = $$('.master-items')[0];

			document.id('master-items').addEvent('click', function(e) {
				if (e) new Event(e).stop();

				document.id('master-defaults').removeClass('active');
				itemsList.setStyles({'visibility': 'visible', 'display': 'block'});
				$$('.notice_defaults').setStyle('display', 'none');
				$$('.notice_menuitems').setStyle('display', 'block');
				this.addClass('active');
				var itemID = mih.getElement('select').value;
				var index = mih.getElement('select').getChildren().getProperty('value');

				Gantry.Selection = itemID;

				index = index.indexOf(itemID);
				Gantry.MenuItemHead.adjustSizes(); 
				Gantry.MenuItemHead.show(itemID);
				Gantry.MenuItemHead.select.fireEvent('change', index);
			});

			document.id('master-defaults').addEvent('click', function(e) {
				new Event(e).stop();

				Gantry.Selection = 'defaults';

				Gantry.MenuItemHead.ajax.cancel();
				document.id('master-items').removeClass('active');
				$$('.master-items')[0].setStyle('display', 'none');
				$$('.notice_menuitems').setStyle('display', 'none');
				$$('.notice_defaults').setStyle('display', 'block');
				this.addClass('active');
				var itemID = mih.getElement('select').value;
				Gantry.MenuItemHead.hide(itemID);
				Gantry.MenuItemHead.loadID('defaults');

			});

			Gantry.MenuItemHead.Keys.each(function(key, i) {
				var check = new Element('input', {
					'type': 'checkbox', 
					'style': 'float:left;margin: -2px 5px 0;display:none;',
					'value': 0
				}).inject(key, 'top').addEvents({
					'click': function() {
						var itemID = mih.getElement('select').value;
						var paramsNow = Gantry.MenuItemHead.getElements(this);
						if (Gantry.Selection == 'defaults') paramsNow = [];
						var cache = Gantry.MenuItemHead.Cache[itemID];

						Gantry.MenuItemHead.adjustSizes(i);
						if (this.checked) {
							Gantry.MenuItemHead.adjustSizes(i).setStyle('display', 'none');
							paramsNow.each(function(paramNow) {
								if (!cache.get(paramNow)) cache.set(paramNow, document.id('params' + paramNow).get('value').toString());
							});
						}
						else {
							Gantry.MenuItemHead.Bgs[i].setStyle('display', 'block');
							paramsNow.each(function(paramNow) {
								cache.erase(paramNow);
							});
						}
						//Gantry.MenuItemHead.Res = [];
						//Gantry.MenuItemHead.getParams();
					},
					'switchon': function() {
						this.checked = true;
						Gantry.MenuItemHead.adjustSizes(i).setStyle('display', 'none');
					},
					'switchoff': function() {
						this.checked = false;
						Gantry.MenuItemHead.Bgs[i].setStyle('display', 'block');

					}
				});

				Gantry.MenuItemHead.Toggles.push(check);

				var bg = new Element('div', {
					'class': 'menuitems-patch',
					'styles': {
						'background': '#f6f6f6',
						'position': 'absolute',
						'z-index': 180,
						'top': -10,
						'left': -10,
						'visibility': 'visible',
						'opacity': 0.6,
						'display': 'none'
					}
				}).inject(Gantry.MenuItemHead.Params[i].getFirst());

				Gantry.MenuItemHead.Bgs.push(bg);
			});

			Gantry.MenuItemHead.Toggles = $$(Gantry.MenuItemHead.Toggles);
			Gantry.MenuItemHead.Bgs = $$(Gantry.MenuItemHead.Bgs);

			Gantry.MenuItemHead.select = mih.getElement('select');
			Gantry.MenuItemHead.ParentBgs = new Hash({}); 
			Gantry.MenuItemHead.ParentSettings = new Hash({});

			Gantry.MenuItemHead.select.addEvent('change', function(index) {

				Gantry.MenuItemHead.switchOff();

				var itemID = this.value;
				Gantry.Selection = this.value;
				
				Gantry.MenuItemHead.Bgs.setStyle('background-color', '#f6f6f6');
				
				if (!Gantry.MenuItemHead.Cache[itemID]) {
					Gantry.MenuItemHead.ajax = new Request.HTML({
						url: AdminURI + '?option=com_admin&tmpl=gantry-ajax-admin',
						onSuccess: function(tree, elements, response) {
							if (response.length) {
								var data = JSON.decode(response);
								var params = data['params'];
								var count = new Hash(data['module_counts']);

								params = new Hash(params);
								
								var tree = new Hash(data['tree']);
								
								params.each(function(value, key) {
									if (typeof value == 'string') Gantry.MenuItemHead.Cache[key] = new Hash({});
									else Gantry.MenuItemHead.Cache[key] = new Hash(value);

									var treeValues = {};
									Gantry.MenuItemHead.ParentBgs.set(key, []);
									tree.each(function(tv, tk) {
										new Hash(tv).each(function(tvv, tvk) {
											treeValues[tvk] = tvv;

											var disable = true;
											var td = document.id('params'+tvk).getParents('td');
											td = td[0];
											var cb = td.getPrevious().getElement('input[type=checkbox]');
											
											var parentbg = Gantry.MenuItemHead.ParentBgs.get(key);
											
											if (Gantry.MenuItemHead.Cache[key].get(tvk)) {
												disable = false;
											}
											
											//Gantry.MenuItemHead.Cache[key].set(tvk, tvv);
											
											//var bg = td.getElement('.menuitems-patch');
											//bg.setStyle('background-color', '#ffc');
											var div = td.getPrevious().getFirst();
											var inherited = new Element('span', {'class': 'inherited-span'}).set('html', 'inherited');

											if (div.get('tag') == 'div') inherited.inject(div);
											else {
												var children = td.getPrevious().getChildren();
												var h = td.getSize().y;
												var tmpDiv = new Element('div', {'styles': {'position': 'relative', 'height': h}});
												tmpDiv.inject(td.getPrevious()).adopt(children);
												tmpDiv.getElement('label').setStyle('line-height', h);
												tmpDiv.getElement('input[type=checkbox]').setStyles({
													'position': 'absolute',
													'top': '40%'
												});
												inherited.inject(tmpDiv);
											}
											
											inherited.setStyles({'position': 'absolute', 'right': 0, 'bottom': 0});
											
											parentbg.push(inherited);
											
											if (disable) {
												cb.fireEvent('click');
											}
										});
									});
									
									Gantry.MenuItemHead.ParentSettings.set(itemID, treeValues);
									
									Gantry.MenuItemHead.countPositions[key] = new Hash();
									count.each(function(inner_value, inner_key) {
										if (inner_key == 'sidebar') inner_key = 'mainbody';
										Gantry.MenuItemHead.countPositions[key].set(inner_key + 'Position-currentPosition', inner_value);

										var cur = window['slider' + inner_key.replace("-", "_") + 'Position'].RT.list[inner_value];
										if (cur) {
											var navigation = window['slider' + inner_key.replace("-", "_") + 'Position'].RT.navigation[inner_value - 1];
											navigation.fireEvent('click');
										}
									});
								});
								
								Gantry.MenuItemHead.loadID(itemID);
								return;
							}
						}
					}).post({
						'model': 'menu-items',
						'template': Gantry.MenuItemHead.Template,
						'action': 'pull',
						'menuitem': itemID
					});
				} else {
					Gantry.MenuItemHead.loadID(itemID);
				}

			});

			Gantry.MenuItemHead.removeable.each(function(del, i) {
				Gantry.MenuItemHead.Toggles[del].dispose();
			});

			Gantry.MenuItemHead.imapreset.each(function(preset, i) {
				Gantry.MenuItemHead.Bgs[preset].dispose();
			});

			Gantry.MenuItemHead.Cache['defaults'] = new Hash(JSON.decode(Gantry.MenuItemHead.getParams())['off']);
			Gantry.MenuItemHead.countPositions = {};
			Gantry.MenuItemHead.countPositions['defaults'] = new Hash();
			var counter = $$('.countPositions');
			counter.each(function(count) {
				var cls = count.className.split(" ");
				Gantry.MenuItemHead.countPositions['defaults'].set(cls[0], count.innerHTML);
			});

			Gantry.MenuItemHead.addAjax();
		},

		loadID: function(id) {
			var params = Gantry.MenuItemHead.Cache[id];
			var cache = params;
			
			var parentbgs = Gantry.MenuItemHead.ParentBgs.get(id);
			
			Gantry.MenuItemHead.ParentBgs.each(function(value, key) {
				$$(value).setStyle('display', 'none');
			});
			
			if (parentbgs && parentbgs.length) $$(parentbgs).setStyle('display', 'block');
			
			var defaults = Gantry.MenuItemHead.Cache['defaults'];

			Gantry.MenuItemHead.countPositions[id].each(function(value, key) {
				var k = $$('.'+key)[0];
				if (k) k.set('html', value);
			});
			
			
			var parents = Gantry.MenuItemHead.ParentSettings.get(id);
			defaults.each(function(value, key) {
				if (!params.get(key)) {
					var tmp = value;
					tmp = value.replace(/mb\;/g, '"mb";').replace(/sc\;/g, '"sc";').replace(/sb\;/g, '"sb";').replace(/sa\;/g, '"sa";');
					
					if (document.id('params' + key)) document.id('params' + key).fireEvent('set', tmp);
					else document.id('params' + key + tmp).fireEvent('click');
					Gantry.MenuItemHead.Cache[id].erase(key);
				};
			});
			
			$H(parents).each(function(value, key) {
				if (!params.get(key)) {
					var tmp = value;
					tmp = value.replace(/mb\;/g, '"mb";').replace(/sc\;/g, '"sc";').replace(/sb\;/g, '"sb";').replace(/sa\;/g, '"sa";');
					document.id('params' + key).fireEvent('set', tmp);
					Gantry.MenuItemHead.Cache[id].erase(key);
				};
			});
			
			params.each(function(value, key) {
				var checkbox = Gantry.MenuItemHead.getCheckbox(key);
				if (checkbox && !checkbox.checked) checkbox.click();
				
				var tmp = value;
				tmp = value.replace(/mb\;/g, '"mb";').replace(/sc\;/g, '"sc";').replace(/sb\;/g, '"sb";').replace(/sa\;/g, '"sa";');
				
				if (document.id('params' + key)) document.id('params' + key).fireEvent('set', tmp);
				else document.id('params' + key + tmp).fireEvent('click');
				
				var navigation = window['slider' + key.replace("-", "_")];
				if (navigation && key.contains('Position') && navigation.RT.navigation.length) {
					var cpos = Gantry.MenuItemHead.countPositions[id].get(key + '-currentPosition');
					if (navigation.RT.navigation[cpos - 1]) navigation.RT.navigation[cpos - 1].fireEvent('click');
				}
				
			});

		},

		getCheckbox: function(key) {
			var search = document.id('params' + key);
			if (search) {
				var parent = search.getParent(), match = null;
				while (parent && parent.get('tag') != 'table') {
					if (parent.get('tag') == 'tr') match = parent;
					parent = parent.getParent();
				}

				return match.getFirst().getElement('input[type=checkbox]');
			} else {
				return null;
			}
		},

		getElements: function(checkbox) {
			var td = checkbox.getParent();
			if (td.hasClass('presets-wrapper') || td.get('tag') == 'div') td = td.getParent();
			td = td.getNext();

			var elements = [];
			var inputs = td.getElements('input');
			var select = td.getElements('select');

			if (select.length) elements = inputs.combine(select);
			else elements = inputs;

			if (!elements.length) return [];

			if (elements.length > 10 && td.getElements('.groupedsel').length) {
				var groups = td.getElements('.groupedsel');
				var copy = elements;
				groups.each(function(group) {
					var groups_elements = [];
					var groups_inputs = group.getElements('input');
					var groups_select = group.getElements('select');
					if (groups_select.length) groups_elements = groups_inputs.combine(groups_select);
					else groups_elements = groups_inputs;

					groups_elements.each(function(grEl) {
						elements.erase(grEl);
					});
				});
			};

			var output = elements.getProperty('id').filter(function(el) {
				return el != null && !el.contains("function(");
			}).map(function(el) {
				return el.replace("params", '');
			});


			return output;
		},

		switchOn: function() {
			Gantry.MenuItemHead.Toggles.each(function(toggle) {
				toggle.fireEvent('switchon');
			});
		},

		switchOff: function() {
			Gantry.MenuItemHead.Toggles.each(function(toggle) {
				toggle.fireEvent('switchoff');
			});
		},

		adjustSizes: function(item) {
			var size;

			if (!Gantry.MenuItemHead.Bgs.length) return false;

			if (item != null) {
				size = Gantry.MenuItemHead.Params[item].getSize();
				Gantry.MenuItemHead.Bgs[item].setStyles({
					'width': size.x,
					'height': size.y
				});
			} else {
				for (var i = 0, l = Gantry.MenuItemHead.Bgs.length; i < l; i++) {
					size = Gantry.MenuItemHead.Params[i].getSize();
					Gantry.MenuItemHead.Bgs[i].setStyles({
						'width': size.x,
						'height': size.y
					});
				}
			}

			return Gantry.MenuItemHead.Bgs[item];
		},

		addAjax: function() {

			var apply = document.id('toolbar-apply').getElement('a'), save = document.id('toolbar-save').getElement('a');

			var buttons = {
				'apply': apply,
				'save': save
			};

			var ajax = new Request.HTML({
				url: AdminURI + '?option=com_admin&tmpl=gantry-ajax-admin',
				onSuccess: function(r) {
					//alert('Well done dude. Your Menu Item stuff has been stored. You ready to rock!');
					document.id('master-items').removeClass('active');
					$$('.master-items')[0].setStyle('display', 'none');
					$$('.notice_menuitems').setStyle('display', 'none');
					$$('.notice_defaults').setStyle('display', 'block');
					document.id('master-defaults').addClass('active');
					var itemID = Gantry.MenuItemHead.mih.getElement('select').value;
					Gantry.MenuItemHead.hide(itemID);
					Gantry.MenuItemHead.loadID('defaults');

					(function() {submitform(ajax.joomlaType);}).delay(10);
				}
			});

			apply.onclick = null;
			save.onclick = null;
			$$(apply, save).addEvent('click', function(e) {
				e = new Event(e).stop();

				ajax.joomlaType = this.getParent().id.contains('apply') ? 'apply' : 'save';
				var menuitem = ($$('.master-items')[0].getStyle('display') == 'block' && document.id('master-items').hasClass('active'));

				Gantry.MenuItemHead.disable(ajax.joomlaType, buttons);

				var cache = Gantry.MenuItemHead.Cache;
				var defaults = cache['defaults'];
				delete cache['defaults'];

				var push = {};
				for (var name in cache) {
					push[name] = cache[name].getClean();
				};

				Gantry.MenuItemHead.Cache['defaults'] = defaults;

				ajax.post({
					'model': 'menu-items',
					'template': Gantry.MenuItemHead.Template,
					'action': 'push',
					'menuitems-data': JSON.encode(push)
				});

			});

		},

		disable: function(type, buttons) {
			for (button in buttons) {
				buttons[button].getParent().addClass('disabled');
				buttons[button].removeEvents('click');
			}

			buttons[type].getParent().addClass('spinner');
		},

		show: function(index) {
			Gantry.MenuItemHead.Toggles.setStyle('display', 'block');
			Gantry.MenuItemHead.Bgs.setStyle('display', 'block');
			$$('.preset-saver').setStyle('display', 'none');
		},

		hide: function(index) {
			Gantry.MenuItemHead.Toggles.setStyle('display', 'none');
			Gantry.MenuItemHead.Bgs.setStyle('display', 'none');
			$$('.preset-saver').setStyle('display', 'block');
		},

		getParams: function() {
			var itemID = Gantry.MenuItemHead.mih.getElement('select').get('value');
//			if (Gantry.MenuItemHead.Cache[itemID]) delete Gantry.MenuItemHead.Cache[itemID];
			var values = {
				'menuitem': itemID,
				'on': {},
				'off': {}
			};

			Gantry.MenuItemHead.Toggles.each(function(toggle, index) {
					var param = Gantry.MenuItemHead.Params[index];

					var elements = [], hash = {};

					var inputs = param.getElements('input');
					var select = param.getElements('select');

					if (select.length) elements = inputs.combine(select);
					else elements = inputs;

					if (!elements.length) return;

					if (elements.length > 10 && param.getElements('.groupedsel').length) {
						var groups = param.getElements('.groupedsel');
						var copy = elements;
						groups.each(function(group) {
							var groups_elements = [];
							var groups_inputs = group.getElements('input');
							var groups_select = group.getElements('select');
							if (groups_select.length) groups_elements = groups_inputs.combine(groups_select);
							else groups_elements = groups_inputs;

							groups_elements.each(function(grEl) {
								elements.erase(grEl);
							});
						});
					};

					elements.each(function(el) {
						var name = el.getProperty('name');
						if (!name) return;

						name = name.replace("params[", "").replace("]", "");

						var value = el.getProperty('value');
						
						if (toggle.checked) values['on'][name] = value;
						else values['off'][name] = value;

					});

			});

			var json = JSON.encode(values);

			return json;			
		}
};

Element.extend({
	getParents: function(tag, el) {
		var matched = [];
		var cur = this.getParent();
		while (cur && cur !== (document.id(el) || document)) {
			if(cur.get('tag').test(tag)) matched.push(cur);
			cur = cur.getParent();
		}
		return $$(matched);
	}
});

window.addEvent('domready', Gantry.MenuItemHead.init);