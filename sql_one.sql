--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: uk_periphery; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA uk_periphery;


ALTER SCHEMA uk_periphery OWNER TO postgres;

SET search_path = uk_periphery, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: sitting_register_tb; Type: TABLE; Schema: uk_periphery; Owner: postgres; Tablespace: 
--

CREATE TABLE sitting_register_tb (
    id integer NOT NULL,
    jo_code character varying(7),
    jo_name character varying,
    jo_desg character varying,
    todays_date date,
    chamber_time time without time zone,
    bench_time time without time zone,
    userid integer,
    user_name character varying,
    user_full_name character varying,
    display character(1) DEFAULT 'Y'::bpchar,
    remark text,
    create_modify timestamp without time zone DEFAULT now()
);


ALTER TABLE uk_periphery.sitting_register_tb OWNER TO postgres;

--
-- Name: sitting_register_tb_id_seq; Type: SEQUENCE; Schema: uk_periphery; Owner: postgres
--

CREATE SEQUENCE sitting_register_tb_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE uk_periphery.sitting_register_tb_id_seq OWNER TO postgres;

--
-- Name: sitting_register_tb_id_seq; Type: SEQUENCE OWNED BY; Schema: uk_periphery; Owner: postgres
--

ALTER SEQUENCE sitting_register_tb_id_seq OWNED BY sitting_register_tb.id;


--
-- Name: id; Type: DEFAULT; Schema: uk_periphery; Owner: postgres
--

ALTER TABLE ONLY sitting_register_tb ALTER COLUMN id SET DEFAULT nextval('sitting_register_tb_id_seq'::regclass);


--
-- Data for Name: sitting_register_tb; Type: TABLE DATA; Schema: uk_periphery; Owner: postgres
--

COPY sitting_register_tb (id, jo_code, jo_name, jo_desg, todays_date, chamber_time, bench_time, userid, user_name, user_full_name, display, remark, create_modify) FROM stdin;
\.


--
-- Name: sitting_register_tb_id_seq; Type: SEQUENCE SET; Schema: uk_periphery; Owner: postgres
--

SELECT pg_catalog.setval('sitting_register_tb_id_seq', 1, true);


--
-- Name: sitting_register_tb_pkey; Type: CONSTRAINT; Schema: uk_periphery; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY sitting_register_tb
    ADD CONSTRAINT sitting_register_tb_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

