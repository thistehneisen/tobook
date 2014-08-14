/*!
 * Restaurant Booking v1.0
 * http://phpjabbers.com/restaurant-booking/
 * 
 * Copyright 2012, StivaSoft Ltd.
 * 
 * Date: Tue May 15 17:00:00 2012 +0200
 */
(function (window, undefined){
	var document = window.document;
	
	function RBooking(options) {
		if (!(this instanceof RBooking)) {
			return new RBooking(options);
		}
		this.options = {};
		this.container = null;
		this.init(options);
		
		return this;
	}
	
	/* $_GET Prefix */
	var $_GET = {}, $rbpf;

	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
	    function decode(s) {
	        return decodeURIComponent(s.split("+").join(" "));
	    }

	    $_GET[decode(arguments[1])] = decode(arguments[2]);
	});
	
	if ( $_GET['rbpf'] != null ) {
		
		$rbpf = "&rbpf=" + $_GET['rbpf'];
		
	} else if ( getCookie('rbpf') != '' ) {
		
		$rbpf = "&rbpf=" + getCookie('rbpf');
		
	} else $rbpf = '';
	
	function getCookie(cname)
	{
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++)
		  {
		  var c = ca[i].trim();
		  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
		  }
		return "";
	} 
	/* End Prefix */
	
	RBooking.prototype = {
		addPromo: function (frm) {
			var self = this,
				code = frm.promo_code.value,
				url = [self.options.folder, "index.php?controller=pjFront" + $rbpf + "&action=addPromo&code=", code];
			JABB.Ajax.getJSON(url.join(""), function (data) {
				if (!data.code) {
					return;
				}
				switch (parseInt(data.code, 10)) {
					case 200:
						var pc = JABB.Utils.getElementsByClass("rbPromoCode", self.container, "span");
						if (pc[0]) {
							pc[0].innerHTML = code;
							pc[0].parentNode.style.display = "";
						}
						var pd = JABB.Utils.getElementsByClass("rbPromoDiscount", self.container, "span");
						if (pd[0]) {
							pd[0].innerHTML = data.discount_text;
							pd[0].parentNode.style.display = "";
						}
						frm.promo_code.value = "";
						break;
					case 100:
						var pi = JABB.Utils.getElementsByClass("rbPromoInvalid", self.container, "p");
						if (pi[0]) {
							pi[0].style.display = "";
							window.setTimeout(function () {
								pi[0].style.display = "none";
							}, 3000);
						}
						break;
				}
			});
			return self;
		},
		addTable: function (el, frm, people) {
			var self = this,
				table_id = el.getAttribute("data-table_id"),
				price_val = el.getAttribute("data-price"),
				box = document.getElementById("RBooking_OverlayBoxSeats_" + self.options.index),
				tbox = document.getElementById(["RBooking_RestaurantHolder_", self.options.index].join("")),
				span = document.createElement("span"),
				price = document.createElement("span"),
				holder = document.createElement("span");
			// 1. Add colorful span in image map
			JABB.Utils.addClass(holder, "RBooking_Table_Holder");
			price.innerHTML = el.innerHTML;//price_val;
			JABB.Utils.addClass(price, "RBooking_Table_Price");
			span.innerHTML = "Selected table:";//el.innerHTML;
			holder.setAttribute("rev", ["sbook-h", self.options.index, "_", table_id].join(""));
			// 1.1. Add "click" event, remove span
			//holder = self.bindTable.apply(self, [holder]);
			holder.style.cursor = "default";
			JABB.Utils.addClass(span, "RBooking_Table");
			holder.appendChild(span);
			holder.appendChild(price);
			if (box) {
				box.appendChild(holder);
				box.style.display = '';
			}
			// 2. Add hidden input in form
			var input = JABB.Utils.createElement("input");
			JABB.Utils.addClass(input, "sbook-hid");
			input.setAttribute("name", "table_id");
			input.setAttribute("value", table_id);
			input.setAttribute("type", "hidden");
			input.setAttribute("rel", el.innerHTML);
			input.setAttribute("rev", ["sbook-h", self.options.index, "_", table_id].join("")); 
			frm.appendChild(input);
			// 3. Add colorful span in form
			if (tbox) {
				var ho = document.createElement("span"),
					///te = document.createElement("span"),
					pr = document.createElement("span");
				
				///te.innerHTML = "change";
				///te.style.textDecoration = "underline";
				///te.style.marginLeft = "5px";
				
				ho.setAttribute("rev", ["sbook-h", self.options.index, "_", table_id].join(""));
				ho.setAttribute("data-width", self.options.map_width);
				ho.setAttribute("data-height", self.options.map_height);
				//JABB.Utils.addClass(pr, "RBooking_Table_Price");
				pr.innerHTML = el.innerHTML;//price_val;
				// add "click" event, open Image Map
				///ho.onclick = function () {
					///self.loadImageMap.apply(self, [people, frm]);
				///};
				JABB.Utils.addClass(ho, "RBooking_Table_Holder");
				//ho.appendChild(sp);
				ho.appendChild(pr);
				ho.appendChild(self.addChange.apply(self, [frm, people]));
				tbox.appendChild(ho);
				tbox.parentNode.style.display = "";
			}	
		},
		addChange: function (frm, people) {
			var self = this,
				te = document.createElement("span");		
			te.innerHTML = "change";
			te.style.textDecoration = "underline";
			te.style.marginLeft = "5px";
			te.style.cursor = "pointer";
			te.onclick = function () {
				self.loadImageMap.apply(self, [people, frm]);
			};			
			return te;
		},
		bindBookingForm: function () {
			var self = this,
				frm = JABB.Utils.getElementsByClass("rbForm", self.container, "form")[0],
				btnBack = document.getElementById("rbBtnBack"),
				btnContinue = document.getElementById("rbBtnContinue"),
				btnTerms = document.getElementById("rbBtnTerms"),
				btnAddVoucher = document.getElementById("rbBtnAddVoucher"),
				btnRemoveVoucher = document.getElementById("rbBtnRemoveVoucher");
			if (btnContinue) {
				btnContinue.onclick = function () {
					var that = this;
					that.disabled = true;
					if (!self.validateBookingForm(frm)) {
						that.disabled = false;
						return;
					}

					if (frm.captcha) {
						JABB.Ajax.getJSON([self.options.folder, "index.php?controller=pjFront" + $rbpf + "&action=checkCaptcha&captcha=", frm.captcha.value].join(""), function (json) {
							switch (json.code) {
								case 100:
									self.errorHandler("\n" + frm.captcha.getAttribute("data-err"));
									that.disabled = false;
									break;
								case 200:
									self.loadSummaryForm.apply(self, [JABB.Utils.serialize(frm)]);
									break;
							}
						});
					} else {
						self.loadSummaryForm.apply(self, [JABB.Utils.serialize(frm)]);
					}
				};
				btnContinue.onmouseover = function () {
					JABB.Utils.addClass(this, "rbBtnHover");
				};
				btnContinue.onmouseout = function () {
					JABB.Utils.removeClass(this, "rbBtnHover");
				};
			}
			if (btnTerms) {
				btnTerms.onclick = function (e) {
					self.overlayTerms.open();
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					return false;
				};
			}
			if (btnBack) {
				btnBack.onclick = function () {
					self.loadSearch.apply(self, []);
				};
				btnBack.onmouseover = function () {
					JABB.Utils.addClass(this, "rbBtnHover");
				};
				btnBack.onmouseout = function () {
					JABB.Utils.removeClass(this, "rbBtnHover");
				};
			}
			if (btnAddVoucher) {
				btnAddVoucher.onclick = function () {
					if (frm && frm.promo_code && frm.promo_code.value != "") {
						self.addPromo.apply(self, [frm]);
					}
				};
				btnAddVoucher.onmouseover = function () {
					JABB.Utils.addClass(this, "rbBtnHover");
				};
				btnAddVoucher.onmouseout = function () {
					JABB.Utils.removeClass(this, "rbBtnHover");
				};
			}
			if (btnRemoveVoucher) {
				btnRemoveVoucher.onclick = function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					self.removePromo.apply(self, [frm]);
					return false;
				};
			}
			if (frm) {
				var pm = frm.payment_method;
				if (pm) {
					pm.onchange = function (e) {
						var data = document.getElementById("rbCCData"),
							names = ["cc_type", "cc_num", "cc_exp_month", "cc_exp_year", "cc_code"],
							i, len = names.length;
						switch (this.options[this.selectedIndex].value) {
							case 'creditcard':
								data.style.display = "";
								for (i = 0; i < len; i++) {
									JABB.Utils.addClass(frm[names[i]], "rbRequired");
								}
								break;
							default:
								data.style.display = "none";
								for (i = 0; i < len; i++) {
									JABB.Utils.removeClass(frm[names[i]], "rbRequired");
								}
						}
					};
				}
			}
			return self;
		},
		bindFormTable: function (frm) {
			var self = this,
				table = JABB.Utils.getElementsByClass("RBooking_Table_Holder", frm, "span");
			if (table[0]) {
				table[0].onclick = function () {
					self.loadImageMap.apply(self, [this.getAttribute("data-people"), frm]);
				};
			}
		},
		bindImageMap: function (people) {
			var self = this,
				i, len,
				oBoxMiddle = document.getElementById("RBooking_OverlayBoxMiddle_" + self.options.index),
				available_arr = JABB.Utils.getElementsByClass('sbook-available', oBoxMiddle, 'span');
			//Add "click" event to hotspots
			for (i = 0, len = available_arr.length; i < len; i++) {
				JABB.Utils.addEvent(available_arr[i], "click", function (event) {
					var target = JABB.Utils.getEventTarget(event),
						frm = document.forms[self.options.booking_map_name],
						rel = target.getAttribute("data-table_id"),
						hid_arr = JABB.Utils.getElementsByClass("sbook-hid", frm, "input"),
						minimum = parseInt(target.getAttribute("data-minimum"), 10),
						capacity = parseInt(target.getAttribute("data-seats"), 10);

					if (hid_arr.length === 0) {
						if (minimum > people) {
							self.errorHandler('\n' + self.options.validation.error_map_1.replace(/\[num\]/, minimum), document.getElementById("RBooking_OverlayBoxTop_" + self.options.index));
						} else if (capacity < people) {
							self.errorHandler('\n' + self.options.validation.error_map_2.replace(/\[num\]/, capacity), document.getElementById("RBooking_OverlayBoxTop_" + self.options.index));							
						} else if (minimum <= people && capacity >= people) {
							self.addTable.apply(self, [target, frm, people]);
						}
					} else if (people === 0) {
						self.errorHandler('\n' + self.options.validation.error_map_4, document.getElementById("RBooking_OverlayBoxTop_" + self.options.index));
					} else {
						var j = JABB.Utils.getElementsByClass("RBooking_Table_Holder", document.getElementById("RBooking_OverlayBoxSeats_" + self.options.index), "span");
						self.removeTable.apply(self, [j[0], false]);
						self.addTable.apply(self, [target, frm, people]);
						//self.errorHandler('\n' + self.options.validation.error_map_5, document.getElementById("RBooking_OverlayBoxTop_" + self.options.index));
					}
					
					self.resizeImageMap();
				});
			}
			// Add "click" event to image map
			JABB.Utils.addEvent(document.getElementById(self.options.booking_map_image), "click", function (e) {
				self.errorHandler('\n' + self.options.validation.error_map_6, document.getElementById("RBooking_OverlayBoxTop_" + self.options.index));
			});
		},
		bindSearch: function () {
			var frm,
				self = this,
				btnContinue = document.getElementById("rbBtnContinue"),
				datepicker = new Calendar({
					element: "rb_date_" + self.options.index,
					dateFormat: self.options.date_format,
					monthNamesFull: self.options.month_names_full,
					dayNames: self.options.day_names,
					disablePast: true,
					onBeforeShowDay: daysOff,
					onSelect: function (element, selectedDate, date, cell) {
						self.getWTime.apply(self, [selectedDate, function (data) {
							document.getElementById("rbTimeBox").innerHTML = data;
						}]);
						self.initSelection.apply(self, [element.form]);
					}
				}),
				lnk = document.getElementById("rbDate_" + self.options.index),
				ppl = document.getElementById("rb_people_" + self.options.index);

			function daysOff(date) {
				var isDateOff = datesOff(date),
					isDateOn = datesOn(date);
				if (isDateOff[0] && !isDateOn[0]) {
					for (var i = 0, len = self.options.days_off.length; i < len; i++) {
						if (self.options.days_off[i] === date.getDay()) {
							return [false, 'bcal-past'];
						}
					}
					return [true, ''];
				} else {
					return isDateOff[0] ? isDateOff: isDateOn;
				}
			}
			function datesOff(date) {
				var d, i, len;
				for (i = 0, len = self.options.dates_off.length; i < len; i++) {
					d = new Date(self.options.dates_off[i]);
					if (new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate()).getTime() === date.getTime()) {
						return [false, 'bcal-past'];
					}
				}
				return [true, ''];
			}
			function datesOn(date) {
				var d, i, len;
				for (i = 0, len = self.options.dates_on.length; i < len; i++) {
					d = new Date(self.options.dates_on[i]);
					if (new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate()).getTime() === date.getTime()) {
						return [true, ''];
					}
				}
				return [false, 'bcal-past'];
			}
			
			if (datepicker.element) {
				self.el = datepicker.element;
			}
			self.elH = document.getElementById("rb_hour_" + self.options.index);
			self.elM = document.getElementById("rb_minutes_" + self.options.index);
			if (self.elH) {
				self.elH.onchange = function () {
					self.initSelection.apply(self, [this.form]);
				};
			}
			if (self.elM) {
				self.elM.onchange = function () {
					self.initSelection.apply(self, [this.form]);
				};
			}
			if (self.el) {
				frm = self.el.form;
			}
			
			if (btnContinue) {
				btnContinue.onclick = function () {
					var that = this;
					that.disabled = true;
					if (!self.validateSearch(frm)) {
						that.disabled = false;
						return;
					}
					var qs = JABB.Utils.serialize(frm);
					self.checkWTime(qs, function (json) {
						switch (json.code) {
							case 200:
								self.loadBookingForm.apply(self, [qs]);
								break;
							case 100:
								self.errorHandler('\n' + self.options.validation.error_search_3);
								that.disabled = false;
								break;
							case 101:
								self.errorHandler('\n' + self.options.validation.error_search_4.replace(/\[x\]/, json.booking_earlier));
								that.disabled = false;
								break;
							case 102:
								self.errorHandler('\n' + self.options.validation.error_search_5);
								that.disabled = false;
								break;
							case 103:
								self.errorHandler('\n' + self.options.validation.error_search_6);
								that.disabled = false;
								break;
							default:
								that.disabled = false;
								break;
						}
					});
				};
				btnContinue.onmouseover = function () {
					JABB.Utils.addClass(this, "rbBtnHover");
				};
				btnContinue.onmouseout = function () {
					JABB.Utils.removeClass(this, "rbBtnHover");
				};
			}
		
			if (lnk) {
				lnk.onclick = function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					datepicker.isOpen ? datepicker.close() : datepicker.open();
					return false;
				};
			}
			
			if (ppl && self.options.use_map === 1) {
				ppl.onchange = function () {
					var that = this;
					self.initSelection.apply(self, [that.form]);
					self.checkPeople.apply(self, [that.options[that.selectedIndex].value, that.form, true]);
				};
			}
			
			return self;
		},
		initSelection: function (frm) {
			var i, len,
				self = this,
				node = document.getElementById("RBooking_RestaurantHolder_" + self.options.index),
				inputs = JABB.Utils.getElementsByClass("sbook-hid", frm, "input");
			if (node) {
				node.parentNode.style.display = "none";
				node.innerHTML = "";
			}
			for (i = 0, len = inputs.length; i < len; i++) {
				inputs[i].parentNode.removeChild(inputs[i]);
			}
		},
		bindSummaryForm: function () {
			var self = this,
				frm = JABB.Utils.getElementsByClass("rbForm", self.container, "form")[0],
				btnBack = document.getElementById("rbBtnBack"),
				btnContinue = document.getElementById("rbBtnContinue");

			if (btnContinue) {
				btnContinue.onclick = function () {
					var that = this;
					that.disabled = true;
					
					if (!self.validateSummaryForm(frm)) {
						that.disabled = false;
						return;
					}
					
					var url = [self.options.folder, "index.php?controller=pjFront" + $rbpf + "&action=bookingSave"];
					JABB.Ajax.postJSON(url.join(""), function (data) {
						if (!data.code) {
							return;
						}
						var box = document.getElementById("rbBoxMiddle_" + self.options.index);
						switch (parseInt(data.code, 10)) {
							case 100:
								self.errorHandler(self.options.message_4);
								that.disabled = false;
								break;
							case 200:
								switch (data.payment) {
									case 'paypal':
										self.triggerLoading('message_1', box);
										self.loadPaymentForm(data);
										break;
									case 'authorize':
										self.triggerLoading('message_2', box);
										self.loadPaymentForm(data);
										break;
									case 'creditcard':
									case 'cash':
										self.triggerLoading('message_3', box);
										break;
									default:
										self.triggerLoading('message_3', box);
								}
								break;
							case 201:
								self.triggerLoading('message_5', box);
								break;
						}
					}, JABB.Utils.serialize(frm));
				};
				btnContinue.onmouseover = function () {
					JABB.Utils.addClass(this, "rbBtnHover");
				};
				btnContinue.onmouseout = function () {
					JABB.Utils.removeClass(this, "rbBtnHover");
				};
			}
			if (btnBack) {
				btnBack.onclick = function () {
					self.loadBookingForm.apply(self, []);
				};
				btnBack.onmouseover = function () {
					JABB.Utils.addClass(this, "rbBtnHover");
				};
				btnBack.onmouseout = function () {
					JABB.Utils.removeClass(this, "rbBtnHover");
				};
			}
			return self;
		},
		bindTable: function (holder) {
			var self = this;
			holder.onclick = function () {
				self.removeTable.apply(self, [this, true]);
			};
			return holder;
		},
		checkPeople: function (people, frm, showMap) {
			var self = this;
			JABB.Ajax.postJSON(self.options.folder + "index.php?controller=pjFront&action=checkPeople" + $rbpf, function (data) {
				if (!data.code) {
					return;
				}
				if (data.code == 200) {
					self.bookingType = "booking";
					if (showMap) {
						self.loadImageMap.apply(self, [people, frm]);
					}
				} else {
					self.bookingType = "enquiry";
				}
			}, JABB.Utils.serialize(frm));
		},
		checkWTime: function (qs, callback) {
			JABB.Ajax.getJSON(this.options.folder + "index.php?controller=pjFront&action=checkWTime&" + qs + $rbpf, function (data) {
				callback(data);
			});
		},
		createFinish: function () {
			var self = this,
				finish = JABB.Utils.createElement("input");
			finish.type = "button";
			finish.value = "";
			finish.className = "rbBtn rbBtnFinish RBooking_Button";
			// Add "click" event to Finish button
			JABB.Utils.addEvent(finish, "click", function () {
				self.overlayHide();
				if (self.validateBookingMap()) {
					var err = JABB.Utils.getElementsByClass("rbError", document.forms[self.options.booking_form_name], "P");
					if (err[0]) {
						err[0].innerHTML = "";
						err[0].style.display = "none";
					}
				}
			});
			// Add "mouseover" event to Finish button
			JABB.Utils.addEvent(finish, "mouseover", function (event) {
				if (!JABB.Utils.hasClass(this, "RBooking_Button_Disabled")) {
					JABB.Utils.addClass(this, "rbBtnHover");
				}
			});
			// Add "mouseout" event to Cancel button
			JABB.Utils.addEvent(finish, "mouseout", function (event) {
				JABB.Utils.removeClass(this, "rbBtnHover");
			});
			return finish;
		},
		errorHandler: function (message, node) {
			var err, st, frm, people, ppl,
				self = this;
			if (node == null) {
				node = document.forms[this.options.booking_form_name];
			}
			err = JABB.Utils.getElementsByClass("rbError", node, "p")[0];
			if (err) {
				err.innerHTML = ['<span></span>', this.options.validation.error_title, '<br />', message.replace(/\n/g, "<br />")].join("");
				st = document.getElementById("rbSelectTable_" + self.options.index);
				if (st) {
					frm = document.forms[self.options.booking_map_name];
					if (frm) {
						ppl = document.getElementById("rb_people_" + self.options.index);
						if (ppl) {
							people = ppl.options[ppl.selectedIndex].value;
						}
					}					
					st.onclick = function (e) {
						if (e && e.preventDefault) {
							e.preventDefault();
						}
						self.loadImageMap.apply(self, [people, frm]);
						return false;
					};
				}
				err.style.display = '';
			} else {
				alert(this.options.validation.error_title + message);
			}
		},
		getWTime: function (date, callback) {
			JABB.Ajax.sendRequest(this.options.folder + "index.php?controller=pjFront&action=getWTime&date=" + encodeURIComponent(date) + $rbpf, function (req) {
				callback(req.responseText);
			});
		},
		init: function (opts) {
			var self = this;
			self.options = opts;
			self.container = document.getElementById(self.options.container_id);
			self.loadSearch();
			self.overlayInit();
			
			var btns = {};
			btns[self.options.close_button] = function (button) {
				this.close();
			};
			
			self.overlayTerms = new OverlayJS({
				selector: "rbDialogTerms",
				modal: true,
				width: 640,
				height: 480,
				onBeforeOpen: function () {
					var that = this;
					JABB.Ajax.sendRequest(self.options.folder + "index.php?controller=pjFront" + $rbpf + "&action=getTerms" + $rbpf, function (req) {
						that.content.innerHTML = req.responseText;
					});
				},
				buttons: btns
			});
			
			return self;
		},
		loadBookingForm: function (post) {
			var self = this,
				url = [self.options.folder, "index.php?controller=pjFront&action=loadBookingForm"];
	
			JABB.Ajax.sendRequest(url.join(""), function (req) {
				self.container.innerHTML = req.responseText;
				self.bindBookingForm();
			}, post);
			return self;
		},
		loadImageMap: function (people, frm) {
			var date, hour, minutes, qs,
				self = this;
			if (people) {
				people = parseInt(people, 10);
				if (frm) {
					date = encodeURIComponent(frm.date.value);
					hour = frm.hour.options[frm.hour.selectedIndex].value;
					minutes = frm.minutes.options[frm.minutes.selectedIndex].value;
				}
				qs = ["&index=", self.options.index, "&date=", date, "&hour=", hour, "&minutes=", minutes, "&people=", people].join("");
			}
			JABB.Ajax.sendRequest(self.options.folder + "index.php?controller=pjFront&action=getMap" + qs + $rbpf, function (req) {
				var oBoxMiddle = document.getElementById("RBooking_OverlayBoxMiddle_" + self.options.index),
					oBoxSeats = document.getElementById("RBooking_OverlayBoxSeats_" + self.options.index),
					oBoxHead = document.getElementById("RBooking_OverlayBoxHead_" + self.options.index),
					oBoxBottom = document.getElementById("RBooking_OverlayBoxBottom_" + self.options.index),
					tbox = document.getElementById(["RBooking_RestaurantHolder_", self.options.index].join(""));
				if (oBoxSeats) {
					oBoxSeats.innerHTML = "";
					if (tbox) {
						var tsh = JABB.Utils.getElementsByClass("RBooking_Table_Holder", tbox, "span"),
							i, len;
						for (i = 0, len = tsh.length; i < len; i++) {
							var sp0 = document.createElement("span"),
								sp1 = document.createElement("span"),
								sp2 = document.createElement("span");
							
							JABB.Utils.addClass(sp0, "RBooking_Table_Holder");
							JABB.Utils.addClass(sp1, "RBooking_Table");
							JABB.Utils.addClass(sp2, "RBooking_Table_Price");
							sp1.innerHTML = "Selected table:";
							sp2.innerHTML = tsh[i].firstChild.innerHTML;
							sp0.setAttribute("rev", tsh[i].getAttribute("rev"));
							
							sp0.appendChild(sp1);
							sp0.appendChild(sp2);
							//sp0 = self.bindTable.apply(self, [sp0]);
							sp0.style.cursor = "default";
							oBoxSeats.appendChild(sp0);
							break;
						}
						if (tsh.length > 0) {
							oBoxSeats.style.display = "";
						}
					}
				}
				if (oBoxHead) {
					oBoxHead.innerHTML = "";
					oBoxHead.appendChild(self.createFinish());
				}
				
				if (oBoxBottom) {
					oBoxBottom.innerHTML = "";
					oBoxBottom.appendChild(self.createFinish());
				}
				
				if (oBoxMiddle) {
					oBoxMiddle.innerHTML = req.responseText;
					self.bindImageMap.apply(self, [people]);
				}
				self.overlayShow({
					width: self.options.map_width, 
					height: self.options.map_height
				});
			});
		},
		loadPaymentForm: function (obj) {
			var self = this,
				div;
			JABB.Ajax.sendRequest(self.options.folder + "index.php?controller=pjFront&action=loadPayment" + $rbpf, function (req) {
				div = document.createElement("div");
				div.innerHTML = req.responseText;
				self.container.appendChild(div);
				var frm = document.forms[obj.payment == 'paypal' ? 'rbPaypal' : 'rbAuthorize'];
				if (typeof frm != 'undefined') {
					frm.submit();						
				}
			}, "id=" + obj.booking_id);
		},
		loadSearch: function () {
			var self = this,
				url = [self.options.folder, "index.php?controller=pjFront" + $rbpf + "&action=loadSearch&index=", self.options.index];
	
			JABB.Ajax.sendRequest(url.join(""), function (req) {
				self.container.innerHTML = req.responseText;
				self.bindSearch();
				var frm = document.forms[self.options.booking_map_name];
				self.bindFormTable.apply(self, [frm]);
				
				var pField = document.getElementById("rb_people_" + self.options.index),
					pValue;
				if (pField) {
					pValue = pField.options[pField.selectedIndex].value;
				}
				if (frm && pField && parseInt(pValue, 10) > 0) {
					self.checkPeople.apply(self, [pValue, frm]);
				}
				
			});
			return self;
		},
		loadSummaryForm: function (post) {
			var self = this,
				url = [self.options.folder, "index.php?controller=pjFront" + $rbpf + "&action=loadSummaryForm&index=", self.options.index];
	
			JABB.Ajax.sendRequest(url.join(""), function (req) {
				self.container.innerHTML = req.responseText;
				self.bindSummaryForm();
			}, post);
			return self;
		},
		overlayHide: function () {
			var self = this,
				ele1 = document.getElementById("RBooking_Overlay_" + self.options.index),
				ele2 = document.getElementById("RBooking_OverlayBox_" + self.options.index);
			if (ele1) {
				ele1.style.display = "none";
			}
			if (ele2) {
				ele2.style.display = "none";
			}
		},
		overlayInit: function () {
			var self = this,
				o = JABB.Utils.createElement("div"),
				oBox = JABB.Utils.createElement("div"),
				oBoxTop = JABB.Utils.createElement("div"),
				oBoxHead = JABB.Utils.createElement("div"),
				oBoxSeats = JABB.Utils.createElement("div"),
				oBoxMiddle = JABB.Utils.createElement("div"),
				oBoxLegend = JABB.Utils.createElement("div"),
				oBoxBottom = JABB.Utils.createElement("div");
							
			o.id = "RBooking_Overlay_" + self.options.index;
			oBox.id = "RBooking_OverlayBox_" + self.options.index;
			oBoxTop.id = "RBooking_OverlayBoxTop_" + self.options.index;
			oBoxHead.id = "RBooking_OverlayBoxHead_" + self.options.index;
			oBoxSeats.id = "RBooking_OverlayBoxSeats_" + self.options.index;
			oBoxMiddle.id = "RBooking_OverlayBoxMiddle_" + self.options.index;
			oBoxLegend.id = "RBooking_OverlayBoxLegend_" + self.options.index;
			oBoxBottom.id = "RBooking_OverlayBoxBottom_" + self.options.index;
			JABB.Utils.addClass(o, "RBooking_Overlay");	
			JABB.Utils.addClass(oBox, "RBooking_OverlayBox");
			JABB.Utils.addClass(oBoxTop, "RBooking_OverlayBoxTop");
			JABB.Utils.addClass(oBoxHead, "RBooking_OverlayBoxHead");
			JABB.Utils.addClass(oBoxSeats, "RBooking_OverlayBoxSeats");
			JABB.Utils.addClass(oBoxMiddle, "RBooking_OverlayBoxMiddle");
			JABB.Utils.addClass(oBoxLegend, "RBooking_OverlayBoxLegend");
			JABB.Utils.addClass(oBoxBottom, "RBooking_OverlayBoxBottom");
			oBoxSeats.innerHTML = '<span style="float: left">Selected seats: </span>';
			oBoxSeats.style.display = "none";
			oBox.appendChild(oBoxTop);
			oBox.appendChild(oBoxHead);
			oBox.appendChild(oBoxSeats);
			oBox.appendChild(oBoxMiddle);
			oBox.appendChild(oBoxLegend);
			oBox.appendChild(oBoxBottom);
			
			/* Fix for IE */
			function initOver() {
				if (arguments.callee.done) {
					return;
				}
				arguments.callee.done = true;
				// do you stuff
				var body = document.getElementsByTagName("body")[0];
				body.appendChild(o);
				body.appendChild(oBox);
			}

			if (document.addEventListener) {
				document.addEventListener('DOMContentLoaded', initOver, false);
			}

			(function() {
				/*@cc_on
				try {
					document.body.doScroll('up');
					return initOver();
				} catch(e) {}
				/*@if (false) @*/
				if (/loaded|complete/.test(document.readyState)) {
					return initOver();
				}
				/*@end @*/
				if (!initOver.done) {
					setTimeout(arguments.callee, 30);
				}
			})();

			if (window.addEventListener) {
				window.addEventListener('load', initOver, false);
			} else if (window.attachEvent) {
				window.attachEvent('onload', initOver);
			}
			/* End fix */
		},
		overlayShow: function (opt) {
			var self = this,
				ele1 = document.getElementById("RBooking_Overlay_" + self.options.index),
				ele2 = document.getElementById("RBooking_OverlayBox_" + self.options.index),
				ele3 = document.getElementById("RBooking_OverlayBoxMiddle_" + self.options.index),
				ele4 = document.getElementById("RBooking_OverlayBoxTop_" + self.options.index),
				ele5 = document.getElementById("RBooking_OverlayBoxSeats_" + self.options.index),
				vp = JABB.Utils.getViewport(),
				hid = JABB.Utils.getElementsByClass("sbook-hid", document.forms[self.options.booking_map_name], "input");
				
			/*if (hid.length === 0) {
				var tb = JABB.Utils.getElementsByClass("RBooking_Seat_Holder", ele5, "span");
				for (var i = 0, len = tb.length; i < len; i++) {
					ele5.removeChild(tb[i]);
				}
			}*/
					
			ele2.style.width = opt.width + "px";
			//ele2.style.height = (parseInt(opt.height, 10) + 175) + "px";
			ele2.setAttribute("data-height", opt.height);
			ele2.style.left = Math.ceil((vp.width - opt.width) / 2) + "px";
			ele2.style.top = Math.ceil((vp.height - opt.height) / 2) + "px";
			
			ele3.style.width = opt.width + "px";
			ele3.style.height = opt.height + "px";
			
			ele4.innerHTML = '<p class="rbError RBooking_Error"><span></span>Select table</p>';
			
			ele1.style.display = "block";
			ele2.style.display = "block";
			
			self.resizeImageMap();
		},
		removePromo: function (frm) {
			var self = this,
				code = frm.promo_code.value,
				url = [self.options.folder, "index.php?controller=pjFront" + $rbpf + "&action=removePromo"];
			JABB.Ajax.getJSON(url.join(""), function (data) {
				if (!data.code) {
					return;
				}
				switch (parseInt(data.code, 10)) {
					case 200:
						var tp = JABB.Utils.getElementsByClass("rbTotalPrice", self.container, "span"),
							yp = JABB.Utils.getElementsByClass("rbYourPrice", self.container, "span"),
							pc = JABB.Utils.getElementsByClass("rbPromoCode", self.container, "span");
						if (tp[0]) {
							JABB.Utils.removeClass(tp[0], "rbStrike");
							//tp[0].innerHTML = data.price_formated;
						}
						if (yp[0]) {
							yp[0].parentNode.style.display = "none";
							yp[0].innerHTML = "";
						}
						if (pc[0]) {
							pc[0].parentNode.style.display = "none";
							pc[0].innerHTML = "";
						}
						break;
				}
			});
			return self;
		},
		removeTable: function (holder, flag) {
			var self = this,
				frm = document.forms[self.options.booking_map_name],
				sbox = document.getElementById(["RBooking_RestaurantHolder_", self.options.index].join("")),
				tb = JABB.Utils.getElementsByClass("RBooking_Table_Holder", sbox, "span"),
				ppl = document.getElementById("rb_people_" + self.options.index),
				people = ppl.options[ppl.selectedIndex].value,
				rev = holder.getAttribute("rev"),
				i, len;
			for (i = 0, len = frm.elements.length; i < len; i++) {
				if (JABB.Utils.hasClass(frm.elements[i], "sbook-hid") && frm.elements[i].getAttribute("rev") == rev) {
					frm.removeChild(frm.elements[i]);						
					break;
				}
			}
			for (i = 0, len = tb.length; i < len; i++) {
				if (tb[i].getAttribute("rev") == rev) {
					var pNode = tb[i].parentNode;
					pNode.removeChild(tb[i]);
					pNode.innerHTML = "";
					if (flag) {
						pNode.appendChild(self.addChange.apply(self, [frm, people]));
					}
					break;
				}
			}
			holder.parentNode.removeChild(holder);
			self.resizeImageMap();
		},
		resizeImageMap: function () {
			var self = this,
				oBox = document.getElementById("RBooking_OverlayBox_" + self.options.index),
				oBoxSeats = document.getElementById("RBooking_OverlayBoxSeats_" + self.options.index);
			if (oBox && oBoxSeats) {
				oBox.style.height = (parseInt(oBox.getAttribute("data-height"), 10) + 175 + oBoxSeats.offsetHeight) + "px";
			}
		},
		triggerLoading: function (message, container) {
			if (container && container.nodeType) {
				container.innerHTML = this.options[message];
			} else if (typeof container != "undefined") {
				var c = document.getElementById(container);
				if (c && c.nodeType) {
					c.innerHTML = this.options[message];
				}
			}
		},
		validateBookingForm: function (frm) {
			var i, len, cls,
				re = /([0-9a-zA-Z\.\-\_]+)@([0-9a-zA-Z\.\-\_]+)\.([0-9a-zA-Z\.\-\_]+)/,
				message = "";

			for (i = 0, len = frm.elements.length; i < len; i++) {
				cls = frm.elements[i].className;
				if (cls.indexOf("rbRequired") !== -1 && frm.elements[i].disabled === false) {
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
				if (cls.indexOf("rbEmail") !== -1) {
					if (frm.elements[i].nodeName === "INPUT" && frm.elements[i].value.length > 0 && frm.elements[i].value.match(re) == null) {
						message += "\n - " + this.options.validation.error_email;
					}
				}
			}
			if (message.length === 0) {
				return true;
			} else {
				this.errorHandler(message);		
				return false;
			}
		},
		validateBookingMap: function (btn) {
			var message = "";
			//TODO
			if (message.length === 0) {
				return true;
			} else {
				this.errorHandler(message);
				if (typeof btn != 'undefined' && btn !== null) {
					btn.disabled = false;
					JABB.Utils.removeClass(btn, "RBooking_Button_Disabled");
				}
				return false;
			}
		},
		validateSearch: function (frm) {
			var message = "",
				fPeople = frm.people,
				fDate = frm.date,
				fTables = JABB.Utils.getElementsByClass("sbook-hid", frm, "input");
			
			if (!fDate || fDate.value == "") {
				message += "\n " + this.options.validation.error_search_1;
			}
				
			if (!fPeople || fPeople.options[fPeople.selectedIndex].value == "") {
				message += "\n " + this.options.validation.error_search_2;
			}
			
			if (this.options.use_map === 1 && this.bookingType == "booking" && fTables.length === 0) {
				message += "\n " + this.options.validation.error_search_7 + '. <a href="#" id="rbSelectTable_' + this.options.index + '">Select table</a>';
			}
			
			if (message.length === 0) {
				return true;
			} else {
				this.errorHandler(message, frm);		
				return false;
			}
		},
		validateSummaryForm: function (frm) {
			var message = "";
			// Add validation rules
			if (message.length === 0) {
				return true;
			} else {
				this.errorHandler(message);		
				return false;
			}
		}
	};
	// expose
	return (window.RBooking = RBooking);
})(window);

function showtime(myRadio) {
	
	var service = document.getElementsByClassName("service"),
		serviceActive = document.getElementsByClassName(myRadio.id);
	
	for(var i = 0; i < service.length; i++) {
		service[i].style.display = "none";
	}
	
	for(var i = 0; i < serviceActive.length; i++) {
		serviceActive[i].style.display = "block";
	}
	
}

function settime(time) {
	
	var select = document.getElementsByClassName("rbSelect"),
	timeActive = document.getElementsByClassName("timeActive");
	
	for(var i = 0; i < timeActive.length; i++) {
		timeActive[i].className = '';
	}
	
	time.className += 'timeActive';
	
	for(var i = 0; i < select.length; i++) {
		
		if (select[i].name == "hour") {
			select[i].value = time.getAttribute('data-h');
		} else if ( select[i].name == "minutes" ) {
			select[i].value = time.getAttribute('data-i');
		}
	}
	
}

