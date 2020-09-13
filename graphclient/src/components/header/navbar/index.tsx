import React from 'react';
import '../../../index.css';

export const Navbar: React.FC = () => {
    if (localStorage.getItem('token') !== null){
        return (
            <nav>
                <div className="nav-wrapper">
                    <a href="/" className="brand-logo">Граф сервис</a>
                    <ul id="nav-mobile" className="right hide-on-med-and-down">
                        <li><a href="/logout">Выйти</a></li>
                    </ul>
                </div>
            </nav>
        );
    }

    return (
        <nav>
            <div className="nav-wrapper">
                <a href="#" className="brand-logo">Граф сервис</a>
                <ul id="nav-mobile" className="right hide-on-med-and-down">
                    <li><a href="/login">Войти</a></li>
                    <li><a href="/signup">Зарегистрироваться</a></li>
                </ul>
            </div>
        </nav>
    );
}
