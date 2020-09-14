import React, { useState } from 'react'
import { Route, Switch } from "react-router-dom"
import { Navbar } from '../Navbar'
import { Footer } from '../Footer'
import {Home} from "../Home";
import {Login} from "../main/login";
import {Signup} from "../main/signup";
import {GraphComponent} from "../Graph";
import {Logout} from "../main/logout";
import styles from './style.module.css'

export const Layout = () => {
	const [tokenExists, setTokenExists] = useState(false)
	return (
		<div className={styles.layout}>
			<Navbar isAuth={tokenExists} />
			<div className="container">
				<Switch>
					<Route path='/' exact >
						<Home />
					</Route>
					<Route path='/login' >
						<Login setTokenExists={setTokenExists}/>
					</Route>
					<Route path='/signup' >
						<Signup />
					</Route>
					<Route path='/logout' >
						<Logout />
					</Route>
					<Route path='/graph' >
						<GraphComponent />
					</Route>
				</Switch>
			</div>
			<Footer />
		</div>
	)
}