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
	var $_GET = {}, $as_pf, $owner_id;

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
	
	if ( $_GET['owner_id'] != null ) {
		
		$owner_id = "&owner_id=" + $_GET['owner_id'];
		
	} else if ( getCookie('owner_id') != '' ) {
		
		$owner_id = "&owner_id=" + getCookie('owner_id');
		
	} else $owner_id = '';

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
	pjQ.$(document).ready( function(){
		// setTimeout( refreshAvaiable, 2000 );
	});
	function refreshAvaiable( ){
		var objCurrent = pjQ.$("div.asEmployee").find("div.asEmployeeTimeslots");
		var arr = pjQ.$("div.asServiceTime").eq(0).text();
		arr = arr.split(" ");
		var minute = arr[0];
		var nextCnt = Math.ceil( minute / 15 );
		
		for( var i = 0; i < objCurrent.size(); i ++ ){
			var $current = objCurrent.eq(i).find("span.asSlotBlock").eq(0);
			var totalCnt = $current.parents("div.asEmployeeTimeslots").eq(0).find("span.asSlotBlock").size();
			var indCurrent = $current.parents("div.asEmployeeTimeslots").eq(0).find("span.asSlotBlock").index( $current );
			for( var k = indCurrent + nextCnt; k < totalCnt; k ++ ){
				var objK = $current.parents("div.asEmployeeTimeslots").eq(0).find("span.asSlotBlock").eq( k );
				if( objK.hasClass("asSlotUnavailable") ){
					continue;
				}else{
					var objP = objK;
					for( var p = 0; p < nextCnt; p ++ ){
						if( objP.hasClass("asSlotUnavailable") ){
							//objK.addClass("asSlotUnselectable");
							continue;
						}
						objP = objP.next();
					}						
				}

			}				
		}

	}	
	function AppScheduler(options) {
		if (!(this instanceof AppScheduler)) {
			return new AppScheduler(options);
		}
				
		this.reset.call(this);
		this.date = options.firstDate;
		this.date_first = options.firstDate;
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
			this.category_id = null;
			this.service_id = null;
			this.employee_id = null;
			this.booking_uuid = null;
			this.options = {};
			this.wt_id = -1;
			this.changedate = false;	
			this.date_first = null;
			return this;
		},
		disableButtons: function () {
			this.$container.find(".asSelectorButton").attr("disabled", "disabled");
		},
		enableButtons: function () {
			this.$container.find(".asSelectorButton").removeAttr("disabled");
		},
		_addToCart: function (arr) {
			var xhr = pjQ.$.post([this.options.folder, "index.php?controller=pjFrontEnd"+ $as_pf  + $owner_id +"&action=pjActionAddToCart&cid=", this.options.cid].join(""), pjQ.$.param(arr));
			
			return xhr;
		},
		_removeFromCart: function (opts) {
			var xhr = pjQ.$.post([this.options.folder, "index.php?controller=pjFrontEnd"+ $as_pf  + $owner_id +"&action=pjActionRemoveFromCart&cid=", this.options.cid].join(""), {
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
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetCart" + $as_pf + $owner_id].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout
			}).done(function (data) {
				that.$container.find(".asSelectorCartWrap").html(data);
			});
		},
		getCalendar: function (year, month) {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetCalendar" + $as_pf + $owner_id].join(""), {
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
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetEmployees" + $as_pf + $owner_id].join(""), {
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
		getLoadAjaxLayout2: function () {
			var that = this, $height;
			
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionLoadAjax" + $as_pf + $owner_id].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout,
				"date": this.date,
				"category_id": this.category_id,
				"service_id": this.service_id,
				"employee_id": this.employee_id,
				"date_first": this.date_first,
				"wt_id": this.wt_id,
			}).done(function (data) {
				if ( that.category_id > 0 ) {
					that.$container.find(".asSingleServices").show();
					that.$container.find(".asSingleServices .asBox").html(data);
					
					if ( that.$container.find(".asSingleCategories ul").height() > that.$container.find(".asSingleServices ul").height() ) {
						$height = that.$container.find(".asSingleCategories ul").height();
					} else $height = that.$container.find(".asSingleServices ul").height();
					
					that.$container.find(".asSingleCategories ul").css("min-height", $height);
					that.$container.find(".asSingleServices ul").css("min-height", $height);
					
				} else if ( that.service_id > 0 && that.employee_id < 1 ) {
					that.$container.find(".asSingleEmployees").show();
					that.$container.find(".asSingleEmployees .asBox").html(data);
					
					if ( that.$container.find(".asSingleCategories ul").height() >= that.$container.find(".asSingleServices ul").height() &&
							that.$container.find(".asSingleCategories ul").height() >= that.$container.find(".asSingleEmployees ul").height() ) {
							
						$height = that.$container.find(".asSingleCategories ul").height();
						
					} else if ( that.$container.find(".asSingleServices ul").height() >= that.$container.find(".asSingleCategories ul").height() &&
							that.$container.find(".asSingleServices ul").height() >= that.$container.find(".asSingleEmployees ul").height() ) {
							
						$height = that.$container.find(".asSingleServices ul").height();
						
					} else $height = that.$container.find(".asSingleEmployees ul").height();
					
					that.$container.find(".asSingleCategories ul").css("min-height", $height);
					that.$container.find(".asSingleServices ul").css("min-height", $height);
					that.$container.find(".asSingleEmployees ul").css("min-height", $height);
					
				} else if ( that.service_id > 0 && (that.employee_id > 0 || that.employee_id == 'all') && !that.changedate ) {
					that.$container.find(".asSingleDate").show();
					that.$container.find(".asSingleDate .asBox").html(data);
				} else if ( that.changedate ) {
					var $data = pjQ.$(data);
					that.$container.find(".asSingleDate .asBox .times").html($data.find(".times").html());
				}
				
				that.category_id = null;
				that.service_id = null;
				that.employee_id = null;
				that.wt_id = -1;
				that.changedate = false;
			});
		},
		
		getLoadAjaxLayout3: function () {
			var that = this, $height;
			
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionLoadAjax" + $as_pf + $owner_id].join(""), {
				"cid": this.options.cid,
				"layout": this.options.layout,
				"date": this.date,
				"category_id": this.category_id,
				"service_id": this.service_id,
				"employee_id": this.employee_id,
				"date_first": this.date_first,
				"wt_id": this.wt_id,
			}).done(function (data) {
				if ( that.service_id > 0 && that.employee_id < 1 ) {
					that.$container.find(".asLayout3Employees .asBoxInner").html(data);
				
				} else if( that.service_id > 0 && (that.employee_id > 0 || that.employee_id == 'all' ) ) {
					that.$container.find(".asLayout3Date .asBoxInner").html(data);
				} 
				
				that.category_id = null;
				that.service_id = null;
				that.employee_id = null;
				that.wt_id = -1;
				that.changedate = false;
			});
		},
		
		getTime: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontEnd&action=pjActionGetTime" + $as_pf + $owner_id].join(""), {
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
						
						pjQ.$.get("index.php?controller=pjFrontPublic&action=getExtraService" + $as_pf + $owner_id, {
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
								"Next": function () {
									var $dialog = pjQ.$(this),
										$frmExtraService = $dialog.find('form');
									
									pjQ.$.post("index.php?controller=pjFrontPublic&action=getExtraService" + $as_pf + $owner_id, $frmExtraService.serialize()).done(function (data) {
										
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
								"Cancel": function () {
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
				
				// modified by jeni : 2014-07-17
				var objCurrent1 = pjQ.$(this);
				var objCurrent2 = pjQ.$(this);
                // if(objCurrent2.hasClass('asSlotUnselectable')){
                //     return;
                // }
				var arr = pjQ.$("div.asServiceTime").eq(0).text();
				arr = arr.split(" ");
				var minute = arr[0];
				var nextCnt = Math.ceil( minute / 15 );
				for( var k = 0; k < nextCnt - 1; k ++ ){
					objCurrent1 = objCurrent1.next();
					if( objCurrent1.hasClass("asSlotFreeTimeUnavailable") || objCurrent1.hasClass("asSlotLunchUnavailable") ){
						return; 
					}
				}
				
				
				/*if ($this.hasClass("asSlotSelected")) {
					$this.siblings(".asSlotBlock").removeClass("asSlotSelected");
					$holder.find(":submit").attr("disabled", "disabled")
						.end().find(".asEmployeeTime").hide()
						.end().find(".asEmployeeTimeValue").html("")
						.end().find("input[name='start_ts']").val("")
						.end().find("input[name='end_ts']").val("");
				} else {*/
				
					$this.siblings(".asSlotBlock").removeClass("asSlotUnselectable");
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
				// }
				for( var k = 0; k < nextCnt - 1; k ++ ){
					objCurrent2 = objCurrent2.next();
					objCurrent2.addClass("asSlotSelected");
				}		
				refreshAvaiable( );

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
				
			// Custom
			}).on("click.as", ".asSingleCategories a", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $height = 0, $this = pjQ.$(this);
				
				$this.parent().addClass("active").siblings(".active").removeClass("active");

				that.category_id = $this.data("id");
			
				that.$container.find(".asSingleServices").hide()
					.end()
					.find(".asSingleEmployees").hide()
					.end()
					.find(".asSingleDate").hide()
					.end()
					.find("input[name='category_id']").val(that.category_id)
					.end()
					.find(":submit").attr("disabled", "disabled");
				
				that.getLoadAjaxLayout2.call(that);

			}).on("click.as", ".asSingleServices a.service", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				$this.parent().addClass("active").siblings(".active").removeClass("active");
				$this.parent().siblings().find(".asServiceMore").hide();
				$this.parent().find(".asServiceMore").show(500);
				$this.parent().siblings().find(".asServiceExtra").hide();
				$this.parent().siblings().find(".asServiceExtra input:checked").removeAttr('checked');
				$this.parent().siblings().find(".asServiceTimes li.active").removeClass("active");
				
				$this.parents(".asSingleInner").removeClass("parentServiceExtra")
				
				if ($this.parent().find(".asServiceExtra").length > 0) {
					$this.parent().find(".asServiceExtra").show(500);
					$this.parents(".asSingleServices").addClass("parentServiceExtra");
				}
				that.service_id = $this.data("id");
				
				that.$container.find(".asSingleEmployees").hide()
					.end()
					.find(".asSingleDate").hide()
					.end()
					.find("input[name='service_id']").val(that.service_id)
					.end()
					.find("input[name='wt_id']").val("0")
					.end()
					.find(":submit").attr("disabled", "disabled");
				
				that.getLoadAjaxLayout2.call(that);
				
			}).on("click.as", ".asSingleServices a.service_time", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				$this.parent().addClass("active").siblings(".active").removeClass("active");
				
				that.service_id = that.$container.find("input[name='service_id']").val();
				that.employee_id = that.$container.find("input[name='employee_id']").val();
				that.wt_id = $this.data("service_time_id");
				that.date = $this.data("date_start");
				that.changedate = true;
				
				that.$container.find("input[name='date']").val("")
					.end()
					.find("input[name='start_ts']").val("")
					.end()
					.find("input[name='end_ts']").val("")
					.end()
					.find("input[name='wt_id']").val($this.data("service_time_id"))
					.end()
					.find(":submit").attr("disabled", "disabled");
					
				that.getLoadAjaxLayout2.call(that);
				
			}).on("click.as", ".asSingleEmployees a", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				$this.parent().addClass("active").siblings(".active").removeClass("active");
				
				that.service_id = that.$container.find("input[name='service_id']").val();
				that.employee_id = $this.data("id");
				//console.log(that.$container.find("input[name='wt_id']").val());
				that.wt_id = that.$container.find("input[name='wt_id']").val();
				that.date = that.$container.find("input[name='date_start']").val();
				
				that.$container.find(".asSingleDate").hide()
					.end()
					.find("input[name='employee_id']").val(that.employee_id)
					.end()
					.find("input[name='date']").val("")
					.end()
					.find("input[name='start_ts']").val("")
					.end()
					.find("input[name='end_ts']").val("")
					.end()
					.find(":submit").attr("disabled", "disabled");
				
				that.getLoadAjaxLayout2.call(that);
				
			}).on("click.as", ".asSingleDate .dateStart a", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				$this.parent().addClass("active").siblings(".active").removeClass("active");
				
				that.service_id = that.$container.find("input[name='service_id']").val();
				that.employee_id = that.$container.find("input[name='employee_id']").val();
				that.wt_id = that.$container.find("input[name='wt_id']").val();
				that.date = $this.data("date_start");
				that.changedate = true;
				
				that.$container.find("input[name='date_start']").val($this.data("date_start"))
					.end()
					.find("input[name='date']").val("")
					.end()
					.find("input[name='start_ts']").val("")
					.end()
					.find("input[name='end_ts']").val("")
					.end()
					.find(":submit").attr("disabled", "disabled");
				
				that.getLoadAjaxLayout2.call(that);
				
			}).on("click.as", ".asSingleDate .times a", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				$this.parent().addClass("active").siblings(".active").removeClass("active");
				
				if ( $this.hasClass("allEmployee") ) {
					that.$container.find("input[name='employee_id']").val($this.data("employee_id"));
				}
				
				that.$container.find("input[name='date']").val($this.data("date"))
					.end()
					.find("input[name='start_ts']").val($this.data("start_ts"))
					.end()
					.find("input[name='end_ts']").val($this.data("end_ts"))
					.end()
					.find(":submit").removeAttr("disabled");
				
				// Layout 3
			}).on("click.as", ".asLayout3 .heading a", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				if ($this.hasClass('collapse')) {
					$this.parents('.asLayout3Inner').siblings(".asLayout3Inner").find('.asBox').removeClass("in");
					$this.parent().siblings(".asBox").addClass("in");
					that.$container.find(".asLayout3 .heading").removeClass("active");
					$this.parent().addClass("active");
				}
				
			}).on("click.as", ".asLayout3 .asCategories input", function (e) {
				
				var $this = pjQ.$(this);
				
				that.$container.find('.asCategories').removeClass("in")
					.end()
					.find('.asService').removeClass("in")
					.end()
					.find('.asCategoryID_' + $this.val()).addClass("in");
				
			}).on("click.as", ".asLayout3 a.asCategoriesBack", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				that.$container.find('.asService').removeClass("in")
					.end()
					.find('.asCategories').addClass("in")
					
			}).on("click.as", ".asLayout3 .asServices input[name=service]", function (e) {
				
				var $this = pjQ.$(this);
				
				that.service_id = $this.val();
				
				if( $this.parents(".asService").find('.asServiceID_' + $this.val()).length > 0 ) {
					that.$container.find('.asService').removeClass("more")
						.end()
						.find('.asLayout3 .heading a').removeClass('collapse')
						.end()
						.find(".asServiceMore input:checked").removeAttr('checked')
						.end()
						.find("input[name='wt_id']").val("0");
						
					$this.parents(".asService").addClass("more").find('.asServiceID_' + $this.val()).addClass("in");
						
				} else {
					that.$container.find('.asLayout3Categories .asBox').removeClass("in")
					.end()
					.find('.asLayout3Employees .asBox').addClass("in")
					.end()
					.find('.asLayout3 .heading a').removeClass('collapse')
					.end()
					.find('.asLayout3Categories .heading a').addClass('collapse')
					.end()
					.find('.asLayout3 .heading').removeClass('active')
					.end()
					.find('.asLayout3Employees .heading').addClass('active');
					
					that.getLoadAjaxLayout3.call(that);
				}
				
				that.$container.find(".asLayout3Employees .asBoxInner").html("")
					.end()
					.find("input[name='service_id']").val($this.val());
						
			}).on("click.as", ".asLayout3 .asServices input[name=service_time]", function (e) {
				
				var $this = pjQ.$(this);
				
				that.wt_id = $this.val();
				
				that.$container.find("input[name='wt_id']").val($this.val());
						
			}).on("click.as", ".asLayout3 a.asServicesBack", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				that.$container.find('.asService').removeClass("more")
					.end()
					.find('.asServiceMore').removeClass("in");
					
			}).on("click.as", ".asLayout3 .asServiceMore a.next", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				that.$container.find('.asLayout3Categories .asBox').removeClass("in")
					.end()
					.find('.asLayout3Employees .asBox').addClass("in")
					.end()
					.find('.asLayout3 .heading a').removeClass('collapse')
					.end()
					.find('.asLayout3Categories .heading a').addClass('collapse')
					.end()
					.find('.asLayout3 .heading').removeClass('active')
					.end()
					.find('.asLayout3Employees .heading').addClass('active');
					
				that.getLoadAjaxLayout3.call(that);
					
			}).on("click.as", ".asLayout3 .asEmployees input", function (e) {
				
				var $this = pjQ.$(this);
				
				that.service_id = that.$container.find("input[name='service_id']").val();
				that.employee_id = $this.val();
				that.wt_id = that.$container.find("input[name='wt_id']").val();
				that.date = that.$container.find("input[name='date_start']").val();
				
				that.$container.find('.asLayout3Date .asBoxInner').html("")
					.end()
					.find('.asLayout3Employees .asBox').removeClass("in")
					.end()
					.find('.asLayout3Date .asBox').addClass("in")
					.end()
					.find('.asLayout3Employees .heading a').addClass('collapse')
					.end()
					.find('.asLayout3Date .heading a').removeClass('collapse')
					.end()
					.find('.asLayout3 .heading').removeClass('active')
					.end()
					.find('.asLayout3Date .heading').addClass('active')
					.end()
					.find("input[name='employee_id']").val($this.val());
					
				that.getLoadAjaxLayout3.call(that);
				
			}).on("click.as", ".asLayout3 .asdate .times a", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = pjQ.$(this);
				
				$this.parent().addClass("active").siblings(".active").removeClass("active");
				
				if ( $this.hasClass("allEmployee") ) {
					that.$container.find("input[name='employee_id']").val($this.data("employee_id"));
				}
				
				that.$container.find("input[name='date']").val($this.data("date"))
					.end()
					.find("input[name='start_ts']").val($this.data("start_ts"))
					.end()
					.find("input[name='end_ts']").val($this.data("end_ts"))
					.end()
					.find('.asLayout3Date .asBox').removeClass("in")
					.end()
					.find('.asLayout3Contact .asBox').addClass("in")
					.end()
					.find('.asLayout3 .heading').removeClass('active')
					.end()
					.find('.asLayout3Contact .heading').addClass('active')
					.end()
					.find('.asLayout3Date .heading a').addClass('collapse');
				
			}).on("focusin", ".datepick", function (e) {
				var $this = pjQ.$(this);
				$this.datepicker({
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev"),
					onSelect: function (dateText, inst) {
					
						that.date =  [inst.selectedYear, inst.selectedMonth+1, inst.selectedDay].join("-");
						that.service_id = that.$container.find("input[name='service_id']").val();
						that.employee_id = that.$container.find("input[name='employee_id']").val();
						that.wt_id = that.$container.find("input[name='wt_id']").val();
						
						that.$container.find("input[name='date_start']").val(that.date)
						
						that.getLoadAjaxLayout3.call(that);
					}
				});
				
			}).on("click", ".pj-form-field-icon-date", function (e) {
				var $dp = $(this).parent().siblings("input[type='text']");
				if ($dp.hasClass("hasDatepicker")) {
					$dp.datepicker("show");
				} else {
					$dp.trigger("focusin").datepicker("show");
				}
				
			}).on("click.as", ".asSingleEmployeeEmail a", function (e) { //End layout 3
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
						//hashBang("#!/Cart");
						hashBang("#!/Checkout");
					} else if (data.status == "ERR") {
						that.enableButtons.call(that);
					}
				}).fail(function () {
					that.enableButtons.call(that);
				});
				
				return false;
				
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
			
			pjQ.$( document ).ajaxStart(function() {
				pjQ.$( "#loading" ).show();
			});
			
			pjQ.$( document ).ajaxStop(function() {
				pjQ.$( "#loading" ).hide();
			});
			
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
				refreshAvaiable();
			});
		},
		loadServices: function () {
			var that = this;
			this.disableButtons.call(this);
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionServices" + $as_pf + $owner_id].join(""), {
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
				
				if (validate) {
					pjQ.jQuery.validator.addClassRules("asRequired", {
						required: true
					});
					pjQ.jQuery.validator.addClassRules("asEmail", {
						email: true
					});
					that.$container.find(".asSelectorLayout3Form").validate({
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
							that._addToCart.call(that, $form.serializeArray()).done(function (data) {
								if (data.status == "OK") {
									
									pjQ.$.post([that.options.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout" + $as_pf].join(""), $form.serialize()).done(function (data) {
										if (data.status == "OK") {
											hashBang("#!/Preview");
											
										} else if (data.status == "ERR") {
											that.enableButtons.call(that);
										}
									});
									
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
				this.enableButtons.call(this);
			});
		},
		loadPreview: function () {
			var that = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionPreview" + $as_pf + $owner_id].join(""), {
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
							pjQ.$.post([that.options.folder, "index.php?controller=pjFrontEnd&action=pjActionProcessOrder" + $as_pf + $owner_id].join(""), $form.serialize()).done(function (data) {
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
