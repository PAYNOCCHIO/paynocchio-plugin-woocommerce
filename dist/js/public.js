(()=>{"use strict";(e=>{const o=o=>{e(`${o} > div.visible`).fadeOut("fast",(function(){e(`${o} > div:not(.visible)`).fadeIn("fast"),e(`${o} > div`).toggleClass("visible")}))},a=(o,a)=>{e(o.target).addClass("cfps-disabled"),e(`#${o.target.id} .cfps-spinner`).removeClass("cfps-hidden"),e.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_activation","ajax-activation-nonce":e("#ajax-activation-nonce").val()},success:function(o){console.log(JSON.parse(o.response.response)),o.success?a?document.location.href=a:document.location.reload():e("#response_message").text(JSON.parse(o.response.response).detail)}}).always((function(){e(`#${o.target.id} .cfps-spinner`).addClass("cfps-hidden"),e(o.target).removeClass("cfps-disabled")})).error((e=>console.log(e.response)))};e(document).ready((function(){const t="/checkout/?activated=1"===window.location.pathname,c=e("#paynocchio_activation_button");t||c.click((e=>a(e))),e(".form-toggle-a").click((()=>o("#login-signup-forms"))),e("form.checkout").on("change",'input[name="payment_method"]',(function(){e(document.body).trigger("update_checkout")})),e(document).on("updated_checkout",(function(){const t=e("#paynocchio_auth_block").length,c=e("#place_order"),n=e(".payment_box.payment_method_paynocchio").is(":hidden"),s=e(".payment_box.payment_method_paynocchio .paynocchio").length;c&&!n&&(s&&(c.addClass("cfps-disabled"),c.text("Please activate the Wallet first")),t&&(c.addClass("cfps-disabled"),c.text("Please login or register"))),e("#paynocchio_activation_button").click((e=>a(e))),e(".form-toggle-a").click((()=>o("#login-signup-forms"))),function(e,o=window.location.href){e=e.replace(/[\[\]]/g,"\\$&");const a=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(o);return a?a[2]?decodeURIComponent(a[2].replace(/\+/g," ")):"":null}("ans")&&e(".woocommerce-notices-wrapper:first-child").prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')}))}))})(jQuery)})();