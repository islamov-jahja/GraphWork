import React, {useState} from 'react';
import './../../index.css';
import { Graph } from 'react-d3-graph';

export const GraphComponent: React.FC = () => {
    const [graph, setData] = useState([]);
    const data = {
        nodes: [
            {id: 'Harry'},
            {id: 'Sally'},
            {id: 'Alice'}
        ],
        links: [
            {source: 'Harry', target: 'Sally', weight: 5},
            {source: 'Harry', target: 'Alice', weight: 8},
        ]
    };

    const myConfig = {
        nodeHighlightBehavior: true,
        node: {
            color: 'lightgreen',
            size: 120,
            highlightStrokeColor: 'blue'
        },
        link: {
            highlightColor: 'lightblue',
            renderLabel: true
        }
    };

    const onClickGraph = function(event: any) {
       console.log('Clicked the graph background');
    };

    const onClickNode = function(nodeId: any) {
        console.log('Clicked node ${nodeId}');
    };

    const onDoubleClickNode = function(nodeId: any) {
        console.log('Double clicked node ${nodeId}');
    };

    const onRightClickNode = function(event: any, nodeId: any) {
        console.log('Right clicked node ${nodeId}');
    };

    const onMouseOverNode = function(nodeId: any) {
        console.log(`Mouse over node ${nodeId}`);
    };

    const onMouseOutNode = function(nodeId: any) {
        console.log(`Mouse out node ${nodeId}`);
    };

    const onClickLink = function(source: any, target: any) {
        console.log(`Clicked link between ${source} and ${target}`);
    };

    const onRightClickLink = function(event: any, source: any, target: any) {
        console.log('Right clicked link between ${source} and ${target}');
    };

    const onMouseOverLink = function(source: any, target: any) {
        console.log(`Mouse over in link between ${source} and ${target}`);
    };

    const onMouseOutLink = function(source: any, target: any) {
        console.log(`Mouse out link between ${source} and ${target}`);
    };

    const onNodePositionChange = function(nodeId: any, x: any, y: any) {
        console.log(`Node ${nodeId} moved to new position x= ${x} y= ${y}`);
    };

    return (
        <div>
            <Graph
                id='graph-id' // id is mandatory, if no id is defined rd3g will throw an error
                data={data}
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
        </div>
    );
}