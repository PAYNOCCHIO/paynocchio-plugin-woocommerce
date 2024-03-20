import { useState, useEffect } from '@wordpress/element';
import { sprintf, __ } from '@wordpress/i18n';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';
import cashback_ill from "../img/cashback_ill.png"

const settings = getSetting( 'paynocchio_data', {} );

const PaymentBlock = () => {
    const [ bonuses, setBonus ] = useState( 0 );

    return (
        <div className="paynocchio-payment-block">
            <div className="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-x-8 cfps-items-stretch">
                <div className="paynocchio-card-simulator">
                    <h3 className="!cfps-mb-0">Kopybara.Pay</h3>
                    <div className="cfps-flex cfps-flex-row cfps-items-center cfps-text-white cfps-gap-x-8 cfps-text-xl">
                        <div>
                            <p>Balance</p>
                            <p>$<span className="paynocchio-numbers paynocchio-balance-value">{settings.wallet.balance}</span></p>
                        </div>
                        <div>
                            <p>Bonuses</p>
                            <p><span className="paynocchio-numbers paynocchio-bonus-value">{settings.wallet.bonuses}</span></p>
                        </div>
                    </div>

                    <div className="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2">
                        <a href="#" className="btn-blue" data-modal=".topUpModal">Add money</a>
                        <a href="#" className="btn-white" data-modal=".withdrawModal">Withdraw</a>
                    </div>
                </div>
                <div className="paynocchio-promo-badge">
                    <div className={'cfps-grid cfps-grid-cols-2 cfps-gap-4'}>
                        <div>
                            <h3 className="!cfps-mb-0">Ultimate Cashback</h3>
                            <p>Make three purchases and get an increased cashback on everything!</p>
                            <a className="btn-white cfps-absolute cfps-bottom-4 cfps-left-4" href="#">Read more</a>
                        </div>
                        <img src={cashback_ill} className={'object-contain'}/>
                    </div>
                </div>
            </div>
            <div className="paynocchio-conversion-rate cfps-mt-8">
                <h3>
                    How much do you want to pay in bonuses?
                </h3>

                <input type="number" className="input-text short"
                       name="bonuses_value" id="bonuses-value" placeholder=""
                       onChange={(event) => setBonus(event.target.value)}
                       value={bonuses}
                />
                <input
                    type="range"
                    name={'bonuses_input'}
                    id={'bonuses_input'}
                    min="0"
                    max={settings.wallet.bonuses}
                    value={bonuses}
                    onChange={(event => setBonus(event.target.value))} />
            </div>

            <div className="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-mt-8">
                <a href="#">Manage Cards</a>
                <a href="#">History</a>
                <a href="#">Support</a>
                <a href="#">Terms</a>
            </div>
        </div>
    )
}

const defaultLabel = __(
    'Paynocchio Payment',
    'woocommerce-paynocchiok'
);

const label = decodeEntities( settings.title ) || defaultLabel;
/**
 * Content component
 */
const Content = (props) => {
    const { eventRegistration, emitResponse } = props;
    const { onPaymentProcessing } = eventRegistration;
    useEffect( () => {
        const unsubscribe = onPaymentProcessing( async () => {
            // Here we can do any processing we need, and then emit a response.
            // For example, we might validate a custom field, or perform an AJAX request, and then emit a response indicating it is valid or not.
            const bonusesValue = '10';
            const customDataIsValid = !! bonusesValue.length;

            if ( customDataIsValid ) {
                return {
                    type: emitResponse.responseTypes.SUCCESS,
                    meta: {
                        paymentMethodData: {
                            bonusesValue,
                        },
                    },
                };
            }

            return {
                type: emitResponse.responseTypes.ERROR,
                message: 'There was an error',
            };
        } );
        // Unsubscribes when this component is unmounted.
        return () => {
            unsubscribe();
        };
    }, [
        emitResponse.responseTypes.ERROR,
        emitResponse.responseTypes.SUCCESS,
        onPaymentProcessing,
    ] );
    return (
        <>
            <PaymentBlock />
        </>
    )

};
/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = ( props ) => {
    const { PaymentMethodLabel } = props.components;
    return <PaymentMethodLabel text={ label } />;
};

/**
 * Paynocchio payment method config object.
 */
const Paynocchio = {
    name: "paynocchio",
    label: <Label />,
    content: <Content />,
    edit: <Content />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings.supports,
    },
};

registerPaymentMethod( Paynocchio );