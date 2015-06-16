(function(){!function(t){"use strict";return t(function(){var n,e,o,a,i,r,l,u,c,s,d,f,h,p;return a=t("#form-search"),l=a.find("[name=q]"),i=a.find("[name=location]"),e=a.find(".force-selection"),r=t("#location-dropdown-wrapper"),p=function(t){var n;return n=app.lang,t.split(".").forEach(function(t){return n=null!=n[t]?n[t]:null}),null!=n?n:t},f=app.prefetch.districts.map(function(t){return{type:"district",name:t}}).concat(app.prefetch.cities.map(function(t){return{type:"city",name:t}})),h=f,u={},u.view=function(){return[m("li[role=presentation]",[m("a.form-search-city[data-current-location=1][href=#]",[m("strong",p("home.search.current_location"))])]),m("li.divider[role=presentation]"),m("li[role=presentation]",{"class":h.length?"soft-hidden":"disabled"},[m("a[href=#]",[m("em","Empty")])]),h.map(function(t){return m("li[role=presentation]",m("a.form-search-city[href=#][data-current-location=0][data-type="+t.type+"]",t.name))})]},m.mount(document.getElementById("big-cities-dropdown"),m.component(u)),d=new Bloodhound({queryTokenizer:Bloodhound.tokenizers.whitespace,local:f,datumTokenizer:function(t){return Bloodhound.tokenizers.whitespace(t.name)}}),d.initialize(),i.on("keyup",function(t){var n;return n=t.target.value,0===n.length?(h=f,void m.redraw()):d.get(n,function(t){return h=t,m.redraw()})}),l.length>0&&VARAA.initTypeahead(l,"services"),l.bind("typeahead:selected",function(t,n){return a.data("disableSubmission",!1),"category"===n.type?(a.data("suggestion",l.val()),a.data("old-action",a.attr("action")),a.attr("action",n.url)):"undefined"!=typeof n.url?window.location=n.url:void 0}),s=function(n){return t(n.target).tooltip("hide")},l.on("focus",s).on("blur",function(){var n,o,i;return n=t(this),i=n.val(),o=l.data("bloodhound"),o.get(i,function(t){return t=t.filter(function(t){return t.name===i}),0===t.length?(e.show(),a.data("disableSubmission",!0)):(e.hide(),a.data("disableSubmission",!1))})}),i.on("focus",function(t){return s(t),r.addClass("open"),this.value=""}),a.on("submit",function(t){var n,e,o;return a.data("disableSubmission")===!0?void t.preventDefault():a.data("bypass")===!0?!0:a.data("old-action")?(l.val()!==a.data("suggestion")&&a.attr("action",a.data("old-action")),!0):(t.preventDefault(),n=function(){return a.data("bypass",!0),a.submit()},o=0===l.val().length,e=0===i.val().length,o&&l.tooltip("show"),e&&i.tooltip("show"),e||o?void 0:i.data(!1)?VARAA.getLocation().then(function(t,n){return a.find("[name=lat]").val(t),a.find("[name=lng]").val(n)}).always(n):n())}),t("#big-cities-dropdown").on("click","a.form-search-city",function(n){var e;return n.preventDefault(),e=t(this),a.find("[name=type]").val(e.data("type")),i.attr("data-current-location",e.data("current-location")),i.val(e.text()),r.removeClass("open")}),o=t("#form-contact"),o.length>0&&o.on("submit",function(n){var e,o,a,i;return n.preventDefault(),o=t(this),i=o.find(".alert-success"),e=o.find(".alert-danger"),a=o.find("[type=submit]"),t.ajax({url:o.attr("action"),method:"POST",dataType:"JSON",data:o.serialize()}).then(function(){return e.hide(),i.show(),a.attr("disabled",!0)}).fail(function(n){var o,a,i,r;if(o=n.responseJSON,422===n.status){e.empty(),r=function(n){return e.append(t("<p/>").html(n.join("<br>")))};for(i in o)a=o[i],r(a)}else e.html(o.message);return e.show()})}),t("#js-navbar").find("a").on("click",function(n){var e,o,a,i,r,l;return n.preventDefault(),e=t(this),o=t("body"),i=o.data("lat"),r=o.data("lng"),null!=i&&null!=r&&""!==i&&""!==r?window.location=e.prop("href"):(l=function(n){return i=n.coords.latitude,r=n.coords.longitude,t.ajax({url:o.data("geo-url"),type:"POST",data:{lat:i,lng:r}}).done(function(){return window.location=e.prop("href")})},a=function(){return window.location=e.prop("href")},navigator.geolocation.getCurrentPosition(l,a,{timeout:1e4}))}),t(".datetime-link").on("click",function(t){return t.preventDefault()}).on("focus",function(n){return n.preventDefault(),t(this).siblings(".datetime-control").show()}).on("blur",function(n){return n.preventDefault(),t(this).siblings(".datetime-control").hide()}),t("#js-choose-category").on("click",function(n){return n.preventDefault(),t.scrollTo("#js-home-categories",{duration:1e3})}),c=function(n,e){var o,a,i,r;return o=n.find("li:not(.toggle):lt("+e+")"),i=n.find("li:not(.toggle):gt("+(e-1)+")"),r=n.find(".toggle"),a=n.find(".more"),o.show(),i.length>0&&a.show(),r.on("click",function(n){var e;return n.preventDefault(),e=t(this),i.slideToggle(),e.hide(),e.siblings(".toggle").show()})},t("ul.list-categories").each(function(n,e){return c(t(e),3)}),c(t("#js-category-filter"),6),n=t(".js-deal"),t("a.js-filter-link").on("click",function(e){var o,a;return e.preventDefault(),a=t(this),a.toggleClass("active"),o=t("a.js-filter-link.active"),o.length?(n.hide(),o.each(function(n,e){var o,a,i;return o=t(e),i=o.data("id"),a=t(".js-deal-category-"+i),a.fadeIn()})):n.show()})})}(jQuery)}).call(this);