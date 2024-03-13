/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/modal.js":
/*!*****************************!*\
  !*** ./assets/src/modal.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ Modal)\n/* harmony export */ });\nclass Modal {\n\n    constructor(options) {\n        let defaults = { element: null, effect: 'zoom', state: 'closed', size: 'medium', content: null, footer: null, header: null, title: null };\n        this.options = Object.assign(defaults, options);\n        if (this.options.element == null) {\n            this.options.element = document.createElement('div');\n            this.options.element.classList.add('modal');\n            this.options.element.innerHTML = `\n                <div class=\"container\">\n                    <div class=\"header\">\n                        <button class=\"close\">&times;</button> \n                    </div>\n                    <div class=\"content\"></div>\n                    <div class=\"footer\">\n                        <button class=\"close\">Close</button>\n                    </div>\n                </div>                        \n            `;\n            document.body.appendChild(this.options.element);\n        }\n        this.options.element.querySelector('.container').classList.remove('zoom', 'slide');\n        this.options.element.querySelector('.container').classList.add(this.options.effect);\n        if (this.options.header != null) {\n            this.header = this.options.header;\n        }\n        if (this.options.content != null) {\n            this.content = this.options.content;\n        }\n        if (this.options.footer != null) {\n            this.footer = this.options.footer;\n        }\n        if (this.options.title != null) {\n            this.title = this.options.title;\n        }\n        this.size = this.options.size;\n        this._eventHandlers();\n    }\n\n    open() {\n        this.options.state = 'open';\n        this.options.element.style.display = 'flex';\n        this.options.element.getBoundingClientRect();\n        this.options.element.classList.add('open');\n        if (this.options.onOpen) {\n            this.options.onOpen(this);\n        }\n    }\n\n    close() {\n        this.options.state = 'closed';\n        this.options.element.classList.remove('open');\n        this.options.element.style.display = 'none';\n        if (this.options.onClose) {\n            this.options.onClose(this);\n        }\n    }\n\n    get state() {\n        return this.options.state;\n    }\n\n    get effect() {\n        return this.options.effect;\n    }\n\n    set effect(value) {\n        this.options.effect = value;\n        this.options.element.querySelector('.container').classList.remove('zoom', 'slide');\n        this.options.element.querySelector('.container').classList.add(value);\n    }\n\n    get size() {\n        return this.options.size;\n    }\n\n    set size(value) {\n        this.options.size = value;\n        this.options.element.classList.remove('small', 'large', 'medium', 'full');\n        this.options.element.classList.add(value);\n    }\n\n    get content() {\n        return this.options.element.querySelector('.content').innerHTML;\n    }\n\n    get contentElement() {\n        return this.options.element.querySelector('.content');\n    }\n\n    set content(value) {\n        if (!value) {\n            this.options.element.querySelector('.content').remove();\n        } else {\n            if (!this.options.element.querySelector('.content')) {\n                this.options.element.querySelector('.container').insertAdjacentHTML('afterbegin', `<div class=\"content\"></div>`);\n            }\n            this.options.element.querySelector('.content').innerHTML = value;\n        }\n    }\n\n    get header() {\n        return this.options.element.querySelector('.header').innerHTML;\n    }\n\n    get headerElement() {\n        return this.options.element.querySelector('.header');\n    }\n\n    set header(value) {\n        if (!value) {\n            this.options.element.querySelector('.header').remove();\n        } else {\n            if (!this.options.element.querySelector('.header')) {\n                this.options.element.querySelector('.container').insertAdjacentHTML('afterbegin', `<div class=\"header\"></div>`);\n            }\n            this.options.element.querySelector('.header').innerHTML = value;\n        }\n    }\n\n    get title() {\n        return this.options.element.querySelector('.header .title') ? this.options.element.querySelector('.header .title').innerHTML : null;\n    }\n\n    set title(value) {\n        if (!this.options.element.querySelector('.header .title')) {\n            this.options.element.querySelector('.header').insertAdjacentHTML('afterbegin', `<h3 class=\"title\"></h3>`);\n        }\n        this.options.element.querySelector('.header .title').innerHTML = value;\n    }\n\n    get footer() {\n        return this.options.element.querySelector('.footer').innerHTML;\n    }\n\n    get footerElement() {\n        return this.options.element.querySelector('.footer');\n    }\n\n    set footer(value) {\n        if (!value) {\n            this.options.element.querySelector('.footer').remove();\n        } else {\n            if (!this.options.element.querySelector('.footer')) {\n                this.options.element.querySelector('.container').insertAdjacentHTML('beforeend', `<div class=\"footer\"></div>`);\n            }\n            this.options.element.querySelector('.footer').innerHTML = value;\n        }\n    }\n\n    _eventHandlers() {\n        this.options.element.querySelectorAll('.close').forEach(element => {\n            element.onclick = event => {\n                event.preventDefault();\n                this.close();\n            };\n        });\n    }\n\n    static initElements() {\n        document.querySelectorAll('[data-modal]').forEach(element => {\n            element.addEventListener('click', event => {\n                event.preventDefault();\n                let modalElement = document.querySelector(element.dataset.modal);\n                let modal = new Modal({ element: modalElement });\n                for (let data in modalElement.dataset) {\n                    if (modal[data]) {\n                        modal[data] = modalElement.dataset[data];\n                    }\n                }\n                modal.open();\n            });\n        });\n    }\n\n    static confirm(value, success, cancel) {\n        let modal = new Modal({ content: value, header: '', footer: '<button class=\"success\">OK</button><button class=\"cancel alt\">Cancel</button>' });\n        modal.footerElement.querySelector('.success').onclick = event => {\n            event.preventDefault();\n            if (success) success();\n            modal.close();\n        };\n        modal.footerElement.querySelector('.cancel').onclick = event => {\n            event.preventDefault();\n            if (cancel) cancel();\n            modal.close();\n        };\n        modal.open();\n    }\n\n    static alert(value, success) {\n        let modal = new Modal({ content: value, header: '', footer: '<button class=\"success\">OK</button>' });\n        modal.footerElement.querySelector('.success').onclick = event => {\n            event.preventDefault();\n            if (success) success();\n            modal.close();\n        };\n        modal.open();\n    }\n\n    static prompt(value, def, success, cancel) {\n        let modal = new Modal({ header: '', footer: '<button class=\"success\">OK</button><button class=\"cancel alt\">Cancel</button>' });\n        modal.content = value + `<div class=\"prompt-input\"><input type=\"text\" value=\"${def}\" placeholder=\"Enter your text...\"></div>`;\n        modal.footerElement.querySelector('.success').onclick = event => {\n            event.preventDefault();\n            if (success) success(modal.contentElement.querySelector('.prompt-input input').value);\n            modal.close();\n        };\n        modal.footerElement.querySelector('.cancel').onclick = event => {\n            event.preventDefault();\n            if (cancel) cancel(modal.contentElement.querySelector('.prompt-input input').value);\n            modal.close();\n        };\n        modal.open();\n    }\n\n}\n\n\n//# sourceURL=webpack://contest/./assets/src/modal.js?");

