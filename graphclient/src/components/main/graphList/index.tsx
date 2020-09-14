import React, {useState} from 'react';
import '../../../index.css';
import {GraphList} from "./graph_list";
import {GraphCreating} from "./create_graph_form";

interface Graph {
    graphListChanged: boolean,
    graphListWasChanged: Function
}

export const Main = (props: Graph) => {
    return (
        <div className="main">
            <GraphCreating graphListChanged={props.graphListChanged} graphListWasChanged={props.graphListWasChanged}/>
            <GraphList graphListChanged={props.graphListChanged} graphListWasChanged={props.graphListWasChanged}/>
        </div>
    );
}