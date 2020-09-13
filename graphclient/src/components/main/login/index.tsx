import React, {useState} from 'react';
import '../../../index.css';
import {Header} from "../../header";
import {Footer} from "../../footer";
import {Redirect} from 'react-router-dom'
import {postData} from "../../../functions/functions";

export const Login: React.FC = () => {
    const [email, setEmail] = useState<string>();
    const [password, setPassword] = useState<string>()

    const emailChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
        setEmail(event.target.value)
    }

    const passwordChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
        setPassword(event.target.value)
    }

    const onLoginKeyPress = async (event: React.KeyboardEvent) => {
        if (event.key == 'Enter') {
            const data = await postData('http://tattelekomgraph/GraphServer/user/login', {
                email: email,
                password: password
            }, '');

            if (data['accessToken'] != null) {
                localStorage.setItem('token', data['accessToken']);

                return(
                    <Redirect from='/login' to='/'/>
                )
            }
        }
    }

    if (localStorage.getItem('token') !== null) {
        return (
            <Redirect from='/login' to='/'/>
        );
    }

    return (
        <div>
            <Header/>
            <div className="row">
                <form className="col s12">
                    <div className="row">
                        <div className="input-field col s12">
                            <input id="email" value={email} onChange={emailChangeHandler} type="email" className="validate"/>
                            <label htmlFor="email">Email</label>
                        </div>
                    </div>
                    <div className="row">
                        <div className="input-field col s12">
                            <input id="password" type="password" value={password} onChange={passwordChangeHandler} onKeyPress={onLoginKeyPress} className="validate"/>
                            <label htmlFor="password">Password</label>
                        </div>
                    </div>
                </form>
            </div>
            <Footer/>
        </div>
    );
}