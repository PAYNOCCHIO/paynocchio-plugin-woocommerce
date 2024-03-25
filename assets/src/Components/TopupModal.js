import Modal from "./Modal";
import {__} from "@wordpress/i18n";
import {useFetch} from "../hooks/useFetch";
import Loader from "./UI/Loader";

export default function TopupModal({onClose}) {

    const {data: wallet, error, loading} = useFetch('wallet/current_user');

    return (
        <Modal onClose={ onClose }>
            <Modal.Header onClose={ onClose }>{__('TopUp Wallet')}</Modal.Header>
            <Modal.Content>
                {loading && <Loader />}
                {!loading && wallet.balance}
            </Modal.Content>
        </Modal>
    );
}