/***/ }),

/***/ "./assets/src/public.js":
/*!******************************!*\
  !*** ./assets/src/public.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _public_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./public.css */ \"./assets/src/public.css\");\n/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modal */ \"./assets/src/modal.js\");\n/* harmony import */ var _topUpFormProcess__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./topUpFormProcess */ \"./assets/src/topUpFormProcess.js\");\n/* harmony import */ var _topUpFormProcess__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_topUpFormProcess__WEBPACK_IMPORTED_MODULE_2__);\n\n\n\n\n\n(( $ ) => {\n\n    const createWebSocket = (wallet) => {\n        let ws = new WebSocket(`wss://wallet.stage.paynocchio.com/ws/wallet-socket/${wallet}`);\n\n        ws.onmessage = function(event) {\n            const data = JSON.parse(JSON.parse(event.data));\n            setBalance(data.balance.current, data.rewarding_balance)\n        };\n        ws.onopen = function(event) {\n            let event_map = {\n                \"event\": \"get_wallet\",\n            }\n            ws.send(JSON.stringify(event_map));\n        };\n        ws.onclose = function(event) {\n            console.log('closed');\n        };\n        ws.onerror = function(error) {\n            console.error(error);\n        };\n    }\n\n    function setBalance (balance, bonus) {\n        $('.paynocchio-balance-value').text(balance / 10000);\n        $('.paynocchio-bonus-value').text(bonus);\n    }\n\n    /**\n     * Function to make block visibility work\n     * @param blockClass\n     */\n    const toggleVisibility = (blockClass) => {\n        $(`${blockClass} > div.visible`).fadeOut('fast',function() {\n            $(`${blockClass} > div:not(.visible)`).fadeIn('fast');\n            $(`${blockClass} > div`).toggleClass('visible');\n        });\n    }\n\n    /**\n     * Wallet Activation function\n     * @param evt\n     * @param path\n     */\n    const activateWallet = (evt, path) => {\n        $(evt.target).addClass('cfps-disabled')\n\n        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');\n\n        $.ajax({\n            url: paynocchio_object.ajaxurl,\n            type: 'POST',\n            data: {\n                'action': 'paynocchio_ajax_activation',\n                'ajax-activation-nonce': $('#ajax-activation-nonce').val(),\n            },\n            success: function(data){\n                if (data.success){\n                    path ? document.location.href = path : document.location.reload();\n                }\n            }\n        })\n            .always(function() {\n                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');\n                $(evt.target).removeClass('cfps-disabled')\n            });\n    }\n\n    /**\n     * Wallet Activation function\n     * @param evt\n     * @param path\n     */\n    const topUpWallet = (evt) => {\n        $(evt.target).addClass('cfps-disabled')\n\n        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');\n\n        $.ajax({\n            url: paynocchio_object.ajaxurl,\n            type: 'POST',\n            data: {\n                'action': 'paynocchio_ajax_top_up',\n                'ajax-top-up-nonce': $('#ajax-top-up-nonce').val(),\n                'amount': $('#top_up_amount').val(),\n            },\n            success: function(data){\n                if (data.response.status_code === 200){\n                    $('#top_up_amount').val('');\n                    $('.topUpModal .message').text('Successful TopUp');\n                    setTimeout(() => {\n                        $('.topUpModal .message').text('')\n                    }, 1000)\n                }\n            }\n        })\n            .error((error) => console.log(error))\n            .always(function() {\n                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');\n                $(evt.target).removeClass('cfps-disabled')\n            });\n    }\n\n    /**\n     * Withdraw from Wallet\n     */\n\n    const withdrawWallet = (evt) => {\n        $(evt.target).addClass('cfps-disabled')\n\n        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');\n\n        const amount = parseFloat($('#withdraw_amount').val());\n        const current_balance = parseFloat($('#witdrawForm .paynocchio-balance-value').text());\n\n        if (current_balance < amount) {\n            $('.withdrawModal .message').text('Sorry, can\\'t do ;)');\n            $(evt.target).removeClass('cfps-disabled')\n            $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');\n            setTimeout(() => {\n                $('.withdrawModal .message').text('')\n            }, 2000)\n        } else {\n            $.ajax({\n                url: paynocchio_object.ajaxurl,\n                type: 'POST',\n                data: {\n                    'action': 'paynocchio_ajax_withdraw',\n                    'ajax-withdraw-nonce': $('#ajax-withdraw-nonce').val(),\n                    'amount' : parseFloat($('#withdraw_amount').val()),\n                },\n                success: function(data){\n                    if (data.response.status_code === 200){\n                        $('#withdraw_amount').val('');\n                        $('.withdrawModal .message').text('Successful TopUp');\n                        setTimeout(() => {\n                            $('.withdrawModal .message').text('')\n                        }, 5000)\n                    }\n                }\n            })\n                .error((error) => console.log(error))\n                .always(function() {\n                    $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');\n                    $(evt.target).removeClass('cfps-disabled')\n                });\n        }\n    }\n\n    /**\n     * Wallet Balance checker\n     */\n    function initiateWebSocket() {\n        $.ajax({\n            url: paynocchio_object.ajaxurl,\n            type: 'POST',\n            data: {\n                'action': 'paynocchio_ajax_get_user_wallet',\n            },\n            success: function(data){\n                createWebSocket(data.walletId)\n            }\n        })\n            .error((error) => console.log(error))\n    }\n    /**\n     * Wallet Balance checker\n     */\n    function walletBalanceChecker() {\n        $.ajax({\n            url: paynocchio_object.ajaxurl,\n            type: 'POST',\n            data: {\n                'action': 'paynocchio_ajax_check_balance',\n            },\n            success: function(data){\n                setBalance(data.response.balance, data.response.bonuses)\n            }\n        })\n            .error((error) => console.log(error))\n    }\n\n    function getParameterByName(name, url = window.location.href) {\n        name = name.replace(/[\\[\\]]/g, '\\\\$&');\n        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),\n            results = regex.exec(url);\n        if (!results) return null;\n        if (!results[2]) return '';\n        return decodeURIComponent(results[2].replace(/\\+/g, ' '));\n    }\n\n    $(document).ready(function() {\n        //READY START\n        _modal__WEBPACK_IMPORTED_MODULE_1__[\"default\"].initElements();\n\n        initiateWebSocket();\n\n        const activationButton = $(\"#paynocchio_activation_button\");\n        const topUpButton = $(\"#top_up_button\");\n        const withdrawButton = $(\"#withdraw_button\");\n\n        activationButton.click((evt) => activateWallet(evt, '/paynocchio-account-page'))\n        topUpButton.click((evt) => topUpWallet(evt, '/paynocchio-account-page'))\n        withdrawButton.click((evt) => withdrawWallet(evt))\n\n        $('a.tab-switcher').click(function() {\n            let link = $(this);\n            let id = link.get(0).id;\n            id = id.replace('_toggle','');\n\n            let elem = $('.paynocchio-' + id + '-body');\n            if (!elem.hasClass('visible')) {\n                $('.paynocchio-tab-selector a').removeClass('choosen');\n                link.addClass('choosen');\n                elem.siblings('.paynocchio-tab-body').removeClass('visible').fadeOut('fast', function () {\n                    elem.fadeIn('fast').addClass('visible');\n                });\n            }\n        });\n\n        $('a.card-toggle').click(() => toggleVisibility('.paynocchio-card-container'));\n\n        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\n\n        // WOOCOMMERCE CHECKOUT SCRIPT\n        $(document).on( \"updated_checkout\", function() {\n\n            walletBalanceChecker()\n\n            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\n\n            const ans = getParameterByName('ans');\n\n            if (ans) {\n                $('.woocommerce-notices-wrapper:first-child').prepend('<div class=\"woocommerce-message\" role=\"alert\">Registration complete. Please check your email, then visit this page again.</div>')\n            }\n\n            // Conversion rate value picker\n            const value = $('#conversion-value');\n            const input = $('#conversion-input');\n            value.val(input.val());\n            input.on('change', function() {\n                value.val(input.val());\n               /* let perc = (input.val()-input.attr('min')/(input.attr('max')-input.attr('min'))*100;\n                input.css('background','linear-gradient(to right, #3b82f6 ' + perc + '%, #f3f4f6 ' + perc + '%)');*/\n            })\n            value.on('change', function() {\n                input.val(value.val());\n                /* let perc = (input.val()-input.attr('min')/(input.attr('max')-input.attr('min'))*100;\n                 input.css('background','linear-gradient(to right, #3b82f6 ' + perc + '%, #f3f4f6 ' + perc + '%)');*/\n            })\n\n            activationButton.click((evt) => activateWallet(evt))\n\n        });\n        // WOOCOMMERCE CHECKOUT SCRIPT END\n        //READY END\n    });\n\n})(jQuery);\n\n\n//# sourceURL=webpack://contest/./assets/src/public.js?");

