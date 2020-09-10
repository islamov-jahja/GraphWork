import React, {useState} from 'react';
import './../../../index.css';
import {postData} from "../../../functions/login";
import {Redirect} from "react-router-dom";

export const GraphCreating: React.FC = () => {
    const [graphName, setGraphName] = useState<string>()
    const graphNameChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
        setGraphName(event.target.value)
    }

    const onCreateGraph = async (event: React.KeyboardEvent) => {
        if (event.key == 'Enter') {
            const data = await postData('http://tattelekomgraph/GraphServer/graph?XDEBUG_SESSION_START=PHPSTORM', {
                name: graphName
            }, '');
        }
    }

    return (
        <div className="row">
            <form className="col s12">
                <div className="row">
                    <div className="input-field col s12">
                        <input id="graphName" value={graphName} onChange={graphNameChangeHandler}
                               onKeyPress={onCreateGraph}/>
                        <label htmlFor="graphName"></label>
                    </div>
                </div>
            </form>
        </div>
    );
}