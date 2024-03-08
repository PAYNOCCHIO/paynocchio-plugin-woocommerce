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

/***/ "./assets/src/ajax-activation.js":
/*!***************************************!*\
  !*** ./assets/src/ajax-activation.js ***!
  \***************************************/
/***/ (() => {

eval("\n(function( $ ) {\n\n    $(document).ready(function() {\n\n        const activation_button = $(\"#paynocchio_activation_button\");\n\n        activation_button.click(() => {\n\n            $.ajax({\n                url: paynocchio_activation_object.ajaxurl,\n                type: 'POST',\n                data: {\n                    'action': 'paynocchio_ajax_activation',\n                    'source': window.location.pathname,\n                    'ajax-activation-nonce': $('#ajax-activation-nonce').val(),\n                },\n                success: function(data){\n                    if (data.success){\n                        document.location.href = '/paynocchio-account-page';\n                    }\n                }\n            });\n        })\n\n    });\n\n})( jQuery );\n\n//# sourceURL=webpack://contest/./assets/src/ajax-activation.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./assets/src/ajax-activation.js"]();
/******/ 	
/******/ })()
;