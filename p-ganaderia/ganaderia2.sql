--
-- PostgreSQL database dump
--

-- Dumped from database version 16.9
-- Dumped by pg_dump version 16.9

-- Started on 2026-04-30 10:08:12

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

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- TOC entry 5111 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 246 (class 1259 OID 35299)
-- Name: animal_ads; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.animal_ads (
    id integer NOT NULL,
    user_id integer NOT NULL,
    animal_type_id integer NOT NULL,
    breed_id integer,
    quantity integer NOT NULL,
    weight_kg numeric(6,2),
    price_per_unit numeric(10,2),
    ad_type character(4) NOT NULL,
    status character varying(20) DEFAULT 'activo'::character varying,
    created_at timestamp without time zone DEFAULT now(),
    CONSTRAINT animal_ads_ad_type_check CHECK ((ad_type = ANY (ARRAY['VENT'::bpchar, 'COMP'::bpchar]))),
    CONSTRAINT animal_ads_quantity_check CHECK ((quantity > 0))
);


ALTER TABLE public.animal_ads OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 35298)
-- Name: animal_ads_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.animal_ads_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.animal_ads_id_seq OWNER TO postgres;

--
-- TOC entry 5112 (class 0 OID 0)
-- Dependencies: 245
-- Name: animal_ads_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.animal_ads_id_seq OWNED BY public.animal_ads.id;


--
-- TOC entry 216 (class 1259 OID 35055)
-- Name: animal_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.animal_types (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    description text
);


ALTER TABLE public.animal_types OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 35054)
-- Name: animal_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.animal_types_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.animal_types_id_seq OWNER TO postgres;

--
-- TOC entry 5113 (class 0 OID 0)
-- Dependencies: 215
-- Name: animal_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.animal_types_id_seq OWNED BY public.animal_types.id;


--
-- TOC entry 224 (class 1259 OID 35112)
-- Name: animals; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.animals (
    id integer NOT NULL,
    tag character varying(20) NOT NULL,
    name character varying(100),
    breed_id integer,
    animal_type_id integer NOT NULL,
    birth_date date,
    weight_kg numeric(6,2),
    gender character(1) NOT NULL,
    status character varying(20) DEFAULT 'activo'::character varying NOT NULL,
    father_id integer,
    mother_id integer,
    batch_id integer,
    facility_id integer,
    notes text,
    CONSTRAINT animals_gender_check CHECK ((gender = ANY (ARRAY['M'::bpchar, 'H'::bpchar]))),
    CONSTRAINT animals_status_check CHECK (((status)::text = ANY ((ARRAY['activo'::character varying, 'produciendo'::character varying, 'reproduciendo'::character varying, 'enfermo'::character varying, 'sacrificado'::character varying, 'vendido'::character varying, 'muerto'::character varying])::text[]))),
    CONSTRAINT animals_weight_kg_check CHECK ((weight_kg > (0)::numeric))
);


ALTER TABLE public.animals OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 35111)
-- Name: animals_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.animals_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.animals_id_seq OWNER TO postgres;

--
-- TOC entry 5114 (class 0 OID 0)
-- Dependencies: 223
-- Name: animals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.animals_id_seq OWNED BY public.animals.id;


--
-- TOC entry 222 (class 1259 OID 35089)
-- Name: batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.batches (
    id integer NOT NULL,
    batch_code character varying(50) NOT NULL,
    animal_type_id integer NOT NULL,
    facility_id integer,
    start_date date NOT NULL,
    end_date date,
    initial_quantity integer NOT NULL,
    mortality integer DEFAULT 0,
    notes text,
    CONSTRAINT batches_initial_quantity_check CHECK ((initial_quantity > 0))
);


ALTER TABLE public.batches OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 35088)
-- Name: batches_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.batches_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.batches_id_seq OWNER TO postgres;

--
-- TOC entry 5115 (class 0 OID 0)
-- Dependencies: 221
-- Name: batches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.batches_id_seq OWNED BY public.batches.id;


--
-- TOC entry 218 (class 1259 OID 35066)
-- Name: breeds; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.breeds (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    animal_type_id integer NOT NULL
);


ALTER TABLE public.breeds OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 35065)
-- Name: breeds_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.breeds_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.breeds_id_seq OWNER TO postgres;

--
-- TOC entry 5116 (class 0 OID 0)
-- Dependencies: 217
-- Name: breeds_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.breeds_id_seq OWNED BY public.breeds.id;


--
-- TOC entry 230 (class 1259 OID 35185)
-- Name: chicken_inventory; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.chicken_inventory (
    id integer NOT NULL,
    inventory_date date NOT NULL,
    quantity integer NOT NULL,
    CONSTRAINT chicken_inventory_quantity_check CHECK ((quantity >= 0))
);


ALTER TABLE public.chicken_inventory OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 35184)
-- Name: chicken_inventory_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.chicken_inventory_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.chicken_inventory_id_seq OWNER TO postgres;

--
-- TOC entry 5117 (class 0 OID 0)
-- Dependencies: 229
-- Name: chicken_inventory_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.chicken_inventory_id_seq OWNED BY public.chicken_inventory.id;


--
-- TOC entry 228 (class 1259 OID 35175)
-- Name: egg_production; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.egg_production (
    id integer NOT NULL,
    production_date date NOT NULL,
    quantity integer NOT NULL,
    CONSTRAINT egg_production_quantity_check CHECK ((quantity >= 0))
);


ALTER TABLE public.egg_production OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 35174)
-- Name: egg_production_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.egg_production_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.egg_production_id_seq OWNER TO postgres;

--
-- TOC entry 5118 (class 0 OID 0)
-- Dependencies: 227
-- Name: egg_production_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.egg_production_id_seq OWNED BY public.egg_production.id;


--
-- TOC entry 260 (class 1259 OID 35432)
-- Name: employees; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.employees (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    role character varying(50),
    monthly_salary numeric(8,2) NOT NULL
);


ALTER TABLE public.employees OWNER TO postgres;

--
-- TOC entry 259 (class 1259 OID 35431)
-- Name: employees_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.employees_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.employees_id_seq OWNER TO postgres;

--
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 259
-- Name: employees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.employees_id_seq OWNED BY public.employees.id;


--
-- TOC entry 220 (class 1259 OID 35080)
-- Name: facilities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.facilities (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    facility_type character varying(50) NOT NULL,
    capacity integer,
    location character varying(100),
    notes text
);


ALTER TABLE public.facilities OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 35079)
-- Name: facilities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.facilities_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.facilities_id_seq OWNER TO postgres;

--
-- TOC entry 5120 (class 0 OID 0)
-- Dependencies: 219
-- Name: facilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.facilities_id_seq OWNED BY public.facilities.id;


--
-- TOC entry 236 (class 1259 OID 35223)
-- Name: feeding; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.feeding (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    food_id integer NOT NULL,
    feeding_date date NOT NULL,
    quantity_kg numeric(6,2) NOT NULL,
    CONSTRAINT feeding_quantity_kg_check CHECK ((quantity_kg > (0)::numeric))
);


ALTER TABLE public.feeding OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 35222)
-- Name: feeding_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.feeding_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.feeding_id_seq OWNER TO postgres;

--
-- TOC entry 5121 (class 0 OID 0)
-- Dependencies: 235
-- Name: feeding_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.feeding_id_seq OWNED BY public.feeding.id;


--
-- TOC entry 264 (class 1259 OID 35453)
-- Name: financial_entries; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.financial_entries (
    id integer NOT NULL,
    entry_date date NOT NULL,
    type character(1) NOT NULL,
    category character varying(50),
    amount numeric(12,2) NOT NULL,
    tax_amount numeric(10,2) DEFAULT 0,
    reference_id integer,
    description text,
    CONSTRAINT financial_entries_type_check CHECK ((type = ANY (ARRAY['I'::bpchar, 'G'::bpchar])))
);


ALTER TABLE public.financial_entries OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 35452)
-- Name: financial_entries_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.financial_entries_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.financial_entries_id_seq OWNER TO postgres;

--
-- TOC entry 5122 (class 0 OID 0)
-- Dependencies: 263
-- Name: financial_entries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.financial_entries_id_seq OWNED BY public.financial_entries.id;


--
-- TOC entry 234 (class 1259 OID 35215)
-- Name: food_catalog; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.food_catalog (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    food_type character varying(50),
    cost_per_kg numeric(8,2),
    protein_pct numeric(5,2),
    stock_kg numeric(10,2) DEFAULT 0
);


ALTER TABLE public.food_catalog OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 35214)
-- Name: food_catalog_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.food_catalog_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.food_catalog_id_seq OWNER TO postgres;

--
-- TOC entry 5123 (class 0 OID 0)
-- Dependencies: 233
-- Name: food_catalog_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.food_catalog_id_seq OWNED BY public.food_catalog.id;


--
-- TOC entry 232 (class 1259 OID 35195)
-- Name: health_events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.health_events (
    id integer NOT NULL,
    animal_id integer,
    batch_id integer,
    event_type character varying(50) NOT NULL,
    event_date date NOT NULL,
    product_used character varying(100),
    dosage character varying(100),
    notes text,
    CONSTRAINT health_events_event_type_check CHECK (((event_type)::text = ANY ((ARRAY['vacunación'::character varying, 'desparasitación'::character varying, 'tratamiento'::character varying, 'control'::character varying, 'otro'::character varying])::text[])))
);


ALTER TABLE public.health_events OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 35194)
-- Name: health_events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.health_events_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.health_events_id_seq OWNER TO postgres;

--
-- TOC entry 5124 (class 0 OID 0)
-- Dependencies: 231
-- Name: health_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.health_events_id_seq OWNED BY public.health_events.id;


--
-- TOC entry 242 (class 1259 OID 35275)
-- Name: market_prices; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.market_prices (
    id integer NOT NULL,
    product_type character varying(30) NOT NULL,
    price_per_unit numeric(8,2) NOT NULL,
    unit character varying(20) NOT NULL,
    price_date date NOT NULL,
    market character varying(100),
    notes text,
    CONSTRAINT market_prices_product_type_check CHECK (((product_type)::text = ANY ((ARRAY['leche'::character varying, 'carne'::character varying, 'huevo'::character varying, 'animal en pie'::character varying])::text[])))
);


ALTER TABLE public.market_prices OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 35274)
-- Name: market_prices_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.market_prices_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.market_prices_id_seq OWNER TO postgres;

--
-- TOC entry 5125 (class 0 OID 0)
-- Dependencies: 241
-- Name: market_prices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.market_prices_id_seq OWNED BY public.market_prices.id;


--
-- TOC entry 226 (class 1259 OID 35160)
-- Name: milk_production; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.milk_production (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    production_date date NOT NULL,
    quantity_liters numeric(5,2) NOT NULL,
    CONSTRAINT milk_production_quantity_liters_check CHECK ((quantity_liters >= (0)::numeric))
);


ALTER TABLE public.milk_production OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 35159)
-- Name: milk_production_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.milk_production_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.milk_production_id_seq OWNER TO postgres;

--
-- TOC entry 5126 (class 0 OID 0)
-- Dependencies: 225
-- Name: milk_production_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.milk_production_id_seq OWNED BY public.milk_production.id;


--
-- TOC entry 238 (class 1259 OID 35241)
-- Name: nutritional_efficiency; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.nutritional_efficiency (
    id integer NOT NULL,
    animal_id integer,
    measurement_date date NOT NULL,
    feed_conversion_ratio numeric(5,2),
    weight_gain_kg numeric(5,2),
    notes text
);


ALTER TABLE public.nutritional_efficiency OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 35240)
-- Name: nutritional_efficiency_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.nutritional_efficiency_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.nutritional_efficiency_id_seq OWNER TO postgres;

--
-- TOC entry 5127 (class 0 OID 0)
-- Dependencies: 237
-- Name: nutritional_efficiency_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.nutritional_efficiency_id_seq OWNED BY public.nutritional_efficiency.id;


--
-- TOC entry 262 (class 1259 OID 35439)
-- Name: payroll; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.payroll (
    id integer NOT NULL,
    employee_id integer NOT NULL,
    period date NOT NULL,
    gross_salary numeric(8,2),
    deductions numeric(8,2),
    net_pay numeric(8,2),
    payment_date date
);


ALTER TABLE public.payroll OWNER TO postgres;

--
-- TOC entry 261 (class 1259 OID 35438)
-- Name: payroll_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.payroll_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.payroll_id_seq OWNER TO postgres;

--
-- TOC entry 5128 (class 0 OID 0)
-- Dependencies: 261
-- Name: payroll_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.payroll_id_seq OWNED BY public.payroll.id;


--
-- TOC entry 256 (class 1259 OID 35384)
-- Name: purchase_order_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.purchase_order_details (
    id integer NOT NULL,
    order_id integer NOT NULL,
    item_type character varying(20) NOT NULL,
    food_id integer,
    supply_id integer,
    quantity numeric(8,2) NOT NULL,
    unit_price numeric(8,2) NOT NULL,
    CONSTRAINT purchase_order_details_item_type_check CHECK (((item_type)::text = ANY ((ARRAY['alimento'::character varying, 'insumo'::character varying])::text[])))
);


ALTER TABLE public.purchase_order_details OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 35383)
-- Name: purchase_order_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.purchase_order_details_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.purchase_order_details_id_seq OWNER TO postgres;

--
-- TOC entry 5129 (class 0 OID 0)
-- Dependencies: 255
-- Name: purchase_order_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.purchase_order_details_id_seq OWNED BY public.purchase_order_details.id;


--
-- TOC entry 254 (class 1259 OID 35370)
-- Name: purchase_orders; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.purchase_orders (
    id integer NOT NULL,
    supplier_id integer NOT NULL,
    order_date date NOT NULL,
    expected_delivery date,
    status character varying(20) DEFAULT 'pendiente'::character varying,
    total_amount numeric(12,2) NOT NULL,
    CONSTRAINT purchase_orders_status_check CHECK (((status)::text = ANY ((ARRAY['pendiente'::character varying, 'recibida'::character varying, 'cancelada'::character varying])::text[])))
);


ALTER TABLE public.purchase_orders OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 35369)
-- Name: purchase_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.purchase_orders_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.purchase_orders_id_seq OWNER TO postgres;

--
-- TOC entry 5130 (class 0 OID 0)
-- Dependencies: 253
-- Name: purchase_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.purchase_orders_id_seq OWNED BY public.purchase_orders.id;


--
-- TOC entry 258 (class 1259 OID 35407)
-- Name: sales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sales (
    id integer NOT NULL,
    sale_type character varying(20) NOT NULL,
    product_type character varying(20),
    animal_id integer,
    batch_id integer,
    slaughter_id integer,
    quantity numeric(8,2) NOT NULL,
    sale_date date NOT NULL,
    total_amount numeric(10,2) NOT NULL,
    buyer_name character varying(150),
    notes text,
    CONSTRAINT sales_sale_type_check CHECK (((sale_type)::text = ANY ((ARRAY['producto'::character varying, 'animal_vivo'::character varying])::text[])))
);


ALTER TABLE public.sales OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 35406)
-- Name: sales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sales_id_seq OWNER TO postgres;

--
-- TOC entry 5131 (class 0 OID 0)
-- Dependencies: 257
-- Name: sales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sales_id_seq OWNED BY public.sales.id;


--
-- TOC entry 240 (class 1259 OID 35255)
-- Name: slaughter_records; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.slaughter_records (
    id integer NOT NULL,
    animal_id integer,
    animal_type_id integer,
    slaughter_date date NOT NULL,
    quantity integer NOT NULL,
    notes text,
    CONSTRAINT slaughter_records_quantity_check CHECK ((quantity > 0))
);


ALTER TABLE public.slaughter_records OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 35254)
-- Name: slaughter_records_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.slaughter_records_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.slaughter_records_id_seq OWNER TO postgres;

--
-- TOC entry 5132 (class 0 OID 0)
-- Dependencies: 239
-- Name: slaughter_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.slaughter_records_id_seq OWNED BY public.slaughter_records.id;


--
-- TOC entry 250 (class 1259 OID 35355)
-- Name: suppliers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.suppliers (
    id integer NOT NULL,
    name character varying(150) NOT NULL,
    contact_person character varying(100),
    phone character varying(20),
    email character varying(100)
);


ALTER TABLE public.suppliers OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 35354)
-- Name: suppliers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.suppliers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.suppliers_id_seq OWNER TO postgres;

--
-- TOC entry 5133 (class 0 OID 0)
-- Dependencies: 249
-- Name: suppliers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.suppliers_id_seq OWNED BY public.suppliers.id;


--
-- TOC entry 252 (class 1259 OID 35362)
-- Name: supplies_catalog; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.supplies_catalog (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    category character varying(50),
    unit character varying(20),
    cost_per_unit numeric(8,2),
    stock_quantity numeric(10,2) DEFAULT 0
);


ALTER TABLE public.supplies_catalog OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 35361)
-- Name: supplies_catalog_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.supplies_catalog_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.supplies_catalog_id_seq OWNER TO postgres;

--
-- TOC entry 5134 (class 0 OID 0)
-- Dependencies: 251
-- Name: supplies_catalog_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.supplies_catalog_id_seq OWNED BY public.supplies_catalog.id;


--
-- TOC entry 248 (class 1259 OID 35325)
-- Name: transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transactions (
    id integer NOT NULL,
    ad_id integer,
    seller_id integer NOT NULL,
    buyer_id integer NOT NULL,
    animal_type_id integer NOT NULL,
    quantity integer NOT NULL,
    total_amount numeric(12,2) NOT NULL,
    transaction_date date NOT NULL,
    notes text,
    CONSTRAINT transactions_quantity_check CHECK ((quantity > 0))
);


ALTER TABLE public.transactions OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 35324)
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transactions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transactions_id_seq OWNER TO postgres;

--
-- TOC entry 5135 (class 0 OID 0)
-- Dependencies: 247
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- TOC entry 244 (class 1259 OID 35287)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    full_name character varying(150) NOT NULL,
    email character varying(100),
    phone character varying(20),
    role character varying(20) DEFAULT 'productor'::character varying
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 35286)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5136 (class 0 OID 0)
-- Dependencies: 243
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4773 (class 2604 OID 35302)
-- Name: animal_ads id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_ads ALTER COLUMN id SET DEFAULT nextval('public.animal_ads_id_seq'::regclass);


--
-- TOC entry 4754 (class 2604 OID 35058)
-- Name: animal_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_types ALTER COLUMN id SET DEFAULT nextval('public.animal_types_id_seq'::regclass);


--
-- TOC entry 4759 (class 2604 OID 35115)
-- Name: animals id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals ALTER COLUMN id SET DEFAULT nextval('public.animals_id_seq'::regclass);


--
-- TOC entry 4757 (class 2604 OID 35092)
-- Name: batches id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.batches ALTER COLUMN id SET DEFAULT nextval('public.batches_id_seq'::regclass);


--
-- TOC entry 4755 (class 2604 OID 35069)
-- Name: breeds id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.breeds ALTER COLUMN id SET DEFAULT nextval('public.breeds_id_seq'::regclass);


--
-- TOC entry 4763 (class 2604 OID 35188)
-- Name: chicken_inventory id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chicken_inventory ALTER COLUMN id SET DEFAULT nextval('public.chicken_inventory_id_seq'::regclass);


--
-- TOC entry 4762 (class 2604 OID 35178)
-- Name: egg_production id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.egg_production ALTER COLUMN id SET DEFAULT nextval('public.egg_production_id_seq'::regclass);


--
-- TOC entry 4784 (class 2604 OID 35435)
-- Name: employees id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.employees ALTER COLUMN id SET DEFAULT nextval('public.employees_id_seq'::regclass);


--
-- TOC entry 4756 (class 2604 OID 35083)
-- Name: facilities id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.facilities ALTER COLUMN id SET DEFAULT nextval('public.facilities_id_seq'::regclass);


--
-- TOC entry 4767 (class 2604 OID 35226)
-- Name: feeding id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feeding ALTER COLUMN id SET DEFAULT nextval('public.feeding_id_seq'::regclass);


--
-- TOC entry 4786 (class 2604 OID 35456)
-- Name: financial_entries id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.financial_entries ALTER COLUMN id SET DEFAULT nextval('public.financial_entries_id_seq'::regclass);


--
-- TOC entry 4765 (class 2604 OID 35218)
-- Name: food_catalog id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.food_catalog ALTER COLUMN id SET DEFAULT nextval('public.food_catalog_id_seq'::regclass);


--
-- TOC entry 4764 (class 2604 OID 35198)
-- Name: health_events id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.health_events ALTER COLUMN id SET DEFAULT nextval('public.health_events_id_seq'::regclass);


--
-- TOC entry 4770 (class 2604 OID 35278)
-- Name: market_prices id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.market_prices ALTER COLUMN id SET DEFAULT nextval('public.market_prices_id_seq'::regclass);


--
-- TOC entry 4761 (class 2604 OID 35163)
-- Name: milk_production id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.milk_production ALTER COLUMN id SET DEFAULT nextval('public.milk_production_id_seq'::regclass);


--
-- TOC entry 4768 (class 2604 OID 35244)
-- Name: nutritional_efficiency id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nutritional_efficiency ALTER COLUMN id SET DEFAULT nextval('public.nutritional_efficiency_id_seq'::regclass);


--
-- TOC entry 4785 (class 2604 OID 35442)
-- Name: payroll id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payroll ALTER COLUMN id SET DEFAULT nextval('public.payroll_id_seq'::regclass);


--
-- TOC entry 4782 (class 2604 OID 35387)
-- Name: purchase_order_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_order_details ALTER COLUMN id SET DEFAULT nextval('public.purchase_order_details_id_seq'::regclass);


--
-- TOC entry 4780 (class 2604 OID 35373)
-- Name: purchase_orders id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_orders ALTER COLUMN id SET DEFAULT nextval('public.purchase_orders_id_seq'::regclass);


--
-- TOC entry 4783 (class 2604 OID 35410)
-- Name: sales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales ALTER COLUMN id SET DEFAULT nextval('public.sales_id_seq'::regclass);


--
-- TOC entry 4769 (class 2604 OID 35258)
-- Name: slaughter_records id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.slaughter_records ALTER COLUMN id SET DEFAULT nextval('public.slaughter_records_id_seq'::regclass);


--
-- TOC entry 4777 (class 2604 OID 35358)
-- Name: suppliers id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.suppliers ALTER COLUMN id SET DEFAULT nextval('public.suppliers_id_seq'::regclass);


--
-- TOC entry 4778 (class 2604 OID 35365)
-- Name: supplies_catalog id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.supplies_catalog ALTER COLUMN id SET DEFAULT nextval('public.supplies_catalog_id_seq'::regclass);


--
-- TOC entry 4776 (class 2604 OID 35328)
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- TOC entry 4771 (class 2604 OID 35290)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5087 (class 0 OID 35299)
-- Dependencies: 246
-- Data for Name: animal_ads; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.animal_ads (id, user_id, animal_type_id, breed_id, quantity, weight_kg, price_per_unit, ad_type, status, created_at) FROM stdin;
1	2	4	16	1	163.46	253.40	COMP	activo	2026-04-29 09:36:00.548629
2	3	2	16	5	53.46	466.64	VENT	activo	2026-04-29 09:36:00.548629
3	2	4	16	3	77.81	487.23	VENT	activo	2026-04-29 09:36:00.548629
4	3	4	16	2	145.52	694.01	VENT	activo	2026-04-29 09:36:00.548629
5	2	4	16	3	184.90	637.19	COMP	activo	2026-04-29 09:36:00.548629
6	3	1	16	3	123.38	381.43	VENT	activo	2026-04-29 09:36:00.548629
7	3	1	16	4	86.40	380.41	COMP	activo	2026-04-29 09:36:00.548629
8	1	2	16	4	164.73	485.47	VENT	activo	2026-04-29 09:36:00.548629
9	2	4	16	2	230.09	229.33	COMP	activo	2026-04-29 09:36:00.548629
10	3	1	16	5	146.62	552.95	COMP	activo	2026-04-29 09:36:00.548629
11	1	4	16	3	66.88	444.67	VENT	activo	2026-04-29 09:36:00.548629
12	3	3	16	3	170.04	238.75	COMP	activo	2026-04-29 09:36:00.548629
13	2	1	16	5	155.11	660.59	COMP	activo	2026-04-29 09:36:00.548629
14	1	1	16	2	239.17	556.60	VENT	activo	2026-04-29 09:36:00.548629
15	3	2	16	3	113.91	312.89	COMP	activo	2026-04-29 09:36:00.548629
16	1	1	16	1	119.78	328.70	COMP	activo	2026-04-29 09:36:00.548629
17	3	2	16	5	195.51	529.60	VENT	activo	2026-04-29 09:36:00.548629
18	2	4	16	5	139.65	655.26	COMP	activo	2026-04-29 09:36:00.548629
19	2	2	16	2	108.27	228.43	VENT	activo	2026-04-29 09:36:00.548629
20	2	2	16	5	67.79	678.91	COMP	activo	2026-04-29 09:36:00.548629
21	2	1	16	1	133.12	579.16	VENT	activo	2026-04-29 09:36:00.548629
22	4	2	16	1	243.25	593.80	COMP	activo	2026-04-29 09:36:00.548629
23	1	2	16	2	63.61	484.55	VENT	activo	2026-04-29 09:36:00.548629
24	1	1	16	1	174.64	595.21	COMP	activo	2026-04-29 09:36:00.548629
25	2	4	16	4	100.22	693.09	COMP	activo	2026-04-29 09:36:00.548629
26	3	4	16	3	189.41	386.73	COMP	activo	2026-04-29 09:36:00.548629
27	2	4	16	3	242.55	225.91	COMP	activo	2026-04-29 09:36:00.548629
28	2	1	16	1	106.64	573.30	COMP	activo	2026-04-29 09:36:00.548629
29	1	2	16	1	176.74	472.13	VENT	activo	2026-04-29 09:36:00.548629
30	2	4	16	5	177.01	511.22	VENT	activo	2026-04-29 09:36:00.548629
31	1	4	16	2	134.49	394.54	COMP	activo	2026-04-29 09:36:00.548629
32	2	2	16	4	76.53	309.12	COMP	activo	2026-04-29 09:36:00.548629
33	2	1	16	2	116.19	208.92	VENT	activo	2026-04-29 09:36:00.548629
34	2	2	16	2	177.23	410.07	COMP	activo	2026-04-29 09:36:00.548629
35	3	1	16	5	241.19	444.59	VENT	activo	2026-04-29 09:36:00.548629
36	2	3	16	1	152.87	458.21	COMP	activo	2026-04-29 09:36:00.548629
37	1	4	16	1	72.12	418.87	COMP	activo	2026-04-29 09:36:00.548629
38	2	2	16	4	74.31	540.05	VENT	activo	2026-04-29 09:36:00.548629
39	3	2	16	1	237.31	420.49	VENT	activo	2026-04-29 09:36:00.548629
40	3	4	16	5	105.23	277.74	COMP	activo	2026-04-29 09:36:00.548629
41	3	1	16	3	119.90	567.03	COMP	activo	2026-04-29 09:36:00.548629
42	4	1	16	4	210.60	434.58	VENT	activo	2026-04-29 09:36:00.548629
43	2	3	16	1	184.62	376.79	VENT	activo	2026-04-29 09:36:00.548629
44	4	4	16	3	233.30	656.73	VENT	activo	2026-04-29 09:36:00.548629
45	1	3	16	1	147.60	490.53	COMP	activo	2026-04-29 09:36:00.548629
46	2	2	16	4	212.03	497.64	VENT	activo	2026-04-29 09:36:00.548629
47	1	1	16	4	224.78	644.65	VENT	activo	2026-04-29 09:36:00.548629
48	1	3	16	2	99.35	334.77	VENT	activo	2026-04-29 09:36:00.548629
49	2	3	16	4	155.50	376.18	VENT	activo	2026-04-29 09:36:00.548629
50	2	4	16	1	93.12	455.47	COMP	activo	2026-04-29 09:36:00.548629
\.


--
-- TOC entry 5057 (class 0 OID 35055)
-- Dependencies: 216
-- Data for Name: animal_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.animal_types (id, name, description) FROM stdin;
1	Bovino	\N
2	Ovino	\N
3	Porcino	\N
4	Caprino	\N
5	Ave	\N
\.


--
-- TOC entry 5065 (class 0 OID 35112)
-- Dependencies: 224
-- Data for Name: animals; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.animals (id, tag, name, breed_id, animal_type_id, birth_date, weight_kg, gender, status, father_id, mother_id, batch_id, facility_id, notes) FROM stdin;
1	V001	Lola	1	1	2023-03-15	650.00	H	produciendo	\N	\N	\N	1	\N
2	V002	Marta	2	1	2022-08-20	550.00	H	produciendo	\N	\N	\N	1	\N
3	V003	Clara	2	1	2023-01-10	480.00	H	enfermo	\N	\N	\N	1	\N
4	V004	Nube	1	1	2022-06-05	680.00	H	produciendo	\N	\N	\N	1	\N
5	V005	Luna	3	1	2023-11-30	700.00	H	produciendo	\N	\N	\N	1	\N
6	V006	Sol	2	1	2022-12-12	590.00	H	enfermo	\N	\N	\N	1	\N
7	V007	Estrella	1	1	2023-05-18	670.00	H	produciendo	\N	\N	\N	1	\N
8	V008	Margarita	4	1	2023-09-03	720.00	H	produciendo	\N	\N	\N	1	\N
9	V009	Rosa	1	1	2023-07-22	640.00	H	produciendo	\N	\N	\N	1	\N
10	V010	Perla	3	1	2024-01-05	690.00	H	produciendo	\N	\N	\N	1	\N
11	T001	Toro Bravo	4	1	2023-04-10	850.00	M	reproduciendo	\N	\N	\N	4	\N
12	T002	Maximus	5	1	2022-11-15	920.00	M	reproduciendo	\N	\N	\N	4	\N
13	T003	Relámpago	6	1	2023-08-02	780.00	M	reproduciendo	\N	\N	\N	4	\N
14	T004	Titán	7	1	2023-02-28	810.00	M	activo	\N	\N	\N	4	\N
15	T005	Zeus	4	1	2022-10-07	880.00	M	sacrificado	\N	\N	\N	4	\N
16	O001	Blanquita	8	2	2024-02-01	45.00	H	produciendo	\N	\N	\N	2	\N
17	O002	Nieves	9	2	2024-01-15	50.00	H	produciendo	\N	\N	\N	2	\N
18	O003	Corderito	10	2	2023-12-10	55.00	H	produciendo	\N	\N	\N	2	\N
19	O004	Oveja Negra	8	2	2024-03-05	48.00	H	produciendo	\N	\N	\N	2	\N
20	O005	Macho Alfa	10	2	2023-11-20	70.00	M	reproduciendo	\N	\N	\N	2	\N
21	O006	Esquila	9	2	2024-02-28	42.00	H	activo	\N	\N	\N	2	\N
22	P001	Porky	11	3	2024-05-20	120.00	M	activo	\N	\N	\N	3	\N
23	P002	Miss Piggy	12	3	2024-04-10	110.00	H	reproduciendo	\N	\N	\N	3	\N
24	P003	Babe	13	3	2024-06-15	95.00	H	activo	\N	\N	\N	3	\N
25	P004	Napoleón	11	3	2024-03-22	130.00	M	reproduciendo	\N	\N	\N	3	\N
26	P005	Gordito	13	3	2024-07-01	105.00	M	activo	\N	\N	\N	3	\N
27	P006	Rosita	12	3	2024-02-14	100.00	H	reproduciendo	\N	\N	\N	3	\N
28	C001	Cabra Loca	14	4	2024-01-10	35.00	H	produciendo	\N	\N	\N	4	\N
29	C002	Chiva	15	4	2024-03-18	38.00	H	produciendo	\N	\N	\N	4	\N
30	C003	Machito	16	4	2024-04-05	42.00	M	reproduciendo	\N	\N	\N	4	\N
31	C004	Perlita	14	4	2024-02-22	33.00	H	activo	\N	\N	\N	4	\N
\.


--
-- TOC entry 5063 (class 0 OID 35089)
-- Dependencies: 222
-- Data for Name: batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.batches (id, batch_code, animal_type_id, facility_id, start_date, end_date, initial_quantity, mortality, notes) FROM stdin;
\.


--
-- TOC entry 5059 (class 0 OID 35066)
-- Dependencies: 218
-- Data for Name: breeds; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.breeds (id, name, animal_type_id) FROM stdin;
1	Holstein	1
2	Jersey	1
3	Pardo Suizo	1
4	Brahman	1
5	Angus	1
6	Hereford	1
7	Charolais	1
8	Merino	2
9	Dorper	2
10	Suffolk	2
11	Yorkshire	3
12	Landrace	3
13	Duroc	3
14	Saanen	4
15	Alpina	4
16	Nubian	4
17	Leghorn	5
18	Rhode Island Red	5
19	Broiler	5
\.


--
-- TOC entry 5071 (class 0 OID 35185)
-- Dependencies: 230
-- Data for Name: chicken_inventory; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.chicken_inventory (id, inventory_date, quantity) FROM stdin;
1	2025-01-01	111
2	2025-01-02	81
3	2025-01-03	83
4	2025-01-04	65
5	2025-01-05	119
6	2025-01-06	61
7	2025-01-07	52
8	2025-01-08	123
9	2025-01-09	58
10	2025-01-10	51
11	2025-01-11	77
12	2025-01-12	91
13	2025-01-13	124
14	2025-01-14	129
15	2025-01-15	101
16	2025-01-16	71
17	2025-01-17	55
18	2025-01-18	99
19	2025-01-19	96
20	2025-01-20	113
21	2025-01-21	72
22	2025-01-22	128
23	2025-01-23	69
24	2025-01-24	102
25	2025-01-25	125
26	2025-01-26	58
27	2025-01-27	78
28	2025-01-28	60
29	2025-01-29	110
30	2025-01-30	105
31	2025-01-31	62
32	2025-02-01	104
33	2025-02-02	114
34	2025-02-03	83
35	2025-02-04	86
36	2025-02-05	75
37	2025-02-06	120
38	2025-02-07	86
39	2025-02-08	81
40	2025-02-09	99
41	2025-02-10	54
42	2025-02-11	85
43	2025-02-12	66
44	2025-02-13	95
45	2025-02-14	53
46	2025-02-15	66
47	2025-02-16	51
48	2025-02-17	130
49	2025-02-18	75
50	2025-02-19	126
51	2025-02-20	91
52	2025-02-21	130
53	2025-02-22	112
54	2025-02-23	83
55	2025-02-24	76
56	2025-02-25	56
57	2025-02-26	91
58	2025-02-27	105
59	2025-02-28	112
60	2025-03-01	105
61	2025-03-02	109
62	2025-03-03	128
63	2025-03-04	59
64	2025-03-05	119
65	2025-03-06	127
66	2025-03-07	72
67	2025-03-08	124
68	2025-03-09	121
69	2025-03-10	106
70	2025-03-11	87
71	2025-03-12	76
72	2025-03-13	65
73	2025-03-14	83
74	2025-03-15	96
75	2025-03-16	99
76	2025-03-17	63
77	2025-03-18	110
78	2025-03-19	110
79	2025-03-20	97
80	2025-03-21	86
81	2025-03-22	104
82	2025-03-23	119
83	2025-03-24	50
84	2025-03-25	128
85	2025-03-26	50
86	2025-03-27	95
87	2025-03-28	68
88	2025-03-29	68
89	2025-03-30	58
90	2025-03-31	85
91	2025-04-01	75
92	2025-04-02	117
93	2025-04-03	96
94	2025-04-04	72
95	2025-04-05	69
96	2025-04-06	72
97	2025-04-07	59
98	2025-04-08	119
99	2025-04-09	107
100	2025-04-10	109
101	2025-04-11	69
102	2025-04-12	89
103	2025-04-13	107
104	2025-04-14	99
105	2025-04-15	65
106	2025-04-16	127
107	2025-04-17	116
108	2025-04-18	111
109	2025-04-19	62
110	2025-04-20	96
111	2025-04-21	68
112	2025-04-22	97
113	2025-04-23	129
114	2025-04-24	61
115	2025-04-25	113
116	2025-04-26	58
117	2025-04-27	129
118	2025-04-28	93
119	2025-04-29	60
120	2025-04-30	67
121	2025-05-01	88
122	2025-05-02	105
123	2025-05-03	94
124	2025-05-04	107
125	2025-05-05	123
126	2025-05-06	74
127	2025-05-07	119
128	2025-05-08	76
129	2025-05-09	95
130	2025-05-10	82
131	2025-05-11	88
132	2025-05-12	89
133	2025-05-13	66
134	2025-05-14	103
135	2025-05-15	72
136	2025-05-16	92
137	2025-05-17	97
138	2025-05-18	82
139	2025-05-19	122
140	2025-05-20	61
141	2025-05-21	109
142	2025-05-22	75
143	2025-05-23	53
144	2025-05-24	56
145	2025-05-25	109
146	2025-05-26	117
147	2025-05-27	124
148	2025-05-28	79
149	2025-05-29	79
150	2025-05-30	100
151	2025-05-31	61
152	2025-06-01	117
153	2025-06-02	55
154	2025-06-03	62
155	2025-06-04	50
156	2025-06-05	92
157	2025-06-06	101
158	2025-06-07	62
159	2025-06-08	55
160	2025-06-09	52
161	2025-06-10	68
162	2025-06-11	79
163	2025-06-12	97
164	2025-06-13	77
165	2025-06-14	63
166	2025-06-15	71
167	2025-06-16	101
168	2025-06-17	120
169	2025-06-18	93
170	2025-06-19	63
171	2025-06-20	126
172	2025-06-21	52
173	2025-06-22	97
174	2025-06-23	94
175	2025-06-24	100
176	2025-06-25	89
177	2025-06-26	96
178	2025-06-27	51
179	2025-06-28	69
180	2025-06-29	123
181	2025-06-30	80
182	2025-07-01	101
183	2025-07-02	65
184	2025-07-03	52
185	2025-07-04	129
186	2025-07-05	85
187	2025-07-06	74
188	2025-07-07	90
189	2025-07-08	86
190	2025-07-09	79
191	2025-07-10	112
192	2025-07-11	76
193	2025-07-12	118
194	2025-07-13	72
195	2025-07-14	62
196	2025-07-15	74
197	2025-07-16	100
198	2025-07-17	122
199	2025-07-18	129
200	2025-07-19	112
201	2025-07-20	94
202	2025-07-21	112
203	2025-07-22	58
204	2025-07-23	124
205	2025-07-24	120
206	2025-07-25	84
207	2025-07-26	117
208	2025-07-27	61
209	2025-07-28	81
210	2025-07-29	60
211	2025-07-30	63
212	2025-07-31	111
213	2025-08-01	96
214	2025-08-02	84
215	2025-08-03	115
216	2025-08-04	75
217	2025-08-05	65
218	2025-08-06	124
219	2025-08-07	116
220	2025-08-08	106
221	2025-08-09	74
222	2025-08-10	84
223	2025-08-11	52
224	2025-08-12	84
225	2025-08-13	107
226	2025-08-14	123
227	2025-08-15	96
228	2025-08-16	73
229	2025-08-17	78
230	2025-08-18	113
231	2025-08-19	81
232	2025-08-20	79
233	2025-08-21	92
234	2025-08-22	50
235	2025-08-23	79
236	2025-08-24	59
237	2025-08-25	77
238	2025-08-26	94
239	2025-08-27	51
240	2025-08-28	102
241	2025-08-29	107
242	2025-08-30	64
243	2025-08-31	100
244	2025-09-01	107
245	2025-09-02	107
246	2025-09-03	68
247	2025-09-04	93
248	2025-09-05	94
249	2025-09-06	121
250	2025-09-07	51
251	2025-09-08	79
252	2025-09-09	98
253	2025-09-10	101
254	2025-09-11	80
255	2025-09-12	85
256	2025-09-13	105
257	2025-09-14	115
258	2025-09-15	56
259	2025-09-16	102
260	2025-09-17	116
261	2025-09-18	102
262	2025-09-19	124
263	2025-09-20	82
264	2025-09-21	85
265	2025-09-22	68
266	2025-09-23	121
267	2025-09-24	69
268	2025-09-25	120
269	2025-09-26	79
270	2025-09-27	130
271	2025-09-28	50
272	2025-09-29	108
273	2025-09-30	95
274	2025-10-01	57
275	2025-10-02	70
276	2025-10-03	91
277	2025-10-04	98
278	2025-10-05	95
279	2025-10-06	78
280	2025-10-07	113
281	2025-10-08	98
282	2025-10-09	73
283	2025-10-10	120
284	2025-10-11	70
285	2025-10-12	87
286	2025-10-13	51
287	2025-10-14	86
288	2025-10-15	110
289	2025-10-16	92
290	2025-10-17	81
291	2025-10-18	80
292	2025-10-19	69
293	2025-10-20	127
294	2025-10-21	128
295	2025-10-22	124
296	2025-10-23	102
297	2025-10-24	68
298	2025-10-25	73
299	2025-10-26	65
300	2025-10-27	54
301	2025-10-28	119
302	2025-10-29	123
303	2025-10-30	57
304	2025-10-31	61
305	2025-11-01	94
306	2025-11-02	94
307	2025-11-03	114
308	2025-11-04	108
309	2025-11-05	124
310	2025-11-06	70
311	2025-11-07	71
312	2025-11-08	114
313	2025-11-09	54
314	2025-11-10	127
315	2025-11-11	60
316	2025-11-12	89
317	2025-11-13	72
318	2025-11-14	91
319	2025-11-15	109
320	2025-11-16	101
321	2025-11-17	106
322	2025-11-18	74
323	2025-11-19	90
324	2025-11-20	65
325	2025-11-21	97
326	2025-11-22	69
327	2025-11-23	98
328	2025-11-24	50
329	2025-11-25	99
330	2025-11-26	122
331	2025-11-27	66
332	2025-11-28	124
333	2025-11-29	56
334	2025-11-30	58
335	2025-12-01	78
336	2025-12-02	124
337	2025-12-03	108
338	2025-12-04	93
339	2025-12-05	111
340	2025-12-06	126
341	2025-12-07	72
342	2025-12-08	57
343	2025-12-09	80
344	2025-12-10	108
345	2025-12-11	99
346	2025-12-12	69
347	2025-12-13	92
348	2025-12-14	129
349	2025-12-15	129
350	2025-12-16	125
351	2025-12-17	96
352	2025-12-18	100
353	2025-12-19	52
354	2025-12-20	126
355	2025-12-21	83
356	2025-12-22	117
357	2025-12-23	58
358	2025-12-24	106
359	2025-12-25	78
360	2025-12-26	107
361	2025-12-27	81
362	2025-12-28	124
363	2025-12-29	53
364	2025-12-30	112
365	2025-12-31	79
366	2026-01-01	82
367	2026-01-02	51
368	2026-01-03	98
369	2026-01-04	117
370	2026-01-05	127
371	2026-01-06	99
372	2026-01-07	80
373	2026-01-08	118
374	2026-01-09	64
375	2026-01-10	86
376	2026-01-11	125
377	2026-01-12	76
378	2026-01-13	83
379	2026-01-14	71
380	2026-01-15	107
381	2026-01-16	74
382	2026-01-17	58
383	2026-01-18	121
384	2026-01-19	58
385	2026-01-20	76
386	2026-01-21	79
387	2026-01-22	119
388	2026-01-23	128
389	2026-01-24	64
390	2026-01-25	81
391	2026-01-26	70
392	2026-01-27	66
393	2026-01-28	70
394	2026-01-29	53
395	2026-01-30	87
396	2026-01-31	81
397	2026-02-01	127
398	2026-02-02	117
399	2026-02-03	52
400	2026-02-04	120
401	2026-02-05	77
402	2026-02-06	75
403	2026-02-07	85
404	2026-02-08	127
405	2026-02-09	88
406	2026-02-10	114
407	2026-02-11	65
408	2026-02-12	56
409	2026-02-13	84
410	2026-02-14	59
411	2026-02-15	73
412	2026-02-16	87
413	2026-02-17	121
414	2026-02-18	52
415	2026-02-19	104
416	2026-02-20	85
417	2026-02-21	55
418	2026-02-22	122
419	2026-02-23	55
420	2026-02-24	61
421	2026-02-25	77
422	2026-02-26	54
423	2026-02-27	65
424	2026-02-28	55
425	2026-03-01	127
426	2026-03-02	110
427	2026-03-03	101
428	2026-03-04	126
429	2026-03-05	106
430	2026-03-06	72
431	2026-03-07	98
432	2026-03-08	85
433	2026-03-09	76
434	2026-03-10	126
435	2026-03-11	55
436	2026-03-12	112
437	2026-03-13	66
438	2026-03-14	107
439	2026-03-15	55
440	2026-03-16	85
441	2026-03-17	57
442	2026-03-18	106
443	2026-03-19	124
444	2026-03-20	104
445	2026-03-21	88
446	2026-03-22	127
447	2026-03-23	75
448	2026-03-24	96
449	2026-03-25	60
450	2026-03-26	118
451	2026-03-27	50
452	2026-03-28	98
453	2026-03-29	126
454	2026-03-30	129
455	2026-03-31	63
456	2026-04-01	70
457	2026-04-02	100
458	2026-04-03	75
459	2026-04-04	105
460	2026-04-05	94
461	2026-04-06	68
462	2026-04-07	129
463	2026-04-08	54
464	2026-04-09	119
465	2026-04-10	94
466	2026-04-11	116
467	2026-04-12	77
468	2026-04-13	65
469	2026-04-14	115
470	2026-04-15	105
471	2026-04-16	75
472	2026-04-17	71
473	2026-04-18	111
474	2026-04-19	69
475	2026-04-20	53
476	2026-04-21	112
477	2026-04-22	65
478	2026-04-23	123
479	2026-04-24	126
480	2026-04-25	87
481	2026-04-26	119
482	2026-04-27	114
483	2026-04-28	62
\.


--
-- TOC entry 5069 (class 0 OID 35175)
-- Dependencies: 228
-- Data for Name: egg_production; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.egg_production (id, production_date, quantity) FROM stdin;
1	2025-01-01	97
2	2025-01-02	54
3	2025-01-03	74
4	2025-01-04	55
5	2025-01-05	42
6	2025-01-06	81
7	2025-01-07	80
8	2025-01-08	79
9	2025-01-09	41
10	2025-01-10	86
11	2025-01-11	49
12	2025-01-12	67
13	2025-01-13	54
14	2025-01-14	69
15	2025-01-15	86
16	2025-01-16	83
17	2025-01-17	92
18	2025-01-18	76
19	2025-01-19	74
20	2025-01-20	98
21	2025-01-21	50
22	2025-01-22	55
23	2025-01-23	94
24	2025-01-24	88
25	2025-01-25	93
26	2025-01-26	45
27	2025-01-27	97
28	2025-01-28	72
29	2025-01-29	64
30	2025-01-30	86
31	2025-01-31	91
32	2025-02-01	73
33	2025-02-02	44
34	2025-02-03	61
35	2025-02-04	69
36	2025-02-05	86
37	2025-02-06	55
38	2025-02-07	53
39	2025-02-08	76
40	2025-02-09	58
41	2025-02-10	57
42	2025-02-11	86
43	2025-02-12	71
44	2025-02-13	82
45	2025-02-14	87
46	2025-02-15	69
47	2025-02-16	75
48	2025-02-17	84
49	2025-02-18	57
50	2025-02-19	56
51	2025-02-20	50
52	2025-02-21	56
53	2025-02-22	65
54	2025-02-23	57
55	2025-02-24	89
56	2025-02-25	59
57	2025-02-26	83
58	2025-02-27	87
59	2025-02-28	52
60	2025-03-01	89
61	2025-03-02	65
62	2025-03-03	59
63	2025-03-04	44
64	2025-03-05	100
65	2025-03-06	71
66	2025-03-07	54
67	2025-03-08	74
68	2025-03-09	90
69	2025-03-10	72
70	2025-03-11	75
71	2025-03-12	90
72	2025-03-13	44
73	2025-03-14	49
74	2025-03-15	79
75	2025-03-16	87
76	2025-03-17	50
77	2025-03-18	71
78	2025-03-19	86
79	2025-03-20	61
80	2025-03-21	79
81	2025-03-22	87
82	2025-03-23	73
83	2025-03-24	45
84	2025-03-25	48
85	2025-03-26	89
86	2025-03-27	100
87	2025-03-28	56
88	2025-03-29	62
89	2025-03-30	79
90	2025-03-31	81
91	2025-04-01	77
92	2025-04-02	100
93	2025-04-03	77
94	2025-04-04	97
95	2025-04-05	52
96	2025-04-06	79
97	2025-04-07	44
98	2025-04-08	100
99	2025-04-09	51
100	2025-04-10	50
101	2025-04-11	80
102	2025-04-12	68
103	2025-04-13	72
104	2025-04-14	61
105	2025-04-15	50
106	2025-04-16	53
107	2025-04-17	49
108	2025-04-18	81
109	2025-04-19	99
110	2025-04-20	59
111	2025-04-21	41
112	2025-04-22	41
113	2025-04-23	97
114	2025-04-24	75
115	2025-04-25	88
116	2025-04-26	81
117	2025-04-27	54
118	2025-04-28	89
119	2025-04-29	86
120	2025-04-30	69
121	2025-05-01	44
122	2025-05-02	92
123	2025-05-03	63
124	2025-05-04	46
125	2025-05-05	95
126	2025-05-06	45
127	2025-05-07	85
128	2025-05-08	87
129	2025-05-09	54
130	2025-05-10	47
131	2025-05-11	68
132	2025-05-12	48
133	2025-05-13	80
134	2025-05-14	65
135	2025-05-15	50
136	2025-05-16	44
137	2025-05-17	93
138	2025-05-18	46
139	2025-05-19	88
140	2025-05-20	95
141	2025-05-21	49
142	2025-05-22	100
143	2025-05-23	85
144	2025-05-24	73
145	2025-05-25	82
146	2025-05-26	49
147	2025-05-27	44
148	2025-05-28	81
149	2025-05-29	43
150	2025-05-30	89
151	2025-05-31	61
152	2025-06-01	60
153	2025-06-02	49
154	2025-06-03	99
155	2025-06-04	42
156	2025-06-05	74
157	2025-06-06	48
158	2025-06-07	95
159	2025-06-08	58
160	2025-06-09	62
161	2025-06-10	48
162	2025-06-11	47
163	2025-06-12	59
164	2025-06-13	59
165	2025-06-14	50
166	2025-06-15	69
167	2025-06-16	42
168	2025-06-17	56
169	2025-06-18	54
170	2025-06-19	41
171	2025-06-20	99
172	2025-06-21	58
173	2025-06-22	65
174	2025-06-23	56
175	2025-06-24	59
176	2025-06-25	47
177	2025-06-26	84
178	2025-06-27	63
179	2025-06-28	98
180	2025-06-29	60
181	2025-06-30	95
182	2025-07-01	88
183	2025-07-02	58
184	2025-07-03	62
185	2025-07-04	52
186	2025-07-05	41
187	2025-07-06	65
188	2025-07-07	89
189	2025-07-08	43
190	2025-07-09	66
191	2025-07-10	67
192	2025-07-11	79
193	2025-07-12	52
194	2025-07-13	80
195	2025-07-14	78
196	2025-07-15	55
197	2025-07-16	68
198	2025-07-17	87
199	2025-07-18	50
200	2025-07-19	46
201	2025-07-20	70
202	2025-07-21	74
203	2025-07-22	71
204	2025-07-23	91
205	2025-07-24	50
206	2025-07-25	67
207	2025-07-26	99
208	2025-07-27	70
209	2025-07-28	42
210	2025-07-29	71
211	2025-07-30	87
212	2025-07-31	68
213	2025-08-01	99
214	2025-08-02	56
215	2025-08-03	98
216	2025-08-04	79
217	2025-08-05	100
218	2025-08-06	62
219	2025-08-07	94
220	2025-08-08	90
221	2025-08-09	90
222	2025-08-10	65
223	2025-08-11	99
224	2025-08-12	69
225	2025-08-13	53
226	2025-08-14	50
227	2025-08-15	79
228	2025-08-16	98
229	2025-08-17	69
230	2025-08-18	77
231	2025-08-19	73
232	2025-08-20	82
233	2025-08-21	83
234	2025-08-22	82
235	2025-08-23	82
236	2025-08-24	53
237	2025-08-25	54
238	2025-08-26	45
239	2025-08-27	76
240	2025-08-28	83
241	2025-08-29	54
242	2025-08-30	99
243	2025-08-31	82
244	2025-09-01	58
245	2025-09-02	56
246	2025-09-03	68
247	2025-09-04	80
248	2025-09-05	59
249	2025-09-06	56
250	2025-09-07	69
251	2025-09-08	54
252	2025-09-09	82
253	2025-09-10	44
254	2025-09-11	90
255	2025-09-12	50
256	2025-09-13	64
257	2025-09-14	87
258	2025-09-15	41
259	2025-09-16	46
260	2025-09-17	85
261	2025-09-18	93
262	2025-09-19	98
263	2025-09-20	81
264	2025-09-21	65
265	2025-09-22	57
266	2025-09-23	86
267	2025-09-24	89
268	2025-09-25	42
269	2025-09-26	42
270	2025-09-27	65
271	2025-09-28	62
272	2025-09-29	65
273	2025-09-30	45
274	2025-10-01	71
275	2025-10-02	61
276	2025-10-03	98
277	2025-10-04	44
278	2025-10-05	44
279	2025-10-06	46
280	2025-10-07	83
281	2025-10-08	78
282	2025-10-09	94
283	2025-10-10	73
284	2025-10-11	70
285	2025-10-12	88
286	2025-10-13	96
287	2025-10-14	59
288	2025-10-15	90
289	2025-10-16	69
290	2025-10-17	70
291	2025-10-18	56
292	2025-10-19	98
293	2025-10-20	60
294	2025-10-21	47
295	2025-10-22	41
296	2025-10-23	88
297	2025-10-24	42
298	2025-10-25	75
299	2025-10-26	44
300	2025-10-27	85
301	2025-10-28	90
302	2025-10-29	46
303	2025-10-30	49
304	2025-10-31	78
305	2025-11-01	60
306	2025-11-02	82
307	2025-11-03	95
308	2025-11-04	49
309	2025-11-05	66
310	2025-11-06	43
311	2025-11-07	48
312	2025-11-08	71
313	2025-11-09	53
314	2025-11-10	94
315	2025-11-11	79
316	2025-11-12	47
317	2025-11-13	60
318	2025-11-14	82
319	2025-11-15	63
320	2025-11-16	62
321	2025-11-17	72
322	2025-11-18	62
323	2025-11-19	62
324	2025-11-20	96
325	2025-11-21	45
326	2025-11-22	97
327	2025-11-23	91
328	2025-11-24	84
329	2025-11-25	49
330	2025-11-26	41
331	2025-11-27	94
332	2025-11-28	42
333	2025-11-29	57
334	2025-11-30	60
335	2025-12-01	49
336	2025-12-02	49
337	2025-12-03	93
338	2025-12-04	85
339	2025-12-05	86
340	2025-12-06	55
341	2025-12-07	45
342	2025-12-08	53
343	2025-12-09	48
344	2025-12-10	72
345	2025-12-11	66
346	2025-12-12	71
347	2025-12-13	59
348	2025-12-14	92
349	2025-12-15	68
350	2025-12-16	88
351	2025-12-17	63
352	2025-12-18	58
353	2025-12-19	68
354	2025-12-20	95
355	2025-12-21	67
356	2025-12-22	100
357	2025-12-23	78
358	2025-12-24	51
359	2025-12-25	86
360	2025-12-26	94
361	2025-12-27	81
362	2025-12-28	81
363	2025-12-29	95
364	2025-12-30	72
365	2025-12-31	45
366	2026-01-01	83
367	2026-01-02	47
368	2026-01-03	46
369	2026-01-04	56
370	2026-01-05	99
371	2026-01-06	90
372	2026-01-07	42
373	2026-01-08	73
374	2026-01-09	99
375	2026-01-10	64
376	2026-01-11	43
377	2026-01-12	49
378	2026-01-13	45
379	2026-01-14	78
380	2026-01-15	57
381	2026-01-16	55
382	2026-01-17	91
383	2026-01-18	84
384	2026-01-19	66
385	2026-01-20	84
386	2026-01-21	47
387	2026-01-22	79
388	2026-01-23	75
389	2026-01-24	85
390	2026-01-25	71
391	2026-01-26	84
392	2026-01-27	96
393	2026-01-28	90
394	2026-01-29	75
395	2026-01-30	90
396	2026-01-31	75
397	2026-02-01	90
398	2026-02-02	79
399	2026-02-03	97
400	2026-02-04	62
401	2026-02-05	54
402	2026-02-06	83
403	2026-02-07	91
404	2026-02-08	43
405	2026-02-09	87
406	2026-02-10	93
407	2026-02-11	78
408	2026-02-12	41
409	2026-02-13	46
410	2026-02-14	73
411	2026-02-15	98
412	2026-02-16	92
413	2026-02-17	52
414	2026-02-18	63
415	2026-02-19	77
416	2026-02-20	65
417	2026-02-21	53
418	2026-02-22	82
419	2026-02-23	81
420	2026-02-24	99
421	2026-02-25	46
422	2026-02-26	53
423	2026-02-27	49
424	2026-02-28	66
425	2026-03-01	50
426	2026-03-02	57
427	2026-03-03	45
428	2026-03-04	84
429	2026-03-05	95
430	2026-03-06	59
431	2026-03-07	46
432	2026-03-08	91
433	2026-03-09	93
434	2026-03-10	62
435	2026-03-11	63
436	2026-03-12	87
437	2026-03-13	75
438	2026-03-14	48
439	2026-03-15	79
440	2026-03-16	82
441	2026-03-17	74
442	2026-03-18	93
443	2026-03-19	70
444	2026-03-20	75
445	2026-03-21	71
446	2026-03-22	83
447	2026-03-23	75
448	2026-03-24	72
449	2026-03-25	72
450	2026-03-26	84
451	2026-03-27	65
452	2026-03-28	58
453	2026-03-29	47
454	2026-03-30	91
455	2026-03-31	94
456	2026-04-01	75
457	2026-04-02	47
458	2026-04-03	89
459	2026-04-04	70
460	2026-04-05	89
461	2026-04-06	48
462	2026-04-07	93
463	2026-04-08	45
464	2026-04-09	57
465	2026-04-10	61
466	2026-04-11	44
467	2026-04-12	70
468	2026-04-13	95
469	2026-04-14	56
470	2026-04-15	77
471	2026-04-16	96
472	2026-04-17	96
473	2026-04-18	62
474	2026-04-19	72
475	2026-04-20	76
476	2026-04-21	80
477	2026-04-22	78
478	2026-04-23	58
479	2026-04-24	95
480	2026-04-25	84
481	2026-04-26	85
482	2026-04-27	68
483	2026-04-28	61
\.


--
-- TOC entry 5101 (class 0 OID 35432)
-- Dependencies: 260
-- Data for Name: employees; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.employees (id, name, role, monthly_salary) FROM stdin;
1	Carlos Méndez	ordeñador	450.00
2	María Rivas	veterinaria	700.00
3	Juan Pérez	administrador	600.00
\.


--
-- TOC entry 5061 (class 0 OID 35080)
-- Dependencies: 220
-- Data for Name: facilities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.facilities (id, name, facility_type, capacity, location, notes) FROM stdin;
1	Establo Principal	establo	80	Nave 1	\N
2	Corral Ovejas	corral	50	Zona norte	\N
3	Galpón Cerdos	galpón	30	Zona este	\N
4	Potrero	potrero	100	Campo abierto	\N
5	Galpón Aves	galpón	500	Zona sur	\N
\.


--
-- TOC entry 5077 (class 0 OID 35223)
-- Dependencies: 236
-- Data for Name: feeding; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.feeding (id, animal_id, food_id, feeding_date, quantity_kg) FROM stdin;
1	1	1	2025-01-01	5.45
2	1	2	2025-01-04	4.10
3	1	1	2025-01-07	6.59
4	1	2	2025-01-10	6.86
5	1	1	2025-01-13	3.93
6	1	1	2025-01-16	4.12
7	1	1	2025-01-19	6.16
8	1	1	2025-01-22	2.01
9	1	1	2025-01-25	2.35
10	1	2	2025-01-28	2.88
11	1	1	2025-01-31	5.51
12	1	1	2025-02-03	5.32
13	1	2	2025-02-06	4.40
14	1	2	2025-02-09	4.25
15	1	1	2025-02-12	5.82
16	1	2	2025-02-15	5.92
17	1	1	2025-02-18	5.58
18	1	1	2025-02-21	2.51
19	1	2	2025-02-24	3.11
20	1	2	2025-02-27	3.48
21	1	1	2025-03-02	2.28
22	1	2	2025-03-05	2.50
23	1	1	2025-03-08	6.05
24	1	2	2025-03-11	4.49
25	1	2	2025-03-14	3.08
26	1	1	2025-03-17	6.09
27	1	2	2025-03-20	6.90
28	1	1	2025-03-23	5.20
29	1	2	2025-03-26	2.23
30	1	1	2025-03-29	6.20
31	1	1	2025-04-01	3.86
32	1	1	2025-04-04	4.55
33	1	2	2025-04-07	5.53
34	1	2	2025-04-10	4.51
35	1	1	2025-04-13	3.63
36	1	2	2025-04-16	3.15
37	1	2	2025-04-19	2.42
38	1	2	2025-04-22	4.46
39	1	2	2025-04-25	5.29
40	1	1	2025-04-28	3.03
41	1	2	2025-05-01	4.06
42	1	2	2025-05-04	2.07
43	1	1	2025-05-07	2.89
44	1	1	2025-05-10	6.80
45	1	1	2025-05-13	2.22
46	1	2	2025-05-16	4.48
47	1	2	2025-05-19	2.99
48	1	1	2025-05-22	2.26
49	1	2	2025-05-25	3.58
50	1	1	2025-05-28	3.16
51	1	1	2025-05-31	4.37
52	1	1	2025-06-03	6.54
53	1	2	2025-06-06	2.66
54	1	1	2025-06-09	5.37
55	1	1	2025-06-12	4.80
56	1	1	2025-06-15	6.33
57	1	1	2025-06-18	5.19
58	1	1	2025-06-21	2.22
59	1	1	2025-06-24	6.93
60	1	1	2025-06-27	2.80
61	1	1	2025-06-30	2.58
62	1	2	2025-07-03	4.71
63	1	1	2025-07-06	4.31
64	1	2	2025-07-09	5.61
65	1	1	2025-07-12	5.46
66	1	2	2025-07-15	5.06
67	1	1	2025-07-18	4.53
68	1	1	2025-07-21	2.65
69	1	1	2025-07-24	6.46
70	1	1	2025-07-27	6.07
71	1	1	2025-07-30	3.11
72	1	2	2025-08-02	6.49
73	1	1	2025-08-05	2.44
74	1	2	2025-08-08	2.80
75	1	2	2025-08-11	4.13
76	1	2	2025-08-14	3.98
77	1	1	2025-08-17	2.51
78	1	1	2025-08-20	3.84
79	1	2	2025-08-23	2.08
80	1	2	2025-08-26	5.95
81	1	2	2025-08-29	6.46
82	1	1	2025-09-01	4.33
83	1	2	2025-09-04	3.90
84	1	2	2025-09-07	5.42
85	1	1	2025-09-10	3.29
86	1	1	2025-09-13	5.13
87	1	2	2025-09-16	3.64
88	1	2	2025-09-19	3.66
89	1	2	2025-09-22	5.29
90	1	2	2025-09-25	2.65
91	1	2	2025-09-28	4.35
92	1	2	2025-10-01	5.25
93	1	1	2025-10-04	4.90
94	1	1	2025-10-07	6.45
95	1	2	2025-10-10	5.25
96	1	1	2025-10-13	4.55
97	1	2	2025-10-16	5.78
98	1	2	2025-10-19	3.13
99	1	2	2025-10-22	5.68
100	1	1	2025-10-25	2.32
101	1	2	2025-10-28	2.59
102	1	1	2025-10-31	4.07
103	1	1	2025-11-03	6.59
104	1	2	2025-11-06	3.35
105	1	2	2025-11-09	6.38
106	1	1	2025-11-12	4.16
107	1	1	2025-11-15	4.25
108	1	2	2025-11-18	5.34
109	1	2	2025-11-21	6.51
110	1	1	2025-11-24	3.78
111	1	2	2025-11-27	5.44
112	1	2	2025-11-30	4.50
113	1	1	2025-12-03	2.87
114	1	2	2025-12-06	2.37
115	1	1	2025-12-09	2.70
116	1	1	2025-12-12	5.11
117	1	2	2025-12-15	5.96
118	1	1	2025-12-18	2.93
119	1	1	2025-12-21	2.23
120	1	2	2025-12-24	5.50
121	1	1	2025-12-27	3.69
122	1	1	2025-12-30	3.47
123	1	2	2026-01-02	6.48
124	1	1	2026-01-05	6.94
125	1	1	2026-01-08	5.70
126	1	2	2026-01-11	2.32
127	1	2	2026-01-14	5.63
128	1	1	2026-01-17	4.93
129	1	1	2026-01-20	5.07
130	1	1	2026-01-23	2.41
131	1	2	2026-01-26	4.98
132	1	2	2026-01-29	2.13
133	1	2	2026-02-01	2.95
134	1	1	2026-02-04	2.11
135	1	2	2026-02-07	3.65
136	1	1	2026-02-10	6.87
137	1	2	2026-02-13	4.02
138	1	1	2026-02-16	4.28
139	1	1	2026-02-19	3.95
140	1	2	2026-02-22	6.31
141	1	1	2026-02-25	4.91
142	1	2	2026-02-28	5.11
143	1	1	2026-03-03	3.30
144	1	2	2026-03-06	6.34
145	1	2	2026-03-09	4.94
146	1	1	2026-03-12	4.06
147	1	2	2026-03-15	5.64
148	1	2	2026-03-18	5.06
149	1	2	2026-03-21	2.48
150	1	2	2026-03-24	5.15
151	1	1	2026-03-27	2.47
152	1	2	2026-03-30	6.92
153	1	1	2026-04-02	6.28
154	1	2	2026-04-05	2.47
155	1	2	2026-04-08	5.72
156	1	2	2026-04-11	2.79
157	1	1	2026-04-14	3.30
158	1	1	2026-04-17	5.95
159	1	1	2026-04-20	4.00
160	1	2	2026-04-23	6.69
161	1	1	2026-04-26	3.85
162	2	2	2025-01-01	4.41
163	2	1	2025-01-04	2.71
164	2	2	2025-01-07	6.23
165	2	1	2025-01-10	4.68
166	2	1	2025-01-13	4.59
167	2	1	2025-01-16	3.76
168	2	2	2025-01-19	6.79
169	2	2	2025-01-22	4.14
170	2	1	2025-01-25	2.93
171	2	2	2025-01-28	3.81
172	2	2	2025-01-31	5.98
173	2	2	2025-02-03	4.54
174	2	1	2025-02-06	4.21
175	2	1	2025-02-09	3.44
176	2	1	2025-02-12	2.84
177	2	2	2025-02-15	2.58
178	2	1	2025-02-18	5.79
179	2	1	2025-02-21	3.67
180	2	1	2025-02-24	6.95
181	2	2	2025-02-27	3.23
182	2	1	2025-03-02	4.98
183	2	2	2025-03-05	6.52
184	2	1	2025-03-08	6.00
185	2	1	2025-03-11	6.16
186	2	1	2025-03-14	2.88
187	2	2	2025-03-17	4.14
188	2	1	2025-03-20	5.39
189	2	1	2025-03-23	5.46
190	2	2	2025-03-26	2.22
191	2	1	2025-03-29	3.45
192	2	2	2025-04-01	6.07
193	2	1	2025-04-04	6.22
194	2	2	2025-04-07	3.35
195	2	1	2025-04-10	4.62
196	2	1	2025-04-13	2.94
197	2	1	2025-04-16	2.43
198	2	1	2025-04-19	4.14
199	2	1	2025-04-22	3.77
200	2	2	2025-04-25	5.77
201	2	1	2025-04-28	3.01
202	2	1	2025-05-01	5.76
203	2	1	2025-05-04	3.23
204	2	2	2025-05-07	2.42
205	2	2	2025-05-10	5.93
206	2	2	2025-05-13	4.70
207	2	1	2025-05-16	4.52
208	2	2	2025-05-19	2.07
209	2	2	2025-05-22	2.30
210	2	1	2025-05-25	5.04
211	2	2	2025-05-28	3.97
212	2	1	2025-05-31	4.44
213	2	2	2025-06-03	2.04
214	2	1	2025-06-06	4.77
215	2	1	2025-06-09	2.73
216	2	1	2025-06-12	4.13
217	2	1	2025-06-15	6.79
218	2	1	2025-06-18	4.52
219	2	2	2025-06-21	3.15
220	2	1	2025-06-24	6.45
221	2	2	2025-06-27	4.67
222	2	2	2025-06-30	3.26
223	2	1	2025-07-03	3.05
224	2	1	2025-07-06	5.37
225	2	2	2025-07-09	2.54
226	2	1	2025-07-12	6.65
227	2	2	2025-07-15	5.38
228	2	1	2025-07-18	4.94
229	2	1	2025-07-21	5.10
230	2	2	2025-07-24	2.59
231	2	2	2025-07-27	4.88
232	2	1	2025-07-30	4.15
233	2	2	2025-08-02	5.98
234	2	2	2025-08-05	4.83
235	2	2	2025-08-08	5.71
236	2	1	2025-08-11	3.15
237	2	1	2025-08-14	4.81
238	2	1	2025-08-17	3.11
239	2	1	2025-08-20	6.71
240	2	2	2025-08-23	5.28
241	2	2	2025-08-26	5.53
242	2	1	2025-08-29	5.89
243	2	1	2025-09-01	2.44
244	2	2	2025-09-04	3.95
245	2	2	2025-09-07	3.35
246	2	2	2025-09-10	3.67
247	2	1	2025-09-13	4.16
248	2	2	2025-09-16	3.38
249	2	2	2025-09-19	6.45
250	2	1	2025-09-22	3.56
251	2	1	2025-09-25	6.17
252	2	2	2025-09-28	3.62
253	2	2	2025-10-01	4.99
254	2	2	2025-10-04	6.41
255	2	1	2025-10-07	4.74
256	2	2	2025-10-10	4.36
257	2	1	2025-10-13	3.04
258	2	2	2025-10-16	3.51
259	2	2	2025-10-19	6.25
260	2	1	2025-10-22	5.22
261	2	2	2025-10-25	3.81
262	2	2	2025-10-28	3.90
263	2	2	2025-10-31	7.00
264	2	1	2025-11-03	4.71
265	2	2	2025-11-06	6.27
266	2	2	2025-11-09	3.60
267	2	2	2025-11-12	6.77
268	2	2	2025-11-15	2.10
269	2	2	2025-11-18	2.57
270	2	2	2025-11-21	5.06
271	2	1	2025-11-24	4.69
272	2	1	2025-11-27	6.82
273	2	2	2025-11-30	4.06
274	2	1	2025-12-03	2.11
275	2	1	2025-12-06	6.02
276	2	2	2025-12-09	6.88
277	2	1	2025-12-12	4.99
278	2	1	2025-12-15	4.70
279	2	2	2025-12-18	5.77
280	2	2	2025-12-21	5.34
281	2	2	2025-12-24	5.19
282	2	2	2025-12-27	2.61
283	2	2	2025-12-30	5.86
284	2	1	2026-01-02	3.66
285	2	2	2026-01-05	2.49
286	2	2	2026-01-08	6.29
287	2	2	2026-01-11	4.03
288	2	1	2026-01-14	4.54
289	2	2	2026-01-17	2.22
290	2	1	2026-01-20	2.76
291	2	2	2026-01-23	4.02
292	2	2	2026-01-26	6.13
293	2	1	2026-01-29	5.55
294	2	2	2026-02-01	2.21
295	2	1	2026-02-04	6.35
296	2	1	2026-02-07	3.68
297	2	1	2026-02-10	2.01
298	2	2	2026-02-13	3.73
299	2	2	2026-02-16	6.07
300	2	1	2026-02-19	2.02
301	2	2	2026-02-22	3.40
302	2	1	2026-02-25	3.03
303	2	1	2026-02-28	5.55
304	2	1	2026-03-03	4.61
305	2	1	2026-03-06	6.19
306	2	1	2026-03-09	5.76
307	2	2	2026-03-12	2.68
308	2	2	2026-03-15	4.62
309	2	2	2026-03-18	5.96
310	2	1	2026-03-21	5.80
311	2	1	2026-03-24	4.39
312	2	1	2026-03-27	5.11
313	2	1	2026-03-30	3.26
314	2	1	2026-04-02	6.72
315	2	1	2026-04-05	5.56
316	2	2	2026-04-08	5.58
317	2	2	2026-04-11	4.19
318	2	2	2026-04-14	6.41
319	2	2	2026-04-17	4.90
320	2	1	2026-04-20	3.33
321	2	2	2026-04-23	6.27
322	2	2	2026-04-26	2.35
323	3	2	2025-01-01	4.39
324	3	2	2025-01-04	3.34
325	3	2	2025-01-07	3.77
326	3	2	2025-01-10	5.55
327	3	1	2025-01-13	4.77
328	3	1	2025-01-16	2.51
329	3	1	2025-01-19	2.17
330	3	2	2025-01-22	2.02
331	3	2	2025-01-25	3.07
332	3	2	2025-01-28	4.98
333	3	1	2025-01-31	4.37
334	3	1	2025-02-03	4.61
335	3	2	2025-02-06	6.38
336	3	1	2025-02-09	3.38
337	3	1	2025-02-12	6.85
338	3	1	2025-02-15	2.81
339	3	2	2025-02-18	2.25
340	3	1	2025-02-21	2.43
341	3	2	2025-02-24	4.14
342	3	1	2025-02-27	2.57
343	3	2	2025-03-02	6.89
344	3	1	2025-03-05	6.23
345	3	1	2025-03-08	6.87
346	3	1	2025-03-11	2.05
347	3	1	2025-03-14	6.51
348	3	2	2025-03-17	5.41
349	3	1	2025-03-20	6.65
350	3	2	2025-03-23	3.12
351	3	2	2025-03-26	4.83
352	3	1	2025-03-29	6.50
353	3	2	2025-04-01	2.05
354	3	2	2025-04-04	2.12
355	3	2	2025-04-07	2.99
356	3	2	2025-04-10	4.66
357	3	2	2025-04-13	6.27
358	3	2	2025-04-16	4.09
359	3	1	2025-04-19	2.32
360	3	1	2025-04-22	3.29
361	3	1	2025-04-25	6.18
362	3	2	2025-04-28	3.93
363	3	2	2025-05-01	6.89
364	3	1	2025-05-04	6.32
365	3	2	2025-05-07	5.57
366	3	1	2025-05-10	6.55
367	3	2	2025-05-13	2.39
368	3	2	2025-05-16	3.30
369	3	1	2025-05-19	4.01
370	3	1	2025-05-22	4.26
371	3	2	2025-05-25	2.67
372	3	1	2025-05-28	3.58
373	3	2	2025-05-31	5.52
374	3	1	2025-06-03	3.36
375	3	2	2025-06-06	2.40
376	3	2	2025-06-09	3.40
377	3	1	2025-06-12	6.94
378	3	2	2025-06-15	3.76
379	3	1	2025-06-18	6.57
380	3	2	2025-06-21	4.17
381	3	2	2025-06-24	4.63
382	3	2	2025-06-27	2.56
383	3	1	2025-06-30	2.07
384	3	1	2025-07-03	4.15
385	3	2	2025-07-06	3.08
386	3	1	2025-07-09	5.87
387	3	1	2025-07-12	6.33
388	3	1	2025-07-15	5.89
389	3	2	2025-07-18	5.85
390	3	1	2025-07-21	3.08
391	3	2	2025-07-24	5.63
392	3	1	2025-07-27	5.52
393	3	2	2025-07-30	4.48
394	3	2	2025-08-02	2.78
395	3	2	2025-08-05	2.01
396	3	1	2025-08-08	4.64
397	3	2	2025-08-11	6.62
398	3	2	2025-08-14	4.74
399	3	1	2025-08-17	6.46
400	3	1	2025-08-20	5.72
401	3	1	2025-08-23	6.61
402	3	2	2025-08-26	3.91
403	3	1	2025-08-29	2.53
404	3	1	2025-09-01	5.21
405	3	1	2025-09-04	3.40
406	3	1	2025-09-07	4.32
407	3	2	2025-09-10	5.96
408	3	1	2025-09-13	5.24
409	3	1	2025-09-16	2.43
410	3	2	2025-09-19	2.13
411	3	2	2025-09-22	6.10
412	3	1	2025-09-25	6.32
413	3	2	2025-09-28	2.80
414	3	1	2025-10-01	6.66
415	3	1	2025-10-04	4.19
416	3	2	2025-10-07	4.01
417	3	1	2025-10-10	3.49
418	3	2	2025-10-13	3.35
419	3	1	2025-10-16	5.66
420	3	2	2025-10-19	6.15
421	3	1	2025-10-22	3.19
422	3	2	2025-10-25	5.01
423	3	1	2025-10-28	2.57
424	3	1	2025-10-31	6.26
425	3	2	2025-11-03	2.63
426	3	2	2025-11-06	6.63
427	3	2	2025-11-09	6.24
428	3	1	2025-11-12	5.90
429	3	2	2025-11-15	2.32
430	3	1	2025-11-18	4.39
431	3	1	2025-11-21	3.72
432	3	1	2025-11-24	6.85
433	3	1	2025-11-27	4.59
434	3	2	2025-11-30	2.92
435	3	2	2025-12-03	6.60
436	3	2	2025-12-06	5.64
437	3	2	2025-12-09	5.05
438	3	2	2025-12-12	2.83
439	3	2	2025-12-15	5.79
440	3	1	2025-12-18	5.02
441	3	1	2025-12-21	6.20
442	3	2	2025-12-24	5.13
443	3	2	2025-12-27	6.29
444	3	2	2025-12-30	2.29
445	3	1	2026-01-02	3.53
446	3	1	2026-01-05	4.30
447	3	1	2026-01-08	6.19
448	3	1	2026-01-11	5.40
449	3	2	2026-01-14	3.36
450	3	1	2026-01-17	3.93
451	3	2	2026-01-20	6.73
452	3	2	2026-01-23	5.39
453	3	2	2026-01-26	2.54
454	3	2	2026-01-29	3.03
455	3	2	2026-02-01	3.06
456	3	2	2026-02-04	4.26
457	3	2	2026-02-07	6.19
458	3	2	2026-02-10	4.45
459	3	1	2026-02-13	2.40
460	3	1	2026-02-16	2.94
461	3	1	2026-02-19	3.37
462	3	2	2026-02-22	2.36
463	3	1	2026-02-25	6.47
464	3	2	2026-02-28	3.53
465	3	1	2026-03-03	6.32
466	3	1	2026-03-06	5.44
467	3	2	2026-03-09	3.93
468	3	2	2026-03-12	3.20
469	3	1	2026-03-15	5.76
470	3	2	2026-03-18	3.24
471	3	1	2026-03-21	2.81
472	3	2	2026-03-24	7.00
473	3	2	2026-03-27	4.20
474	3	2	2026-03-30	5.83
475	3	2	2026-04-02	5.97
476	3	2	2026-04-05	4.08
477	3	2	2026-04-08	4.92
478	3	1	2026-04-11	6.16
479	3	2	2026-04-14	4.57
480	3	2	2026-04-17	6.68
481	3	1	2026-04-20	3.38
482	3	2	2026-04-23	6.44
483	3	1	2026-04-26	6.70
484	4	2	2025-01-01	4.70
485	4	2	2025-01-04	3.61
486	4	2	2025-01-07	5.52
487	4	1	2025-01-10	5.57
488	4	2	2025-01-13	2.29
489	4	1	2025-01-16	6.25
490	4	1	2025-01-19	4.62
491	4	2	2025-01-22	5.26
492	4	1	2025-01-25	4.75
493	4	2	2025-01-28	3.96
494	4	1	2025-01-31	4.76
495	4	2	2025-02-03	4.59
496	4	2	2025-02-06	5.52
497	4	2	2025-02-09	6.37
498	4	1	2025-02-12	6.94
499	4	1	2025-02-15	6.93
500	4	2	2025-02-18	6.11
501	4	2	2025-02-21	5.58
502	4	1	2025-02-24	5.12
503	4	2	2025-02-27	2.03
504	4	1	2025-03-02	4.46
505	4	1	2025-03-05	6.47
506	4	1	2025-03-08	6.61
507	4	1	2025-03-11	2.96
508	4	1	2025-03-14	4.76
509	4	1	2025-03-17	5.71
510	4	2	2025-03-20	2.30
511	4	2	2025-03-23	4.59
512	4	1	2025-03-26	4.73
513	4	2	2025-03-29	2.32
514	4	2	2025-04-01	2.35
515	4	2	2025-04-04	6.67
516	4	1	2025-04-07	2.48
517	4	1	2025-04-10	4.31
518	4	2	2025-04-13	3.09
519	4	1	2025-04-16	6.76
520	4	2	2025-04-19	6.30
521	4	1	2025-04-22	2.25
522	4	1	2025-04-25	3.27
523	4	2	2025-04-28	5.94
524	4	1	2025-05-01	3.13
525	4	1	2025-05-04	6.88
526	4	2	2025-05-07	6.91
527	4	1	2025-05-10	4.89
528	4	1	2025-05-13	3.56
529	4	1	2025-05-16	5.97
530	4	1	2025-05-19	2.72
531	4	1	2025-05-22	5.02
532	4	2	2025-05-25	4.44
533	4	2	2025-05-28	2.56
534	4	1	2025-05-31	5.69
535	4	1	2025-06-03	4.38
536	4	2	2025-06-06	5.85
537	4	2	2025-06-09	6.07
538	4	1	2025-06-12	5.36
539	4	1	2025-06-15	3.85
540	4	2	2025-06-18	6.14
541	4	2	2025-06-21	6.56
542	4	2	2025-06-24	3.30
543	4	1	2025-06-27	4.23
544	4	1	2025-06-30	5.54
545	4	1	2025-07-03	4.18
546	4	2	2025-07-06	3.82
547	4	2	2025-07-09	3.57
548	4	2	2025-07-12	6.33
549	4	1	2025-07-15	3.84
550	4	1	2025-07-18	5.04
551	4	2	2025-07-21	5.00
552	4	2	2025-07-24	6.67
553	4	1	2025-07-27	4.53
554	4	1	2025-07-30	4.55
555	4	2	2025-08-02	5.35
556	4	2	2025-08-05	4.52
557	4	2	2025-08-08	2.66
558	4	1	2025-08-11	4.53
559	4	1	2025-08-14	5.39
560	4	2	2025-08-17	4.68
561	4	2	2025-08-20	3.14
562	4	1	2025-08-23	5.93
563	4	1	2025-08-26	4.25
564	4	1	2025-08-29	6.84
565	4	1	2025-09-01	3.04
566	4	2	2025-09-04	5.85
567	4	1	2025-09-07	2.56
568	4	1	2025-09-10	5.67
569	4	1	2025-09-13	4.48
570	4	1	2025-09-16	2.27
571	4	2	2025-09-19	6.39
572	4	1	2025-09-22	2.33
573	4	1	2025-09-25	4.12
574	4	1	2025-09-28	3.88
575	4	2	2025-10-01	6.01
576	4	1	2025-10-04	2.77
577	4	2	2025-10-07	5.44
578	4	2	2025-10-10	5.74
579	4	2	2025-10-13	5.81
580	4	1	2025-10-16	4.18
581	4	2	2025-10-19	3.37
582	4	2	2025-10-22	3.43
583	4	2	2025-10-25	6.18
584	4	2	2025-10-28	2.42
585	4	2	2025-10-31	5.31
586	4	2	2025-11-03	6.04
587	4	2	2025-11-06	3.17
588	4	1	2025-11-09	2.33
589	4	2	2025-11-12	6.64
590	4	1	2025-11-15	6.31
591	4	1	2025-11-18	5.06
592	4	1	2025-11-21	6.41
593	4	2	2025-11-24	2.45
594	4	2	2025-11-27	3.30
595	4	2	2025-11-30	3.67
596	4	2	2025-12-03	6.62
597	4	2	2025-12-06	6.36
598	4	2	2025-12-09	3.46
599	4	1	2025-12-12	5.16
600	4	2	2025-12-15	5.07
601	4	2	2025-12-18	2.53
602	4	2	2025-12-21	4.28
603	4	1	2025-12-24	3.31
604	4	1	2025-12-27	3.75
605	4	1	2025-12-30	3.59
606	4	1	2026-01-02	3.92
607	4	2	2026-01-05	6.77
608	4	2	2026-01-08	3.76
609	4	1	2026-01-11	5.03
610	4	2	2026-01-14	4.08
611	4	2	2026-01-17	5.79
612	4	1	2026-01-20	5.05
613	4	1	2026-01-23	2.43
614	4	2	2026-01-26	5.88
615	4	2	2026-01-29	2.36
616	4	2	2026-02-01	3.19
617	4	2	2026-02-04	2.40
618	4	2	2026-02-07	4.39
619	4	1	2026-02-10	4.71
620	4	1	2026-02-13	3.66
621	4	1	2026-02-16	4.82
622	4	1	2026-02-19	2.49
623	4	2	2026-02-22	4.15
624	4	1	2026-02-25	6.84
625	4	2	2026-02-28	6.89
626	4	1	2026-03-03	5.65
627	4	2	2026-03-06	3.66
628	4	2	2026-03-09	4.01
629	4	1	2026-03-12	2.74
630	4	1	2026-03-15	2.89
631	4	2	2026-03-18	4.72
632	4	2	2026-03-21	2.66
633	4	1	2026-03-24	3.35
634	4	2	2026-03-27	4.65
635	4	1	2026-03-30	3.45
636	4	2	2026-04-02	4.04
637	4	1	2026-04-05	3.91
638	4	2	2026-04-08	2.31
639	4	1	2026-04-11	6.65
640	4	2	2026-04-14	6.33
641	4	2	2026-04-17	3.21
642	4	2	2026-04-20	5.53
643	4	2	2026-04-23	5.70
644	4	2	2026-04-26	3.84
645	5	2	2025-01-01	6.37
646	5	1	2025-01-04	3.58
647	5	1	2025-01-07	5.69
648	5	1	2025-01-10	4.63
649	5	1	2025-01-13	4.93
650	5	2	2025-01-16	3.58
651	5	1	2025-01-19	3.08
652	5	1	2025-01-22	4.12
653	5	1	2025-01-25	5.56
654	5	1	2025-01-28	6.37
655	5	1	2025-01-31	3.25
656	5	1	2025-02-03	3.97
657	5	2	2025-02-06	4.05
658	5	2	2025-02-09	4.27
659	5	2	2025-02-12	4.34
660	5	2	2025-02-15	3.66
661	5	2	2025-02-18	2.13
662	5	1	2025-02-21	4.97
663	5	1	2025-02-24	5.86
664	5	1	2025-02-27	4.13
665	5	1	2025-03-02	2.29
666	5	2	2025-03-05	3.92
667	5	2	2025-03-08	2.39
668	5	1	2025-03-11	5.51
669	5	2	2025-03-14	3.44
670	5	2	2025-03-17	5.34
671	5	1	2025-03-20	5.26
672	5	1	2025-03-23	4.27
673	5	2	2025-03-26	4.19
674	5	1	2025-03-29	3.53
675	5	1	2025-04-01	4.28
676	5	1	2025-04-04	5.46
677	5	2	2025-04-07	2.55
678	5	2	2025-04-10	5.39
679	5	2	2025-04-13	5.79
680	5	2	2025-04-16	3.27
681	5	2	2025-04-19	5.67
682	5	1	2025-04-22	2.22
683	5	1	2025-04-25	2.49
684	5	2	2025-04-28	3.00
685	5	2	2025-05-01	6.06
686	5	2	2025-05-04	2.47
687	5	2	2025-05-07	6.33
688	5	2	2025-05-10	5.37
689	5	2	2025-05-13	3.80
690	5	2	2025-05-16	4.52
691	5	2	2025-05-19	6.83
692	5	1	2025-05-22	2.77
693	5	1	2025-05-25	5.72
694	5	1	2025-05-28	4.63
695	5	2	2025-05-31	3.66
696	5	2	2025-06-03	3.14
697	5	1	2025-06-06	4.38
698	5	1	2025-06-09	4.01
699	5	1	2025-06-12	4.73
700	5	1	2025-06-15	4.40
701	5	2	2025-06-18	5.23
702	5	2	2025-06-21	3.84
703	5	2	2025-06-24	5.38
704	5	2	2025-06-27	2.45
705	5	2	2025-06-30	6.03
706	5	1	2025-07-03	3.42
707	5	2	2025-07-06	2.71
708	5	1	2025-07-09	4.17
709	5	2	2025-07-12	2.11
710	5	1	2025-07-15	2.30
711	5	2	2025-07-18	3.00
712	5	2	2025-07-21	3.70
713	5	2	2025-07-24	5.42
714	5	1	2025-07-27	5.00
715	5	2	2025-07-30	4.05
716	5	1	2025-08-02	5.87
717	5	2	2025-08-05	4.10
718	5	1	2025-08-08	6.78
719	5	2	2025-08-11	5.18
720	5	1	2025-08-14	4.22
721	5	2	2025-08-17	2.16
722	5	2	2025-08-20	3.47
723	5	2	2025-08-23	2.79
724	5	1	2025-08-26	5.57
725	5	2	2025-08-29	5.13
726	5	2	2025-09-01	3.12
727	5	1	2025-09-04	4.51
728	5	1	2025-09-07	6.68
729	5	2	2025-09-10	6.14
730	5	1	2025-09-13	4.80
731	5	1	2025-09-16	5.57
732	5	2	2025-09-19	6.56
733	5	2	2025-09-22	3.09
734	5	1	2025-09-25	4.18
735	5	1	2025-09-28	6.58
736	5	2	2025-10-01	4.96
737	5	1	2025-10-04	4.21
738	5	2	2025-10-07	6.48
739	5	2	2025-10-10	2.67
740	5	1	2025-10-13	6.96
741	5	2	2025-10-16	4.45
742	5	2	2025-10-19	6.49
743	5	1	2025-10-22	4.79
744	5	2	2025-10-25	5.59
745	5	2	2025-10-28	3.63
746	5	1	2025-10-31	2.39
747	5	2	2025-11-03	4.34
748	5	2	2025-11-06	6.38
749	5	2	2025-11-09	2.22
750	5	1	2025-11-12	4.43
751	5	1	2025-11-15	2.92
752	5	2	2025-11-18	5.32
753	5	2	2025-11-21	4.87
754	5	1	2025-11-24	5.79
755	5	1	2025-11-27	4.23
756	5	1	2025-11-30	2.42
757	5	2	2025-12-03	2.38
758	5	1	2025-12-06	5.96
759	5	2	2025-12-09	3.85
760	5	2	2025-12-12	3.80
761	5	1	2025-12-15	3.99
762	5	2	2025-12-18	3.92
763	5	1	2025-12-21	3.22
764	5	2	2025-12-24	2.19
765	5	1	2025-12-27	6.25
766	5	1	2025-12-30	5.89
767	5	1	2026-01-02	5.31
768	5	1	2026-01-05	5.23
769	5	1	2026-01-08	5.99
770	5	1	2026-01-11	2.86
771	5	2	2026-01-14	2.52
772	5	2	2026-01-17	6.63
773	5	2	2026-01-20	6.72
774	5	1	2026-01-23	5.62
775	5	1	2026-01-26	6.34
776	5	1	2026-01-29	5.24
777	5	1	2026-02-01	5.90
778	5	2	2026-02-04	5.81
779	5	2	2026-02-07	2.64
780	5	1	2026-02-10	3.57
781	5	1	2026-02-13	6.14
782	5	1	2026-02-16	4.28
783	5	1	2026-02-19	3.08
784	5	2	2026-02-22	5.92
785	5	1	2026-02-25	5.86
786	5	1	2026-02-28	3.08
787	5	2	2026-03-03	4.29
788	5	2	2026-03-06	4.77
789	5	2	2026-03-09	2.12
790	5	1	2026-03-12	5.80
791	5	1	2026-03-15	5.78
792	5	2	2026-03-18	3.29
793	5	1	2026-03-21	2.29
794	5	2	2026-03-24	3.80
795	5	2	2026-03-27	6.65
796	5	2	2026-03-30	2.83
797	5	1	2026-04-02	4.31
798	5	1	2026-04-05	3.89
799	5	2	2026-04-08	2.28
800	5	1	2026-04-11	3.22
801	5	1	2026-04-14	4.91
802	5	1	2026-04-17	2.76
803	5	1	2026-04-20	4.72
804	5	2	2026-04-23	2.55
805	5	2	2026-04-26	5.79
806	6	2	2025-01-01	5.64
807	6	1	2025-01-04	3.00
808	6	2	2025-01-07	3.24
809	6	2	2025-01-10	2.99
810	6	1	2025-01-13	6.07
811	6	2	2025-01-16	5.00
812	6	1	2025-01-19	4.05
813	6	2	2025-01-22	2.39
814	6	2	2025-01-25	3.09
815	6	1	2025-01-28	3.18
816	6	2	2025-01-31	6.88
817	6	2	2025-02-03	6.84
818	6	1	2025-02-06	3.66
819	6	2	2025-02-09	5.04
820	6	2	2025-02-12	3.09
821	6	1	2025-02-15	3.61
822	6	2	2025-02-18	3.45
823	6	1	2025-02-21	4.12
824	6	1	2025-02-24	4.27
825	6	1	2025-02-27	2.28
826	6	2	2025-03-02	6.98
827	6	1	2025-03-05	4.33
828	6	2	2025-03-08	6.93
829	6	1	2025-03-11	5.06
830	6	2	2025-03-14	5.74
831	6	1	2025-03-17	4.36
832	6	2	2025-03-20	5.66
833	6	1	2025-03-23	2.81
834	6	2	2025-03-26	3.83
835	6	1	2025-03-29	5.40
836	6	2	2025-04-01	6.49
837	6	1	2025-04-04	5.32
838	6	2	2025-04-07	5.61
839	6	2	2025-04-10	2.30
840	6	1	2025-04-13	2.58
841	6	1	2025-04-16	5.01
842	6	2	2025-04-19	6.41
843	6	2	2025-04-22	2.80
844	6	2	2025-04-25	4.59
845	6	1	2025-04-28	5.46
846	6	1	2025-05-01	5.50
847	6	2	2025-05-04	2.57
848	6	2	2025-05-07	2.38
849	6	1	2025-05-10	5.52
850	6	1	2025-05-13	6.10
851	6	1	2025-05-16	4.47
852	6	1	2025-05-19	4.93
853	6	2	2025-05-22	3.21
854	6	2	2025-05-25	5.12
855	6	1	2025-05-28	5.26
856	6	1	2025-05-31	4.60
857	6	1	2025-06-03	5.02
858	6	2	2025-06-06	4.87
859	6	1	2025-06-09	5.15
860	6	1	2025-06-12	3.40
861	6	1	2025-06-15	3.12
862	6	1	2025-06-18	4.62
863	6	1	2025-06-21	5.88
864	6	2	2025-06-24	6.25
865	6	1	2025-06-27	2.99
866	6	2	2025-06-30	6.39
867	6	1	2025-07-03	6.52
868	6	1	2025-07-06	4.29
869	6	2	2025-07-09	3.02
870	6	2	2025-07-12	2.42
871	6	2	2025-07-15	6.00
872	6	1	2025-07-18	2.17
873	6	1	2025-07-21	3.26
874	6	2	2025-07-24	2.30
875	6	2	2025-07-27	4.01
876	6	2	2025-07-30	6.21
877	6	2	2025-08-02	6.38
878	6	1	2025-08-05	5.74
879	6	2	2025-08-08	3.82
880	6	1	2025-08-11	6.11
881	6	2	2025-08-14	5.19
882	6	2	2025-08-17	4.49
883	6	2	2025-08-20	6.83
884	6	1	2025-08-23	2.21
885	6	2	2025-08-26	5.38
886	6	1	2025-08-29	2.91
887	6	2	2025-09-01	4.36
888	6	1	2025-09-04	6.82
889	6	2	2025-09-07	5.48
890	6	1	2025-09-10	2.46
891	6	2	2025-09-13	4.18
892	6	2	2025-09-16	2.49
893	6	2	2025-09-19	6.85
894	6	1	2025-09-22	6.78
895	6	2	2025-09-25	3.88
896	6	1	2025-09-28	4.71
897	6	1	2025-10-01	4.74
898	6	1	2025-10-04	6.47
899	6	2	2025-10-07	2.23
900	6	2	2025-10-10	3.28
901	6	2	2025-10-13	4.03
902	6	1	2025-10-16	2.01
903	6	2	2025-10-19	2.77
904	6	1	2025-10-22	2.35
905	6	1	2025-10-25	2.06
906	6	2	2025-10-28	5.66
907	6	2	2025-10-31	2.89
908	6	1	2025-11-03	5.34
909	6	2	2025-11-06	6.76
910	6	1	2025-11-09	2.75
911	6	1	2025-11-12	6.58
912	6	2	2025-11-15	3.00
913	6	2	2025-11-18	5.58
914	6	2	2025-11-21	4.19
915	6	2	2025-11-24	6.26
916	6	2	2025-11-27	3.14
917	6	2	2025-11-30	3.95
918	6	1	2025-12-03	6.31
919	6	1	2025-12-06	5.79
920	6	1	2025-12-09	3.38
921	6	1	2025-12-12	5.30
922	6	2	2025-12-15	3.70
923	6	2	2025-12-18	4.02
924	6	1	2025-12-21	6.43
925	6	2	2025-12-24	6.54
926	6	1	2025-12-27	2.95
927	6	1	2025-12-30	3.43
928	6	2	2026-01-02	2.23
929	6	2	2026-01-05	6.57
930	6	2	2026-01-08	6.31
931	6	1	2026-01-11	2.54
932	6	2	2026-01-14	5.49
933	6	2	2026-01-17	4.52
934	6	2	2026-01-20	2.13
935	6	1	2026-01-23	6.44
936	6	1	2026-01-26	2.67
937	6	1	2026-01-29	6.88
938	6	2	2026-02-01	6.70
939	6	2	2026-02-04	2.87
940	6	1	2026-02-07	5.03
941	6	1	2026-02-10	2.63
942	6	2	2026-02-13	4.70
943	6	1	2026-02-16	6.49
944	6	2	2026-02-19	4.55
945	6	2	2026-02-22	2.45
946	6	2	2026-02-25	6.00
947	6	1	2026-02-28	2.27
948	6	2	2026-03-03	6.87
949	6	1	2026-03-06	2.67
950	6	1	2026-03-09	5.38
951	6	2	2026-03-12	6.06
952	6	1	2026-03-15	6.32
953	6	2	2026-03-18	3.73
954	6	1	2026-03-21	6.94
955	6	2	2026-03-24	3.49
956	6	1	2026-03-27	4.33
957	6	1	2026-03-30	5.09
958	6	2	2026-04-02	4.98
959	6	2	2026-04-05	6.70
960	6	2	2026-04-08	2.08
961	6	2	2026-04-11	3.73
962	6	1	2026-04-14	5.16
963	6	1	2026-04-17	3.45
964	6	2	2026-04-20	3.31
965	6	2	2026-04-23	3.32
966	6	1	2026-04-26	4.45
967	7	2	2025-01-01	5.95
968	7	2	2025-01-04	4.05
969	7	1	2025-01-07	3.54
970	7	1	2025-01-10	5.75
971	7	1	2025-01-13	6.25
972	7	1	2025-01-16	4.09
973	7	1	2025-01-19	5.00
974	7	2	2025-01-22	6.15
975	7	1	2025-01-25	5.03
976	7	2	2025-01-28	4.45
977	7	2	2025-01-31	4.31
978	7	1	2025-02-03	2.35
979	7	2	2025-02-06	3.52
980	7	2	2025-02-09	4.95
981	7	1	2025-02-12	5.94
982	7	1	2025-02-15	3.22
983	7	1	2025-02-18	3.79
984	7	1	2025-02-21	2.97
985	7	1	2025-02-24	6.68
986	7	1	2025-02-27	6.57
987	7	2	2025-03-02	2.72
988	7	1	2025-03-05	3.65
989	7	2	2025-03-08	3.14
990	7	1	2025-03-11	2.50
991	7	2	2025-03-14	4.27
992	7	1	2025-03-17	4.00
993	7	2	2025-03-20	5.56
994	7	2	2025-03-23	2.35
995	7	2	2025-03-26	6.60
996	7	1	2025-03-29	6.39
997	7	1	2025-04-01	4.18
998	7	1	2025-04-04	4.59
999	7	2	2025-04-07	5.78
1000	7	2	2025-04-10	2.74
1001	7	2	2025-04-13	2.01
1002	7	1	2025-04-16	4.39
1003	7	1	2025-04-19	4.94
1004	7	1	2025-04-22	2.97
1005	7	2	2025-04-25	3.52
1006	7	2	2025-04-28	2.83
1007	7	1	2025-05-01	4.39
1008	7	2	2025-05-04	6.64
1009	7	2	2025-05-07	4.15
1010	7	2	2025-05-10	3.10
1011	7	1	2025-05-13	6.04
1012	7	1	2025-05-16	3.56
1013	7	2	2025-05-19	2.80
1014	7	1	2025-05-22	3.84
1015	7	2	2025-05-25	5.63
1016	7	2	2025-05-28	2.99
1017	7	2	2025-05-31	4.08
1018	7	1	2025-06-03	6.47
1019	7	2	2025-06-06	6.62
1020	7	1	2025-06-09	6.00
1021	7	2	2025-06-12	4.50
1022	7	2	2025-06-15	2.07
1023	7	1	2025-06-18	2.59
1024	7	1	2025-06-21	3.28
1025	7	2	2025-06-24	3.43
1026	7	2	2025-06-27	2.03
1027	7	2	2025-06-30	4.61
1028	7	1	2025-07-03	2.96
1029	7	1	2025-07-06	2.67
1030	7	2	2025-07-09	4.68
1031	7	2	2025-07-12	6.59
1032	7	2	2025-07-15	4.12
1033	7	1	2025-07-18	3.88
1034	7	2	2025-07-21	4.94
1035	7	2	2025-07-24	6.21
1036	7	1	2025-07-27	4.52
1037	7	1	2025-07-30	2.47
1038	7	1	2025-08-02	6.46
1039	7	1	2025-08-05	4.02
1040	7	2	2025-08-08	2.98
1041	7	2	2025-08-11	3.70
1042	7	2	2025-08-14	5.90
1043	7	1	2025-08-17	2.15
1044	7	2	2025-08-20	6.50
1045	7	1	2025-08-23	3.06
1046	7	1	2025-08-26	6.54
1047	7	2	2025-08-29	3.37
1048	7	1	2025-09-01	6.27
1049	7	1	2025-09-04	6.51
1050	7	2	2025-09-07	5.90
1051	7	1	2025-09-10	3.99
1052	7	1	2025-09-13	2.12
1053	7	2	2025-09-16	5.32
1054	7	1	2025-09-19	2.32
1055	7	1	2025-09-22	2.03
1056	7	1	2025-09-25	3.88
1057	7	1	2025-09-28	4.30
1058	7	2	2025-10-01	5.20
1059	7	2	2025-10-04	5.67
1060	7	2	2025-10-07	5.01
1061	7	1	2025-10-10	5.88
1062	7	2	2025-10-13	3.47
1063	7	1	2025-10-16	6.88
1064	7	1	2025-10-19	6.06
1065	7	2	2025-10-22	3.03
1066	7	1	2025-10-25	4.34
1067	7	1	2025-10-28	2.78
1068	7	1	2025-10-31	2.98
1069	7	2	2025-11-03	6.73
1070	7	1	2025-11-06	2.33
1071	7	2	2025-11-09	3.68
1072	7	2	2025-11-12	2.46
1073	7	1	2025-11-15	6.07
1074	7	2	2025-11-18	6.71
1075	7	1	2025-11-21	5.52
1076	7	1	2025-11-24	4.22
1077	7	1	2025-11-27	6.13
1078	7	1	2025-11-30	4.89
1079	7	1	2025-12-03	5.71
1080	7	1	2025-12-06	4.60
1081	7	2	2025-12-09	6.35
1082	7	2	2025-12-12	4.79
1083	7	1	2025-12-15	2.83
1084	7	1	2025-12-18	2.11
1085	7	2	2025-12-21	2.21
1086	7	1	2025-12-24	2.34
1087	7	2	2025-12-27	5.93
1088	7	2	2025-12-30	4.27
1089	7	1	2026-01-02	3.57
1090	7	2	2026-01-05	6.73
1091	7	1	2026-01-08	3.19
1092	7	2	2026-01-11	6.91
1093	7	1	2026-01-14	2.14
1094	7	2	2026-01-17	5.12
1095	7	1	2026-01-20	6.33
1096	7	2	2026-01-23	6.73
1097	7	1	2026-01-26	4.92
1098	7	1	2026-01-29	5.97
1099	7	2	2026-02-01	3.49
1100	7	1	2026-02-04	3.41
1101	7	2	2026-02-07	5.22
1102	7	1	2026-02-10	2.09
1103	7	1	2026-02-13	2.78
1104	7	1	2026-02-16	2.96
1105	7	2	2026-02-19	4.87
1106	7	1	2026-02-22	6.43
1107	7	2	2026-02-25	6.14
1108	7	2	2026-02-28	2.18
1109	7	1	2026-03-03	3.16
1110	7	2	2026-03-06	3.02
1111	7	2	2026-03-09	5.48
1112	7	2	2026-03-12	4.43
1113	7	1	2026-03-15	4.16
1114	7	1	2026-03-18	6.24
1115	7	2	2026-03-21	2.02
1116	7	2	2026-03-24	3.46
1117	7	2	2026-03-27	5.57
1118	7	1	2026-03-30	4.67
1119	7	2	2026-04-02	6.86
1120	7	1	2026-04-05	3.81
1121	7	1	2026-04-08	4.76
1122	7	1	2026-04-11	5.65
1123	7	1	2026-04-14	2.01
1124	7	1	2026-04-17	2.48
1125	7	2	2026-04-20	6.48
1126	7	1	2026-04-23	3.89
1127	7	2	2026-04-26	5.59
1128	8	1	2025-01-01	6.61
1129	8	2	2025-01-04	2.13
1130	8	1	2025-01-07	2.36
1131	8	1	2025-01-10	4.91
1132	8	1	2025-01-13	3.06
1133	8	1	2025-01-16	4.95
1134	8	1	2025-01-19	2.95
1135	8	1	2025-01-22	6.23
1136	8	1	2025-01-25	3.29
1137	8	2	2025-01-28	6.80
1138	8	2	2025-01-31	3.33
1139	8	1	2025-02-03	3.95
1140	8	1	2025-02-06	5.76
1141	8	2	2025-02-09	3.03
1142	8	1	2025-02-12	4.98
1143	8	2	2025-02-15	3.15
1144	8	1	2025-02-18	4.04
1145	8	2	2025-02-21	5.99
1146	8	1	2025-02-24	2.84
1147	8	2	2025-02-27	4.66
1148	8	2	2025-03-02	6.91
1149	8	1	2025-03-05	5.68
1150	8	2	2025-03-08	6.54
1151	8	2	2025-03-11	2.08
1152	8	1	2025-03-14	4.98
1153	8	2	2025-03-17	6.60
1154	8	2	2025-03-20	2.64
1155	8	1	2025-03-23	5.68
1156	8	2	2025-03-26	5.21
1157	8	2	2025-03-29	6.74
1158	8	2	2025-04-01	4.72
1159	8	1	2025-04-04	5.73
1160	8	2	2025-04-07	4.51
1161	8	1	2025-04-10	6.21
1162	8	1	2025-04-13	4.96
1163	8	2	2025-04-16	2.18
1164	8	1	2025-04-19	6.54
1165	8	2	2025-04-22	5.97
1166	8	1	2025-04-25	3.81
1167	8	1	2025-04-28	2.43
1168	8	1	2025-05-01	6.28
1169	8	2	2025-05-04	3.33
1170	8	2	2025-05-07	3.51
1171	8	2	2025-05-10	6.99
1172	8	2	2025-05-13	5.13
1173	8	1	2025-05-16	6.75
1174	8	2	2025-05-19	2.90
1175	8	2	2025-05-22	2.14
1176	8	1	2025-05-25	3.61
1177	8	2	2025-05-28	3.38
1178	8	2	2025-05-31	4.46
1179	8	2	2025-06-03	5.37
1180	8	2	2025-06-06	4.93
1181	8	1	2025-06-09	3.78
1182	8	1	2025-06-12	3.77
1183	8	2	2025-06-15	5.22
1184	8	1	2025-06-18	6.71
1185	8	2	2025-06-21	5.88
1186	8	1	2025-06-24	5.62
1187	8	1	2025-06-27	2.89
1188	8	2	2025-06-30	4.65
1189	8	2	2025-07-03	4.78
1190	8	1	2025-07-06	5.09
1191	8	1	2025-07-09	3.02
1192	8	1	2025-07-12	5.27
1193	8	1	2025-07-15	5.98
1194	8	1	2025-07-18	2.92
1195	8	1	2025-07-21	6.95
1196	8	2	2025-07-24	4.10
1197	8	1	2025-07-27	6.87
1198	8	2	2025-07-30	6.65
1199	8	2	2025-08-02	3.57
1200	8	1	2025-08-05	3.70
1201	8	2	2025-08-08	2.20
1202	8	2	2025-08-11	4.97
1203	8	2	2025-08-14	6.38
1204	8	2	2025-08-17	2.60
1205	8	2	2025-08-20	2.03
1206	8	2	2025-08-23	6.73
1207	8	2	2025-08-26	4.99
1208	8	2	2025-08-29	6.49
1209	8	2	2025-09-01	2.38
1210	8	2	2025-09-04	3.74
1211	8	1	2025-09-07	5.49
1212	8	1	2025-09-10	5.58
1213	8	1	2025-09-13	2.88
1214	8	2	2025-09-16	6.74
1215	8	2	2025-09-19	5.21
1216	8	2	2025-09-22	3.80
1217	8	1	2025-09-25	6.36
1218	8	2	2025-09-28	4.66
1219	8	1	2025-10-01	6.47
1220	8	2	2025-10-04	4.82
1221	8	2	2025-10-07	4.43
1222	8	1	2025-10-10	2.49
1223	8	2	2025-10-13	4.15
1224	8	1	2025-10-16	6.43
1225	8	2	2025-10-19	2.61
1226	8	1	2025-10-22	3.96
1227	8	2	2025-10-25	3.02
1228	8	2	2025-10-28	6.75
1229	8	2	2025-10-31	5.81
1230	8	2	2025-11-03	6.78
1231	8	2	2025-11-06	5.24
1232	8	2	2025-11-09	3.60
1233	8	1	2025-11-12	4.59
1234	8	2	2025-11-15	4.94
1235	8	1	2025-11-18	4.77
1236	8	2	2025-11-21	3.45
1237	8	2	2025-11-24	6.61
1238	8	1	2025-11-27	2.83
1239	8	1	2025-11-30	3.74
1240	8	1	2025-12-03	4.71
1241	8	2	2025-12-06	3.97
1242	8	1	2025-12-09	5.08
1243	8	2	2025-12-12	2.20
1244	8	2	2025-12-15	5.20
1245	8	1	2025-12-18	5.94
1246	8	1	2025-12-21	2.89
1247	8	1	2025-12-24	4.93
1248	8	2	2025-12-27	5.00
1249	8	1	2025-12-30	5.96
1250	8	2	2026-01-02	4.02
1251	8	2	2026-01-05	2.22
1252	8	2	2026-01-08	2.19
1253	8	1	2026-01-11	3.53
1254	8	1	2026-01-14	2.65
1255	8	2	2026-01-17	3.03
1256	8	2	2026-01-20	2.51
1257	8	1	2026-01-23	6.43
1258	8	2	2026-01-26	4.84
1259	8	2	2026-01-29	3.08
1260	8	1	2026-02-01	3.61
1261	8	2	2026-02-04	6.46
1262	8	1	2026-02-07	3.90
1263	8	2	2026-02-10	5.22
1264	8	2	2026-02-13	4.95
1265	8	1	2026-02-16	4.06
1266	8	1	2026-02-19	2.51
1267	8	1	2026-02-22	5.49
1268	8	1	2026-02-25	5.05
1269	8	2	2026-02-28	5.78
1270	8	2	2026-03-03	5.41
1271	8	1	2026-03-06	3.55
1272	8	2	2026-03-09	5.64
1273	8	2	2026-03-12	6.84
1274	8	1	2026-03-15	4.90
1275	8	1	2026-03-18	5.01
1276	8	1	2026-03-21	2.23
1277	8	1	2026-03-24	3.47
1278	8	2	2026-03-27	2.42
1279	8	1	2026-03-30	3.81
1280	8	1	2026-04-02	3.22
1281	8	1	2026-04-05	3.37
1282	8	1	2026-04-08	5.33
1283	8	2	2026-04-11	4.03
1284	8	2	2026-04-14	6.95
1285	8	1	2026-04-17	2.45
1286	8	1	2026-04-20	5.23
1287	8	2	2026-04-23	6.37
1288	8	2	2026-04-26	6.20
1289	9	2	2025-01-01	5.55
1290	9	2	2025-01-04	4.34
1291	9	2	2025-01-07	2.31
1292	9	2	2025-01-10	4.91
1293	9	1	2025-01-13	5.62
1294	9	1	2025-01-16	5.41
1295	9	1	2025-01-19	2.04
1296	9	2	2025-01-22	3.77
1297	9	2	2025-01-25	5.90
1298	9	1	2025-01-28	5.35
1299	9	2	2025-01-31	2.35
1300	9	1	2025-02-03	6.07
1301	9	2	2025-02-06	6.82
1302	9	1	2025-02-09	4.14
1303	9	1	2025-02-12	5.23
1304	9	2	2025-02-15	6.24
1305	9	2	2025-02-18	4.24
1306	9	1	2025-02-21	6.46
1307	9	1	2025-02-24	5.62
1308	9	1	2025-02-27	2.94
1309	9	1	2025-03-02	3.18
1310	9	2	2025-03-05	6.54
1311	9	1	2025-03-08	2.16
1312	9	2	2025-03-11	5.69
1313	9	1	2025-03-14	5.47
1314	9	1	2025-03-17	6.57
1315	9	2	2025-03-20	3.75
1316	9	2	2025-03-23	4.22
1317	9	2	2025-03-26	2.08
1318	9	1	2025-03-29	6.06
1319	9	2	2025-04-01	5.31
1320	9	1	2025-04-04	6.88
1321	9	2	2025-04-07	5.54
1322	9	2	2025-04-10	3.51
1323	9	1	2025-04-13	3.94
1324	9	2	2025-04-16	5.58
1325	9	2	2025-04-19	4.33
1326	9	1	2025-04-22	2.77
1327	9	2	2025-04-25	4.69
1328	9	1	2025-04-28	5.48
1329	9	2	2025-05-01	5.86
1330	9	2	2025-05-04	2.35
1331	9	1	2025-05-07	3.38
1332	9	1	2025-05-10	5.17
1333	9	1	2025-05-13	6.35
1334	9	1	2025-05-16	5.75
1335	9	2	2025-05-19	3.15
1336	9	1	2025-05-22	3.36
1337	9	2	2025-05-25	3.69
1338	9	2	2025-05-28	4.00
1339	9	1	2025-05-31	2.75
1340	9	1	2025-06-03	3.16
1341	9	1	2025-06-06	4.17
1342	9	1	2025-06-09	3.52
1343	9	2	2025-06-12	2.45
1344	9	1	2025-06-15	4.44
1345	9	2	2025-06-18	2.51
1346	9	1	2025-06-21	3.51
1347	9	2	2025-06-24	6.91
1348	9	2	2025-06-27	3.58
1349	9	2	2025-06-30	6.25
1350	9	1	2025-07-03	6.88
1351	9	2	2025-07-06	5.92
1352	9	1	2025-07-09	4.02
1353	9	2	2025-07-12	3.38
1354	9	2	2025-07-15	2.90
1355	9	1	2025-07-18	3.85
1356	9	2	2025-07-21	2.66
1357	9	2	2025-07-24	6.10
1358	9	2	2025-07-27	3.40
1359	9	1	2025-07-30	4.21
1360	9	1	2025-08-02	4.34
1361	9	2	2025-08-05	4.00
1362	9	1	2025-08-08	3.89
1363	9	1	2025-08-11	3.09
1364	9	1	2025-08-14	6.65
1365	9	2	2025-08-17	2.79
1366	9	1	2025-08-20	2.60
1367	9	2	2025-08-23	2.85
1368	9	2	2025-08-26	3.56
1369	9	1	2025-08-29	4.80
1370	9	1	2025-09-01	2.56
1371	9	2	2025-09-04	4.01
1372	9	1	2025-09-07	4.06
1373	9	1	2025-09-10	4.52
1374	9	1	2025-09-13	3.70
1375	9	2	2025-09-16	5.71
1376	9	1	2025-09-19	4.46
1377	9	2	2025-09-22	4.30
1378	9	1	2025-09-25	4.10
1379	9	2	2025-09-28	3.45
1380	9	1	2025-10-01	5.48
1381	9	1	2025-10-04	5.17
1382	9	2	2025-10-07	2.52
1383	9	2	2025-10-10	3.43
1384	9	1	2025-10-13	6.80
1385	9	1	2025-10-16	2.04
1386	9	1	2025-10-19	4.07
1387	9	2	2025-10-22	5.54
1388	9	1	2025-10-25	3.79
1389	9	1	2025-10-28	2.14
1390	9	2	2025-10-31	4.70
1391	9	1	2025-11-03	4.81
1392	9	1	2025-11-06	3.22
1393	9	2	2025-11-09	5.13
1394	9	1	2025-11-12	2.87
1395	9	1	2025-11-15	6.95
1396	9	1	2025-11-18	3.26
1397	9	2	2025-11-21	5.14
1398	9	2	2025-11-24	3.90
1399	9	1	2025-11-27	5.94
1400	9	1	2025-11-30	6.39
1401	9	1	2025-12-03	5.75
1402	9	1	2025-12-06	4.34
1403	9	2	2025-12-09	4.15
1404	9	1	2025-12-12	5.72
1405	9	1	2025-12-15	6.08
1406	9	2	2025-12-18	4.64
1407	9	2	2025-12-21	2.03
1408	9	2	2025-12-24	6.65
1409	9	2	2025-12-27	2.47
1410	9	1	2025-12-30	4.15
1411	9	2	2026-01-02	6.11
1412	9	2	2026-01-05	2.67
1413	9	1	2026-01-08	5.20
1414	9	2	2026-01-11	3.28
1415	9	1	2026-01-14	3.50
1416	9	2	2026-01-17	4.39
1417	9	2	2026-01-20	3.57
1418	9	2	2026-01-23	2.32
1419	9	2	2026-01-26	4.91
1420	9	2	2026-01-29	2.15
1421	9	2	2026-02-01	2.14
1422	9	1	2026-02-04	6.63
1423	9	2	2026-02-07	4.45
1424	9	2	2026-02-10	3.15
1425	9	1	2026-02-13	5.46
1426	9	1	2026-02-16	4.11
1427	9	2	2026-02-19	3.23
1428	9	1	2026-02-22	4.59
1429	9	2	2026-02-25	2.75
1430	9	2	2026-02-28	3.70
1431	9	2	2026-03-03	5.80
1432	9	1	2026-03-06	3.39
1433	9	1	2026-03-09	4.70
1434	9	2	2026-03-12	3.06
1435	9	1	2026-03-15	4.97
1436	9	2	2026-03-18	3.39
1437	9	2	2026-03-21	6.37
1438	9	2	2026-03-24	3.77
1439	9	2	2026-03-27	2.97
1440	9	1	2026-03-30	6.75
1441	9	2	2026-04-02	4.69
1442	9	2	2026-04-05	4.62
1443	9	1	2026-04-08	4.84
1444	9	2	2026-04-11	2.88
1445	9	1	2026-04-14	2.25
1446	9	2	2026-04-17	4.86
1447	9	2	2026-04-20	3.77
1448	9	1	2026-04-23	5.07
1449	9	1	2026-04-26	2.91
1450	10	1	2025-01-01	6.59
1451	10	2	2025-01-04	2.34
1452	10	2	2025-01-07	6.79
1453	10	1	2025-01-10	3.63
1454	10	1	2025-01-13	6.24
1455	10	1	2025-01-16	4.32
1456	10	1	2025-01-19	5.89
1457	10	2	2025-01-22	6.88
1458	10	2	2025-01-25	3.40
1459	10	1	2025-01-28	5.68
1460	10	2	2025-01-31	4.93
1461	10	2	2025-02-03	3.37
1462	10	1	2025-02-06	3.54
1463	10	2	2025-02-09	2.01
1464	10	1	2025-02-12	6.54
1465	10	2	2025-02-15	3.03
1466	10	1	2025-02-18	6.19
1467	10	1	2025-02-21	4.74
1468	10	1	2025-02-24	4.91
1469	10	2	2025-02-27	2.87
1470	10	2	2025-03-02	3.23
1471	10	1	2025-03-05	5.23
1472	10	2	2025-03-08	6.15
1473	10	1	2025-03-11	2.73
1474	10	2	2025-03-14	4.07
1475	10	1	2025-03-17	6.64
1476	10	1	2025-03-20	5.71
1477	10	2	2025-03-23	4.09
1478	10	2	2025-03-26	2.67
1479	10	2	2025-03-29	3.29
1480	10	1	2025-04-01	5.48
1481	10	2	2025-04-04	3.73
1482	10	1	2025-04-07	3.51
1483	10	1	2025-04-10	3.74
1484	10	1	2025-04-13	4.66
1485	10	2	2025-04-16	2.26
1486	10	2	2025-04-19	4.33
1487	10	2	2025-04-22	4.57
1488	10	2	2025-04-25	6.73
1489	10	2	2025-04-28	2.49
1490	10	2	2025-05-01	3.68
1491	10	1	2025-05-04	2.27
1492	10	1	2025-05-07	4.40
1493	10	2	2025-05-10	7.00
1494	10	1	2025-05-13	5.72
1495	10	1	2025-05-16	6.16
1496	10	1	2025-05-19	5.38
1497	10	1	2025-05-22	6.76
1498	10	2	2025-05-25	4.81
1499	10	1	2025-05-28	2.83
1500	10	2	2025-05-31	6.03
1501	10	2	2025-06-03	4.88
1502	10	2	2025-06-06	2.67
1503	10	2	2025-06-09	3.56
1504	10	2	2025-06-12	6.56
1505	10	2	2025-06-15	3.20
1506	10	2	2025-06-18	6.55
1507	10	1	2025-06-21	5.88
1508	10	1	2025-06-24	4.00
1509	10	2	2025-06-27	5.73
1510	10	2	2025-06-30	6.51
1511	10	2	2025-07-03	6.30
1512	10	1	2025-07-06	2.94
1513	10	1	2025-07-09	3.03
1514	10	2	2025-07-12	6.00
1515	10	2	2025-07-15	3.53
1516	10	1	2025-07-18	2.70
1517	10	2	2025-07-21	3.57
1518	10	2	2025-07-24	5.47
1519	10	1	2025-07-27	2.71
1520	10	2	2025-07-30	4.69
1521	10	1	2025-08-02	5.65
1522	10	1	2025-08-05	5.61
1523	10	1	2025-08-08	4.92
1524	10	2	2025-08-11	3.06
1525	10	2	2025-08-14	6.05
1526	10	1	2025-08-17	3.41
1527	10	1	2025-08-20	6.07
1528	10	1	2025-08-23	6.64
1529	10	2	2025-08-26	3.26
1530	10	2	2025-08-29	5.40
1531	10	2	2025-09-01	5.58
1532	10	2	2025-09-04	3.67
1533	10	2	2025-09-07	2.79
1534	10	2	2025-09-10	6.00
1535	10	1	2025-09-13	2.01
1536	10	2	2025-09-16	3.62
1537	10	2	2025-09-19	4.72
1538	10	2	2025-09-22	4.24
1539	10	2	2025-09-25	3.53
1540	10	1	2025-09-28	4.84
1541	10	2	2025-10-01	4.53
1542	10	2	2025-10-04	3.31
1543	10	2	2025-10-07	5.33
1544	10	1	2025-10-10	2.53
1545	10	2	2025-10-13	6.10
1546	10	2	2025-10-16	3.22
1547	10	1	2025-10-19	4.76
1548	10	2	2025-10-22	2.07
1549	10	1	2025-10-25	3.13
1550	10	1	2025-10-28	5.46
1551	10	2	2025-10-31	6.54
1552	10	1	2025-11-03	4.09
1553	10	2	2025-11-06	2.81
1554	10	2	2025-11-09	2.44
1555	10	1	2025-11-12	4.01
1556	10	1	2025-11-15	5.76
1557	10	2	2025-11-18	4.88
1558	10	1	2025-11-21	4.92
1559	10	2	2025-11-24	6.10
1560	10	1	2025-11-27	5.71
1561	10	2	2025-11-30	6.45
1562	10	1	2025-12-03	5.10
1563	10	1	2025-12-06	3.21
1564	10	1	2025-12-09	3.38
1565	10	1	2025-12-12	2.16
1566	10	1	2025-12-15	5.97
1567	10	1	2025-12-18	3.13
1568	10	2	2025-12-21	2.36
1569	10	1	2025-12-24	6.96
1570	10	2	2025-12-27	5.72
1571	10	1	2025-12-30	5.11
1572	10	1	2026-01-02	3.75
1573	10	1	2026-01-05	3.34
1574	10	1	2026-01-08	4.71
1575	10	1	2026-01-11	3.36
1576	10	1	2026-01-14	5.09
1577	10	2	2026-01-17	6.77
1578	10	1	2026-01-20	3.54
1579	10	2	2026-01-23	2.59
1580	10	2	2026-01-26	4.95
1581	10	1	2026-01-29	3.98
1582	10	1	2026-02-01	4.06
1583	10	1	2026-02-04	5.66
1584	10	2	2026-02-07	2.13
1585	10	1	2026-02-10	4.19
1586	10	1	2026-02-13	2.89
1587	10	1	2026-02-16	4.23
1588	10	2	2026-02-19	3.03
1589	10	2	2026-02-22	4.74
1590	10	2	2026-02-25	6.09
1591	10	1	2026-02-28	6.67
1592	10	2	2026-03-03	3.00
1593	10	1	2026-03-06	6.41
1594	10	2	2026-03-09	4.09
1595	10	1	2026-03-12	6.99
1596	10	2	2026-03-15	6.95
1597	10	2	2026-03-18	3.43
1598	10	2	2026-03-21	5.10
1599	10	2	2026-03-24	5.01
1600	10	2	2026-03-27	6.62
1601	10	1	2026-03-30	3.87
1602	10	2	2026-04-02	5.07
1603	10	2	2026-04-05	4.62
1604	10	1	2026-04-08	4.46
1605	10	2	2026-04-11	6.58
1606	10	2	2026-04-14	4.11
1607	10	1	2026-04-17	3.91
1608	10	1	2026-04-20	6.09
1609	10	1	2026-04-23	6.17
1610	10	2	2026-04-26	6.18
1611	11	2	2025-01-01	5.37
1612	11	1	2025-01-04	5.04
1613	11	2	2025-01-07	5.42
1614	11	1	2025-01-10	5.11
1615	11	2	2025-01-13	5.40
1616	11	2	2025-01-16	6.99
1617	11	1	2025-01-19	6.20
1618	11	2	2025-01-22	5.23
1619	11	2	2025-01-25	3.03
1620	11	2	2025-01-28	4.88
1621	11	2	2025-01-31	3.32
1622	11	2	2025-02-03	2.52
1623	11	1	2025-02-06	4.71
1624	11	1	2025-02-09	5.16
1625	11	1	2025-02-12	6.38
1626	11	1	2025-02-15	6.89
1627	11	1	2025-02-18	4.48
1628	11	2	2025-02-21	4.82
1629	11	2	2025-02-24	4.48
1630	11	2	2025-02-27	6.56
1631	11	2	2025-03-02	5.72
1632	11	1	2025-03-05	6.53
1633	11	1	2025-03-08	5.90
1634	11	1	2025-03-11	4.33
1635	11	1	2025-03-14	4.38
1636	11	1	2025-03-17	6.01
1637	11	1	2025-03-20	5.71
1638	11	2	2025-03-23	3.03
1639	11	1	2025-03-26	5.08
1640	11	2	2025-03-29	2.73
1641	11	2	2025-04-01	4.36
1642	11	1	2025-04-04	4.12
1643	11	1	2025-04-07	2.24
1644	11	2	2025-04-10	2.94
1645	11	2	2025-04-13	4.35
1646	11	2	2025-04-16	6.09
1647	11	1	2025-04-19	6.75
1648	11	1	2025-04-22	5.90
1649	11	2	2025-04-25	2.60
1650	11	1	2025-04-28	4.07
1651	11	1	2025-05-01	4.13
1652	11	1	2025-05-04	3.80
1653	11	1	2025-05-07	5.32
1654	11	2	2025-05-10	5.63
1655	11	2	2025-05-13	4.65
1656	11	2	2025-05-16	2.09
1657	11	2	2025-05-19	2.63
1658	11	1	2025-05-22	4.56
1659	11	1	2025-05-25	4.48
1660	11	1	2025-05-28	6.65
1661	11	1	2025-05-31	3.38
1662	11	1	2025-06-03	2.25
1663	11	2	2025-06-06	2.17
1664	11	2	2025-06-09	3.36
1665	11	2	2025-06-12	5.65
1666	11	2	2025-06-15	5.84
1667	11	1	2025-06-18	6.54
1668	11	1	2025-06-21	2.48
1669	11	2	2025-06-24	2.87
1670	11	2	2025-06-27	2.90
1671	11	2	2025-06-30	6.86
1672	11	1	2025-07-03	5.75
1673	11	1	2025-07-06	3.88
1674	11	1	2025-07-09	3.45
1675	11	1	2025-07-12	2.22
1676	11	2	2025-07-15	3.49
1677	11	1	2025-07-18	6.19
1678	11	2	2025-07-21	4.94
1679	11	1	2025-07-24	2.01
1680	11	1	2025-07-27	3.49
1681	11	1	2025-07-30	4.08
1682	11	1	2025-08-02	6.86
1683	11	2	2025-08-05	6.45
1684	11	2	2025-08-08	3.85
1685	11	2	2025-08-11	5.52
1686	11	1	2025-08-14	4.82
1687	11	2	2025-08-17	5.85
1688	11	2	2025-08-20	4.56
1689	11	1	2025-08-23	6.60
1690	11	2	2025-08-26	4.42
1691	11	1	2025-08-29	2.18
1692	11	2	2025-09-01	3.71
1693	11	2	2025-09-04	3.61
1694	11	1	2025-09-07	6.52
1695	11	2	2025-09-10	2.86
1696	11	1	2025-09-13	6.01
1697	11	2	2025-09-16	3.47
1698	11	2	2025-09-19	5.66
1699	11	2	2025-09-22	3.80
1700	11	1	2025-09-25	2.64
1701	11	2	2025-09-28	5.51
1702	11	2	2025-10-01	5.33
1703	11	2	2025-10-04	4.16
1704	11	2	2025-10-07	2.26
1705	11	2	2025-10-10	5.81
1706	11	2	2025-10-13	2.32
1707	11	1	2025-10-16	2.65
1708	11	2	2025-10-19	4.87
1709	11	2	2025-10-22	3.71
1710	11	2	2025-10-25	6.98
1711	11	1	2025-10-28	5.82
1712	11	1	2025-10-31	2.29
1713	11	1	2025-11-03	5.28
1714	11	2	2025-11-06	2.81
1715	11	2	2025-11-09	5.39
1716	11	1	2025-11-12	3.16
1717	11	2	2025-11-15	5.72
1718	11	1	2025-11-18	6.34
1719	11	2	2025-11-21	5.67
1720	11	1	2025-11-24	3.14
1721	11	2	2025-11-27	3.39
1722	11	1	2025-11-30	3.49
1723	11	2	2025-12-03	2.16
1724	11	2	2025-12-06	3.01
1725	11	2	2025-12-09	6.29
1726	11	2	2025-12-12	5.24
1727	11	1	2025-12-15	6.85
1728	11	1	2025-12-18	5.57
1729	11	2	2025-12-21	6.62
1730	11	1	2025-12-24	5.50
1731	11	1	2025-12-27	6.64
1732	11	1	2025-12-30	2.04
1733	11	2	2026-01-02	5.39
1734	11	1	2026-01-05	4.28
1735	11	1	2026-01-08	4.46
1736	11	2	2026-01-11	4.31
1737	11	2	2026-01-14	6.22
1738	11	2	2026-01-17	3.42
1739	11	2	2026-01-20	2.35
1740	11	1	2026-01-23	2.81
1741	11	2	2026-01-26	4.04
1742	11	1	2026-01-29	5.86
1743	11	2	2026-02-01	3.12
1744	11	1	2026-02-04	4.37
1745	11	2	2026-02-07	5.72
1746	11	1	2026-02-10	2.89
1747	11	2	2026-02-13	4.76
1748	11	2	2026-02-16	6.58
1749	11	1	2026-02-19	4.34
1750	11	2	2026-02-22	2.01
1751	11	2	2026-02-25	3.97
1752	11	1	2026-02-28	3.16
1753	11	1	2026-03-03	5.74
1754	11	1	2026-03-06	5.64
1755	11	2	2026-03-09	6.28
1756	11	2	2026-03-12	2.81
1757	11	2	2026-03-15	5.18
1758	11	1	2026-03-18	4.02
1759	11	2	2026-03-21	2.23
1760	11	2	2026-03-24	6.81
1761	11	1	2026-03-27	4.92
1762	11	1	2026-03-30	3.75
1763	11	1	2026-04-02	3.75
1764	11	1	2026-04-05	2.83
1765	11	1	2026-04-08	4.97
1766	11	1	2026-04-11	4.56
1767	11	1	2026-04-14	6.93
1768	11	1	2026-04-17	2.60
1769	11	2	2026-04-20	6.99
1770	11	2	2026-04-23	3.85
1771	11	2	2026-04-26	6.92
1772	12	1	2025-01-01	2.42
1773	12	2	2025-01-04	2.36
1774	12	2	2025-01-07	3.80
1775	12	1	2025-01-10	2.26
1776	12	2	2025-01-13	4.07
1777	12	1	2025-01-16	4.31
1778	12	2	2025-01-19	5.46
1779	12	1	2025-01-22	5.41
1780	12	1	2025-01-25	6.67
1781	12	2	2025-01-28	2.01
1782	12	2	2025-01-31	2.41
1783	12	2	2025-02-03	5.55
1784	12	1	2025-02-06	4.94
1785	12	1	2025-02-09	2.70
1786	12	1	2025-02-12	6.45
1787	12	2	2025-02-15	5.39
1788	12	2	2025-02-18	4.43
1789	12	2	2025-02-21	4.75
1790	12	2	2025-02-24	4.52
1791	12	1	2025-02-27	4.17
1792	12	1	2025-03-02	6.79
1793	12	2	2025-03-05	3.54
1794	12	1	2025-03-08	2.75
1795	12	2	2025-03-11	5.31
1796	12	1	2025-03-14	3.25
1797	12	2	2025-03-17	2.78
1798	12	1	2025-03-20	4.77
1799	12	1	2025-03-23	2.34
1800	12	1	2025-03-26	2.46
1801	12	2	2025-03-29	5.39
1802	12	1	2025-04-01	2.22
1803	12	1	2025-04-04	3.51
1804	12	1	2025-04-07	2.85
1805	12	2	2025-04-10	2.82
1806	12	2	2025-04-13	6.71
1807	12	2	2025-04-16	5.03
1808	12	2	2025-04-19	2.30
1809	12	2	2025-04-22	4.83
1810	12	2	2025-04-25	6.66
1811	12	1	2025-04-28	4.83
1812	12	1	2025-05-01	6.07
1813	12	1	2025-05-04	3.83
1814	12	2	2025-05-07	5.08
1815	12	1	2025-05-10	3.42
1816	12	2	2025-05-13	6.26
1817	12	2	2025-05-16	4.89
1818	12	1	2025-05-19	4.28
1819	12	1	2025-05-22	3.06
1820	12	2	2025-05-25	6.49
1821	12	2	2025-05-28	6.18
1822	12	2	2025-05-31	2.16
1823	12	1	2025-06-03	3.95
1824	12	1	2025-06-06	4.00
1825	12	1	2025-06-09	3.98
1826	12	1	2025-06-12	2.83
1827	12	2	2025-06-15	2.55
1828	12	1	2025-06-18	2.72
1829	12	2	2025-06-21	2.99
1830	12	2	2025-06-24	4.37
1831	12	2	2025-06-27	5.12
1832	12	1	2025-06-30	4.99
1833	12	1	2025-07-03	4.17
1834	12	1	2025-07-06	2.60
1835	12	2	2025-07-09	2.88
1836	12	1	2025-07-12	6.16
1837	12	2	2025-07-15	5.65
1838	12	1	2025-07-18	3.97
1839	12	2	2025-07-21	4.06
1840	12	2	2025-07-24	4.59
1841	12	1	2025-07-27	3.56
1842	12	2	2025-07-30	2.14
1843	12	2	2025-08-02	6.65
1844	12	1	2025-08-05	5.66
1845	12	2	2025-08-08	5.47
1846	12	2	2025-08-11	4.81
1847	12	1	2025-08-14	6.74
1848	12	2	2025-08-17	2.60
1849	12	1	2025-08-20	6.36
1850	12	1	2025-08-23	6.60
1851	12	1	2025-08-26	2.34
1852	12	2	2025-08-29	3.67
1853	12	1	2025-09-01	3.40
1854	12	1	2025-09-04	2.94
1855	12	2	2025-09-07	2.06
1856	12	1	2025-09-10	6.97
1857	12	1	2025-09-13	5.40
1858	12	1	2025-09-16	5.88
1859	12	1	2025-09-19	6.67
1860	12	1	2025-09-22	3.99
1861	12	2	2025-09-25	6.72
1862	12	2	2025-09-28	2.20
1863	12	2	2025-10-01	2.63
1864	12	1	2025-10-04	2.41
1865	12	1	2025-10-07	4.46
1866	12	2	2025-10-10	3.04
1867	12	2	2025-10-13	2.19
1868	12	1	2025-10-16	4.58
1869	12	2	2025-10-19	5.46
1870	12	1	2025-10-22	5.00
1871	12	2	2025-10-25	4.42
1872	12	2	2025-10-28	3.74
1873	12	1	2025-10-31	5.33
1874	12	2	2025-11-03	2.26
1875	12	2	2025-11-06	5.73
1876	12	2	2025-11-09	2.61
1877	12	1	2025-11-12	5.22
1878	12	1	2025-11-15	4.76
1879	12	2	2025-11-18	5.98
1880	12	1	2025-11-21	3.95
1881	12	1	2025-11-24	4.92
1882	12	1	2025-11-27	3.29
1883	12	1	2025-11-30	5.24
1884	12	1	2025-12-03	5.50
1885	12	1	2025-12-06	4.77
1886	12	1	2025-12-09	6.90
1887	12	2	2025-12-12	2.11
1888	12	1	2025-12-15	5.10
1889	12	1	2025-12-18	3.75
1890	12	2	2025-12-21	2.90
1891	12	1	2025-12-24	4.85
1892	12	1	2025-12-27	5.77
1893	12	2	2025-12-30	6.65
1894	12	1	2026-01-02	5.78
1895	12	2	2026-01-05	5.15
1896	12	2	2026-01-08	5.96
1897	12	2	2026-01-11	5.06
1898	12	2	2026-01-14	6.50
1899	12	1	2026-01-17	4.42
1900	12	2	2026-01-20	5.24
1901	12	1	2026-01-23	4.32
1902	12	2	2026-01-26	2.75
1903	12	2	2026-01-29	2.03
1904	12	2	2026-02-01	6.36
1905	12	1	2026-02-04	4.04
1906	12	2	2026-02-07	3.06
1907	12	2	2026-02-10	5.17
1908	12	1	2026-02-13	2.66
1909	12	1	2026-02-16	6.03
1910	12	2	2026-02-19	6.53
1911	12	1	2026-02-22	6.09
1912	12	1	2026-02-25	3.04
1913	12	1	2026-02-28	6.52
1914	12	1	2026-03-03	3.93
1915	12	1	2026-03-06	6.99
1916	12	1	2026-03-09	3.56
1917	12	1	2026-03-12	3.69
1918	12	1	2026-03-15	4.31
1919	12	2	2026-03-18	6.14
1920	12	2	2026-03-21	5.92
1921	12	2	2026-03-24	4.78
1922	12	2	2026-03-27	6.21
1923	12	1	2026-03-30	2.72
1924	12	1	2026-04-02	5.48
1925	12	2	2026-04-05	2.55
1926	12	1	2026-04-08	6.12
1927	12	1	2026-04-11	2.23
1928	12	2	2026-04-14	5.39
1929	12	1	2026-04-17	5.82
1930	12	1	2026-04-20	3.20
1931	12	1	2026-04-23	4.49
1932	12	2	2026-04-26	4.95
1933	13	2	2025-01-01	4.25
1934	13	1	2025-01-04	5.28
1935	13	1	2025-01-07	2.81
1936	13	1	2025-01-10	6.11
1937	13	1	2025-01-13	5.81
1938	13	2	2025-01-16	5.54
1939	13	2	2025-01-19	4.47
1940	13	2	2025-01-22	5.30
1941	13	2	2025-01-25	5.24
1942	13	1	2025-01-28	6.48
1943	13	2	2025-01-31	3.38
1944	13	1	2025-02-03	5.20
1945	13	1	2025-02-06	6.59
1946	13	1	2025-02-09	5.32
1947	13	1	2025-02-12	6.57
1948	13	2	2025-02-15	3.39
1949	13	2	2025-02-18	4.54
1950	13	1	2025-02-21	3.55
1951	13	2	2025-02-24	5.99
1952	13	2	2025-02-27	5.67
1953	13	2	2025-03-02	2.88
1954	13	1	2025-03-05	3.66
1955	13	1	2025-03-08	5.79
1956	13	2	2025-03-11	3.03
1957	13	2	2025-03-14	5.73
1958	13	2	2025-03-17	2.63
1959	13	2	2025-03-20	2.82
1960	13	2	2025-03-23	4.57
1961	13	1	2025-03-26	5.34
1962	13	1	2025-03-29	6.00
1963	13	1	2025-04-01	4.57
1964	13	1	2025-04-04	2.55
1965	13	2	2025-04-07	6.70
1966	13	2	2025-04-10	4.90
1967	13	1	2025-04-13	2.85
1968	13	1	2025-04-16	6.21
1969	13	2	2025-04-19	6.72
1970	13	2	2025-04-22	4.44
1971	13	2	2025-04-25	6.13
1972	13	2	2025-04-28	4.28
1973	13	1	2025-05-01	3.84
1974	13	2	2025-05-04	4.00
1975	13	2	2025-05-07	3.99
1976	13	2	2025-05-10	5.28
1977	13	2	2025-05-13	4.59
1978	13	1	2025-05-16	4.03
1979	13	1	2025-05-19	3.47
1980	13	1	2025-05-22	4.80
1981	13	1	2025-05-25	6.89
1982	13	2	2025-05-28	2.33
1983	13	1	2025-05-31	2.44
1984	13	2	2025-06-03	3.28
1985	13	1	2025-06-06	2.64
1986	13	1	2025-06-09	3.99
1987	13	1	2025-06-12	2.50
1988	13	1	2025-06-15	4.34
1989	13	2	2025-06-18	6.86
1990	13	1	2025-06-21	5.61
1991	13	1	2025-06-24	2.80
1992	13	2	2025-06-27	4.71
1993	13	1	2025-06-30	5.48
1994	13	2	2025-07-03	5.89
1995	13	1	2025-07-06	6.97
1996	13	2	2025-07-09	4.24
1997	13	1	2025-07-12	6.23
1998	13	1	2025-07-15	5.62
1999	13	2	2025-07-18	4.31
2000	13	1	2025-07-21	5.46
2001	13	1	2025-07-24	3.06
2002	13	2	2025-07-27	2.95
2003	13	2	2025-07-30	4.73
2004	13	2	2025-08-02	4.38
2005	13	1	2025-08-05	4.34
2006	13	2	2025-08-08	2.06
2007	13	2	2025-08-11	6.03
2008	13	2	2025-08-14	3.56
2009	13	2	2025-08-17	6.37
2010	13	2	2025-08-20	4.66
2011	13	2	2025-08-23	6.90
2012	13	2	2025-08-26	6.71
2013	13	2	2025-08-29	3.69
2014	13	1	2025-09-01	2.30
2015	13	1	2025-09-04	2.01
2016	13	1	2025-09-07	6.41
2017	13	2	2025-09-10	5.77
2018	13	1	2025-09-13	4.48
2019	13	1	2025-09-16	5.98
2020	13	2	2025-09-19	3.43
2021	13	1	2025-09-22	5.58
2022	13	1	2025-09-25	3.27
2023	13	1	2025-09-28	4.30
2024	13	1	2025-10-01	5.47
2025	13	2	2025-10-04	5.37
2026	13	1	2025-10-07	4.17
2027	13	1	2025-10-10	6.92
2028	13	2	2025-10-13	5.71
2029	13	1	2025-10-16	6.58
2030	13	2	2025-10-19	2.50
2031	13	2	2025-10-22	5.93
2032	13	1	2025-10-25	6.12
2033	13	1	2025-10-28	6.61
2034	13	1	2025-10-31	5.03
2035	13	1	2025-11-03	6.10
2036	13	2	2025-11-06	3.29
2037	13	2	2025-11-09	5.47
2038	13	2	2025-11-12	3.38
2039	13	2	2025-11-15	6.04
2040	13	2	2025-11-18	4.29
2041	13	2	2025-11-21	5.14
2042	13	2	2025-11-24	6.52
2043	13	1	2025-11-27	4.54
2044	13	2	2025-11-30	5.37
2045	13	2	2025-12-03	6.68
2046	13	1	2025-12-06	3.01
2047	13	2	2025-12-09	6.06
2048	13	2	2025-12-12	3.26
2049	13	1	2025-12-15	5.18
2050	13	2	2025-12-18	6.52
2051	13	1	2025-12-21	6.90
2052	13	1	2025-12-24	4.00
2053	13	1	2025-12-27	4.80
2054	13	2	2025-12-30	4.46
2055	13	1	2026-01-02	4.91
2056	13	1	2026-01-05	2.18
2057	13	2	2026-01-08	4.54
2058	13	2	2026-01-11	3.41
2059	13	1	2026-01-14	3.38
2060	13	1	2026-01-17	2.77
2061	13	1	2026-01-20	3.70
2062	13	1	2026-01-23	6.90
2063	13	2	2026-01-26	3.70
2064	13	2	2026-01-29	4.87
2065	13	1	2026-02-01	3.74
2066	13	2	2026-02-04	5.53
2067	13	2	2026-02-07	6.26
2068	13	2	2026-02-10	3.19
2069	13	2	2026-02-13	2.38
2070	13	1	2026-02-16	2.95
2071	13	2	2026-02-19	5.72
2072	13	2	2026-02-22	4.66
2073	13	1	2026-02-25	2.47
2074	13	1	2026-02-28	3.29
2075	13	2	2026-03-03	3.11
2076	13	2	2026-03-06	3.91
2077	13	1	2026-03-09	2.45
2078	13	1	2026-03-12	5.99
2079	13	1	2026-03-15	3.18
2080	13	1	2026-03-18	3.03
2081	13	1	2026-03-21	3.95
2082	13	1	2026-03-24	2.01
2083	13	2	2026-03-27	6.85
2084	13	2	2026-03-30	5.42
2085	13	1	2026-04-02	3.20
2086	13	1	2026-04-05	2.89
2087	13	1	2026-04-08	5.03
2088	13	2	2026-04-11	5.12
2089	13	1	2026-04-14	5.38
2090	13	1	2026-04-17	5.11
2091	13	2	2026-04-20	6.23
2092	13	2	2026-04-23	6.55
2093	13	1	2026-04-26	3.99
2094	14	1	2025-01-01	5.04
2095	14	1	2025-01-04	3.35
2096	14	1	2025-01-07	3.63
2097	14	2	2025-01-10	2.91
2098	14	2	2025-01-13	5.13
2099	14	1	2025-01-16	5.20
2100	14	2	2025-01-19	4.90
2101	14	2	2025-01-22	4.95
2102	14	2	2025-01-25	4.11
2103	14	1	2025-01-28	5.84
2104	14	2	2025-01-31	6.82
2105	14	2	2025-02-03	6.81
2106	14	2	2025-02-06	6.71
2107	14	1	2025-02-09	2.95
2108	14	2	2025-02-12	6.19
2109	14	1	2025-02-15	5.24
2110	14	1	2025-02-18	5.81
2111	14	1	2025-02-21	6.73
2112	14	2	2025-02-24	2.99
2113	14	2	2025-02-27	5.04
2114	14	1	2025-03-02	6.26
2115	14	2	2025-03-05	4.09
2116	14	2	2025-03-08	5.62
2117	14	1	2025-03-11	2.26
2118	14	2	2025-03-14	4.56
2119	14	2	2025-03-17	5.07
2120	14	2	2025-03-20	2.18
2121	14	2	2025-03-23	5.55
2122	14	1	2025-03-26	3.50
2123	14	1	2025-03-29	4.40
2124	14	2	2025-04-01	2.64
2125	14	1	2025-04-04	2.13
2126	14	2	2025-04-07	4.16
2127	14	2	2025-04-10	4.07
2128	14	2	2025-04-13	5.67
2129	14	2	2025-04-16	5.03
2130	14	2	2025-04-19	3.06
2131	14	2	2025-04-22	6.41
2132	14	1	2025-04-25	6.58
2133	14	2	2025-04-28	6.10
2134	14	2	2025-05-01	3.90
2135	14	2	2025-05-04	2.50
2136	14	1	2025-05-07	2.78
2137	14	1	2025-05-10	6.32
2138	14	1	2025-05-13	6.99
2139	14	1	2025-05-16	3.87
2140	14	1	2025-05-19	5.89
2141	14	1	2025-05-22	4.21
2142	14	2	2025-05-25	3.89
2143	14	2	2025-05-28	3.57
2144	14	2	2025-05-31	3.61
2145	14	2	2025-06-03	6.98
2146	14	2	2025-06-06	6.94
2147	14	1	2025-06-09	4.97
2148	14	1	2025-06-12	3.22
2149	14	1	2025-06-15	5.78
2150	14	2	2025-06-18	4.53
2151	14	1	2025-06-21	3.13
2152	14	2	2025-06-24	2.19
2153	14	2	2025-06-27	3.96
2154	14	2	2025-06-30	3.05
2155	14	1	2025-07-03	5.31
2156	14	1	2025-07-06	3.28
2157	14	2	2025-07-09	3.77
2158	14	2	2025-07-12	3.02
2159	14	2	2025-07-15	4.49
2160	14	2	2025-07-18	3.71
2161	14	2	2025-07-21	5.45
2162	14	2	2025-07-24	6.46
2163	14	2	2025-07-27	3.52
2164	14	2	2025-07-30	6.93
2165	14	1	2025-08-02	3.20
2166	14	1	2025-08-05	4.92
2167	14	1	2025-08-08	3.20
2168	14	2	2025-08-11	2.63
2169	14	2	2025-08-14	3.90
2170	14	2	2025-08-17	5.57
2171	14	1	2025-08-20	5.43
2172	14	2	2025-08-23	3.94
2173	14	1	2025-08-26	5.88
2174	14	1	2025-08-29	4.48
2175	14	1	2025-09-01	5.45
2176	14	2	2025-09-04	6.52
2177	14	2	2025-09-07	3.84
2178	14	1	2025-09-10	3.75
2179	14	2	2025-09-13	4.26
2180	14	2	2025-09-16	4.60
2181	14	2	2025-09-19	5.46
2182	14	2	2025-09-22	5.16
2183	14	1	2025-09-25	2.68
2184	14	1	2025-09-28	5.62
2185	14	2	2025-10-01	2.16
2186	14	2	2025-10-04	4.63
2187	14	1	2025-10-07	5.07
2188	14	2	2025-10-10	3.87
2189	14	1	2025-10-13	5.37
2190	14	2	2025-10-16	2.77
2191	14	1	2025-10-19	3.49
2192	14	2	2025-10-22	3.29
2193	14	2	2025-10-25	6.22
2194	14	1	2025-10-28	5.07
2195	14	1	2025-10-31	5.95
2196	14	1	2025-11-03	5.00
2197	14	1	2025-11-06	6.47
2198	14	2	2025-11-09	4.95
2199	14	1	2025-11-12	6.54
2200	14	2	2025-11-15	3.95
2201	14	1	2025-11-18	3.21
2202	14	1	2025-11-21	5.48
2203	14	1	2025-11-24	4.95
2204	14	1	2025-11-27	6.38
2205	14	2	2025-11-30	4.89
2206	14	1	2025-12-03	5.01
2207	14	1	2025-12-06	5.45
2208	14	1	2025-12-09	2.61
2209	14	1	2025-12-12	6.86
2210	14	1	2025-12-15	3.22
2211	14	2	2025-12-18	4.75
2212	14	2	2025-12-21	4.45
2213	14	1	2025-12-24	3.00
2214	14	2	2025-12-27	5.43
2215	14	1	2025-12-30	2.56
2216	14	2	2026-01-02	5.61
2217	14	1	2026-01-05	5.85
2218	14	1	2026-01-08	6.66
2219	14	2	2026-01-11	6.57
2220	14	1	2026-01-14	2.55
2221	14	1	2026-01-17	6.91
2222	14	1	2026-01-20	4.55
2223	14	2	2026-01-23	4.58
2224	14	1	2026-01-26	5.22
2225	14	2	2026-01-29	6.87
2226	14	1	2026-02-01	4.60
2227	14	1	2026-02-04	6.97
2228	14	1	2026-02-07	2.83
2229	14	1	2026-02-10	2.66
2230	14	2	2026-02-13	3.75
2231	14	1	2026-02-16	5.97
2232	14	1	2026-02-19	4.06
2233	14	1	2026-02-22	2.51
2234	14	1	2026-02-25	2.02
2235	14	2	2026-02-28	6.83
2236	14	2	2026-03-03	3.87
2237	14	1	2026-03-06	2.38
2238	14	2	2026-03-09	4.41
2239	14	2	2026-03-12	4.48
2240	14	1	2026-03-15	5.63
2241	14	1	2026-03-18	4.43
2242	14	2	2026-03-21	2.79
2243	14	1	2026-03-24	5.45
2244	14	1	2026-03-27	2.13
2245	14	1	2026-03-30	4.35
2246	14	1	2026-04-02	5.80
2247	14	2	2026-04-05	3.94
2248	14	2	2026-04-08	4.91
2249	14	1	2026-04-11	6.72
2250	14	2	2026-04-14	5.12
2251	14	2	2026-04-17	6.52
2252	14	1	2026-04-20	6.77
2253	14	1	2026-04-23	2.04
2254	14	1	2026-04-26	3.95
2255	15	2	2025-01-01	3.79
2256	15	1	2025-01-04	4.47
2257	15	1	2025-01-07	2.32
2258	15	1	2025-01-10	2.10
2259	15	2	2025-01-13	3.14
2260	15	2	2025-01-16	5.53
2261	15	1	2025-01-19	5.55
2262	15	1	2025-01-22	4.96
2263	15	2	2025-01-25	5.32
2264	15	1	2025-01-28	3.85
2265	15	1	2025-01-31	3.15
2266	15	1	2025-02-03	6.62
2267	15	2	2025-02-06	4.33
2268	15	1	2025-02-09	2.93
2269	15	2	2025-02-12	3.78
2270	15	2	2025-02-15	2.42
2271	15	2	2025-02-18	5.77
2272	15	2	2025-02-21	4.73
2273	15	2	2025-02-24	5.17
2274	15	1	2025-02-27	4.06
2275	15	1	2025-03-02	2.78
2276	15	1	2025-03-05	3.00
2277	15	1	2025-03-08	2.71
2278	15	2	2025-03-11	5.53
2279	15	1	2025-03-14	2.89
2280	15	2	2025-03-17	4.07
2281	15	1	2025-03-20	6.15
2282	15	1	2025-03-23	4.07
2283	15	2	2025-03-26	3.64
2284	15	1	2025-03-29	2.31
2285	15	2	2025-04-01	2.48
2286	15	2	2025-04-04	2.82
2287	15	2	2025-04-07	6.64
2288	15	2	2025-04-10	2.58
2289	15	2	2025-04-13	6.40
2290	15	1	2025-04-16	2.44
2291	15	2	2025-04-19	2.21
2292	15	2	2025-04-22	6.40
2293	15	2	2025-04-25	2.56
2294	15	1	2025-04-28	4.18
2295	15	1	2025-05-01	5.15
2296	15	2	2025-05-04	4.50
2297	15	1	2025-05-07	2.07
2298	15	2	2025-05-10	2.59
2299	15	1	2025-05-13	2.13
2300	15	1	2025-05-16	3.39
2301	15	2	2025-05-19	4.02
2302	15	1	2025-05-22	5.24
2303	15	2	2025-05-25	6.38
2304	15	1	2025-05-28	6.13
2305	15	1	2025-05-31	5.97
2306	15	1	2025-06-03	4.66
2307	15	1	2025-06-06	4.03
2308	15	1	2025-06-09	6.66
2309	15	1	2025-06-12	4.10
2310	15	2	2025-06-15	6.35
2311	15	1	2025-06-18	6.09
2312	15	2	2025-06-21	5.02
2313	15	2	2025-06-24	5.38
2314	15	1	2025-06-27	2.76
2315	15	1	2025-06-30	2.57
2316	15	2	2025-07-03	3.85
2317	15	1	2025-07-06	3.89
2318	15	1	2025-07-09	2.02
2319	15	1	2025-07-12	5.16
2320	15	1	2025-07-15	3.12
2321	15	1	2025-07-18	3.79
2322	15	2	2025-07-21	4.60
2323	15	1	2025-07-24	4.95
2324	15	1	2025-07-27	3.37
2325	15	1	2025-07-30	4.82
2326	15	1	2025-08-02	5.02
2327	15	1	2025-08-05	6.48
2328	15	1	2025-08-08	4.06
2329	15	2	2025-08-11	6.14
2330	15	1	2025-08-14	4.07
2331	15	1	2025-08-17	6.31
2332	15	2	2025-08-20	4.19
2333	15	2	2025-08-23	4.98
2334	15	1	2025-08-26	2.79
2335	15	2	2025-08-29	3.62
2336	15	2	2025-09-01	6.94
2337	15	2	2025-09-04	2.85
2338	15	1	2025-09-07	2.87
2339	15	2	2025-09-10	4.66
2340	15	2	2025-09-13	3.52
2341	15	2	2025-09-16	6.66
2342	15	1	2025-09-19	5.56
2343	15	2	2025-09-22	6.41
2344	15	2	2025-09-25	3.79
2345	15	1	2025-09-28	5.74
2346	15	2	2025-10-01	3.65
2347	15	1	2025-10-04	5.27
2348	15	1	2025-10-07	2.74
2349	15	1	2025-10-10	6.57
2350	15	2	2025-10-13	5.22
2351	15	2	2025-10-16	6.29
2352	15	2	2025-10-19	5.19
2353	15	1	2025-10-22	3.61
2354	15	1	2025-10-25	4.76
2355	15	1	2025-10-28	6.57
2356	15	2	2025-10-31	2.82
2357	15	1	2025-11-03	6.81
2358	15	1	2025-11-06	4.45
2359	15	1	2025-11-09	5.78
2360	15	1	2025-11-12	2.53
2361	15	1	2025-11-15	2.21
2362	15	2	2025-11-18	4.13
2363	15	1	2025-11-21	6.33
2364	15	1	2025-11-24	5.06
2365	15	1	2025-11-27	3.94
2366	15	2	2025-11-30	2.10
2367	15	1	2025-12-03	5.25
2368	15	1	2025-12-06	4.38
2369	15	2	2025-12-09	3.83
2370	15	1	2025-12-12	4.41
2371	15	2	2025-12-15	6.57
2372	15	2	2025-12-18	2.21
2373	15	2	2025-12-21	2.40
2374	15	1	2025-12-24	2.38
2375	15	1	2025-12-27	6.27
2376	15	1	2025-12-30	5.03
2377	15	1	2026-01-02	5.07
2378	15	1	2026-01-05	6.44
2379	15	1	2026-01-08	5.58
2380	15	1	2026-01-11	6.73
2381	15	1	2026-01-14	2.58
2382	15	2	2026-01-17	5.24
2383	15	2	2026-01-20	6.83
2384	15	2	2026-01-23	3.70
2385	15	1	2026-01-26	5.34
2386	15	1	2026-01-29	3.93
2387	15	1	2026-02-01	2.04
2388	15	1	2026-02-04	3.26
2389	15	2	2026-02-07	4.12
2390	15	2	2026-02-10	5.57
2391	15	2	2026-02-13	3.56
2392	15	1	2026-02-16	2.05
2393	15	2	2026-02-19	6.73
2394	15	1	2026-02-22	6.89
2395	15	2	2026-02-25	6.88
2396	15	2	2026-02-28	4.79
2397	15	2	2026-03-03	2.49
2398	15	1	2026-03-06	2.24
2399	15	2	2026-03-09	5.31
2400	15	2	2026-03-12	4.65
2401	15	1	2026-03-15	4.53
2402	15	1	2026-03-18	2.26
2403	15	2	2026-03-21	4.31
2404	15	2	2026-03-24	2.84
2405	15	1	2026-03-27	4.08
2406	15	1	2026-03-30	4.30
2407	15	1	2026-04-02	6.50
2408	15	2	2026-04-05	6.35
2409	15	1	2026-04-08	3.56
2410	15	2	2026-04-11	4.72
2411	15	1	2026-04-14	6.34
2412	15	1	2026-04-17	5.26
2413	15	1	2026-04-20	2.43
2414	15	2	2026-04-23	4.71
2415	15	2	2026-04-26	4.96
\.


--
-- TOC entry 5105 (class 0 OID 35453)
-- Dependencies: 264
-- Data for Name: financial_entries; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.financial_entries (id, entry_date, type, category, amount, tax_amount, reference_id, description) FROM stdin;
1	2025-01-01	I	venta leche	58.21	0.00	1	Venta leche
2	2025-01-02	I	venta leche	55.38	0.00	2	Venta leche
3	2025-01-03	I	venta leche	32.20	0.00	3	Venta leche
4	2025-01-04	I	venta leche	67.11	0.00	4	Venta leche
5	2025-01-05	I	venta leche	33.86	0.00	5	Venta leche
6	2025-01-06	I	venta leche	44.67	0.00	6	Venta leche
7	2025-01-07	I	venta leche	58.15	0.00	7	Venta leche
8	2025-01-08	I	venta leche	29.20	0.00	8	Venta leche
9	2025-01-09	I	venta leche	58.33	0.00	9	Venta leche
10	2025-01-10	I	venta leche	37.68	0.00	10	Venta leche
11	2025-01-11	I	venta leche	47.63	0.00	11	Venta leche
12	2025-01-12	I	venta leche	44.23	0.00	12	Venta leche
13	2025-01-13	I	venta leche	64.45	0.00	13	Venta leche
14	2025-01-14	I	venta leche	56.53	0.00	14	Venta leche
15	2025-01-15	I	venta leche	49.67	0.00	15	Venta leche
16	2025-01-16	I	venta leche	63.35	0.00	16	Venta leche
17	2025-01-17	I	venta leche	30.77	0.00	17	Venta leche
18	2025-01-18	I	venta leche	61.96	0.00	18	Venta leche
19	2025-01-19	I	venta leche	34.72	0.00	19	Venta leche
20	2025-01-20	I	venta leche	56.73	0.00	20	Venta leche
21	2025-01-21	I	venta leche	62.33	0.00	21	Venta leche
22	2025-01-22	I	venta leche	36.52	0.00	22	Venta leche
23	2025-01-23	I	venta leche	30.03	0.00	23	Venta leche
24	2025-01-24	I	venta leche	69.39	0.00	24	Venta leche
25	2025-01-25	I	venta leche	29.47	0.00	25	Venta leche
26	2025-01-26	I	venta leche	36.23	0.00	26	Venta leche
27	2025-01-27	I	venta leche	25.42	0.00	27	Venta leche
28	2025-01-28	I	venta leche	57.61	0.00	28	Venta leche
29	2025-01-29	I	venta leche	67.25	0.00	29	Venta leche
30	2025-01-30	I	venta leche	28.83	0.00	30	Venta leche
31	2025-01-31	I	venta leche	35.10	0.00	31	Venta leche
32	2025-02-01	I	venta leche	50.83	0.00	32	Venta leche
33	2025-02-02	I	venta leche	62.03	0.00	33	Venta leche
34	2025-02-03	I	venta leche	34.15	0.00	34	Venta leche
35	2025-02-04	I	venta leche	55.52	0.00	35	Venta leche
36	2025-02-05	I	venta leche	33.42	0.00	36	Venta leche
37	2025-02-06	I	venta leche	52.10	0.00	37	Venta leche
38	2025-02-07	I	venta leche	46.06	0.00	38	Venta leche
39	2025-02-08	I	venta leche	53.48	0.00	39	Venta leche
40	2025-02-09	I	venta leche	61.94	0.00	40	Venta leche
41	2025-02-10	I	venta leche	68.23	0.00	41	Venta leche
42	2025-02-11	I	venta leche	40.25	0.00	42	Venta leche
43	2025-02-12	I	venta leche	66.97	0.00	43	Venta leche
44	2025-02-13	I	venta leche	35.26	0.00	44	Venta leche
45	2025-02-14	I	venta leche	64.28	0.00	45	Venta leche
46	2025-02-15	I	venta leche	42.08	0.00	46	Venta leche
47	2025-02-16	I	venta leche	30.29	0.00	47	Venta leche
48	2025-02-17	I	venta leche	44.88	0.00	48	Venta leche
49	2025-02-18	I	venta leche	49.65	0.00	49	Venta leche
50	2025-02-19	I	venta leche	30.82	0.00	50	Venta leche
51	2025-02-20	I	venta leche	54.29	0.00	51	Venta leche
52	2025-02-21	I	venta leche	31.45	0.00	52	Venta leche
53	2025-02-22	I	venta leche	54.97	0.00	53	Venta leche
54	2025-02-23	I	venta leche	61.93	0.00	54	Venta leche
55	2025-02-24	I	venta leche	64.69	0.00	55	Venta leche
56	2025-02-25	I	venta leche	59.26	0.00	56	Venta leche
57	2025-02-26	I	venta leche	52.25	0.00	57	Venta leche
58	2025-02-27	I	venta leche	26.87	0.00	58	Venta leche
59	2025-02-28	I	venta leche	50.48	0.00	59	Venta leche
60	2025-03-01	I	venta leche	38.17	0.00	60	Venta leche
61	2025-03-02	I	venta leche	67.89	0.00	61	Venta leche
62	2025-03-03	I	venta leche	30.93	0.00	62	Venta leche
63	2025-03-04	I	venta leche	31.35	0.00	63	Venta leche
64	2025-03-05	I	venta leche	51.89	0.00	64	Venta leche
65	2025-03-06	I	venta leche	42.19	0.00	65	Venta leche
66	2025-03-07	I	venta leche	66.33	0.00	66	Venta leche
67	2025-03-08	I	venta leche	41.08	0.00	67	Venta leche
68	2025-03-09	I	venta leche	64.18	0.00	68	Venta leche
69	2025-03-10	I	venta leche	33.83	0.00	69	Venta leche
70	2025-03-11	I	venta leche	31.14	0.00	70	Venta leche
71	2025-03-12	I	venta leche	54.61	0.00	71	Venta leche
72	2025-03-13	I	venta leche	28.64	0.00	72	Venta leche
73	2025-03-14	I	venta leche	66.23	0.00	73	Venta leche
74	2025-03-15	I	venta leche	47.86	0.00	74	Venta leche
75	2025-03-16	I	venta leche	68.84	0.00	75	Venta leche
76	2025-03-17	I	venta leche	20.76	0.00	76	Venta leche
77	2025-03-18	I	venta leche	62.23	0.00	77	Venta leche
78	2025-03-19	I	venta leche	42.64	0.00	78	Venta leche
79	2025-03-20	I	venta leche	29.10	0.00	79	Venta leche
80	2025-03-21	I	venta leche	42.90	0.00	80	Venta leche
81	2025-03-22	I	venta leche	49.83	0.00	81	Venta leche
82	2025-03-23	I	venta leche	51.62	0.00	82	Venta leche
83	2025-03-24	I	venta leche	40.53	0.00	83	Venta leche
84	2025-03-25	I	venta leche	49.59	0.00	84	Venta leche
85	2025-03-26	I	venta leche	48.96	0.00	85	Venta leche
86	2025-03-27	I	venta leche	58.68	0.00	86	Venta leche
87	2025-03-28	I	venta leche	53.49	0.00	87	Venta leche
88	2025-03-29	I	venta leche	26.49	0.00	88	Venta leche
89	2025-03-30	I	venta leche	20.44	0.00	89	Venta leche
90	2025-03-31	I	venta leche	28.70	0.00	90	Venta leche
91	2025-04-01	I	venta leche	68.12	0.00	91	Venta leche
92	2025-04-02	I	venta leche	30.06	0.00	92	Venta leche
93	2025-04-03	I	venta leche	44.44	0.00	93	Venta leche
94	2025-04-04	I	venta leche	62.88	0.00	94	Venta leche
95	2025-04-05	I	venta leche	53.61	0.00	95	Venta leche
96	2025-04-06	I	venta leche	66.48	0.00	96	Venta leche
97	2025-04-07	I	venta leche	53.11	0.00	97	Venta leche
98	2025-04-08	I	venta leche	28.07	0.00	98	Venta leche
99	2025-04-09	I	venta leche	33.83	0.00	99	Venta leche
100	2025-04-10	I	venta leche	30.43	0.00	100	Venta leche
101	2025-04-11	I	venta leche	26.95	0.00	101	Venta leche
102	2025-04-12	I	venta leche	48.08	0.00	102	Venta leche
103	2025-04-13	I	venta leche	35.15	0.00	103	Venta leche
104	2025-04-14	I	venta leche	26.90	0.00	104	Venta leche
105	2025-04-15	I	venta leche	55.13	0.00	105	Venta leche
106	2025-04-16	I	venta leche	25.45	0.00	106	Venta leche
107	2025-04-17	I	venta leche	22.39	0.00	107	Venta leche
108	2025-04-18	I	venta leche	38.70	0.00	108	Venta leche
109	2025-04-19	I	venta leche	51.70	0.00	109	Venta leche
110	2025-04-20	I	venta leche	23.45	0.00	110	Venta leche
111	2025-04-21	I	venta leche	31.07	0.00	111	Venta leche
112	2025-04-22	I	venta leche	41.27	0.00	112	Venta leche
113	2025-04-23	I	venta leche	56.83	0.00	113	Venta leche
114	2025-04-24	I	venta leche	58.63	0.00	114	Venta leche
115	2025-04-25	I	venta leche	48.58	0.00	115	Venta leche
116	2025-04-26	I	venta leche	52.81	0.00	116	Venta leche
117	2025-04-27	I	venta leche	48.57	0.00	117	Venta leche
118	2025-04-28	I	venta leche	69.97	0.00	118	Venta leche
119	2025-04-29	I	venta leche	30.14	0.00	119	Venta leche
120	2025-04-30	I	venta leche	28.61	0.00	120	Venta leche
121	2025-05-01	I	venta leche	65.50	0.00	121	Venta leche
122	2025-05-02	I	venta leche	21.50	0.00	122	Venta leche
123	2025-05-03	I	venta leche	40.51	0.00	123	Venta leche
124	2025-05-04	I	venta leche	43.60	0.00	124	Venta leche
125	2025-05-05	I	venta leche	43.26	0.00	125	Venta leche
126	2025-05-06	I	venta leche	23.11	0.00	126	Venta leche
127	2025-05-07	I	venta leche	53.54	0.00	127	Venta leche
128	2025-05-08	I	venta leche	28.65	0.00	128	Venta leche
129	2025-05-09	I	venta leche	52.33	0.00	129	Venta leche
130	2025-05-10	I	venta leche	38.08	0.00	130	Venta leche
131	2025-05-11	I	venta leche	68.89	0.00	131	Venta leche
132	2025-05-12	I	venta leche	47.80	0.00	132	Venta leche
133	2025-05-13	I	venta leche	27.88	0.00	133	Venta leche
134	2025-05-14	I	venta leche	60.73	0.00	134	Venta leche
135	2025-05-15	I	venta leche	30.27	0.00	135	Venta leche
136	2025-05-16	I	venta leche	44.42	0.00	136	Venta leche
137	2025-05-17	I	venta leche	58.27	0.00	137	Venta leche
138	2025-05-18	I	venta leche	60.91	0.00	138	Venta leche
139	2025-05-19	I	venta leche	49.74	0.00	139	Venta leche
140	2025-05-20	I	venta leche	56.40	0.00	140	Venta leche
141	2025-05-21	I	venta leche	35.23	0.00	141	Venta leche
142	2025-05-22	I	venta leche	30.45	0.00	142	Venta leche
143	2025-05-23	I	venta leche	32.02	0.00	143	Venta leche
144	2025-05-24	I	venta leche	47.65	0.00	144	Venta leche
145	2025-05-25	I	venta leche	34.19	0.00	145	Venta leche
146	2025-05-26	I	venta leche	41.16	0.00	146	Venta leche
147	2025-05-27	I	venta leche	52.31	0.00	147	Venta leche
148	2025-05-28	I	venta leche	23.97	0.00	148	Venta leche
149	2025-05-29	I	venta leche	21.65	0.00	149	Venta leche
150	2025-05-30	I	venta leche	64.46	0.00	150	Venta leche
151	2025-05-31	I	venta leche	62.07	0.00	151	Venta leche
152	2025-06-01	I	venta leche	24.50	0.00	152	Venta leche
153	2025-06-02	I	venta leche	48.57	0.00	153	Venta leche
154	2025-06-03	I	venta leche	67.29	0.00	154	Venta leche
155	2025-06-04	I	venta leche	48.69	0.00	155	Venta leche
156	2025-06-05	I	venta leche	32.64	0.00	156	Venta leche
157	2025-06-06	I	venta leche	27.33	0.00	157	Venta leche
158	2025-06-07	I	venta leche	58.65	0.00	158	Venta leche
159	2025-06-08	I	venta leche	47.45	0.00	159	Venta leche
160	2025-06-09	I	venta leche	53.16	0.00	160	Venta leche
161	2025-06-10	I	venta leche	36.80	0.00	161	Venta leche
162	2025-06-11	I	venta leche	20.61	0.00	162	Venta leche
163	2025-06-12	I	venta leche	58.41	0.00	163	Venta leche
164	2025-06-13	I	venta leche	37.74	0.00	164	Venta leche
165	2025-06-14	I	venta leche	40.02	0.00	165	Venta leche
166	2025-06-15	I	venta leche	36.19	0.00	166	Venta leche
167	2025-06-16	I	venta leche	37.99	0.00	167	Venta leche
168	2025-06-17	I	venta leche	61.08	0.00	168	Venta leche
169	2025-06-18	I	venta leche	58.75	0.00	169	Venta leche
170	2025-06-19	I	venta leche	57.84	0.00	170	Venta leche
171	2025-06-20	I	venta leche	23.30	0.00	171	Venta leche
172	2025-06-21	I	venta leche	35.59	0.00	172	Venta leche
173	2025-06-22	I	venta leche	26.01	0.00	173	Venta leche
174	2025-06-23	I	venta leche	36.22	0.00	174	Venta leche
175	2025-06-24	I	venta leche	39.34	0.00	175	Venta leche
176	2025-06-25	I	venta leche	34.62	0.00	176	Venta leche
177	2025-06-26	I	venta leche	39.84	0.00	177	Venta leche
178	2025-06-27	I	venta leche	20.94	0.00	178	Venta leche
179	2025-06-28	I	venta leche	43.40	0.00	179	Venta leche
180	2025-06-29	I	venta leche	40.87	0.00	180	Venta leche
181	2025-06-30	I	venta leche	59.07	0.00	181	Venta leche
182	2025-07-01	I	venta leche	31.64	0.00	182	Venta leche
183	2025-07-02	I	venta leche	45.08	0.00	183	Venta leche
184	2025-07-03	I	venta leche	55.26	0.00	184	Venta leche
185	2025-07-04	I	venta leche	38.43	0.00	185	Venta leche
186	2025-07-05	I	venta leche	34.42	0.00	186	Venta leche
187	2025-07-06	I	venta leche	46.51	0.00	187	Venta leche
188	2025-07-07	I	venta leche	62.83	0.00	188	Venta leche
189	2025-07-08	I	venta leche	45.52	0.00	189	Venta leche
190	2025-07-09	I	venta leche	62.31	0.00	190	Venta leche
191	2025-07-10	I	venta leche	67.15	0.00	191	Venta leche
192	2025-07-11	I	venta leche	21.42	0.00	192	Venta leche
193	2025-07-12	I	venta leche	45.00	0.00	193	Venta leche
194	2025-07-13	I	venta leche	42.53	0.00	194	Venta leche
195	2025-07-14	I	venta leche	26.65	0.00	195	Venta leche
196	2025-07-15	I	venta leche	54.41	0.00	196	Venta leche
197	2025-07-16	I	venta leche	46.78	0.00	197	Venta leche
198	2025-07-17	I	venta leche	60.80	0.00	198	Venta leche
199	2025-07-18	I	venta leche	28.13	0.00	199	Venta leche
200	2025-07-19	I	venta leche	43.37	0.00	200	Venta leche
201	2025-07-20	I	venta leche	55.22	0.00	201	Venta leche
202	2025-07-21	I	venta leche	48.29	0.00	202	Venta leche
203	2025-07-22	I	venta leche	41.59	0.00	203	Venta leche
204	2025-07-23	I	venta leche	50.27	0.00	204	Venta leche
205	2025-07-24	I	venta leche	63.90	0.00	205	Venta leche
206	2025-07-25	I	venta leche	49.93	0.00	206	Venta leche
207	2025-07-26	I	venta leche	58.77	0.00	207	Venta leche
208	2025-07-27	I	venta leche	41.55	0.00	208	Venta leche
209	2025-07-28	I	venta leche	53.74	0.00	209	Venta leche
210	2025-07-29	I	venta leche	57.21	0.00	210	Venta leche
211	2025-07-30	I	venta leche	56.76	0.00	211	Venta leche
212	2025-07-31	I	venta leche	34.76	0.00	212	Venta leche
213	2025-08-01	I	venta leche	50.91	0.00	213	Venta leche
214	2025-08-02	I	venta leche	45.36	0.00	214	Venta leche
215	2025-08-03	I	venta leche	21.94	0.00	215	Venta leche
216	2025-08-04	I	venta leche	54.22	0.00	216	Venta leche
217	2025-08-05	I	venta leche	60.61	0.00	217	Venta leche
218	2025-08-06	I	venta leche	68.62	0.00	218	Venta leche
219	2025-08-07	I	venta leche	53.33	0.00	219	Venta leche
220	2025-08-08	I	venta leche	31.16	0.00	220	Venta leche
221	2025-08-09	I	venta leche	38.28	0.00	221	Venta leche
222	2025-08-10	I	venta leche	20.02	0.00	222	Venta leche
223	2025-08-11	I	venta leche	26.42	0.00	223	Venta leche
224	2025-08-12	I	venta leche	42.89	0.00	224	Venta leche
225	2025-08-13	I	venta leche	42.33	0.00	225	Venta leche
226	2025-08-14	I	venta leche	53.04	0.00	226	Venta leche
227	2025-08-15	I	venta leche	45.45	0.00	227	Venta leche
228	2025-08-16	I	venta leche	20.87	0.00	228	Venta leche
229	2025-08-17	I	venta leche	49.61	0.00	229	Venta leche
230	2025-08-18	I	venta leche	33.64	0.00	230	Venta leche
231	2025-08-19	I	venta leche	57.51	0.00	231	Venta leche
232	2025-08-20	I	venta leche	30.66	0.00	232	Venta leche
233	2025-08-21	I	venta leche	61.56	0.00	233	Venta leche
234	2025-08-22	I	venta leche	39.77	0.00	234	Venta leche
235	2025-08-23	I	venta leche	56.90	0.00	235	Venta leche
236	2025-08-24	I	venta leche	25.10	0.00	236	Venta leche
237	2025-08-25	I	venta leche	22.71	0.00	237	Venta leche
238	2025-08-26	I	venta leche	51.48	0.00	238	Venta leche
239	2025-08-27	I	venta leche	27.03	0.00	239	Venta leche
240	2025-08-28	I	venta leche	29.16	0.00	240	Venta leche
241	2025-08-29	I	venta leche	65.06	0.00	241	Venta leche
242	2025-08-30	I	venta leche	61.68	0.00	242	Venta leche
243	2025-08-31	I	venta leche	25.79	0.00	243	Venta leche
244	2025-09-01	I	venta leche	66.24	0.00	244	Venta leche
245	2025-09-02	I	venta leche	43.43	0.00	245	Venta leche
246	2025-09-03	I	venta leche	55.00	0.00	246	Venta leche
247	2025-09-04	I	venta leche	69.30	0.00	247	Venta leche
248	2025-09-05	I	venta leche	28.69	0.00	248	Venta leche
249	2025-09-06	I	venta leche	63.81	0.00	249	Venta leche
250	2025-09-07	I	venta leche	32.97	0.00	250	Venta leche
251	2025-09-08	I	venta leche	33.65	0.00	251	Venta leche
252	2025-09-09	I	venta leche	51.97	0.00	252	Venta leche
253	2025-09-10	I	venta leche	38.66	0.00	253	Venta leche
254	2025-09-11	I	venta leche	67.90	0.00	254	Venta leche
255	2025-09-12	I	venta leche	58.02	0.00	255	Venta leche
256	2025-09-13	I	venta leche	65.88	0.00	256	Venta leche
257	2025-09-14	I	venta leche	64.01	0.00	257	Venta leche
258	2025-09-15	I	venta leche	51.21	0.00	258	Venta leche
259	2025-09-16	I	venta leche	40.60	0.00	259	Venta leche
260	2025-09-17	I	venta leche	68.64	0.00	260	Venta leche
261	2025-09-18	I	venta leche	36.84	0.00	261	Venta leche
262	2025-09-19	I	venta leche	60.71	0.00	262	Venta leche
263	2025-09-20	I	venta leche	61.88	0.00	263	Venta leche
264	2025-09-21	I	venta leche	59.87	0.00	264	Venta leche
265	2025-09-22	I	venta leche	25.18	0.00	265	Venta leche
266	2025-09-23	I	venta leche	66.38	0.00	266	Venta leche
267	2025-09-24	I	venta leche	45.60	0.00	267	Venta leche
268	2025-09-25	I	venta leche	69.04	0.00	268	Venta leche
269	2025-09-26	I	venta leche	67.25	0.00	269	Venta leche
270	2025-09-27	I	venta leche	31.32	0.00	270	Venta leche
271	2025-09-28	I	venta leche	40.87	0.00	271	Venta leche
272	2025-09-29	I	venta leche	55.63	0.00	272	Venta leche
273	2025-09-30	I	venta leche	54.66	0.00	273	Venta leche
274	2025-10-01	I	venta leche	56.01	0.00	274	Venta leche
275	2025-10-02	I	venta leche	68.31	0.00	275	Venta leche
276	2025-10-03	I	venta leche	28.97	0.00	276	Venta leche
277	2025-10-04	I	venta leche	21.42	0.00	277	Venta leche
278	2025-10-05	I	venta leche	53.25	0.00	278	Venta leche
279	2025-10-06	I	venta leche	28.43	0.00	279	Venta leche
280	2025-10-07	I	venta leche	21.32	0.00	280	Venta leche
281	2025-10-08	I	venta leche	52.58	0.00	281	Venta leche
282	2025-10-09	I	venta leche	52.01	0.00	282	Venta leche
283	2025-10-10	I	venta leche	33.36	0.00	283	Venta leche
284	2025-10-11	I	venta leche	64.75	0.00	284	Venta leche
285	2025-10-12	I	venta leche	29.25	0.00	285	Venta leche
286	2025-10-13	I	venta leche	38.89	0.00	286	Venta leche
287	2025-10-14	I	venta leche	27.17	0.00	287	Venta leche
288	2025-10-15	I	venta leche	42.45	0.00	288	Venta leche
289	2025-10-16	I	venta leche	47.40	0.00	289	Venta leche
290	2025-10-17	I	venta leche	63.87	0.00	290	Venta leche
291	2025-10-18	I	venta leche	62.71	0.00	291	Venta leche
292	2025-10-19	I	venta leche	46.77	0.00	292	Venta leche
293	2025-10-20	I	venta leche	30.81	0.00	293	Venta leche
294	2025-10-21	I	venta leche	28.63	0.00	294	Venta leche
295	2025-10-22	I	venta leche	65.12	0.00	295	Venta leche
296	2025-10-23	I	venta leche	42.54	0.00	296	Venta leche
297	2025-10-24	I	venta leche	59.40	0.00	297	Venta leche
298	2025-10-25	I	venta leche	43.43	0.00	298	Venta leche
299	2025-10-26	I	venta leche	23.16	0.00	299	Venta leche
300	2025-10-27	I	venta leche	68.24	0.00	300	Venta leche
301	2025-10-28	I	venta leche	67.63	0.00	301	Venta leche
302	2025-10-29	I	venta leche	37.90	0.00	302	Venta leche
303	2025-10-30	I	venta leche	55.32	0.00	303	Venta leche
304	2025-10-31	I	venta leche	56.08	0.00	304	Venta leche
305	2025-11-01	I	venta leche	55.64	0.00	305	Venta leche
306	2025-11-02	I	venta leche	27.68	0.00	306	Venta leche
307	2025-11-03	I	venta leche	30.23	0.00	307	Venta leche
308	2025-11-04	I	venta leche	52.85	0.00	308	Venta leche
309	2025-11-05	I	venta leche	41.67	0.00	309	Venta leche
310	2025-11-06	I	venta leche	65.01	0.00	310	Venta leche
311	2025-11-07	I	venta leche	51.91	0.00	311	Venta leche
312	2025-11-08	I	venta leche	41.45	0.00	312	Venta leche
313	2025-11-09	I	venta leche	50.89	0.00	313	Venta leche
314	2025-11-10	I	venta leche	36.05	0.00	314	Venta leche
315	2025-11-11	I	venta leche	43.00	0.00	315	Venta leche
316	2025-11-12	I	venta leche	22.11	0.00	316	Venta leche
317	2025-11-13	I	venta leche	32.12	0.00	317	Venta leche
318	2025-11-14	I	venta leche	25.95	0.00	318	Venta leche
319	2025-11-15	I	venta leche	63.70	0.00	319	Venta leche
320	2025-11-16	I	venta leche	51.01	0.00	320	Venta leche
321	2025-11-17	I	venta leche	39.89	0.00	321	Venta leche
322	2025-11-18	I	venta leche	48.59	0.00	322	Venta leche
323	2025-11-19	I	venta leche	40.28	0.00	323	Venta leche
324	2025-11-20	I	venta leche	23.51	0.00	324	Venta leche
325	2025-11-21	I	venta leche	50.32	0.00	325	Venta leche
326	2025-11-22	I	venta leche	46.21	0.00	326	Venta leche
327	2025-11-23	I	venta leche	46.14	0.00	327	Venta leche
328	2025-11-24	I	venta leche	27.29	0.00	328	Venta leche
329	2025-11-25	I	venta leche	48.88	0.00	329	Venta leche
330	2025-11-26	I	venta leche	65.12	0.00	330	Venta leche
331	2025-11-27	I	venta leche	68.91	0.00	331	Venta leche
332	2025-11-28	I	venta leche	69.44	0.00	332	Venta leche
333	2025-11-29	I	venta leche	38.30	0.00	333	Venta leche
334	2025-11-30	I	venta leche	39.45	0.00	334	Venta leche
335	2025-12-01	I	venta leche	54.73	0.00	335	Venta leche
336	2025-12-02	I	venta leche	32.25	0.00	336	Venta leche
337	2025-12-03	I	venta leche	44.28	0.00	337	Venta leche
338	2025-12-04	I	venta leche	42.40	0.00	338	Venta leche
339	2025-12-05	I	venta leche	50.25	0.00	339	Venta leche
340	2025-12-06	I	venta leche	41.66	0.00	340	Venta leche
341	2025-12-07	I	venta leche	48.11	0.00	341	Venta leche
342	2025-12-08	I	venta leche	60.38	0.00	342	Venta leche
343	2025-12-09	I	venta leche	57.00	0.00	343	Venta leche
344	2025-12-10	I	venta leche	43.27	0.00	344	Venta leche
345	2025-12-11	I	venta leche	28.57	0.00	345	Venta leche
346	2025-12-12	I	venta leche	25.68	0.00	346	Venta leche
347	2025-12-13	I	venta leche	65.33	0.00	347	Venta leche
348	2025-12-14	I	venta leche	51.52	0.00	348	Venta leche
349	2025-12-15	I	venta leche	48.99	0.00	349	Venta leche
350	2025-12-16	I	venta leche	33.84	0.00	350	Venta leche
351	2025-12-17	I	venta leche	64.68	0.00	351	Venta leche
352	2025-12-18	I	venta leche	23.18	0.00	352	Venta leche
353	2025-12-19	I	venta leche	42.43	0.00	353	Venta leche
354	2025-12-20	I	venta leche	49.22	0.00	354	Venta leche
355	2025-12-21	I	venta leche	66.05	0.00	355	Venta leche
356	2025-12-22	I	venta leche	31.23	0.00	356	Venta leche
357	2025-12-23	I	venta leche	31.07	0.00	357	Venta leche
358	2025-12-24	I	venta leche	42.87	0.00	358	Venta leche
359	2025-12-25	I	venta leche	35.19	0.00	359	Venta leche
360	2025-12-26	I	venta leche	53.29	0.00	360	Venta leche
361	2025-12-27	I	venta leche	22.37	0.00	361	Venta leche
362	2025-12-28	I	venta leche	44.31	0.00	362	Venta leche
363	2025-12-29	I	venta leche	50.70	0.00	363	Venta leche
364	2025-12-30	I	venta leche	69.30	0.00	364	Venta leche
365	2025-12-31	I	venta leche	22.82	0.00	365	Venta leche
366	2026-01-01	I	venta leche	33.74	0.00	366	Venta leche
367	2026-01-02	I	venta leche	37.16	0.00	367	Venta leche
368	2026-01-03	I	venta leche	45.75	0.00	368	Venta leche
369	2026-01-04	I	venta leche	39.24	0.00	369	Venta leche
370	2026-01-05	I	venta leche	31.66	0.00	370	Venta leche
371	2026-01-06	I	venta leche	32.58	0.00	371	Venta leche
372	2026-01-07	I	venta leche	48.57	0.00	372	Venta leche
373	2026-01-08	I	venta leche	44.08	0.00	373	Venta leche
374	2026-01-09	I	venta leche	46.77	0.00	374	Venta leche
375	2026-01-10	I	venta leche	53.97	0.00	375	Venta leche
376	2026-01-11	I	venta leche	23.44	0.00	376	Venta leche
377	2026-01-12	I	venta leche	30.06	0.00	377	Venta leche
378	2026-01-13	I	venta leche	25.02	0.00	378	Venta leche
379	2026-01-14	I	venta leche	21.99	0.00	379	Venta leche
380	2026-01-15	I	venta leche	62.19	0.00	380	Venta leche
381	2026-01-16	I	venta leche	58.47	0.00	381	Venta leche
382	2026-01-17	I	venta leche	53.48	0.00	382	Venta leche
383	2026-01-18	I	venta leche	31.32	0.00	383	Venta leche
384	2026-01-19	I	venta leche	65.70	0.00	384	Venta leche
385	2026-01-20	I	venta leche	21.52	0.00	385	Venta leche
386	2026-01-21	I	venta leche	43.23	0.00	386	Venta leche
387	2026-01-22	I	venta leche	49.61	0.00	387	Venta leche
388	2026-01-23	I	venta leche	32.44	0.00	388	Venta leche
389	2026-01-24	I	venta leche	22.25	0.00	389	Venta leche
390	2026-01-25	I	venta leche	38.54	0.00	390	Venta leche
391	2026-01-26	I	venta leche	29.83	0.00	391	Venta leche
392	2026-01-27	I	venta leche	69.66	0.00	392	Venta leche
393	2026-01-28	I	venta leche	45.43	0.00	393	Venta leche
394	2026-01-29	I	venta leche	34.64	0.00	394	Venta leche
395	2026-01-30	I	venta leche	65.11	0.00	395	Venta leche
396	2026-01-31	I	venta leche	30.29	0.00	396	Venta leche
397	2026-02-01	I	venta leche	66.58	0.00	397	Venta leche
398	2026-02-02	I	venta leche	62.25	0.00	398	Venta leche
399	2026-02-03	I	venta leche	27.14	0.00	399	Venta leche
400	2026-02-04	I	venta leche	31.33	0.00	400	Venta leche
401	2026-02-05	I	venta leche	54.91	0.00	401	Venta leche
402	2026-02-06	I	venta leche	64.16	0.00	402	Venta leche
403	2026-02-07	I	venta leche	58.88	0.00	403	Venta leche
404	2026-02-08	I	venta leche	53.20	0.00	404	Venta leche
405	2026-02-09	I	venta leche	69.71	0.00	405	Venta leche
406	2026-02-10	I	venta leche	68.22	0.00	406	Venta leche
407	2026-02-11	I	venta leche	40.49	0.00	407	Venta leche
408	2026-02-12	I	venta leche	52.65	0.00	408	Venta leche
409	2026-02-13	I	venta leche	40.98	0.00	409	Venta leche
410	2026-02-14	I	venta leche	51.34	0.00	410	Venta leche
411	2026-02-15	I	venta leche	48.11	0.00	411	Venta leche
412	2026-02-16	I	venta leche	62.91	0.00	412	Venta leche
413	2026-02-17	I	venta leche	28.51	0.00	413	Venta leche
414	2026-02-18	I	venta leche	64.40	0.00	414	Venta leche
415	2026-02-19	I	venta leche	61.93	0.00	415	Venta leche
416	2026-02-20	I	venta leche	49.17	0.00	416	Venta leche
417	2026-02-21	I	venta leche	49.88	0.00	417	Venta leche
418	2026-02-22	I	venta leche	32.88	0.00	418	Venta leche
419	2026-02-23	I	venta leche	49.95	0.00	419	Venta leche
420	2026-02-24	I	venta leche	24.79	0.00	420	Venta leche
421	2026-02-25	I	venta leche	41.13	0.00	421	Venta leche
422	2026-02-26	I	venta leche	41.36	0.00	422	Venta leche
423	2026-02-27	I	venta leche	53.92	0.00	423	Venta leche
424	2026-02-28	I	venta leche	48.86	0.00	424	Venta leche
425	2026-03-01	I	venta leche	68.89	0.00	425	Venta leche
426	2026-03-02	I	venta leche	25.80	0.00	426	Venta leche
427	2026-03-03	I	venta leche	45.90	0.00	427	Venta leche
428	2026-03-04	I	venta leche	42.74	0.00	428	Venta leche
429	2026-03-05	I	venta leche	35.20	0.00	429	Venta leche
430	2026-03-06	I	venta leche	46.62	0.00	430	Venta leche
431	2026-03-07	I	venta leche	65.20	0.00	431	Venta leche
432	2026-03-08	I	venta leche	35.22	0.00	432	Venta leche
433	2026-03-09	I	venta leche	49.77	0.00	433	Venta leche
434	2026-03-10	I	venta leche	65.73	0.00	434	Venta leche
435	2026-03-11	I	venta leche	58.95	0.00	435	Venta leche
436	2026-03-12	I	venta leche	24.61	0.00	436	Venta leche
437	2026-03-13	I	venta leche	68.10	0.00	437	Venta leche
438	2026-03-14	I	venta leche	39.95	0.00	438	Venta leche
439	2026-03-15	I	venta leche	55.07	0.00	439	Venta leche
440	2026-03-16	I	venta leche	60.81	0.00	440	Venta leche
441	2026-03-17	I	venta leche	54.07	0.00	441	Venta leche
442	2026-03-18	I	venta leche	40.06	0.00	442	Venta leche
443	2026-03-19	I	venta leche	38.72	0.00	443	Venta leche
444	2026-03-20	I	venta leche	30.50	0.00	444	Venta leche
445	2026-03-21	I	venta leche	40.06	0.00	445	Venta leche
446	2026-03-22	I	venta leche	54.90	0.00	446	Venta leche
447	2026-03-23	I	venta leche	43.93	0.00	447	Venta leche
448	2026-03-24	I	venta leche	47.82	0.00	448	Venta leche
449	2026-03-25	I	venta leche	35.62	0.00	449	Venta leche
450	2026-03-26	I	venta leche	46.44	0.00	450	Venta leche
451	2026-03-27	I	venta leche	39.85	0.00	451	Venta leche
452	2026-03-28	I	venta leche	34.62	0.00	452	Venta leche
453	2026-03-29	I	venta leche	58.65	0.00	453	Venta leche
454	2026-03-30	I	venta leche	38.16	0.00	454	Venta leche
455	2026-03-31	I	venta leche	36.59	0.00	455	Venta leche
456	2026-04-01	I	venta leche	52.85	0.00	456	Venta leche
457	2026-04-02	I	venta leche	67.85	0.00	457	Venta leche
458	2026-04-03	I	venta leche	39.84	0.00	458	Venta leche
459	2026-04-04	I	venta leche	67.62	0.00	459	Venta leche
460	2026-04-05	I	venta leche	50.18	0.00	460	Venta leche
461	2026-04-06	I	venta leche	67.74	0.00	461	Venta leche
462	2026-04-07	I	venta leche	26.43	0.00	462	Venta leche
463	2026-04-08	I	venta leche	48.72	0.00	463	Venta leche
464	2026-04-09	I	venta leche	20.82	0.00	464	Venta leche
465	2026-04-10	I	venta leche	67.89	0.00	465	Venta leche
466	2026-04-11	I	venta leche	65.05	0.00	466	Venta leche
467	2026-04-12	I	venta leche	34.13	0.00	467	Venta leche
468	2026-04-13	I	venta leche	37.10	0.00	468	Venta leche
469	2026-04-14	I	venta leche	39.37	0.00	469	Venta leche
470	2026-04-15	I	venta leche	58.53	0.00	470	Venta leche
471	2026-04-16	I	venta leche	67.13	0.00	471	Venta leche
472	2026-04-17	I	venta leche	35.45	0.00	472	Venta leche
473	2026-04-18	I	venta leche	23.25	0.00	473	Venta leche
474	2026-04-19	I	venta leche	69.50	0.00	474	Venta leche
475	2026-04-20	I	venta leche	65.25	0.00	475	Venta leche
476	2026-04-21	I	venta leche	37.05	0.00	476	Venta leche
477	2026-04-22	I	venta leche	49.43	0.00	477	Venta leche
478	2026-04-23	I	venta leche	64.04	0.00	478	Venta leche
479	2026-04-24	I	venta leche	35.99	0.00	479	Venta leche
480	2026-04-25	I	venta leche	55.55	0.00	480	Venta leche
481	2026-04-26	I	venta leche	65.34	0.00	481	Venta leche
482	2026-04-27	I	venta leche	37.04	0.00	482	Venta leche
483	2026-04-28	I	venta leche	33.33	0.00	483	Venta leche
484	2025-01-26	G	salarios	405.00	0.00	1	Salario 1
485	2025-01-26	G	salarios	630.00	0.00	2	Salario 2
486	2025-01-26	G	salarios	540.00	0.00	3	Salario 3
487	2025-02-26	G	salarios	405.00	0.00	4	Salario 1
488	2025-02-26	G	salarios	630.00	0.00	5	Salario 2
489	2025-02-26	G	salarios	540.00	0.00	6	Salario 3
490	2025-03-26	G	salarios	405.00	0.00	7	Salario 1
491	2025-03-26	G	salarios	630.00	0.00	8	Salario 2
492	2025-03-26	G	salarios	540.00	0.00	9	Salario 3
493	2025-04-26	G	salarios	405.00	0.00	10	Salario 1
494	2025-04-26	G	salarios	630.00	0.00	11	Salario 2
495	2025-04-26	G	salarios	540.00	0.00	12	Salario 3
496	2025-05-26	G	salarios	405.00	0.00	13	Salario 1
497	2025-05-26	G	salarios	630.00	0.00	14	Salario 2
498	2025-05-26	G	salarios	540.00	0.00	15	Salario 3
499	2025-06-26	G	salarios	405.00	0.00	16	Salario 1
500	2025-06-26	G	salarios	630.00	0.00	17	Salario 2
501	2025-06-26	G	salarios	540.00	0.00	18	Salario 3
502	2025-07-26	G	salarios	405.00	0.00	19	Salario 1
503	2025-07-26	G	salarios	630.00	0.00	20	Salario 2
504	2025-07-26	G	salarios	540.00	0.00	21	Salario 3
505	2025-08-26	G	salarios	405.00	0.00	22	Salario 1
506	2025-08-26	G	salarios	630.00	0.00	23	Salario 2
507	2025-08-26	G	salarios	540.00	0.00	24	Salario 3
508	2025-09-26	G	salarios	405.00	0.00	25	Salario 1
509	2025-09-26	G	salarios	630.00	0.00	26	Salario 2
510	2025-09-26	G	salarios	540.00	0.00	27	Salario 3
511	2025-10-26	G	salarios	405.00	0.00	28	Salario 1
512	2025-10-26	G	salarios	630.00	0.00	29	Salario 2
513	2025-10-26	G	salarios	540.00	0.00	30	Salario 3
514	2025-11-26	G	salarios	405.00	0.00	31	Salario 1
515	2025-11-26	G	salarios	630.00	0.00	32	Salario 2
516	2025-11-26	G	salarios	540.00	0.00	33	Salario 3
517	2025-12-26	G	salarios	405.00	0.00	34	Salario 1
518	2025-12-26	G	salarios	630.00	0.00	35	Salario 2
519	2025-12-26	G	salarios	540.00	0.00	36	Salario 3
520	2026-01-26	G	salarios	405.00	0.00	37	Salario 1
521	2026-01-26	G	salarios	630.00	0.00	38	Salario 2
522	2026-01-26	G	salarios	540.00	0.00	39	Salario 3
523	2026-02-26	G	salarios	405.00	0.00	40	Salario 1
524	2026-02-26	G	salarios	630.00	0.00	41	Salario 2
525	2026-02-26	G	salarios	540.00	0.00	42	Salario 3
526	2026-03-26	G	salarios	405.00	0.00	43	Salario 1
527	2026-03-26	G	salarios	630.00	0.00	44	Salario 2
528	2026-03-26	G	salarios	540.00	0.00	45	Salario 3
529	2026-04-26	G	salarios	405.00	0.00	46	Salario 1
530	2026-04-26	G	salarios	630.00	0.00	47	Salario 2
531	2026-04-26	G	salarios	540.00	0.00	48	Salario 3
532	2025-01-01	G	compra insumos	100.17	0.00	1	Orden compra 1
533	2025-03-01	G	compra insumos	130.15	0.00	2	Orden compra 2
534	2025-05-01	G	compra insumos	156.87	0.00	3	Orden compra 3
535	2025-07-01	G	compra insumos	58.01	0.00	4	Orden compra 4
536	2025-09-01	G	compra insumos	194.05	0.00	5	Orden compra 5
537	2025-11-01	G	compra insumos	54.74	0.00	6	Orden compra 6
538	2026-01-01	G	compra insumos	234.09	0.00	7	Orden compra 7
539	2026-03-01	G	compra insumos	162.88	0.00	8	Orden compra 8
\.


--
-- TOC entry 5075 (class 0 OID 35215)
-- Dependencies: 234
-- Data for Name: food_catalog; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.food_catalog (id, name, food_type, cost_per_kg, protein_pct, stock_kg) FROM stdin;
1	Pasto natural	forraje	0.15	8.50	5000.00
2	Concentrado bovino lechero	concentrado	0.45	18.00	2000.00
3	Concentrado engorde	concentrado	0.40	16.00	1500.00
4	Suplemento mineral	suplemento	0.80	5.00	300.00
5	Heno de alfalfa	forraje	0.25	15.00	1000.00
6	Maíz molido	concentrado	0.30	9.00	3000.00
7	Alimento balanceado aves	concentrado	0.50	20.00	800.00
\.


--
-- TOC entry 5073 (class 0 OID 35195)
-- Dependencies: 232
-- Data for Name: health_events; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.health_events (id, animal_id, batch_id, event_type, event_date, product_used, dosage, notes) FROM stdin;
1	\N	\N	vacunación	2025-01-15	Vacuna triple bovina	\N	Campaña semestral
2	\N	\N	vacunación	2025-07-15	Vacuna triple bovina	\N	Campaña semestral
3	\N	\N	vacunación	2026-01-15	Vacuna triple bovina	\N	Campaña semestral
\.


--
-- TOC entry 5083 (class 0 OID 35275)
-- Dependencies: 242
-- Data for Name: market_prices; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.market_prices (id, product_type, price_per_unit, unit, price_date, market, notes) FROM stdin;
1	leche	0.58	litro	2025-01-01	Mercado Central	\N
2	leche	0.65	litro	2025-02-01	Mercado Central	\N
3	leche	0.61	litro	2025-03-01	Mercado Central	\N
4	leche	0.62	litro	2025-04-01	Mercado Central	\N
5	leche	0.61	litro	2025-05-01	Mercado Central	\N
6	leche	0.59	litro	2025-06-01	Mercado Central	\N
7	leche	0.62	litro	2025-07-01	Mercado Central	\N
8	leche	0.62	litro	2025-08-01	Mercado Central	\N
9	leche	0.56	litro	2025-09-01	Mercado Central	\N
10	leche	0.63	litro	2025-10-01	Mercado Central	\N
11	leche	0.61	litro	2025-11-01	Mercado Central	\N
12	leche	0.56	litro	2025-12-01	Mercado Central	\N
13	leche	0.63	litro	2026-01-01	Mercado Central	\N
14	leche	0.60	litro	2026-02-01	Mercado Central	\N
15	leche	0.57	litro	2026-03-01	Mercado Central	\N
16	leche	0.55	litro	2026-04-01	Mercado Central	\N
17	carne	4.62	kg	2025-01-01	Mercado Central	\N
18	carne	4.86	kg	2025-02-01	Mercado Central	\N
19	carne	4.57	kg	2025-03-01	Mercado Central	\N
20	carne	4.76	kg	2025-04-01	Mercado Central	\N
21	carne	4.80	kg	2025-05-01	Mercado Central	\N
22	carne	4.72	kg	2025-06-01	Mercado Central	\N
23	carne	4.86	kg	2025-07-01	Mercado Central	\N
24	carne	4.66	kg	2025-08-01	Mercado Central	\N
25	carne	4.93	kg	2025-09-01	Mercado Central	\N
26	carne	4.67	kg	2025-10-01	Mercado Central	\N
27	carne	4.56	kg	2025-11-01	Mercado Central	\N
28	carne	4.84	kg	2025-12-01	Mercado Central	\N
29	carne	4.52	kg	2026-01-01	Mercado Central	\N
30	carne	4.53	kg	2026-02-01	Mercado Central	\N
31	carne	4.53	kg	2026-03-01	Mercado Central	\N
32	carne	4.96	kg	2026-04-01	Mercado Central	\N
33	huevo	0.12	unidad	2025-01-01	Mercado Central	\N
34	huevo	0.13	unidad	2025-02-01	Mercado Central	\N
35	huevo	0.13	unidad	2025-03-01	Mercado Central	\N
36	huevo	0.13	unidad	2025-04-01	Mercado Central	\N
37	huevo	0.14	unidad	2025-05-01	Mercado Central	\N
38	huevo	0.12	unidad	2025-06-01	Mercado Central	\N
39	huevo	0.14	unidad	2025-07-01	Mercado Central	\N
40	huevo	0.12	unidad	2025-08-01	Mercado Central	\N
41	huevo	0.13	unidad	2025-09-01	Mercado Central	\N
42	huevo	0.12	unidad	2025-10-01	Mercado Central	\N
43	huevo	0.14	unidad	2025-11-01	Mercado Central	\N
44	huevo	0.13	unidad	2025-12-01	Mercado Central	\N
45	huevo	0.13	unidad	2026-01-01	Mercado Central	\N
46	huevo	0.12	unidad	2026-02-01	Mercado Central	\N
47	huevo	0.13	unidad	2026-03-01	Mercado Central	\N
48	huevo	0.14	unidad	2026-04-01	Mercado Central	\N
\.


--
-- TOC entry 5067 (class 0 OID 35160)
-- Dependencies: 226
-- Data for Name: milk_production; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.milk_production (id, animal_id, production_date, quantity_liters) FROM stdin;
1	1	2025-01-01	32.52
2	1	2025-01-02	12.99
3	1	2025-01-03	33.25
4	1	2025-01-04	20.07
5	1	2025-01-05	20.69
6	1	2025-01-06	15.07
7	1	2025-01-07	21.49
8	1	2025-01-08	22.40
9	1	2025-01-09	18.73
10	1	2025-01-10	22.00
11	1	2025-01-11	33.86
12	1	2025-01-12	22.18
13	1	2025-01-13	11.05
14	1	2025-01-14	26.66
15	1	2025-01-15	23.01
16	1	2025-01-16	17.40
17	1	2025-01-17	17.52
18	1	2025-01-18	19.73
19	1	2025-01-19	15.32
20	1	2025-01-20	21.21
21	1	2025-01-21	31.20
22	1	2025-01-22	20.66
23	1	2025-01-23	11.62
24	1	2025-01-24	28.78
25	1	2025-01-25	28.23
26	1	2025-01-26	33.62
27	1	2025-01-27	28.56
28	1	2025-01-28	24.01
29	1	2025-01-29	30.23
30	1	2025-01-30	12.10
31	1	2025-01-31	27.12
32	1	2025-02-01	30.21
33	1	2025-02-02	19.03
34	1	2025-02-03	11.92
35	1	2025-02-04	27.91
36	1	2025-02-05	12.26
37	1	2025-02-06	17.17
38	1	2025-02-07	17.54
39	1	2025-02-08	23.95
40	1	2025-02-09	34.01
41	1	2025-02-10	18.89
42	1	2025-02-11	18.37
43	1	2025-02-12	31.13
44	1	2025-02-13	13.51
45	1	2025-02-14	12.99
46	1	2025-02-15	15.64
47	1	2025-02-16	18.53
48	1	2025-02-17	11.79
49	1	2025-02-18	34.23
50	1	2025-02-19	20.41
51	1	2025-02-20	16.86
52	1	2025-02-21	14.74
53	1	2025-02-22	18.40
54	1	2025-02-23	20.11
55	1	2025-02-24	27.18
56	1	2025-02-25	22.32
57	1	2025-02-26	14.46
58	1	2025-02-27	18.56
59	1	2025-02-28	18.06
60	1	2025-03-01	17.61
61	1	2025-03-02	25.88
62	1	2025-03-03	13.49
63	1	2025-03-04	34.02
64	1	2025-03-05	16.68
65	1	2025-03-06	23.03
66	1	2025-03-07	18.55
67	1	2025-03-08	33.58
68	1	2025-03-09	24.42
69	1	2025-03-10	20.13
70	1	2025-03-11	21.76
71	1	2025-03-12	20.09
72	1	2025-03-13	18.12
73	1	2025-03-14	15.88
74	1	2025-03-15	33.83
75	1	2025-03-16	33.30
76	1	2025-03-17	14.31
77	1	2025-03-18	19.37
78	1	2025-03-19	19.81
79	1	2025-03-20	31.47
80	1	2025-03-21	27.06
81	1	2025-03-22	25.29
82	1	2025-03-23	24.69
83	1	2025-03-24	20.85
84	1	2025-03-25	32.80
85	1	2025-03-26	29.53
86	1	2025-03-27	24.33
87	1	2025-03-28	28.28
88	1	2025-03-29	31.49
89	1	2025-03-30	29.63
90	1	2025-03-31	13.40
91	1	2025-04-01	11.26
92	1	2025-04-02	15.74
93	1	2025-04-03	19.55
94	1	2025-04-04	21.26
95	1	2025-04-05	30.01
96	1	2025-04-06	10.60
97	1	2025-04-07	26.98
98	1	2025-04-08	24.82
99	1	2025-04-09	18.68
100	1	2025-04-10	29.93
101	1	2025-04-11	23.89
102	1	2025-04-12	31.29
103	1	2025-04-13	33.55
104	1	2025-04-14	30.92
105	1	2025-04-15	23.78
106	1	2025-04-16	16.28
107	1	2025-04-17	31.76
108	1	2025-04-18	15.88
109	1	2025-04-19	14.04
110	1	2025-04-20	34.20
111	1	2025-04-21	33.72
112	1	2025-04-22	18.92
113	1	2025-04-23	20.73
114	1	2025-04-24	24.30
115	1	2025-04-25	28.43
116	1	2025-04-26	34.67
117	1	2025-04-27	30.04
118	1	2025-04-28	24.16
119	1	2025-04-29	32.43
120	1	2025-04-30	19.82
121	1	2025-05-01	17.98
122	1	2025-05-02	30.47
123	1	2025-05-03	32.41
124	1	2025-05-04	19.64
125	1	2025-05-05	26.64
126	1	2025-05-06	12.85
127	1	2025-05-07	30.17
128	1	2025-05-08	24.75
129	1	2025-05-09	12.87
130	1	2025-05-10	17.33
131	1	2025-05-11	17.08
132	1	2025-05-12	31.46
133	1	2025-05-13	17.60
134	1	2025-05-14	25.49
135	1	2025-05-15	30.49
136	1	2025-05-16	29.14
137	1	2025-05-17	28.49
138	1	2025-05-18	29.05
139	1	2025-05-19	29.44
140	1	2025-05-20	34.42
141	1	2025-05-21	27.40
142	1	2025-05-22	16.18
143	1	2025-05-23	23.34
144	1	2025-05-24	29.05
145	1	2025-05-25	22.18
146	1	2025-05-26	32.26
147	1	2025-05-27	15.54
148	1	2025-05-28	19.48
149	1	2025-05-29	16.43
150	1	2025-05-30	17.86
151	1	2025-05-31	13.20
152	1	2025-06-01	31.90
153	1	2025-06-02	31.03
154	1	2025-06-03	31.05
155	1	2025-06-04	25.05
156	1	2025-06-05	24.69
157	1	2025-06-06	33.24
158	1	2025-06-07	11.25
159	1	2025-06-08	19.67
160	1	2025-06-09	10.05
161	1	2025-06-10	33.70
162	1	2025-06-11	31.41
163	1	2025-06-12	17.44
164	1	2025-06-13	30.06
165	1	2025-06-14	19.76
166	1	2025-06-15	21.50
167	1	2025-06-16	24.08
168	1	2025-06-17	22.52
169	1	2025-06-18	15.97
170	1	2025-06-19	16.35
171	1	2025-06-20	11.04
172	1	2025-06-21	22.53
173	1	2025-06-22	32.52
174	1	2025-06-23	12.29
175	1	2025-06-24	18.64
176	1	2025-06-25	30.95
177	1	2025-06-26	33.86
178	1	2025-06-27	18.54
179	1	2025-06-28	18.27
180	1	2025-06-29	15.87
181	1	2025-06-30	13.47
182	1	2025-07-01	34.40
183	1	2025-07-02	15.11
184	1	2025-07-03	13.91
185	1	2025-07-04	17.85
186	1	2025-07-05	10.30
187	1	2025-07-06	34.12
188	1	2025-07-07	18.31
189	1	2025-07-08	27.15
190	1	2025-07-09	25.31
191	1	2025-07-10	27.97
192	1	2025-07-11	28.00
193	1	2025-07-12	16.91
194	1	2025-07-13	17.05
195	1	2025-07-14	11.67
196	1	2025-07-15	27.98
197	1	2025-07-16	15.48
198	1	2025-07-17	19.69
199	1	2025-07-18	26.91
200	1	2025-07-19	27.30
201	1	2025-07-20	11.65
202	1	2025-07-21	32.17
203	1	2025-07-22	19.98
204	1	2025-07-23	15.67
205	1	2025-07-24	12.31
206	1	2025-07-25	33.05
207	1	2025-07-26	34.44
208	1	2025-07-27	31.28
209	1	2025-07-28	20.60
210	1	2025-07-29	16.55
211	1	2025-07-30	16.23
212	1	2025-07-31	31.14
213	1	2025-08-01	25.27
214	1	2025-08-02	10.93
215	1	2025-08-03	10.96
216	1	2025-08-04	19.94
217	1	2025-08-05	17.20
218	1	2025-08-06	27.65
219	1	2025-08-07	12.43
220	1	2025-08-08	20.10
221	1	2025-08-09	10.71
222	1	2025-08-10	31.13
223	1	2025-08-11	13.39
224	1	2025-08-12	25.62
225	1	2025-08-13	27.78
226	1	2025-08-14	17.15
227	1	2025-08-15	15.80
228	1	2025-08-16	21.11
229	1	2025-08-17	12.00
230	1	2025-08-18	23.53
231	1	2025-08-19	17.89
232	1	2025-08-20	16.43
233	1	2025-08-21	29.60
234	1	2025-08-22	18.82
235	1	2025-08-23	24.22
236	1	2025-08-24	13.24
237	1	2025-08-25	17.15
238	1	2025-08-26	29.03
239	1	2025-08-27	14.94
240	1	2025-08-28	33.32
241	1	2025-08-29	13.90
242	1	2025-08-30	33.60
243	1	2025-08-31	13.36
244	1	2025-09-01	16.39
245	1	2025-09-02	19.50
246	1	2025-09-03	33.44
247	1	2025-09-04	32.88
248	1	2025-09-05	27.80
249	1	2025-09-06	30.98
250	1	2025-09-07	13.86
251	1	2025-09-08	31.54
252	1	2025-09-09	10.94
253	1	2025-09-10	31.44
254	1	2025-09-11	26.20
255	1	2025-09-12	24.21
256	1	2025-09-13	21.92
257	1	2025-09-14	18.96
258	1	2025-09-15	17.50
259	1	2025-09-16	28.23
260	1	2025-09-17	26.52
261	1	2025-09-18	29.44
262	1	2025-09-19	24.93
263	1	2025-09-20	15.07
264	1	2025-09-21	22.64
265	1	2025-09-22	34.39
266	1	2025-09-23	19.57
267	1	2025-09-24	31.21
268	1	2025-09-25	21.29
269	1	2025-09-26	26.17
270	1	2025-09-27	14.81
271	1	2025-09-28	13.62
272	1	2025-09-29	33.80
273	1	2025-09-30	16.15
274	1	2025-10-01	27.88
275	1	2025-10-02	17.27
276	1	2025-10-03	34.22
277	1	2025-10-04	33.02
278	1	2025-10-05	12.17
279	1	2025-10-06	14.37
280	1	2025-10-07	29.82
281	1	2025-10-08	26.70
282	1	2025-10-09	20.03
283	1	2025-10-10	31.72
284	1	2025-10-11	23.87
285	1	2025-10-12	30.74
286	1	2025-10-13	34.01
287	1	2025-10-14	20.72
288	1	2025-10-15	26.85
289	1	2025-10-16	33.55
290	1	2025-10-17	17.18
291	1	2025-10-18	30.06
292	1	2025-10-19	22.77
293	1	2025-10-20	25.17
294	1	2025-10-21	25.57
295	1	2025-10-22	22.51
296	1	2025-10-23	30.84
297	1	2025-10-24	28.65
298	1	2025-10-25	27.65
299	1	2025-10-26	30.85
300	1	2025-10-27	12.13
301	1	2025-10-28	22.68
302	1	2025-10-29	16.49
303	1	2025-10-30	28.82
304	1	2025-10-31	16.77
305	1	2025-11-01	16.10
306	1	2025-11-02	22.22
307	1	2025-11-03	32.80
308	1	2025-11-04	32.64
309	1	2025-11-05	32.22
310	1	2025-11-06	16.78
311	1	2025-11-07	11.54
312	1	2025-11-08	29.81
313	1	2025-11-09	28.50
314	1	2025-11-10	24.57
315	1	2025-11-11	17.32
316	1	2025-11-12	13.12
317	1	2025-11-13	30.40
318	1	2025-11-14	29.36
319	1	2025-11-15	16.29
320	1	2025-11-16	13.87
321	1	2025-11-17	19.64
322	1	2025-11-18	22.28
323	1	2025-11-19	18.25
324	1	2025-11-20	21.70
325	1	2025-11-21	11.30
326	1	2025-11-22	14.97
327	1	2025-11-23	17.28
328	1	2025-11-24	27.04
329	1	2025-11-25	11.41
330	1	2025-11-26	29.55
331	1	2025-11-27	11.42
332	1	2025-11-28	30.70
333	1	2025-11-29	26.87
334	1	2025-11-30	11.72
335	1	2025-12-01	33.37
336	1	2025-12-02	17.42
337	1	2025-12-03	32.62
338	1	2025-12-04	26.97
339	1	2025-12-05	25.86
340	1	2025-12-06	24.42
341	1	2025-12-07	15.93
342	1	2025-12-08	28.36
343	1	2025-12-09	16.13
344	1	2025-12-10	33.77
345	1	2025-12-11	26.02
346	1	2025-12-12	14.92
347	1	2025-12-13	23.73
348	1	2025-12-14	28.61
349	1	2025-12-15	23.76
350	1	2025-12-16	32.42
351	1	2025-12-17	23.04
352	1	2025-12-18	25.04
353	1	2025-12-19	29.02
354	1	2025-12-20	31.24
355	1	2025-12-21	15.09
356	1	2025-12-22	11.17
357	1	2025-12-23	13.67
358	1	2025-12-24	16.02
359	1	2025-12-25	34.02
360	1	2025-12-26	14.53
361	1	2025-12-27	17.09
362	1	2025-12-28	26.69
363	1	2025-12-29	22.79
364	1	2025-12-30	17.53
365	1	2025-12-31	22.48
366	1	2026-01-01	20.32
367	1	2026-01-02	10.79
368	1	2026-01-03	21.06
369	1	2026-01-04	32.19
370	1	2026-01-05	26.55
371	1	2026-01-06	25.16
372	1	2026-01-07	23.61
373	1	2026-01-08	10.21
374	1	2026-01-09	32.29
375	1	2026-01-10	17.91
376	1	2026-01-11	24.31
377	1	2026-01-12	12.81
378	1	2026-01-13	25.98
379	1	2026-01-14	18.64
380	1	2026-01-15	33.71
381	1	2026-01-16	16.98
382	1	2026-01-17	20.51
383	1	2026-01-18	22.53
384	1	2026-01-19	32.58
385	1	2026-01-20	27.64
386	1	2026-01-21	10.67
387	1	2026-01-22	13.93
388	1	2026-01-23	21.25
389	1	2026-01-24	29.81
390	1	2026-01-25	16.34
391	1	2026-01-26	29.33
392	1	2026-01-27	16.92
393	1	2026-01-28	22.36
394	1	2026-01-29	28.47
395	1	2026-01-30	16.45
396	1	2026-01-31	28.90
397	1	2026-02-01	10.65
398	1	2026-02-02	14.64
399	1	2026-02-03	25.01
400	1	2026-02-04	26.86
401	1	2026-02-05	33.54
402	1	2026-02-06	22.65
403	1	2026-02-07	22.34
404	1	2026-02-08	30.87
405	1	2026-02-09	10.55
406	1	2026-02-10	29.43
407	1	2026-02-11	19.92
408	1	2026-02-12	30.77
409	1	2026-02-13	11.35
410	1	2026-02-14	24.26
411	1	2026-02-15	20.65
412	1	2026-02-16	26.77
413	1	2026-02-17	32.69
414	1	2026-02-18	30.82
415	1	2026-02-19	14.75
416	1	2026-02-20	32.38
417	1	2026-02-21	13.56
418	1	2026-02-22	31.20
419	1	2026-02-23	32.54
420	1	2026-02-24	16.28
421	1	2026-02-25	17.05
422	1	2026-02-26	19.83
423	1	2026-02-27	34.49
424	1	2026-02-28	24.00
425	1	2026-03-01	24.48
426	1	2026-03-02	19.32
427	1	2026-03-03	30.85
428	1	2026-03-04	19.96
429	1	2026-03-05	21.03
430	1	2026-03-06	26.14
431	1	2026-03-07	18.59
432	1	2026-03-08	20.76
433	1	2026-03-09	20.51
434	1	2026-03-10	15.81
435	1	2026-03-11	33.98
436	1	2026-03-12	16.64
437	1	2026-03-13	15.44
438	1	2026-03-14	23.72
439	1	2026-03-15	29.71
440	1	2026-03-16	33.97
441	1	2026-03-17	32.65
442	1	2026-03-18	31.37
443	1	2026-03-19	26.78
444	1	2026-03-20	33.32
445	1	2026-03-21	26.39
446	1	2026-03-22	17.75
447	1	2026-03-23	25.71
448	1	2026-03-24	15.19
449	1	2026-03-25	33.78
450	1	2026-03-26	12.52
451	1	2026-03-27	11.80
452	1	2026-03-28	20.01
453	1	2026-03-29	11.98
454	1	2026-03-30	26.18
455	1	2026-03-31	29.64
456	1	2026-04-01	18.14
457	1	2026-04-02	29.52
458	1	2026-04-03	11.16
459	1	2026-04-04	31.93
460	1	2026-04-05	12.16
461	1	2026-04-06	10.01
462	1	2026-04-07	21.26
463	1	2026-04-08	29.37
464	1	2026-04-09	16.49
465	1	2026-04-10	20.81
466	1	2026-04-11	15.62
467	1	2026-04-12	12.74
468	1	2026-04-13	33.45
469	1	2026-04-14	33.13
470	1	2026-04-15	18.06
471	1	2026-04-16	28.97
472	1	2026-04-17	15.66
473	1	2026-04-18	11.90
474	1	2026-04-19	32.65
475	1	2026-04-20	16.34
476	1	2026-04-21	15.83
477	1	2026-04-22	28.00
478	1	2026-04-23	15.19
479	1	2026-04-24	32.66
480	1	2026-04-25	18.37
481	1	2026-04-26	31.00
482	1	2026-04-27	13.33
483	1	2026-04-28	31.56
484	2	2025-01-01	30.49
485	2	2025-01-02	11.97
486	2	2025-01-03	26.28
487	2	2025-01-04	19.45
488	2	2025-01-05	17.48
489	2	2025-01-06	33.93
490	2	2025-01-07	27.14
491	2	2025-01-08	21.08
492	2	2025-01-09	14.82
493	2	2025-01-10	32.19
494	2	2025-01-11	34.94
495	2	2025-01-12	29.03
496	2	2025-01-13	23.02
497	2	2025-01-14	32.07
498	2	2025-01-15	23.89
499	2	2025-01-16	18.80
500	2	2025-01-17	26.70
501	2	2025-01-18	22.89
502	2	2025-01-19	18.00
503	2	2025-01-20	30.08
504	2	2025-01-21	12.20
505	2	2025-01-22	20.92
506	2	2025-01-23	15.96
507	2	2025-01-24	26.03
508	2	2025-01-25	16.01
509	2	2025-01-26	14.21
510	2	2025-01-27	21.62
511	2	2025-01-28	27.00
512	2	2025-01-29	34.94
513	2	2025-01-30	10.65
514	2	2025-01-31	20.64
515	2	2025-02-01	11.50
516	2	2025-02-02	18.03
517	2	2025-02-03	28.26
518	2	2025-02-04	29.33
519	2	2025-02-05	23.33
520	2	2025-02-06	11.04
521	2	2025-02-07	27.86
522	2	2025-02-08	33.87
523	2	2025-02-09	21.60
524	2	2025-02-10	14.05
525	2	2025-02-11	25.72
526	2	2025-02-12	19.57
527	2	2025-02-13	27.18
528	2	2025-02-14	14.64
529	2	2025-02-15	26.55
530	2	2025-02-16	23.21
531	2	2025-02-17	31.78
532	2	2025-02-18	29.86
533	2	2025-02-19	11.66
534	2	2025-02-20	11.76
535	2	2025-02-21	22.16
536	2	2025-02-22	31.28
537	2	2025-02-23	34.19
538	2	2025-02-24	30.37
539	2	2025-02-25	20.74
540	2	2025-02-26	23.42
541	2	2025-02-27	31.58
542	2	2025-02-28	14.40
543	2	2025-03-01	25.92
544	2	2025-03-02	21.02
545	2	2025-03-03	18.28
546	2	2025-03-04	11.39
547	2	2025-03-05	13.25
548	2	2025-03-06	33.94
549	2	2025-03-07	25.05
550	2	2025-03-08	22.64
551	2	2025-03-09	34.95
552	2	2025-03-10	14.38
553	2	2025-03-11	23.97
554	2	2025-03-12	32.17
555	2	2025-03-13	21.81
556	2	2025-03-14	26.73
557	2	2025-03-15	25.88
558	2	2025-03-16	16.09
559	2	2025-03-17	25.43
560	2	2025-03-18	10.95
561	2	2025-03-19	15.93
562	2	2025-03-20	13.56
563	2	2025-03-21	32.77
564	2	2025-03-22	24.58
565	2	2025-03-23	32.99
566	2	2025-03-24	18.05
567	2	2025-03-25	12.80
568	2	2025-03-26	21.13
569	2	2025-03-27	22.69
570	2	2025-03-28	24.64
571	2	2025-03-29	14.56
572	2	2025-03-30	25.85
573	2	2025-03-31	22.16
574	2	2025-04-01	13.93
575	2	2025-04-02	32.05
576	2	2025-04-03	22.67
577	2	2025-04-04	11.63
578	2	2025-04-05	33.68
579	2	2025-04-06	10.05
580	2	2025-04-07	10.32
581	2	2025-04-08	32.72
582	2	2025-04-09	25.25
583	2	2025-04-10	12.47
584	2	2025-04-11	33.71
585	2	2025-04-12	18.46
586	2	2025-04-13	19.98
587	2	2025-04-14	28.92
588	2	2025-04-15	11.31
589	2	2025-04-16	29.50
590	2	2025-04-17	17.78
591	2	2025-04-18	16.59
592	2	2025-04-19	10.67
593	2	2025-04-20	16.21
594	2	2025-04-21	12.83
595	2	2025-04-22	22.95
596	2	2025-04-23	10.32
597	2	2025-04-24	11.93
598	2	2025-04-25	33.32
599	2	2025-04-26	22.32
600	2	2025-04-27	22.38
601	2	2025-04-28	21.55
602	2	2025-04-29	34.37
603	2	2025-04-30	18.41
604	2	2025-05-01	16.10
605	2	2025-05-02	24.79
606	2	2025-05-03	28.00
607	2	2025-05-04	12.67
608	2	2025-05-05	19.90
609	2	2025-05-06	26.23
610	2	2025-05-07	26.14
611	2	2025-05-08	15.84
612	2	2025-05-09	24.73
613	2	2025-05-10	12.97
614	2	2025-05-11	28.59
615	2	2025-05-12	12.23
616	2	2025-05-13	28.53
617	2	2025-05-14	21.56
618	2	2025-05-15	10.14
619	2	2025-05-16	10.40
620	2	2025-05-17	10.24
621	2	2025-05-18	26.99
622	2	2025-05-19	19.83
623	2	2025-05-20	15.40
624	2	2025-05-21	14.97
625	2	2025-05-22	16.37
626	2	2025-05-23	28.65
627	2	2025-05-24	29.28
628	2	2025-05-25	20.23
629	2	2025-05-26	14.94
630	2	2025-05-27	14.94
631	2	2025-05-28	18.95
632	2	2025-05-29	22.98
633	2	2025-05-30	31.11
634	2	2025-05-31	23.16
635	2	2025-06-01	17.76
636	2	2025-06-02	25.56
637	2	2025-06-03	15.88
638	2	2025-06-04	31.47
639	2	2025-06-05	25.46
640	2	2025-06-06	14.73
641	2	2025-06-07	22.10
642	2	2025-06-08	32.47
643	2	2025-06-09	17.75
644	2	2025-06-10	12.83
645	2	2025-06-11	10.27
646	2	2025-06-12	11.33
647	2	2025-06-13	16.60
648	2	2025-06-14	17.85
649	2	2025-06-15	25.58
650	2	2025-06-16	13.70
651	2	2025-06-17	11.27
652	2	2025-06-18	15.26
653	2	2025-06-19	25.32
654	2	2025-06-20	20.68
655	2	2025-06-21	18.12
656	2	2025-06-22	29.89
657	2	2025-06-23	17.89
658	2	2025-06-24	18.21
659	2	2025-06-25	19.36
660	2	2025-06-26	19.11
661	2	2025-06-27	31.50
662	2	2025-06-28	17.77
663	2	2025-06-29	15.72
664	2	2025-06-30	27.85
665	2	2025-07-01	21.47
666	2	2025-07-02	22.55
667	2	2025-07-03	23.31
668	2	2025-07-04	20.54
669	2	2025-07-05	16.87
670	2	2025-07-06	31.63
671	2	2025-07-07	13.94
672	2	2025-07-08	19.39
673	2	2025-07-09	20.34
674	2	2025-07-10	21.54
675	2	2025-07-11	23.71
676	2	2025-07-12	27.76
677	2	2025-07-13	23.41
678	2	2025-07-14	29.97
679	2	2025-07-15	31.41
680	2	2025-07-16	13.90
681	2	2025-07-17	33.07
682	2	2025-07-18	17.81
683	2	2025-07-19	13.47
684	2	2025-07-20	31.69
685	2	2025-07-21	10.73
686	2	2025-07-22	12.63
687	2	2025-07-23	17.78
688	2	2025-07-24	19.62
689	2	2025-07-25	20.94
690	2	2025-07-26	10.08
691	2	2025-07-27	18.43
692	2	2025-07-28	14.16
693	2	2025-07-29	19.39
694	2	2025-07-30	33.47
695	2	2025-07-31	19.67
696	2	2025-08-01	17.45
697	2	2025-08-02	19.30
698	2	2025-08-03	20.36
699	2	2025-08-04	16.18
700	2	2025-08-05	14.37
701	2	2025-08-06	13.41
702	2	2025-08-07	22.01
703	2	2025-08-08	34.58
704	2	2025-08-09	18.95
705	2	2025-08-10	24.48
706	2	2025-08-11	12.89
707	2	2025-08-12	26.32
708	2	2025-08-13	11.55
709	2	2025-08-14	30.99
710	2	2025-08-15	15.83
711	2	2025-08-16	22.53
712	2	2025-08-17	24.93
713	2	2025-08-18	32.22
714	2	2025-08-19	16.94
715	2	2025-08-20	33.15
716	2	2025-08-21	24.67
717	2	2025-08-22	18.96
718	2	2025-08-23	28.59
719	2	2025-08-24	21.18
720	2	2025-08-25	28.15
721	2	2025-08-26	21.16
722	2	2025-08-27	33.95
723	2	2025-08-28	27.52
724	2	2025-08-29	25.23
725	2	2025-08-30	28.97
726	2	2025-08-31	27.19
727	2	2025-09-01	23.34
728	2	2025-09-02	27.70
729	2	2025-09-03	28.09
730	2	2025-09-04	19.60
731	2	2025-09-05	10.26
732	2	2025-09-06	10.01
733	2	2025-09-07	34.34
734	2	2025-09-08	30.17
735	2	2025-09-09	17.05
736	2	2025-09-10	26.28
737	2	2025-09-11	26.06
738	2	2025-09-12	33.24
739	2	2025-09-13	16.78
740	2	2025-09-14	13.02
741	2	2025-09-15	30.64
742	2	2025-09-16	20.43
743	2	2025-09-17	21.18
744	2	2025-09-18	31.61
745	2	2025-09-19	33.59
746	2	2025-09-20	17.51
747	2	2025-09-21	17.06
748	2	2025-09-22	17.67
749	2	2025-09-23	13.09
750	2	2025-09-24	15.62
751	2	2025-09-25	28.71
752	2	2025-09-26	10.34
753	2	2025-09-27	32.10
754	2	2025-09-28	21.24
755	2	2025-09-29	31.12
756	2	2025-09-30	31.78
757	2	2025-10-01	31.82
758	2	2025-10-02	12.03
759	2	2025-10-03	19.81
760	2	2025-10-04	26.72
761	2	2025-10-05	34.85
762	2	2025-10-06	26.83
763	2	2025-10-07	22.93
764	2	2025-10-08	14.17
765	2	2025-10-09	25.05
766	2	2025-10-10	14.98
767	2	2025-10-11	30.40
768	2	2025-10-12	23.35
769	2	2025-10-13	21.42
770	2	2025-10-14	17.31
771	2	2025-10-15	10.24
772	2	2025-10-16	13.47
773	2	2025-10-17	22.93
774	2	2025-10-18	31.12
775	2	2025-10-19	11.26
776	2	2025-10-20	28.45
777	2	2025-10-21	22.23
778	2	2025-10-22	14.97
779	2	2025-10-23	26.47
780	2	2025-10-24	11.16
781	2	2025-10-25	12.07
782	2	2025-10-26	17.28
783	2	2025-10-27	30.71
784	2	2025-10-28	27.82
785	2	2025-10-29	29.95
786	2	2025-10-30	21.66
787	2	2025-10-31	15.07
788	2	2025-11-01	14.30
789	2	2025-11-02	32.86
790	2	2025-11-03	16.62
791	2	2025-11-04	29.89
792	2	2025-11-05	27.25
793	2	2025-11-06	10.61
794	2	2025-11-07	29.65
795	2	2025-11-08	17.98
796	2	2025-11-09	29.55
797	2	2025-11-10	21.68
798	2	2025-11-11	32.55
799	2	2025-11-12	11.69
800	2	2025-11-13	25.01
801	2	2025-11-14	34.48
802	2	2025-11-15	20.31
803	2	2025-11-16	15.58
804	2	2025-11-17	26.24
805	2	2025-11-18	32.42
806	2	2025-11-19	17.38
807	2	2025-11-20	26.16
808	2	2025-11-21	13.42
809	2	2025-11-22	28.75
810	2	2025-11-23	28.92
811	2	2025-11-24	20.56
812	2	2025-11-25	24.20
813	2	2025-11-26	19.64
814	2	2025-11-27	28.36
815	2	2025-11-28	28.91
816	2	2025-11-29	22.20
817	2	2025-11-30	30.46
818	2	2025-12-01	16.87
819	2	2025-12-02	20.26
820	2	2025-12-03	17.78
821	2	2025-12-04	32.95
822	2	2025-12-05	10.77
823	2	2025-12-06	21.88
824	2	2025-12-07	23.65
825	2	2025-12-08	28.85
826	2	2025-12-09	21.14
827	2	2025-12-10	26.75
828	2	2025-12-11	13.01
829	2	2025-12-12	22.80
830	2	2025-12-13	18.11
831	2	2025-12-14	32.23
832	2	2025-12-15	33.91
833	2	2025-12-16	22.70
834	2	2025-12-17	24.22
835	2	2025-12-18	12.04
836	2	2025-12-19	33.25
837	2	2025-12-20	26.56
838	2	2025-12-21	25.97
839	2	2025-12-22	27.04
840	2	2025-12-23	31.23
841	2	2025-12-24	30.33
842	2	2025-12-25	22.39
843	2	2025-12-26	24.80
844	2	2025-12-27	31.34
845	2	2025-12-28	17.34
846	2	2025-12-29	33.65
847	2	2025-12-30	28.01
848	2	2025-12-31	14.85
849	2	2026-01-01	22.46
850	2	2026-01-02	13.30
851	2	2026-01-03	33.39
852	2	2026-01-04	15.37
853	2	2026-01-05	12.65
854	2	2026-01-06	22.24
855	2	2026-01-07	18.03
856	2	2026-01-08	19.11
857	2	2026-01-09	34.17
858	2	2026-01-10	21.31
859	2	2026-01-11	11.55
860	2	2026-01-12	21.91
861	2	2026-01-13	24.88
862	2	2026-01-14	12.85
863	2	2026-01-15	15.56
864	2	2026-01-16	24.78
865	2	2026-01-17	17.17
866	2	2026-01-18	11.13
867	2	2026-01-19	21.99
868	2	2026-01-20	27.15
869	2	2026-01-21	27.73
870	2	2026-01-22	29.13
871	2	2026-01-23	34.19
872	2	2026-01-24	12.96
873	2	2026-01-25	23.05
874	2	2026-01-26	32.39
875	2	2026-01-27	24.09
876	2	2026-01-28	22.21
877	2	2026-01-29	24.51
878	2	2026-01-30	25.85
879	2	2026-01-31	24.26
880	2	2026-02-01	30.91
881	2	2026-02-02	28.78
882	2	2026-02-03	11.65
883	2	2026-02-04	12.53
884	2	2026-02-05	16.12
885	2	2026-02-06	10.26
886	2	2026-02-07	10.22
887	2	2026-02-08	15.12
888	2	2026-02-09	30.00
889	2	2026-02-10	18.99
890	2	2026-02-11	29.05
891	2	2026-02-12	10.23
892	2	2026-02-13	12.11
893	2	2026-02-14	19.22
894	2	2026-02-15	18.95
895	2	2026-02-16	16.28
896	2	2026-02-17	34.73
897	2	2026-02-18	21.14
898	2	2026-02-19	19.43
899	2	2026-02-20	13.58
900	2	2026-02-21	30.05
901	2	2026-02-22	13.73
902	2	2026-02-23	16.54
903	2	2026-02-24	32.58
904	2	2026-02-25	28.72
905	2	2026-02-26	24.03
906	2	2026-02-27	25.07
907	2	2026-02-28	29.60
908	2	2026-03-01	17.41
909	2	2026-03-02	23.88
910	2	2026-03-03	32.40
911	2	2026-03-04	32.97
912	2	2026-03-05	15.33
913	2	2026-03-06	29.17
914	2	2026-03-07	10.40
915	2	2026-03-08	27.51
916	2	2026-03-09	24.34
917	2	2026-03-10	17.96
918	2	2026-03-11	22.46
919	2	2026-03-12	23.23
920	2	2026-03-13	17.97
921	2	2026-03-14	23.66
922	2	2026-03-15	18.64
923	2	2026-03-16	10.46
924	2	2026-03-17	27.35
925	2	2026-03-18	26.61
926	2	2026-03-19	22.86
927	2	2026-03-20	25.48
928	2	2026-03-21	17.32
929	2	2026-03-22	23.35
930	2	2026-03-23	17.82
931	2	2026-03-24	13.80
932	2	2026-03-25	14.80
933	2	2026-03-26	25.97
934	2	2026-03-27	31.15
935	2	2026-03-28	11.70
936	2	2026-03-29	14.81
937	2	2026-03-30	31.94
938	2	2026-03-31	15.54
939	2	2026-04-01	13.41
940	2	2026-04-02	17.79
941	2	2026-04-03	28.46
942	2	2026-04-04	11.67
943	2	2026-04-05	25.67
944	2	2026-04-06	12.09
945	2	2026-04-07	26.11
946	2	2026-04-08	24.59
947	2	2026-04-09	16.04
948	2	2026-04-10	33.92
949	2	2026-04-11	11.14
950	2	2026-04-12	25.52
951	2	2026-04-13	16.02
952	2	2026-04-14	26.63
953	2	2026-04-15	28.21
954	2	2026-04-16	27.08
955	2	2026-04-17	11.06
956	2	2026-04-18	23.01
957	2	2026-04-19	11.56
958	2	2026-04-20	24.39
959	2	2026-04-21	16.13
960	2	2026-04-22	33.69
961	2	2026-04-23	16.27
962	2	2026-04-24	19.28
963	2	2026-04-25	11.16
964	2	2026-04-26	21.86
965	2	2026-04-27	10.92
966	2	2026-04-28	20.48
967	4	2025-01-01	12.39
968	4	2025-01-02	10.49
969	4	2025-01-03	31.91
970	4	2025-01-04	10.96
971	4	2025-01-05	20.31
972	4	2025-01-06	17.59
973	4	2025-01-07	23.14
974	4	2025-01-08	23.10
975	4	2025-01-09	28.04
976	4	2025-01-10	13.53
977	4	2025-01-11	11.77
978	4	2025-01-12	24.80
979	4	2025-01-13	26.78
980	4	2025-01-14	13.03
981	4	2025-01-15	28.32
982	4	2025-01-16	22.23
983	4	2025-01-17	12.61
984	4	2025-01-18	17.34
985	4	2025-01-19	23.79
986	4	2025-01-20	28.82
987	4	2025-01-21	11.59
988	4	2025-01-22	33.23
989	4	2025-01-23	12.71
990	4	2025-01-24	32.81
991	4	2025-01-25	27.14
992	4	2025-01-26	24.54
993	4	2025-01-27	27.59
994	4	2025-01-28	25.02
995	4	2025-01-29	27.44
996	4	2025-01-30	12.46
997	4	2025-01-31	24.15
998	4	2025-02-01	23.15
999	4	2025-02-02	27.04
1000	4	2025-02-03	16.23
1001	4	2025-02-04	30.94
1002	4	2025-02-05	24.63
1003	4	2025-02-06	27.90
1004	4	2025-02-07	30.72
1005	4	2025-02-08	26.69
1006	4	2025-02-09	16.68
1007	4	2025-02-10	21.78
1008	4	2025-02-11	30.63
1009	4	2025-02-12	12.85
1010	4	2025-02-13	23.61
1011	4	2025-02-14	22.92
1012	4	2025-02-15	24.59
1013	4	2025-02-16	29.41
1014	4	2025-02-17	29.74
1015	4	2025-02-18	13.76
1016	4	2025-02-19	24.74
1017	4	2025-02-20	13.79
1018	4	2025-02-21	20.23
1019	4	2025-02-22	28.01
1020	4	2025-02-23	22.44
1021	4	2025-02-24	24.87
1022	4	2025-02-25	22.66
1023	4	2025-02-26	14.52
1024	4	2025-02-27	16.63
1025	4	2025-02-28	28.97
1026	4	2025-03-01	11.45
1027	4	2025-03-02	26.19
1028	4	2025-03-03	12.52
1029	4	2025-03-04	31.56
1030	4	2025-03-05	25.74
1031	4	2025-03-06	20.53
1032	4	2025-03-07	34.50
1033	4	2025-03-08	26.57
1034	4	2025-03-09	33.36
1035	4	2025-03-10	15.87
1036	4	2025-03-11	17.01
1037	4	2025-03-12	14.94
1038	4	2025-03-13	11.00
1039	4	2025-03-14	34.61
1040	4	2025-03-15	21.50
1041	4	2025-03-16	16.35
1042	4	2025-03-17	31.97
1043	4	2025-03-18	30.42
1044	4	2025-03-19	21.11
1045	4	2025-03-20	18.61
1046	4	2025-03-21	33.91
1047	4	2025-03-22	12.05
1048	4	2025-03-23	14.71
1049	4	2025-03-24	19.77
1050	4	2025-03-25	13.60
1051	4	2025-03-26	22.97
1052	4	2025-03-27	10.66
1053	4	2025-03-28	24.73
1054	4	2025-03-29	14.94
1055	4	2025-03-30	27.52
1056	4	2025-03-31	15.45
1057	4	2025-04-01	18.17
1058	4	2025-04-02	11.74
1059	4	2025-04-03	12.13
1060	4	2025-04-04	29.45
1061	4	2025-04-05	12.69
1062	4	2025-04-06	29.95
1063	4	2025-04-07	17.50
1064	4	2025-04-08	18.51
1065	4	2025-04-09	19.90
1066	4	2025-04-10	18.00
1067	4	2025-04-11	17.29
1068	4	2025-04-12	27.89
1069	4	2025-04-13	32.16
1070	4	2025-04-14	19.38
1071	4	2025-04-15	17.59
1072	4	2025-04-16	21.80
1073	4	2025-04-17	34.61
1074	4	2025-04-18	21.40
1075	4	2025-04-19	14.21
1076	4	2025-04-20	20.51
1077	4	2025-04-21	16.57
1078	4	2025-04-22	23.31
1079	4	2025-04-23	30.51
1080	4	2025-04-24	10.50
1081	4	2025-04-25	14.77
1082	4	2025-04-26	17.67
1083	4	2025-04-27	20.84
1084	4	2025-04-28	20.07
1085	4	2025-04-29	11.39
1086	4	2025-04-30	13.79
1087	4	2025-05-01	34.05
1088	4	2025-05-02	34.74
1089	4	2025-05-03	33.16
1090	4	2025-05-04	27.19
1091	4	2025-05-05	28.03
1092	4	2025-05-06	17.57
1093	4	2025-05-07	14.56
1094	4	2025-05-08	34.48
1095	4	2025-05-09	30.87
1096	4	2025-05-10	33.53
1097	4	2025-05-11	17.87
1098	4	2025-05-12	34.35
1099	4	2025-05-13	24.54
1100	4	2025-05-14	23.94
1101	4	2025-05-15	31.21
1102	4	2025-05-16	11.86
1103	4	2025-05-17	13.01
1104	4	2025-05-18	33.42
1105	4	2025-05-19	26.76
1106	4	2025-05-20	29.15
1107	4	2025-05-21	23.80
1108	4	2025-05-22	19.36
1109	4	2025-05-23	13.18
1110	4	2025-05-24	28.83
1111	4	2025-05-25	31.28
1112	4	2025-05-26	34.03
1113	4	2025-05-27	23.30
1114	4	2025-05-28	21.52
1115	4	2025-05-29	33.63
1116	4	2025-05-30	24.36
1117	4	2025-05-31	26.39
1118	4	2025-06-01	26.37
1119	4	2025-06-02	29.11
1120	4	2025-06-03	19.50
1121	4	2025-06-04	33.86
1122	4	2025-06-05	34.91
1123	4	2025-06-06	22.07
1124	4	2025-06-07	12.16
1125	4	2025-06-08	14.64
1126	4	2025-06-09	25.19
1127	4	2025-06-10	29.27
1128	4	2025-06-11	23.07
1129	4	2025-06-12	14.80
1130	4	2025-06-13	15.83
1131	4	2025-06-14	10.98
1132	4	2025-06-15	30.45
1133	4	2025-06-16	17.01
1134	4	2025-06-17	19.53
1135	4	2025-06-18	27.12
1136	4	2025-06-19	18.64
1137	4	2025-06-20	21.42
1138	4	2025-06-21	28.03
1139	4	2025-06-22	27.92
1140	4	2025-06-23	25.39
1141	4	2025-06-24	12.55
1142	4	2025-06-25	12.77
1143	4	2025-06-26	31.62
1144	4	2025-06-27	13.22
1145	4	2025-06-28	30.00
1146	4	2025-06-29	14.75
1147	4	2025-06-30	34.62
1148	4	2025-07-01	16.76
1149	4	2025-07-02	29.11
1150	4	2025-07-03	19.56
1151	4	2025-07-04	30.72
1152	4	2025-07-05	28.29
1153	4	2025-07-06	30.35
1154	4	2025-07-07	30.44
1155	4	2025-07-08	20.63
1156	4	2025-07-09	10.96
1157	4	2025-07-10	23.50
1158	4	2025-07-11	21.14
1159	4	2025-07-12	12.44
1160	4	2025-07-13	24.03
1161	4	2025-07-14	29.57
1162	4	2025-07-15	11.03
1163	4	2025-07-16	12.81
1164	4	2025-07-17	26.47
1165	4	2025-07-18	11.53
1166	4	2025-07-19	21.62
1167	4	2025-07-20	29.63
1168	4	2025-07-21	11.26
1169	4	2025-07-22	25.89
1170	4	2025-07-23	19.37
1171	4	2025-07-24	18.03
1172	4	2025-07-25	18.64
1173	4	2025-07-26	25.06
1174	4	2025-07-27	24.26
1175	4	2025-07-28	29.12
1176	4	2025-07-29	28.25
1177	4	2025-07-30	13.98
1178	4	2025-07-31	28.71
1179	4	2025-08-01	12.10
1180	4	2025-08-02	13.44
1181	4	2025-08-03	31.10
1182	4	2025-08-04	26.64
1183	4	2025-08-05	22.12
1184	4	2025-08-06	33.90
1185	4	2025-08-07	14.81
1186	4	2025-08-08	22.75
1187	4	2025-08-09	27.40
1188	4	2025-08-10	21.38
1189	4	2025-08-11	26.53
1190	4	2025-08-12	18.50
1191	4	2025-08-13	23.19
1192	4	2025-08-14	29.01
1193	4	2025-08-15	11.41
1194	4	2025-08-16	14.01
1195	4	2025-08-17	30.37
1196	4	2025-08-18	21.37
1197	4	2025-08-19	27.49
1198	4	2025-08-20	18.78
1199	4	2025-08-21	10.57
1200	4	2025-08-22	29.55
1201	4	2025-08-23	32.01
1202	4	2025-08-24	19.79
1203	4	2025-08-25	33.98
1204	4	2025-08-26	30.57
1205	4	2025-08-27	22.64
1206	4	2025-08-28	32.50
1207	4	2025-08-29	14.31
1208	4	2025-08-30	29.95
1209	4	2025-08-31	13.92
1210	4	2025-09-01	34.31
1211	4	2025-09-02	33.32
1212	4	2025-09-03	19.15
1213	4	2025-09-04	16.41
1214	4	2025-09-05	20.34
1215	4	2025-09-06	28.32
1216	4	2025-09-07	11.92
1217	4	2025-09-08	19.29
1218	4	2025-09-09	25.85
1219	4	2025-09-10	14.25
1220	4	2025-09-11	16.86
1221	4	2025-09-12	16.70
1222	4	2025-09-13	28.80
1223	4	2025-09-14	16.67
1224	4	2025-09-15	12.38
1225	4	2025-09-16	18.79
1226	4	2025-09-17	34.34
1227	4	2025-09-18	13.86
1228	4	2025-09-19	34.74
1229	4	2025-09-20	16.81
1230	4	2025-09-21	12.75
1231	4	2025-09-22	32.67
1232	4	2025-09-23	19.54
1233	4	2025-09-24	25.59
1234	4	2025-09-25	29.60
1235	4	2025-09-26	24.37
1236	4	2025-09-27	14.56
1237	4	2025-09-28	15.29
1238	4	2025-09-29	24.66
1239	4	2025-09-30	34.24
1240	4	2025-10-01	28.89
1241	4	2025-10-02	21.71
1242	4	2025-10-03	11.38
1243	4	2025-10-04	21.52
1244	4	2025-10-05	11.15
1245	4	2025-10-06	16.14
1246	4	2025-10-07	30.90
1247	4	2025-10-08	18.70
1248	4	2025-10-09	19.98
1249	4	2025-10-10	34.51
1250	4	2025-10-11	18.15
1251	4	2025-10-12	18.30
1252	4	2025-10-13	20.09
1253	4	2025-10-14	23.43
1254	4	2025-10-15	10.17
1255	4	2025-10-16	34.79
1256	4	2025-10-17	18.05
1257	4	2025-10-18	16.20
1258	4	2025-10-19	14.96
1259	4	2025-10-20	29.29
1260	4	2025-10-21	16.84
1261	4	2025-10-22	31.79
1262	4	2025-10-23	32.78
1263	4	2025-10-24	34.36
1264	4	2025-10-25	12.02
1265	4	2025-10-26	28.31
1266	4	2025-10-27	24.16
1267	4	2025-10-28	19.87
1268	4	2025-10-29	13.18
1269	4	2025-10-30	31.67
1270	4	2025-10-31	13.49
1271	4	2025-11-01	29.49
1272	4	2025-11-02	19.42
1273	4	2025-11-03	27.36
1274	4	2025-11-04	30.16
1275	4	2025-11-05	22.96
1276	4	2025-11-06	20.22
1277	4	2025-11-07	22.66
1278	4	2025-11-08	27.92
1279	4	2025-11-09	34.91
1280	4	2025-11-10	17.14
1281	4	2025-11-11	19.87
1282	4	2025-11-12	26.70
1283	4	2025-11-13	19.00
1284	4	2025-11-14	11.94
1285	4	2025-11-15	34.91
1286	4	2025-11-16	14.50
1287	4	2025-11-17	18.37
1288	4	2025-11-18	31.20
1289	4	2025-11-19	29.81
1290	4	2025-11-20	32.20
1291	4	2025-11-21	14.20
1292	4	2025-11-22	17.67
1293	4	2025-11-23	21.00
1294	4	2025-11-24	20.79
1295	4	2025-11-25	23.22
1296	4	2025-11-26	21.94
1297	4	2025-11-27	26.78
1298	4	2025-11-28	29.52
1299	4	2025-11-29	29.66
1300	4	2025-11-30	31.54
1301	4	2025-12-01	16.09
1302	4	2025-12-02	23.00
1303	4	2025-12-03	32.72
1304	4	2025-12-04	17.30
1305	4	2025-12-05	17.19
1306	4	2025-12-06	11.24
1307	4	2025-12-07	23.10
1308	4	2025-12-08	21.22
1309	4	2025-12-09	17.47
1310	4	2025-12-10	11.70
1311	4	2025-12-11	19.61
1312	4	2025-12-12	34.19
1313	4	2025-12-13	19.45
1314	4	2025-12-14	28.85
1315	4	2025-12-15	10.46
1316	4	2025-12-16	20.35
1317	4	2025-12-17	17.24
1318	4	2025-12-18	12.21
1319	4	2025-12-19	32.37
1320	4	2025-12-20	16.43
1321	4	2025-12-21	20.23
1322	4	2025-12-22	29.11
1323	4	2025-12-23	33.75
1324	4	2025-12-24	32.40
1325	4	2025-12-25	21.69
1326	4	2025-12-26	11.21
1327	4	2025-12-27	20.07
1328	4	2025-12-28	16.87
1329	4	2025-12-29	31.75
1330	4	2025-12-30	25.99
1331	4	2025-12-31	32.87
1332	4	2026-01-01	28.27
1333	4	2026-01-02	34.95
1334	4	2026-01-03	18.37
1335	4	2026-01-04	21.64
1336	4	2026-01-05	11.66
1337	4	2026-01-06	12.49
1338	4	2026-01-07	22.44
1339	4	2026-01-08	33.92
1340	4	2026-01-09	31.12
1341	4	2026-01-10	34.37
1342	4	2026-01-11	24.41
1343	4	2026-01-12	21.82
1344	4	2026-01-13	28.04
1345	4	2026-01-14	18.99
1346	4	2026-01-15	16.33
1347	4	2026-01-16	33.73
1348	4	2026-01-17	13.44
1349	4	2026-01-18	34.78
1350	4	2026-01-19	22.44
1351	4	2026-01-20	20.29
1352	4	2026-01-21	29.69
1353	4	2026-01-22	28.12
1354	4	2026-01-23	21.71
1355	4	2026-01-24	17.80
1356	4	2026-01-25	34.92
1357	4	2026-01-26	22.18
1358	4	2026-01-27	27.70
1359	4	2026-01-28	26.64
1360	4	2026-01-29	21.75
1361	4	2026-01-30	13.91
1362	4	2026-01-31	17.49
1363	4	2026-02-01	22.07
1364	4	2026-02-02	20.02
1365	4	2026-02-03	33.81
1366	4	2026-02-04	11.74
1367	4	2026-02-05	28.82
1368	4	2026-02-06	13.04
1369	4	2026-02-07	26.49
1370	4	2026-02-08	17.84
1371	4	2026-02-09	33.13
1372	4	2026-02-10	19.41
1373	4	2026-02-11	25.01
1374	4	2026-02-12	28.79
1375	4	2026-02-13	17.46
1376	4	2026-02-14	30.40
1377	4	2026-02-15	10.69
1378	4	2026-02-16	10.51
1379	4	2026-02-17	32.63
1380	4	2026-02-18	32.39
1381	4	2026-02-19	16.84
1382	4	2026-02-20	31.63
1383	4	2026-02-21	20.27
1384	4	2026-02-22	10.32
1385	4	2026-02-23	25.04
1386	4	2026-02-24	18.81
1387	4	2026-02-25	25.58
1388	4	2026-02-26	34.35
1389	4	2026-02-27	24.93
1390	4	2026-02-28	29.68
1391	4	2026-03-01	14.96
1392	4	2026-03-02	24.06
1393	4	2026-03-03	10.47
1394	4	2026-03-04	12.08
1395	4	2026-03-05	25.49
1396	4	2026-03-06	10.19
1397	4	2026-03-07	20.10
1398	4	2026-03-08	24.95
1399	4	2026-03-09	14.81
1400	4	2026-03-10	19.70
1401	4	2026-03-11	25.31
1402	4	2026-03-12	18.49
1403	4	2026-03-13	11.04
1404	4	2026-03-14	28.08
1405	4	2026-03-15	11.26
1406	4	2026-03-16	17.13
1407	4	2026-03-17	32.27
1408	4	2026-03-18	21.21
1409	4	2026-03-19	28.26
1410	4	2026-03-20	32.36
1411	4	2026-03-21	18.50
1412	4	2026-03-22	25.44
1413	4	2026-03-23	32.38
1414	4	2026-03-24	19.94
1415	4	2026-03-25	25.60
1416	4	2026-03-26	21.09
1417	4	2026-03-27	27.96
1418	4	2026-03-28	22.00
1419	4	2026-03-29	26.85
1420	4	2026-03-30	21.40
1421	4	2026-03-31	25.65
1422	4	2026-04-01	34.58
1423	4	2026-04-02	26.05
1424	4	2026-04-03	19.88
1425	4	2026-04-04	29.63
1426	4	2026-04-05	13.42
1427	4	2026-04-06	10.70
1428	4	2026-04-07	16.05
1429	4	2026-04-08	19.87
1430	4	2026-04-09	18.99
1431	4	2026-04-10	26.10
1432	4	2026-04-11	28.32
1433	4	2026-04-12	16.31
1434	4	2026-04-13	30.14
1435	4	2026-04-14	10.25
1436	4	2026-04-15	26.40
1437	4	2026-04-16	13.42
1438	4	2026-04-17	31.61
1439	4	2026-04-18	22.62
1440	4	2026-04-19	19.82
1441	4	2026-04-20	34.22
1442	4	2026-04-21	11.42
1443	4	2026-04-22	17.00
1444	4	2026-04-23	11.91
1445	4	2026-04-24	17.22
1446	4	2026-04-25	32.71
1447	4	2026-04-26	23.41
1448	4	2026-04-27	27.39
1449	4	2026-04-28	30.39
1450	5	2025-01-01	29.32
1451	5	2025-01-02	18.90
1452	5	2025-01-03	26.69
1453	5	2025-01-04	25.00
1454	5	2025-01-05	33.11
1455	5	2025-01-06	27.17
1456	5	2025-01-07	18.42
1457	5	2025-01-08	32.59
1458	5	2025-01-09	15.14
1459	5	2025-01-10	27.32
1460	5	2025-01-11	30.97
1461	5	2025-01-12	18.88
1462	5	2025-01-13	24.69
1463	5	2025-01-14	16.57
1464	5	2025-01-15	11.53
1465	5	2025-01-16	16.68
1466	5	2025-01-17	22.38
1467	5	2025-01-18	28.73
1468	5	2025-01-19	24.98
1469	5	2025-01-20	29.72
1470	5	2025-01-21	14.80
1471	5	2025-01-22	27.16
1472	5	2025-01-23	15.53
1473	5	2025-01-24	10.93
1474	5	2025-01-25	25.66
1475	5	2025-01-26	28.89
1476	5	2025-01-27	33.15
1477	5	2025-01-28	18.18
1478	5	2025-01-29	19.98
1479	5	2025-01-30	15.84
1480	5	2025-01-31	31.52
1481	5	2025-02-01	10.75
1482	5	2025-02-02	17.12
1483	5	2025-02-03	27.16
1484	5	2025-02-04	34.93
1485	5	2025-02-05	25.76
1486	5	2025-02-06	17.39
1487	5	2025-02-07	16.69
1488	5	2025-02-08	13.72
1489	5	2025-02-09	15.54
1490	5	2025-02-10	21.70
1491	5	2025-02-11	25.10
1492	5	2025-02-12	15.51
1493	5	2025-02-13	27.39
1494	5	2025-02-14	29.83
1495	5	2025-02-15	23.86
1496	5	2025-02-16	33.10
1497	5	2025-02-17	30.66
1498	5	2025-02-18	24.05
1499	5	2025-02-19	20.63
1500	5	2025-02-20	23.51
1501	5	2025-02-21	33.25
1502	5	2025-02-22	31.10
1503	5	2025-02-23	16.89
1504	5	2025-02-24	33.80
1505	5	2025-02-25	19.75
1506	5	2025-02-26	17.62
1507	5	2025-02-27	21.35
1508	5	2025-02-28	33.61
1509	5	2025-03-01	16.75
1510	5	2025-03-02	27.12
1511	5	2025-03-03	29.98
1512	5	2025-03-04	27.18
1513	5	2025-03-05	14.66
1514	5	2025-03-06	10.00
1515	5	2025-03-07	15.12
1516	5	2025-03-08	14.30
1517	5	2025-03-09	19.28
1518	5	2025-03-10	23.20
1519	5	2025-03-11	26.63
1520	5	2025-03-12	16.87
1521	5	2025-03-13	13.40
1522	5	2025-03-14	26.99
1523	5	2025-03-15	12.04
1524	5	2025-03-16	23.60
1525	5	2025-03-17	27.12
1526	5	2025-03-18	16.04
1527	5	2025-03-19	16.31
1528	5	2025-03-20	10.78
1529	5	2025-03-21	10.52
1530	5	2025-03-22	31.21
1531	5	2025-03-23	26.53
1532	5	2025-03-24	14.36
1533	5	2025-03-25	12.85
1534	5	2025-03-26	22.29
1535	5	2025-03-27	20.17
1536	5	2025-03-28	17.23
1537	5	2025-03-29	14.56
1538	5	2025-03-30	34.03
1539	5	2025-03-31	24.05
1540	5	2025-04-01	19.13
1541	5	2025-04-02	22.80
1542	5	2025-04-03	26.21
1543	5	2025-04-04	30.15
1544	5	2025-04-05	34.37
1545	5	2025-04-06	12.21
1546	5	2025-04-07	22.10
1547	5	2025-04-08	21.29
1548	5	2025-04-09	26.42
1549	5	2025-04-10	29.03
1550	5	2025-04-11	19.47
1551	5	2025-04-12	27.44
1552	5	2025-04-13	16.30
1553	5	2025-04-14	14.92
1554	5	2025-04-15	17.52
1555	5	2025-04-16	10.06
1556	5	2025-04-17	23.23
1557	5	2025-04-18	18.35
1558	5	2025-04-19	13.70
1559	5	2025-04-20	13.01
1560	5	2025-04-21	11.47
1561	5	2025-04-22	32.54
1562	5	2025-04-23	11.56
1563	5	2025-04-24	18.97
1564	5	2025-04-25	31.26
1565	5	2025-04-26	33.48
1566	5	2025-04-27	34.99
1567	5	2025-04-28	26.44
1568	5	2025-04-29	20.31
1569	5	2025-04-30	10.03
1570	5	2025-05-01	24.09
1571	5	2025-05-02	34.91
1572	5	2025-05-03	21.97
1573	5	2025-05-04	34.96
1574	5	2025-05-05	27.21
1575	5	2025-05-06	14.45
1576	5	2025-05-07	15.70
1577	5	2025-05-08	24.27
1578	5	2025-05-09	29.13
1579	5	2025-05-10	31.10
1580	5	2025-05-11	10.16
1581	5	2025-05-12	17.98
1582	5	2025-05-13	32.83
1583	5	2025-05-14	23.50
1584	5	2025-05-15	20.06
1585	5	2025-05-16	18.31
1586	5	2025-05-17	16.90
1587	5	2025-05-18	32.81
1588	5	2025-05-19	31.91
1589	5	2025-05-20	24.20
1590	5	2025-05-21	16.43
1591	5	2025-05-22	14.55
1592	5	2025-05-23	12.33
1593	5	2025-05-24	25.52
1594	5	2025-05-25	25.75
1595	5	2025-05-26	15.13
1596	5	2025-05-27	11.22
1597	5	2025-05-28	26.21
1598	5	2025-05-29	28.60
1599	5	2025-05-30	25.34
1600	5	2025-05-31	29.18
1601	5	2025-06-01	21.69
1602	5	2025-06-02	24.48
1603	5	2025-06-03	22.40
1604	5	2025-06-04	27.22
1605	5	2025-06-05	23.21
1606	5	2025-06-06	22.10
1607	5	2025-06-07	24.84
1608	5	2025-06-08	33.05
1609	5	2025-06-09	10.35
1610	5	2025-06-10	11.39
1611	5	2025-06-11	33.51
1612	5	2025-06-12	20.40
1613	5	2025-06-13	29.82
1614	5	2025-06-14	12.79
1615	5	2025-06-15	24.11
1616	5	2025-06-16	31.75
1617	5	2025-06-17	33.74
1618	5	2025-06-18	16.81
1619	5	2025-06-19	19.51
1620	5	2025-06-20	20.65
1621	5	2025-06-21	14.07
1622	5	2025-06-22	13.98
1623	5	2025-06-23	29.03
1624	5	2025-06-24	12.64
1625	5	2025-06-25	13.16
1626	5	2025-06-26	17.53
1627	5	2025-06-27	28.52
1628	5	2025-06-28	25.82
1629	5	2025-06-29	33.19
1630	5	2025-06-30	17.26
1631	5	2025-07-01	26.30
1632	5	2025-07-02	23.38
1633	5	2025-07-03	24.51
1634	5	2025-07-04	16.48
1635	5	2025-07-05	27.45
1636	5	2025-07-06	16.21
1637	5	2025-07-07	23.41
1638	5	2025-07-08	27.68
1639	5	2025-07-09	15.08
1640	5	2025-07-10	29.55
1641	5	2025-07-11	26.88
1642	5	2025-07-12	32.73
1643	5	2025-07-13	11.16
1644	5	2025-07-14	13.38
1645	5	2025-07-15	31.81
1646	5	2025-07-16	32.39
1647	5	2025-07-17	16.13
1648	5	2025-07-18	33.99
1649	5	2025-07-19	16.33
1650	5	2025-07-20	28.85
1651	5	2025-07-21	10.95
1652	5	2025-07-22	18.03
1653	5	2025-07-23	20.73
1654	5	2025-07-24	17.29
1655	5	2025-07-25	20.20
1656	5	2025-07-26	32.47
1657	5	2025-07-27	28.91
1658	5	2025-07-28	18.03
1659	5	2025-07-29	24.07
1660	5	2025-07-30	34.61
1661	5	2025-07-31	29.68
1662	5	2025-08-01	16.16
1663	5	2025-08-02	16.66
1664	5	2025-08-03	21.03
1665	5	2025-08-04	11.08
1666	5	2025-08-05	17.40
1667	5	2025-08-06	11.87
1668	5	2025-08-07	22.21
1669	5	2025-08-08	33.64
1670	5	2025-08-09	10.63
1671	5	2025-08-10	20.00
1672	5	2025-08-11	27.17
1673	5	2025-08-12	13.62
1674	5	2025-08-13	20.56
1675	5	2025-08-14	27.33
1676	5	2025-08-15	10.63
1677	5	2025-08-16	18.05
1678	5	2025-08-17	12.25
1679	5	2025-08-18	22.68
1680	5	2025-08-19	15.12
1681	5	2025-08-20	24.52
1682	5	2025-08-21	21.64
1683	5	2025-08-22	11.31
1684	5	2025-08-23	30.80
1685	5	2025-08-24	34.98
1686	5	2025-08-25	20.64
1687	5	2025-08-26	28.72
1688	5	2025-08-27	31.43
1689	5	2025-08-28	24.15
1690	5	2025-08-29	28.05
1691	5	2025-08-30	21.23
1692	5	2025-08-31	10.21
1693	5	2025-09-01	33.02
1694	5	2025-09-02	33.23
1695	5	2025-09-03	32.22
1696	5	2025-09-04	11.97
1697	5	2025-09-05	18.90
1698	5	2025-09-06	18.21
1699	5	2025-09-07	18.04
1700	5	2025-09-08	21.20
1701	5	2025-09-09	17.83
1702	5	2025-09-10	18.97
1703	5	2025-09-11	20.28
1704	5	2025-09-12	21.92
1705	5	2025-09-13	13.39
1706	5	2025-09-14	23.03
1707	5	2025-09-15	26.10
1708	5	2025-09-16	24.29
1709	5	2025-09-17	16.79
1710	5	2025-09-18	15.05
1711	5	2025-09-19	24.05
1712	5	2025-09-20	15.90
1713	5	2025-09-21	31.74
1714	5	2025-09-22	33.44
1715	5	2025-09-23	19.40
1716	5	2025-09-24	14.45
1717	5	2025-09-25	15.77
1718	5	2025-09-26	31.87
1719	5	2025-09-27	30.82
1720	5	2025-09-28	18.01
1721	5	2025-09-29	29.91
1722	5	2025-09-30	27.73
1723	5	2025-10-01	22.96
1724	5	2025-10-02	12.95
1725	5	2025-10-03	12.38
1726	5	2025-10-04	13.76
1727	5	2025-10-05	24.99
1728	5	2025-10-06	34.06
1729	5	2025-10-07	11.82
1730	5	2025-10-08	20.25
1731	5	2025-10-09	33.72
1732	5	2025-10-10	21.84
1733	5	2025-10-11	26.21
1734	5	2025-10-12	11.43
1735	5	2025-10-13	15.84
1736	5	2025-10-14	10.65
1737	5	2025-10-15	31.88
1738	5	2025-10-16	27.52
1739	5	2025-10-17	34.29
1740	5	2025-10-18	30.74
1741	5	2025-10-19	13.02
1742	5	2025-10-20	24.27
1743	5	2025-10-21	24.61
1744	5	2025-10-22	21.95
1745	5	2025-10-23	28.59
1746	5	2025-10-24	28.80
1747	5	2025-10-25	10.18
1748	5	2025-10-26	14.07
1749	5	2025-10-27	32.16
1750	5	2025-10-28	16.99
1751	5	2025-10-29	22.92
1752	5	2025-10-30	25.90
1753	5	2025-10-31	12.80
1754	5	2025-11-01	31.06
1755	5	2025-11-02	16.56
1756	5	2025-11-03	20.27
1757	5	2025-11-04	14.85
1758	5	2025-11-05	26.22
1759	5	2025-11-06	29.66
1760	5	2025-11-07	18.32
1761	5	2025-11-08	13.40
1762	5	2025-11-09	31.59
1763	5	2025-11-10	34.45
1764	5	2025-11-11	25.04
1765	5	2025-11-12	28.72
1766	5	2025-11-13	28.28
1767	5	2025-11-14	20.08
1768	5	2025-11-15	19.33
1769	5	2025-11-16	27.08
1770	5	2025-11-17	33.90
1771	5	2025-11-18	26.24
1772	5	2025-11-19	16.90
1773	5	2025-11-20	13.99
1774	5	2025-11-21	17.13
1775	5	2025-11-22	15.18
1776	5	2025-11-23	22.52
1777	5	2025-11-24	22.59
1778	5	2025-11-25	27.83
1779	5	2025-11-26	12.67
1780	5	2025-11-27	13.24
1781	5	2025-11-28	23.35
1782	5	2025-11-29	15.76
1783	5	2025-11-30	18.84
1784	5	2025-12-01	26.33
1785	5	2025-12-02	13.27
1786	5	2025-12-03	11.22
1787	5	2025-12-04	21.33
1788	5	2025-12-05	19.28
1789	5	2025-12-06	13.05
1790	5	2025-12-07	17.25
1791	5	2025-12-08	20.27
1792	5	2025-12-09	27.93
1793	5	2025-12-10	21.98
1794	5	2025-12-11	22.40
1795	5	2025-12-12	30.46
1796	5	2025-12-13	29.60
1797	5	2025-12-14	17.37
1798	5	2025-12-15	15.33
1799	5	2025-12-16	25.35
1800	5	2025-12-17	28.82
1801	5	2025-12-18	18.85
1802	5	2025-12-19	32.68
1803	5	2025-12-20	25.90
1804	5	2025-12-21	21.90
1805	5	2025-12-22	17.81
1806	5	2025-12-23	28.50
1807	5	2025-12-24	30.97
1808	5	2025-12-25	31.29
1809	5	2025-12-26	27.82
1810	5	2025-12-27	10.27
1811	5	2025-12-28	16.83
1812	5	2025-12-29	17.04
1813	5	2025-12-30	19.25
1814	5	2025-12-31	23.50
1815	5	2026-01-01	34.14
1816	5	2026-01-02	18.80
1817	5	2026-01-03	26.65
1818	5	2026-01-04	18.74
1819	5	2026-01-05	20.06
1820	5	2026-01-06	22.81
1821	5	2026-01-07	18.39
1822	5	2026-01-08	12.13
1823	5	2026-01-09	10.90
1824	5	2026-01-10	20.78
1825	5	2026-01-11	20.12
1826	5	2026-01-12	34.75
1827	5	2026-01-13	32.91
1828	5	2026-01-14	18.05
1829	5	2026-01-15	32.58
1830	5	2026-01-16	15.31
1831	5	2026-01-17	22.39
1832	5	2026-01-18	10.84
1833	5	2026-01-19	13.57
1834	5	2026-01-20	21.69
1835	5	2026-01-21	22.02
1836	5	2026-01-22	34.71
1837	5	2026-01-23	29.10
1838	5	2026-01-24	20.24
1839	5	2026-01-25	20.51
1840	5	2026-01-26	20.35
1841	5	2026-01-27	18.11
1842	5	2026-01-28	23.11
1843	5	2026-01-29	10.73
1844	5	2026-01-30	21.67
1845	5	2026-01-31	24.74
1846	5	2026-02-01	16.92
1847	5	2026-02-02	21.61
1848	5	2026-02-03	23.84
1849	5	2026-02-04	31.53
1850	5	2026-02-05	25.58
1851	5	2026-02-06	24.57
1852	5	2026-02-07	30.05
1853	5	2026-02-08	29.01
1854	5	2026-02-09	26.82
1855	5	2026-02-10	21.90
1856	5	2026-02-11	28.63
1857	5	2026-02-12	14.58
1858	5	2026-02-13	10.42
1859	5	2026-02-14	10.15
1860	5	2026-02-15	24.26
1861	5	2026-02-16	30.55
1862	5	2026-02-17	15.73
1863	5	2026-02-18	29.08
1864	5	2026-02-19	34.65
1865	5	2026-02-20	14.76
1866	5	2026-02-21	10.42
1867	5	2026-02-22	23.46
1868	5	2026-02-23	19.27
1869	5	2026-02-24	14.79
1870	5	2026-02-25	33.95
1871	5	2026-02-26	18.73
1872	5	2026-02-27	19.13
1873	5	2026-02-28	31.78
1874	5	2026-03-01	15.07
1875	5	2026-03-02	21.02
1876	5	2026-03-03	32.52
1877	5	2026-03-04	31.15
1878	5	2026-03-05	21.28
1879	5	2026-03-06	21.18
1880	5	2026-03-07	13.69
1881	5	2026-03-08	27.06
1882	5	2026-03-09	19.92
1883	5	2026-03-10	18.54
1884	5	2026-03-11	34.24
1885	5	2026-03-12	26.12
1886	5	2026-03-13	26.85
1887	5	2026-03-14	19.62
1888	5	2026-03-15	19.19
1889	5	2026-03-16	31.64
1890	5	2026-03-17	15.15
1891	5	2026-03-18	19.49
1892	5	2026-03-19	15.11
1893	5	2026-03-20	27.62
1894	5	2026-03-21	19.06
1895	5	2026-03-22	17.44
1896	5	2026-03-23	29.76
1897	5	2026-03-24	31.15
1898	5	2026-03-25	20.07
1899	5	2026-03-26	20.81
1900	5	2026-03-27	34.09
1901	5	2026-03-28	16.84
1902	5	2026-03-29	27.11
1903	5	2026-03-30	15.32
1904	5	2026-03-31	33.17
1905	5	2026-04-01	27.29
1906	5	2026-04-02	27.59
1907	5	2026-04-03	27.48
1908	5	2026-04-04	34.19
1909	5	2026-04-05	23.68
1910	5	2026-04-06	12.11
1911	5	2026-04-07	29.60
1912	5	2026-04-08	10.75
1913	5	2026-04-09	18.33
1914	5	2026-04-10	23.19
1915	5	2026-04-11	18.62
1916	5	2026-04-12	27.65
1917	5	2026-04-13	12.23
1918	5	2026-04-14	26.14
1919	5	2026-04-15	20.09
1920	5	2026-04-16	29.39
1921	5	2026-04-17	31.16
1922	5	2026-04-18	19.74
1923	5	2026-04-19	23.27
1924	5	2026-04-20	16.25
1925	5	2026-04-21	13.90
1926	5	2026-04-22	13.04
1927	5	2026-04-23	22.38
1928	5	2026-04-24	13.55
1929	5	2026-04-25	17.19
1930	5	2026-04-26	26.25
1931	5	2026-04-27	11.13
1932	5	2026-04-28	23.90
1933	7	2025-01-01	10.40
1934	7	2025-01-02	25.64
1935	7	2025-01-03	18.26
1936	7	2025-01-04	12.76
1937	7	2025-01-05	12.20
1938	7	2025-01-06	20.60
1939	7	2025-01-07	13.96
1940	7	2025-01-08	33.10
1941	7	2025-01-09	12.54
1942	7	2025-01-10	18.48
1943	7	2025-01-11	29.14
1944	7	2025-01-12	34.85
1945	7	2025-01-13	28.99
1946	7	2025-01-14	28.14
1947	7	2025-01-15	10.56
1948	7	2025-01-16	28.52
1949	7	2025-01-17	10.24
1950	7	2025-01-18	30.90
1951	7	2025-01-19	29.18
1952	7	2025-01-20	23.66
1953	7	2025-01-21	15.17
1954	7	2025-01-22	10.93
1955	7	2025-01-23	11.30
1956	7	2025-01-24	28.34
1957	7	2025-01-25	29.19
1958	7	2025-01-26	23.38
1959	7	2025-01-27	10.63
1960	7	2025-01-28	17.09
1961	7	2025-01-29	12.86
1962	7	2025-01-30	20.20
1963	7	2025-01-31	18.99
1964	7	2025-02-01	32.45
1965	7	2025-02-02	17.61
1966	7	2025-02-03	33.73
1967	7	2025-02-04	19.85
1968	7	2025-02-05	30.48
1969	7	2025-02-06	21.78
1970	7	2025-02-07	15.71
1971	7	2025-02-08	24.62
1972	7	2025-02-09	10.46
1973	7	2025-02-10	11.25
1974	7	2025-02-11	32.77
1975	7	2025-02-12	18.42
1976	7	2025-02-13	17.74
1977	7	2025-02-14	33.23
1978	7	2025-02-15	22.82
1979	7	2025-02-16	31.29
1980	7	2025-02-17	16.38
1981	7	2025-02-18	18.17
1982	7	2025-02-19	27.33
1983	7	2025-02-20	28.34
1984	7	2025-02-21	17.82
1985	7	2025-02-22	26.82
1986	7	2025-02-23	25.13
1987	7	2025-02-24	14.73
1988	7	2025-02-25	27.11
1989	7	2025-02-26	30.77
1990	7	2025-02-27	28.41
1991	7	2025-02-28	19.86
1992	7	2025-03-01	16.20
1993	7	2025-03-02	32.41
1994	7	2025-03-03	29.10
1995	7	2025-03-04	30.88
1996	7	2025-03-05	19.51
1997	7	2025-03-06	33.82
1998	7	2025-03-07	13.04
1999	7	2025-03-08	27.37
2000	7	2025-03-09	27.16
2001	7	2025-03-10	23.89
2002	7	2025-03-11	27.09
2003	7	2025-03-12	29.80
2004	7	2025-03-13	12.19
2005	7	2025-03-14	21.01
2006	7	2025-03-15	11.60
2007	7	2025-03-16	24.27
2008	7	2025-03-17	31.09
2009	7	2025-03-18	26.67
2010	7	2025-03-19	29.63
2011	7	2025-03-20	12.62
2012	7	2025-03-21	20.44
2013	7	2025-03-22	30.66
2014	7	2025-03-23	11.80
2015	7	2025-03-24	17.97
2016	7	2025-03-25	16.10
2017	7	2025-03-26	30.92
2018	7	2025-03-27	20.76
2019	7	2025-03-28	14.15
2020	7	2025-03-29	13.94
2021	7	2025-03-30	25.48
2022	7	2025-03-31	19.15
2023	7	2025-04-01	25.79
2024	7	2025-04-02	25.91
2025	7	2025-04-03	18.53
2026	7	2025-04-04	16.36
2027	7	2025-04-05	26.08
2028	7	2025-04-06	17.62
2029	7	2025-04-07	21.97
2030	7	2025-04-08	22.81
2031	7	2025-04-09	32.77
2032	7	2025-04-10	31.27
2033	7	2025-04-11	21.87
2034	7	2025-04-12	23.00
2035	7	2025-04-13	27.29
2036	7	2025-04-14	18.19
2037	7	2025-04-15	13.73
2038	7	2025-04-16	26.59
2039	7	2025-04-17	25.94
2040	7	2025-04-18	29.25
2041	7	2025-04-19	31.29
2042	7	2025-04-20	33.65
2043	7	2025-04-21	32.28
2044	7	2025-04-22	33.44
2045	7	2025-04-23	21.25
2046	7	2025-04-24	18.94
2047	7	2025-04-25	27.15
2048	7	2025-04-26	18.05
2049	7	2025-04-27	33.05
2050	7	2025-04-28	19.07
2051	7	2025-04-29	10.63
2052	7	2025-04-30	25.58
2053	7	2025-05-01	33.63
2054	7	2025-05-02	11.74
2055	7	2025-05-03	18.37
2056	7	2025-05-04	16.77
2057	7	2025-05-05	13.82
2058	7	2025-05-06	16.01
2059	7	2025-05-07	25.72
2060	7	2025-05-08	14.10
2061	7	2025-05-09	16.12
2062	7	2025-05-10	16.48
2063	7	2025-05-11	20.08
2064	7	2025-05-12	26.33
2065	7	2025-05-13	16.57
2066	7	2025-05-14	24.72
2067	7	2025-05-15	14.27
2068	7	2025-05-16	20.96
2069	7	2025-05-17	13.48
2070	7	2025-05-18	10.40
2071	7	2025-05-19	21.51
2072	7	2025-05-20	23.34
2073	7	2025-05-21	17.35
2074	7	2025-05-22	28.47
2075	7	2025-05-23	18.82
2076	7	2025-05-24	17.52
2077	7	2025-05-25	15.37
2078	7	2025-05-26	12.27
2079	7	2025-05-27	24.40
2080	7	2025-05-28	13.22
2081	7	2025-05-29	30.56
2082	7	2025-05-30	15.64
2083	7	2025-05-31	26.35
2084	7	2025-06-01	32.19
2085	7	2025-06-02	20.51
2086	7	2025-06-03	11.51
2087	7	2025-06-04	27.17
2088	7	2025-06-05	18.33
2089	7	2025-06-06	20.70
2090	7	2025-06-07	34.93
2091	7	2025-06-08	27.29
2092	7	2025-06-09	25.86
2093	7	2025-06-10	24.27
2094	7	2025-06-11	27.98
2095	7	2025-06-12	31.23
2096	7	2025-06-13	14.63
2097	7	2025-06-14	16.49
2098	7	2025-06-15	30.06
2099	7	2025-06-16	12.67
2100	7	2025-06-17	22.14
2101	7	2025-06-18	26.79
2102	7	2025-06-19	29.60
2103	7	2025-06-20	22.83
2104	7	2025-06-21	26.49
2105	7	2025-06-22	11.57
2106	7	2025-06-23	14.50
2107	7	2025-06-24	13.64
2108	7	2025-06-25	33.99
2109	7	2025-06-26	15.56
2110	7	2025-06-27	16.89
2111	7	2025-06-28	31.51
2112	7	2025-06-29	23.89
2113	7	2025-06-30	31.22
2114	7	2025-07-01	24.08
2115	7	2025-07-02	23.70
2116	7	2025-07-03	13.02
2117	7	2025-07-04	17.22
2118	7	2025-07-05	17.94
2119	7	2025-07-06	15.89
2120	7	2025-07-07	15.60
2121	7	2025-07-08	32.14
2122	7	2025-07-09	23.20
2123	7	2025-07-10	28.24
2124	7	2025-07-11	18.74
2125	7	2025-07-12	17.88
2126	7	2025-07-13	28.04
2127	7	2025-07-14	16.15
2128	7	2025-07-15	19.64
2129	7	2025-07-16	25.49
2130	7	2025-07-17	10.18
2131	7	2025-07-18	30.75
2132	7	2025-07-19	12.50
2133	7	2025-07-20	14.29
2134	7	2025-07-21	29.10
2135	7	2025-07-22	13.55
2136	7	2025-07-23	19.90
2137	7	2025-07-24	27.31
2138	7	2025-07-25	25.28
2139	7	2025-07-26	27.08
2140	7	2025-07-27	31.74
2141	7	2025-07-28	22.63
2142	7	2025-07-29	29.45
2143	7	2025-07-30	27.04
2144	7	2025-07-31	12.71
2145	7	2025-08-01	15.60
2146	7	2025-08-02	19.71
2147	7	2025-08-03	33.56
2148	7	2025-08-04	33.43
2149	7	2025-08-05	25.09
2150	7	2025-08-06	26.25
2151	7	2025-08-07	20.45
2152	7	2025-08-08	11.21
2153	7	2025-08-09	24.19
2154	7	2025-08-10	28.67
2155	7	2025-08-11	19.26
2156	7	2025-08-12	16.92
2157	7	2025-08-13	24.45
2158	7	2025-08-14	33.29
2159	7	2025-08-15	22.31
2160	7	2025-08-16	20.37
2161	7	2025-08-17	30.64
2162	7	2025-08-18	28.14
2163	7	2025-08-19	26.63
2164	7	2025-08-20	22.10
2165	7	2025-08-21	33.94
2166	7	2025-08-22	26.00
2167	7	2025-08-23	21.44
2168	7	2025-08-24	14.24
2169	7	2025-08-25	27.19
2170	7	2025-08-26	11.78
2171	7	2025-08-27	29.52
2172	7	2025-08-28	33.26
2173	7	2025-08-29	21.28
2174	7	2025-08-30	25.89
2175	7	2025-08-31	21.10
2176	7	2025-09-01	24.29
2177	7	2025-09-02	31.02
2178	7	2025-09-03	13.17
2179	7	2025-09-04	26.86
2180	7	2025-09-05	17.09
2181	7	2025-09-06	30.78
2182	7	2025-09-07	33.45
2183	7	2025-09-08	32.61
2184	7	2025-09-09	13.30
2185	7	2025-09-10	20.38
2186	7	2025-09-11	11.27
2187	7	2025-09-12	14.58
2188	7	2025-09-13	28.23
2189	7	2025-09-14	22.83
2190	7	2025-09-15	32.89
2191	7	2025-09-16	10.58
2192	7	2025-09-17	17.83
2193	7	2025-09-18	11.01
2194	7	2025-09-19	33.05
2195	7	2025-09-20	12.95
2196	7	2025-09-21	10.15
2197	7	2025-09-22	30.66
2198	7	2025-09-23	29.46
2199	7	2025-09-24	29.57
2200	7	2025-09-25	26.14
2201	7	2025-09-26	29.09
2202	7	2025-09-27	15.03
2203	7	2025-09-28	28.00
2204	7	2025-09-29	30.06
2205	7	2025-09-30	17.20
2206	7	2025-10-01	28.13
2207	7	2025-10-02	19.46
2208	7	2025-10-03	19.43
2209	7	2025-10-04	34.27
2210	7	2025-10-05	32.61
2211	7	2025-10-06	32.86
2212	7	2025-10-07	34.77
2213	7	2025-10-08	16.16
2214	7	2025-10-09	34.63
2215	7	2025-10-10	22.70
2216	7	2025-10-11	34.65
2217	7	2025-10-12	14.83
2218	7	2025-10-13	12.00
2219	7	2025-10-14	18.70
2220	7	2025-10-15	27.16
2221	7	2025-10-16	34.13
2222	7	2025-10-17	17.19
2223	7	2025-10-18	33.98
2224	7	2025-10-19	12.18
2225	7	2025-10-20	13.73
2226	7	2025-10-21	29.06
2227	7	2025-10-22	26.35
2228	7	2025-10-23	19.24
2229	7	2025-10-24	17.93
2230	7	2025-10-25	16.55
2231	7	2025-10-26	31.13
2232	7	2025-10-27	18.56
2233	7	2025-10-28	30.66
2234	7	2025-10-29	32.51
2235	7	2025-10-30	10.62
2236	7	2025-10-31	20.11
2237	7	2025-11-01	14.49
2238	7	2025-11-02	28.60
2239	7	2025-11-03	28.55
2240	7	2025-11-04	21.58
2241	7	2025-11-05	22.80
2242	7	2025-11-06	32.57
2243	7	2025-11-07	10.65
2244	7	2025-11-08	20.19
2245	7	2025-11-09	25.86
2246	7	2025-11-10	34.50
2247	7	2025-11-11	26.15
2248	7	2025-11-12	27.03
2249	7	2025-11-13	23.44
2250	7	2025-11-14	14.90
2251	7	2025-11-15	28.08
2252	7	2025-11-16	20.08
2253	7	2025-11-17	22.90
2254	7	2025-11-18	13.69
2255	7	2025-11-19	20.85
2256	7	2025-11-20	15.52
2257	7	2025-11-21	26.27
2258	7	2025-11-22	24.76
2259	7	2025-11-23	25.55
2260	7	2025-11-24	30.06
2261	7	2025-11-25	29.52
2262	7	2025-11-26	18.53
2263	7	2025-11-27	13.03
2264	7	2025-11-28	29.36
2265	7	2025-11-29	29.35
2266	7	2025-11-30	15.41
2267	7	2025-12-01	18.48
2268	7	2025-12-02	16.77
2269	7	2025-12-03	13.44
2270	7	2025-12-04	14.63
2271	7	2025-12-05	14.75
2272	7	2025-12-06	18.66
2273	7	2025-12-07	33.72
2274	7	2025-12-08	31.56
2275	7	2025-12-09	29.66
2276	7	2025-12-10	28.65
2277	7	2025-12-11	15.68
2278	7	2025-12-12	10.25
2279	7	2025-12-13	33.69
2280	7	2025-12-14	25.13
2281	7	2025-12-15	11.65
2282	7	2025-12-16	28.63
2283	7	2025-12-17	34.23
2284	7	2025-12-18	13.62
2285	7	2025-12-19	19.91
2286	7	2025-12-20	33.12
2287	7	2025-12-21	22.83
2288	7	2025-12-22	32.75
2289	7	2025-12-23	11.34
2290	7	2025-12-24	33.46
2291	7	2025-12-25	21.70
2292	7	2025-12-26	25.64
2293	7	2025-12-27	33.05
2294	7	2025-12-28	34.85
2295	7	2025-12-29	23.45
2296	7	2025-12-30	13.01
2297	7	2025-12-31	26.43
2298	7	2026-01-01	21.32
2299	7	2026-01-02	15.02
2300	7	2026-01-03	27.78
2301	7	2026-01-04	15.12
2302	7	2026-01-05	24.45
2303	7	2026-01-06	31.32
2304	7	2026-01-07	20.51
2305	7	2026-01-08	10.96
2306	7	2026-01-09	28.50
2307	7	2026-01-10	32.34
2308	7	2026-01-11	19.60
2309	7	2026-01-12	19.56
2310	7	2026-01-13	29.44
2311	7	2026-01-14	15.78
2312	7	2026-01-15	16.69
2313	7	2026-01-16	34.57
2314	7	2026-01-17	32.18
2315	7	2026-01-18	28.01
2316	7	2026-01-19	10.93
2317	7	2026-01-20	16.64
2318	7	2026-01-21	20.11
2319	7	2026-01-22	23.31
2320	7	2026-01-23	25.23
2321	7	2026-01-24	24.63
2322	7	2026-01-25	29.44
2323	7	2026-01-26	23.74
2324	7	2026-01-27	17.53
2325	7	2026-01-28	29.67
2326	7	2026-01-29	24.75
2327	7	2026-01-30	18.18
2328	7	2026-01-31	22.05
2329	7	2026-02-01	16.65
2330	7	2026-02-02	32.96
2331	7	2026-02-03	26.75
2332	7	2026-02-04	14.30
2333	7	2026-02-05	34.90
2334	7	2026-02-06	16.19
2335	7	2026-02-07	18.01
2336	7	2026-02-08	22.55
2337	7	2026-02-09	20.95
2338	7	2026-02-10	14.51
2339	7	2026-02-11	31.43
2340	7	2026-02-12	28.59
2341	7	2026-02-13	10.37
2342	7	2026-02-14	16.36
2343	7	2026-02-15	30.57
2344	7	2026-02-16	19.77
2345	7	2026-02-17	30.83
2346	7	2026-02-18	16.85
2347	7	2026-02-19	34.30
2348	7	2026-02-20	32.13
2349	7	2026-02-21	22.90
2350	7	2026-02-22	11.95
2351	7	2026-02-23	16.21
2352	7	2026-02-24	11.05
2353	7	2026-02-25	12.59
2354	7	2026-02-26	11.48
2355	7	2026-02-27	24.55
2356	7	2026-02-28	17.31
2357	7	2026-03-01	28.28
2358	7	2026-03-02	14.08
2359	7	2026-03-03	13.57
2360	7	2026-03-04	24.43
2361	7	2026-03-05	15.23
2362	7	2026-03-06	18.41
2363	7	2026-03-07	15.99
2364	7	2026-03-08	11.73
2365	7	2026-03-09	21.67
2366	7	2026-03-10	26.08
2367	7	2026-03-11	12.61
2368	7	2026-03-12	17.26
2369	7	2026-03-13	22.29
2370	7	2026-03-14	13.68
2371	7	2026-03-15	31.32
2372	7	2026-03-16	26.24
2373	7	2026-03-17	19.79
2374	7	2026-03-18	10.11
2375	7	2026-03-19	32.56
2376	7	2026-03-20	21.10
2377	7	2026-03-21	20.66
2378	7	2026-03-22	24.99
2379	7	2026-03-23	16.25
2380	7	2026-03-24	16.42
2381	7	2026-03-25	27.59
2382	7	2026-03-26	32.62
2383	7	2026-03-27	32.81
2384	7	2026-03-28	32.55
2385	7	2026-03-29	20.59
2386	7	2026-03-30	34.35
2387	7	2026-03-31	17.60
2388	7	2026-04-01	11.10
2389	7	2026-04-02	19.27
2390	7	2026-04-03	13.77
2391	7	2026-04-04	22.66
2392	7	2026-04-05	17.39
2393	7	2026-04-06	16.10
2394	7	2026-04-07	23.12
2395	7	2026-04-08	16.53
2396	7	2026-04-09	13.00
2397	7	2026-04-10	13.61
2398	7	2026-04-11	28.67
2399	7	2026-04-12	25.44
2400	7	2026-04-13	34.87
2401	7	2026-04-14	29.42
2402	7	2026-04-15	18.79
2403	7	2026-04-16	23.75
2404	7	2026-04-17	26.37
2405	7	2026-04-18	20.70
2406	7	2026-04-19	27.38
2407	7	2026-04-20	32.55
2408	7	2026-04-21	12.35
2409	7	2026-04-22	22.98
2410	7	2026-04-23	23.82
2411	7	2026-04-24	26.25
2412	7	2026-04-25	11.01
2413	7	2026-04-26	20.11
2414	7	2026-04-27	17.50
2415	7	2026-04-28	30.72
2416	8	2025-01-01	23.94
2417	8	2025-01-02	22.48
2418	8	2025-01-03	15.09
2419	8	2025-01-04	33.17
2420	8	2025-01-05	13.97
2421	8	2025-01-06	25.74
2422	8	2025-01-07	31.69
2423	8	2025-01-08	17.48
2424	8	2025-01-09	30.91
2425	8	2025-01-10	26.33
2426	8	2025-01-11	23.65
2427	8	2025-01-12	17.03
2428	8	2025-01-13	27.42
2429	8	2025-01-14	27.04
2430	8	2025-01-15	22.85
2431	8	2025-01-16	24.14
2432	8	2025-01-17	21.18
2433	8	2025-01-18	19.70
2434	8	2025-01-19	17.31
2435	8	2025-01-20	16.88
2436	8	2025-01-21	15.58
2437	8	2025-01-22	12.74
2438	8	2025-01-23	27.89
2439	8	2025-01-24	20.13
2440	8	2025-01-25	13.69
2441	8	2025-01-26	29.90
2442	8	2025-01-27	25.16
2443	8	2025-01-28	28.12
2444	8	2025-01-29	32.34
2445	8	2025-01-30	11.09
2446	8	2025-01-31	11.02
2447	8	2025-02-01	24.04
2448	8	2025-02-02	11.89
2449	8	2025-02-03	21.26
2450	8	2025-02-04	32.61
2451	8	2025-02-05	14.06
2452	8	2025-02-06	12.03
2453	8	2025-02-07	14.20
2454	8	2025-02-08	25.37
2455	8	2025-02-09	14.27
2456	8	2025-02-10	19.09
2457	8	2025-02-11	31.16
2458	8	2025-02-12	12.19
2459	8	2025-02-13	14.52
2460	8	2025-02-14	28.53
2461	8	2025-02-15	22.35
2462	8	2025-02-16	29.35
2463	8	2025-02-17	34.93
2464	8	2025-02-18	14.57
2465	8	2025-02-19	29.43
2466	8	2025-02-20	32.25
2467	8	2025-02-21	24.37
2468	8	2025-02-22	29.36
2469	8	2025-02-23	27.78
2470	8	2025-02-24	31.12
2471	8	2025-02-25	29.01
2472	8	2025-02-26	33.97
2473	8	2025-02-27	21.68
2474	8	2025-02-28	11.79
2475	8	2025-03-01	22.20
2476	8	2025-03-02	18.98
2477	8	2025-03-03	19.40
2478	8	2025-03-04	27.64
2479	8	2025-03-05	24.41
2480	8	2025-03-06	15.40
2481	8	2025-03-07	32.06
2482	8	2025-03-08	22.05
2483	8	2025-03-09	21.93
2484	8	2025-03-10	32.25
2485	8	2025-03-11	23.63
2486	8	2025-03-12	24.01
2487	8	2025-03-13	15.72
2488	8	2025-03-14	22.55
2489	8	2025-03-15	19.86
2490	8	2025-03-16	28.99
2491	8	2025-03-17	23.97
2492	8	2025-03-18	21.06
2493	8	2025-03-19	20.98
2494	8	2025-03-20	28.26
2495	8	2025-03-21	23.77
2496	8	2025-03-22	28.95
2497	8	2025-03-23	20.96
2498	8	2025-03-24	25.64
2499	8	2025-03-25	12.71
2500	8	2025-03-26	17.27
2501	8	2025-03-27	13.34
2502	8	2025-03-28	16.12
2503	8	2025-03-29	20.10
2504	8	2025-03-30	10.99
2505	8	2025-03-31	34.97
2506	8	2025-04-01	14.35
2507	8	2025-04-02	16.92
2508	8	2025-04-03	16.96
2509	8	2025-04-04	21.64
2510	8	2025-04-05	34.06
2511	8	2025-04-06	27.74
2512	8	2025-04-07	20.64
2513	8	2025-04-08	13.77
2514	8	2025-04-09	25.99
2515	8	2025-04-10	28.79
2516	8	2025-04-11	21.94
2517	8	2025-04-12	25.58
2518	8	2025-04-13	33.36
2519	8	2025-04-14	12.45
2520	8	2025-04-15	14.46
2521	8	2025-04-16	14.41
2522	8	2025-04-17	15.38
2523	8	2025-04-18	33.60
2524	8	2025-04-19	10.23
2525	8	2025-04-20	28.75
2526	8	2025-04-21	19.17
2527	8	2025-04-22	15.11
2528	8	2025-04-23	21.29
2529	8	2025-04-24	28.16
2530	8	2025-04-25	16.77
2531	8	2025-04-26	13.77
2532	8	2025-04-27	19.62
2533	8	2025-04-28	12.93
2534	8	2025-04-29	27.18
2535	8	2025-04-30	20.32
2536	8	2025-05-01	13.38
2537	8	2025-05-02	24.81
2538	8	2025-05-03	33.78
2539	8	2025-05-04	29.12
2540	8	2025-05-05	28.69
2541	8	2025-05-06	27.49
2542	8	2025-05-07	30.82
2543	8	2025-05-08	11.60
2544	8	2025-05-09	34.65
2545	8	2025-05-10	27.21
2546	8	2025-05-11	11.81
2547	8	2025-05-12	19.05
2548	8	2025-05-13	23.50
2549	8	2025-05-14	14.48
2550	8	2025-05-15	10.49
2551	8	2025-05-16	30.67
2552	8	2025-05-17	27.28
2553	8	2025-05-18	11.80
2554	8	2025-05-19	24.11
2555	8	2025-05-20	20.88
2556	8	2025-05-21	28.03
2557	8	2025-05-22	27.04
2558	8	2025-05-23	10.07
2559	8	2025-05-24	24.97
2560	8	2025-05-25	14.85
2561	8	2025-05-26	31.44
2562	8	2025-05-27	30.74
2563	8	2025-05-28	10.33
2564	8	2025-05-29	26.48
2565	8	2025-05-30	21.05
2566	8	2025-05-31	29.26
2567	8	2025-06-01	29.32
2568	8	2025-06-02	26.90
2569	8	2025-06-03	23.90
2570	8	2025-06-04	34.67
2571	8	2025-06-05	30.68
2572	8	2025-06-06	26.46
2573	8	2025-06-07	19.52
2574	8	2025-06-08	20.91
2575	8	2025-06-09	30.05
2576	8	2025-06-10	26.07
2577	8	2025-06-11	19.90
2578	8	2025-06-12	28.26
2579	8	2025-06-13	33.22
2580	8	2025-06-14	16.82
2581	8	2025-06-15	18.50
2582	8	2025-06-16	26.84
2583	8	2025-06-17	29.94
2584	8	2025-06-18	31.86
2585	8	2025-06-19	13.47
2586	8	2025-06-20	28.07
2587	8	2025-06-21	22.45
2588	8	2025-06-22	24.05
2589	8	2025-06-23	26.29
2590	8	2025-06-24	10.61
2591	8	2025-06-25	30.79
2592	8	2025-06-26	17.98
2593	8	2025-06-27	23.76
2594	8	2025-06-28	24.56
2595	8	2025-06-29	16.94
2596	8	2025-06-30	12.72
2597	8	2025-07-01	10.77
2598	8	2025-07-02	26.00
2599	8	2025-07-03	25.76
2600	8	2025-07-04	20.92
2601	8	2025-07-05	17.14
2602	8	2025-07-06	23.49
2603	8	2025-07-07	14.22
2604	8	2025-07-08	32.53
2605	8	2025-07-09	32.62
2606	8	2025-07-10	18.53
2607	8	2025-07-11	33.96
2608	8	2025-07-12	15.53
2609	8	2025-07-13	29.34
2610	8	2025-07-14	19.87
2611	8	2025-07-15	16.44
2612	8	2025-07-16	24.80
2613	8	2025-07-17	10.43
2614	8	2025-07-18	30.12
2615	8	2025-07-19	13.21
2616	8	2025-07-20	25.25
2617	8	2025-07-21	20.48
2618	8	2025-07-22	21.73
2619	8	2025-07-23	23.79
2620	8	2025-07-24	32.64
2621	8	2025-07-25	17.09
2622	8	2025-07-26	20.10
2623	8	2025-07-27	10.61
2624	8	2025-07-28	19.11
2625	8	2025-07-29	19.66
2626	8	2025-07-30	19.43
2627	8	2025-07-31	10.36
2628	8	2025-08-01	30.45
2629	8	2025-08-02	20.69
2630	8	2025-08-03	23.62
2631	8	2025-08-04	14.83
2632	8	2025-08-05	30.56
2633	8	2025-08-06	21.96
2634	8	2025-08-07	33.72
2635	8	2025-08-08	31.66
2636	8	2025-08-09	23.45
2637	8	2025-08-10	13.91
2638	8	2025-08-11	27.32
2639	8	2025-08-12	14.56
2640	8	2025-08-13	19.72
2641	8	2025-08-14	31.04
2642	8	2025-08-15	16.12
2643	8	2025-08-16	34.15
2644	8	2025-08-17	15.59
2645	8	2025-08-18	12.76
2646	8	2025-08-19	25.14
2647	8	2025-08-20	19.50
2648	8	2025-08-21	30.37
2649	8	2025-08-22	32.79
2650	8	2025-08-23	14.18
2651	8	2025-08-24	23.83
2652	8	2025-08-25	32.13
2653	8	2025-08-26	16.33
2654	8	2025-08-27	20.46
2655	8	2025-08-28	30.05
2656	8	2025-08-29	33.99
2657	8	2025-08-30	24.61
2658	8	2025-08-31	34.91
2659	8	2025-09-01	17.50
2660	8	2025-09-02	14.64
2661	8	2025-09-03	17.05
2662	8	2025-09-04	12.00
2663	8	2025-09-05	24.89
2664	8	2025-09-06	34.59
2665	8	2025-09-07	21.93
2666	8	2025-09-08	14.51
2667	8	2025-09-09	16.58
2668	8	2025-09-10	33.60
2669	8	2025-09-11	20.99
2670	8	2025-09-12	26.71
2671	8	2025-09-13	24.44
2672	8	2025-09-14	30.52
2673	8	2025-09-15	19.55
2674	8	2025-09-16	12.90
2675	8	2025-09-17	14.14
2676	8	2025-09-18	33.25
2677	8	2025-09-19	17.92
2678	8	2025-09-20	26.01
2679	8	2025-09-21	29.48
2680	8	2025-09-22	18.64
2681	8	2025-09-23	22.75
2682	8	2025-09-24	34.89
2683	8	2025-09-25	24.92
2684	8	2025-09-26	28.43
2685	8	2025-09-27	17.00
2686	8	2025-09-28	31.94
2687	8	2025-09-29	14.22
2688	8	2025-09-30	26.35
2689	8	2025-10-01	26.09
2690	8	2025-10-02	11.13
2691	8	2025-10-03	13.45
2692	8	2025-10-04	28.61
2693	8	2025-10-05	17.93
2694	8	2025-10-06	33.97
2695	8	2025-10-07	29.61
2696	8	2025-10-08	27.70
2697	8	2025-10-09	24.01
2698	8	2025-10-10	13.67
2699	8	2025-10-11	26.53
2700	8	2025-10-12	18.73
2701	8	2025-10-13	26.69
2702	8	2025-10-14	20.29
2703	8	2025-10-15	24.82
2704	8	2025-10-16	13.04
2705	8	2025-10-17	22.57
2706	8	2025-10-18	34.61
2707	8	2025-10-19	22.64
2708	8	2025-10-20	34.06
2709	8	2025-10-21	25.70
2710	8	2025-10-22	18.86
2711	8	2025-10-23	21.80
2712	8	2025-10-24	24.32
2713	8	2025-10-25	19.67
2714	8	2025-10-26	19.56
2715	8	2025-10-27	17.48
2716	8	2025-10-28	13.73
2717	8	2025-10-29	31.98
2718	8	2025-10-30	16.26
2719	8	2025-10-31	15.53
2720	8	2025-11-01	29.82
2721	8	2025-11-02	31.40
2722	8	2025-11-03	20.30
2723	8	2025-11-04	34.45
2724	8	2025-11-05	22.74
2725	8	2025-11-06	17.87
2726	8	2025-11-07	24.70
2727	8	2025-11-08	17.78
2728	8	2025-11-09	33.72
2729	8	2025-11-10	17.27
2730	8	2025-11-11	12.28
2731	8	2025-11-12	15.87
2732	8	2025-11-13	31.61
2733	8	2025-11-14	30.22
2734	8	2025-11-15	30.72
2735	8	2025-11-16	33.63
2736	8	2025-11-17	26.41
2737	8	2025-11-18	10.14
2738	8	2025-11-19	16.73
2739	8	2025-11-20	20.38
2740	8	2025-11-21	10.69
2741	8	2025-11-22	16.41
2742	8	2025-11-23	25.81
2743	8	2025-11-24	19.49
2744	8	2025-11-25	12.49
2745	8	2025-11-26	19.26
2746	8	2025-11-27	23.74
2747	8	2025-11-28	14.08
2748	8	2025-11-29	29.37
2749	8	2025-11-30	20.95
2750	8	2025-12-01	10.02
2751	8	2025-12-02	29.18
2752	8	2025-12-03	27.80
2753	8	2025-12-04	21.83
2754	8	2025-12-05	10.97
2755	8	2025-12-06	28.52
2756	8	2025-12-07	12.07
2757	8	2025-12-08	24.64
2758	8	2025-12-09	27.00
2759	8	2025-12-10	26.68
2760	8	2025-12-11	25.87
2761	8	2025-12-12	20.54
2762	8	2025-12-13	18.98
2763	8	2025-12-14	29.77
2764	8	2025-12-15	33.79
2765	8	2025-12-16	17.41
2766	8	2025-12-17	14.62
2767	8	2025-12-18	11.34
2768	8	2025-12-19	17.94
2769	8	2025-12-20	17.25
2770	8	2025-12-21	26.90
2771	8	2025-12-22	17.08
2772	8	2025-12-23	27.77
2773	8	2025-12-24	19.57
2774	8	2025-12-25	31.98
2775	8	2025-12-26	33.43
2776	8	2025-12-27	14.37
2777	8	2025-12-28	21.93
2778	8	2025-12-29	12.94
2779	8	2025-12-30	10.70
2780	8	2025-12-31	10.79
2781	8	2026-01-01	29.70
2782	8	2026-01-02	12.41
2783	8	2026-01-03	29.60
2784	8	2026-01-04	34.28
2785	8	2026-01-05	25.79
2786	8	2026-01-06	23.48
2787	8	2026-01-07	26.55
2788	8	2026-01-08	16.39
2789	8	2026-01-09	33.11
2790	8	2026-01-10	15.30
2791	8	2026-01-11	16.72
2792	8	2026-01-12	21.41
2793	8	2026-01-13	15.50
2794	8	2026-01-14	19.35
2795	8	2026-01-15	33.27
2796	8	2026-01-16	24.84
2797	8	2026-01-17	30.30
2798	8	2026-01-18	31.10
2799	8	2026-01-19	29.31
2800	8	2026-01-20	10.64
2801	8	2026-01-21	33.96
2802	8	2026-01-22	17.57
2803	8	2026-01-23	16.32
2804	8	2026-01-24	14.69
2805	8	2026-01-25	27.39
2806	8	2026-01-26	34.84
2807	8	2026-01-27	29.61
2808	8	2026-01-28	16.89
2809	8	2026-01-29	12.41
2810	8	2026-01-30	13.47
2811	8	2026-01-31	18.90
2812	8	2026-02-01	19.66
2813	8	2026-02-02	20.29
2814	8	2026-02-03	14.84
2815	8	2026-02-04	28.06
2816	8	2026-02-05	33.04
2817	8	2026-02-06	18.19
2818	8	2026-02-07	23.01
2819	8	2026-02-08	21.56
2820	8	2026-02-09	17.75
2821	8	2026-02-10	21.17
2822	8	2026-02-11	19.35
2823	8	2026-02-12	33.44
2824	8	2026-02-13	29.70
2825	8	2026-02-14	28.87
2826	8	2026-02-15	28.83
2827	8	2026-02-16	14.94
2828	8	2026-02-17	18.22
2829	8	2026-02-18	24.28
2830	8	2026-02-19	23.71
2831	8	2026-02-20	26.47
2832	8	2026-02-21	10.08
2833	8	2026-02-22	33.73
2834	8	2026-02-23	24.96
2835	8	2026-02-24	15.97
2836	8	2026-02-25	19.09
2837	8	2026-02-26	14.84
2838	8	2026-02-27	19.24
2839	8	2026-02-28	13.34
2840	8	2026-03-01	21.04
2841	8	2026-03-02	11.20
2842	8	2026-03-03	26.07
2843	8	2026-03-04	27.11
2844	8	2026-03-05	32.03
2845	8	2026-03-06	24.24
2846	8	2026-03-07	32.26
2847	8	2026-03-08	23.21
2848	8	2026-03-09	19.62
2849	8	2026-03-10	22.77
2850	8	2026-03-11	20.48
2851	8	2026-03-12	14.92
2852	8	2026-03-13	31.39
2853	8	2026-03-14	30.48
2854	8	2026-03-15	18.33
2855	8	2026-03-16	13.95
2856	8	2026-03-17	13.91
2857	8	2026-03-18	20.61
2858	8	2026-03-19	25.28
2859	8	2026-03-20	33.60
2860	8	2026-03-21	19.92
2861	8	2026-03-22	11.15
2862	8	2026-03-23	15.31
2863	8	2026-03-24	10.53
2864	8	2026-03-25	12.03
2865	8	2026-03-26	13.75
2866	8	2026-03-27	30.20
2867	8	2026-03-28	30.17
2868	8	2026-03-29	29.16
2869	8	2026-03-30	32.02
2870	8	2026-03-31	16.42
2871	8	2026-04-01	13.04
2872	8	2026-04-02	15.76
2873	8	2026-04-03	22.41
2874	8	2026-04-04	14.76
2875	8	2026-04-05	16.30
2876	8	2026-04-06	10.58
2877	8	2026-04-07	26.42
2878	8	2026-04-08	11.64
2879	8	2026-04-09	22.78
2880	8	2026-04-10	10.24
2881	8	2026-04-11	27.64
2882	8	2026-04-12	25.20
2883	8	2026-04-13	30.81
2884	8	2026-04-14	11.91
2885	8	2026-04-15	23.89
2886	8	2026-04-16	30.13
2887	8	2026-04-17	30.98
2888	8	2026-04-18	16.86
2889	8	2026-04-19	13.53
2890	8	2026-04-20	16.83
2891	8	2026-04-21	20.03
2892	8	2026-04-22	19.24
2893	8	2026-04-23	31.03
2894	8	2026-04-24	19.92
2895	8	2026-04-25	26.98
2896	8	2026-04-26	30.43
2897	8	2026-04-27	13.25
2898	8	2026-04-28	34.84
2899	9	2025-01-01	23.48
2900	9	2025-01-02	32.77
2901	9	2025-01-03	14.21
2902	9	2025-01-04	15.21
2903	9	2025-01-05	14.65
2904	9	2025-01-06	23.68
2905	9	2025-01-07	23.22
2906	9	2025-01-08	27.47
2907	9	2025-01-09	17.19
2908	9	2025-01-10	20.78
2909	9	2025-01-11	14.88
2910	9	2025-01-12	29.88
2911	9	2025-01-13	21.59
2912	9	2025-01-14	28.55
2913	9	2025-01-15	30.26
2914	9	2025-01-16	11.17
2915	9	2025-01-17	15.58
2916	9	2025-01-18	22.69
2917	9	2025-01-19	22.30
2918	9	2025-01-20	24.67
2919	9	2025-01-21	14.92
2920	9	2025-01-22	32.02
2921	9	2025-01-23	21.92
2922	9	2025-01-24	25.01
2923	9	2025-01-25	27.70
2924	9	2025-01-26	22.16
2925	9	2025-01-27	25.28
2926	9	2025-01-28	30.32
2927	9	2025-01-29	10.23
2928	9	2025-01-30	31.19
2929	9	2025-01-31	15.39
2930	9	2025-02-01	14.28
2931	9	2025-02-02	31.64
2932	9	2025-02-03	33.51
2933	9	2025-02-04	21.86
2934	9	2025-02-05	33.31
2935	9	2025-02-06	16.74
2936	9	2025-02-07	32.92
2937	9	2025-02-08	33.13
2938	9	2025-02-09	32.98
2939	9	2025-02-10	28.88
2940	9	2025-02-11	18.41
2941	9	2025-02-12	14.39
2942	9	2025-02-13	23.69
2943	9	2025-02-14	31.75
2944	9	2025-02-15	17.61
2945	9	2025-02-16	13.03
2946	9	2025-02-17	11.38
2947	9	2025-02-18	25.52
2948	9	2025-02-19	22.54
2949	9	2025-02-20	13.94
2950	9	2025-02-21	27.85
2951	9	2025-02-22	32.44
2952	9	2025-02-23	16.79
2953	9	2025-02-24	11.66
2954	9	2025-02-25	29.73
2955	9	2025-02-26	11.56
2956	9	2025-02-27	14.80
2957	9	2025-02-28	25.85
2958	9	2025-03-01	12.46
2959	9	2025-03-02	34.61
2960	9	2025-03-03	32.30
2961	9	2025-03-04	13.40
2962	9	2025-03-05	17.62
2963	9	2025-03-06	20.95
2964	9	2025-03-07	21.09
2965	9	2025-03-08	33.59
2966	9	2025-03-09	17.28
2967	9	2025-03-10	13.82
2968	9	2025-03-11	27.45
2969	9	2025-03-12	12.98
2970	9	2025-03-13	19.31
2971	9	2025-03-14	25.98
2972	9	2025-03-15	12.75
2973	9	2025-03-16	19.82
2974	9	2025-03-17	15.18
2975	9	2025-03-18	17.35
2976	9	2025-03-19	24.78
2977	9	2025-03-20	12.59
2978	9	2025-03-21	23.17
2979	9	2025-03-22	13.39
2980	9	2025-03-23	11.54
2981	9	2025-03-24	13.30
2982	9	2025-03-25	18.18
2983	9	2025-03-26	33.96
2984	9	2025-03-27	23.27
2985	9	2025-03-28	29.82
2986	9	2025-03-29	19.27
2987	9	2025-03-30	26.81
2988	9	2025-03-31	31.68
2989	9	2025-04-01	19.98
2990	9	2025-04-02	11.94
2991	9	2025-04-03	12.67
2992	9	2025-04-04	26.39
2993	9	2025-04-05	16.94
2994	9	2025-04-06	20.35
2995	9	2025-04-07	26.46
2996	9	2025-04-08	14.82
2997	9	2025-04-09	22.05
2998	9	2025-04-10	19.99
2999	9	2025-04-11	10.09
3000	9	2025-04-12	29.14
3001	9	2025-04-13	10.19
3002	9	2025-04-14	33.81
3003	9	2025-04-15	15.24
3004	9	2025-04-16	33.25
3005	9	2025-04-17	15.28
3006	9	2025-04-18	19.26
3007	9	2025-04-19	24.08
3008	9	2025-04-20	26.40
3009	9	2025-04-21	14.61
3010	9	2025-04-22	13.34
3011	9	2025-04-23	23.13
3012	9	2025-04-24	26.52
3013	9	2025-04-25	33.98
3014	9	2025-04-26	29.31
3015	9	2025-04-27	13.95
3016	9	2025-04-28	34.51
3017	9	2025-04-29	21.05
3018	9	2025-04-30	20.61
3019	9	2025-05-01	14.04
3020	9	2025-05-02	28.52
3021	9	2025-05-03	24.70
3022	9	2025-05-04	10.24
3023	9	2025-05-05	27.68
3024	9	2025-05-06	33.05
3025	9	2025-05-07	29.47
3026	9	2025-05-08	29.71
3027	9	2025-05-09	25.14
3028	9	2025-05-10	29.12
3029	9	2025-05-11	11.69
3030	9	2025-05-12	10.42
3031	9	2025-05-13	10.21
3032	9	2025-05-14	18.51
3033	9	2025-05-15	12.75
3034	9	2025-05-16	12.32
3035	9	2025-05-17	29.21
3036	9	2025-05-18	23.57
3037	9	2025-05-19	18.48
3038	9	2025-05-20	32.23
3039	9	2025-05-21	14.66
3040	9	2025-05-22	13.98
3041	9	2025-05-23	32.62
3042	9	2025-05-24	28.78
3043	9	2025-05-25	12.34
3044	9	2025-05-26	17.56
3045	9	2025-05-27	19.79
3046	9	2025-05-28	28.99
3047	9	2025-05-29	30.62
3048	9	2025-05-30	27.94
3049	9	2025-05-31	32.93
3050	9	2025-06-01	12.02
3051	9	2025-06-02	19.53
3052	9	2025-06-03	14.51
3053	9	2025-06-04	23.30
3054	9	2025-06-05	13.87
3055	9	2025-06-06	28.57
3056	9	2025-06-07	13.49
3057	9	2025-06-08	28.46
3058	9	2025-06-09	11.21
3059	9	2025-06-10	15.69
3060	9	2025-06-11	15.81
3061	9	2025-06-12	34.48
3062	9	2025-06-13	14.17
3063	9	2025-06-14	15.04
3064	9	2025-06-15	27.88
3065	9	2025-06-16	15.94
3066	9	2025-06-17	13.03
3067	9	2025-06-18	33.97
3068	9	2025-06-19	11.67
3069	9	2025-06-20	31.35
3070	9	2025-06-21	29.45
3071	9	2025-06-22	31.62
3072	9	2025-06-23	30.93
3073	9	2025-06-24	25.09
3074	9	2025-06-25	17.98
3075	9	2025-06-26	19.96
3076	9	2025-06-27	15.29
3077	9	2025-06-28	23.00
3078	9	2025-06-29	28.90
3079	9	2025-06-30	14.41
3080	9	2025-07-01	33.04
3081	9	2025-07-02	28.61
3082	9	2025-07-03	12.55
3083	9	2025-07-04	11.96
3084	9	2025-07-05	30.35
3085	9	2025-07-06	19.50
3086	9	2025-07-07	13.14
3087	9	2025-07-08	21.13
3088	9	2025-07-09	19.87
3089	9	2025-07-10	30.38
3090	9	2025-07-11	28.64
3091	9	2025-07-12	25.50
3092	9	2025-07-13	29.94
3093	9	2025-07-14	13.66
3094	9	2025-07-15	30.39
3095	9	2025-07-16	20.64
3096	9	2025-07-17	30.16
3097	9	2025-07-18	24.66
3098	9	2025-07-19	29.63
3099	9	2025-07-20	16.14
3100	9	2025-07-21	10.34
3101	9	2025-07-22	20.22
3102	9	2025-07-23	12.44
3103	9	2025-07-24	30.39
3104	9	2025-07-25	11.10
3105	9	2025-07-26	25.75
3106	9	2025-07-27	30.15
3107	9	2025-07-28	20.27
3108	9	2025-07-29	30.52
3109	9	2025-07-30	25.90
3110	9	2025-07-31	11.16
3111	9	2025-08-01	21.73
3112	9	2025-08-02	24.33
3113	9	2025-08-03	31.78
3114	9	2025-08-04	26.10
3115	9	2025-08-05	15.23
3116	9	2025-08-06	32.77
3117	9	2025-08-07	31.38
3118	9	2025-08-08	20.28
3119	9	2025-08-09	34.75
3120	9	2025-08-10	24.60
3121	9	2025-08-11	14.98
3122	9	2025-08-12	18.56
3123	9	2025-08-13	13.82
3124	9	2025-08-14	21.86
3125	9	2025-08-15	17.82
3126	9	2025-08-16	13.93
3127	9	2025-08-17	25.61
3128	9	2025-08-18	19.61
3129	9	2025-08-19	34.93
3130	9	2025-08-20	31.53
3131	9	2025-08-21	23.10
3132	9	2025-08-22	17.64
3133	9	2025-08-23	15.24
3134	9	2025-08-24	14.39
3135	9	2025-08-25	30.98
3136	9	2025-08-26	21.61
3137	9	2025-08-27	14.24
3138	9	2025-08-28	16.23
3139	9	2025-08-29	26.33
3140	9	2025-08-30	20.99
3141	9	2025-08-31	13.54
3142	9	2025-09-01	33.86
3143	9	2025-09-02	25.76
3144	9	2025-09-03	30.22
3145	9	2025-09-04	29.80
3146	9	2025-09-05	25.09
3147	9	2025-09-06	23.30
3148	9	2025-09-07	21.49
3149	9	2025-09-08	10.97
3150	9	2025-09-09	25.91
3151	9	2025-09-10	31.99
3152	9	2025-09-11	13.56
3153	9	2025-09-12	31.90
3154	9	2025-09-13	10.68
3155	9	2025-09-14	14.70
3156	9	2025-09-15	31.39
3157	9	2025-09-16	33.84
3158	9	2025-09-17	12.92
3159	9	2025-09-18	25.24
3160	9	2025-09-19	18.30
3161	9	2025-09-20	34.10
3162	9	2025-09-21	18.03
3163	9	2025-09-22	17.39
3164	9	2025-09-23	13.13
3165	9	2025-09-24	26.22
3166	9	2025-09-25	20.10
3167	9	2025-09-26	28.37
3168	9	2025-09-27	17.80
3169	9	2025-09-28	26.37
3170	9	2025-09-29	13.31
3171	9	2025-09-30	33.68
3172	9	2025-10-01	12.82
3173	9	2025-10-02	25.95
3174	9	2025-10-03	16.57
3175	9	2025-10-04	32.63
3176	9	2025-10-05	19.98
3177	9	2025-10-06	30.41
3178	9	2025-10-07	14.96
3179	9	2025-10-08	22.78
3180	9	2025-10-09	25.38
3181	9	2025-10-10	11.43
3182	9	2025-10-11	10.53
3183	9	2025-10-12	11.44
3184	9	2025-10-13	29.31
3185	9	2025-10-14	20.14
3186	9	2025-10-15	32.48
3187	9	2025-10-16	18.63
3188	9	2025-10-17	27.04
3189	9	2025-10-18	12.92
3190	9	2025-10-19	12.90
3191	9	2025-10-20	14.76
3192	9	2025-10-21	27.66
3193	9	2025-10-22	24.89
3194	9	2025-10-23	20.01
3195	9	2025-10-24	20.63
3196	9	2025-10-25	29.10
3197	9	2025-10-26	11.35
3198	9	2025-10-27	19.90
3199	9	2025-10-28	16.66
3200	9	2025-10-29	34.95
3201	9	2025-10-30	13.49
3202	9	2025-10-31	30.32
3203	9	2025-11-01	28.51
3204	9	2025-11-02	28.77
3205	9	2025-11-03	24.64
3206	9	2025-11-04	27.21
3207	9	2025-11-05	25.94
3208	9	2025-11-06	31.89
3209	9	2025-11-07	25.72
3210	9	2025-11-08	10.08
3211	9	2025-11-09	18.27
3212	9	2025-11-10	34.77
3213	9	2025-11-11	25.39
3214	9	2025-11-12	27.27
3215	9	2025-11-13	32.84
3216	9	2025-11-14	32.83
3217	9	2025-11-15	13.69
3218	9	2025-11-16	28.21
3219	9	2025-11-17	11.30
3220	9	2025-11-18	26.84
3221	9	2025-11-19	33.49
3222	9	2025-11-20	34.80
3223	9	2025-11-21	29.08
3224	9	2025-11-22	26.19
3225	9	2025-11-23	32.03
3226	9	2025-11-24	23.76
3227	9	2025-11-25	17.40
3228	9	2025-11-26	15.11
3229	9	2025-11-27	17.96
3230	9	2025-11-28	13.08
3231	9	2025-11-29	15.35
3232	9	2025-11-30	34.98
3233	9	2025-12-01	19.25
3234	9	2025-12-02	24.80
3235	9	2025-12-03	33.25
3236	9	2025-12-04	22.49
3237	9	2025-12-05	34.80
3238	9	2025-12-06	28.33
3239	9	2025-12-07	14.71
3240	9	2025-12-08	22.60
3241	9	2025-12-09	15.84
3242	9	2025-12-10	16.69
3243	9	2025-12-11	18.55
3244	9	2025-12-12	11.87
3245	9	2025-12-13	16.01
3246	9	2025-12-14	30.32
3247	9	2025-12-15	33.59
3248	9	2025-12-16	34.87
3249	9	2025-12-17	17.27
3250	9	2025-12-18	28.79
3251	9	2025-12-19	12.75
3252	9	2025-12-20	33.19
3253	9	2025-12-21	24.75
3254	9	2025-12-22	27.52
3255	9	2025-12-23	26.85
3256	9	2025-12-24	29.86
3257	9	2025-12-25	20.50
3258	9	2025-12-26	29.33
3259	9	2025-12-27	20.76
3260	9	2025-12-28	10.65
3261	9	2025-12-29	17.76
3262	9	2025-12-30	32.07
3263	9	2025-12-31	20.97
3264	9	2026-01-01	34.35
3265	9	2026-01-02	23.46
3266	9	2026-01-03	25.56
3267	9	2026-01-04	19.15
3268	9	2026-01-05	16.11
3269	9	2026-01-06	32.52
3270	9	2026-01-07	21.38
3271	9	2026-01-08	22.60
3272	9	2026-01-09	31.68
3273	9	2026-01-10	23.57
3274	9	2026-01-11	34.22
3275	9	2026-01-12	12.19
3276	9	2026-01-13	28.26
3277	9	2026-01-14	15.24
3278	9	2026-01-15	27.14
3279	9	2026-01-16	26.12
3280	9	2026-01-17	33.45
3281	9	2026-01-18	12.01
3282	9	2026-01-19	16.28
3283	9	2026-01-20	17.21
3284	9	2026-01-21	31.90
3285	9	2026-01-22	31.27
3286	9	2026-01-23	17.92
3287	9	2026-01-24	34.77
3288	9	2026-01-25	24.34
3289	9	2026-01-26	11.00
3290	9	2026-01-27	10.68
3291	9	2026-01-28	23.08
3292	9	2026-01-29	13.38
3293	9	2026-01-30	13.97
3294	9	2026-01-31	24.95
3295	9	2026-02-01	27.48
3296	9	2026-02-02	19.41
3297	9	2026-02-03	34.14
3298	9	2026-02-04	20.55
3299	9	2026-02-05	23.16
3300	9	2026-02-06	29.04
3301	9	2026-02-07	11.64
3302	9	2026-02-08	19.16
3303	9	2026-02-09	30.52
3304	9	2026-02-10	15.81
3305	9	2026-02-11	10.95
3306	9	2026-02-12	19.55
3307	9	2026-02-13	21.12
3308	9	2026-02-14	30.23
3309	9	2026-02-15	29.43
3310	9	2026-02-16	10.39
3311	9	2026-02-17	30.26
3312	9	2026-02-18	18.50
3313	9	2026-02-19	18.15
3314	9	2026-02-20	32.67
3315	9	2026-02-21	15.55
3316	9	2026-02-22	21.69
3317	9	2026-02-23	13.72
3318	9	2026-02-24	33.25
3319	9	2026-02-25	15.35
3320	9	2026-02-26	21.41
3321	9	2026-02-27	15.65
3322	9	2026-02-28	15.79
3323	9	2026-03-01	15.24
3324	9	2026-03-02	16.93
3325	9	2026-03-03	11.81
3326	9	2026-03-04	10.58
3327	9	2026-03-05	22.72
3328	9	2026-03-06	25.01
3329	9	2026-03-07	33.30
3330	9	2026-03-08	26.72
3331	9	2026-03-09	25.97
3332	9	2026-03-10	21.10
3333	9	2026-03-11	16.51
3334	9	2026-03-12	28.79
3335	9	2026-03-13	29.34
3336	9	2026-03-14	20.72
3337	9	2026-03-15	30.77
3338	9	2026-03-16	14.50
3339	9	2026-03-17	19.25
3340	9	2026-03-18	16.86
3341	9	2026-03-19	18.29
3342	9	2026-03-20	17.64
3343	9	2026-03-21	30.22
3344	9	2026-03-22	32.32
3345	9	2026-03-23	12.96
3346	9	2026-03-24	27.98
3347	9	2026-03-25	31.23
3348	9	2026-03-26	13.09
3349	9	2026-03-27	26.63
3350	9	2026-03-28	32.78
3351	9	2026-03-29	21.58
3352	9	2026-03-30	23.75
3353	9	2026-03-31	25.29
3354	9	2026-04-01	27.81
3355	9	2026-04-02	24.32
3356	9	2026-04-03	28.57
3357	9	2026-04-04	30.02
3358	9	2026-04-05	22.09
3359	9	2026-04-06	25.27
3360	9	2026-04-07	34.64
3361	9	2026-04-08	29.29
3362	9	2026-04-09	11.84
3363	9	2026-04-10	23.82
3364	9	2026-04-11	25.40
3365	9	2026-04-12	32.74
3366	9	2026-04-13	15.20
3367	9	2026-04-14	22.27
3368	9	2026-04-15	23.60
3369	9	2026-04-16	30.16
3370	9	2026-04-17	33.17
3371	9	2026-04-18	15.75
3372	9	2026-04-19	29.03
3373	9	2026-04-20	27.55
3374	9	2026-04-21	30.89
3375	9	2026-04-22	21.03
3376	9	2026-04-23	11.72
3377	9	2026-04-24	10.03
3378	9	2026-04-25	27.35
3379	9	2026-04-26	18.33
3380	9	2026-04-27	13.15
3381	9	2026-04-28	15.66
3382	10	2025-01-01	29.21
3383	10	2025-01-02	13.38
3384	10	2025-01-03	13.14
3385	10	2025-01-04	23.05
3386	10	2025-01-05	16.36
3387	10	2025-01-06	22.33
3388	10	2025-01-07	13.47
3389	10	2025-01-08	20.28
3390	10	2025-01-09	23.22
3391	10	2025-01-10	25.95
3392	10	2025-01-11	29.03
3393	10	2025-01-12	13.60
3394	10	2025-01-13	16.46
3395	10	2025-01-14	23.58
3396	10	2025-01-15	13.87
3397	10	2025-01-16	25.00
3398	10	2025-01-17	31.81
3399	10	2025-01-18	31.69
3400	10	2025-01-19	14.36
3401	10	2025-01-20	14.23
3402	10	2025-01-21	16.49
3403	10	2025-01-22	28.96
3404	10	2025-01-23	31.78
3405	10	2025-01-24	27.07
3406	10	2025-01-25	20.26
3407	10	2025-01-26	12.34
3408	10	2025-01-27	16.35
3409	10	2025-01-28	14.33
3410	10	2025-01-29	32.17
3411	10	2025-01-30	21.27
3412	10	2025-01-31	18.97
3413	10	2025-02-01	33.06
3414	10	2025-02-02	24.01
3415	10	2025-02-03	18.87
3416	10	2025-02-04	16.90
3417	10	2025-02-05	30.04
3418	10	2025-02-06	10.56
3419	10	2025-02-07	15.68
3420	10	2025-02-08	27.78
3421	10	2025-02-09	33.14
3422	10	2025-02-10	11.61
3423	10	2025-02-11	21.55
3424	10	2025-02-12	30.27
3425	10	2025-02-13	10.38
3426	10	2025-02-14	21.93
3427	10	2025-02-15	10.54
3428	10	2025-02-16	10.69
3429	10	2025-02-17	26.74
3430	10	2025-02-18	28.88
3431	10	2025-02-19	15.39
3432	10	2025-02-20	10.17
3433	10	2025-02-21	28.37
3434	10	2025-02-22	33.04
3435	10	2025-02-23	29.52
3436	10	2025-02-24	30.49
3437	10	2025-02-25	11.75
3438	10	2025-02-26	17.45
3439	10	2025-02-27	13.84
3440	10	2025-02-28	26.37
3441	10	2025-03-01	18.65
3442	10	2025-03-02	25.78
3443	10	2025-03-03	28.46
3444	10	2025-03-04	29.40
3445	10	2025-03-05	22.04
3446	10	2025-03-06	28.35
3447	10	2025-03-07	16.10
3448	10	2025-03-08	18.64
3449	10	2025-03-09	29.71
3450	10	2025-03-10	12.83
3451	10	2025-03-11	28.71
3452	10	2025-03-12	31.71
3453	10	2025-03-13	31.79
3454	10	2025-03-14	25.78
3455	10	2025-03-15	20.93
3456	10	2025-03-16	18.07
3457	10	2025-03-17	13.83
3458	10	2025-03-18	29.56
3459	10	2025-03-19	11.46
3460	10	2025-03-20	31.46
3461	10	2025-03-21	30.65
3462	10	2025-03-22	28.16
3463	10	2025-03-23	17.70
3464	10	2025-03-24	26.71
3465	10	2025-03-25	24.08
3466	10	2025-03-26	19.03
3467	10	2025-03-27	12.13
3468	10	2025-03-28	20.35
3469	10	2025-03-29	10.07
3470	10	2025-03-30	14.92
3471	10	2025-03-31	18.28
3472	10	2025-04-01	22.65
3473	10	2025-04-02	30.14
3474	10	2025-04-03	20.38
3475	10	2025-04-04	19.23
3476	10	2025-04-05	34.06
3477	10	2025-04-06	12.64
3478	10	2025-04-07	15.28
3479	10	2025-04-08	25.07
3480	10	2025-04-09	28.19
3481	10	2025-04-10	33.33
3482	10	2025-04-11	22.66
3483	10	2025-04-12	11.02
3484	10	2025-04-13	15.81
3485	10	2025-04-14	30.82
3486	10	2025-04-15	30.74
3487	10	2025-04-16	14.68
3488	10	2025-04-17	13.02
3489	10	2025-04-18	27.91
3490	10	2025-04-19	18.97
3491	10	2025-04-20	34.98
3492	10	2025-04-21	20.18
3493	10	2025-04-22	22.18
3494	10	2025-04-23	26.79
3495	10	2025-04-24	26.40
3496	10	2025-04-25	25.18
3497	10	2025-04-26	34.79
3498	10	2025-04-27	24.17
3499	10	2025-04-28	33.28
3500	10	2025-04-29	22.28
3501	10	2025-04-30	29.93
3502	10	2025-05-01	33.68
3503	10	2025-05-02	33.13
3504	10	2025-05-03	22.02
3505	10	2025-05-04	34.44
3506	10	2025-05-05	14.88
3507	10	2025-05-06	11.55
3508	10	2025-05-07	32.30
3509	10	2025-05-08	31.60
3510	10	2025-05-09	19.20
3511	10	2025-05-10	16.02
3512	10	2025-05-11	31.69
3513	10	2025-05-12	30.48
3514	10	2025-05-13	28.02
3515	10	2025-05-14	22.28
3516	10	2025-05-15	30.43
3517	10	2025-05-16	27.83
3518	10	2025-05-17	33.39
3519	10	2025-05-18	29.66
3520	10	2025-05-19	23.61
3521	10	2025-05-20	12.82
3522	10	2025-05-21	28.32
3523	10	2025-05-22	25.25
3524	10	2025-05-23	22.34
3525	10	2025-05-24	27.35
3526	10	2025-05-25	16.55
3527	10	2025-05-26	14.55
3528	10	2025-05-27	23.50
3529	10	2025-05-28	22.14
3530	10	2025-05-29	18.72
3531	10	2025-05-30	12.76
3532	10	2025-05-31	29.32
3533	10	2025-06-01	12.93
3534	10	2025-06-02	27.70
3535	10	2025-06-03	18.06
3536	10	2025-06-04	19.08
3537	10	2025-06-05	23.49
3538	10	2025-06-06	28.05
3539	10	2025-06-07	24.93
3540	10	2025-06-08	18.85
3541	10	2025-06-09	13.60
3542	10	2025-06-10	10.50
3543	10	2025-06-11	32.55
3544	10	2025-06-12	19.03
3545	10	2025-06-13	29.85
3546	10	2025-06-14	12.05
3547	10	2025-06-15	20.89
3548	10	2025-06-16	22.82
3549	10	2025-06-17	28.83
3550	10	2025-06-18	14.15
3551	10	2025-06-19	28.04
3552	10	2025-06-20	29.18
3553	10	2025-06-21	15.85
3554	10	2025-06-22	23.48
3555	10	2025-06-23	21.17
3556	10	2025-06-24	32.79
3557	10	2025-06-25	28.17
3558	10	2025-06-26	25.66
3559	10	2025-06-27	22.79
3560	10	2025-06-28	24.35
3561	10	2025-06-29	14.62
3562	10	2025-06-30	14.54
3563	10	2025-07-01	25.16
3564	10	2025-07-02	25.01
3565	10	2025-07-03	17.98
3566	10	2025-07-04	21.05
3567	10	2025-07-05	30.61
3568	10	2025-07-06	24.33
3569	10	2025-07-07	19.04
3570	10	2025-07-08	31.60
3571	10	2025-07-09	25.39
3572	10	2025-07-10	19.97
3573	10	2025-07-11	23.29
3574	10	2025-07-12	32.11
3575	10	2025-07-13	27.42
3576	10	2025-07-14	31.09
3577	10	2025-07-15	20.58
3578	10	2025-07-16	21.52
3579	10	2025-07-17	19.63
3580	10	2025-07-18	32.70
3581	10	2025-07-19	13.76
3582	10	2025-07-20	23.15
3583	10	2025-07-21	21.56
3584	10	2025-07-22	33.20
3585	10	2025-07-23	25.53
3586	10	2025-07-24	33.99
3587	10	2025-07-25	24.04
3588	10	2025-07-26	26.50
3589	10	2025-07-27	34.81
3590	10	2025-07-28	21.64
3591	10	2025-07-29	28.84
3592	10	2025-07-30	15.53
3593	10	2025-07-31	24.35
3594	10	2025-08-01	23.60
3595	10	2025-08-02	22.09
3596	10	2025-08-03	13.12
3597	10	2025-08-04	28.83
3598	10	2025-08-05	33.21
3599	10	2025-08-06	10.85
3600	10	2025-08-07	28.11
3601	10	2025-08-08	32.72
3602	10	2025-08-09	18.15
3603	10	2025-08-10	29.52
3604	10	2025-08-11	23.92
3605	10	2025-08-12	29.72
3606	10	2025-08-13	30.62
3607	10	2025-08-14	17.00
3608	10	2025-08-15	26.40
3609	10	2025-08-16	14.15
3610	10	2025-08-17	27.62
3611	10	2025-08-18	10.85
3612	10	2025-08-19	18.90
3613	10	2025-08-20	13.14
3614	10	2025-08-21	27.92
3615	10	2025-08-22	12.27
3616	10	2025-08-23	34.30
3617	10	2025-08-24	13.66
3618	10	2025-08-25	22.18
3619	10	2025-08-26	34.94
3620	10	2025-08-27	30.62
3621	10	2025-08-28	21.97
3622	10	2025-08-29	26.45
3623	10	2025-08-30	28.68
3624	10	2025-08-31	20.67
3625	10	2025-09-01	32.27
3626	10	2025-09-02	15.68
3627	10	2025-09-03	12.28
3628	10	2025-09-04	32.68
3629	10	2025-09-05	15.52
3630	10	2025-09-06	14.99
3631	10	2025-09-07	34.54
3632	10	2025-09-08	13.09
3633	10	2025-09-09	26.70
3634	10	2025-09-10	26.47
3635	10	2025-09-11	22.63
3636	10	2025-09-12	28.63
3637	10	2025-09-13	20.93
3638	10	2025-09-14	15.01
3639	10	2025-09-15	32.07
3640	10	2025-09-16	27.35
3641	10	2025-09-17	33.53
3642	10	2025-09-18	18.02
3643	10	2025-09-19	31.60
3644	10	2025-09-20	24.36
3645	10	2025-09-21	33.94
3646	10	2025-09-22	21.20
3647	10	2025-09-23	23.56
3648	10	2025-09-24	20.97
3649	10	2025-09-25	20.93
3650	10	2025-09-26	26.99
3651	10	2025-09-27	22.53
3652	10	2025-09-28	15.01
3653	10	2025-09-29	14.34
3654	10	2025-09-30	23.53
3655	10	2025-10-01	34.95
3656	10	2025-10-02	34.87
3657	10	2025-10-03	32.11
3658	10	2025-10-04	26.67
3659	10	2025-10-05	25.98
3660	10	2025-10-06	21.03
3661	10	2025-10-07	10.05
3662	10	2025-10-08	30.55
3663	10	2025-10-09	22.30
3664	10	2025-10-10	26.13
3665	10	2025-10-11	15.64
3666	10	2025-10-12	26.52
3667	10	2025-10-13	34.15
3668	10	2025-10-14	22.63
3669	10	2025-10-15	28.14
3670	10	2025-10-16	17.32
3671	10	2025-10-17	33.42
3672	10	2025-10-18	34.06
3673	10	2025-10-19	17.84
3674	10	2025-10-20	18.89
3675	10	2025-10-21	20.64
3676	10	2025-10-22	23.31
3677	10	2025-10-23	33.27
3678	10	2025-10-24	32.76
3679	10	2025-10-25	31.77
3680	10	2025-10-26	23.86
3681	10	2025-10-27	21.04
3682	10	2025-10-28	27.30
3683	10	2025-10-29	24.13
3684	10	2025-10-30	24.68
3685	10	2025-10-31	24.61
3686	10	2025-11-01	11.98
3687	10	2025-11-02	29.89
3688	10	2025-11-03	34.08
3689	10	2025-11-04	28.70
3690	10	2025-11-05	26.72
3691	10	2025-11-06	26.29
3692	10	2025-11-07	15.45
3693	10	2025-11-08	14.17
3694	10	2025-11-09	21.94
3695	10	2025-11-10	11.10
3696	10	2025-11-11	26.95
3697	10	2025-11-12	11.39
3698	10	2025-11-13	17.40
3699	10	2025-11-14	17.51
3700	10	2025-11-15	26.17
3701	10	2025-11-16	32.26
3702	10	2025-11-17	31.67
3703	10	2025-11-18	23.56
3704	10	2025-11-19	28.23
3705	10	2025-11-20	24.11
3706	10	2025-11-21	11.44
3707	10	2025-11-22	11.73
3708	10	2025-11-23	26.63
3709	10	2025-11-24	25.32
3710	10	2025-11-25	10.69
3711	10	2025-11-26	24.97
3712	10	2025-11-27	30.60
3713	10	2025-11-28	28.24
3714	10	2025-11-29	11.01
3715	10	2025-11-30	34.22
3716	10	2025-12-01	15.81
3717	10	2025-12-02	31.97
3718	10	2025-12-03	13.00
3719	10	2025-12-04	33.88
3720	10	2025-12-05	21.31
3721	10	2025-12-06	23.50
3722	10	2025-12-07	19.19
3723	10	2025-12-08	29.71
3724	10	2025-12-09	20.67
3725	10	2025-12-10	11.64
3726	10	2025-12-11	30.67
3727	10	2025-12-12	22.96
3728	10	2025-12-13	32.35
3729	10	2025-12-14	19.37
3730	10	2025-12-15	11.05
3731	10	2025-12-16	26.43
3732	10	2025-12-17	32.01
3733	10	2025-12-18	17.63
3734	10	2025-12-19	28.76
3735	10	2025-12-20	33.38
3736	10	2025-12-21	13.14
3737	10	2025-12-22	29.23
3738	10	2025-12-23	33.04
3739	10	2025-12-24	19.93
3740	10	2025-12-25	31.93
3741	10	2025-12-26	34.30
3742	10	2025-12-27	23.20
3743	10	2025-12-28	24.62
3744	10	2025-12-29	14.30
3745	10	2025-12-30	29.64
3746	10	2025-12-31	11.32
3747	10	2026-01-01	27.12
3748	10	2026-01-02	31.15
3749	10	2026-01-03	29.85
3750	10	2026-01-04	15.12
3751	10	2026-01-05	26.79
3752	10	2026-01-06	28.38
3753	10	2026-01-07	25.16
3754	10	2026-01-08	24.60
3755	10	2026-01-09	19.40
3756	10	2026-01-10	20.51
3757	10	2026-01-11	13.07
3758	10	2026-01-12	12.63
3759	10	2026-01-13	23.25
3760	10	2026-01-14	27.43
3761	10	2026-01-15	13.51
3762	10	2026-01-16	27.28
3763	10	2026-01-17	20.90
3764	10	2026-01-18	10.43
3765	10	2026-01-19	22.57
3766	10	2026-01-20	13.95
3767	10	2026-01-21	20.60
3768	10	2026-01-22	26.20
3769	10	2026-01-23	31.26
3770	10	2026-01-24	12.98
3771	10	2026-01-25	15.89
3772	10	2026-01-26	26.14
3773	10	2026-01-27	15.04
3774	10	2026-01-28	13.76
3775	10	2026-01-29	17.47
3776	10	2026-01-30	27.47
3777	10	2026-01-31	18.33
3778	10	2026-02-01	14.67
3779	10	2026-02-02	33.91
3780	10	2026-02-03	11.45
3781	10	2026-02-04	24.64
3782	10	2026-02-05	21.24
3783	10	2026-02-06	15.66
3784	10	2026-02-07	21.55
3785	10	2026-02-08	29.76
3786	10	2026-02-09	22.91
3787	10	2026-02-10	23.77
3788	10	2026-02-11	28.22
3789	10	2026-02-12	16.48
3790	10	2026-02-13	10.98
3791	10	2026-02-14	18.35
3792	10	2026-02-15	15.11
3793	10	2026-02-16	33.96
3794	10	2026-02-17	18.83
3795	10	2026-02-18	12.57
3796	10	2026-02-19	17.97
3797	10	2026-02-20	10.61
3798	10	2026-02-21	21.47
3799	10	2026-02-22	19.58
3800	10	2026-02-23	27.37
3801	10	2026-02-24	33.63
3802	10	2026-02-25	20.12
3803	10	2026-02-26	26.43
3804	10	2026-02-27	31.70
3805	10	2026-02-28	30.73
3806	10	2026-03-01	32.70
3807	10	2026-03-02	34.78
3808	10	2026-03-03	23.26
3809	10	2026-03-04	16.03
3810	10	2026-03-05	33.80
3811	10	2026-03-06	23.71
3812	10	2026-03-07	20.20
3813	10	2026-03-08	33.98
3814	10	2026-03-09	25.12
3815	10	2026-03-10	33.00
3816	10	2026-03-11	11.10
3817	10	2026-03-12	23.98
3818	10	2026-03-13	16.28
3819	10	2026-03-14	15.14
3820	10	2026-03-15	14.08
3821	10	2026-03-16	21.56
3822	10	2026-03-17	25.52
3823	10	2026-03-18	27.13
3824	10	2026-03-19	29.39
3825	10	2026-03-20	16.09
3826	10	2026-03-21	26.95
3827	10	2026-03-22	24.76
3828	10	2026-03-23	10.93
3829	10	2026-03-24	26.27
3830	10	2026-03-25	22.90
3831	10	2026-03-26	29.19
3832	10	2026-03-27	33.49
3833	10	2026-03-28	30.87
3834	10	2026-03-29	24.91
3835	10	2026-03-30	21.50
3836	10	2026-03-31	27.95
3837	10	2026-04-01	30.04
3838	10	2026-04-02	24.94
3839	10	2026-04-03	10.10
3840	10	2026-04-04	28.76
3841	10	2026-04-05	26.50
3842	10	2026-04-06	16.75
3843	10	2026-04-07	20.56
3844	10	2026-04-08	12.51
3845	10	2026-04-09	16.53
3846	10	2026-04-10	15.94
3847	10	2026-04-11	33.52
3848	10	2026-04-12	28.78
3849	10	2026-04-13	28.97
3850	10	2026-04-14	12.88
3851	10	2026-04-15	21.41
3852	10	2026-04-16	20.01
3853	10	2026-04-17	14.23
3854	10	2026-04-18	23.85
3855	10	2026-04-19	11.19
3856	10	2026-04-20	28.93
3857	10	2026-04-21	18.14
3858	10	2026-04-22	31.30
3859	10	2026-04-23	28.38
3860	10	2026-04-24	30.68
3861	10	2026-04-25	27.19
3862	10	2026-04-26	32.87
3863	10	2026-04-27	20.52
3864	10	2026-04-28	10.71
\.


--
-- TOC entry 5079 (class 0 OID 35241)
-- Dependencies: 238
-- Data for Name: nutritional_efficiency; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.nutritional_efficiency (id, animal_id, measurement_date, feed_conversion_ratio, weight_gain_kg, notes) FROM stdin;
\.


--
-- TOC entry 5103 (class 0 OID 35439)
-- Dependencies: 262
-- Data for Name: payroll; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.payroll (id, employee_id, period, gross_salary, deductions, net_pay, payment_date) FROM stdin;
1	1	2025-01-01	450.00	45.00	405.00	2025-01-26
2	2	2025-01-01	700.00	70.00	630.00	2025-01-26
3	3	2025-01-01	600.00	60.00	540.00	2025-01-26
4	1	2025-02-01	450.00	45.00	405.00	2025-02-26
5	2	2025-02-01	700.00	70.00	630.00	2025-02-26
6	3	2025-02-01	600.00	60.00	540.00	2025-02-26
7	1	2025-03-01	450.00	45.00	405.00	2025-03-26
8	2	2025-03-01	700.00	70.00	630.00	2025-03-26
9	3	2025-03-01	600.00	60.00	540.00	2025-03-26
10	1	2025-04-01	450.00	45.00	405.00	2025-04-26
11	2	2025-04-01	700.00	70.00	630.00	2025-04-26
12	3	2025-04-01	600.00	60.00	540.00	2025-04-26
13	1	2025-05-01	450.00	45.00	405.00	2025-05-26
14	2	2025-05-01	700.00	70.00	630.00	2025-05-26
15	3	2025-05-01	600.00	60.00	540.00	2025-05-26
16	1	2025-06-01	450.00	45.00	405.00	2025-06-26
17	2	2025-06-01	700.00	70.00	630.00	2025-06-26
18	3	2025-06-01	600.00	60.00	540.00	2025-06-26
19	1	2025-07-01	450.00	45.00	405.00	2025-07-26
20	2	2025-07-01	700.00	70.00	630.00	2025-07-26
21	3	2025-07-01	600.00	60.00	540.00	2025-07-26
22	1	2025-08-01	450.00	45.00	405.00	2025-08-26
23	2	2025-08-01	700.00	70.00	630.00	2025-08-26
24	3	2025-08-01	600.00	60.00	540.00	2025-08-26
25	1	2025-09-01	450.00	45.00	405.00	2025-09-26
26	2	2025-09-01	700.00	70.00	630.00	2025-09-26
27	3	2025-09-01	600.00	60.00	540.00	2025-09-26
28	1	2025-10-01	450.00	45.00	405.00	2025-10-26
29	2	2025-10-01	700.00	70.00	630.00	2025-10-26
30	3	2025-10-01	600.00	60.00	540.00	2025-10-26
31	1	2025-11-01	450.00	45.00	405.00	2025-11-26
32	2	2025-11-01	700.00	70.00	630.00	2025-11-26
33	3	2025-11-01	600.00	60.00	540.00	2025-11-26
34	1	2025-12-01	450.00	45.00	405.00	2025-12-26
35	2	2025-12-01	700.00	70.00	630.00	2025-12-26
36	3	2025-12-01	600.00	60.00	540.00	2025-12-26
37	1	2026-01-01	450.00	45.00	405.00	2026-01-26
38	2	2026-01-01	700.00	70.00	630.00	2026-01-26
39	3	2026-01-01	600.00	60.00	540.00	2026-01-26
40	1	2026-02-01	450.00	45.00	405.00	2026-02-26
41	2	2026-02-01	700.00	70.00	630.00	2026-02-26
42	3	2026-02-01	600.00	60.00	540.00	2026-02-26
43	1	2026-03-01	450.00	45.00	405.00	2026-03-26
44	2	2026-03-01	700.00	70.00	630.00	2026-03-26
45	3	2026-03-01	600.00	60.00	540.00	2026-03-26
46	1	2026-04-01	450.00	45.00	405.00	2026-04-26
47	2	2026-04-01	700.00	70.00	630.00	2026-04-26
48	3	2026-04-01	600.00	60.00	540.00	2026-04-26
\.


--
-- TOC entry 5097 (class 0 OID 35384)
-- Dependencies: 256
-- Data for Name: purchase_order_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.purchase_order_details (id, order_id, item_type, food_id, supply_id, quantity, unit_price) FROM stdin;
1	1	alimento	1	\N	13.10	0.15
2	1	alimento	3	\N	17.14	0.40
3	1	alimento	7	\N	11.33	0.50
4	2	insumo	\N	1	3.47	2.50
5	2	insumo	\N	2	8.70	15.00
\.


--
-- TOC entry 5095 (class 0 OID 35370)
-- Dependencies: 254
-- Data for Name: purchase_orders; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.purchase_orders (id, supplier_id, order_date, expected_delivery, status, total_amount) FROM stdin;
1	1	2025-01-01	2025-01-16	recibida	100.17
2	1	2025-03-01	2025-03-16	recibida	130.15
3	1	2025-05-01	2025-05-16	recibida	156.87
4	1	2025-07-01	2025-07-16	recibida	58.01
5	1	2025-09-01	2025-09-16	recibida	194.05
6	1	2025-11-01	2025-11-16	recibida	54.74
7	1	2026-01-01	2026-01-16	recibida	234.09
8	1	2026-03-01	2026-03-16	recibida	162.88
\.


--
-- TOC entry 5099 (class 0 OID 35407)
-- Dependencies: 258
-- Data for Name: sales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sales (id, sale_type, product_type, animal_id, batch_id, slaughter_id, quantity, sale_date, total_amount, buyer_name, notes) FROM stdin;
1	producto	leche	\N	\N	\N	76.16	2025-01-01	58.21	Lácteos del Valle	\N
2	producto	leche	\N	\N	\N	109.54	2025-01-02	55.38	Lácteos del Valle	\N
3	producto	leche	\N	\N	\N	135.97	2025-01-03	32.20	Lácteos del Valle	\N
4	producto	leche	\N	\N	\N	143.07	2025-01-04	67.11	Lácteos del Valle	\N
5	producto	leche	\N	\N	\N	77.95	2025-01-05	33.86	Lácteos del Valle	\N
6	producto	leche	\N	\N	\N	60.91	2025-01-06	44.67	Lácteos del Valle	\N
7	producto	leche	\N	\N	\N	87.05	2025-01-07	58.15	Lácteos del Valle	\N
8	producto	leche	\N	\N	\N	51.21	2025-01-08	29.20	Lácteos del Valle	\N
9	producto	leche	\N	\N	\N	80.32	2025-01-09	58.33	Lácteos del Valle	\N
10	producto	leche	\N	\N	\N	56.92	2025-01-10	37.68	Lácteos del Valle	\N
11	producto	leche	\N	\N	\N	56.80	2025-01-11	47.63	Lácteos del Valle	\N
12	producto	leche	\N	\N	\N	71.22	2025-01-12	44.23	Lácteos del Valle	\N
13	producto	leche	\N	\N	\N	128.75	2025-01-13	64.45	Lácteos del Valle	\N
14	producto	leche	\N	\N	\N	94.00	2025-01-14	56.53	Lácteos del Valle	\N
15	producto	leche	\N	\N	\N	119.40	2025-01-15	49.67	Lácteos del Valle	\N
16	producto	leche	\N	\N	\N	142.17	2025-01-16	63.35	Lácteos del Valle	\N
17	producto	leche	\N	\N	\N	129.24	2025-01-17	30.77	Lácteos del Valle	\N
18	producto	leche	\N	\N	\N	77.71	2025-01-18	61.96	Lácteos del Valle	\N
19	producto	leche	\N	\N	\N	67.39	2025-01-19	34.72	Lácteos del Valle	\N
20	producto	leche	\N	\N	\N	85.49	2025-01-20	56.73	Lácteos del Valle	\N
21	producto	leche	\N	\N	\N	75.47	2025-01-21	62.33	Lácteos del Valle	\N
22	producto	leche	\N	\N	\N	134.30	2025-01-22	36.52	Lácteos del Valle	\N
23	producto	leche	\N	\N	\N	146.59	2025-01-23	30.03	Lácteos del Valle	\N
24	producto	leche	\N	\N	\N	124.36	2025-01-24	69.39	Lácteos del Valle	\N
25	producto	leche	\N	\N	\N	90.23	2025-01-25	29.47	Lácteos del Valle	\N
26	producto	leche	\N	\N	\N	110.43	2025-01-26	36.23	Lácteos del Valle	\N
27	producto	leche	\N	\N	\N	112.54	2025-01-27	25.42	Lácteos del Valle	\N
28	producto	leche	\N	\N	\N	135.29	2025-01-28	57.61	Lácteos del Valle	\N
29	producto	leche	\N	\N	\N	96.15	2025-01-29	67.25	Lácteos del Valle	\N
30	producto	leche	\N	\N	\N	70.46	2025-01-30	28.83	Lácteos del Valle	\N
31	producto	leche	\N	\N	\N	88.82	2025-01-31	35.10	Lácteos del Valle	\N
32	producto	leche	\N	\N	\N	149.39	2025-02-01	50.83	Lácteos del Valle	\N
33	producto	leche	\N	\N	\N	110.65	2025-02-02	62.03	Lácteos del Valle	\N
34	producto	leche	\N	\N	\N	64.42	2025-02-03	34.15	Lácteos del Valle	\N
35	producto	leche	\N	\N	\N	117.20	2025-02-04	55.52	Lácteos del Valle	\N
36	producto	leche	\N	\N	\N	114.88	2025-02-05	33.42	Lácteos del Valle	\N
37	producto	leche	\N	\N	\N	71.51	2025-02-06	52.10	Lácteos del Valle	\N
38	producto	leche	\N	\N	\N	104.89	2025-02-07	46.06	Lácteos del Valle	\N
39	producto	leche	\N	\N	\N	65.33	2025-02-08	53.48	Lácteos del Valle	\N
40	producto	leche	\N	\N	\N	88.92	2025-02-09	61.94	Lácteos del Valle	\N
41	producto	leche	\N	\N	\N	147.13	2025-02-10	68.23	Lácteos del Valle	\N
42	producto	leche	\N	\N	\N	86.59	2025-02-11	40.25	Lácteos del Valle	\N
43	producto	leche	\N	\N	\N	59.78	2025-02-12	66.97	Lácteos del Valle	\N
44	producto	leche	\N	\N	\N	101.44	2025-02-13	35.26	Lácteos del Valle	\N
45	producto	leche	\N	\N	\N	89.00	2025-02-14	64.28	Lácteos del Valle	\N
46	producto	leche	\N	\N	\N	79.68	2025-02-15	42.08	Lácteos del Valle	\N
47	producto	leche	\N	\N	\N	120.15	2025-02-16	30.29	Lácteos del Valle	\N
48	producto	leche	\N	\N	\N	131.63	2025-02-17	44.88	Lácteos del Valle	\N
49	producto	leche	\N	\N	\N	118.80	2025-02-18	49.65	Lácteos del Valle	\N
50	producto	leche	\N	\N	\N	70.84	2025-02-19	30.82	Lácteos del Valle	\N
51	producto	leche	\N	\N	\N	121.42	2025-02-20	54.29	Lácteos del Valle	\N
52	producto	leche	\N	\N	\N	149.27	2025-02-21	31.45	Lácteos del Valle	\N
53	producto	leche	\N	\N	\N	144.28	2025-02-22	54.97	Lácteos del Valle	\N
54	producto	leche	\N	\N	\N	126.74	2025-02-23	61.93	Lácteos del Valle	\N
55	producto	leche	\N	\N	\N	119.40	2025-02-24	64.69	Lácteos del Valle	\N
56	producto	leche	\N	\N	\N	144.66	2025-02-25	59.26	Lácteos del Valle	\N
57	producto	leche	\N	\N	\N	108.90	2025-02-26	52.25	Lácteos del Valle	\N
58	producto	leche	\N	\N	\N	139.26	2025-02-27	26.87	Lácteos del Valle	\N
59	producto	leche	\N	\N	\N	59.46	2025-02-28	50.48	Lácteos del Valle	\N
60	producto	leche	\N	\N	\N	138.19	2025-03-01	38.17	Lácteos del Valle	\N
61	producto	leche	\N	\N	\N	121.61	2025-03-02	67.89	Lácteos del Valle	\N
62	producto	leche	\N	\N	\N	133.58	2025-03-03	30.93	Lácteos del Valle	\N
63	producto	leche	\N	\N	\N	54.31	2025-03-04	31.35	Lácteos del Valle	\N
64	producto	leche	\N	\N	\N	135.19	2025-03-05	51.89	Lácteos del Valle	\N
65	producto	leche	\N	\N	\N	110.18	2025-03-06	42.19	Lácteos del Valle	\N
66	producto	leche	\N	\N	\N	51.76	2025-03-07	66.33	Lácteos del Valle	\N
67	producto	leche	\N	\N	\N	146.91	2025-03-08	41.08	Lácteos del Valle	\N
68	producto	leche	\N	\N	\N	66.03	2025-03-09	64.18	Lácteos del Valle	\N
69	producto	leche	\N	\N	\N	95.91	2025-03-10	33.83	Lácteos del Valle	\N
70	producto	leche	\N	\N	\N	122.30	2025-03-11	31.14	Lácteos del Valle	\N
71	producto	leche	\N	\N	\N	54.80	2025-03-12	54.61	Lácteos del Valle	\N
72	producto	leche	\N	\N	\N	140.98	2025-03-13	28.64	Lácteos del Valle	\N
73	producto	leche	\N	\N	\N	109.74	2025-03-14	66.23	Lácteos del Valle	\N
74	producto	leche	\N	\N	\N	68.23	2025-03-15	47.86	Lácteos del Valle	\N
75	producto	leche	\N	\N	\N	117.77	2025-03-16	68.84	Lácteos del Valle	\N
76	producto	leche	\N	\N	\N	60.99	2025-03-17	20.76	Lácteos del Valle	\N
77	producto	leche	\N	\N	\N	79.46	2025-03-18	62.23	Lácteos del Valle	\N
78	producto	leche	\N	\N	\N	74.53	2025-03-19	42.64	Lácteos del Valle	\N
79	producto	leche	\N	\N	\N	109.31	2025-03-20	29.10	Lácteos del Valle	\N
80	producto	leche	\N	\N	\N	80.24	2025-03-21	42.90	Lácteos del Valle	\N
81	producto	leche	\N	\N	\N	138.27	2025-03-22	49.83	Lácteos del Valle	\N
82	producto	leche	\N	\N	\N	84.17	2025-03-23	51.62	Lácteos del Valle	\N
83	producto	leche	\N	\N	\N	137.81	2025-03-24	40.53	Lácteos del Valle	\N
84	producto	leche	\N	\N	\N	74.15	2025-03-25	49.59	Lácteos del Valle	\N
85	producto	leche	\N	\N	\N	91.81	2025-03-26	48.96	Lácteos del Valle	\N
86	producto	leche	\N	\N	\N	99.99	2025-03-27	58.68	Lácteos del Valle	\N
87	producto	leche	\N	\N	\N	133.13	2025-03-28	53.49	Lácteos del Valle	\N
88	producto	leche	\N	\N	\N	63.56	2025-03-29	26.49	Lácteos del Valle	\N
89	producto	leche	\N	\N	\N	71.01	2025-03-30	20.44	Lácteos del Valle	\N
90	producto	leche	\N	\N	\N	64.96	2025-03-31	28.70	Lácteos del Valle	\N
91	producto	leche	\N	\N	\N	67.55	2025-04-01	68.12	Lácteos del Valle	\N
92	producto	leche	\N	\N	\N	93.12	2025-04-02	30.06	Lácteos del Valle	\N
93	producto	leche	\N	\N	\N	60.06	2025-04-03	44.44	Lácteos del Valle	\N
94	producto	leche	\N	\N	\N	135.07	2025-04-04	62.88	Lácteos del Valle	\N
95	producto	leche	\N	\N	\N	61.29	2025-04-05	53.61	Lácteos del Valle	\N
96	producto	leche	\N	\N	\N	147.32	2025-04-06	66.48	Lácteos del Valle	\N
97	producto	leche	\N	\N	\N	88.38	2025-04-07	53.11	Lácteos del Valle	\N
98	producto	leche	\N	\N	\N	120.67	2025-04-08	28.07	Lácteos del Valle	\N
99	producto	leche	\N	\N	\N	65.18	2025-04-09	33.83	Lácteos del Valle	\N
100	producto	leche	\N	\N	\N	140.83	2025-04-10	30.43	Lácteos del Valle	\N
101	producto	leche	\N	\N	\N	130.91	2025-04-11	26.95	Lácteos del Valle	\N
102	producto	leche	\N	\N	\N	119.91	2025-04-12	48.08	Lácteos del Valle	\N
103	producto	leche	\N	\N	\N	136.94	2025-04-13	35.15	Lácteos del Valle	\N
104	producto	leche	\N	\N	\N	112.27	2025-04-14	26.90	Lácteos del Valle	\N
105	producto	leche	\N	\N	\N	126.52	2025-04-15	55.13	Lácteos del Valle	\N
106	producto	leche	\N	\N	\N	124.92	2025-04-16	25.45	Lácteos del Valle	\N
107	producto	leche	\N	\N	\N	84.64	2025-04-17	22.39	Lácteos del Valle	\N
108	producto	leche	\N	\N	\N	78.58	2025-04-18	38.70	Lácteos del Valle	\N
109	producto	leche	\N	\N	\N	92.50	2025-04-19	51.70	Lácteos del Valle	\N
110	producto	leche	\N	\N	\N	74.70	2025-04-20	23.45	Lácteos del Valle	\N
111	producto	leche	\N	\N	\N	138.35	2025-04-21	31.07	Lácteos del Valle	\N
112	producto	leche	\N	\N	\N	81.94	2025-04-22	41.27	Lácteos del Valle	\N
113	producto	leche	\N	\N	\N	65.03	2025-04-23	56.83	Lácteos del Valle	\N
114	producto	leche	\N	\N	\N	120.46	2025-04-24	58.63	Lácteos del Valle	\N
115	producto	leche	\N	\N	\N	128.73	2025-04-25	48.58	Lácteos del Valle	\N
116	producto	leche	\N	\N	\N	123.73	2025-04-26	52.81	Lácteos del Valle	\N
117	producto	leche	\N	\N	\N	74.74	2025-04-27	48.57	Lácteos del Valle	\N
118	producto	leche	\N	\N	\N	86.80	2025-04-28	69.97	Lácteos del Valle	\N
119	producto	leche	\N	\N	\N	117.84	2025-04-29	30.14	Lácteos del Valle	\N
120	producto	leche	\N	\N	\N	65.28	2025-04-30	28.61	Lácteos del Valle	\N
121	producto	leche	\N	\N	\N	133.22	2025-05-01	65.50	Lácteos del Valle	\N
122	producto	leche	\N	\N	\N	103.01	2025-05-02	21.50	Lácteos del Valle	\N
123	producto	leche	\N	\N	\N	122.16	2025-05-03	40.51	Lácteos del Valle	\N
124	producto	leche	\N	\N	\N	59.38	2025-05-04	43.60	Lácteos del Valle	\N
125	producto	leche	\N	\N	\N	118.31	2025-05-05	43.26	Lácteos del Valle	\N
126	producto	leche	\N	\N	\N	115.87	2025-05-06	23.11	Lácteos del Valle	\N
127	producto	leche	\N	\N	\N	61.42	2025-05-07	53.54	Lácteos del Valle	\N
128	producto	leche	\N	\N	\N	50.67	2025-05-08	28.65	Lácteos del Valle	\N
129	producto	leche	\N	\N	\N	83.20	2025-05-09	52.33	Lácteos del Valle	\N
130	producto	leche	\N	\N	\N	137.06	2025-05-10	38.08	Lácteos del Valle	\N
131	producto	leche	\N	\N	\N	58.62	2025-05-11	68.89	Lácteos del Valle	\N
132	producto	leche	\N	\N	\N	69.85	2025-05-12	47.80	Lácteos del Valle	\N
133	producto	leche	\N	\N	\N	124.47	2025-05-13	27.88	Lácteos del Valle	\N
134	producto	leche	\N	\N	\N	101.46	2025-05-14	60.73	Lácteos del Valle	\N
135	producto	leche	\N	\N	\N	105.33	2025-05-15	30.27	Lácteos del Valle	\N
136	producto	leche	\N	\N	\N	95.55	2025-05-16	44.42	Lácteos del Valle	\N
137	producto	leche	\N	\N	\N	67.28	2025-05-17	58.27	Lácteos del Valle	\N
138	producto	leche	\N	\N	\N	138.00	2025-05-18	60.91	Lácteos del Valle	\N
139	producto	leche	\N	\N	\N	57.46	2025-05-19	49.74	Lácteos del Valle	\N
140	producto	leche	\N	\N	\N	78.46	2025-05-20	56.40	Lácteos del Valle	\N
141	producto	leche	\N	\N	\N	80.96	2025-05-21	35.23	Lácteos del Valle	\N
142	producto	leche	\N	\N	\N	98.39	2025-05-22	30.45	Lácteos del Valle	\N
143	producto	leche	\N	\N	\N	87.57	2025-05-23	32.02	Lácteos del Valle	\N
144	producto	leche	\N	\N	\N	95.39	2025-05-24	47.65	Lácteos del Valle	\N
145	producto	leche	\N	\N	\N	130.84	2025-05-25	34.19	Lácteos del Valle	\N
146	producto	leche	\N	\N	\N	79.06	2025-05-26	41.16	Lácteos del Valle	\N
147	producto	leche	\N	\N	\N	143.79	2025-05-27	52.31	Lácteos del Valle	\N
148	producto	leche	\N	\N	\N	143.65	2025-05-28	23.97	Lácteos del Valle	\N
149	producto	leche	\N	\N	\N	134.78	2025-05-29	21.65	Lácteos del Valle	\N
150	producto	leche	\N	\N	\N	149.17	2025-05-30	64.46	Lácteos del Valle	\N
151	producto	leche	\N	\N	\N	53.54	2025-05-31	62.07	Lácteos del Valle	\N
152	producto	leche	\N	\N	\N	53.66	2025-06-01	24.50	Lácteos del Valle	\N
153	producto	leche	\N	\N	\N	54.70	2025-06-02	48.57	Lácteos del Valle	\N
154	producto	leche	\N	\N	\N	90.65	2025-06-03	67.29	Lácteos del Valle	\N
155	producto	leche	\N	\N	\N	57.23	2025-06-04	48.69	Lácteos del Valle	\N
156	producto	leche	\N	\N	\N	75.94	2025-06-05	32.64	Lácteos del Valle	\N
157	producto	leche	\N	\N	\N	51.92	2025-06-06	27.33	Lácteos del Valle	\N
158	producto	leche	\N	\N	\N	72.80	2025-06-07	58.65	Lácteos del Valle	\N
159	producto	leche	\N	\N	\N	103.84	2025-06-08	47.45	Lácteos del Valle	\N
160	producto	leche	\N	\N	\N	97.04	2025-06-09	53.16	Lácteos del Valle	\N
161	producto	leche	\N	\N	\N	141.68	2025-06-10	36.80	Lácteos del Valle	\N
162	producto	leche	\N	\N	\N	110.81	2025-06-11	20.61	Lácteos del Valle	\N
163	producto	leche	\N	\N	\N	51.05	2025-06-12	58.41	Lácteos del Valle	\N
164	producto	leche	\N	\N	\N	134.71	2025-06-13	37.74	Lácteos del Valle	\N
165	producto	leche	\N	\N	\N	65.08	2025-06-14	40.02	Lácteos del Valle	\N
166	producto	leche	\N	\N	\N	145.78	2025-06-15	36.19	Lácteos del Valle	\N
167	producto	leche	\N	\N	\N	75.85	2025-06-16	37.99	Lácteos del Valle	\N
168	producto	leche	\N	\N	\N	132.40	2025-06-17	61.08	Lácteos del Valle	\N
169	producto	leche	\N	\N	\N	95.17	2025-06-18	58.75	Lácteos del Valle	\N
170	producto	leche	\N	\N	\N	60.29	2025-06-19	57.84	Lácteos del Valle	\N
171	producto	leche	\N	\N	\N	140.12	2025-06-20	23.30	Lácteos del Valle	\N
172	producto	leche	\N	\N	\N	123.86	2025-06-21	35.59	Lácteos del Valle	\N
173	producto	leche	\N	\N	\N	57.48	2025-06-22	26.01	Lácteos del Valle	\N
174	producto	leche	\N	\N	\N	122.98	2025-06-23	36.22	Lácteos del Valle	\N
175	producto	leche	\N	\N	\N	85.86	2025-06-24	39.34	Lácteos del Valle	\N
176	producto	leche	\N	\N	\N	66.08	2025-06-25	34.62	Lácteos del Valle	\N
177	producto	leche	\N	\N	\N	77.51	2025-06-26	39.84	Lácteos del Valle	\N
178	producto	leche	\N	\N	\N	88.08	2025-06-27	20.94	Lácteos del Valle	\N
179	producto	leche	\N	\N	\N	67.49	2025-06-28	43.40	Lácteos del Valle	\N
180	producto	leche	\N	\N	\N	109.13	2025-06-29	40.87	Lácteos del Valle	\N
181	producto	leche	\N	\N	\N	53.08	2025-06-30	59.07	Lácteos del Valle	\N
182	producto	leche	\N	\N	\N	141.32	2025-07-01	31.64	Lácteos del Valle	\N
183	producto	leche	\N	\N	\N	116.85	2025-07-02	45.08	Lácteos del Valle	\N
184	producto	leche	\N	\N	\N	117.27	2025-07-03	55.26	Lácteos del Valle	\N
185	producto	leche	\N	\N	\N	126.96	2025-07-04	38.43	Lácteos del Valle	\N
186	producto	leche	\N	\N	\N	116.58	2025-07-05	34.42	Lácteos del Valle	\N
187	producto	leche	\N	\N	\N	120.45	2025-07-06	46.51	Lácteos del Valle	\N
188	producto	leche	\N	\N	\N	60.35	2025-07-07	62.83	Lácteos del Valle	\N
189	producto	leche	\N	\N	\N	51.78	2025-07-08	45.52	Lácteos del Valle	\N
190	producto	leche	\N	\N	\N	133.80	2025-07-09	62.31	Lácteos del Valle	\N
191	producto	leche	\N	\N	\N	112.25	2025-07-10	67.15	Lácteos del Valle	\N
192	producto	leche	\N	\N	\N	126.04	2025-07-11	21.42	Lácteos del Valle	\N
193	producto	leche	\N	\N	\N	98.85	2025-07-12	45.00	Lácteos del Valle	\N
194	producto	leche	\N	\N	\N	77.73	2025-07-13	42.53	Lácteos del Valle	\N
195	producto	leche	\N	\N	\N	100.76	2025-07-14	26.65	Lácteos del Valle	\N
196	producto	leche	\N	\N	\N	71.66	2025-07-15	54.41	Lácteos del Valle	\N
197	producto	leche	\N	\N	\N	128.58	2025-07-16	46.78	Lácteos del Valle	\N
198	producto	leche	\N	\N	\N	120.54	2025-07-17	60.80	Lácteos del Valle	\N
199	producto	leche	\N	\N	\N	138.89	2025-07-18	28.13	Lácteos del Valle	\N
200	producto	leche	\N	\N	\N	58.94	2025-07-19	43.37	Lácteos del Valle	\N
201	producto	leche	\N	\N	\N	110.55	2025-07-20	55.22	Lácteos del Valle	\N
202	producto	leche	\N	\N	\N	104.53	2025-07-21	48.29	Lácteos del Valle	\N
203	producto	leche	\N	\N	\N	108.36	2025-07-22	41.59	Lácteos del Valle	\N
204	producto	leche	\N	\N	\N	112.59	2025-07-23	50.27	Lácteos del Valle	\N
205	producto	leche	\N	\N	\N	71.29	2025-07-24	63.90	Lácteos del Valle	\N
206	producto	leche	\N	\N	\N	131.78	2025-07-25	49.93	Lácteos del Valle	\N
207	producto	leche	\N	\N	\N	139.26	2025-07-26	58.77	Lácteos del Valle	\N
208	producto	leche	\N	\N	\N	86.10	2025-07-27	41.55	Lácteos del Valle	\N
209	producto	leche	\N	\N	\N	142.04	2025-07-28	53.74	Lácteos del Valle	\N
210	producto	leche	\N	\N	\N	117.61	2025-07-29	57.21	Lácteos del Valle	\N
211	producto	leche	\N	\N	\N	95.18	2025-07-30	56.76	Lácteos del Valle	\N
212	producto	leche	\N	\N	\N	60.94	2025-07-31	34.76	Lácteos del Valle	\N
213	producto	leche	\N	\N	\N	55.62	2025-08-01	50.91	Lácteos del Valle	\N
214	producto	leche	\N	\N	\N	89.99	2025-08-02	45.36	Lácteos del Valle	\N
215	producto	leche	\N	\N	\N	138.91	2025-08-03	21.94	Lácteos del Valle	\N
216	producto	leche	\N	\N	\N	138.50	2025-08-04	54.22	Lácteos del Valle	\N
217	producto	leche	\N	\N	\N	129.47	2025-08-05	60.61	Lácteos del Valle	\N
218	producto	leche	\N	\N	\N	123.97	2025-08-06	68.62	Lácteos del Valle	\N
219	producto	leche	\N	\N	\N	59.60	2025-08-07	53.33	Lácteos del Valle	\N
220	producto	leche	\N	\N	\N	78.66	2025-08-08	31.16	Lácteos del Valle	\N
221	producto	leche	\N	\N	\N	64.45	2025-08-09	38.28	Lácteos del Valle	\N
222	producto	leche	\N	\N	\N	119.40	2025-08-10	20.02	Lácteos del Valle	\N
223	producto	leche	\N	\N	\N	57.63	2025-08-11	26.42	Lácteos del Valle	\N
224	producto	leche	\N	\N	\N	69.43	2025-08-12	42.89	Lácteos del Valle	\N
225	producto	leche	\N	\N	\N	144.71	2025-08-13	42.33	Lácteos del Valle	\N
226	producto	leche	\N	\N	\N	97.07	2025-08-14	53.04	Lácteos del Valle	\N
227	producto	leche	\N	\N	\N	63.95	2025-08-15	45.45	Lácteos del Valle	\N
228	producto	leche	\N	\N	\N	114.72	2025-08-16	20.87	Lácteos del Valle	\N
229	producto	leche	\N	\N	\N	128.19	2025-08-17	49.61	Lácteos del Valle	\N
230	producto	leche	\N	\N	\N	55.41	2025-08-18	33.64	Lácteos del Valle	\N
231	producto	leche	\N	\N	\N	108.02	2025-08-19	57.51	Lácteos del Valle	\N
232	producto	leche	\N	\N	\N	146.65	2025-08-20	30.66	Lácteos del Valle	\N
233	producto	leche	\N	\N	\N	54.87	2025-08-21	61.56	Lácteos del Valle	\N
234	producto	leche	\N	\N	\N	107.97	2025-08-22	39.77	Lácteos del Valle	\N
235	producto	leche	\N	\N	\N	111.05	2025-08-23	56.90	Lácteos del Valle	\N
236	producto	leche	\N	\N	\N	95.10	2025-08-24	25.10	Lácteos del Valle	\N
237	producto	leche	\N	\N	\N	126.93	2025-08-25	22.71	Lácteos del Valle	\N
238	producto	leche	\N	\N	\N	92.07	2025-08-26	51.48	Lácteos del Valle	\N
239	producto	leche	\N	\N	\N	85.10	2025-08-27	27.03	Lácteos del Valle	\N
240	producto	leche	\N	\N	\N	63.71	2025-08-28	29.16	Lácteos del Valle	\N
241	producto	leche	\N	\N	\N	61.84	2025-08-29	65.06	Lácteos del Valle	\N
242	producto	leche	\N	\N	\N	115.51	2025-08-30	61.68	Lácteos del Valle	\N
243	producto	leche	\N	\N	\N	56.54	2025-08-31	25.79	Lácteos del Valle	\N
244	producto	leche	\N	\N	\N	89.67	2025-09-01	66.24	Lácteos del Valle	\N
245	producto	leche	\N	\N	\N	65.10	2025-09-02	43.43	Lácteos del Valle	\N
246	producto	leche	\N	\N	\N	75.47	2025-09-03	55.00	Lácteos del Valle	\N
247	producto	leche	\N	\N	\N	149.96	2025-09-04	69.30	Lácteos del Valle	\N
248	producto	leche	\N	\N	\N	57.75	2025-09-05	28.69	Lácteos del Valle	\N
249	producto	leche	\N	\N	\N	67.58	2025-09-06	63.81	Lácteos del Valle	\N
250	producto	leche	\N	\N	\N	136.41	2025-09-07	32.97	Lácteos del Valle	\N
251	producto	leche	\N	\N	\N	80.38	2025-09-08	33.65	Lácteos del Valle	\N
252	producto	leche	\N	\N	\N	146.27	2025-09-09	51.97	Lácteos del Valle	\N
253	producto	leche	\N	\N	\N	96.22	2025-09-10	38.66	Lácteos del Valle	\N
254	producto	leche	\N	\N	\N	59.06	2025-09-11	67.90	Lácteos del Valle	\N
255	producto	leche	\N	\N	\N	142.19	2025-09-12	58.02	Lácteos del Valle	\N
256	producto	leche	\N	\N	\N	63.50	2025-09-13	65.88	Lácteos del Valle	\N
257	producto	leche	\N	\N	\N	135.06	2025-09-14	64.01	Lácteos del Valle	\N
258	producto	leche	\N	\N	\N	65.85	2025-09-15	51.21	Lácteos del Valle	\N
259	producto	leche	\N	\N	\N	135.32	2025-09-16	40.60	Lácteos del Valle	\N
260	producto	leche	\N	\N	\N	52.91	2025-09-17	68.64	Lácteos del Valle	\N
261	producto	leche	\N	\N	\N	128.05	2025-09-18	36.84	Lácteos del Valle	\N
262	producto	leche	\N	\N	\N	123.57	2025-09-19	60.71	Lácteos del Valle	\N
263	producto	leche	\N	\N	\N	107.02	2025-09-20	61.88	Lácteos del Valle	\N
264	producto	leche	\N	\N	\N	65.00	2025-09-21	59.87	Lácteos del Valle	\N
265	producto	leche	\N	\N	\N	149.22	2025-09-22	25.18	Lácteos del Valle	\N
266	producto	leche	\N	\N	\N	122.16	2025-09-23	66.38	Lácteos del Valle	\N
267	producto	leche	\N	\N	\N	131.61	2025-09-24	45.60	Lácteos del Valle	\N
268	producto	leche	\N	\N	\N	144.41	2025-09-25	69.04	Lácteos del Valle	\N
269	producto	leche	\N	\N	\N	113.66	2025-09-26	67.25	Lácteos del Valle	\N
270	producto	leche	\N	\N	\N	143.15	2025-09-27	31.32	Lácteos del Valle	\N
271	producto	leche	\N	\N	\N	117.88	2025-09-28	40.87	Lácteos del Valle	\N
272	producto	leche	\N	\N	\N	66.00	2025-09-29	55.63	Lácteos del Valle	\N
273	producto	leche	\N	\N	\N	149.88	2025-09-30	54.66	Lácteos del Valle	\N
274	producto	leche	\N	\N	\N	94.58	2025-10-01	56.01	Lácteos del Valle	\N
275	producto	leche	\N	\N	\N	127.00	2025-10-02	68.31	Lácteos del Valle	\N
276	producto	leche	\N	\N	\N	103.05	2025-10-03	28.97	Lácteos del Valle	\N
277	producto	leche	\N	\N	\N	71.81	2025-10-04	21.42	Lácteos del Valle	\N
278	producto	leche	\N	\N	\N	143.69	2025-10-05	53.25	Lácteos del Valle	\N
279	producto	leche	\N	\N	\N	73.33	2025-10-06	28.43	Lácteos del Valle	\N
280	producto	leche	\N	\N	\N	117.41	2025-10-07	21.32	Lácteos del Valle	\N
281	producto	leche	\N	\N	\N	69.69	2025-10-08	52.58	Lácteos del Valle	\N
282	producto	leche	\N	\N	\N	127.83	2025-10-09	52.01	Lácteos del Valle	\N
283	producto	leche	\N	\N	\N	86.85	2025-10-10	33.36	Lácteos del Valle	\N
284	producto	leche	\N	\N	\N	78.28	2025-10-11	64.75	Lácteos del Valle	\N
285	producto	leche	\N	\N	\N	80.81	2025-10-12	29.25	Lácteos del Valle	\N
286	producto	leche	\N	\N	\N	88.63	2025-10-13	38.89	Lácteos del Valle	\N
287	producto	leche	\N	\N	\N	132.16	2025-10-14	27.17	Lácteos del Valle	\N
288	producto	leche	\N	\N	\N	98.87	2025-10-15	42.45	Lácteos del Valle	\N
289	producto	leche	\N	\N	\N	58.93	2025-10-16	47.40	Lácteos del Valle	\N
290	producto	leche	\N	\N	\N	93.30	2025-10-17	63.87	Lácteos del Valle	\N
291	producto	leche	\N	\N	\N	121.05	2025-10-18	62.71	Lácteos del Valle	\N
292	producto	leche	\N	\N	\N	144.85	2025-10-19	46.77	Lácteos del Valle	\N
293	producto	leche	\N	\N	\N	130.91	2025-10-20	30.81	Lácteos del Valle	\N
294	producto	leche	\N	\N	\N	114.80	2025-10-21	28.63	Lácteos del Valle	\N
295	producto	leche	\N	\N	\N	70.45	2025-10-22	65.12	Lácteos del Valle	\N
296	producto	leche	\N	\N	\N	107.19	2025-10-23	42.54	Lácteos del Valle	\N
297	producto	leche	\N	\N	\N	148.04	2025-10-24	59.40	Lácteos del Valle	\N
298	producto	leche	\N	\N	\N	89.92	2025-10-25	43.43	Lácteos del Valle	\N
299	producto	leche	\N	\N	\N	122.39	2025-10-26	23.16	Lácteos del Valle	\N
300	producto	leche	\N	\N	\N	69.05	2025-10-27	68.24	Lácteos del Valle	\N
301	producto	leche	\N	\N	\N	51.54	2025-10-28	67.63	Lácteos del Valle	\N
302	producto	leche	\N	\N	\N	110.04	2025-10-29	37.90	Lácteos del Valle	\N
303	producto	leche	\N	\N	\N	133.61	2025-10-30	55.32	Lácteos del Valle	\N
304	producto	leche	\N	\N	\N	85.27	2025-10-31	56.08	Lácteos del Valle	\N
305	producto	leche	\N	\N	\N	103.37	2025-11-01	55.64	Lácteos del Valle	\N
306	producto	leche	\N	\N	\N	114.55	2025-11-02	27.68	Lácteos del Valle	\N
307	producto	leche	\N	\N	\N	68.31	2025-11-03	30.23	Lácteos del Valle	\N
308	producto	leche	\N	\N	\N	149.64	2025-11-04	52.85	Lácteos del Valle	\N
309	producto	leche	\N	\N	\N	80.88	2025-11-05	41.67	Lácteos del Valle	\N
310	producto	leche	\N	\N	\N	84.82	2025-11-06	65.01	Lácteos del Valle	\N
311	producto	leche	\N	\N	\N	55.20	2025-11-07	51.91	Lácteos del Valle	\N
312	producto	leche	\N	\N	\N	101.56	2025-11-08	41.45	Lácteos del Valle	\N
313	producto	leche	\N	\N	\N	80.09	2025-11-09	50.89	Lácteos del Valle	\N
314	producto	leche	\N	\N	\N	66.31	2025-11-10	36.05	Lácteos del Valle	\N
315	producto	leche	\N	\N	\N	132.18	2025-11-11	43.00	Lácteos del Valle	\N
316	producto	leche	\N	\N	\N	105.79	2025-11-12	22.11	Lácteos del Valle	\N
317	producto	leche	\N	\N	\N	59.13	2025-11-13	32.12	Lácteos del Valle	\N
318	producto	leche	\N	\N	\N	123.52	2025-11-14	25.95	Lácteos del Valle	\N
319	producto	leche	\N	\N	\N	114.84	2025-11-15	63.70	Lácteos del Valle	\N
320	producto	leche	\N	\N	\N	110.67	2025-11-16	51.01	Lácteos del Valle	\N
321	producto	leche	\N	\N	\N	128.98	2025-11-17	39.89	Lácteos del Valle	\N
322	producto	leche	\N	\N	\N	93.01	2025-11-18	48.59	Lácteos del Valle	\N
323	producto	leche	\N	\N	\N	106.73	2025-11-19	40.28	Lácteos del Valle	\N
324	producto	leche	\N	\N	\N	125.66	2025-11-20	23.51	Lácteos del Valle	\N
325	producto	leche	\N	\N	\N	115.46	2025-11-21	50.32	Lácteos del Valle	\N
326	producto	leche	\N	\N	\N	68.95	2025-11-22	46.21	Lácteos del Valle	\N
327	producto	leche	\N	\N	\N	82.05	2025-11-23	46.14	Lácteos del Valle	\N
328	producto	leche	\N	\N	\N	83.79	2025-11-24	27.29	Lácteos del Valle	\N
329	producto	leche	\N	\N	\N	57.91	2025-11-25	48.88	Lácteos del Valle	\N
330	producto	leche	\N	\N	\N	114.42	2025-11-26	65.12	Lácteos del Valle	\N
331	producto	leche	\N	\N	\N	72.14	2025-11-27	68.91	Lácteos del Valle	\N
332	producto	leche	\N	\N	\N	57.13	2025-11-28	69.44	Lácteos del Valle	\N
333	producto	leche	\N	\N	\N	88.03	2025-11-29	38.30	Lácteos del Valle	\N
334	producto	leche	\N	\N	\N	71.83	2025-11-30	39.45	Lácteos del Valle	\N
335	producto	leche	\N	\N	\N	118.87	2025-12-01	54.73	Lácteos del Valle	\N
336	producto	leche	\N	\N	\N	129.57	2025-12-02	32.25	Lácteos del Valle	\N
337	producto	leche	\N	\N	\N	61.12	2025-12-03	44.28	Lácteos del Valle	\N
338	producto	leche	\N	\N	\N	66.40	2025-12-04	42.40	Lácteos del Valle	\N
339	producto	leche	\N	\N	\N	131.01	2025-12-05	50.25	Lácteos del Valle	\N
340	producto	leche	\N	\N	\N	69.96	2025-12-06	41.66	Lácteos del Valle	\N
341	producto	leche	\N	\N	\N	129.69	2025-12-07	48.11	Lácteos del Valle	\N
342	producto	leche	\N	\N	\N	136.69	2025-12-08	60.38	Lácteos del Valle	\N
343	producto	leche	\N	\N	\N	131.18	2025-12-09	57.00	Lácteos del Valle	\N
344	producto	leche	\N	\N	\N	129.39	2025-12-10	43.27	Lácteos del Valle	\N
345	producto	leche	\N	\N	\N	76.04	2025-12-11	28.57	Lácteos del Valle	\N
346	producto	leche	\N	\N	\N	121.74	2025-12-12	25.68	Lácteos del Valle	\N
347	producto	leche	\N	\N	\N	63.79	2025-12-13	65.33	Lácteos del Valle	\N
348	producto	leche	\N	\N	\N	135.92	2025-12-14	51.52	Lácteos del Valle	\N
349	producto	leche	\N	\N	\N	102.88	2025-12-15	48.99	Lácteos del Valle	\N
350	producto	leche	\N	\N	\N	58.27	2025-12-16	33.84	Lácteos del Valle	\N
351	producto	leche	\N	\N	\N	136.85	2025-12-17	64.68	Lácteos del Valle	\N
352	producto	leche	\N	\N	\N	108.09	2025-12-18	23.18	Lácteos del Valle	\N
353	producto	leche	\N	\N	\N	57.16	2025-12-19	42.43	Lácteos del Valle	\N
354	producto	leche	\N	\N	\N	126.65	2025-12-20	49.22	Lácteos del Valle	\N
355	producto	leche	\N	\N	\N	60.49	2025-12-21	66.05	Lácteos del Valle	\N
356	producto	leche	\N	\N	\N	127.81	2025-12-22	31.23	Lácteos del Valle	\N
357	producto	leche	\N	\N	\N	62.52	2025-12-23	31.07	Lácteos del Valle	\N
358	producto	leche	\N	\N	\N	51.56	2025-12-24	42.87	Lácteos del Valle	\N
359	producto	leche	\N	\N	\N	63.04	2025-12-25	35.19	Lácteos del Valle	\N
360	producto	leche	\N	\N	\N	148.00	2025-12-26	53.29	Lácteos del Valle	\N
361	producto	leche	\N	\N	\N	133.98	2025-12-27	22.37	Lácteos del Valle	\N
362	producto	leche	\N	\N	\N	58.47	2025-12-28	44.31	Lácteos del Valle	\N
363	producto	leche	\N	\N	\N	132.81	2025-12-29	50.70	Lácteos del Valle	\N
364	producto	leche	\N	\N	\N	75.56	2025-12-30	69.30	Lácteos del Valle	\N
365	producto	leche	\N	\N	\N	130.14	2025-12-31	22.82	Lácteos del Valle	\N
366	producto	leche	\N	\N	\N	142.08	2026-01-01	33.74	Lácteos del Valle	\N
367	producto	leche	\N	\N	\N	138.92	2026-01-02	37.16	Lácteos del Valle	\N
368	producto	leche	\N	\N	\N	67.39	2026-01-03	45.75	Lácteos del Valle	\N
369	producto	leche	\N	\N	\N	59.81	2026-01-04	39.24	Lácteos del Valle	\N
370	producto	leche	\N	\N	\N	124.70	2026-01-05	31.66	Lácteos del Valle	\N
371	producto	leche	\N	\N	\N	147.92	2026-01-06	32.58	Lácteos del Valle	\N
372	producto	leche	\N	\N	\N	119.92	2026-01-07	48.57	Lácteos del Valle	\N
373	producto	leche	\N	\N	\N	92.61	2026-01-08	44.08	Lácteos del Valle	\N
374	producto	leche	\N	\N	\N	107.95	2026-01-09	46.77	Lácteos del Valle	\N
375	producto	leche	\N	\N	\N	122.10	2026-01-10	53.97	Lácteos del Valle	\N
376	producto	leche	\N	\N	\N	111.81	2026-01-11	23.44	Lácteos del Valle	\N
377	producto	leche	\N	\N	\N	131.41	2026-01-12	30.06	Lácteos del Valle	\N
378	producto	leche	\N	\N	\N	102.08	2026-01-13	25.02	Lácteos del Valle	\N
379	producto	leche	\N	\N	\N	139.58	2026-01-14	21.99	Lácteos del Valle	\N
380	producto	leche	\N	\N	\N	112.37	2026-01-15	62.19	Lácteos del Valle	\N
381	producto	leche	\N	\N	\N	90.54	2026-01-16	58.47	Lácteos del Valle	\N
382	producto	leche	\N	\N	\N	87.57	2026-01-17	53.48	Lácteos del Valle	\N
383	producto	leche	\N	\N	\N	65.71	2026-01-18	31.32	Lácteos del Valle	\N
384	producto	leche	\N	\N	\N	54.62	2026-01-19	65.70	Lácteos del Valle	\N
385	producto	leche	\N	\N	\N	83.24	2026-01-20	21.52	Lácteos del Valle	\N
386	producto	leche	\N	\N	\N	140.09	2026-01-21	43.23	Lácteos del Valle	\N
387	producto	leche	\N	\N	\N	130.13	2026-01-22	49.61	Lácteos del Valle	\N
388	producto	leche	\N	\N	\N	81.42	2026-01-23	32.44	Lácteos del Valle	\N
389	producto	leche	\N	\N	\N	54.46	2026-01-24	22.25	Lácteos del Valle	\N
390	producto	leche	\N	\N	\N	103.36	2026-01-25	38.54	Lácteos del Valle	\N
391	producto	leche	\N	\N	\N	78.74	2026-01-26	29.83	Lácteos del Valle	\N
392	producto	leche	\N	\N	\N	116.68	2026-01-27	69.66	Lácteos del Valle	\N
393	producto	leche	\N	\N	\N	124.94	2026-01-28	45.43	Lácteos del Valle	\N
394	producto	leche	\N	\N	\N	122.85	2026-01-29	34.64	Lácteos del Valle	\N
395	producto	leche	\N	\N	\N	53.46	2026-01-30	65.11	Lácteos del Valle	\N
396	producto	leche	\N	\N	\N	78.68	2026-01-31	30.29	Lácteos del Valle	\N
397	producto	leche	\N	\N	\N	55.88	2026-02-01	66.58	Lácteos del Valle	\N
398	producto	leche	\N	\N	\N	133.40	2026-02-02	62.25	Lácteos del Valle	\N
399	producto	leche	\N	\N	\N	131.13	2026-02-03	27.14	Lácteos del Valle	\N
400	producto	leche	\N	\N	\N	67.02	2026-02-04	31.33	Lácteos del Valle	\N
401	producto	leche	\N	\N	\N	121.16	2026-02-05	54.91	Lácteos del Valle	\N
402	producto	leche	\N	\N	\N	100.73	2026-02-06	64.16	Lácteos del Valle	\N
403	producto	leche	\N	\N	\N	91.09	2026-02-07	58.88	Lácteos del Valle	\N
404	producto	leche	\N	\N	\N	129.97	2026-02-08	53.20	Lácteos del Valle	\N
405	producto	leche	\N	\N	\N	116.96	2026-02-09	69.71	Lácteos del Valle	\N
406	producto	leche	\N	\N	\N	99.37	2026-02-10	68.22	Lácteos del Valle	\N
407	producto	leche	\N	\N	\N	121.24	2026-02-11	40.49	Lácteos del Valle	\N
408	producto	leche	\N	\N	\N	83.15	2026-02-12	52.65	Lácteos del Valle	\N
409	producto	leche	\N	\N	\N	145.43	2026-02-13	40.98	Lácteos del Valle	\N
410	producto	leche	\N	\N	\N	57.01	2026-02-14	51.34	Lácteos del Valle	\N
411	producto	leche	\N	\N	\N	91.01	2026-02-15	48.11	Lácteos del Valle	\N
412	producto	leche	\N	\N	\N	86.09	2026-02-16	62.91	Lácteos del Valle	\N
413	producto	leche	\N	\N	\N	96.76	2026-02-17	28.51	Lácteos del Valle	\N
414	producto	leche	\N	\N	\N	90.23	2026-02-18	64.40	Lácteos del Valle	\N
415	producto	leche	\N	\N	\N	128.94	2026-02-19	61.93	Lácteos del Valle	\N
416	producto	leche	\N	\N	\N	103.75	2026-02-20	49.17	Lácteos del Valle	\N
417	producto	leche	\N	\N	\N	54.52	2026-02-21	49.88	Lácteos del Valle	\N
418	producto	leche	\N	\N	\N	88.68	2026-02-22	32.88	Lácteos del Valle	\N
419	producto	leche	\N	\N	\N	50.06	2026-02-23	49.95	Lácteos del Valle	\N
420	producto	leche	\N	\N	\N	77.20	2026-02-24	24.79	Lácteos del Valle	\N
421	producto	leche	\N	\N	\N	94.12	2026-02-25	41.13	Lácteos del Valle	\N
422	producto	leche	\N	\N	\N	67.67	2026-02-26	41.36	Lácteos del Valle	\N
423	producto	leche	\N	\N	\N	84.40	2026-02-27	53.92	Lácteos del Valle	\N
424	producto	leche	\N	\N	\N	67.27	2026-02-28	48.86	Lácteos del Valle	\N
425	producto	leche	\N	\N	\N	70.26	2026-03-01	68.89	Lácteos del Valle	\N
426	producto	leche	\N	\N	\N	60.10	2026-03-02	25.80	Lácteos del Valle	\N
427	producto	leche	\N	\N	\N	50.08	2026-03-03	45.90	Lácteos del Valle	\N
428	producto	leche	\N	\N	\N	140.89	2026-03-04	42.74	Lácteos del Valle	\N
429	producto	leche	\N	\N	\N	124.39	2026-03-05	35.20	Lácteos del Valle	\N
430	producto	leche	\N	\N	\N	63.88	2026-03-06	46.62	Lácteos del Valle	\N
431	producto	leche	\N	\N	\N	92.35	2026-03-07	65.20	Lácteos del Valle	\N
432	producto	leche	\N	\N	\N	64.27	2026-03-08	35.22	Lácteos del Valle	\N
433	producto	leche	\N	\N	\N	87.09	2026-03-09	49.77	Lácteos del Valle	\N
434	producto	leche	\N	\N	\N	54.99	2026-03-10	65.73	Lácteos del Valle	\N
435	producto	leche	\N	\N	\N	65.58	2026-03-11	58.95	Lácteos del Valle	\N
436	producto	leche	\N	\N	\N	127.89	2026-03-12	24.61	Lácteos del Valle	\N
437	producto	leche	\N	\N	\N	69.55	2026-03-13	68.10	Lácteos del Valle	\N
438	producto	leche	\N	\N	\N	54.28	2026-03-14	39.95	Lácteos del Valle	\N
439	producto	leche	\N	\N	\N	122.67	2026-03-15	55.07	Lácteos del Valle	\N
440	producto	leche	\N	\N	\N	105.33	2026-03-16	60.81	Lácteos del Valle	\N
441	producto	leche	\N	\N	\N	61.22	2026-03-17	54.07	Lácteos del Valle	\N
442	producto	leche	\N	\N	\N	125.81	2026-03-18	40.06	Lácteos del Valle	\N
443	producto	leche	\N	\N	\N	140.87	2026-03-19	38.72	Lácteos del Valle	\N
444	producto	leche	\N	\N	\N	134.23	2026-03-20	30.50	Lácteos del Valle	\N
445	producto	leche	\N	\N	\N	104.45	2026-03-21	40.06	Lácteos del Valle	\N
446	producto	leche	\N	\N	\N	79.70	2026-03-22	54.90	Lácteos del Valle	\N
447	producto	leche	\N	\N	\N	55.84	2026-03-23	43.93	Lácteos del Valle	\N
448	producto	leche	\N	\N	\N	93.39	2026-03-24	47.82	Lácteos del Valle	\N
449	producto	leche	\N	\N	\N	121.93	2026-03-25	35.62	Lácteos del Valle	\N
450	producto	leche	\N	\N	\N	147.02	2026-03-26	46.44	Lácteos del Valle	\N
451	producto	leche	\N	\N	\N	80.54	2026-03-27	39.85	Lácteos del Valle	\N
452	producto	leche	\N	\N	\N	77.64	2026-03-28	34.62	Lácteos del Valle	\N
453	producto	leche	\N	\N	\N	84.39	2026-03-29	58.65	Lácteos del Valle	\N
454	producto	leche	\N	\N	\N	99.48	2026-03-30	38.16	Lácteos del Valle	\N
455	producto	leche	\N	\N	\N	54.42	2026-03-31	36.59	Lácteos del Valle	\N
456	producto	leche	\N	\N	\N	68.40	2026-04-01	52.85	Lácteos del Valle	\N
457	producto	leche	\N	\N	\N	85.57	2026-04-02	67.85	Lácteos del Valle	\N
458	producto	leche	\N	\N	\N	113.98	2026-04-03	39.84	Lácteos del Valle	\N
459	producto	leche	\N	\N	\N	137.37	2026-04-04	67.62	Lácteos del Valle	\N
460	producto	leche	\N	\N	\N	130.88	2026-04-05	50.18	Lácteos del Valle	\N
461	producto	leche	\N	\N	\N	139.65	2026-04-06	67.74	Lácteos del Valle	\N
462	producto	leche	\N	\N	\N	67.99	2026-04-07	26.43	Lácteos del Valle	\N
463	producto	leche	\N	\N	\N	91.85	2026-04-08	48.72	Lácteos del Valle	\N
464	producto	leche	\N	\N	\N	69.84	2026-04-09	20.82	Lácteos del Valle	\N
465	producto	leche	\N	\N	\N	87.39	2026-04-10	67.89	Lácteos del Valle	\N
466	producto	leche	\N	\N	\N	64.67	2026-04-11	65.05	Lácteos del Valle	\N
467	producto	leche	\N	\N	\N	101.33	2026-04-12	34.13	Lácteos del Valle	\N
468	producto	leche	\N	\N	\N	133.08	2026-04-13	37.10	Lácteos del Valle	\N
469	producto	leche	\N	\N	\N	65.20	2026-04-14	39.37	Lácteos del Valle	\N
470	producto	leche	\N	\N	\N	76.18	2026-04-15	58.53	Lácteos del Valle	\N
471	producto	leche	\N	\N	\N	135.87	2026-04-16	67.13	Lácteos del Valle	\N
472	producto	leche	\N	\N	\N	74.13	2026-04-17	35.45	Lácteos del Valle	\N
473	producto	leche	\N	\N	\N	84.90	2026-04-18	23.25	Lácteos del Valle	\N
474	producto	leche	\N	\N	\N	92.96	2026-04-19	69.50	Lácteos del Valle	\N
475	producto	leche	\N	\N	\N	61.13	2026-04-20	65.25	Lácteos del Valle	\N
476	producto	leche	\N	\N	\N	51.53	2026-04-21	37.05	Lácteos del Valle	\N
477	producto	leche	\N	\N	\N	132.06	2026-04-22	49.43	Lácteos del Valle	\N
478	producto	leche	\N	\N	\N	108.89	2026-04-23	64.04	Lácteos del Valle	\N
479	producto	leche	\N	\N	\N	74.99	2026-04-24	35.99	Lácteos del Valle	\N
480	producto	leche	\N	\N	\N	69.04	2026-04-25	55.55	Lácteos del Valle	\N
481	producto	leche	\N	\N	\N	147.91	2026-04-26	65.34	Lácteos del Valle	\N
482	producto	leche	\N	\N	\N	125.00	2026-04-27	37.04	Lácteos del Valle	\N
483	producto	leche	\N	\N	\N	131.10	2026-04-28	33.33	Lácteos del Valle	\N
\.


--
-- TOC entry 5081 (class 0 OID 35255)
-- Dependencies: 240
-- Data for Name: slaughter_records; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.slaughter_records (id, animal_id, animal_type_id, slaughter_date, quantity, notes) FROM stdin;
\.


--
-- TOC entry 5091 (class 0 OID 35355)
-- Dependencies: 250
-- Data for Name: suppliers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.suppliers (id, name, contact_person, phone, email) FROM stdin;
1	Agroinsumos S.A.	Luis Gómez	555-1234	luis@agroinsumos.com
2	Veterinaria La Campiña	Ana Torres	555-5678	ana@campina.com
\.


--
-- TOC entry 5093 (class 0 OID 35362)
-- Dependencies: 252
-- Data for Name: supplies_catalog; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.supplies_catalog (id, name, category, unit, cost_per_unit, stock_quantity) FROM stdin;
1	Vacuna triple bovina	medicina	unidad	2.50	50.00
2	Desparasitante oral	medicina	litro	15.00	20.00
3	Fertilizante NPK	fertilizante	kg	1.20	200.00
4	Semilla de pasto	semilla	kg	5.00	100.00
5	Pala	herramienta	unidad	12.00	10.00
\.


--
-- TOC entry 5089 (class 0 OID 35325)
-- Dependencies: 248
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transactions (id, ad_id, seller_id, buyer_id, animal_type_id, quantity, total_amount, transaction_date, notes) FROM stdin;
1	1	2	1	4	1	253.40	2026-04-29	\N
2	11	1	2	4	3	1334.01	2026-04-29	\N
3	12	3	1	3	3	716.25	2026-04-29	\N
4	14	1	2	1	2	1113.20	2026-04-29	\N
5	15	3	1	2	3	938.67	2026-04-29	\N
6	16	1	2	1	1	328.70	2026-04-29	\N
7	17	3	1	2	5	2648.00	2026-04-29	\N
8	18	2	1	4	5	3276.30	2026-04-29	\N
9	22	4	1	2	1	593.80	2026-04-29	\N
10	25	2	1	4	4	2772.36	2026-04-29	\N
11	28	2	1	1	1	573.30	2026-04-29	\N
12	34	2	1	2	2	820.14	2026-04-29	\N
13	40	3	1	4	5	1388.70	2026-04-29	\N
14	41	3	1	1	3	1701.09	2026-04-29	\N
15	42	4	1	1	4	1738.32	2026-04-29	\N
16	50	2	1	4	1	455.47	2026-04-29	\N
\.


--
-- TOC entry 5085 (class 0 OID 35287)
-- Dependencies: 244
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, full_name, email, phone, role) FROM stdin;
1	prod_ganaderia2	Hacienda El Roble	\N	\N	productor
2	comprador_1	Cooperativa Ganadera	\N	\N	comprador
3	granja_los_pinos	Granja Los Pinos	\N	\N	productor
4	proveedor_externo	Proveedor Externo	\N	\N	proveedor
\.


--
-- TOC entry 5137 (class 0 OID 0)
-- Dependencies: 245
-- Name: animal_ads_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.animal_ads_id_seq', 50, true);


--
-- TOC entry 5138 (class 0 OID 0)
-- Dependencies: 215
-- Name: animal_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.animal_types_id_seq', 5, true);


--
-- TOC entry 5139 (class 0 OID 0)
-- Dependencies: 223
-- Name: animals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.animals_id_seq', 31, true);


--
-- TOC entry 5140 (class 0 OID 0)
-- Dependencies: 221
-- Name: batches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.batches_id_seq', 1, true);


--
-- TOC entry 5141 (class 0 OID 0)
-- Dependencies: 217
-- Name: breeds_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.breeds_id_seq', 19, true);


--
-- TOC entry 5142 (class 0 OID 0)
-- Dependencies: 229
-- Name: chicken_inventory_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.chicken_inventory_id_seq', 483, true);


--
-- TOC entry 5143 (class 0 OID 0)
-- Dependencies: 227
-- Name: egg_production_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.egg_production_id_seq', 483, true);


--
-- TOC entry 5144 (class 0 OID 0)
-- Dependencies: 259
-- Name: employees_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.employees_id_seq', 3, true);


--
-- TOC entry 5145 (class 0 OID 0)
-- Dependencies: 219
-- Name: facilities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.facilities_id_seq', 5, true);


--
-- TOC entry 5146 (class 0 OID 0)
-- Dependencies: 235
-- Name: feeding_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.feeding_id_seq', 2415, true);


--
-- TOC entry 5147 (class 0 OID 0)
-- Dependencies: 263
-- Name: financial_entries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.financial_entries_id_seq', 539, true);


--
-- TOC entry 5148 (class 0 OID 0)
-- Dependencies: 233
-- Name: food_catalog_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.food_catalog_id_seq', 7, true);


--
-- TOC entry 5149 (class 0 OID 0)
-- Dependencies: 231
-- Name: health_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.health_events_id_seq', 3, true);


--
-- TOC entry 5150 (class 0 OID 0)
-- Dependencies: 241
-- Name: market_prices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.market_prices_id_seq', 48, true);


--
-- TOC entry 5151 (class 0 OID 0)
-- Dependencies: 225
-- Name: milk_production_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.milk_production_id_seq', 3864, true);


--
-- TOC entry 5152 (class 0 OID 0)
-- Dependencies: 237
-- Name: nutritional_efficiency_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.nutritional_efficiency_id_seq', 1, true);


--
-- TOC entry 5153 (class 0 OID 0)
-- Dependencies: 261
-- Name: payroll_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.payroll_id_seq', 48, true);


--
-- TOC entry 5154 (class 0 OID 0)
-- Dependencies: 255
-- Name: purchase_order_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.purchase_order_details_id_seq', 5, true);


--
-- TOC entry 5155 (class 0 OID 0)
-- Dependencies: 253
-- Name: purchase_orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.purchase_orders_id_seq', 8, true);


--
-- TOC entry 5156 (class 0 OID 0)
-- Dependencies: 257
-- Name: sales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sales_id_seq', 483, true);


--
-- TOC entry 5157 (class 0 OID 0)
-- Dependencies: 239
-- Name: slaughter_records_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.slaughter_records_id_seq', 1, false);


--
-- TOC entry 5158 (class 0 OID 0)
-- Dependencies: 249
-- Name: suppliers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.suppliers_id_seq', 2, true);


--
-- TOC entry 5159 (class 0 OID 0)
-- Dependencies: 251
-- Name: supplies_catalog_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.supplies_catalog_id_seq', 5, true);


--
-- TOC entry 5160 (class 0 OID 0)
-- Dependencies: 247
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transactions_id_seq', 16, true);


--
-- TOC entry 5161 (class 0 OID 0)
-- Dependencies: 243
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- TOC entry 4860 (class 2606 OID 35308)
-- Name: animal_ads animal_ads_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_ads
    ADD CONSTRAINT animal_ads_pkey PRIMARY KEY (id);


--
-- TOC entry 4807 (class 2606 OID 35064)
-- Name: animal_types animal_types_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_types
    ADD CONSTRAINT animal_types_name_key UNIQUE (name);


--
-- TOC entry 4809 (class 2606 OID 35062)
-- Name: animal_types animal_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_types
    ADD CONSTRAINT animal_types_pkey PRIMARY KEY (id);


--
-- TOC entry 4821 (class 2606 OID 35123)
-- Name: animals animals_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_pkey PRIMARY KEY (id);


--
-- TOC entry 4823 (class 2606 OID 35125)
-- Name: animals animals_tag_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_tag_key UNIQUE (tag);


--
-- TOC entry 4817 (class 2606 OID 35100)
-- Name: batches batches_batch_code_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.batches
    ADD CONSTRAINT batches_batch_code_key UNIQUE (batch_code);


--
-- TOC entry 4819 (class 2606 OID 35098)
-- Name: batches batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.batches
    ADD CONSTRAINT batches_pkey PRIMARY KEY (id);


--
-- TOC entry 4811 (class 2606 OID 35073)
-- Name: breeds breeds_name_animal_type_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.breeds
    ADD CONSTRAINT breeds_name_animal_type_id_key UNIQUE (name, animal_type_id);


--
-- TOC entry 4813 (class 2606 OID 35071)
-- Name: breeds breeds_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.breeds
    ADD CONSTRAINT breeds_pkey PRIMARY KEY (id);


--
-- TOC entry 4836 (class 2606 OID 35193)
-- Name: chicken_inventory chicken_inventory_inventory_date_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chicken_inventory
    ADD CONSTRAINT chicken_inventory_inventory_date_key UNIQUE (inventory_date);


--
-- TOC entry 4838 (class 2606 OID 35191)
-- Name: chicken_inventory chicken_inventory_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chicken_inventory
    ADD CONSTRAINT chicken_inventory_pkey PRIMARY KEY (id);


--
-- TOC entry 4832 (class 2606 OID 35181)
-- Name: egg_production egg_production_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.egg_production
    ADD CONSTRAINT egg_production_pkey PRIMARY KEY (id);


--
-- TOC entry 4834 (class 2606 OID 35183)
-- Name: egg_production egg_production_production_date_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.egg_production
    ADD CONSTRAINT egg_production_production_date_key UNIQUE (production_date);


--
-- TOC entry 4874 (class 2606 OID 35437)
-- Name: employees employees_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_pkey PRIMARY KEY (id);


--
-- TOC entry 4815 (class 2606 OID 35087)
-- Name: facilities facilities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.facilities
    ADD CONSTRAINT facilities_pkey PRIMARY KEY (id);


--
-- TOC entry 4844 (class 2606 OID 35229)
-- Name: feeding feeding_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feeding
    ADD CONSTRAINT feeding_pkey PRIMARY KEY (id);


--
-- TOC entry 4880 (class 2606 OID 35462)
-- Name: financial_entries financial_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.financial_entries
    ADD CONSTRAINT financial_entries_pkey PRIMARY KEY (id);


--
-- TOC entry 4842 (class 2606 OID 35221)
-- Name: food_catalog food_catalog_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.food_catalog
    ADD CONSTRAINT food_catalog_pkey PRIMARY KEY (id);


--
-- TOC entry 4840 (class 2606 OID 35203)
-- Name: health_events health_events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.health_events
    ADD CONSTRAINT health_events_pkey PRIMARY KEY (id);


--
-- TOC entry 4850 (class 2606 OID 35283)
-- Name: market_prices market_prices_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.market_prices
    ADD CONSTRAINT market_prices_pkey PRIMARY KEY (id);


--
-- TOC entry 4852 (class 2606 OID 35285)
-- Name: market_prices market_prices_product_type_price_date_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.market_prices
    ADD CONSTRAINT market_prices_product_type_price_date_key UNIQUE (product_type, price_date);


--
-- TOC entry 4828 (class 2606 OID 35168)
-- Name: milk_production milk_production_animal_id_production_date_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.milk_production
    ADD CONSTRAINT milk_production_animal_id_production_date_key UNIQUE (animal_id, production_date);


--
-- TOC entry 4830 (class 2606 OID 35166)
-- Name: milk_production milk_production_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.milk_production
    ADD CONSTRAINT milk_production_pkey PRIMARY KEY (id);


--
-- TOC entry 4846 (class 2606 OID 35248)
-- Name: nutritional_efficiency nutritional_efficiency_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nutritional_efficiency
    ADD CONSTRAINT nutritional_efficiency_pkey PRIMARY KEY (id);


--
-- TOC entry 4876 (class 2606 OID 35446)
-- Name: payroll payroll_employee_id_period_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payroll
    ADD CONSTRAINT payroll_employee_id_period_key UNIQUE (employee_id, period);


--
-- TOC entry 4878 (class 2606 OID 35444)
-- Name: payroll payroll_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payroll
    ADD CONSTRAINT payroll_pkey PRIMARY KEY (id);


--
-- TOC entry 4870 (class 2606 OID 35390)
-- Name: purchase_order_details purchase_order_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_order_details
    ADD CONSTRAINT purchase_order_details_pkey PRIMARY KEY (id);


--
-- TOC entry 4868 (class 2606 OID 35377)
-- Name: purchase_orders purchase_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_pkey PRIMARY KEY (id);


--
-- TOC entry 4872 (class 2606 OID 35415)
-- Name: sales sales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_pkey PRIMARY KEY (id);


--
-- TOC entry 4848 (class 2606 OID 35263)
-- Name: slaughter_records slaughter_records_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.slaughter_records
    ADD CONSTRAINT slaughter_records_pkey PRIMARY KEY (id);


--
-- TOC entry 4864 (class 2606 OID 35360)
-- Name: suppliers suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (id);


--
-- TOC entry 4866 (class 2606 OID 35368)
-- Name: supplies_catalog supplies_catalog_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.supplies_catalog
    ADD CONSTRAINT supplies_catalog_pkey PRIMARY KEY (id);


--
-- TOC entry 4862 (class 2606 OID 35333)
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


--
-- TOC entry 4854 (class 2606 OID 35297)
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- TOC entry 4856 (class 2606 OID 35293)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4858 (class 2606 OID 35295)
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- TOC entry 4824 (class 1259 OID 35158)
-- Name: idx_animals_breed; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_animals_breed ON public.animals USING btree (breed_id);


--
-- TOC entry 4825 (class 1259 OID 35157)
-- Name: idx_animals_status; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_animals_status ON public.animals USING btree (status);


--
-- TOC entry 4826 (class 1259 OID 35156)
-- Name: idx_animals_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_animals_type ON public.animals USING btree (animal_type_id);


--
-- TOC entry 4898 (class 2606 OID 35314)
-- Name: animal_ads animal_ads_animal_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_ads
    ADD CONSTRAINT animal_ads_animal_type_id_fkey FOREIGN KEY (animal_type_id) REFERENCES public.animal_types(id);


--
-- TOC entry 4899 (class 2606 OID 35319)
-- Name: animal_ads animal_ads_breed_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_ads
    ADD CONSTRAINT animal_ads_breed_id_fkey FOREIGN KEY (breed_id) REFERENCES public.breeds(id);


--
-- TOC entry 4900 (class 2606 OID 35309)
-- Name: animal_ads animal_ads_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animal_ads
    ADD CONSTRAINT animal_ads_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- TOC entry 4884 (class 2606 OID 35131)
-- Name: animals animals_animal_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_animal_type_id_fkey FOREIGN KEY (animal_type_id) REFERENCES public.animal_types(id);


--
-- TOC entry 4885 (class 2606 OID 35146)
-- Name: animals animals_batch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_batch_id_fkey FOREIGN KEY (batch_id) REFERENCES public.batches(id);


--
-- TOC entry 4886 (class 2606 OID 35126)
-- Name: animals animals_breed_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_breed_id_fkey FOREIGN KEY (breed_id) REFERENCES public.breeds(id);


--
-- TOC entry 4887 (class 2606 OID 35151)
-- Name: animals animals_facility_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_facility_id_fkey FOREIGN KEY (facility_id) REFERENCES public.facilities(id);


--
-- TOC entry 4888 (class 2606 OID 35136)
-- Name: animals animals_father_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_father_id_fkey FOREIGN KEY (father_id) REFERENCES public.animals(id);


--
-- TOC entry 4889 (class 2606 OID 35141)
-- Name: animals animals_mother_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_mother_id_fkey FOREIGN KEY (mother_id) REFERENCES public.animals(id);


--
-- TOC entry 4882 (class 2606 OID 35101)
-- Name: batches batches_animal_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.batches
    ADD CONSTRAINT batches_animal_type_id_fkey FOREIGN KEY (animal_type_id) REFERENCES public.animal_types(id);


--
-- TOC entry 4883 (class 2606 OID 35106)
-- Name: batches batches_facility_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.batches
    ADD CONSTRAINT batches_facility_id_fkey FOREIGN KEY (facility_id) REFERENCES public.facilities(id);


--
-- TOC entry 4881 (class 2606 OID 35074)
-- Name: breeds breeds_animal_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.breeds
    ADD CONSTRAINT breeds_animal_type_id_fkey FOREIGN KEY (animal_type_id) REFERENCES public.animal_types(id);


--
-- TOC entry 4893 (class 2606 OID 35230)
-- Name: feeding feeding_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feeding
    ADD CONSTRAINT feeding_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id);


--
-- TOC entry 4894 (class 2606 OID 35235)
-- Name: feeding feeding_food_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feeding
    ADD CONSTRAINT feeding_food_id_fkey FOREIGN KEY (food_id) REFERENCES public.food_catalog(id);


--
-- TOC entry 4891 (class 2606 OID 35204)
-- Name: health_events health_events_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.health_events
    ADD CONSTRAINT health_events_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id);


--
-- TOC entry 4892 (class 2606 OID 35209)
-- Name: health_events health_events_batch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.health_events
    ADD CONSTRAINT health_events_batch_id_fkey FOREIGN KEY (batch_id) REFERENCES public.batches(id);


--
-- TOC entry 4890 (class 2606 OID 35169)
-- Name: milk_production milk_production_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.milk_production
    ADD CONSTRAINT milk_production_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id);


--
-- TOC entry 4895 (class 2606 OID 35249)
-- Name: nutritional_efficiency nutritional_efficiency_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nutritional_efficiency
    ADD CONSTRAINT nutritional_efficiency_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id);


--
-- TOC entry 4912 (class 2606 OID 35447)
-- Name: payroll payroll_employee_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payroll
    ADD CONSTRAINT payroll_employee_id_fkey FOREIGN KEY (employee_id) REFERENCES public.employees(id);


--
-- TOC entry 4906 (class 2606 OID 35396)
-- Name: purchase_order_details purchase_order_details_food_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_order_details
    ADD CONSTRAINT purchase_order_details_food_id_fkey FOREIGN KEY (food_id) REFERENCES public.food_catalog(id);


--
-- TOC entry 4907 (class 2606 OID 35391)
-- Name: purchase_order_details purchase_order_details_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_order_details
    ADD CONSTRAINT purchase_order_details_order_id_fkey FOREIGN KEY (order_id) REFERENCES public.purchase_orders(id);


--
-- TOC entry 4908 (class 2606 OID 35401)
-- Name: purchase_order_details purchase_order_details_supply_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_order_details
    ADD CONSTRAINT purchase_order_details_supply_id_fkey FOREIGN KEY (supply_id) REFERENCES public.supplies_catalog(id);


--
-- TOC entry 4905 (class 2606 OID 35378)
-- Name: purchase_orders purchase_orders_supplier_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_supplier_id_fkey FOREIGN KEY (supplier_id) REFERENCES public.suppliers(id);


--
-- TOC entry 4909 (class 2606 OID 35416)
-- Name: sales sales_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id);


--
-- TOC entry 4910 (class 2606 OID 35421)
-- Name: sales sales_batch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_batch_id_fkey FOREIGN KEY (batch_id) REFERENCES public.batches(id);


--
-- TOC entry 4911 (class 2606 OID 35426)
-- Name: sales sales_slaughter_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_slaughter_id_fkey FOREIGN KEY (slaughter_id) REFERENCES public.slaughter_records(id);


--
-- TOC entry 4896 (class 2606 OID 35264)
-- Name: slaughter_records slaughter_records_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.slaughter_records
    ADD CONSTRAINT slaughter_records_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id);


--
-- TOC entry 4897 (class 2606 OID 35269)
-- Name: slaughter_records slaughter_records_animal_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.slaughter_records
    ADD CONSTRAINT slaughter_records_animal_type_id_fkey FOREIGN KEY (animal_type_id) REFERENCES public.animal_types(id);


--
-- TOC entry 4901 (class 2606 OID 35334)
-- Name: transactions transactions_ad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_ad_id_fkey FOREIGN KEY (ad_id) REFERENCES public.animal_ads(id);


--
-- TOC entry 4902 (class 2606 OID 35349)
-- Name: transactions transactions_animal_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_animal_type_id_fkey FOREIGN KEY (animal_type_id) REFERENCES public.animal_types(id);


--
-- TOC entry 4903 (class 2606 OID 35344)
-- Name: transactions transactions_buyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_buyer_id_fkey FOREIGN KEY (buyer_id) REFERENCES public.users(id);


--
-- TOC entry 4904 (class 2606 OID 35339)
-- Name: transactions transactions_seller_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_seller_id_fkey FOREIGN KEY (seller_id) REFERENCES public.users(id);


-- Completed on 2026-04-30 10:08:13

--
-- PostgreSQL database dump complete
--

