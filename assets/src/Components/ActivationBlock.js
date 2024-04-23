import debit_card_example from "../../img/debit_card_example.png";
import icon_tickets from "../../img/icon_tickets.svg";
import icon_dollar from "../../img/icon_dollar.svg";
import icon_coupon from "../../img/icon_coupon.svg";
import icon_magic from "../../img/icon_magic.svg";

const ActivationBlock = () => {
    return (
        <section className="paynocchio">
            <div className="article-body cfps-max-w-4xl cfps-mx-auto cfps-mt-4">
                <div className="cfps-mb-10 lg:cfps-mb-20">
                    <img
                        className="cfps-block !cfps-mx-auto"
                        src={debit_card_example}
                        alt=""/>
                </div>
                <div className="cfps-grid cfps-grid-cols-[40px_1fr] cfps-gap-x-6 cfps-gap-y-12 cfps-mb-10">
                    <div>
                        <img
                            src={icon_tickets}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="cfps-h2 cfps-mb-0">Fly on the right day. Always.</h2>
                        <p className="cfps-text-base">We will book a ticket and notify you in advance if there is a seat
                            available on the plane. Refund the money for the ticket if you do not fly.</p>
                    </div>
                    <div>
                        <img
                            src={icon_dollar}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="cfps-h2 cfps-mb-0">Ultimate cashback</h2>
                        <p className="cfps-text-base">Make 3 purchases and get an increased cashback on everything</p>
                    </div>
                    <div>
                        <img
                            src={icon_coupon}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="cfps-h2 cfps-mb-0 paynocchio-tab-selector">Pay with bonuses</h2>
                        <p className="cfps-text-base">Make 5 purchases and get 500 bonuses that can be spent on
                            flights.</p>
                    </div>
                    <div>
                        <img
                            src={icon_magic}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="cfps-h2 cfps-mb-0">Unique card design from AI</h2>
                        <p className="cfps-text-base">Our AI will generate your individual unique map design for
                            you.</p>
                    </div>
                </div>
                <div className="cfps-flex cfps-justify-center cfps-mb-10">
                    <button id="paynocchio_activation_button"
                            type="button"
                            className="cfps-btn-primary">
                        Activate Paynocchio.Pay
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

                <div className="cfps-pl-10 cfps-pb-6 cfps-text-center">
                    <p className="cfps-text-slate-500">I agree to <a href="#"
                                                                     className="cfps-text-slate-500 cfps-underline">Paynocchio
                        Terms & Conditions</a> and <a href="#" className="cfps-text-slate-500 cfps-underline">Rules of
                        Paynocchio.Pay Priority program</a></p>
                </div>
            </div>
        </section>
    );
}

export default ActivationBlock;