import React, {useEffect, useState} from 'react';
import '../../../index.css';
import {Graph, GraphNode, GraphLink} from 'react-d3-graph';

import {
    getData,
    onClickGraph,
    onClickLink,
    onClickNode,
    onDoubleClickNode,
    onMouseOutLink,
    onMouseOutNode,
    onMouseOverLink,
    onMouseOverNode,
    onRightClickLink,
    onRightClickNode
} from "../../../functions/functions";
import {Header} from "../../header";
import {Footer} from "../../footer";
import {CreateVertex} from "./create_vertex_form";
import {StateManager} from "../../../functions/states";

export const GraphComponent: React.FC = (props) => {
    let id = new URLSearchParams(window.location.search).get("id")
    if (id != null) {
        localStorage.setItem('graphId', id)
    }
    const [graph, refreshGraph] = useState({
        name: String, id: Number,
        vertexes: [{
            id: Number, name: String, edges: [{
                id: Number, secondVertexId: Number, weight: Number
            }]
        }]
    });

    useEffect(() => {
        async function getGraph() {
            const result = await getData('http://tattelekomgraph/GraphServer/graph/' + localStorage.getItem('graphId'), localStorage.getItem('token'))
            refreshGraph(result)
        }

        getGraph()
        if (localStorage.getItem('loadGraph') == null){
            localStorage.setItem('loadGraph', '0')
        }

    }, [StateManager.needLoadGraph]);

    let GraphLib = require("graph-data-structure");
    let graphObject = GraphLib()

    let vertexes = graph.vertexes;
    if (vertexes != undefined) {
        let addingNodes: string[] = []
        vertexes.forEach((vertex) => {
            let edges: Array<any> = vertex.edges;
            graphObject.addNode(vertex.id + ':' + vertex.name)
            if (edges != undefined) {
                edges.map((edge) => {
                    let nameOfSecondVertex
                    vertexes.map((v) => {
                        if (v.id == edge.secondVertexId) {
                            nameOfSecondVertex = v.name
                        }
                    })

                    graphObject.addEdge(vertex.id + ':' + vertex.name, edge.secondVertexId + ':' + nameOfSecondVertex, edge.weight)
                })
            }
        })
    }

    const myConfig = {
        highlightDegree: 0,
        linkLength: 40,
        labelProperty: "refljr",
        renderLabel: true,
        directed: true,
        height: 1000,
        width: 1200,
        panAndZoom: true,
        staticGraphWithDragAndDrop: true,
        staticGraph: false,
        automaticRearrangeAfterDropNode: true,
        node: {
            color: 'lightgreen',
            highlightStrokeColor: 'blue',
            size: 500,
            fontSize: 14
        },
        link: {
            highlightColor: 'lightblue',
            renderLabel: true
        }
    };


    if (vertexes == undefined) {
        return (
            <div>
                <Header/>
                <CreateVertex/>
                <Footer/>
            </div>
        )
    }

    return (
        <div>
            <Header/>
            <CreateVertex/>
            <Graph
                id='graph-id'
                data={graphObject.serialize()}
                config={myConfig}
                onClickGraph={onClickGraph}
                onClickNode={onClickNode}
                onDoubleClickNode={onDoubleClickNode}
                onRightClickNode={onRightClickNode}
                onClickLink={onClickLink}
                onRightClickLink={onRightClickLink}
                onMouseOverNode={onMouseOverNode}
                onMouseOutNode={onMouseOutNode}
                onMouseOverLink={onMouseOverLink}
                onMouseOutLink={onMouseOutLink}
            />

            <Footer/>
        </div>
    );
}