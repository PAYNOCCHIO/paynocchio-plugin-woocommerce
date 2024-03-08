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

eval("\r\n(function( $ ) {\r\n\r\n    $(document).ready(function() {\r\n\r\n        const activation_button = $(\"#paynocchio_activation_button\");\r\n\r\n        activation_button.click(() => {\r\n\r\n            $.ajax({\r\n                url: paynocchio_activation_object.ajaxurl,\r\n                type: 'POST',\r\n                data: {\r\n                    'action': 'paynocchio_ajax_activation',\r\n                    'source': window.location.pathname,\r\n                    'ajax-activation-nonce': $('#ajax-activation-nonce').val(),\r\n                },\r\n                success: function(data){\r\n                    console.log(data)\r\n                    if (data){\r\n                        //document.location.href = data.redirecturl;\r\n                    }\r\n                }\r\n            });\r\n        })\r\n\r\n    });\r\n\r\n})( jQuery );\n\n//# sourceURL=webpack://contest/./assets/src/ajax-activation.js?");

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