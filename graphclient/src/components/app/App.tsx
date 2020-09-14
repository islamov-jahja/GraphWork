import React from 'react';
import { BrowserRouter } from "react-router-dom"

import { Layout } from '../Layout'

import style from './style.module.css';

export const App: React.FC = () => {

    return (
		 <BrowserRouter>
			  	<div className={style.AppWrapper}>
					<Layout />
        		</div>
      </BrowserRouter>
    );
}