import Modal from "./Modal";
import {__} from "@wordpress/i18n";
import {useFetch} from "../hooks/useFetch";
import Loader from "./UI/Loader";

export default function TopupModal({onClose}) {

    const {data, error, loading} = useFetch('paynocchio_ajax_check_balance');

    return (
        <Modal onClose={ onClose }>
            <Modal.Header onClose={ onClose }>{__('TopUp Wallet')}</Modal.Header>
            <Modal.Content>
                {loading && <Loader />}
                {!loading && data.balance}
            </Modal.Content>
        </Modal>
    );
}