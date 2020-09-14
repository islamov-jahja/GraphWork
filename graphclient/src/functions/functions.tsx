
import React from "react";

export async function postData(url: Request | string = '', data: any = {}, token: string | null) {
    const response = await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: JSON.stringify(data)
    });

    if (!response.ok) {
        return null;
    }

    return await response.json(); // parses JSON response into native JavaScript objects
}

export async function putMethod(url: Request | string = '', data: any = {}, token: string | null){
    await fetch(url, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: {
            'Authorization': 'Bearer ' + token
        },
    });
}

export async function deleteMethod(url: string = '', token: string | null) {
    await fetch(url, {
        method: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + token
        },
    });
}
export async function getData(url: Request | string = '', token: string | null) {
    const response = await fetch(url, {
        headers: {
            'Authorization': 'Bearer ' + token
        },
    })

    if (!response.ok) {
        return null;
    }

    return await response.json(); // parses JSON response into native JavaScript objects
}

export const onClickGraph = function (event: any) {
    console.log('Clicked the graph background');
};

export const onClickNode = function (nodeId: any) {
    return <div>
        hello world
    </div>
    console.log('Clicked node ${nodeId}');
};

export const onDoubleClickNode = function (nodeId: any) {
    console.log('Double clicked node ${nodeId}');
};

export const onRightClickNode = function (event: any, nodeId: any) {
    let nodeIdOnInt: number = nodeId.split(':')[0];
    deleteMethod('http://GraphWork/GraphServer/graph/' + localStorage.getItem('graphId') + '/vertex/' + nodeIdOnInt, localStorage.getItem('token'))
};

export const onMouseOverNode = function (nodeId: any) {
    console.log(`Mouse over node ${nodeId}`);
};

export const onMouseOutNode = function (nodeId: any) {
    console.log(`Mouse out node ${nodeId}`);
};

export const onClickLink = function (source: any, target: any) {
    console.log(`Clicked link between ${source} and ${target}`);
};

export const onMouseOverLink = function (source: any, target: any) {
    console.log(`Mouse over in link between ${source} and ${target}`);
};

export const onMouseOutLink = function (source: any, target: any) {
    console.log(`Mouse out link between ${source} and ${target}`);
};

export const onNodePositionChange = function (nodeId: any, x: any, y: any) {
    console.log(`Node ${nodeId} moved to new position x= ${x} y= ${y}`);
};

export const getEdgeId = function (vertexes: any, firstVertexId: number, secondVertexId: number): number{
    let edgeId = -1;
    vertexes.map((v: any) =>{
        if (v.id == firstVertexId){
            v.edges.map((e: any) => {
                if (e.secondVertexId == secondVertexId){
                    edgeId = e.id
                }
            })
        }
    });

    return edgeId
}