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

/***/ "./assets/src/ajax.js":
/*!****************************!*\
  !*** ./assets/src/ajax.js ***!
  \****************************/
/***/ (() => {

eval("\n(function( $ ) {\n\n    $(document).ready(function() {\n\n        jQuery(document).on( \"updated_checkout\", function(){\n\n            $('#place_order').addClass('disabled');\n\n            $('#paynocchio_login_button').click(function (e) {\n\n                e.preventDefault();\n\n                $.ajax({\n                    url: paynocchio_login_object.ajaxurl,\n                    type: 'POST',\n                    data: {\n                        'action': 'paynocchio_ajax_login',\n                        'username': $('#paynocchio_login_username').val(),\n                        'password': $('#paynocchio_login_password').val(),\n                        'ajax-login-nonce': $('#paynocchio_login_password').val() },\n                    success: function(data){\n                        if (data.loggedin == true){\n                            document.location.href = paynocchio_login_object.redirecturl;\n                        }\n                    }\n                });\n            })\n        });\n\n    });\n\n})( jQuery );\n\n//# sourceURL=webpack://contest/./assets/src/ajax.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./assets/src/ajax.js"]();
/******/ 	
/******/ })()
;