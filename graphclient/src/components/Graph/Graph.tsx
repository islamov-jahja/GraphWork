import React, { useEffect, useState } from 'react';
import { Graph } from 'react-d3-graph';
import GraphLib from "graph-data-structure";
import {deleteMethod, getData, getEdgeId, postData, putMethod} from "../../functions/functions";
import { CreateVertex } from "./create_vertex_form";
import styles from './style.module.css'

const myConfig = {
	highlightDegree: 0,
	linkLength: 40,
	labelProperty: "refljr",
	renderLabel: true,
	directed: true,
	panAndZoom: true,
	automaticRearrangeAfterDropNode: true,
	nodeHighlightBehavior: true,
	linkHighlightBehavior: true,
	node: {
		color: 'lightgreen',
		highlightStrokeColor: 'blue',
		size: 500,
		fontSize: 14
	},
	link: {
		labelProperty: (node: any) => {
			return node.weight
		},
		highlightColor: 'lightblue',
		renderLabel: true
	}
};

interface GraphList {
	graphListChanged: boolean,
	graphListWasChanged: Function
}

export const GraphComponent = (props: GraphList) => {

	const [activeNodes, setActiveNodes]: any[] = useState([])
	const [activeLink, setActiveLink]: any[] = useState([])
	const [weight, setWeight] = useState<any>()

	let id = new URLSearchParams(window.location.search).get("id")
	if (id != null) {
		localStorage.setItem('graphId', id)
	}
	const [graph, refreshGraph] = useState({
		name: String, id: Number,
		vertexes: [{
			id: Number,
			name: String,
			edges: [{
				id: Number,
				secondVertexId: Number,
				weight: Number
			}]
		}]
	});

	useEffect(() => {
		async function getGraph() {
			const result = await getData('http://tattelekomgraph/GraphServer/graph/' + localStorage.getItem('graphId'), localStorage.getItem('token'))
			refreshGraph(result)
		}

		getGraph()
	}, [props.graphListChanged]);

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


	const onClickNode = (nodeId: any) => {
		if (activeNodes.includes(nodeId)) return
		setActiveNodes((prev: any[]) => [...prev, nodeId])
	}

	const handleReset = () => setActiveNodes([])

	const onClickLink = (src: any, trg: any) => {
		setActiveLink([src, trg])
	}

	const onDoubleClickNode = async function (nodeId: any) {
		let vertexId: number = nodeId.split(':')[0]
		await deleteMethod('http://tattelekomgraph/GraphServer/graph/' + localStorage.getItem('graphId') + '/vertex/' + vertexId, localStorage.getItem('token'))
		props.graphListWasChanged(!props.graphListChanged)
	};

	function onChangeWeight(event: React.ChangeEvent<HTMLInputElement>) {
		setWeight(event.target.value)
	}

	async function createEdge() {
		let firstVertexId = activeNodes[0].split(':')[0]
		let secondVertexId = activeNodes[1].split(':')[0]
		console.log(weight)
		await postData('http://tattelekomgraph/GraphServer/graph/' + localStorage.getItem('graphId') + '/edge', {
			weight: weight,
			firstVertexId: firstVertexId,
			secondVertexId: secondVertexId
		}, localStorage.getItem('token'))

		props.graphListWasChanged(!props.graphListChanged)
	}

	const onRightClickLink = async function (event: any, source: any, target: any) {
		let firstVertexId = activeNodes[0].split(':')[0]
		let secondVertexId = activeNodes[1].split(':')[0]
		let edgeId = getEdgeId(graph.vertexes, firstVertexId, secondVertexId);

		await deleteMethod('http://tattelekomgraph/GraphServer/graph/' + localStorage.getItem('graphId') + '/edge/' + edgeId, localStorage.getItem('token'))
		props.graphListWasChanged(!props.graphListChanged)
	};

	async function changeFirstWeight() {
		let firstVertexId = activeNodes[0].split(':')[0]
		let secondVertexId = activeNodes[1].split(':')[0]

		let edgeId = getEdgeId(graph.vertexes, firstVertexId, secondVertexId);

		await putMethod('http://tattelekomgraph/GraphServer/graph/'
			+ localStorage.getItem('graphId') + '/edge/' + edgeId + '/weight/' + weight, {
		}, localStorage.getItem('token'))

		props.graphListWasChanged(!props.graphListChanged)
	}

	function changeSecondWeight() {

	}

	return (
		<>
			<CreateVertex graphListChanged={props.graphListChanged} graphListWasChanged={props.graphListWasChanged}/>
			<div className={styles.graphWrapper}>
				{activeLink.length > 0 && (
					<div style={{marginLeft: '10px'}}>
						<div>
							<input type="text" id="weight" value={weight} onChange={onChangeWeight} placeholder={`Вес из ${activeLink[0]} в ${activeLink[1]}`} />
						</div>
						<button className='waves-effect waves-light btn-small' onClick={changeFirstWeight} >Изменить вес</button>

						<div>
							<input type="text" id="weight" value={weight} onChange={onChangeWeight} placeholder={`Вес из ${activeLink[1]} в ${activeLink[0]}`}  />
						</div>
						<button className='waves-effect waves-light btn-small' onClick={changeSecondWeight}>Изменить вес</button>
					</div>
				)}
				{vertexes && (
					<Graph
						id='graph-id'
						data={graphObject.serialize()}
						config={myConfig}
						onClickNode={onClickNode}
						onClickLink={onClickLink}
						onDoubleClickNode={onDoubleClickNode}
						onRightClickLink={onRightClickLink}
					/>
				)}
				<div style={{marginRight: '10px'}}>
					<button
						className='waves-effect waves-light btn-small'
						onClick={handleReset}
						style={{ marginTop: '10px' }}
					>
						Снять выделение
					</button>
					<div>Выделенные ноды:</div>
					{activeNodes.map((i: any) => <h6>{i}</h6>)}
					{activeNodes.length === 2 && (
						<>
							<button className='waves-effect waves-light btn-small'>Вычислить кратчайший путь</button>
							<div>или</div>
							<input type="text" id="weight" value={weight} onChange={onChangeWeight} placeholder="Укажите вес узла" />
							<button className='waves-effect waves-light btn-small' onClick={createEdge}>Создать промежуточный узел</button>
						</>
					)}
				</div>
			</div>
		</>
	);
}