import React from "react";
import { createPortal } from 'react-dom';

const Header = ({children, onClose}) => {
    return (
        <div className="header">
            <h3>{children}</h3>
            <button className="close" onClick={onClose}>&times;</button>
        </div>
    );
}

const Content = ({children, className}) => {
    return (
        <div className={`content${className ? ' ' + className : ''}`}>
            {children}
        </div>
    );
}


const Modal = ({children, onClose}) => {

    return (
        <>
            {createPortal(
                <div className="modal medium open">
                    <div className="close-modal close" onClick={onClose}></div>
                    <div className="container">
                        {children}
                    </div>
                </div>,
                document.body
            )}
        </>
    );
}

Modal.Header = Header;
Modal.Content = Content;

export default Modal;