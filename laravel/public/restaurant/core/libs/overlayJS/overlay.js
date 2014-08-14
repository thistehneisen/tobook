/*!
 * OverlayJS v1.0
 *
 * Copyright 2011, Dimitar Ivanov (http://www.bulgaria-web-developers.com/projects/javascript/overlay/)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) and GPL Version 3 
 * (http://www.opensource.org/licenses/gpl-3.0.html) license.
 * 
 * Date: Sun Sep 25 20:54:10 2011 +0300
 */
(function (window, undefined) {
	var document = window.document,
		prefix = "ojs-";
		
	function getViewport() {
		var width, height;
		// the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
		if (typeof window.innerWidth !== 'undefined') {
			width = window.innerWidth;
			height = window.innerHeight;
		} else if (typeof document.documentElement !== 'undefined' && 
			typeof document.documentElement.clientWidth !== 'undefined' &&
			document.documentElement.clientWidth !== 0) { // IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
			width = document.documentElement.clientWidth;
			height = document.documentElement.clientHeight;
		} else { // older versions of IE
			width = document.body.clientWidth;
			height = document.body.clientHeight;
		}
		return {"width": parseInt(width, 10), "height": parseInt(height, 10)};
	}
	
	function getScroll() {
		var scrollLeft, scrollTop;
		if (typeof window.pageYOffset !== 'undefined') { //Netscape compliant
			scrollTop = window.pageYOffset;
			scrollLeft = window.pageXOffset;
		} else {
			var B = document.body, //IE 'quirks', DOM compliant
				D = document.documentElement; //IE with doctype
			D = (D.clientHeight) ? D : B;
			scrollTop = D.scrollTop;
			scrollLeft = D.scrollLeft;
		}
		return {"top": parseInt(scrollTop, 10), "left": parseInt(scrollLeft, 10)};
	}
	
	function getDocHeight() {
		return Math.max(
			Math.max(document.body.scrollHeight, document.documentElement.scrollHeight),
			Math.max(document.body.offsetHeight, document.documentElement.offsetHeight),
			Math.max(document.body.clientHeight, document.documentElement.clientHeight)
		);
	}
	
	function getDocWidth() {
		return Math.max(
			Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
			Math.max(document.body.offsetWidth, document.documentElement.offsetWidth),
			Math.max(document.body.clientWidth, document.documentElement.clientWidth)
		);
	}
	
	function addEvent(obj, type, fn) {
		if (obj.attachEvent) {
			obj['e' + type + fn] = fn;
			obj[type + fn] = function () { 
				obj['e' + type + fn](window.event);
			};
			obj.attachEvent('on' + type, obj[type + fn]);
		} else {
			obj.addEventListener(type, fn, false);
		}
	}
	
	function OverlayJS(options) {
		if (!(this instanceof OverlayJS)) {
			return new OverlayJS(options);
		}
		this.curInst = null;
		this.data = {};
		this.id = Math.floor(Math.random() * 999999);
		this.version = "1.0";
		this.isOpen = false;
		this.opts = {
			selector: "",
			width: 320,
			height: 240,
			autoOpen: false,
			modal: false,
			header: true,
			footer: true,
			buttons: {
				'Ok': function () {
					this.close();
				}
			},
			onBeforeOpen: function () {},
			onOpen: function () {},
			onBeforeClose: function () {},
			onClose: function () {}
		};
		for (var attr in options) {
			if (options.hasOwnProperty(attr)) {
				this.opts[attr] = options[attr];
			}
		}
		this._attachOverlay();
		return this;
	}
	OverlayJS.prototype = {
		_attachOverlay: function () {
			var self = this,
				body = document.getElementsByTagName("body")[0],
				elem = document.getElementById(self.opts.selector),
				container = document.createElement("div"),
				wrapper = document.createElement("div"),
				holder = document.createElement("div"),
				header = document.createElement("div"),
				exit = document.createElement('span'),
				content = document.createElement("div"),
				footer = document.createElement("div"),
				viewport = getViewport(),
				scroller = getScroll(),
				btn;
			if (!self.opts.selector || !elem) {
				return;
			}
			container.id = [prefix, "container-", self.id].join("");
			wrapper.id = [prefix, "wrapper-", self.id].join("");
			holder.id = [prefix, "holder-", self.id].join("");
			header.id = [prefix, "header-", self.id].join("");
			content.id = [prefix, "content-", self.id].join("");
			footer.id = [prefix, "footer-", self.id].join("");
			
			container.className = [prefix, "container"].join("");
			wrapper.className = [prefix, "wrapper"].join("");
			holder.className = [prefix, "holder"].join("");
			header.className = [prefix, "header"].join("");
			content.className = [prefix, "content"].join("");
			footer.className = [prefix, "footer"].join("");
			exit.className = [prefix, "close"].join("");
			
			header.innerHTML = elem.title;
			content.innerHTML = elem.innerHTML;
			for (var key in self.opts.buttons) {
				if (self.opts.buttons.hasOwnProperty(key)) {
					btn = document.createElement("input");
					btn.type = "button";
					btn.value = key;
					btn.onclick = function (k) {
						return function () {
							self.opts.buttons[k].apply(self, [this]);
						};
					}(key);
					footer.appendChild(btn);
				}
			}
			exit.onclick = function () {
				self.close();
			};
			header.appendChild(exit);
			
			holder.style.width = self.opts.width + "px";
			holder.style.height = self.opts.height + "px";
			holder.style.webkitTransitionProperty = "top, left";
			holder.style.webkitTransitionDuration = "1000ms";
			
			if (self.opts.header) {
				holder.appendChild(header);
			} else {
				content.style.top = 0;
			}
			holder.appendChild(content);
			if (self.opts.footer) {
				holder.appendChild(footer);
			} else {
				content.style.bottom = 0;
			}
			container.appendChild(wrapper);
			container.appendChild(holder);
			body.appendChild(container);
			
			addEvent(window, "resize", function () {
				self._onWindowResize.call(self);
			});
			addEvent(window, "scroll", function () {
				self._onWindowResize.call(self);
			});
			addEvent(document, "keydown", function (e) {
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if (key === 27) {
					if (self.curInst) {
						self.curInst.close();
					}
				}
			});
			self.content = content;
			self.holder = holder;
			self.wrapper = wrapper;
			self.container = container;
			if (self.opts.autoOpen) {
				self.open();
			}
			return self;
		},
		_setPosition: function () {
			var hs = this.holder.style,
				scroller = getScroll(),
				viewport = getViewport();
			hs.top = [scroller.top + (viewport.height - this.opts.height) / 2, "px"].join("");
			hs.left = [scroller.left + (viewport.width - this.opts.width) / 2, "px"].join("");
		},
		_setSize: function () {
			var ws = this.wrapper.style;
			ws.height = [getDocHeight(), "px"].join("");
			ws.width = [getDocWidth(), "px"].join("");
		},
		_onWindowResize: function () {
			if (!this.isOpen) {
				return;
			}
			this._setPosition();
			this._setSize();
		},
		setData: function (key, value) {
			this.data[key] = value;
			return this;
		},
		getData: function (key) {
			return this.data[key];
		},
		open: function () {
			var self = this,
				result = self.opts.onBeforeOpen.call(self);
			self.curInst = self;
			if (result === false) {
				return self;
			}
			self._setSize();
			self._setPosition();
			self.wrapper.style.display = self.opts.modal ? "block" : "none"; 
			self.container.style.display = "block";
			self.opts.onOpen.call(self);
			self.isOpen = true;
			return self;
		},
		close: function () {
			var self = this,
				result = self.opts.onBeforeClose.call(self);
			if (result === false) {
				return self;
			}
			self.container.style.display = "none";
			self.opts.onClose.call(self);
			self.curInst = null;
			self.isOpen = false;
			return self;
		}
	};
	return (window.OverlayJS = OverlayJS);
})(window);