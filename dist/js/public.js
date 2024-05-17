(()=>{"use strict";(e=>{const s=s=>{e(`${s} > div.visible`).fadeOut("fast",(function(){e(`${s} > div:not(.visible)`).fadeIn("fast"),e(`${s} > div`).toggleClass("visible")}))},a=s=>{s.preventDefault(),e("#user_login").val()||e("#user_email").val()?(e("#login_messages").hide(),e.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_registration","ajax-registration-nonce":e("#ajax-registration-nonce").val(),login:e("#user_login").val(),email:e("#user_email").val()},success:function(s){console.log(s),s.success?e("#register_messages").show().addClass("success").html("Please check your email to confirm registration."):"Sorry, that email address is already used!"===s.data.message?e("#register_messages").show().removeClass("success").html("Sorry, this email address is already registered. Please <a style='color:#0c88b4' href='/account'>log in</a> or <a style='color:#0c88b4' href='/account'>restore password</a>. For any case please <a style='color:#0c88b4' href='mailto:support@kopybara.com'>contact support</a>.</p>"):e("#register_messages").show().removeClass("success").text(s.data.message)},error:e=>console.log(e)})):e("#register_messages").show().removeClass("success").text("Email and login are required")},o=s=>{s.preventDefault(),e("#user_login").val()||e("#user_pass").val()?(e("#login_messages").hide(),e.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_login",nonce:e("#loginsecurity").val(),password:e("#user_pass").val(),username:e("#user_name").val(),remember:e("#paynocchio_rememberme").val()},success:function(s){s=JSON.parse(s),console.log(s),1==s.loggedin?(e("#login_messages").show().addClass("success").text(s.message),document.location.href="/checkout/?step=2"):e("#login_messages").show().text(s.message)},error:e=>console.log(e)})):e("#login_messages").show().text("Login and password are required")},t=(s,a)=>{e(s.target).addClass("cfps-disabled"),e(`#${s.target.id} .cfps-spinner`).removeClass("cfps-hidden"),e.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_activation","ajax-activation-nonce":e("#ajax-activation-nonce").val()},success:function(s){s.success?(e("#response_message").text("Activation processing"),a?document.location.href=a:document.location.reload()):e("#response_message").text(JSON.parse(s.response.response).detail)}}).always((function(){e(`#${s.target.id} .cfps-spinner`).addClass("cfps-hidden"),e(s.target).removeClass("cfps-disabled")})).error((e=>console.log(e.response)))};function i(s){e("#user_pass").hasClass("shown")?(e("#user_pass").attr("type","password").removeClass("shown"),e("#show_password").attr("title","Show Password").find("span").removeClass("dashicons-hidden").addClass("dashicons-visibility")):(e("#user_pass").attr("type","text").addClass("shown"),e("#show_password").attr("title","Hide Password").find("span").addClass("dashicons-hidden").removeClass("dashicons-visibility"))}e(document).ready((function(){e("#wp-submit-registration").click((e=>a(e))),e("#paynocchio_wp-submit").click((e=>o(e))),e("#show_password").click((e=>i()));const n="/checkout/?activated=1"===window.location.pathname,c=e("#paynocchio_activation_button");n||c.click((e=>t(e))),e(".form-toggle-a").click((()=>s("#login-signup-forms"))),e("form.checkout").on("change",'input[name="payment_method"]',(function(){e(document.body).trigger("update_checkout")})),e(document).on("updated_checkout",(function(){e("#wp-submit-registration").click((e=>a(e))),e("#paynocchio_wp-submit").click((e=>o(e))),e("#show_password").click((e=>i()));const n=e("#paynocchio_auth_block").length,c=e("#place_order"),r=e(".payment_box.payment_method_paynocchio").is(":hidden"),l=e(".payment_box.payment_method_paynocchio .paynocchio").length;c&&!r&&(l&&(c.addClass("cfps-disabled"),c.text("Please activate the Wallet first")),n&&(c.addClass("cfps-disabled"),c.text("Please login or register"))),e("#paynocchio_activation_button").click((e=>t(e,"/checkout/?step=2"))),e(".form-toggle-a").click((()=>s("#login-signup-forms"))),function(e,s=window.location.href){e=e.replace(/[\[\]]/g,"\\$&");const a=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(s);return a?a[2]?decodeURIComponent(a[2].replace(/\+/g," ")):"":null}("ans")&&e(".woocommerce-notices-wrapper:first-child").prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')}))})),jQuery(document).ready((function(e){e("a#show_login").on("click",(function(s){e("body").prepend('<div class="login_overlay"></div>'),e("form#login").fadeIn(500),e("div.login_overlay, form#login a.close").on("click",(function(){e("div.login_overlay").remove(),e("form#login").hide()})),s.preventDefault()})),e("form#login").on("submit",(function(s){e("form#login p.status").show().text(ajax_login_object.loadingmessage),e.ajax({type:"POST",dataType:"json",url:ajax_login_object.ajaxurl,data:{action:"ajaxlogin",username:e("form#login #username").val(),password:e("form#login #password").val(),security:e("form#login #security").val()},success:function(s){e("form#login p.status").text(s.message),1==s.loggedin&&(document.location.href=ajax_login_object.redirecturl)}}),s.preventDefault()}))}))})(jQuery)})();