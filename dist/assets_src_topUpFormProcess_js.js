/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunkcontest"] = self["webpackChunkcontest"] || []).push([["assets_src_topUpFormProcess_js"],{

/***/ "./assets/src/topUpFormProcess.js":
/*!****************************************!*\
  !*** ./assets/src/topUpFormProcess.js ***!
  \****************************************/
/***/ (() => {

eval("\n(( $ ) => {\n    $(document).ready(function () {\n\n        $('#topup_amount').on('input propertychange', function() {\n            this.value = this.value\n                .replace(/ /g, \".\")\n                .replace(/_/g, \"-\")\n                .replace(/\\.+/g, \".\")\n                .replace(/\\-+/g, \"-\")\n                .replace(/[^\\w.-]|[a-zA-Z]|^[.-]/g, \"\")\n        })\n\n        $('.top-up-variants > a').click(function() {\n           let amount = $(this).get(0).id.replace('variant_','');\n           $('#topup_amount').val(amount);\n        });\n\n        $('.toggle-autodeposit').click(function () {\n            $(this).toggleClass('checked');\n            if ($(this).hasClass('checked')) {\n                $('input#autodeposit').attr('value','1');\n            } else {\n                $('input#autodeposit').attr('value','0');\n            };\n        });\n\n        //$('#source-card').attr('value',$('.current-card').id);\n\n        $('.card-var').click(function () {\n            $('.card-variants').toggleClass('clicked');\n            $('.clicked .card-var').click(function() {\n                $('.card-var').removeClass('current-card');\n                $(this).addClass('current-card');\n                $('#source-card').attr('value',$(this).attr('data-pan'));\n            });\n\n        });\n\n\n\n    });\n})(jQuery);\n\n//# sourceURL=webpack://contest/./assets/src/topUpFormProcess.js?");

/***/ })

}]);