(function(){$(function(){"use strict";var t,n,e,a,o,r,i,l,c,u;return n=$(document),l=$("#js-queryInput"),i=$("#js-locationInput"),c=function(t,n){var e;return e=new Bloodhound({datumTokenizer:Bloodhound.tokenizers.obj.whitespace("name"),queryTokenizer:Bloodhound.tokenizers.whitespace,limit:10,prefetch:{url:"/search/"+n+".json",filter:function(t){return"string"==typeof t[0]?$.map(t,function(t){return{name:t}}):t}}}),e.clearPrefetchCache(),e.initialize(),t.typeahead({highlight:!0,hint:!0},{name:n,displayKey:"name",source:e.ttAdapter()})},null!=l&&null!=i&&l.length>0&&i.length>0&&(c(l,"services"),c(i,"locations")),e=$("#main-search-form"),e.length&&(o=e.find("[name=lat]"),r=e.find("[name=lng]"),u=!(o.val().length>0&&r.val().length>0),l.on("focus",function(){var t,n,a;if(navigator.geolocation&&u)return t=$("#js-geolocation-info"),u&&t.show(),a=function(t){var n,a;return n=t.coords.latitude,a=t.coords.longitude,o.val(n),r.val(a),$.ajax({url:e.data("update-location-url"),type:"POST",data:{lat:n,lng:a}})},n=function(t){return console.log(t)},navigator.geolocation.getCurrentPosition(a,n,{timeout:1e4}),u=!1})),a=$("#js-languageSwitcher"),a.change(function(){return window.location=this.value}),t=$("#header-cart"),t.popover({placement:"bottom",trigger:"click",html:!0}),n.on("click",function(n){var e;e=$(n.target),"popover"!==e.data("toggle")&&0===e.parents("#header-cart").length&&0===e.parents(".popover.in").length&&t.popover("hide")}),n.on("cart.reload",function(e,a){return $.ajax({url:t.data("cart-url"),dataType:"JSON"}).done(function(e){return t.find(".content").html(e.totalItems),t.attr("data-content",e.content),a?(t.popover("show"),n.scrollTop(0)):void 0})}),n.on("click","a.js-btn-cart-remove",function(t){var e;return t.preventDefault(),e=$(this),e.find("i.fa").removeClass("fa-close").addClass("fa-spinner fa-spin"),$.ajax({url:e.attr("href")}).done(function(){return $("tr.cart-detail-"+e.data("detail-id")).fadeOut(),n.trigger("cart.reload",!0)})}),n.trigger("cart.reload",!1),VARAA.applyCountdown=function(t){return t.each(function(){var t;return t=$(this),t.countdown({until:new Date(t.data("date")),compact:!0,layout:"{hnn}{sep}{mnn}{sep}{snn}"})})},VARAA.equalize=function(t){var n;return n=0,$(t).each(function(){var t;return t=$(this).outerHeight(),t>n?n=t:void 0}).css("height",n)}})}).call(this);