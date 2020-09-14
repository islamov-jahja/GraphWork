import React, {useState} from 'react';
import {postData} from "../../functions/functions";
import {StateManager} from '../../functions/states'

interface GraphList {
    graphListChanged: boolean,
    graphListWasChanged: Function
}

export const CreateVertex = (props: any) => {
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

            props.graphListWasChanged(!props.graphListChanged)
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