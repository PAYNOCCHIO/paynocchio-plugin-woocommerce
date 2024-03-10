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

/***/ "./assets/src/public.js":
/*!******************************!*\
  !*** ./assets/src/public.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _public_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./public.css */ \"./assets/src/public.css\");\n\n\n(( $ ) => {\n\n    const toggleVisibility = (blockClass) => {\n        $(`${blockClass} > div.visible`).fadeOut('fast',function() {\n            $(`${blockClass} > div:not(.visible)`).fadeIn('fast');\n            $(`${blockClass} > div`).toggleClass('visible');\n        });\n    }\n\n    const activateWallet = (evt, path) => {\n        $(evt.target).addClass('cfps-disabled')\n\n        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');\n\n        $.ajax({\n            url: paynocchio_object.ajaxurl,\n            type: 'POST',\n            data: {\n                'action': 'paynocchio_ajax_activation',\n                'ajax-activation-nonce': $('#ajax-activation-nonce').val(),\n            },\n            success: function(data){\n                if (data.success){\n                    path ? document.location.href = path : document.location.reload();\n                }\n            }\n        })\n            .always(function() {\n                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');\n            });\n    }\n\n    function getParameterByName(name, url = window.location.href) {\n        name = name.replace(/[\\[\\]]/g, '\\\\$&');\n        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),\n            results = regex.exec(url);\n        if (!results) return null;\n        if (!results[2]) return '';\n        return decodeURIComponent(results[2].replace(/\\+/g, ' '));\n    }\n\n    $(document).ready(function() {\n        //READY START\n\n        $(\"#paynocchio_activation_button\").click((evt) => activateWallet(evt, '/paynocchio-account-page'))\n\n        $('a.tab-switcher').click(function() {\n            let link = $(this);\n            let id = link.get(0).id;\n            id = id.replace('_toggle','');\n\n            let elem = $('.paynocchio-' + id + '-body');\n            if (!elem.hasClass('visible')) {\n                $('.paynocchio-tab-selector a').removeClass('choosen');\n                link.addClass('choosen');\n                elem.siblings('.paynocchio-tab-body').removeClass('visible').fadeOut('fast', function () {\n                    elem.fadeIn('fast').addClass('visible');\n                });\n            }\n        });\n\n        $(\"a.card-toggle\").click(function () {\n            $('.paynocchio-card-container .visible').fadeOut('fast',function() {\n                $('.paynocchio-card-container > div').toggleClass('visible');\n                $('.paynocchio-card-container .visible').fadeIn('fast');\n            });\n        });\n\n        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\n        //READY END\n    });\n\n\n    // WOOCOMMERCE CHECKOUT SCRIPT\n    $(document).on( \"updated_checkout\", function() {\n        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\n\n        const ans = getParameterByName('ans');\n\n        if (ans) {\n            $('.woocommerce-notices-wrapper:first-child').prepend('<div class=\"woocommerce-message\" role=\"alert\">Registration complete. Please check your email, then visit this page again.</div>')\n        }\n\n        $(\"#paynocchio_activation_button\").click(() => activateWallet())\n\n    });\n\n})(jQuery);\n\n\n//# sourceURL=webpack://contest/./assets/src/public.js?");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/src/public.js");
/******/ 	
/******/ })()
;