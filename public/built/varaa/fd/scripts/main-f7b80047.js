(function(){!function(t){"use strict";return t(function(){var e,a;return e={format:"yyyy-mm-dd",weekStart:1,autoclose:!0,startDate:new Date,language:t("body").data("locale")},t(".date-picker").datepicker(e),a=function(){return Math.floor(65536*(1+Math.random())).toString(16).substring(1)},t("#js-fd-add-date").on("click",function(n){var r,d,i;n.preventDefault(),d=t("#js-fd-date-template"),r=t(d.clone()),i=a(),r.find(".date-picker").datepicker(e),r.find("input:text").attr("name","date["+i+"]"),r.find("input:radio").attr("name","time["+i+"]"),t("div.js-fd-date:last").after(r)}),t("a.js-fd-delete-date").on("click",function(e){var a;return e.preventDefault(),a=t(this),confirm(a.data("confirm"))!==!1?(a.find("i").removeClass("fa-close").addClass("fa-refresh fa-spin"),t.ajax({url:a.attr("href"),type:"GET"}).done(function(){return t("#js-fd-date-"+a.data("id")).fadeOut()})):void 0})})}(jQuery)}).call(this);