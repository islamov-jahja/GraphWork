import React, {useEffect, useState} from 'react';
import '../../../index.css';
import {deleteMethod, getData, postData} from "../../../functions/functions";

export const GraphList: React.FC = () => {
    const [graphs, setData] = useState([]);
    useEffect(() => {
        async function getGraphs() {
            const result = await getData('http://tattelekomgraph/GraphServer/graph/limit/100/page/1', localStorage.getItem('token'))
            setData(result)
        }

        getGraphs()
    }, []);

    const deleteGraph = (id: number) => {
        deleteMethod('http://tattelekomgraph/GraphServer/graph/' + id, localStorage.getItem('token'));
    }

    return (
        <ul className="collection with-header">
            <li className="collection-header"><h4>Графы</h4></li>
            {graphs.map((g) => {
                return <li className="collection-item">
                    <div>{g['name']}<a href={"/graph?id=" + g['id']} className="secondary-content"><i className="material-icons">send</i></a></div>
                    <a className="waves-effect waves-light btn-small" onClick={() => deleteGraph(g['id'])}>удалить</a>
                </li>
            })}
        </ul>
    );
}