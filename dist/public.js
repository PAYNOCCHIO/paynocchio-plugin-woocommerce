/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/modal.js":
/*!*****************************!*\
  !*** ./assets/src/modal.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ Modal)\n/* harmony export */ });\nclass Modal {\r\n\r\n    constructor(options) {\r\n        let defaults = { element: null, effect: 'zoom', state: 'closed', size: 'medium', content: null, footer: null, header: null, title: null };\r\n        this.options = Object.assign(defaults, options);\r\n        if (this.options.element == null) {\r\n            this.options.element = document.createElement('div');\r\n            this.options.element.classList.add('modal');\r\n            this.options.element.innerHTML = `\r\n                <div class=\"container\">\r\n                    <div class=\"header\">\r\n                        <button class=\"close\">&times;</button> \r\n                    </div>\r\n                    <div class=\"content\"></div>\r\n                    <div class=\"footer\">\r\n                        <button class=\"close\">Close</button>\r\n                    </div>\r\n                </div>                        \r\n            `;\r\n            document.body.appendChild(this.options.element);\r\n        }\r\n        this.options.element.querySelector('.container').classList.remove('zoom', 'slide');\r\n        this.options.element.querySelector('.container').classList.add(this.options.effect);\r\n        if (this.options.header != null) {\r\n            this.header = this.options.header;\r\n        }\r\n        if (this.options.content != null) {\r\n            this.content = this.options.content;\r\n        }\r\n        if (this.options.footer != null) {\r\n            this.footer = this.options.footer;\r\n        }\r\n        if (this.options.title != null) {\r\n            this.title = this.options.title;\r\n        }\r\n        this.size = this.options.size;\r\n        this._eventHandlers();\r\n    }\r\n\r\n    open() {\r\n        this.options.state = 'open';\r\n        this.options.element.style.display = 'flex';\r\n        this.options.element.getBoundingClientRect();\r\n        this.options.element.classList.add('open');\r\n        if (this.options.onOpen) {\r\n            this.options.onOpen(this);\r\n        }\r\n    }\r\n\r\n    close() {\r\n        this.options.state = 'closed';\r\n        this.options.element.classList.remove('open');\r\n        this.options.element.style.display = 'none';\r\n        if (this.options.onClose) {\r\n            this.options.onClose(this);\r\n        }\r\n    }\r\n\r\n    get state() {\r\n        return this.options.state;\r\n    }\r\n\r\n    get effect() {\r\n        return this.options.effect;\r\n    }\r\n\r\n    set effect(value) {\r\n        this.options.effect = value;\r\n        this.options.element.querySelector('.container').classList.remove('zoom', 'slide');\r\n        this.options.element.querySelector('.container').classList.add(value);\r\n    }\r\n\r\n    get size() {\r\n        return this.options.size;\r\n    }\r\n\r\n    set size(value) {\r\n        this.options.size = value;\r\n        this.options.element.classList.remove('small', 'large', 'medium', 'full');\r\n        this.options.element.classList.add(value);\r\n    }\r\n\r\n    get content() {\r\n        return this.options.element.querySelector('.content').innerHTML;\r\n    }\r\n\r\n    get contentElement() {\r\n        return this.options.element.querySelector('.content');\r\n    }\r\n\r\n    set content(value) {\r\n        if (!value) {\r\n            this.options.element.querySelector('.content').remove();\r\n        } else {\r\n            if (!this.options.element.querySelector('.content')) {\r\n                this.options.element.querySelector('.container').insertAdjacentHTML('afterbegin', `<div class=\"content\"></div>`);\r\n            }\r\n            this.options.element.querySelector('.content').innerHTML = value;\r\n        }\r\n    }\r\n\r\n    get header() {\r\n        return this.options.element.querySelector('.header').innerHTML;\r\n    }\r\n\r\n    get headerElement() {\r\n        return this.options.element.querySelector('.header');\r\n    }\r\n\r\n    set header(value) {\r\n        if (!value) {\r\n            this.options.element.querySelector('.header').remove();\r\n        } else {\r\n            if (!this.options.element.querySelector('.header')) {\r\n                this.options.element.querySelector('.container').insertAdjacentHTML('afterbegin', `<div class=\"header\"></div>`);\r\n            }\r\n            this.options.element.querySelector('.header').innerHTML = value;\r\n        }\r\n    }\r\n\r\n    get title() {\r\n        return this.options.element.querySelector('.header .title') ? this.options.element.querySelector('.header .title').innerHTML : null;\r\n    }\r\n\r\n    set title(value) {\r\n        if (!this.options.element.querySelector('.header .title')) {\r\n            this.options.element.querySelector('.header').insertAdjacentHTML('afterbegin', `<h1 class=\"title\"></h1>`);\r\n        }\r\n        this.options.element.querySelector('.header .title').innerHTML = value;\r\n    }\r\n\r\n    get footer() {\r\n        return this.options.element.querySelector('.footer').innerHTML;\r\n    }\r\n\r\n    get footerElement() {\r\n        return this.options.element.querySelector('.footer');\r\n    }\r\n\r\n    set footer(value) {\r\n        if (!value) {\r\n            this.options.element.querySelector('.footer').remove();\r\n        } else {\r\n            if (!this.options.element.querySelector('.footer')) {\r\n                this.options.element.querySelector('.container').insertAdjacentHTML('beforeend', `<div class=\"footer\"></div>`);\r\n            }\r\n            this.options.element.querySelector('.footer').innerHTML = value;\r\n        }\r\n    }\r\n\r\n    _eventHandlers() {\r\n        this.options.element.querySelectorAll('.close').forEach(element => {\r\n            element.onclick = event => {\r\n                event.preventDefault();\r\n                this.close();\r\n            };\r\n        });\r\n    }\r\n\r\n    static initElements() {\r\n        document.querySelectorAll('[data-modal]').forEach(element => {\r\n            element.addEventListener('click', event => {\r\n                event.preventDefault();\r\n                let modalElement = document.querySelector(element.dataset.modal);\r\n                let modal = new Modal({ element: modalElement });\r\n                for (let data in modalElement.dataset) {\r\n                    if (modal[data]) {\r\n                        modal[data] = modalElement.dataset[data];\r\n                    }\r\n                }\r\n                modal.open();\r\n            });\r\n        });\r\n    }\r\n\r\n    static confirm(value, success, cancel) {\r\n        let modal = new Modal({ content: value, header: '', footer: '<button class=\"success\">OK</button><button class=\"cancel alt\">Cancel</button>' });\r\n        modal.footerElement.querySelector('.success').onclick = event => {\r\n            event.preventDefault();\r\n            if (success) success();\r\n            modal.close();\r\n        };\r\n        modal.footerElement.querySelector('.cancel').onclick = event => {\r\n            event.preventDefault();\r\n            if (cancel) cancel();\r\n            modal.close();\r\n        };\r\n        modal.open();\r\n    }\r\n\r\n    static alert(value, success) {\r\n        let modal = new Modal({ content: value, header: '', footer: '<button class=\"success\">OK</button>' });\r\n        modal.footerElement.querySelector('.success').onclick = event => {\r\n            event.preventDefault();\r\n            if (success) success();\r\n            modal.close();\r\n        };\r\n        modal.open();\r\n    }\r\n\r\n    static prompt(value, def, success, cancel) {\r\n        let modal = new Modal({ header: '', footer: '<button class=\"success\">OK</button><button class=\"cancel alt\">Cancel</button>' });\r\n        modal.content = value + `<div class=\"prompt-input\"><input type=\"text\" value=\"${def}\" placeholder=\"Enter your text...\"></div>`;\r\n        modal.footerElement.querySelector('.success').onclick = event => {\r\n            event.preventDefault();\r\n            if (success) success(modal.contentElement.querySelector('.prompt-input input').value);\r\n            modal.close();\r\n        };\r\n        modal.footerElement.querySelector('.cancel').onclick = event => {\r\n            event.preventDefault();\r\n            if (cancel) cancel(modal.contentElement.querySelector('.prompt-input input').value);\r\n            modal.close();\r\n        };\r\n        modal.open();\r\n    }\r\n\r\n}\n\n//# sourceURL=webpack://contest/./assets/src/modal.js?");

