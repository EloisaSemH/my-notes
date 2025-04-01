--
-- PostgreSQL database dump
--

-- Dumped from database version 15.12 (Debian 15.12-1.pgdg120+1)
-- Dumped by pg_dump version 15.12 (Debian 15.12-1.pgdg120+1)

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
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: user
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO "user";

--
-- Name: notes; Type: TABLE; Schema: public; Owner: user
--

CREATE TABLE public.notes (
    id integer NOT NULL,
    user_id integer NOT NULL,
    uuid uuid NOT NULL,
    title character varying(255) NOT NULL,
    content text NOT NULL,
    color character varying(6) DEFAULT NULL::character varying,
    is_pinned boolean,
    is_archived boolean,
    source character varying(255) NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.notes OWNER TO "user";

--
-- Name: COLUMN notes.updated_at; Type: COMMENT; Schema: public; Owner: user
--

COMMENT ON COLUMN public.notes.updated_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN notes.created_at; Type: COMMENT; Schema: public; Owner: user
--

COMMENT ON COLUMN public.notes.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: notes_id_seq; Type: SEQUENCE; Schema: public; Owner: user
--

CREATE SEQUENCE public.notes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.notes_id_seq OWNER TO "user";

--
-- Name: notes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: user
--

ALTER SEQUENCE public.notes_id_seq OWNED BY public.notes.id;


--
-- Name: reset_password_request; Type: TABLE; Schema: public; Owner: user
--

CREATE TABLE public.reset_password_request (
    id integer NOT NULL,
    user_id integer NOT NULL,
    selector character varying(20) NOT NULL,
    hashed_token character varying(100) NOT NULL,
    requested_at timestamp(0) without time zone NOT NULL,
    expires_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.reset_password_request OWNER TO "user";

--
-- Name: COLUMN reset_password_request.requested_at; Type: COMMENT; Schema: public; Owner: user
--

COMMENT ON COLUMN public.reset_password_request.requested_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN reset_password_request.expires_at; Type: COMMENT; Schema: public; Owner: user
--

COMMENT ON COLUMN public.reset_password_request.expires_at IS '(DC2Type:datetime_immutable)';


--
-- Name: reset_password_request_id_seq; Type: SEQUENCE; Schema: public; Owner: user
--

CREATE SEQUENCE public.reset_password_request_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.reset_password_request_id_seq OWNER TO "user";

--
-- Name: reset_password_request_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: user
--

ALTER SEQUENCE public.reset_password_request_id_seq OWNED BY public.reset_password_request.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: user
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password_hash character varying(255) NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.users OWNER TO "user";

--
-- Name: COLUMN users.updated_at; Type: COMMENT; Schema: public; Owner: user
--

COMMENT ON COLUMN public.users.updated_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN users.created_at; Type: COMMENT; Schema: public; Owner: user
--

COMMENT ON COLUMN public.users.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: user
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO "user";

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: user
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: notes id; Type: DEFAULT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.notes ALTER COLUMN id SET DEFAULT nextval('public.notes_id_seq'::regclass);


--
-- Name: reset_password_request id; Type: DEFAULT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.reset_password_request ALTER COLUMN id SET DEFAULT nextval('public.reset_password_request_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: notes notes_pkey; Type: CONSTRAINT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_pkey PRIMARY KEY (id);


--
-- Name: reset_password_request reset_password_request_pkey; Type: CONSTRAINT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.reset_password_request
    ADD CONSTRAINT reset_password_request_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: idx_11ba68ca76ed395; Type: INDEX; Schema: public; Owner: user
--

CREATE INDEX idx_11ba68ca76ed395 ON public.notes USING btree (user_id);


--
-- Name: idx_7ce748aa76ed395; Type: INDEX; Schema: public; Owner: user
--

CREATE INDEX idx_7ce748aa76ed395 ON public.reset_password_request USING btree (user_id);


--
-- Name: uniq_11ba68cd17f50a6; Type: INDEX; Schema: public; Owner: user
--

CREATE UNIQUE INDEX uniq_11ba68cd17f50a6 ON public.notes USING btree (uuid);


--
-- Name: uniq_1483a5e9e7927c74; Type: INDEX; Schema: public; Owner: user
--

CREATE UNIQUE INDEX uniq_1483a5e9e7927c74 ON public.users USING btree (email);


--
-- Name: notes fk_11ba68ca76ed395; Type: FK CONSTRAINT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT fk_11ba68ca76ed395 FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: reset_password_request fk_7ce748aa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: user
--

ALTER TABLE ONLY public.reset_password_request
    ADD CONSTRAINT fk_7ce748aa76ed395 FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- PostgreSQL database dump complete
--

