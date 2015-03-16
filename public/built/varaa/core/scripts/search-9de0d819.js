(function(){var t,e;t=function(){function t(t){this.businesses=t.businesses,this.lat=t.lat,this.lng=t.lng,null!=t.categoryId&&(this.categoryId=t.categoryId),null!=t.serviceId&&(this.serviceId=t.serviceId),null!=t.employeeId&&(this.employeeId=t.employeeId),null!=t.time&&(this.time=t.time),null!=this.businesses[0]&&(this.single=this.businesses[0])}return t.prototype.run=function(){return this.categoryId&&this.serviceId&&this.selectBookingForm(),$("#js-map-"+this.single.user_id).length?this.showSingleBusiness():this.showBusinesses()},t.prototype.selectBookingForm=function(){return $("input:radio[name=category_id][value="+this.categoryId+"], input:radio[name=service_id][value="+this.serviceId+"]").click(),$("input[name=service_id]").on("afterSelect",function(){return $("input:radio[name=employee_id][value="+this.employeeId+"]").click()}),$("#as-step-3").on("afterShow",function(){return $("button[data-time="+this.time+"]").click()})},t.prototype.renderMap=function(t,e,s,n){var i,a,r,o;if(i=new GMaps({div:t,lat:e,lng:s,zoom:8}),void 0!==typeof n)for(r=0,o=n.length;o>r;r++)a=n[r],i.addMarkers(n);return i},t.prototype.showBusinesses=function(){var t,e,s,n;return e=$("#js-loading"),t=$("#js-business-content"),s=$("#map-canvas"),s.show(),n=this.extractMarkers(this.businesses),this.renderMap("#map-canvas",this.lat,this.lng,n),$("div.result-row").on("click",function(n){var i;return i=$(this),n.preventDefault(),$(window).width()<768?void(window.location=i.data("url")):t.data("currentBusiness")===i.data("id")?!0:($("div.result-row").removeClass("selected"),i.addClass("selected"),e.show(),$.ajax({url:i.data("url"),type:"GET"}).done(function(n){var a,r,o,l;return e.hide(),s.hide(),t.html(n),VARAA.initLayout3(),t.data("currentBusiness",i.data("id")),l="#js-map-"+i.data("id"),r=$(l).data("lat"),o=$(l).data("lng"),a=new GMaps({div:l,lat:r,lng:o,zoom:15}),a.addMarkers([{lat:r,lng:o}]),VARAA.applyCountdown(t.find("span.countdown"))}))})},t.prototype.extractMarkers=function(t){var e,s,n,i;for(s=[],n=0,i=t.length;i>n;n++)e=t[n],s.push({lat:e.lat,lng:e.lng,title:e.name});return s},t.prototype.showSingleBusiness=function(){return this.renderMap("#js-map-"+this.single.user_id,this.lat,this.lng,[{lat:this.lat,lng:this.lng}])},t}(),e=new t(VARAA.Search),e.run()}).call(this);