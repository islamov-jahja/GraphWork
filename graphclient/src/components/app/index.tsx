import React from 'react';
import './../../index.css';
import {
    BrowserRouter,
    Route,
    Switch
} from "react-router-dom"

import {Home} from "../home";
import {Login} from "../main/login";
import {Signup} from "../main/signup";
import {GraphComponent} from "../main/graph";
import {Logout} from "../main/logout";

export const Index: React.FC = () => {
    return (
        <div className="app">
            <BrowserRouter>
                <Switch>
                    <Route component={Home} path='/' exact/>
                    <Route component={Login} path='/login' />
                    <Route component={Signup} path='/signup' />
                    <Route component={Logout} path='/logout' />
                    <Route component={GraphComponent} path='/graph' />
                </Switch>
            </BrowserRouter>
        </div>
    );
}
