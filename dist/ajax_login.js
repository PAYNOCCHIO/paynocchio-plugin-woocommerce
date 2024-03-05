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

eval("(( $ ) => {\n\n    jQuery(document).on( \"updated_checkout\", function() {\n        $('.form-toggle-a').click(() => {\n            $('#paynocchio_auth_block > div.visible').fadeOut('fast',function() {\n                $('#paynocchio_auth_block > div:not(.visible)').fadeIn('fast');\n                $('#paynocchio_auth_block > div').toggleClass('visible');\n            });\n        });\n\n        function getParameterByName(name, url = window.location.href) {\n            name = name.replace(/[\\[\\]]/g, '\\\\$&');\n            const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),\n                results = regex.exec(url);\n            if (!results) return null;\n            if (!results[2]) return '';\n            return decodeURIComponent(results[2].replace(/\\+/g, ' '));\n        }\n\n        const ans = getParameterByName('ans');\n\n        if (ans) {\n            $('.woocommerce-notices-wrapper:first-child').append('<div class=\"woocommerce-message\" role=\"alert\">Registration complete. Please check your email, then visit this page again.</div>')\n        }\n    });\n\n\n})( jQuery );\n\n//# sourceURL=webpack://contest/./assets/src/ajax-login.js?");

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