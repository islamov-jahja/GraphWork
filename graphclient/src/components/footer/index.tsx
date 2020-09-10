import React from 'react';
import './../../index.css';

export const Footer: React.FC = () => {
    return (
        <footer className="page-footer">
            <div className="container">
                <div className="row">
                    <div className="col l6 s12">
                        <h5 className="white-text">Граф сервис</h5>
                    </div>
                </div>
            </div>
            <div className="footer-copyright">
                <div className="container">
                    © 2020 Islamov corporation
                </div>
            </div>
        </footer>
    );
}