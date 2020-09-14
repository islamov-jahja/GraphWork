import React from 'react';
import '../../../index.css';

import {Redirect} from "react-router-dom";

export const Signup: React.FC = () => {
    if (localStorage.getItem('token') != null) {
        return (
            <Redirect from='/login' to='/'/>
        );
	 }
	 
	 const handleReg = () => {

	 }

    return (
        <div>
            <div className="row">
                <form className="col s12">
                    <div className="row">
                        <div className="input-field col s6">
                            <input placeholder="Placeholder" id="first_name" type="text" className="validate"/>
                                <label htmlFor="first_name">Имя</label>
                        </div>
                    </div>
                    <div className="row">
                        <div className="input-field col s12">
                            <input id="email" type="email" className="validate"/>
                            <label htmlFor="email">Почта</label>
                        </div>
                    </div>
                    <div className="row">
                        <div className="input-field col s12">
                            <input id="password" type="password" className="validate"/>
                            <label htmlFor="password">Пароль</label>
                        </div>
                    </div>
						  <button className="waves-effect waves-light btn-small" onClick={handleReg}>Зарегистрироваться</button>
                </form>
            </div>
        </div>
    );
}