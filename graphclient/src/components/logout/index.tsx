import React from 'react';
import './../../index.css';
import {Redirect} from 'react-router-dom'
import {postData} from "../../functions/login";

export const Logout: React.FC = () => {
    if (localStorage.getItem('token') === null){
        return(
            <Redirect from='/logout' to='/'/>
        )
    }
    postData('http://tattelekomgraph/GraphServer/user/logout', {}, localStorage.getItem('token'));
    localStorage.removeItem('token');

    return (
        <Redirect from='/logout' to='/'/>
    );
}