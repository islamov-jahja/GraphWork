import React from 'react';
import './../../index.css';
import {
    BrowserRouter,
    Route,
    Switch
} from "react-router-dom"

import {Home} from "../home";
import {Login} from "../login";
import {Signup} from "../signup";
import {GraphComponent} from "../graph";
import {Logout} from "../logout";

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
