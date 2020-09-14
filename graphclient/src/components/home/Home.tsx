import React from 'react';
import {Main} from "../main/graphList";
// import style from 'style.module.css';

interface Graph {
    graphListChanged: boolean,
    graphListWasChanged: Function
}

export const Home = (props: Graph) => {
    return (
        <div>
            <Main graphListChanged={props.graphListChanged} graphListWasChanged={props.graphListWasChanged}/>
        </div>
    );
}