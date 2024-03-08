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

eval("(( $ ) => {\r\n\r\n    $(document).ready(() => {\r\n\r\n        const toggleVisibility = (blockClass) => {\r\n            $(`${blockClass} > div.visible`).fadeOut('fast',function() {\r\n                $(`${blockClass} > div:not(.visible)`).fadeIn('fast');\r\n                $(`${blockClass} > div`).toggleClass('visible');\r\n            });\r\n        }\r\n\r\n        function getParameterByName(name, url = window.location.href) {\r\n            name = name.replace(/[\\[\\]]/g, '\\\\$&');\r\n            const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),\r\n                results = regex.exec(url);\r\n            if (!results) return null;\r\n            if (!results[2]) return '';\r\n            return decodeURIComponent(results[2].replace(/\\+/g, ' '));\r\n        }\r\n\r\n        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\r\n\r\n        $(document).on( \"updated_checkout\", function() {\r\n            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));\r\n\r\n            const ans = getParameterByName('ans');\r\n\r\n            if (ans) {\r\n                $('.woocommerce-notices-wrapper:first-child').prepend('<div class=\"woocommerce-message\" role=\"alert\">Registration complete. Please check your email, then visit this page again.</div>')\r\n            }\r\n        });\r\n    })\r\n\r\n})( jQuery );\n\n//# sourceURL=webpack://contest/./assets/src/ajax-login.js?");

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