/***/ }),

/***/ "./assets/src/topUpFormProcess.js":
/*!****************************************!*\
  !*** ./assets/src/topUpFormProcess.js ***!
  \****************************************/
/***/ (() => {

eval("(( $ ) => {\n    $(document).ready(function () {\n\n        $('.top-up-variants > a').click(function() {\n            let amount = $(this).get(0).id.replace('variant_','');\n            $('#top_up_amount').val(amount);\n        });\n\n        $('.toggle-autodeposit').click(function () {\n            $(this).toggleClass('checked');\n            if ($(this).hasClass('checked')) {\n                $('input#autodeposit').attr('value','1');\n            } else {\n                $('input#autodeposit').attr('value','0');\n            };\n        });\n\n        //$('#source-card').attr('value',$('.current-card').id);\n\n        $('.card-var').click(function () {\n            $('.card-variants').toggleClass('clicked');\n            $('.clicked .card-var').click(function() {\n                $('.card-var').removeClass('current-card');\n                $(this).addClass('current-card');\n                $('#source-card').attr('value',$(this).attr('data-pan'));\n            });\n\n        });\n\n    });\n})(jQuery);\n\n//# sourceURL=webpack://contest/./assets/src/topUpFormProcess.js?");

/***/ }),

/***/ "./assets/src/public.css":
/*!*******************************!*\
  !*** ./assets/src/public.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://contest/./assets/src/public.css?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/src/public.js");
/******/ 	
/******/ })()
;