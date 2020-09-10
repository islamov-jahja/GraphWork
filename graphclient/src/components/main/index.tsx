import React from 'react';
import './../../index.css';
import {GraphList} from "./body/graph_list";
import {GraphCreating} from "./body/create_graph_form";

export const Main: React.FC = () => {

    return (
        <div className="main">
            <GraphCreating/>
            <GraphList/>
        </div>
    );
}