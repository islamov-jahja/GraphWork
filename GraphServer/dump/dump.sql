--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2
-- Dumped by pg_dump version 12.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: edge; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.edge (
    id integer NOT NULL,
    first_vertex_id integer,
    second_vertex_id integer,
    weight integer
);


ALTER TABLE public.edge OWNER TO postgres;

--
-- Name: edge_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.edge_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.edge_id_seq OWNER TO postgres;

--
-- Name: edge_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.edge_id_seq OWNED BY public.edge.id;


--
-- Name: graph; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.graph (
    id integer NOT NULL,
    user_id integer,
    name character varying(500)
);


ALTER TABLE public.graph OWNER TO postgres;

--
-- Name: graph_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.graph_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.graph_id_seq OWNER TO postgres;

--
-- Name: graph_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.graph_id_seq OWNED BY public.graph.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    name character varying(80),
    email character varying(80),
    token character varying(255),
    password character varying(255)
);


ALTER TABLE public."user" OWNER TO postgres;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO postgres;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- Name: vertex; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vertex (
    id integer NOT NULL,
    graph_id integer,
    name character varying(500)
);


ALTER TABLE public.vertex OWNER TO postgres;

--
-- Name: vertex_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vertex_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vertex_id_seq OWNER TO postgres;

--
-- Name: vertex_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vertex_id_seq OWNED BY public.vertex.id;


--
-- Name: edge id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge ALTER COLUMN id SET DEFAULT nextval('public.edge_id_seq'::regclass);


--
-- Name: graph id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.graph ALTER COLUMN id SET DEFAULT nextval('public.graph_id_seq'::regclass);


--
-- Name: user id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- Name: vertex id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vertex ALTER COLUMN id SET DEFAULT nextval('public.vertex_id_seq'::regclass);


--
-- Data for Name: edge; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.edge (id, first_vertex_id, second_vertex_id, weight) FROM stdin;
\.


--
-- Data for Name: graph; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.graph (id, user_id, name) FROM stdin;
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."user" (id, name, email, token, password) FROM stdin;
3	Данила	danila.zemchenko13@mail.ru	hhND0BKTXt6c1p--lGwQhd4iE4Wybllu0sHnVivRFO_HTHSHIMsSsNaGq_3EIiXarlJW4_Ffb0Ofk4gQbYVOng==	$2y$13$4WR5Ll3LincjPvs9NOZoA.rJcmJLfO39OnqAqQqHTWJlBqMM2NOpy
1	новый моудль	yahyaislamov15@mail.ru	\N	$2y$13$ISNQOSivGQn0MG7fuwYfr.g3EkofITiBVnaYV7gfP6TurPP0ElVCK
2	Вася	vasya123@mail.ru	\N	$2y$13$ptDEfLcFt0eNYX1/HCne9uNW8ePSL9u/nZ/I6yvGWqCsDZW1UOftC
\.


--
-- Data for Name: vertex; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vertex (id, graph_id, name) FROM stdin;
\.


--
-- Name: edge_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.edge_id_seq', 74, true);


--
-- Name: graph_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.graph_id_seq', 63, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_id_seq', 3, true);


--
-- Name: vertex_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vertex_id_seq', 67, true);


--
-- Name: edge edge_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge
    ADD CONSTRAINT edge_pkey PRIMARY KEY (id);


--
-- Name: graph graph_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.graph
    ADD CONSTRAINT graph_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: vertex vertex_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vertex
    ADD CONSTRAINT vertex_pkey PRIMARY KEY (id);


--
-- Name: graph graph_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.graph
    ADD CONSTRAINT graph_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: vertex uiwe_123_key1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vertex
    ADD CONSTRAINT uiwe_123_key1 FOREIGN KEY (graph_id) REFERENCES public.graph(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: edge wedwu_111_rf; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge
    ADD CONSTRAINT wedwu_111_rf FOREIGN KEY (first_vertex_id) REFERENCES public.vertex(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: edge wedwu_222_rf; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.edge
    ADD CONSTRAINT wedwu_222_rf FOREIGN KEY (second_vertex_id) REFERENCES public.vertex(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

