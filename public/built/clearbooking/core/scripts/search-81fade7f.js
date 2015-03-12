(function(){var e,t;e=function(){function e(e){this.businesses=e.businesses,this.lat=e.lat,this.lng=e.lng,null!=e.categoryId&&(this.categoryId=e.categoryId),null!=e.serviceId&&(this.serviceId=e.serviceId),null!=e.employeeId&&(this.employeeId=e.employeeId),null!=e.time&&(this.time=e.time)}return e.prototype.run=function(){return this.categoryId&&this.serviceId&&this.selectBookingForm(),this.showBusinesses()},e.prototype.selectBookingForm=function(){return $("input:radio[name=category_id][value="+this.categoryId+"], input:radio[name=service_id][value="+this.serviceId+"]").click(),$("input[name=service_id]").on("afterSelect",function(){return $("input:radio[name=employee_id][value="+this.employeeId+"]").click()}),$("#as-step-3").on("afterShow",function(){return $("button[data-time="+this.time+"]").click()})},e.prototype.renderMap=function(e,t,n,i){var s,a,r,o;if(s=new GMaps({div:e,lat:t,lng:n,zoom:8}),null!=i)for(a=0,r=i.length;r>a;a++)o=i[a],s.addMarkers(i);return s},e.prototype.showBusinesses=function(){var e,t,n,i;return t=$("#js-loading"),e=$("#js-business-content"),n=$("#map-canvas"),n.show(),i=this.extractMarkers(this.businesses),this.renderMap("#map-canvas",this.lat,this.lng,i),$("div.result-row").on("click",function(i){var s;return s=$(this),i.preventDefault(),$(window).width()<768?void(window.location=s.data("url")):e.data("currentBusiness")===s.data("id")?!0:($("div.result-row").removeClass("selected"),s.addClass("selected"),t.show(),$.ajax({url:s.data("url"),type:"GET"}).done(function(i){var a,r,o,l;return t.hide(),n.hide(),e.html(i),VARAA.initLayout3(),e.data("currentBusiness",s.data("id")),l="#js-map-"+s.data("id"),r=$(l).data("lat"),o=$(l).data("lng"),a=new GMaps({div:l,lat:r,lng:o,zoom:15}),a.addMarkers([{lat:r,lng:o}])}))})},e.prototype.extractMarkers=function(e){var t,n,i,s;for(s=[],n=0,i=e.length;i>n;n++)t=e[n],s.push({lat:t.lat,lng:t.lng,title:t.name});return s},e}(),t=new e(VARAA.Search),t.run()}).call(this);