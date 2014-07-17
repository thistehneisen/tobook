/**
 * @requires JABB 0.2
 */
function TSBCal () {
	var d = window.document;	
	this.options = {};
	this.date = null;
	this.version = "0.1";
	
	this.getVersion = function () {
		return this.version;
	};

	this.bindCalendar = function () {
		var i, len, self = this,
			cells = JABB.Utils.getElementsByClass(this.options.class_name_dates, d.getElementById(this.options.container_calendar), "td");
		//Add "click" event to calendar cells
		for (i = 0, len = cells.length; i < len; i++) {
			JABB.Utils.addEvent(cells[i], "click", function (event) {
				self.triggerLoading('message_1', self.options.container_slots);
				if (self.options.position === 2) {
					d.getElementById(self.options.container_calendar).style.display = 'none';
				}
				self.loadSlots(this.getAttributeNode("axis").value);
			});
		}
		//Add "click" event to calendar nav links
		var a = JABB.Utils.getElementsByClass(self.options.class_name_month, d.getElementById(self.options.container_calendar), "a");
		for (var m, y, rel, j = 0, alen  = a.length; j < alen; j++) {
			a[j].onclick = function () {
				rel = this.getAttributeNode("rel").value;
				switch (rel.split("-")[0]) {
				case 'next':
					m = parseInt(self.options.month, 10) + parseInt(self.options.view, 10) > 12 ? parseInt(self.options.month) + parseInt(self.options.view, 10) - 12 : parseInt(self.options.month) + parseInt(self.options.view, 10);
					y = parseInt(self.options.month, 10) + parseInt(self.options.view, 10) > 12 ? parseInt(self.options.year) + 1 : parseInt(self.options.year);
					break;
				case 'prev':
					m = parseInt(self.options.month, 10) - parseInt(self.options.view, 10) < 1 ? parseInt(self.options.month) - parseInt(self.options.view, 10) + 12 : parseInt(self.options.month) - parseInt(self.options.view, 10);
					y = parseInt(self.options.month, 10) - parseInt(self.options.view, 10) < 1 ? parseInt(self.options.year) - 1 : parseInt(self.options.year);
					break;
				}
				//self.triggerLoading('message_5', self.options.container_calendar);
				d.getElementById(self.options.container_calendar).innerHTML = '';
				d.getElementById(self.options.container_slots).innerHTML = "";
				d.getElementById(['TSBC_Preload_', self.options.calendar_id].join('')).style.display = '';
				self.loadCalendar(m, y);
				return false;
			};
		}
		
		// Add 'mouseover' and 'mouseout' event
		var t = JABB.Utils.getElementsByClass('calendar', d.getElementById(this.options.container_calendar), "TD");
		for (i = 0, len = t.length; i < len; i++) {
			JABB.Utils.addEvent(t[i], "mouseover", function (event) {
				if (d.getElementById('t_' + this.getAttributeNode("id").value)) {
					d.getElementById('t_' + this.getAttributeNode("id").value).style.visibility = 'visible';
				}
			});
			JABB.Utils.addEvent(t[i], "mouseout", function (event) {
				if (d.getElementById('t_' + this.getAttributeNode("id").value)) {
					d.getElementById('t_' + this.getAttributeNode("id").value).style.visibility = 'hidden';
				}
			});
		}
	};
	
	this.bindSlots = function () {
		var i, len, arr, self = this;
				
		// Add "click" event to "Proceed to booking" anchor
		arr = JABB.Utils.getElementsByClass(this.options.class_proceed, d.getElementById(this.options.container_slots), "A");
		for (i = 0, len = arr.length; i < len; i++) {
			arr[i].onclick = function () {
				self.triggerLoading('message_2', self.options.container_slots);
				self.loadBookingForm();
				return false;
			};
		}
		
		// Add "click", "mouseover" and "mouseout" event to rows
		arr = JABB.Utils.getElementsByClass("TSBC_Slot_Enabled", d.getElementById(this.options.container_slots), "TR");
		for (i = 0, len = arr.length; i < len; i++) {
			JABB.Utils.addEvent(arr[i], "mouseover", function () {
				JABB.Utils.addClass(this, "TSBC_Slot_Hover");
			});
			JABB.Utils.addEvent(arr[i], "mouseout", function () {
				JABB.Utils.removeClass(this, "TSBC_Slot_Hover");
			});
			JABB.Utils.addEvent(arr[i], "click", function () {
				var timeslot = JABB.Utils.getElementsByClass("TSBC_Slot", this, "input"),
					isHandle = JABB.Utils.hasClass(this, "TSBC_Handle");// Tells whether or not checkbox is clicked
				if ((!timeslot[0].checked && !isHandle) || (timeslot[0].checked && isHandle)) {
					timeslot[0].checked = true;
					JABB.Ajax.sendRequest(self.options.get_add_cart_url + "&cid=" + self.options.calendar_id, function(req){

					}, timeslot[0].name + "=" + timeslot[0].value);
				} else {
					timeslot[0].checked = false;
					JABB.Ajax.sendRequest(self.options.get_remove_cart_url + "&cid=" + self.options.calendar_id, function(req){
						
					}, timeslot[0].name + "=" + timeslot[0].value);
				}
				JABB.Utils.removeClass(this, "TSBC_Handle");
			});
			var timeslot = JABB.Utils.getElementsByClass("TSBC_Slot", arr[i], "input");
			JABB.Utils.addEvent(timeslot[0], "click", function () {
				JABB.Utils.addClass(this.parentNode.parentNode, "TSBC_Handle");
			});
		}
	};
	
	this.bindClose = function () {
		var i, len, self = this,
		btnClose = JABB.Utils.getElementsByClass(this.options.slots_close, d.getElementById(this.options.container_slots), "a");
		// Add "click" event to Close link
		for (i = 0, len = btnClose.length; i < len; i++) {
			btnClose[i].onclick = function () {
				d.getElementById(self.options.container_slots).innerHTML = "";
				if (self.options.position === 2) {
					d.getElementById(self.options.container_calendar).style.display = "";
				}
				return false;
			};
		}
	};
	
	this.bindBookingForm = function () {
		var self = this;
		// Add "change" event to Payment method combo box
		if (d.forms[self.options.booking_form_name] && d.forms[self.options.booking_form_name][self.options.booking_form_payment_method]) {
			JABB.Utils.addEvent(d.forms[self.options.booking_form_name][self.options.booking_form_payment_method], "change", function () {
				// if there will be any credit card option...
				if (self.options.cc_data_flag) {
					var $ccData = JABB.Utils.getElementsByClass(self.options.cc_data_wrapper, d.forms[self.options.booking_form_name], "DIV")[0],
						$value = this.options[this.selectedIndex].value;
					
					if ($value == 'creditcard') {
						// show the credit cards fields
						$ccData.style.display = "block";
						
						// for each field add a requered class name
						for (i = 0; i < self.options.cc_data_names.length; i++) {
							JABB.Utils.addClass(d.forms[self.options.booking_form_name][self.options.cc_data_names[i]], 'TSBC_Required');
						}
					} else {
						// hide the credit cards fields
						$ccData.style.display = "none";
						
						// for each field remove the requered class name
						for (i = 0; i < self.options.cc_data_names.length; i++) {
							JABB.Utils.removeClass(d.forms[self.options.booking_form_name][self.options.cc_data_names[i]], 'TSBC_Required');
						}
					}
				}
			});
		}
		
		//Add "click" event to Submit button
		if (d.forms[self.options.booking_form_name] && d.forms[self.options.booking_form_name][self.options.booking_form_submit_name]) {
			JABB.Utils.addEvent(d.forms[self.options.booking_form_name][self.options.booking_form_submit_name], "click", function (event) {
				var $this = this;
				$this.disabled = true;
				if (!self.validateBookingForm($this)) {
					return;
				}
				if ($this.form.captcha) {
					JABB.Ajax.getJSON(self.options.get_booking_captcha_url + "&captcha=" + $this.form.captcha.value, function (json) {
						switch (json.code) {
						case 100:
							self.errorHandler(self.options.validation.error_captcha);
							$this.disabled = false;
							break;
						case 200:
							self.loadBookingSummary(JABB.Utils.serialize(d.forms[self.options.booking_form_name]));
							self.triggerLoading('message_4', self.options.container_slots);							
							break;
						}
					});
				} else {
					self.loadBookingSummary(JABB.Utils.serialize(d.forms[self.options.booking_form_name]));
					self.triggerLoading('message_4', self.options.container_slots);					
				}				
			});
		}
		//Add "click" event to Cancel button
		if (d.forms[self.options.booking_form_name] && d.forms[self.options.booking_form_name][self.options.booking_form_cancel_name]) {
			JABB.Utils.addEvent(d.forms[self.options.booking_form_name][self.options.booking_form_cancel_name], "click", function (event) {
				this.disabled = true;
				self.triggerLoading('message_1', self.options.container_slots);
				self.loadSlots();				
			});
		}
	};
	
	this.bindBookingSummary = function (post) {
		var self = this;
		//Add "click" event to Submit button
		if (d.forms[self.options.booking_summary_name] && d.forms[self.options.booking_summary_name][self.options.booking_summary_submit_name]) {
			JABB.Utils.addEvent(d.forms[self.options.booking_summary_name][self.options.booking_summary_submit_name], "click", function (event) {
				var $this = this;
				$this.disabled = true;
				if (!self.validateBookingSummary($this)) {
					return;
				}
				JABB.Ajax.postJSON(self.options.get_booking_save_url + "&cid=" + self.options.calendar_id, function (json) {
					switch (json.code) {
					case 100:
					case 101:
						self.errorHandler(self.options.message_8);
						$this.disabled = false;
						break;
					case 200:
						if (json.processing == 1) {
							self.loadPaymentForm(json);
						} else {
							self.triggerLoading('message_7', self.options.container_slots);
						}
						break;
					}																								
				}, post + "&" + JABB.Utils.serialize(d.forms[self.options.booking_summary_name]) + "&calendar_id=" + self.options.calendar_id);
			});
		}
		//Add "click" event to Cancel button
		if (d.forms[self.options.booking_summary_name] && d.forms[self.options.booking_summary_name][self.options.booking_summary_cancel_name]) {
			JABB.Utils.addEvent(d.forms[self.options.booking_summary_name][self.options.booking_summary_cancel_name], "click", function (event) {
				this.disabled = true;
				self.triggerLoading('message_2', self.options.container_slots);
				self.loadBookingForm(post);
			});
		}
	};
	
	this.bindCart = function () {
		var i, len, arr, self = this;		
		// Add "click", "mouseover" and "mouseout" event to rows in Basket(Cart)
		arr = JABB.Utils.getElementsByClass("TSBC_Slot_Enabled", d.getElementById(this.options.container_slots), "TR");
		for (i = 0, len = arr.length; i < len; i++) {
			JABB.Utils.addEvent(arr[i], "mouseover", function () {
				JABB.Utils.addClass(this, "TSBC_Slot_Remove");
			});
			JABB.Utils.addEvent(arr[i], "mouseout", function () {
				JABB.Utils.removeClass(this, "TSBC_Slot_Remove");
			});
			JABB.Utils.addEvent(arr[i], "click", function () {
				var timeslot = JABB.Utils.getElementsByClass("TSBC_Slot", this, "input");
				timeslot[0].checked = false;
				JABB.Ajax.sendRequest(self.options.get_remove_cart_url + "&cid=" + self.options.calendar_id, function(req){
					self.loadBookingForm(JABB.Utils.serialize(d.forms[self.options.booking_form_name]));
				}, timeslot[0].name + "=" + timeslot[0].value);				
			});
		}
	};
	
	this.loadCart = function () {
		var self = this;
		JABB.Ajax.sendRequest(self.options.get_cart_url + "&cid=" + self.options.calendar_id, function (req) {
			d.getElementById(self.options.container_basket).innerHTML = req.responseText;
			self.bindCart();
			self.bindClose();
			
			JABB.Sort.that = JABB.Sort;
			JABB.Sort.makeSortable(JABB.Utils.getElementsByClass("TSBC_TableSlots")[0]);						
		});
	};
	
	this.loadCalendar = function (m, y) {
		var self = this,
			queryStr = "&cid=" + self.options.calendar_id + "&view=" + self.options.view + "&month=" + m + "&year=" + y;
		JABB.Ajax.sendRequest(self.options.get_calendar_url + queryStr, function (req) {
			d.getElementById(self.options.container_calendar).innerHTML = '';
			d.getElementById(['TSBC_Preload_', self.options.calendar_id].join('')).style.display = 'none';
				
			d.getElementById(self.options.container_calendar).innerHTML = req.responseText;
			d.getElementById(self.options.container_slots).innerHTML = "";
			if (self.options.position === 2) {
				d.getElementById(self.options.container_calendar).style.display = '';
			}
			self.options.month = m;
			self.options.year = y;
			self.bindCalendar();
		});
	};
	
	this.loadSlots = function (date) {
		if (typeof date !== "undefined") {
			this.date = date;
		}
		var self = this,
			qs = "&cid=" + self.options.calendar_id + "&date=" + self.date;
		JABB.Ajax.sendRequest(this.options.get_slots_url + qs, function (req) {
			d.getElementById(self.options.container_slots).innerHTML = req.responseText;
			self.bindSlots();
			self.bindClose();
		});
	};
	
	this.loadBookingForm = function (post) {
		var self = this,
			qs = "&cid=" + self.options.calendar_id;
		JABB.Ajax.sendRequest(self.options.get_booking_form_url + qs, function (req) {
			d.getElementById(self.options.container_slots).innerHTML = req.responseText;
			self.bindBookingForm();
			self.bindClose();
			self.loadCart();
		}, post);
	};
	
	this.loadBookingSummary = function (post) {
		var self = this,
			qs = "&cid=" + self.options.calendar_id;
		JABB.Ajax.sendRequest(self.options.get_booking_summary_url + qs, function (req) {
			d.getElementById(self.options.container_slots).innerHTML = req.responseText;
			self.bindBookingSummary(post);
			self.bindClose();
		}, post);
	};
	
	this.loadPaymentForm = function (obj) {
		var self = this,
		qs = "&cid=" + self.options.calendar_id,
		bs, div;
		JABB.Ajax.sendRequest(self.options.get_payment_form_url + qs, function (req) {
			bs = d.forms[self.options.booking_summary_name];
			if (bs && bs.parentNode) {
				div = d.createElement("div");
				div.innerHTML = req.responseText;
				bs.parentNode.insertBefore(div, bs);
				
				if (typeof d.forms[self.options.payment[obj.payment]] != 'undefined') {
					d.forms[self.options.payment[obj.payment]].submit();
				} else {
					self.triggerLoading('message_7', self.options.container_slots);
				}
			}
			
		}, "id=" + obj.booking_id);
	};
	
	this.toggleCreditCard = function () {
		
	};
	
	this.validateBookingForm = function (btn) {
		var re = /([0-9a-zA-Z\.\-\_]+)@([0-9a-zA-Z\.\-\_]+)\.([0-9a-zA-Z\.\-\_]+)/,
			message = "";
		
		var frm = d.forms[this.options.booking_form_name];
		for (var i = 0, len = frm.elements.length; i < len; i++) {
			var cls = frm.elements[i].getAttributeNode("class");
			if (cls && cls.value.indexOf("TSBC_Required") !== -1) {
				switch (frm.elements[i].nodeName) {
				case "INPUT":
					switch (frm.elements[i].type) {
					case "checkbox":
					case "radio":
						if (!frm.elements[i].checked && frm.elements[i].getAttribute("rev")) {
							message += "\n - " + frm.elements[i].getAttribute("rev"); 
						}
						break;
					default:
						if (frm.elements[i].value.length === 0 && frm.elements[i].getAttribute("rev")) {
							message += "\n - " + frm.elements[i].getAttribute("rev");
						}
						break;
					}
					break;
				case "TEXTAREA":
					if (frm.elements[i].value.length === 0 && frm.elements[i].getAttribute("rev")) {						
						message += "\n - " + frm.elements[i].getAttribute("rev");
					}
					break;
				case "SELECT":
					switch (frm.elements[i].type) {
					case 'select-one':
						if (frm.elements[i].value.length === 0 && frm.elements[i].getAttribute("rev")) {
							message += "\n - " + frm.elements[i].getAttribute("rev"); 
						}
						break;
					case 'select-multiple':
						var has = false;
						for (j = frm.elements[i].options.length - 1; j >= 0; j = j - 1) {
							if (frm.elements[i].options[j].selected) {
								has = true;
								break;
							}
						}
						if (!has && frm.elements[i].getAttribute("rev")) {
							message += "\n - " + frm.elements[i].getAttribute("rev");
						}
						break;
					}
					break;
				default:
					break;
				}
			}
			if (cls && cls.value.indexOf("TSBC_Email") !== -1) {
				if (frm.elements[i].nodeName === "INPUT" && frm.elements[i].value.length > 0 && frm.elements[i].value.match(re) == null) {
					message += "\n - " + this.options.validation.error_email;
				}
			}
		}
		
		if (JABB.Utils.getElementsByClass("TSBC_Slot", d.getElementById(this.options.container_slots), "INPUT").length === 0) {
			message += "\n - " + this.options.validation.error_min;
		}
		
		if (message.length === 0) {
			return true;
		} else {
			this.errorHandler(message);		
			btn.disabled = false;
			return false;
		}
	};
	
	this.validateBookingSummary = function (btn) {
		var	pass = true,
			message = "\n" + this.options.validation.error_payment;		
		// Validation rules
		
		if (pass) {
			return true;
		} else {
			this.errorHandler(message);		
			btn.disabled = false;
			return false;
		}
	};

	this.errorHandler = function (message) {
		var err = JABB.Utils.getElementsByClass("TSBC_Error", d.getElementById(this.options.container_slots), "P");
		if (err[0]) {
			err[0].innerHTML = message.replace(/\n/g, "<br />");
			err[0].style.display = '';
		} else {
			alert(message);
		}
	};
	
	this.triggerLoading = function (message, container) {
		d.getElementById(container).innerHTML = this.options[message];
		d.getElementById(this.options.container_messages).innerHTML = '';
	};

	this.init = function (calObj) {
		this.options = calObj;
		this.bindCalendar();
	}
}