import React, { useState } from 'react';
import { Redirect } from 'react-router-dom'
import { postData } from "../../../functions/functions";

import {
	
	useHistory,
	
 } from "react-router-dom";

export const Login = (props: any) => {
	const [email, setEmail] = useState<string>();
	const [password, setPassword] = useState<string>()

	let history = useHistory();

	if (localStorage.getItem('token') !== null) {
		return (
			<Redirect from='/login' to='/' />
		);
	}

	const emailChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
		setEmail(event.target.value)
	}

	const passwordChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
		setPassword(event.target.value)
	}


	const handleLogin = async (e: any) => {
		e.preventDefault()
		const data = await postData('http://tattelekomgraph/GraphServer/user/login', {
			email: email,
			password: password
		}, '');

		if (data['accessToken'] != null) {
			localStorage.setItem('token', data['accessToken']);
			props.setTokenExists(true)
			history.push("/")
		}
	}

	return (
		<div>
			<div className="row">
				<form className="col s12">
					<div className="row">
						<div className="input-field col s12">
							<input id="email" value={email} onChange={emailChangeHandler} type="email" className="validate" />
							<label htmlFor="email">Email</label>
						</div>
					</div>
					<div className="row">
						<div className="input-field col s12">
							<input id="password" type="password" value={password} onChange={passwordChangeHandler} className="validate" />
							<label htmlFor="password">Password</label>
						</div>
					</div>
					<button className="waves-effect waves-light btn-small" onClick={handleLogin}>Войти</button>
				</form>
			</div>
		</div>
	);
}