import React from 'react';
import '../../../index.css';
import {GraphList} from "./graph_list";
import {GraphCreating} from "./create_graph_form";

export const Main: React.FC = () => {

    return (
        <div className="main">
            <GraphCreating/>
            <GraphList/>
        </div>
    );
}