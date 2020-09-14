import React, {useState} from 'react';
import '../../../index.css';

import {Redirect} from "react-router-dom";
import {postData} from "../../../functions/functions";

export const Signup: React.FC = () => {
    const [firstName, setFirstName] = useState<string>()
    const [email, setEmail] = useState<string>()
    const [password, setPassword] = useState<string>()

    if (localStorage.getItem('token') != null) {
        return (
            <Redirect from='/login' to='/'/>
        );
	 }
	 
	 const handleReg = async (event: any) => {
        event.preventDefault()
        await postData('http://GraphWork/GraphServer/user/signup', {
            name: firstName,
            email: email,
            password: password
        }, '')
	 }

    function changeFirstName(event: React.ChangeEvent<HTMLInputElement>) {
        setFirstName(event.target.value)
    }

    function changeEmail(event: React.ChangeEvent<HTMLInputElement>) {
        setEmail(event.target.value)
    }

    function changePassword(event: React.ChangeEvent<HTMLInputElement>) {
        setPassword(event.target.value)
    }

    return (
        <div>
            <div className="row">
                <form className="col s12">
                    <div className="row">
                        <div className="input-field col s6">
                            <input value={firstName} onChange={changeFirstName} placeholder="Placeholder" id="firstName" type="text" className="validate"/>
                                <label htmlFor="first_name">Имя</label>
                        </div>
                    </div>
                    <div className="row">
                        <div className="input-field col s12">
                            <input value={email} onChange={changeEmail} id="email" type="email" className="validate"/>
                            <label htmlFor="email">Почта</label>
                        </div>
                    </div>
                    <div className="row">
                        <div className="input-field col s12">
                            <input value={password} onChange={changePassword} id="password" type="password" className="validate"/>
                            <label htmlFor="password">Пароль</label>
                        </div>
                    </div>
						  <button className="waves-effect waves-light btn-small" onClick={handleReg}>Зарегистрироваться</button>
                </form>
            </div>
        </div>
    );
}