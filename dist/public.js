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

/***/ "./assets/src/wallet-activation.js":
/*!*****************************************!*\
  !*** ./assets/src/wallet-activation.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _public_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./public.css */ \"./assets/src/public.css\");\n\r\n\r\n\r\n(( $ ) => {\r\n\r\n    /**\r\n     * Function to make block visibility work\r\n     * @param blockClass\r\n     */\r\n    const toggleVisibility = (blockClass) => {\r\n        $(`${blockClass} > div.visible`).fadeOut('fast',function() {\r\n            $(`${blockClass} > div:not(.visible)`).fadeIn('fast');\r\n            $(`${blockClass} > div`).toggleClass('visible');\r\n        });\r\n    }\r\n\r\n    /**\r\n     * Wallet Activation function\r\n     * @param evt\r\n     * @param path\r\n     */\r\n    const activateWallet = (evt, path) => {\r\n        $(evt.target).addClass('cfps-disabled')\r\n\r\n        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');\r\n\r\n        $.ajax({\r\n            url: paynocchio_object.ajaxurl,\r\n            type: 'POST',\r\n            data: {\r\n                'action': 'paynocchio_ajax_activation',\r\n                'ajax-activation-nonce': $('#ajax-activation-nonce').val(),\r\n            },\r\n            success: function(data){\r\n                if (data.success){\r\n                    path ? document.location.href = path : document.location.reload();\r\n                }\r\n            }\r\n        })\r\n            .always(function() {\r\n                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');\r\n                $(evt.target).removeClass('cfps-disabled')\r\n            });\r\n    }\r\n\r\n\r\n    function getParameterByName(name, url = window.location.href) {\r\n        name = name.replace(/[\\[\\]]/g, '\\\\$&');\r\n        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),\r\n            results = regex.exec(url);\r\n        if (!results) return null;\r\n        if (!results[2]) return '';\r\n        return decodeURIComponent(results[2].replace(/\\+/g, ' '));\r\n    }\r\n\r\n    $(document).ready(function() {\r\n        //READY START\r\n\r\n        const checkout = window.location.pathname === '/checkout/';\r\n\r\n        const activationButton = $(\"#paynocchio_activation_button\");\r\n\r\n        if(!checkout) {\r\n            activationButton.click((evt) => activateWallet(evt))\r\n        }\r\n\r\n        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\r\n\r\n        // WOOCOMMERCE CHECKOUT SCRIPT\r\n        $(document).on( \"updated_checkout\", function() {\r\n\r\n            const activationButton = $(\"#paynocchio_activation_button\");\r\n\r\n            activationButton.click((evt) => activateWallet(evt))\r\n\r\n\r\n            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\r\n\r\n            const ans = getParameterByName('ans');\r\n\r\n            if (ans) {\r\n                $('.woocommerce-notices-wrapper:first-child').prepend('<div class=\"woocommerce-message\" role=\"alert\">Registration complete. Please check your email, then visit this page again.</div>')\r\n            }\r\n\r\n\r\n        });\r\n        // WOOCOMMERCE CHECKOUT SCRIPT END\r\n        //READY END\r\n    });\r\n\r\n})(jQuery);\r\n\n\n//# sourceURL=webpack://contest/./assets/src/wallet-activation.js?");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/src/wallet-activation.js");
/******/ 	
/******/ })()
;