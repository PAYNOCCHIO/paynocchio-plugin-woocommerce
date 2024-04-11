/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/modal.js":
/*!*****************************!*\
  !*** ./assets/src/modal.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Modal)
/* harmony export */ });
class Modal {
  constructor(options) {
    let defaults = {
      element: null,
      effect: 'zoom',
      state: 'closed',
      size: 'medium',
      content: null,
      footer: null,
      header: null,
      title: null
    };
    this.options = Object.assign(defaults, options);
    //this.options.body = document.body;
    if (this.options.element == null) {
      this.options.element = document.createElement('div');
      this.options.element.classList.add('modal');
      this.options.element.innerHTML = `
                <div class="container">
                    <div class="header">
                        <button class="close">&times;</button> 
                    </div>
                    <div class="content"></div>
                    <div class="footer">
                        <button class="close">Close</button>
                    </div>
                </div>                        
            `;
      document.body.appendChild(this.options.element);
    }
    this.options.element.querySelector('.container').classList.remove('zoom', 'slide');
    this.options.element.querySelector('.container').classList.add(this.options.effect);
    if (this.options.header != null) {
      this.header = this.options.header;
    }
    if (this.options.content != null) {
      this.content = this.options.content;
    }
    if (this.options.footer != null) {
      this.footer = this.options.footer;
    }
    if (this.options.title != null) {
      this.title = this.options.title;
    }
    this.size = this.options.size;
    this._eventHandlers();
  }
  open() {
    this.options.state = 'open';
    this.options.element.style.display = 'flex';
    this.options.element.getBoundingClientRect();
    this.options.element.classList.add('open');
    document.body.classList.add('paynocchio-modal-open');
    if (this.options.onOpen) {
      this.options.onOpen(this);
    }
  }
  close() {
    if (document.querySelectorAll('.modal.open').length == 1) {
      document.body.classList.remove('paynocchio-modal-open');
    }
    this.options.state = 'closed';
    this.options.element.classList.remove('open');
    this.options.element.style.display = 'none';
    if (this.options.onClose) {
      this.options.onClose(this);
    }
  }
  get state() {
    return this.options.state;
  }
  get effect() {
    return this.options.effect;
  }
  set effect(value) {
    this.options.effect = value;
    this.options.element.querySelector('.container').classList.remove('zoom', 'slide');
    this.options.element.querySelector('.container').classList.add(value);
  }
  get size() {
    return this.options.size;
  }
  set size(value) {
    this.options.size = value;
    this.options.element.classList.remove('small', 'large', 'medium', 'full');
    this.options.element.classList.add(value);
  }
  get content() {
    return this.options.element.querySelector('.content').innerHTML;
  }
  get contentElement() {
    return this.options.element.querySelector('.content');
  }
  set content(value) {
    if (!value) {
      this.options.element.querySelector('.content').remove();
    } else {
      if (!this.options.element.querySelector('.content')) {
        this.options.element.querySelector('.container').insertAdjacentHTML('afterbegin', `<div class="content"></div>`);
      }
      this.options.element.querySelector('.content').innerHTML = value;
    }
  }
  get header() {
    return this.options.element.querySelector('.header').innerHTML;
  }
  get headerElement() {
    return this.options.element.querySelector('.header');
  }
  set header(value) {
    if (!value) {
      this.options.element.querySelector('.header').remove();
    } else {
      if (!this.options.element.querySelector('.header')) {
        this.options.element.querySelector('.container').insertAdjacentHTML('afterbegin', `<div class="header"></div>`);
      }
      this.options.element.querySelector('.header').innerHTML = value;
    }
  }
  get title() {
    return this.options.element.querySelector('.header .title') ? this.options.element.querySelector('.header .title').innerHTML : null;
  }
  set title(value) {
    if (!this.options.element.querySelector('.header .title')) {
      this.options.element.querySelector('.header').insertAdjacentHTML('afterbegin', `<h3 class="title"></h3>`);
    }
    this.options.element.querySelector('.header .title').innerHTML = value;
  }
  get footer() {
    return this.options.element.querySelector('.footer').innerHTML;
  }
  get footerElement() {
    return this.options.element.querySelector('.footer');
  }
  set footer(value) {
    if (!value) {
      this.options.element.querySelector('.footer').remove();
    } else {
      if (!this.options.element.querySelector('.footer')) {
        this.options.element.querySelector('.container').insertAdjacentHTML('beforeend', `<div class="footer"></div>`);
      }
      this.options.element.querySelector('.footer').innerHTML = value;
    }
  }
  _eventHandlers() {
    this.options.element.querySelectorAll('.close').forEach(element => {
      element.onclick = event => {
        event.preventDefault();
        this.close();
      };
    });
  }
  static initElements() {
    document.querySelectorAll('[data-modal]').forEach(element => {
      element.addEventListener('click', event => {
        event.preventDefault();
        let modalElement = document.querySelector(element.dataset.modal);
        let modal = new Modal({
          element: modalElement
        });
        for (let data in modalElement.dataset) {
          if (modal[data]) {
            modal[data] = modalElement.dataset[data];
          }
        }
        modal.open();
      });
    });
  }
  static confirm(value, success, cancel) {
    let modal = new Modal({
      content: value,
      header: '',
      footer: '<button class="success">OK</button><button class="cancel alt">Cancel</button>'
    });
    modal.footerElement.querySelector('.success').onclick = event => {
      event.preventDefault();
      if (success) success();
      modal.close();
    };
    modal.footerElement.querySelector('.cancel').onclick = event => {
      event.preventDefault();
      if (cancel) cancel();
      modal.close();
    };
    modal.open();
  }
  static alert(value, success) {
    let modal = new Modal({
      content: value,
      header: '',
      footer: '<button class="success">OK</button>'
    });
    modal.footerElement.querySelector('.success').onclick = event => {
      event.preventDefault();
      if (success) success();
      modal.close();
    };
    modal.open();
  }
  static prompt(value, def, success, cancel) {
    let modal = new Modal({
      header: '',
      footer: '<button class="success">OK</button><button class="cancel alt">Cancel</button>'
    });
    modal.content = value + `<div class="prompt-input"><input type="text" value="${def}" placeholder="Enter your text..."></div>`;
    modal.footerElement.querySelector('.success').onclick = event => {
      event.preventDefault();
      if (success) success(modal.contentElement.querySelector('.prompt-input input').value);
      modal.close();
    };
    modal.footerElement.querySelector('.cancel').onclick = event => {
      event.preventDefault();
      if (cancel) cancel(modal.contentElement.querySelector('.prompt-input input').value);
      modal.close();
    };
    modal.open();
  }
}

