(()=>{"use strict";class e{constructor(e){this.options=Object.assign({element:null,effect:"zoom",state:"closed",size:"medium",content:null,footer:null,header:null,title:null},e),null==this.options.element&&(this.options.element=document.createElement("div"),this.options.element.classList.add("modal"),this.options.element.innerHTML='\n                <div class="container">\n                    <div class="header">\n                        <button class="close">&times;</button> \n                    </div>\n                    <div class="content"></div>\n                    <div class="footer">\n                        <button class="close">Close</button>\n                    </div>\n                </div>                        \n            ',document.body.appendChild(this.options.element)),this.options.element.querySelector(".container").classList.remove("zoom","slide"),this.options.element.querySelector(".container").classList.add(this.options.effect),null!=this.options.header&&(this.header=this.options.header),null!=this.options.content&&(this.content=this.options.content),null!=this.options.footer&&(this.footer=this.options.footer),null!=this.options.title&&(this.title=this.options.title),this.size=this.options.size,this._eventHandlers()}open(){this.options.state="open",this.options.element.style.display="flex",this.options.element.getBoundingClientRect(),this.options.element.classList.add("open"),document.body.classList.add("paynocchio-modal-open"),this.options.element.parentNode!==document.body&&document.body.append(this.options.element),this.options.onOpen&&this.options.onOpen(this)}close(){1==document.querySelectorAll(".modal.open").length&&document.body.classList.remove("paynocchio-modal-open"),this.options.state="closed",this.options.element.classList.remove("open"),this.options.element.style.display="none",this.options.onClose&&this.options.onClose(this)}get state(){return this.options.state}get effect(){return this.options.effect}set effect(e){this.options.effect=e,this.options.element.querySelector(".container").classList.remove("zoom","slide"),this.options.element.querySelector(".container").classList.add(e)}get size(){return this.options.size}set size(e){this.options.size=e,this.options.element.classList.remove("small","large","medium","full"),this.options.element.classList.add(e)}get content(){return this.options.element.querySelector(".content").innerHTML}get contentElement(){return this.options.element.querySelector(".content")}set content(e){e?(this.options.element.querySelector(".content")||this.options.element.querySelector(".container").insertAdjacentHTML("afterbegin",'<div class="content"></div>'),this.options.element.querySelector(".content").innerHTML=e):this.options.element.querySelector(".content").remove()}get header(){return this.options.element.querySelector(".header").innerHTML}get headerElement(){return this.options.element.querySelector(".header")}set header(e){e?(this.options.element.querySelector(".header")||this.options.element.querySelector(".container").insertAdjacentHTML("afterbegin",'<div class="header"></div>'),this.options.element.querySelector(".header").innerHTML=e):this.options.element.querySelector(".header").remove()}get title(){return this.options.element.querySelector(".header .title")?this.options.element.querySelector(".header .title").innerHTML:null}set title(e){this.options.element.querySelector(".header .title")||this.options.element.querySelector(".header").insertAdjacentHTML("afterbegin",'<h3 class="title"></h3>'),this.options.element.querySelector(".header .title").innerHTML=e}get footer(){return this.options.element.querySelector(".footer").innerHTML}get footerElement(){return this.options.element.querySelector(".footer")}set footer(e){e?(this.options.element.querySelector(".footer")||this.options.element.querySelector(".container").insertAdjacentHTML("beforeend",'<div class="footer"></div>'),this.options.element.querySelector(".footer").innerHTML=e):this.options.element.querySelector(".footer").remove()}_eventHandlers(){this.options.element.querySelectorAll(".close").forEach((e=>{e.onclick=e=>{e.preventDefault(),this.close()}}))}static initElements(){document.querySelectorAll("[data-modal]").forEach((t=>{t.addEventListener("click",(o=>{o.preventDefault();let n=document.querySelector(t.dataset.modal),s=new e({element:n});for(let e in n.dataset)s[e]&&(s[e]=n.dataset[e]);s.open()}))}))}static confirm(t,o,n){let s=new e({content:t,header:"",footer:'<button class="success">OK</button><button class="cancel alt">Cancel</button>'});s.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),o&&o(),s.close()},s.footerElement.querySelector(".cancel").onclick=e=>{e.preventDefault(),n&&n(),s.close()},s.open()}static alert(t,o){let n=new e({content:t,header:"",footer:'<button class="success">OK</button>'});n.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),o&&o(),n.close()},n.open()}static prompt(t,o,n,s){let a=new e({header:"",footer:'<button class="success">OK</button><button class="cancel alt">Cancel</button>'});a.content=t+`<div class="prompt-input"><input type="text" value="${o}" placeholder="Enter your text..."></div>`,a.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),n&&n(a.contentElement.querySelector(".prompt-input input").value),a.close()},a.footerElement.querySelector(".cancel").onclick=e=>{e.preventDefault(),s&&s(a.contentElement.querySelector(".prompt-input input").value),a.close()},a.open()}}function t(e,t){const o=document.getElementById("top_up_amount"),n=document.getElementById("bonusesCounter");o&&(o.setAttribute("data-value",e),n.innerText=(e*t).toFixed())}var o;(o=jQuery)(document).ready((function(){o(".top-up-variants > a").click((function(){let e=o(this).get(0).id.replace("variant_","");o("#top_up_amount").val(e),t(e,.1)})),o(".toggle-autodeposit").click((function(){o(this).toggleClass("checked"),o(this).hasClass("checked")?o("input#autodeposit").attr("value","1"):o("input#autodeposit").attr("value","0")})),o(".card-var").click((function(){o(".card-variants").toggleClass("clicked"),o(".clicked .card-var").click((function(){o(".card-var").removeClass("current-card"),o(this).addClass("current-card"),o("#source-card").attr("value",o(this).attr("data-pan"))}))}))})),(o=>{const n=e=>{o(e.target).addClass("cfps-disabled"),o(`#${e.target.id} .cfps-spinner`).removeClass("cfps-hidden"),o.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_top_up","ajax-top-up-nonce":o("#ajax-top-up-nonce").val(),amount:o("#top_up_amount").val()},success:function(e){200===e.response.status_code?(o(".topUpModal .message").text("Success!"),i(),c(),o(".topUpModal").delay(1e3).fadeOut("fast"),o("body").removeClass("paynocchio-modal-open"),o("body").hasClass("woocommerce-checkout")&&o(".topUpModal").remove()):o(".topUpModal .message").html("An error occurred. Please reload page and try again!")}}).error((e=>alert(e.response.response))).always((function(){o(`#${e.target.id} .cfps-spinner`).addClass("cfps-hidden"),o(e.target).removeClass("cfps-disabled")}))},s=(e,t)=>{o(e.target).addClass("cfps-disabled"),o(".paynocchio-profile-actions .cfps-spinner").removeClass("cfps-hidden"),o.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_set_status","ajax-status-nonce":o("#ajax-status-nonce").val(),status:t},success:()=>o(window.location.reload())}).error((e=>alert(e))).always((()=>o(".paynocchio-profile-actions .cfps-spinner").addClass("cfps-hidden")))},a=e=>{o(e.target).addClass("cfps-disabled"),o(`#${e.target.id} .cfps-spinner`).removeClass("cfps-hidden");const t=parseFloat(o("#withdraw_amount").val());parseFloat(o("#witdrawForm .paynocchio-balance-value").text())<t?(o(".withdrawModal .message").text("Sorry, can't do ;)"),o(e.target).removeClass("cfps-disabled"),o(`#${e.target.id} .cfps-spinner`).addClass("cfps-hidden")):o.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_withdraw","ajax-withdraw-nonce":o("#ajax-withdraw-nonce").val(),amount:parseFloat(o("#withdraw_amount").val())},success:function(e){200===e.response.status_code?(o("#withdraw_amount").val(""),o(".withdrawModal .message").text("Success!"),i(),c(),o(".withdrawModal").delay(1e3).fadeOut("fast"),o("body").removeClass("paynocchio-modal-open")):o(".withdrawModal .message").text("An error occurred. Please reload page and try again!")}}).error((e=>console.log(e))).always((function(){o(`#${e.target.id} .cfps-spinner`).addClass("cfps-hidden"),o(e.target).removeClass("cfps-disabled")}))};function i(){o.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_check_balance"},success:function(e){!function(e,t){o(".paynocchio-balance-value").attr("data-balance",e),o(".paynocchio-bonus-value").attr("data-bonus",t);let n=parseFloat(o(".paynocchio-balance-value").first().text()),s=parseFloat(e),a=parseFloat(o(".paynocchio-bonus-value").first().text()),i=parseFloat(t);function c(e,t,n){let s=o(".paynocchio-"+e+"-value");if(t===n)return;let a=t,i=(n-t)/250,c=0;var l=setInterval((function(){a+=i;let e=parseFloat(a).toFixed(2);s.html(e),c++,250==c&&(clearInterval(l),s.html(n))}),4)}c("balance",n,s),c("bonus",a,i)}(e.response.balance,e.response.bonuses)}}).error((e=>console.log(e)))}function c(){const e=o("#place_order"),t=o(".payment_box.payment_method_paynocchio").is(":hidden");e&&!t&&o(document.body).trigger("update_checkout")}setInterval((()=>i()),1e3),o(document).ready((function(){e.initElements();const l=o("#top_up_button"),r=o("#top_up_mini_form_button"),d=o("#withdraw_button"),p=o("#suspend_button"),u=o("#reactivate_button"),h=o("#block_button"),m=o("#delete_button");l.click((e=>n(e))),r.click((e=>(e=>{o(".topup_mini_form .cfps-spinner").removeClass("cfps-hidden"),o(".topup_mini_form .cfps-check").addClass("cfps-hidden"),o(".topup_mini_form .cfps-cross").addClass("cfps-hidden"),o(e.target).addClass("cfps-disabled"),o.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_top_up","ajax-top-up-nonce":o("#ajax-top-up-nonce-mini-form").val(),amount:o("#top_up_amount_mini_form").val()},success:function(t){200===t.response.status_code?(o(".topup_mini_form .cfps-check").removeClass("cfps-hidden"),i(),c(),o(".topup_mini_form").delay(2e3).fadeOut("fast",(function(){o("#show_mini_modal").css("transform","rotate(0deg)"),o("#top_up_amount_mini_form").val(""),o(".topup_mini_form .cfps-check").addClass("cfps-hidden"),o(e.target).removeClass("cfps-disabled")}))):alert(t.response.response)}}).error((function(){o(".topup_mini_form .cfps-cross").removeClass("cfps-hidden")})).always((function(){o(".topup_mini_form .cfps-spinner").addClass("cfps-hidden"),o(e.target).removeClass("cfps-disabled")}))})(e))),d.click((e=>a(e))),p.click((e=>s(e,"SUSPEND"))),u.click((e=>s(e,"ACTIVE"))),h.click((e=>s(e,"BLOCKED"))),m.click((e=>(e=>{o(e.target).addClass("cfps-disabled"),o(`#${e.target.id} .cfps-spinner`).removeClass("cfps-hidden"),o.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_delete_wallet","ajax-delete-nonce":o("#ajax-delete-nonce").val()},success:e=>o(window.location.reload())}).error((e=>console.log(e)))})(e))),o("a.card-toggle").click((()=>{var e;o((e=".paynocchio-card-container")+" > div.visible").fadeOut("fast",(function(){o(`${e} > div:not(.visible)`).fadeIn("fast"),o(`${e} > div`).toggleClass("visible")}))})),o("form.checkout").on("change",'input[name="payment_method"]',(function(){o(document.body).trigger("update_checkout")})),o("#top_up_amount").keyup((e=>{t(e.target.value,.1)})),o(document).on("updated_checkout",(function(){e.initElements();const s=o("#top_up_button"),c=o("#withdraw_button");s.click((e=>n(e))),c.click((e=>a(e))),i(),function(e,t=window.location.href){e=e.replace(/[\[\]]/g,"\\$&");const o=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(t);return o?o[2]?decodeURIComponent(o[2].replace(/\+/g," ")):"":null}("ans")&&o(".woocommerce-notices-wrapper:first-child").prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>');const l=o("#bonuses-value"),r=o("#bonuses-input");function d(){const e=o("#place_order"),t=o(".payment_box.payment_method_paynocchio").is(":hidden");if(e&&!t){const t=parseFloat(o(".paynocchio-balance-value").first().attr("data-balance")),n=parseFloat(o(".paynocchio-bonus-value").first().attr("data-bonus")),s=parseFloat(o(".woocommerce-Price-amount").text().replace("$","")),a=parseFloat(o("#bonuses-value").val());t+n<s?(e.addClass("cfps-disabled"),e.text("Please TopUp your Wallet")):t+n>=s&&a+t<s?(e.addClass("cfps-disabled"),e.text("Please TopUp your Wallet or use your Bonuses")):(e.removeClass("cfps-disabled"),e.text("Place order"))}}l.val(r.val()),r.on("change",(function(){l.val(r.val())})),l.on("change",(function(){r.val(l.val())})),o("input[type=range]").on("input",(function(){o(this).trigger("change")})),o(".top-up-variants > a").click((function(){let e=o(this).get(0).id.replace("variant_","");o("#top_up_amount").val(e),t(e,.1)})),o(".toggle-autodeposit").click((function(){o(this).toggleClass("checked"),o(this).hasClass("checked")?o("input#autodeposit").attr("value","1"):o("input#autodeposit").attr("value","0")})),o(".card-var").click((function(){o(".card-variants").toggleClass("clicked"),o(".clicked .card-var").click((function(){o(".card-var").removeClass("current-card"),o(this).addClass("current-card"),o("#source-card").attr("value",o(this).attr("data-pan"))}))})),d(),o("#bonuses-value").on("change",(function(){d()})),o('input[type="range"].slider-progress').each((function(){o(this).css("--value",o(this).val()),o(this).css("--min",""==o(this).attr("min")?"0":o(this).attr("min")),o(this).css("--max",""==o(this).attr("max")?"0":o(this).attr("max")),o(this).on("input",(function(){o(this).css("--value",o(this).val()),o("#bonuses-value").trigger("change")}))})),o("#top_up_amount").keyup((e=>{t(e.target.value,.1)}))})),o("#show_mini_modal").on("click",(function(){o(".topup_mini_form").toggle(),o(this).toggleClass("active"),o(this).hasClass("active")?o(this).css("transform","rotate(45deg)"):o(this).css("transform","rotate(0deg)")}))}))})(jQuery)})();