/***/ }),

/***/ "./assets/src/public.js":
/*!******************************!*\
  !*** ./assets/src/public.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _public_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./public.css */ \"./assets/src/public.css\");\n/* harmony import */ var _ws__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ws */ \"./assets/src/ws.js\");\n/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modal */ \"./assets/src/modal.js\");\n\r\n\r\n\r\n\r\n\r\n(( $ ) => {\r\n\r\n    /**\r\n     * Function to make block visibility work\r\n     * @param blockClass\r\n     */\r\n    const toggleVisibility = (blockClass) => {\r\n        $(`${blockClass} > div.visible`).fadeOut('fast',function() {\r\n            $(`${blockClass} > div:not(.visible)`).fadeIn('fast');\r\n            $(`${blockClass} > div`).toggleClass('visible');\r\n        });\r\n    }\r\n\r\n    /**\r\n     * Wallet Activation function\r\n     * @param evt\r\n     * @param path\r\n     */\r\n    const activateWallet = (evt, path) => {\r\n        $(evt.target).addClass('cfps-disabled')\r\n\r\n        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');\r\n\r\n        $.ajax({\r\n            url: paynocchio_object.ajaxurl,\r\n            type: 'POST',\r\n            data: {\r\n                'action': 'paynocchio_ajax_activation',\r\n                'ajax-activation-nonce': $('#ajax-activation-nonce').val(),\r\n            },\r\n            success: function(data){\r\n                if (data.success){\r\n                    path ? document.location.href = path : document.location.reload();\r\n                }\r\n            }\r\n        })\r\n            .always(function() {\r\n                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');\r\n            });\r\n    }\r\n\r\n    const setBalance = (balance, bonus) => {\r\n        $('.paynocchio-balance-value').css('--value', balance);\r\n        $('.paynocchio-bonus-value').css('--value', bonus);\r\n    }\r\n\r\n    function getParameterByName(name, url = window.location.href) {\r\n        name = name.replace(/[\\[\\]]/g, '\\\\$&');\r\n        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),\r\n            results = regex.exec(url);\r\n        if (!results) return null;\r\n        if (!results[2]) return '';\r\n        return decodeURIComponent(results[2].replace(/\\+/g, ' '));\r\n    }\r\n\r\n    $(document).ready(function() {\r\n        //READY START\r\n        _modal__WEBPACK_IMPORTED_MODULE_2__[\"default\"].initElements();\r\n        const activationButton = $(\"#paynocchio_activation_button\");\r\n\r\n        activationButton.click((evt) => activateWallet(evt, '/paynocchio-account-page'))\r\n\r\n        $('a.tab-switcher').click(function() {\r\n            let link = $(this);\r\n            let id = link.get(0).id;\r\n            id = id.replace('_toggle','');\r\n\r\n            let elem = $('.paynocchio-' + id + '-body');\r\n            if (!elem.hasClass('visible')) {\r\n                $('.paynocchio-tab-selector a').removeClass('choosen');\r\n                link.addClass('choosen');\r\n                elem.siblings('.paynocchio-tab-body').removeClass('visible').fadeOut('fast', function () {\r\n                    elem.fadeIn('fast').addClass('visible');\r\n                });\r\n            }\r\n        });\r\n\r\n        $('a.card-toggle').click(() => toggleVisibility('.paynocchio-card-container'));\r\n\r\n        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\r\n\r\n        setBalance(200, 300)\r\n        setTimeout(() => {\r\n            setInterval(() => {\r\n                setBalance(parseInt($('.paynocchio-balance-value').css('--value')) + 10, parseInt($('.paynocchio-bonus-value').css('--value')) + 20)\r\n            }, 5000)\r\n        }, 2000)\r\n\r\n\r\n\r\n        window.wsSingleton = new _ws__WEBPACK_IMPORTED_MODULE_1__[\"default\"](\"wss://wallet.stage.paynocchio.com/ws/wallet-socket/872ee190-6075-4081-91f2-5a38240c2240\")\r\n\r\n        window.wsSingleton.clientPromise\r\n            .then( wsClient =>{\r\n                wsClient.send({\"event\":\"get_wallet\"});\r\n                console.log('sended')\r\n            })\r\n            .catch(error => console.log(error))\r\n\r\n\r\n        // WOOCOMMERCE CHECKOUT SCRIPT\r\n        $(document).on( \"updated_checkout\", function() {\r\n\r\n            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\r\n\r\n            const ans = getParameterByName('ans');\r\n\r\n            if (ans) {\r\n                $('.woocommerce-notices-wrapper:first-child').prepend('<div class=\"woocommerce-message\" role=\"alert\">Registration complete. Please check your email, then visit this page again.</div>')\r\n            }\r\n\r\n            activationButton.click((evt) => activateWallet(evt))\r\n\r\n        });\r\n        // WOOCOMMERCE CHECKOUT SCRIPT END\r\n        //READY END\r\n    });\r\n\r\n})(jQuery);\r\n\n\n//# sourceURL=webpack://contest/./assets/src/public.js?");

/***/ }),

/***/ "./assets/src/ws.js":
/*!**************************!*\
  !*** ./assets/src/ws.js ***!
  \**************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ Ws)\n/* harmony export */ });\nclass Ws {\r\n    constructor(url) {\r\n        this.url = url;\r\n    }\r\n\r\n    get newClientPromise() {\r\n        return new Promise((resolve, reject) => {\r\n            let wsClient = new WebSocket(this.url);\r\n            console.log(wsClient)\r\n            wsClient.onopen = () => {\r\n                console.log(\"connected\");\r\n                resolve(wsClient);\r\n            };\r\n            wsClient.onerror = error => reject(error);\r\n        })\r\n    }\r\n\r\n    get clientPromise() {\r\n        if (!this.promise) {\r\n            this.promise = this.newClientPromise\r\n        }\r\n        return this.promise;\r\n    }\r\n}\n\n//# sourceURL=webpack://contest/./assets/src/ws.js?");

/***/ }),

/***/ "./assets/src/public.css":
/*!*******************************!*\
  !*** ./assets/src/public.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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