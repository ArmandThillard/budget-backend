--
-- PostgreSQL database dump
--

-- Dumped from database version 16.0 (Ubuntu 16.0-1.pgdg22.04+1)
-- Dumped by pg_dump version 16.0 (Ubuntu 16.0-1.pgdg22.04+1)

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
-- Name: account; Type: TABLE; Schema: public; Owner: ath
--

CREATE TABLE public.account (
    account_id integer NOT NULL,
    num character varying(255) NOT NULL,
    label character varying(255) NOT NULL
);


ALTER TABLE public.account OWNER TO ath;

--
-- Name: account_seq; Type: SEQUENCE; Schema: public; Owner: ath
--

CREATE SEQUENCE public.account_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.account_seq OWNER TO ath;

--
-- Name: category; Type: TABLE; Schema: public; Owner: ath
--

CREATE TABLE public.category (
    category_id integer NOT NULL,
    label character varying(255) NOT NULL,
    parent_category_id integer
);


ALTER TABLE public.category OWNER TO ath;

--
-- Name: category_seq; Type: SEQUENCE; Schema: public; Owner: ath
--

CREATE SEQUENCE public.category_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.category_seq OWNER TO ath;

--
-- Name: export_csv; Type: TABLE; Schema: public; Owner: ath
--

CREATE TABLE public.export_csv (
    dateop text,
    dateval text,
    label text,
    category text,
    categoryparent text,
    supplierfound text,
    amount text,
    accountnum text,
    accountlabel text,
    accountbalance text,
    comment text,
    pointer text
);


ALTER TABLE public.export_csv OWNER TO ath;

--
-- Name: file; Type: TABLE; Schema: public; Owner: ath
--

CREATE TABLE public.file (
    file_id integer NOT NULL,
    name character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    hash character varying(255) NOT NULL,
    import_date date NOT NULL,
    month character varying(7) NOT NULL,
    income boolean NOT NULL
);


ALTER TABLE public.file OWNER TO ath;

--
-- Name: file_file_id_seq; Type: SEQUENCE; Schema: public; Owner: ath
--

CREATE SEQUENCE public.file_file_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.file_file_id_seq OWNER TO ath;

--
-- Name: supplier; Type: TABLE; Schema: public; Owner: ath
--

CREATE TABLE public.supplier (
    supplier_id integer NOT NULL,
    label character varying(255) NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.supplier OWNER TO ath;

--
-- Name: supplier_seq; Type: SEQUENCE; Schema: public; Owner: ath
--

CREATE SEQUENCE public.supplier_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.supplier_seq OWNER TO ath;

--
-- Name: transaction; Type: TABLE; Schema: public; Owner: ath
--

CREATE TABLE public.transaction (
    transaction_id integer NOT NULL,
    category_id integer,
    supplier_id integer,
    account_id integer NOT NULL,
    file_id integer NOT NULL,
    date_op date NOT NULL,
    date_val date NOT NULL,
    label character varying(255) NOT NULL,
    amount double precision NOT NULL,
    comment character varying(255) DEFAULT NULL::character varying,
    pointed boolean NOT NULL,
    need boolean NOT NULL
);


ALTER TABLE public.transaction OWNER TO ath;

--
-- Name: transaction_seq; Type: SEQUENCE; Schema: public; Owner: ath
--

CREATE SEQUENCE public.transaction_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transaction_seq OWNER TO ath;

--
-- Name: account account_pkey; Type: CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.account
    ADD CONSTRAINT account_pkey PRIMARY KEY (account_id);


--
-- Name: category category_pkey; Type: CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.category
    ADD CONSTRAINT category_pkey PRIMARY KEY (category_id);


--
-- Name: file file_pkey; Type: CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.file
    ADD CONSTRAINT file_pkey PRIMARY KEY (file_id);


--
-- Name: supplier supplier_pkey; Type: CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.supplier
    ADD CONSTRAINT supplier_pkey PRIMARY KEY (supplier_id);


--
-- Name: transaction transaction_pkey; Type: CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT transaction_pkey PRIMARY KEY (transaction_id);


--
-- Name: idx_723705d112469de2; Type: INDEX; Schema: public; Owner: ath
--

CREATE INDEX idx_723705d112469de2 ON public.transaction USING btree (category_id);


--
-- Name: idx_723705d12add6d8c; Type: INDEX; Schema: public; Owner: ath
--

CREATE INDEX idx_723705d12add6d8c ON public.transaction USING btree (supplier_id);


--
-- Name: idx_723705d193cb796c; Type: INDEX; Schema: public; Owner: ath
--

CREATE INDEX idx_723705d193cb796c ON public.transaction USING btree (file_id);


--
-- Name: idx_723705d19b6b5fba; Type: INDEX; Schema: public; Owner: ath
--

CREATE INDEX idx_723705d19b6b5fba ON public.transaction USING btree (account_id);


--
-- Name: transaction fk_723705d112469de2; Type: FK CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT fk_723705d112469de2 FOREIGN KEY (category_id) REFERENCES public.category(category_id);


--
-- Name: transaction fk_723705d12add6d8c; Type: FK CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT fk_723705d12add6d8c FOREIGN KEY (supplier_id) REFERENCES public.supplier(supplier_id);


--
-- Name: transaction fk_723705d193cb796c; Type: FK CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT fk_723705d193cb796c FOREIGN KEY (file_id) REFERENCES public.file(file_id);


--
-- Name: transaction fk_723705d19b6b5fba; Type: FK CONSTRAINT; Schema: public; Owner: ath
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT fk_723705d19b6b5fba FOREIGN KEY (account_id) REFERENCES public.account(account_id);


--
-- PostgreSQL database dump complete
--

