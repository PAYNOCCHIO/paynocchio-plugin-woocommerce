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

/***/ "./assets/src/ajax-login.js":
/*!**********************************!*\
  !*** ./assets/src/ajax-login.js ***!
  \**********************************/
/***/ (() => {

eval("(( $ ) => {\n\n    $(document).ready(() => {\n\n        const toggleVisibility = (blockClass) => {\n            $(`${blockClass} > div.visible`).fadeOut('fast',function() {\n                $(`${blockClass} > div:not(.visible)`).fadeIn('fast');\n                $(`${blockClass} > div`).toggleClass('visible');\n            });\n        }\n\n        function getParameterByName(name, url = window.location.href) {\n            name = name.replace(/[\\[\\]]/g, '\\\\$&');\n            const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),\n                results = regex.exec(url);\n            if (!results) return null;\n            if (!results[2]) return '';\n            return decodeURIComponent(results[2].replace(/\\+/g, ' '));\n        }\n\n        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\n\n        // WOOCOMMERCE CHECKOUT SCRIPT\n        $(document).on( \"updated_checkout\", function() {\n            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\n\n            const ans = getParameterByName('ans');\n\n            if (ans) {\n                $('.woocommerce-notices-wrapper:first-child').prepend('<div class=\"woocommerce-message\" role=\"alert\">Registration complete. Please check your email, then visit this page again.</div>')\n            }\n\n            const activation_button = $(\"#paynocchio_activation_button\");\n\n            activation_button.click(() => {\n\n                $.ajax({\n                    url: paynocchio_activation_object.ajaxurl,\n                    type: 'POST',\n                    data: {\n                        'action': 'paynocchio_ajax_activation',\n                        'source': window.location.pathname,\n                        'ajax-activation-nonce': $('#ajax-activation-nonce').val(),\n                    },\n                    success: function(data){\n                        if (data.success){\n                            document.location.reload();\n                        }\n                    }\n                });\n            })\n\n        });\n    })\n\n})( jQuery );\n\n//# sourceURL=webpack://contest/./assets/src/ajax-login.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./assets/src/ajax-login.js"]();
/******/ 	
/******/ })()
;