import React, { useEffect, useState } from 'react';
import { Graph } from 'react-d3-graph';
import GraphLib from "graph-data-structure";
import { getData } from "../../functions/functions";
import { CreateVertex } from "./create_vertex_form";
import styles from './style.module.css'

const data = {
	nodes: [{ id: "Harry", nodeColor: '#000' }, { id: "Sally" }, { id: "Alice" }],
	links: [
		{ source: "Harry", target: "Sally" },
		{ source: "Harry", target: "Alice" },
	],
};

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
		highlightColor: 'lightblue',
		renderLabel: true
	}
};

export const GraphComponent: React.FC = (props) => {

	const [activeNodes, setActiveNodes]: any[] = useState([])
	const [activeLink, setActiveLink]: any[] = useState([])


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
	}, []);

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
		console.log(`${src} ${trg}`)
	}

	return (
		<>
			<CreateVertex />
			<div className={styles.graphWrapper}>
				{activeLink.length > 0 && (
					<div style={{marginLeft: '10px'}}>
						<div>
							<input type="text" placeholder={`Вес из ${activeLink[0]} в ${activeLink[1]}`} />
						</div>
						<button className='waves-effect waves-light btn-small'>Изменить вес</button>

						<div>
							<input type="text" placeholder={`Вес из ${activeLink[1]} в ${activeLink[0]}`} />
						</div>
						<button className='waves-effect waves-light btn-small'>Изменить вес</button>
					</div>
				)}
				{vertexes && (
					<Graph
						id='graph-id'
						data={data}
						config={myConfig}
						onClickNode={onClickNode}
						onClickLink={onClickLink}
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
							<input type="text" placeholder="Укажите вес узла" />
							<button className='waves-effect waves-light btn-small'>Создать промежуточный узел</button>
						</>
					)}
				</div>
			</div>
		</>
	);
}