(()=>{var e={177:()=>{var e;(e=jQuery)(document).ready((function(){e(".top-up-variants > a").click((function(){let t=e(this).get(0).id.replace("variant_","");e("#top_up_amount").val(t)})),e(".toggle-autodeposit").click((function(){e(this).toggleClass("checked"),e(this).hasClass("checked")?e("input#autodeposit").attr("value","1"):e("input#autodeposit").attr("value","0")})),e(".card-var").click((function(){e(".card-variants").toggleClass("clicked"),e(".clicked .card-var").click((function(){e(".card-var").removeClass("current-card"),e(this).addClass("current-card"),e("#source-card").attr("value",e(this).attr("data-pan"))}))}))}))}},t={};function o(s){var n=t[s];if(void 0!==n)return n.exports;var a=t[s]={exports:{}};return e[s](a,a.exports,o),a.exports}(()=>{"use strict";class e{constructor(e){this.options=Object.assign({element:null,effect:"zoom",state:"closed",size:"medium",content:null,footer:null,header:null,title:null},e),null==this.options.element&&(this.options.element=document.createElement("div"),this.options.element.classList.add("modal"),this.options.element.innerHTML='\n                <div class="container">\n                    <div class="header">\n                        <button class="close">&times;</button> \n                    </div>\n                    <div class="content"></div>\n                    <div class="footer">\n                        <button class="close">Close</button>\n                    </div>\n                </div>                        \n            ',document.body.appendChild(this.options.element)),this.options.element.querySelector(".container").classList.remove("zoom","slide"),this.options.element.querySelector(".container").classList.add(this.options.effect),null!=this.options.header&&(this.header=this.options.header),null!=this.options.content&&(this.content=this.options.content),null!=this.options.footer&&(this.footer=this.options.footer),null!=this.options.title&&(this.title=this.options.title),this.size=this.options.size,this._eventHandlers()}open(){this.options.state="open",this.options.element.style.display="flex",this.options.element.getBoundingClientRect(),this.options.element.classList.add("open"),document.body.classList.add("paynocchio-modal-open"),this.options.onOpen&&this.options.onOpen(this)}close(){1==document.querySelectorAll(".modal.open").length&&document.body.classList.remove("paynocchio-modal-open"),this.options.state="closed",this.options.element.classList.remove("open"),this.options.element.style.display="none",this.options.onClose&&this.options.onClose(this)}get state(){return this.options.state}get effect(){return this.options.effect}set effect(e){this.options.effect=e,this.options.element.querySelector(".container").classList.remove("zoom","slide"),this.options.element.querySelector(".container").classList.add(e)}get size(){return this.options.size}set size(e){this.options.size=e,this.options.element.classList.remove("small","large","medium","full"),this.options.element.classList.add(e)}get content(){return this.options.element.querySelector(".content").innerHTML}get contentElement(){return this.options.element.querySelector(".content")}set content(e){e?(this.options.element.querySelector(".content")||this.options.element.querySelector(".container").insertAdjacentHTML("afterbegin",'<div class="content"></div>'),this.options.element.querySelector(".content").innerHTML=e):this.options.element.querySelector(".content").remove()}get header(){return this.options.element.querySelector(".header").innerHTML}get headerElement(){return this.options.element.querySelector(".header")}set header(e){e?(this.options.element.querySelector(".header")||this.options.element.querySelector(".container").insertAdjacentHTML("afterbegin",'<div class="header"></div>'),this.options.element.querySelector(".header").innerHTML=e):this.options.element.querySelector(".header").remove()}get title(){return this.options.element.querySelector(".header .title")?this.options.element.querySelector(".header .title").innerHTML:null}set title(e){this.options.element.querySelector(".header .title")||this.options.element.querySelector(".header").insertAdjacentHTML("afterbegin",'<h3 class="title"></h3>'),this.options.element.querySelector(".header .title").innerHTML=e}get footer(){return this.options.element.querySelector(".footer").innerHTML}get footerElement(){return this.options.element.querySelector(".footer")}set footer(e){e?(this.options.element.querySelector(".footer")||this.options.element.querySelector(".container").insertAdjacentHTML("beforeend",'<div class="footer"></div>'),this.options.element.querySelector(".footer").innerHTML=e):this.options.element.querySelector(".footer").remove()}_eventHandlers(){this.options.element.querySelectorAll(".close").forEach((e=>{e.onclick=e=>{e.preventDefault(),this.close()}}))}static initElements(){document.querySelectorAll("[data-modal]").forEach((t=>{t.addEventListener("click",(o=>{o.preventDefault();let s=document.querySelector(t.dataset.modal),n=new e({element:s});for(let e in s.dataset)n[e]&&(n[e]=s.dataset[e]);n.open()}))}))}static confirm(t,o,s){let n=new e({content:t,header:"",footer:'<button class="success">OK</button><button class="cancel alt">Cancel</button>'});n.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),o&&o(),n.close()},n.footerElement.querySelector(".cancel").onclick=e=>{e.preventDefault(),s&&s(),n.close()},n.open()}static alert(t,o){let s=new e({content:t,header:"",footer:'<button class="success">OK</button>'});s.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),o&&o(),s.close()},s.open()}static prompt(t,o,s,n){let a=new e({header:"",footer:'<button class="success">OK</button><button class="cancel alt">Cancel</button>'});a.content=t+`<div class="prompt-input"><input type="text" value="${o}" placeholder="Enter your text..."></div>`,a.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),s&&s(a.contentElement.querySelector(".prompt-input input").value),a.close()},a.footerElement.querySelector(".cancel").onclick=e=>{e.preventDefault(),n&&n(a.contentElement.querySelector(".prompt-input input").value),a.close()},a.open()}}o(177),(t=>{const o=e=>{t(e.target).addClass("cfps-disabled"),t(`#${e.target.id} .cfps-spinner`).removeClass("cfps-hidden"),t.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_top_up","ajax-top-up-nonce":t("#ajax-top-up-nonce").val(),amount:t("#top_up_amount").val()},success:function(e){200===e.response.status_code&&(t(".topUpModal .message").text("Success!"),a(),i(),t(".topUpModal").delay(1e3).fadeOut("fast"),t("body").removeClass("paynocchio-modal-open"))}}).error((e=>alert(e.response.response))).always((function(){t(`#${e.target.id} .cfps-spinner`).addClass("cfps-hidden"),t(e.target).removeClass("cfps-disabled"),t(".topUpModal .message").text("")}))},s=(e,o)=>{t(e.target).addClass("cfps-disabled"),t(".paynocchio-profile-actions .cfps-spinner").removeClass("cfps-hidden"),t.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_set_status","ajax-status-nonce":t("#ajax-status-nonce").val(),status:o},success:()=>t(window.location.reload())}).error((e=>alert(e))).always((()=>t(".paynocchio-profile-actions .cfps-spinner").addClass("cfps-hidden")))},n=e=>{t(e.target).addClass("cfps-disabled"),t(`#${e.target.id} .cfps-spinner`).removeClass("cfps-hidden");const o=parseFloat(t("#withdraw_amount").val());parseFloat(t("#witdrawForm .paynocchio-balance-value").text())<o?(t(".withdrawModal .message").text("Sorry, can't do ;)"),t(e.target).removeClass("cfps-disabled"),t(`#${e.target.id} .cfps-spinner`).addClass("cfps-hidden")):t.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_withdraw","ajax-withdraw-nonce":t("#ajax-withdraw-nonce").val(),amount:parseFloat(t("#withdraw_amount").val())},success:function(e){200===e.response.status_code&&(t("#withdraw_amount").val(""),t(".withdrawModal .message").text("Success!"),a(),i(),t(".withdrawModal").delay(1e3).fadeOut("fast"),t("body").removeClass("paynocchio-modal-open"))}}).error((e=>console.log(e))).always((function(){t(`#${e.target.id} .cfps-spinner`).addClass("cfps-hidden"),t(e.target).removeClass("cfps-disabled")}))};function a(){t.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_check_balance"},success:function(e){!function(e,o){let s=parseFloat(t(".paynocchio-balance-value").first().text()),n=parseFloat(e),a=parseFloat(t(".paynocchio-bonus-value").first().text()),i=parseFloat(o);function c(e,o,s){let n=t(".paynocchio-"+e+"-value");if(o===s)return;let a=o,i=(s-o)/250,c=0;var l=setInterval((function(){a+=i;let e=parseFloat(a).toFixed(2);n.html(e),c++,250==c&&clearInterval(l)}),4)}c("balance",s,n),c("bonus",a,i)}(e.response.balance,e.response.bonuses)}}).error((e=>console.log(e)))}function i(){const e=t("#place_order"),o=t(".payment_box.payment_method_paynocchio").is(":hidden");e&&!o&&t(document.body).trigger("update_checkout")}setInterval((()=>a()),5e3),t(document).ready((function(){e.initElements();const c=t("#top_up_button"),l=t("#top_up_mini_form_button"),r=t("#withdraw_button"),d=t("#suspend_button"),p=t("#reactivate_button"),u=t("#block_button"),h=t("#delete_button");c.click((e=>o(e))),l.click((e=>(e=>{t(e.target).addClass("cfps-disabled"),t(`#${e.target.id} .cfps-spinner`).removeClass("cfps-hidden"),t(`#${e.target.id} .cfps-check`).addClass("cfps-hidden"),t(`#${e.target.id} .cfps-cross`).addClass("cfps-hidden"),t.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_top_up","ajax-top-up-nonce":t("#ajax-top-up-nonce-mini-form").val(),amount:t("#top_up_amount_mini_form").val()},success:function(o){200===o.response.status_code?(t(`#${e.target.id} .cfps-check`).removeClass("cfps-hidden"),a(),i(),t(".topup_mini_form").delay(2e3).fadeOut("fast",(function(){t("#show_mini_modal").css("transform","rotate(0deg)"),t("#top_up_amount_mini_form").val(""),t(`#${e.target.id} .cfps-check`).addClass("cfps-hidden")}))):alert(o.response.response)}}).error((function(){t(`#${e.target.id} .cfps-cross`).removeClass("cfps-hidden")})).always((function(){t(`#${e.target.id} .cfps-spinner`).addClass("cfps-hidden"),t(e.target).removeClass("cfps-disabled")}))})(e))),r.click((e=>n(e))),d.click((e=>s(e,"SUSPEND"))),p.click((e=>s(e,"ACTIVE"))),u.click((e=>s(e,"BLOCKED"))),h.click((e=>(e=>{t(e.target).addClass("cfps-disabled"),t(`#${e.target.id} .cfps-spinner`).removeClass("cfps-hidden"),t.ajax({url:paynocchio_object.ajaxurl,type:"POST",data:{action:"paynocchio_ajax_delete_wallet","ajax-delete-nonce":t("#ajax-delete-nonce").val()},success:e=>t(window.location.reload())}).error((e=>console.log(e)))})(e))),t("a.tab-switcher").click((function(){let e=t(this),o=e.get(0).id;o=o.replace("_toggle","");let s=t("#paynocchio_"+o+"_body");s.hasClass("visible")||(t(".paynocchio_tab_switchers a").removeClass("choosen"),e.addClass("choosen"),s.siblings(".paynocchio-tab-body").removeClass("visible").fadeOut("fast",(function(){s.fadeIn("fast").addClass("visible")})))})),t("a.card-toggle").click((()=>{var e;t((e=".paynocchio-card-container")+" > div.visible").fadeOut("fast",(function(){t(`${e} > div:not(.visible)`).fadeIn("fast"),t(`${e} > div`).toggleClass("visible")}))})),t("form.checkout").on("change",'input[name="payment_method"]',(function(){t(document.body).trigger("update_checkout")})),t(document).on("updated_checkout",(function(){const s=t("#top_up_button"),i=t("#withdraw_button");s.click((e=>o(e))),i.click((e=>n(e))),a(),e.initElements(),function(e,t=window.location.href){e=e.replace(/[\[\]]/g,"\\$&");const o=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(t);return o?o[2]?decodeURIComponent(o[2].replace(/\+/g," ")):"":null}("ans")&&t(".woocommerce-notices-wrapper:first-child").prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>');const c=t("#bonuses-value"),l=t("#bonuses-input");function r(){const e=t("#place_order"),o=t(".payment_box.payment_method_paynocchio").is(":hidden");if(e&&!o){const o=parseFloat(t(".paynocchio-card-simulator .paynocchio-balance-value").text()),s=parseFloat(t(".paynocchio-card-simulator .paynocchio-bonus-value").text()),n=parseFloat(t(".woocommerce-Price-amount").text().replace("$","")),a=parseFloat(t("#bonuses-value").val());o+s<n?(e.addClass("cfps-disabled"),e.text("Please TopUp your Wallet")):o+s>=n&&a+o<n?(e.addClass("cfps-disabled"),e.text("Please TopUp your Wallet or use your Bonuses")):(e.removeClass("cfps-disabled"),e.text("Place order"))}}c.val(l.val()),l.on("change",(function(){c.val(l.val())})),c.on("change",(function(){l.val(c.val())})),t("input[type=range]").on("input",(function(){t(this).trigger("change")})),t(".top-up-variants > a").click((function(){let e=t(this).get(0).id.replace("variant_","");t("#top_up_amount").val(e)})),t(".toggle-autodeposit").click((function(){t(this).toggleClass("checked"),t(this).hasClass("checked")?t("input#autodeposit").attr("value","1"):t("input#autodeposit").attr("value","0")})),t(".card-var").click((function(){t(".card-variants").toggleClass("clicked"),t(".clicked .card-var").click((function(){t(".card-var").removeClass("current-card"),t(this).addClass("current-card"),t("#source-card").attr("value",t(this).attr("data-pan"))}))})),r(),t("#bonuses-value").on("change",(function(){r()})),t('input[type="range"].slider-progress').each((function(){t(this).css("--value",t(this).val()),t(this).css("--min",""==t(this).attr("min")?"0":t(this).attr("min")),t(this).css("--max",""==t(this).attr("max")?"0":t(this).attr("max")),t(this).on("input",(function(){t(this).css("--value",t(this).val()),t("#bonuses-value").trigger("change")}))}))})),t("#show_mini_modal").on("click",(function(){t(".topup_mini_form").toggle(),t(this).toggleClass("active"),t(this).hasClass("active")?t(this).css("transform","rotate(45deg)"):t(this).css("transform","rotate(0deg)")}))}))})(jQuery)})()})();