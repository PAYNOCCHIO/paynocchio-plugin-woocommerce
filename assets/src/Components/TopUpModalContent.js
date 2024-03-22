import React from "react";
import Modal from "./Modal";
import {__} from "@wordpress/i18n";

import icon_mc from "../../img/mc.png";
import icon_vs from "../../img/vs.png";
import icon_arr_d from "../../img/arr_d.png";
import icon_arr_rb from "../../img/arr_rb.png";
import icon_plus_b from "../../img/plus_b.png";
import icon_i_gr from "../../img/i-gr.png";

export default function TopUpModal({onClose}) {
    return (
        <Modal onClose={ onClose }>
            <Modal.Header onClose={ onClose }>{__('TopUp Wallet')}</Modal.Header>
            <Modal.Content><div className="">
                <p className="cfps-text-gray-500">
                    From
                </p>
                <div className="card-variants">
                    <div className="card-var current-card" data-pan="<?php echo $wallet['card_number']; ?>">
                        <div className="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                            <img src={icon_mc} className="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block"/>
                            <p>1356 5674 2352 2373</p>
                        </div>
                        <img src={icon_arr_d} className="cfps-h-[30px] cfps-w-[30px] cfps-inline-block"/>
                    </div>
                    <div className="card-var" data-pan="3727844328348156">
                        <div className="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                            <img src={icon_vs} className="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block"/>
                            <p>3727 8443 2834 8156</p>
                        </div>
                        <img src={icon_arr_d} className="cfps-h-[30px] cfps-w-[30px] cfps-inline-block"/>
                    </div>
                    <div className="card-var" data-pan="">
                        <div className="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                            <img data-modal=".paymentMethodModal" src={icon_plus_b}
                                 className="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block"/>
                            <p data-modal=".paymentMethodModal">Add new payment method</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="source-card" name="source-card" value=""/>

                <div className="top-up-amount-container cfps-mt-8 lg:cfps-mt-12 cfps-flex cfps-flex-row">
                    <p className="cfps-text-3xl">$</p>
                    <input type="number" step="0.01"
                           className="cfps-bg-white cfps-border-0 cfps-text-3xl !cfps-p-0 focus:!cfps-outline-none"
                           name="amount" id="top_up_amount" placeholder="0" value="1000"/>

                </div>

                <div
                    className="top-up-variants cfps-flex cfps-flex-row cfps-items-center cfps-mt-8 lg:cfps-mt-12 cfps-gap-x-2">
                    <a className="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer"
                       id="variant_1000">
                        $1000
                    </a>
                    <a className="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer"
                       id="variant_2000">
                        $2000
                    </a>
                    <a className="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer"
                       id="variant_5000">
                        $5000
                    </a>
                    <a className="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer"
                       id="variant_10000">
                        $10 000
                    </a>
                    <a className="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer"
                       id="variant_15000">
                        $15 000
                    </a>
                </div>
                <p className="cfps-flex cfps-flex-row cfps-items-center cfps-mt-4 cfps-gap-x-0.5">
                    The sending bank may charge a fee.<a href="#">Here's how to avoid it.</a>
                    <img src={icon_arr_rb}
                         className="cfps-h-[18px] cfps-w-[18px] cfps-inline-block"/>
                </p>

                <div className="autodeposit cfps-flex cfps-flex-row cfps-items-center cfps-mt-8 lg:cfps-mt-12 cfps-gap-x-2">
                    <img src={icon_i_gr}
                         className="cfps-h-[18px] cfps-w-[16px] cfps-mr-1 cfps-inline-block"/>
                    Autodeposit
                    <div className="toggle-autodeposit">
                        <p>ON</p>
                        <div className="toggler"></div>
                        <p>OFF</p>
                    </div>
                    <input className="hidden" value="0" name="autodeposit" id="autodeposit"/>
                </div>

                <div>
                    <button id="top_up_button"
                            type="button"
                            className="cfps-btn-primary">
                        Top up
                        <svg
                            className="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle className="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path className="cfps-opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            </Modal.Content>
        </Modal>

    );
}