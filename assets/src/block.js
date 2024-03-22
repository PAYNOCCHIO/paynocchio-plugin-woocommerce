import { useState, useEffect } from '@wordpress/element';
import { sprintf, __ } from '@wordpress/i18n';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';

import cashback_ill from "../img/cashback_ill.png"

import Modal from "./Components/Modal";
import ActivationBlock from "./Components/ActivationBlock";
import RegistrationBlock from "./Components/RegistrationBlock";
import TopUpModal from "./Components/TopUpModalContent";

const settings = getSetting( 'paynocchio_data', {} );

const PaymentBlock = ({bonuses, setBonuses}) => {

    const [ isOpen, setOpen ] = useState( false );
    const [ isTopUpModalOpen, setTopUpModalOpen ] = useState( false );
    const [ isUser, setIsUser ] = useState( settings.user );


    const openModal = () => setOpen( true );
    const openTopUpModal = () => setTopUpModalOpen(true);
    const closeModal = () => setOpen( false );
    const closeTopUpModal = () => setTopUpModalOpen( false );

    let max_bonuses;
    if(settings.wallet.balance < settings.cart_total) {
        max_bonuses = settings.wallet.balance;
    } else {
        max_bonuses = settings.cart_total;
    }

    if(!isUser) {
        return <RegistrationBlock />
    }

    return (<div className="paynocchio-payment-block">
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
                    <a className="btn-blue" onClick={ openTopUpModal } >Add money</a>
                    <a className="btn-white" >Withdraw</a>
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
        {settings.wallet.bonuses &&
        <div className="paynocchio-conversion-rate cfps-mt-8">

            <h3>
                How much do you want to pay in bonuses?
            </h3>

            <input type="number" className="input-text short"
                   name="bonuses_value" id="bonuses-value" placeholder=""
                   onChange={(event) => setBonuses(event.target.value)}
                   value={bonuses}
            />
            <input
                type="range"
                name={'bonuses_input'}
                id={'bonuses_input'}
                min="0"
                max={max_bonuses}
                value={bonuses}
                onChange={(event => setBonuses(event.target.value))}/>

        </div>

        }

        <div className="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-mt-8">
            <a href="#">Manage Cards</a>
            <a href="#">History</a>
            <a href="#">Support</a>
            <a href="#">Terms</a>
            <span role={'button'} onClick={ openModal }>
                Open Modal
            </span>
        </div>

        { isOpen && (
            <Modal onClose={ closeModal }>
                <Modal.Header onClose={ closeModal }>{__('TopUp Wallet')}</Modal.Header>
                <Modal.Content>hello</Modal.Content>
            </Modal>
        ) }

        { isTopUpModalOpen && <TopUpModal onClose={ closeTopUpModal } /> }
    </div>);
}

const defaultLabel = __(
    'Paynocchio Payment',
    'woocommerce-paynocchio'
);

const label = decodeEntities( settings.title ) || defaultLabel;
/**
 * Content component
 */
const Content = (props) => {
    const { eventRegistration, emitResponse } = props;
    const { onPaymentSetup } = eventRegistration;
    const [ bonuses, setBonus ] = useState(0);

    useEffect( () => {
        const unsubscribe = onPaymentSetup( async () => {
            // Here we can do any processing we need, and then emit a response.
            // For example, we might validate a custom field, or perform an AJAX request, and then emit a response indicating it is valid or not.
            const bonusesValue = bonuses;
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
        onPaymentSetup,
        bonuses
    ] );
    return (
        <PaymentBlock bonuses={bonuses} setBonuses={setBonus} />
    )
};

const DummyContent = () => {
    return (
        <div>Best Payment method ever</div>
    );
}
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
    edit: <DummyContent />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings.supports,
    },
};

registerPaymentMethod( Paynocchio );