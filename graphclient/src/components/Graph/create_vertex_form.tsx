import React, {useState} from 'react';
import {postData} from "../../functions/functions";
import {StateManager} from '../../functions/states'
export const CreateVertex: React.FC = () => {
    const [vertexName, setVertexName] = useState<string>()
    const vertexNameChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
        setVertexName(event.target.value)
    }

    const onCreateVertex = async (event: React.KeyboardEvent) => {
        if (event.key == 'Enter') {
            event.preventDefault()
            const data = await postData('http://tattelekomgraph/GraphServer/graph/' + localStorage.getItem('graphId') + '/vertex', {
                name: vertexName
            }, localStorage.getItem('token'));

            console.log(StateManager.needLoadGraph);
            StateManager.needLoadGraph = !StateManager.needLoadGraph
            console.log(StateManager.needLoadGraph);
        }
    }

    return (
        <div className="row">
            <form className="col s12">
                <div className="row">
                    <div className="input-field col s12">
                        <input id="vertexName" value={vertexName} onChange={vertexNameChangeHandler}
                               onKeyPress={onCreateVertex}/>
                        <label htmlFor="vertexName"></label>
                    </div>
                </div>
            </form>
        </div>
    );
}