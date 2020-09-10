import React from 'react';
import './../../index.css';
import {Header} from "../header";
import {Main} from "../main";
import {Footer} from "../footer";

export const Home: React.FC = () => {
    return (
        <div>
            <Header/>
            <Main/>
            <Footer/>
        </div>
    );
}