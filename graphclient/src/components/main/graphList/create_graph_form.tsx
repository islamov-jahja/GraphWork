import React, {useState} from 'react';
import '../../../index.css';
import {postData} from "../../../functions/functions";
import {Redirect} from "react-router-dom";

export const GraphCreating: React.FC = () => {
    const [graphName, setGraphName] = useState<string>()
    const graphNameChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
        setGraphName(event.target.value)
    }

    const onCreateGraph = async (event: React.KeyboardEvent) => {
        if (event.key == 'Enter') {
            event.preventDefault()
            const data = await postData('http://tattelekomgraph/GraphServer/graph', {
                name: graphName
            }, localStorage.getItem('token'));
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