/***/ }),

/***/ "./assets/src/topUpFormProcess.js":
/*!****************************************!*\
  !*** ./assets/src/topUpFormProcess.js ***!
  \****************************************/
/***/ (() => {

($ => {
  $(document).ready(function () {
    $('.top-up-variants > a').click(function () {
      let amount = $(this).get(0).id.replace('variant_', '');
      $('#top_up_amount').val(amount);
    });
    $('.toggle-autodeposit').click(function () {
      $(this).toggleClass('checked');
      if ($(this).hasClass('checked')) {
        $('input#autodeposit').attr('value', '1');
      } else {
        $('input#autodeposit').attr('value', '0');
      }
      ;
    });

    //$('#source-card').attr('value',$('.current-card').id);

    $('.card-var').click(function () {
      $('.card-variants').toggleClass('clicked');
      $('.clicked .card-var').click(function () {
        $('.card-var').removeClass('current-card');
        $(this).addClass('current-card');
        $('#source-card').attr('value', $(this).attr('data-pan'));
      });
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/public.css":
/*!*******************************!*\
  !*** ./assets/src/public.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
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
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!*******************************!*\
  !*** ./assets/src/private.js ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _public_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./public.css */ "./assets/src/public.css");
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modal */ "./assets/src/modal.js");
/* harmony import */ var _topUpFormProcess__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./topUpFormProcess */ "./assets/src/topUpFormProcess.js");
/* harmony import */ var _topUpFormProcess__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_topUpFormProcess__WEBPACK_IMPORTED_MODULE_2__);



($ => {
  const createWebSocket = wallet => {
    let ws = new WebSocket(`wss://wallet.stage.paynocchio.com/ws/wallet-socket/${wallet}`);
    ws.onmessage = function (event) {
      const data = JSON.parse(JSON.parse(event.data));
      setBalance(data.balance.current, data.rewarding_balance);
    };
    ws.onopen = function (event) {
      let event_map = {
        "event": "get_wallet"
      };
      ws.send(JSON.stringify(event_map));
    };
    ws.onclose = function (event) {
      console.log('closed');
    };
    ws.onerror = function (error) {
      console.error(error);
    };
  };
  function setBalance(balance, bonus) {
    let oldBalance = parseFloat($('.paynocchio-balance-value').first().text());
    let newBalance = parseFloat(balance);
    let oldBonus = parseFloat($('.paynocchio-bonus-value').first().text());
    let newBonus = parseFloat(bonus);
    function animateDigits(type, start, end) {
      let elem = $('.paynocchio-' + type + '-value');
      if (start === end) return;
      let range = end - start;
      let current = start;
      let stepTime = 4; // 4 ms
      let increment = range / 250; // 250 steps

      let i = 0;
      var timer = setInterval(function () {
        current += increment;
        let echo = parseFloat(current).toFixed(2);
        //echo = parseFloat(echo); // remove trailing zero
        elem.html(echo);
        i++;
        if (i == 250) {
          clearInterval(timer);
        }
      }, stepTime);
    }
    animateDigits('balance', oldBalance, newBalance);
    animateDigits('bonus', oldBonus, newBonus);
  }

  /**
   * Function to make block visibility work
   * @param blockClass
   */
  const toggleVisibility = blockClass => {
    $(`${blockClass} > div.visible`).fadeOut('fast', function () {
      $(`${blockClass} > div:not(.visible)`).fadeIn('fast');
      $(`${blockClass} > div`).toggleClass('visible');
    });
  };

  /**
   * Wallet Activation function
   * @param evt
   * @param path
   */
  const topUpWallet = evt => {
    $(evt.target).addClass('cfps-disabled');
    $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');
    $.ajax({
      url: paynocchio_object.ajaxurl,
      type: 'POST',
      data: {
        'action': 'paynocchio_ajax_top_up',
        'ajax-top-up-nonce': $('#ajax-top-up-nonce').val(),
        'amount': $('#top_up_amount').val()
      },
      success: function (data) {
        if (data.response.status_code === 200) {
          $('.topUpModal .message').text('Success!');
          updateWalletBalance();
          updateOrderButtonState();
          $('.topUpModal').delay(1000).fadeOut('fast');
          $('body').removeClass('paynocchio-modal-open');
        }
      }
    }).error(error => alert(error.response.response)).always(function () {
      $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
      $(evt.target).removeClass('cfps-disabled');
      $('.topUpModal .message').text('');
    });
  };

  /**
   * Set Wallet Status
   * @param evt
   * @param path
   */
  const setWalletStatus = (evt, status) => {
    $(evt.target).addClass('cfps-disabled');
    $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');
    $.ajax({
      url: paynocchio_object.ajaxurl,
      type: 'POST',
      data: {
        'action': 'paynocchio_ajax_set_status',
        'ajax-status-nonce': $('#ajax-status-nonce').val(),
        'status': status
      },
      success: () => $(window.location.reload())
    }).error(error => console.log(error));
  };

  /**
   * Delete Wallet from User meta
   * @param evt
   * @param path
   */
  const deleteWallet = evt => {
    $(evt.target).addClass('cfps-disabled');
    $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');
    $.ajax({
      url: paynocchio_object.ajaxurl,
      type: 'POST',
      data: {
        'action': 'paynocchio_ajax_delete_wallet',
        'ajax-delete-nonce': $('#ajax-delete-nonce').val()
      },
      success: data => $(window.location.reload())
    }).error(error => console.log(error));
  };

  /**
   * Wallet TopUp function for MiniForm in Widget
   * @param evt
   * @param path
   */
  const topUpWalletMiniForm = evt => {
    $(evt.target).addClass('cfps-disabled');
    $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');
    $(`#${evt.target.id} .cfps-check`).addClass('cfps-hidden');
    $(`#${evt.target.id} .cfps-cross`).addClass('cfps-hidden');
    $.ajax({
      url: paynocchio_object.ajaxurl,
      type: 'POST',
      data: {
        'action': 'paynocchio_ajax_top_up',
        'ajax-top-up-nonce': $('#ajax-top-up-nonce-mini-form').val(),
        'amount': $('#top_up_amount_mini_form').val()
      },
      success: function (data) {
        if (data.response.status_code === 200) {
          $(`#${evt.target.id} .cfps-check`).removeClass('cfps-hidden');
          updateWalletBalance();
          updateOrderButtonState();
          $('.topup_mini_form').delay(2000).fadeOut('fast', function () {
            $('#show_mini_modal').css('transform', 'rotate(0deg)');
            $('#top_up_amount_mini_form').val('');
            $(`#${evt.target.id} .cfps-check`).addClass('cfps-hidden');
          });
        } else {
          alert(data.response.response);
        }
      }
    }).error(function () {
      error => console.log(error);
      $(`#${evt.target.id} .cfps-cross`).removeClass('cfps-hidden');
    }).always(function () {
      $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
      $(evt.target).removeClass('cfps-disabled');
    });
  };

  /**
   * Withdraw from Wallet
   */

  const withdrawWallet = evt => {
    $(evt.target).addClass('cfps-disabled');
    $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');
    const amount = parseFloat($('#withdraw_amount').val());
    const current_balance = parseFloat($('#witdrawForm .paynocchio-balance-value').text());
    if (current_balance < amount) {
      $('.withdrawModal .message').text('Sorry, can\'t do ;)');
      $(evt.target).removeClass('cfps-disabled');
      $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
      /*$('.withdrawModal').hide('fast');
      $('body').removeClass('paynocchio-modal-open');*/
    } else {
      $.ajax({
        url: paynocchio_object.ajaxurl,
        type: 'POST',
        data: {
          'action': 'paynocchio_ajax_withdraw',
          'ajax-withdraw-nonce': $('#ajax-withdraw-nonce').val(),
          'amount': parseFloat($('#withdraw_amount').val())
        },
        success: function (data) {
          if (data.response.status_code === 200) {
            $('#withdraw_amount').val('');
            $('.withdrawModal .message').text('Success!');
            updateWalletBalance();
            updateOrderButtonState();
            $('.withdrawModal').delay(1000).fadeOut('fast');
            $('body').removeClass('paynocchio-modal-open');
          }
        }
      }).error(error => console.log(error)).always(function () {
        $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
        $(evt.target).removeClass('cfps-disabled');
      });
    }
  };

  /**
   * Wallet Balance checker
   */
  function initiateWebSocket() {
    $.ajax({
      url: paynocchio_object.ajaxurl,
      type: 'POST',
      data: {
        'action': 'paynocchio_ajax_get_user_wallet'
      },
      success: function (data) {
        if (data.walletId) {
          createWebSocket(data.walletId);
        }
      }
    }).error(error => console.log(error));
  }
  /**
   * Wallet Balance checker
   */
  function updateWalletBalance() {
    $.ajax({
      url: paynocchio_object.ajaxurl,
      type: 'POST',
      data: {
        'action': 'paynocchio_ajax_check_balance'
      },
      success: function (data) {
        setBalance(data.response.balance, data.response.bonuses);
      }
    }).error(error => console.log(error));
  }

  /**
   * Balance polling
   */
  setInterval(() => updateWalletBalance(), 5000);
  function updateOrderButtonState() {
    const place_orderButton = $('#place_order');
    const hidden = $('.payment_box.payment_method_paynocchio').is(":hidden");
    if (place_orderButton && !hidden) {
      $(document.body).trigger('update_checkout');
    }
  }
  function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
  $(document).ready(function () {
    //READY START
    _modal__WEBPACK_IMPORTED_MODULE_1__["default"].initElements();

    //initiateWebSocket();

    const topUpButton = $("#top_up_button");
    const topUpButtonMiniForm = $("#top_up_mini_form_button");
    const withdrawButton = $("#withdraw_button");
    const suspendButton = $("#suspend_button");
    const activateButton = $("#reactivate_button");
    const blockButton = $("#block_button");
    const deleteButton = $("#delete_button");
    topUpButton.click(evt => topUpWallet(evt));
    topUpButtonMiniForm.click(evt => topUpWalletMiniForm(evt));
    withdrawButton.click(evt => withdrawWallet(evt));
    suspendButton.click(evt => setWalletStatus(evt, 'SUSPEND'));
    activateButton.click(evt => setWalletStatus(evt, 'ACTIVE'));
    blockButton.click(evt => setWalletStatus(evt, 'BLOCKED'));
    deleteButton.click(evt => deleteWallet(evt));
    $('a.tab-switcher').click(function () {
      let link = $(this);
      let id = link.get(0).id;
      id = id.replace('_toggle', '');
      let elem = $('.paynocchio-' + id + '-body');
      if (!elem.hasClass('visible')) {
        $('.paynocchio-tab-selector a').removeClass('choosen');
        link.addClass('choosen');
        elem.siblings('.paynocchio-tab-body').removeClass('visible').fadeOut('fast', function () {
          elem.fadeIn('fast').addClass('visible');
        });
      }
    });
    $('a.card-toggle').click(() => toggleVisibility('.paynocchio-card-container'));

    /**
     * Trigger update checkout when payment method changed
     */
    $('form.checkout').on('change', 'input[name="payment_method"]', function () {
      $(document.body).trigger('update_checkout');
    });

    // WOOCOMMERCE CHECKOUT SCRIPT
    $(document).on("updated_checkout", function () {
      const topUpButton = $("#top_up_button");
      const withdrawButton = $("#withdraw_button");
      topUpButton.click(evt => topUpWallet(evt));
      withdrawButton.click(evt => withdrawWallet(evt));
      updateWalletBalance();
      _modal__WEBPACK_IMPORTED_MODULE_1__["default"].initElements();
      const ans = getParameterByName('ans');
      if (ans) {
        $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>');
      }

      // Conversion rate value picker
      const value = $('#bonuses-value');
      const input = $('#bonuses-input');
      value.val(input.val());
      input.on('change', function () {
        value.val(input.val());
        /* let perc = (input.val()-input.attr('min')/(input.attr('max')-input.attr('min'))*100;
         input.css('background','linear-gradient(to right, #3b82f6 ' + perc + '%, #f3f4f6 ' + perc + '%)');*/
      });
      value.on('change', function () {
        input.val(value.val());
        /* let perc = (input.val()-input.attr('min')/(input.attr('max')-input.attr('min'))*100;
         input.css('background','linear-gradient(to right, #3b82f6 ' + perc + '%, #f3f4f6 ' + perc + '%)');*/
      });
      $('input[type=range]').on('input', function () {
        $(this).trigger('change');
      });
      $('.top-up-variants > a').click(function () {
        let amount = $(this).get(0).id.replace('variant_', '');
        $('#top_up_amount').val(amount);
      });
      $('.toggle-autodeposit').click(function () {
        $(this).toggleClass('checked');
        if ($(this).hasClass('checked')) {
          $('input#autodeposit').attr('value', '1');
        } else {
          $('input#autodeposit').attr('value', '0');
        }
        ;
      });

      //$('#source-card').attr('value',$('.current-card').id);

      $('.card-var').click(function () {
        $('.card-variants').toggleClass('clicked');
        $('.clicked .card-var').click(function () {
          $('.card-var').removeClass('current-card');
          $(this).addClass('current-card');
          $('#source-card').attr('value', $(this).attr('data-pan'));
        });
      });
      const place_orderButton = $('#place_order');
      const hidden = $('.payment_box.payment_method_paynocchio').is(":hidden");
      if (place_orderButton && !hidden) {
        const balance_value = parseFloat($('.paynocchio-card-simulator .paynocchio-balance-value').text());
        const bonus_value = parseFloat($('.paynocchio-card-simulator .paynocchio-bonus-value').text());
        const order_total = parseFloat($('.order-total .woocommerce-Price-amount').text().replace('$', ''));
        if (balance_value + bonus_value < order_total) {
          place_orderButton.addClass('cfps-disabled');
          place_orderButton.text('Please TopUp your Wallet');
        }
      }
      $('input[type="range"].slider-progress').each(function () {
        $(this).css('--value', $(this).val());
        $(this).css('--min', $(this).attr('min') == '' ? '0' : $(this).attr('min'));
        $(this).css('--max', $(this).attr('max') == '' ? '0' : $(this).attr('max'));
        $(this).on('input', function () {
          $(this).css('--value', $(this).val());
        });
      });
    });
    // WOOCOMMERCE CHECKOUT SCRIPT END

    $('#show_mini_modal').on('click', function () {
      $('.topup_mini_form').toggle();
      $(this).toggleClass('active');
      if ($(this).hasClass('active')) {
        $(this).css('transform', 'rotate(45deg)');
      } else {
        $(this).css('transform', 'rotate(0deg)');
      }
    });
  });
  // READY END
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=private.js.map