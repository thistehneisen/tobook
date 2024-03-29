(function(){!function(e){"use strict";return window.showConsumerInfo=function(t,r){return null==r&&(r=0),e.ajax({url:VARAA.getRoute("consumers",{id:t,coreid:r}),dataType:"html",type:"GET"}).done(function(t){return e("#consumer-info").html(t)})},e(function(){var t,r,a,n,o;return o=function(t,r){var a;return a=e("#js-messageModal"),a.find(".modal-title").text(t),a.find(".modal-body").html(r),a.modal("show")},n=e("#consumer-table tbody tr"),r=e("#js-createConsumerForm"),n.on("click",function(){var t,r,a;return t=e(this),t.hasClass("selected")?void 0:(n.removeClass("selected"),t.addClass("selected"),r=t.data("consumerid"),a=t.data("coreconsumerid"),showConsumerInfo(r,a))}),r.bootstrapValidator({message:"This value is not valid",feedbackIcons:{valid:"glyphicon glyphicon-ok",invalid:"glyphicon glyphicon-remove",validating:"glyphicon glyphicon-refresh"},fields:{first_name:{validators:{notEmpty:{message:"First name is required"}}},last_name:{validators:{notEmpty:{message:"Last name is required"}}},email:{validators:{regexp:{regexp:VARAA.regex_email_validation,message:"Not valid email address"}}},phone:{validators:{notEmpty:{message:"Phone number is required"},numeric:{message:"Phone number must contain only numbers"}}}}}),e("#js-createConsumerModal").on("click","#js-cancelCreateConsumer",function(){return r.trigger("reset"),r.bootstrapValidator("resetForm",!0),e("#js-alert").addClass("hidden")}),r.on("success.form.bv",function(t){var r;return t.preventDefault(),r=e(this),e.ajax({url:r.prop("action"),dataType:"JSON",type:"post",data:r.serialize()}).done(function(e){return e.success===!0?window.location.reload():void 0})}),t=e("#consumer-info"),t.on("click","#js-back",function(){return t.html(""),t.find("tr").removeClass("selected")}),t.on("click","#js-addStamp",function(){var t,r;return t=e(this),r=t.data("offerid"),e.ajax({url:t.data("url"),dataType:"JSON",type:"PUT",data:{action:"addStamp",offerID:r}}).done(function(t){return o("Add Stamp",t.message),e("#js-currentStamp"+r).text(t.stamps)})}),t.on("click","#js-useOffer",function(){var t,r;return t=e(this),r=t.data("offerid"),e.ajax({url:t.data("url"),dataType:"JSON",type:"PUT",data:{action:"useOffer",offerID:r}}).done(function(t){return o("Use Offer",t.message),e("#js-currentStamp"+r).text(t.stamps)})}),a=e("#js-givePointModal"),a.on("show.bs.modal",function(t){return e(this).find(".modal-footer #js-confirmGivePoint").data("url",e(t.relatedTarget).data("url"))}),a.on("click","#js-confirmGivePoint",function(t){var r;return t.preventDefault(),r=e(this),e.ajax({url:r.data("url"),type:"PUT",data:{action:"addPoint",points:e("#points").val()}}).done(function(t){var r;return t.success?(a.modal("hide"),e("#js-currentPoint").text(t.points),o("Give Points",t.message)):(r="",e.each(t.errors,function(e,t){return r+="- "+t+"\n"}),o("Give Points",r))}),e("#js-givePointForm").trigger("reset")}),a.on("click","#js-cancelGivePoint",function(){return e("#js-givePointForm").trigger("reset")}),t.on("click","#js-useVoucher",function(){var t,r,n,i,s;return r=e(this),t=e("#js-currentPoint"),s=r.data("voucherid"),i=parseInt(r.data("required"),10),n=parseInt(t.text(),10),n>=i?e.ajax({url:r.data("url"),dataType:"JSON",type:"PUT",data:{action:"usePoint",voucherID:s}}).done(function(e){return a.modal("hide"),t.text(e.points),o("Use Points",e.message)}):o("Use Points","Not enough point")}),t.on("click","#js-writeCard",function(){var t,r;return t=e(this),r=t.data("consumerid"),external.SetCardWriteMode(!0),confirm("Put the card near the NFC card reader and press OK")?(t.prop("disabled",!0),external.WriteCard(r)===!0?o("Write to card","Successful!"):o("Write to card","Error writing to card!"),t.prop("disabled",!1),!1):(external.SetCardWriteMode(!1),!1)})})}(jQuery)}).call(this);