(()=>{"use strict";(e=>{const a=a=>{e(`${a} > div.visible`).fadeOut("fast",(function(){e(`${a} > div:not(.visible)`).fadeIn("fast"),e(`${a} > div`).toggleClass("visible")}))},o=a=>{a.preventDefault(),e("#user_login").val()||e("#user_email").val()?e.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_registration","ajax-registration-nonce":e("#ajax-registration-nonce").val(),login:e("#user_login").val(),email:e("#user_email").val()},success:function(a){console.log(a),a.success?e("#register_messages").show().addClass("success").html("Please check your email to confirm registration."):"Sorry, that email address is already used!"===a.data.message?e("#register_messages").show().removeClass("success").html("Sorry, this email address is already registered. Please <a style='color:#0c88b4' href='/account'>log in</a> or <a style='color:#0c88b4' href='/account'>restore password</a>. For any case please <a style='color:#0c88b4' href='mailto:support@kopybara.com'>contact support</a>.</p>"):e("#register_messages").show().removeClass("success").text(a.data.message)},error:e=>console.log(e)}):e("#register_messages").text("<p>Email and login are required</p>")},s=a=>{a.preventDefault(),e("#user_login").val()||e("#user_pass").val()?e.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_login",nonce:e("#loginsecurity").val(),password:e("#user_pass").val(),username:e("#user_name").val(),remember:e("#paynocchio_rememberme").val()},success:function(a){a=JSON.parse(a),console.log(a),1==a.loggedin?(e("#login_messages").show().addClass("success").text(a.message),document.location.href="/checkout/?step=2"):e("#login_messages").show().text(a.message)},error:e=>console.log(e)}):e("#login_messages").text("<p>Login and password are required</p>")},t=(a,o)=>{e(a.target).addClass("cfps-disabled"),e(`#${a.target.id} .cfps-spinner`).removeClass("cfps-hidden"),e.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_activation","ajax-activation-nonce":e("#ajax-activation-nonce").val()},success:function(a){a.success?(e("#response_message").text("Activation processing"),o?document.location.href=o:document.location.reload()):e("#response_message").text(JSON.parse(a.response.response).detail)}}).always((function(){e(`#${a.target.id} .cfps-spinner`).addClass("cfps-hidden"),e(a.target).removeClass("cfps-disabled")})).error((e=>console.log(e.response)))};e(document).ready((function(){e("#wp-submit-registration").click((e=>o(e))),e("#paynocchio_wp-submit").click((e=>s(e)));const n="/checkout/?activated=1"===window.location.pathname,c=e("#paynocchio_activation_button");n||c.click((e=>t(e))),e(".form-toggle-a").click((()=>a("#login-signup-forms"))),e("form.checkout").on("change",'input[name="payment_method"]',(function(){e(document.body).trigger("update_checkout")})),e(document).on("updated_checkout",(function(){e("#wp-submit-registration").click((e=>o(e))),e("#paynocchio_wp-submit").click((e=>s(e)));const n=e("#paynocchio_auth_block").length,c=e("#place_order"),i=e(".payment_box.payment_method_paynocchio").is(":hidden"),r=e(".payment_box.payment_method_paynocchio .paynocchio").length;c&&!i&&(r&&(c.addClass("cfps-disabled"),c.text("Please activate the Wallet first")),n&&(c.addClass("cfps-disabled"),c.text("Please login or register"))),e("#paynocchio_activation_button").click((e=>t(e,"/checkout/?step=2"))),e(".form-toggle-a").click((()=>a("#login-signup-forms"))),function(e,a=window.location.href){e=e.replace(/[\[\]]/g,"\\$&");const o=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(a);return o?o[2]?decodeURIComponent(o[2].replace(/\+/g," ")):"":null}("ans")&&e(".woocommerce-notices-wrapper:first-child").prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')}))})),jQuery(document).ready((function(e){e("a#show_login").on("click",(function(a){e("body").prepend('<div class="login_overlay"></div>'),e("form#login").fadeIn(500),e("div.login_overlay, form#login a.close").on("click",(function(){e("div.login_overlay").remove(),e("form#login").hide()})),a.preventDefault()})),e("form#login").on("submit",(function(a){e("form#login p.status").show().text(ajax_login_object.loadingmessage),e.ajax({type:"POST",dataType:"json",url:ajax_login_object.ajaxurl,data:{action:"ajaxlogin",username:e("form#login #username").val(),password:e("form#login #password").val(),security:e("form#login #security").val()},success:function(a){e("form#login p.status").text(a.message),1==a.loggedin&&(document.location.href=ajax_login_object.redirecturl)}}),a.preventDefault()}))}))})(jQuery)})();