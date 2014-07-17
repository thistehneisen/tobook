/*!
 * Appointment Scheduler v2.0
 * http://phpjabbers.com/appointment-scheduler/
 * 
 * Copyright 2013, StivaSoft Ltd.
 * 
 * Date: Wed Sep 18 12:48:26 2013 +0300
 */
(function (window, undefined){
	"use strict";
	var document = window.document,
		validate = (pjQ.$.fn.validate !== undefined),
		datepicker = (pjQ.$.fn.datepicker !== undefined),
		dialog = (pjQ.$.fn.dialog !== undefined),
		spinner = (pjQ.$.fn.spinner !== undefined),
		routes = [
		          {pattern: /^#!\/Checkout$/, eventName: "loadCheckout"},
		          {pattern: /^#!\/Service\/date:([\d\-\.\/]+)\/id:(\d+)$/, eventName: "loadService"},
		          {pattern: /^#!\/((?:19|20)\d\d)\/(0[1-9]|1[012])\/(0[1-9]|[12][0-9]|3[01])\/.*-(\d+)\.html$/, eventName: "loadService"},
		          {pattern: /^#!\/Services$/, eventName: "loadServices"},
		          {pattern: /^#!\/Services\/date:([\d\-\.\/]+)?\/page:(\d+)?$/, eventName: "loadServices"},
		          {pattern: /^#!\/((?:19|20)\d\d)\/(0[1-9]|1[012])\/(0[1-9]|[12][0-9]|3[01])\/(\d+)?$/, eventName: "loadServices"},
		          {pattern: /^#!\/Preview$/, eventName: "loadPreview"},
		          {pattern: /^#!\/Booking\/([A-Z]{2}\d{10})$/, eventName: "loadBooking"},
		          {pattern: /^#!\/Cart$/, eventName: "loadCart"}
		];
	
	/* $_GET Prefix */
	var $_GET = {}, $as_pf;

	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
	    function decode(s) {
	        return decodeURIComponent(s.split("+").join(" "));
	    }

	    $_GET[decode(arguments[1])] = decode(arguments[2]);
	});
	
	if ( $_GET['as_pf'] != null ) {
		
		$as_pf = "&as_pf=" + $_GET['as_pf'];
		
	} else if ( getCookie('as_pf') != '' ) {
		
		$as_pf = "&as_pf=" + getCookie('as_pf');
		
	} else $as_pf = '';
	
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
	
	function log() {
		if (window.console && window.console.log) {
			for (var x in arguments) {
				if (arguments.hasOwnProperty(x)) {
					window.console.log(arguments[x]);
				}
			}
		}
	}
	
	function assert() {
		if (window && window.console && window.console.assert) {
			window.console.assert.apply(window.console, arguments);
		}
	}
	
	function hashBang(value) {
		
		if (value !== undefined && value.match(/^#!\//) !== null) {
			
			if (window.location.hash == value) {
				return false;
			}
			window.location.hash = value;
			return true;
		}
		
		return false;
	}
	
	function onHashChange() {
		var i, iCnt, m;
		
		for (i = 0, iCnt = routes.length; i < iCnt; i++) {
		
			m = unescape(window.location.hash).match(routes[i].pattern);
			
			if (m !== null) {
				pjQ.$(window).trigger(routes[i].eventName, m.slice(1));
				break;
			}
		}
		if (m === null) {
			pjQ.$(window).trigger("loadServices");
		}
	}
	
	pjQ.$(window).on("hashchange", function (e) {
    	onHashChange.call(null);
    });
	
	function AppScheduler(options) {
		if (!(this instanceof AppScheduler)) {
			return new AppScheduler(options);
		}
				
		this.reset.call(this);
		this.date = options.firstDate;
		this.init.call(this, options);
		
		return this;
	}
	
	AppScheduler.prototype = {
		reset: function () {
			this.$container = null;
			this.container = null;
			this.view = null;
			this.page = 1;
			this.date = null;
			this.start_ts = null;
			this.end_ts = null;
			this.service_id = null;
			this.booking_uuid = null;
			this.options = {};
			this.wt_id = -1;
			
			return this;
		},
		disableButtons: function () {
			this.$container.find(".asSelectorButton").attr("disabled", "disabled");
		},
		enableButtons: function () {
			this.$container.find(".asSelectorButton").removeAttr("disabled");
		},
		_addToCart: function (arr) {
			var xhr = pjQ.$.post([this.options.folder, "index.php?controller=pjFrontEnd"+ $as_pf +"&action=pjActionAddToCart&cid=", this.options.cid].join(""), pjQ.$.param(arr));
			
			return xhr;
		},
		_removeFromCart: function (opts) {
			var xhr = pjQ.$.post([this.options.folder, "index.php?controller=pjFrontEnd"+ $as_pf +"&action=pjActionRemoveFromCart&cid=", this.options.cid].join(""), {
				"date": opts.date,
				"service_id": opts.service_id,
				"start_ts": opts.start_ts,
				"end_ts": opts.end_ts,
				"employee_id": opts.employee_id,
				"wt_id": opts.wt_id
			});
			
			return xhr;
		},
		addToCart: function (arr) {
			var that = this;
			this.disableButtons.call(this);
			this._addToCart.call(this, arr).done(function (data) {
				//that.getCart.call(that);
				//that.enableButtons.call(that);
				
				var result = pjQ.$.grep(arr, function (item) {
					return item.name == 'employee_id';
				});
				
				//that.loadService.call(that, result[0].value);
				hashBang("#!/Checkout");
				
				return false;
				
			}).fail(function () {
				that.enableButtons.call(that);
			});
			
			return this;
		},
		addToCartEmployee: function (arr) {
			var that = this;
			this.disableButtons.call(this);
			this._addToCart.call(this, arr).done(function (data) {
				//that.getCart.call(that);
				//that.enableButtons.call(that);
				
				var result = pjQ.$.grep(arr, function (item) {
					return item.name == 'employee_id';
				});
				
				that.loadServices.call(that);
			}).fail(function () {
				that.enableButtons.call(that);
			});
			
			return this;
		},
		removeFromCart: function (opts) {
			var that = this;
			this.disableButtons.call(this);
			this._removeFromCart.call(this, opts).done(function (data) {
				//that.getCart.call(that);
				//that.enableButtons.call(that);
				switch (that.view) {
				case 'pjActionService':
					pjQ.$(window).trigger("loadService");
					break;
				case 'pjActionServices':
					pjQ.$(window).trigger("loadServices");
					break;
				case 'pjActionCheckout':
					pjQ.$(window).trigger("loadCheckout");
					break;
				case 'pjActionPreview':
					pjQ.$(window).trigger("loadPreview");
					break;
				case 'pjActionCart':
					pjQ.$(window).trigger("loadCart");
					break;
				}
			}).fail(function () {
				that.enableButtons.call(that);
			});
			
			return this;
		},
		getCart: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetCart" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout
			}).done(function (data) {
				that.$container.find(".asSelectorCartWrap").html(data);
			});
		},
		getCalendar: function (year, month) {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetCalendar" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout,
				"year": year,
				"month": month
			}).done(function (data) {
				that.$container.find(".asSelectorCalendar").html(data);
			});
		},
		getEmployees: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetEmployees" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout,
				"date": this.date,
				"service_id": this.service_id,
				"start_ts": this.start_ts,
				"end_ts": this.end_ts
			}).done(function (data) {
				that.$container.find(".asSelectorSingleEmployee").html(data).show();
			});
		},
		getTime: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetTime" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout,
				"service_id": this.service_id,
				"date": this.date
			}).done(function (data) {
				that.$container.find(".asSelectorSingleTimeBox").html(data)
					.end()
					.find(".asSelectorSingleTime").trigger("change");
			});
		},
		init: function (opts) {
			var that = this;
			this.options = opts;
			
			this.container = document.getElementById("asContainer_" + this.options.cid);
			this.$container = pjQ.$(this.container);
			
			this.$container.on("click.as", ".asSelectorService", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this),
					service_id = $this.data("id"),
					extra_count = $this.data("extra_count"),
					iso = $this.data("iso"),
					slug = $this.data("slug"),
					$dialogExtraService = pjQ.$("#dialogExtraService"),
					dialog = (pjQ.$.fn.dialog !== undefined);
				
				if ( extra_count > 0 ) {
					if ( $dialogExtraService.lenght > 0 ) {
						
					}
					if ($dialogExtraService.length > 0 && dialog) {
						$dialogExtraService.html("");
						
						pjQ.$.get("index.php?controller=pjFrontPublic&action=getExtraService" + $as_pf, {
							"service_id": service_id,
						}).done(function (data) {
							$dialogExtraService.html(data);
						});
						
						$dialogExtraService.dialog();
						$dialogExtraService.data("data", $this.data()).dialog("open");
					}
					
					if ($dialogExtraService.length > 0 && dialog) {
						$dialogExtraService.dialog({
							modal: true,
							resizable: false,
							draggable: false,
							autoOpen: false,
							width: 250,
							buttons: {
								"Jatka": function () {
									var $dialog = pjQ.$(this),
										$frmExtraService = $dialog.find('form');
									
									pjQ.$.post("index.php?controller=pjFrontPublic&action=getExtraService" + $as_pf, $frmExtraService.serialize()).done(function (data) {
										
										that.wt_id = $this.data("wt");
										
										if (that.options.seoUrl === 1 && slug.length > 0) {
											hashBang("#!/" + slug);
										} else {
											hashBang("#!/Service/date:" + encodeURIComponent(iso) + "/id:" + encodeURIComponent(service_id));
										}
									});
									
									$dialog.dialog("close");
									
									return false;
								},
								"Peruuta": function () {
									var $dialog = pjQ.$(this);
									$dialog.dialog("close");
								}
							}
						});
					}
					
				} else {
					that.wt_id = $this.data("wt");
					
					if (that.options.seoUrl === 1 && slug.length > 0) {
						hashBang("#!/" + slug);
					} else {
						hashBang("#!/Service/date:" + encodeURIComponent(iso) + "/id:" + encodeURIComponent(service_id));
					}
				}
				
				return false;
				
			}).on("click.as", ".asSelectorServices", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (that.options.seoUrl === 1) {
					var d = that.date.split("-");
					hashBang(["#!", d[0], d[1], d[2], that.page].join("/"));
				} else {
					hashBang(["#!/Services/date:", (that.date && that.date !== undefined ? encodeURIComponent(that.date) : ""),
					          "/page:", (that.page && that.page !== undefined ? encodeURIComponent(that.page) : 1)].join(""));	
				}
				return false;
			}).on("click.as", ".asSelectorServicesPage", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var page = pjQ.$(this).data("page");
				hashBang(["#!/Services/date:", (that.date && that.date !== undefined ? encodeURIComponent(that.date) : ""),
				          "/page:", (page && page !== undefined ? encodeURIComponent(page) : 1)].join(""));
				return false;

			}).on("click.as", ".asSelectorCart", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Cart");
				return false;
				
			}).on("click.as", ".asSlotAvailable:not(.asSlotNotAccept)", function (e) {	
			
				var $this = pjQ.$(this),
					$parents,
					$offset_top,
					$holder = $this.closest(".asEmployeeInfo");
				
				$parents = $this.parents('.asEmployeeInfo');
				$offset_top = $parents.find('.asEmployeeAppointmentBottom').offset().top;
				
				if ( $offset_top > window.outerHeight ) {
					$this.parents("html").animate({ scrollTop: $offset_top - 50 });
				}
				
				if ($this.hasClass("asSlotSelected")) {
					$this.removeClass("asSlotSelected");
					$holder.find(":submit").attr("disabled", "disabled")
						.end().find(".asEmployeeTime").hide()
						.end().find(".asEmployeeTimeValue").html("")
						.end().find("input[name='start_ts']").val("")
						.end().find("input[name='end_ts']").val("");
				} else {
					$this.siblings(".asSlotBlock").removeClass("asSlotSelected");
					$this.addClass("asSlotSelected");
					$holder.find(":submit").removeAttr("disabled")
						.end().find(".asEmployeeTimeValue").eq(0).html($this.text())
						.end()
						.end().find(".asEmployeeTimeValue").eq(1).html($this.data("end"))
						.end()
						.end().find(".asEmployeeTime").show()
						.end().find("input[name='start_ts']").val($this.data("start_ts"))
						.end().find("input[name='end_ts']").val($this.data("end_ts"));
				}
				
			}).on("submit.as", ".asSelectorAppointmentForm", function (e) {
				
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				that.addToCart.call(that, pjQ.$(this).serializeArray());
				return false;
				
			}).on("submit.as", ".asEmployeeAppointmentForm", function (e) {
				
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				that.addToCartEmployee.call(that, pjQ.$(this).serializeArray());
				return false;
				
			}).on("submit.as", ".asEmployeesAppointmentForm", function (e) {
				
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				that.loademployee(pjQ.$(this).serializeArray());
				return false;
				
			}).on("click.as", ".asCalendarLinkMonth", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this);
				that.getCalendar.call(that, $this.data("year"), $this.data("month"));
				return false;
			}).on("click.as", ".asCalendarDate", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var d, iso = pjQ.$(this).data("iso");
				if (that.options.seoUrl === 1) {
					d = iso.split("-");
					if (window.location.hash.length === 0) {
						hashBang(["#!", d[0], d[1], d[2], 1].join("/"));
					
					} else 
						hashBang(window.location.hash.replace(/(\d+)\/(\d+)\/(\d+)/, d[0] + '/' + d[1] + '/' + d[2]));
					
				} else {
					if (window.location.hash.length === 0) {
						hashBang(["#!/Services/date:", encodeURIComponent(iso), "/page:1"].join(""));	
					
					} else {
						hashBang(window.location.hash.replace(/date:(.*)\//, 'date:' + encodeURIComponent(iso) + '/'));
					}
				}
				
				return false;
			
			// Single (Layout 2)
			}).on("change.as", ".asSelectorSingleDate", function (e) {
				
				var value = this.options[this.selectedIndex].value;
				if (value != "datepicker") {
					that.date = value;
					that.getTime.call(that);
					that.$container.find(".asSelectorSingleDatepicker").hide()
						.end()
						.find(".asSelectorSingleEmployee").hide()
						.end()
						.find("input[name='employee_id'], input[name='start_ts'], input[name='end_ts']").val("")
						.end()
						.find(":submit").attr("disabled", "disabled");
				} else {
					that.$container.find(".asSelectorSingleDatepicker").show();
				}
				
			}).on("change.as", ".asSelectorSingleService", function (e) {
				
				var service_id = this.options[this.selectedIndex].value;
				that.$container.find(".asSelectorServiceBox").hide()
					.end()
					.find(".asSelectorService_" + service_id).show();
				that.service_id = service_id;
				
				that.$container.find(".asSelectorSingleTime").trigger("change");
				
			}).on("change.as", ".asSelectorSingleTime", function (e) {
				if (this.options.length > 0) {
					var option = this.options[this.selectedIndex].value;
					that.start_ts = option.split("|")[0];
					that.end_ts = option.split("|")[1];
				
					that.$container
						.find("input[name='employee_id']").val("")
						.end()
						.find("input[name='start_ts']").val(that.start_ts)
						.end()
						.find("input[name='end_ts']").val(that.end_ts)
						.end()
						.find(":submit").attr("disabled", "disabled");
					
					that.getEmployees.call(that);
				}
			}).on("click.as", ".asSingleEmployeeEmail a", function (e) {
				e.stopPropagation();
				return true;
			}).on("click.as", ".asSelectorEmployee", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this),
					$form = $this.closest("form");

				if ($this.hasClass(".asSingleNotAccept")) {
					return;
				}
				
				if ($this.hasClass("asSingleSelected")) {
					$this.removeClass("asSingleSelected");
					$form.find(":submit").attr("disabled", "disabled")
						.end().find("input[name='employee_id']").val("");
				} else {
					$this.siblings(".asSingleEmployee").removeClass("asSingleSelected");
					$this.addClass("asSingleSelected");
					$form.find(":submit").removeAttr("disabled")
						.end().find("input[name='employee_id']").val($this.data("id"));
				}
				return false;
				
			}).on("submit.as", ".asSelectorSingleForm", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				that.disableButtons.call(that);
				that._addToCart.call(that, pjQ.$(this).serializeArray()).done(function (data) {
					if (data.status == "OK") {
						hashBang("#!/Cart");
					} else if (data.status == "ERR") {
						that.enableButtons.call(that);
					}
				}).fail(function () {
					that.enableButtons.call(that);
				});
				
				return false;
				
			// Cart (Basket)
			}).on("click.as", ".asSelectorContinueShopping", function (e) {
				hashBang(["#!/Services/date:", (that.date && that.date !== undefined ? encodeURIComponent(that.date) : ""),
				          "/page:", (that.page && that.page !== undefined ? encodeURIComponent(that.page) : 1)].join(""));
			}).on("click.as", ".asSelectorEmptyCart", function (e) {
				that.emptyCart.call(that);
			}).on("click.as", ".asSelectorUpdateCart", function (e) {
				that.updateCart.call(that);
			}).on("click.as", ".asSelectorRemoveFromCart", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this);
				that.removeFromCart.call(that, $this.data());
				return false;
			}).on("click.as", ".asSelectorCheckout", function (e) {
				//that.$container.find(".asSelectorCartForm").trigger("submit");
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Checkout");
				return false;
			}).on("click.as", ".asSelectorPreview", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Preview");
				return false;
			}).on("change.as", "select[name='payment_method']", function () {
				that.$container.find(".asSelectorCCard").hide();
				that.$container.find(".asSelectorBank").hide();
				switch (pjQ.$(this).find("option:selected").val()) {
				case 'creditcard':
					that.$container.find(".asSelectorCCard").show();
					break;
				case 'bank':
					that.$container.find(".asSelectorBank").show();
					break;
				}
			}).on("click", ".asServiceDetails", function () {
				
				var $this = pjQ.$(this);
				
				$this.siblings(".asServiceDetails").removeClass("active");
				$this.addClass("active");
				
				$this.siblings(".asServiceAvailability").find("input").attr("data-wt", $this.data("wt"));
			
			}).on("click", ".accordion-heading .accordion-title", function (e) {
				
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this),
					$parent = pjQ.$($this.parents(".accordion-heading"));
				
				$parent.siblings(".accordion-body").slideToggle("slow");
				
				
			});
			
			//Custom events
			pjQ.$(window).on("loadCart", this.container, function (e) {
				that.loadCart.call(that);
			}).on("loadService", this.container, function (e) {
				switch (arguments.length) {
				case 5:
					that.date = [arguments[1], arguments[2], arguments[3]].join("-");
					that.service_id = arguments[4];
					break;
				case 3:
					that.date = arguments[1];
					that.service_id = arguments[2];
					break;
				}
				that.loadService.call(that);
			}).on("loadServices", this.container, function (e) {
				switch (arguments.length) {
				case 5:
					that.date = [arguments[1], arguments[2], arguments[3]].join("-");
					that.page = arguments[4];
					break;
				case 3:
					that.date = arguments[1];
					that.page = arguments[2];
					break;
				}
				that.loadServices.call(that);
			}).on("loadCheckout", this.container, function (e) {
				that.loadCheckout.call(that);
			}).on("loadPreview", this.container, function (e) {
				that.loadPreview.call(that);
			}).on("loadBooking", this.container, function (e, booking_uuid) {
				that.booking_uuid = booking_uuid;
				that.loadBooking.call(that);
			});
			
			if (window.location.hash.length === 0) {
				pjQ.$(window).trigger("loadServices");
			} else {
				onHashChange.call(null);
			}
			
			return this;
		},
		loadCheckout: function () {
			var that = this;
			this.disableButtons.call(this);
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout
			}).done(function (data) {
				that.$container.html(data);
				that.view = 'pjActionCheckout';
				
				if (validate) {
					pjQ.jQuery.validator.addClassRules("asRequired", {
						required: true
					});
					pjQ.jQuery.validator.addClassRules("asEmail", {
						email: true
					});
					that.$container.find(".asSelectorCheckoutForm").validate({
						rules: {
							"captcha" : {
								remote: that.options.folder + "index.php?controller=pjFrontEnd"+ $as_pf +"&action=pjActionCheckCaptcha",
								required: true,
								minlength: 6,
								maxlength: 6
							}
						},
						onkeyup: false,
						onclick: false,
						onfocusout: false,
						errorClass: "asError",
						validClass: "asValid",
						wrapper: "em",
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent());
						},
						submitHandler: function (form) {
							that.disableButtons.call(that);
							var $form = pjQ.$(form);
							pjQ.$.post([that.options.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout" + $as_pf].join(""), $form.serialize()).done(function (data) {
								if (data.status == "OK") {
									hashBang("#!/Preview");
								} else if (data.status == "ERR") {
									that.enableButtons.call(that);
								}
							}).fail(function () {
								that.enableButtons.call(that);
							});
							return false;
						}
					});
				}
				
			}).fail(function () {
				that.enableButtons.call(that);
			});
		},
		loademployee: function (attr) {
			var that = this,
				obj = {
					"cid": this.options.cid,
					"layout": this.options.layout,
					"date": this.date
				};
			
			var check = 0;
			for ( var i = 0; i < attr.length; i++) {
				
				if (attr[i].name == 'id') {
					obj = pjQ.$.extend(obj, {
						"id": attr[i].value
					});
					check++;
				}else if (attr[i].name == 'date') {
					obj = pjQ.$.extend(obj, {
						"date": attr[i].value
					});
					check++;
				}else if (attr[i].name == 'start_ts') {
					obj = pjQ.$.extend(obj, {
						"start_ts": attr[i].value
					});
					check++;
				}
			}
	
			if (check == 3) {
				pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionEmployee" + $as_pf].join(""), obj).done(function (data) {
					that.$container.html(data);
					that.view = 'pjActionEmployee';
				});	
			} else {
				that.loadServices.call(that);
			}
		},
		loadService: function (employee_id) {
			
			var that = this,
				obj = {
					"cid": this.options.cid,
					"layout": this.options.layout,
					"id": this.service_id,
					"date": this.date,
					"wt_id": this.wt_id
				};

			if (employee_id !== undefined) {
				obj = pjQ.$.extend(obj, {
					"employee_id": employee_id
				});
			}

			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionService" + $as_pf].join(""), obj).done(function (data) {
				that.$container.html(data);
				that.view = 'pjActionService';
			});
		},
		loadServices: function () {
			var that = this;
			this.disableButtons.call(this);
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionServices" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout,
				"date": this.date,
				"page": this.page,
				
			}).done(function (data) {
				that.$container.html(data);
				
				switch (that.options.layout) {
					case 2:
						that.view = 'pjActionSingle';
					
						var service_id = that.$container.find(".asSelectorSingleService").find("option:selected").val();
						if (parseInt(service_id, 10) > 0) {
							that.service_id = service_id;
							that.$container.find(".asSelectorSingleService").trigger("change");
							that.$container.find(".asSelectorSingleTime").trigger("change");
						}
						break;
					case 1:
					default:
						that.view = 'pjActionServices';
						break;
				}
				
			}).fail(function () {
				this.enableButtons.call(this);
			});
		},
		loadPreview: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionPreview" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout
			}).done(function (data) {
				that.$container.html(data);
				that.view = 'pjActionPreview';
				
				if (validate) {
					that.$container.find(".asSelectorPreviewForm").validate({
						rules: {
							as_validate: {
								remote: [that.options.folder, "index.php?controller=pjFrontEnd"+ $as_pf +"&action=pjActionValidateCart"].join("")
							}
						},
						messages: {
							as_validate: {
								remote: that.options.fields.v_remote
							}
						},
						onkeyup: false,
						onclick: false,
						onfocusout: false,
						ignore: ".asIgnore",
						errorClass: "asError",
						validClass: "asValid",
						wrapper: "em",
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent());
						},
						submitHandler: function (form) {
							that.disableButtons.call(that);
							var $form = pjQ.$(form);
							pjQ.$.post([that.options.folder, "index.php?controller=pjFrontEnd&action=pjActionProcessOrder" + $as_pf].join(""), $form.serialize()).done(function (data) {
								if (data.status == "OK") {
									hashBang("#!/Booking/" + data.booking_uuid);
								} else if (data.status == "ERR") {
									that.enableButtons.call(that);
								}
							}).fail(function () {
								that.enableButtons.call(that);
							});
							return false;
						}
					});
				}
			});
		},
		loadBooking: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionBooking" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout,
				"booking_uuid": this.booking_uuid
			}).done(function (data) {
				that.$container.html(data);
				that.view = 'pjActionBooking';
				
				var $paypal = that.$container.find("form[name='asPaypal']"),
					$authorize = that.$container.find("form[name='asAuthorize']");
				
				if ($paypal.length > 0) {
					window.setTimeout(function () {
						$paypal.trigger('submit');
					}, 3000);
				} else if ($authorize.length > 0) {
					window.setTimeout(function () {
						$authorize.trigger('submit');
					}, 3000);
				}
			});
		},
		loadCart: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionCart" + $as_pf].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout
			}).done(function (data) {
				that.$container.html(data);
				that.view = 'pjActionCart';
			});
		}
	};
	
	// expose
	window.AppScheduler = AppScheduler;	
})(window);