--
-- PostgreSQL database dump
--

-- Dumped from database version 11.8
-- Dumped by pg_dump version 11.8

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
-- Name: concat(text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.concat(text, text) RETURNS text
    LANGUAGE sql
    AS $_$select case when $1 = '' then $2 else ($1 || ', ' || $2) end$_$;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: anexos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.anexos (
    id integer NOT NULL,
    anex_radi_nume numeric(20,0) NOT NULL,
    anex_codigo character varying(20) NOT NULL,
    anex_tipo numeric(4,0) NOT NULL,
    anex_tamano numeric,
    anex_solo_lect character varying(1) NOT NULL,
    anex_creador character varying(35) NOT NULL,
    anex_desc character varying(512),
    anex_numero numeric(5,0) NOT NULL,
    anex_nomb_archivo character varying(50) NOT NULL,
    anex_borrado character varying(1) NOT NULL,
    anex_origen numeric(1,0) DEFAULT 0,
    anex_ubic character varying(15),
    anex_salida numeric(1,0) DEFAULT 0,
    radi_nume_salida numeric(20,0),
    anex_radi_fech timestamp without time zone,
    anex_estado numeric(1,0) DEFAULT 0,
    usua_doc character varying(14),
    sgd_rem_destino numeric(1,0) DEFAULT 0,
    anex_fech_envio timestamp without time zone,
    sgd_dir_tipo numeric(4,0),
    anex_fech_impres date,
    anex_depe_creador numeric(7,0),
    sgd_doc_secuencia numeric(15,0),
    sgd_doc_padre character varying(20),
    sgd_arg_codigo numeric(2,0),
    sgd_tpr_codigo numeric(4,0) DEFAULT 0 NOT NULL,
    sgd_deve_codigo numeric(2,0),
    sgd_deve_fech timestamp without time zone,
    sgd_fech_impres timestamp without time zone,
    anex_fech_anex timestamp without time zone,
    anex_depe_codi character varying(7),
    sgd_pnufe_codi numeric(4,0),
    sgd_dnufe_codi numeric(4,0),
    anex_usudoc_creador character varying(15),
    sgd_fech_doc timestamp without time zone,
    sgd_apli_codi numeric(4,0),
    sgd_trad_codigo numeric(2,0),
    sgd_dir_direccion character varying(150),
    muni_codi numeric(4,0),
    dpto_codi numeric(2,0),
    sgd_exp_numero character varying(18),
    anex_tipo_envio numeric(2,0),
    sgd_exp_prestamo integer DEFAULT 0 NOT NULL,
    anex_carpeta character varying(512),
    anex_desc2 character varying(512),
    anex_tipo_final numeric(4,0),
    sgd_dir_mail text,
    anex_adjuntos_rr text,
    anex_env_email numeric(2,0) DEFAULT 0,
    anex_hash character varying(300)
);


--
-- Name: COLUMN anexos.anex_tipo_envio; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.anexos.anex_tipo_envio IS 'Codigo de envio que debe estar en la tabla medio_recepcion.';


--
-- Name: COLUMN anexos.sgd_exp_prestamo; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.anexos.sgd_exp_prestamo IS 'Prestamo de expedientes  --1 true - 0 false--';


--
-- Name: COLUMN anexos.anex_env_email; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.anexos.anex_env_email IS 'Variable que determina si el correo fue enviado (1) o no ha sido enviado (0)';


--
-- Name: anexos_historico; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.anexos_historico (
    anex_hist_anex_codi character varying(20) NOT NULL,
    anex_hist_num_ver numeric(4,0) NOT NULL,
    anex_hist_tipo_mod character varying(2) NOT NULL,
    anex_hist_usua character varying(15) NOT NULL,
    anex_hist_fecha timestamp without time zone DEFAULT now() NOT NULL,
    usua_doc character varying(14)
);


--
-- Name: anexos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.anexos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: anexos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.anexos_id_seq OWNED BY public.anexos.id;


--
-- Name: anexos_tipo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.anexos_tipo (
    anex_tipo_codi numeric(4,0) NOT NULL,
    anex_tipo_ext character varying(10) NOT NULL,
    anex_tipo_desc character varying(100),
    anex_tipo_mime character varying(150)
);


--
-- Name: autg_grupos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.autg_grupos (
    id integer NOT NULL,
    nombre character varying,
    descripcion character varying
);


--
-- Name: autm_membresias; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.autm_membresias (
    id integer NOT NULL,
    autg_id integer,
    autu_id integer
);


--
-- Name: autp_permisos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.autp_permisos (
    id integer NOT NULL,
    autg_id integer,
    nombre character varying(250),
    descripcion character varying(500),
    crud integer,
    dependencia character varying(150)
);


--
-- Name: COLUMN autp_permisos.descripcion; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.autp_permisos.descripcion IS 'Descripción del permiso';


--
-- Name: COLUMN autp_permisos.crud; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.autp_permisos.crud IS 'Realizar crud sobre el elemento.
Leer 1
Editar 2
Crear y Borrar 3';


--
-- Name: COLUMN autp_permisos.dependencia; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.autp_permisos.dependencia IS 'Permite seleccionar el alcanze del elemento, acceso a la pripia depedendencia, todsas las depedencias o ninguna.
0 ninguna
1 propia
2 todas';


--
-- Name: autr_restric_grupo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.autr_restric_grupo (
    id integer,
    autg_id integer,
    autp_id integer
);


--
-- Name: autu_usuarios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.autu_usuarios (
    id integer NOT NULL,
    nombres character varying(250),
    apellidos character varying(250),
    correo character varying(250),
    contrasena character varying(300),
    usuario character varying(150),
    estado character varying
);


--
-- Name: COLUMN autu_usuarios.usuario; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.autu_usuarios.usuario IS 'Identificacion del usuario';


--
-- Name: COLUMN autu_usuarios.estado; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.autu_usuarios.estado IS 'Estado de los usuarios, nos indican si estan activos o no. ';


--
-- Name: bodega_empresas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bodega_empresas (
    nombre_de_la_empresa character varying(160),
    nuir character varying(13),
    nit_de_la_empresa character varying(80),
    sigla_de_la_empresa character varying(80),
    direccion character varying(4000),
    codigo_del_departamento character varying(4000),
    codigo_del_municipio character varying(4000),
    telefono_1 character varying(4000),
    telefono_2 character varying(4000),
    email character varying(4000),
    nombre_rep_legal character varying(4000),
    cargo_rep_legal character varying(4000),
    identificador_empresa numeric(5,0) NOT NULL,
    are_esp_secue numeric(8,0),
    id_cont numeric(2,0) DEFAULT 1,
    id_pais numeric(4,0) DEFAULT 170,
    activa numeric(1,0) DEFAULT 1,
    flag_rups character(1)
);


--
-- Name: carpeta; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.carpeta (
    carp_codi numeric(2,0) NOT NULL,
    carp_desc character varying(80) NOT NULL
);


--
-- Name: carpeta_per; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.carpeta_per (
    usua_codi numeric(10,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    nomb_carp character varying(10),
    desc_carp character varying(30),
    codi_carp numeric(3,0)
);


--
-- Name: centro_poblado; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.centro_poblado (
    cpob_codi numeric(4,0) NOT NULL,
    muni_codi numeric(4,0) NOT NULL,
    dpto_codi numeric(2,0) NOT NULL,
    cpob_nomb character varying(100) NOT NULL,
    cpob_nomb_anterior character varying(100)
);


--
-- Name: choices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.choices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: choices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.choices (
    list_name text,
    list_value character varying(100),
    list_label text,
    list_generadora text,
    project_id integer,
    id bigint DEFAULT nextval('public.choices_id_seq'::regclass) NOT NULL,
    list_order integer,
    description character varying(400),
    image character varying(1000) DEFAULT ''::character varying,
    generic boolean DEFAULT false
);


--
-- Name: COLUMN choices.project_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.choices.project_id IS 'Proyecto al que corresponde el choice';


--
-- Name: choices_table_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.choices_table_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: choices_table; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.choices_table (
    id integer DEFAULT nextval('public.choices_table_id_seq'::regclass) NOT NULL,
    name_table character varying(50),
    key_table character varying(100),
    value_table character varying(100),
    name_description character varying(200)
);


--
-- Name: TABLE choices_table; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public.choices_table IS 'En esta tabla, se guardará un listado de las tablas que el usuario puede usar para seleccionar en los estudios';


--
-- Name: date_seleccionpredios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.date_seleccionpredios (
    barmanpre character varying,
    chip character varying(15),
    expediente character varying(20)
);


--
-- Name: departamento; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.departamento (
    dpto_codi numeric(4,0) NOT NULL,
    dpto_nomb character varying(70) NOT NULL,
    id_cont numeric(2,0) DEFAULT 1,
    id_pais numeric(4,0) DEFAULT 170,
    depto_acapella numeric
);


--
-- Name: dependencia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.dependencia (
    id integer NOT NULL,
    depe_codi numeric(7,0) NOT NULL,
    depe_nomb character varying(70) NOT NULL,
    dpto_codi numeric(2,0),
    depe_codi_padre numeric(7,0),
    muni_codi numeric(4,0),
    depe_codi_territorial numeric(7,0),
    dep_sigla character varying(100),
    dep_central numeric(1,0),
    dep_direccion character varying(100),
    depe_num_interna numeric(4,0),
    depe_num_resolucion numeric(4,0),
    depe_rad_tp1 numeric(3,0),
    depe_rad_tp2 numeric(3,0),
    depe_rad_tp3 numeric(3,0),
    acto_admon character varying(100),
    id_cont numeric(2,0) DEFAULT 1,
    id_pais numeric(4,0) DEFAULT 170,
    depe_estado numeric(1,0) DEFAULT 1,
    depe_rad_tp4 smallint,
    depe_rad_tp5 smallint,
    depe_rad_tp9 smallint,
    depe_rad_tp7 smallint,
    depe_rad_tp8 smallint,
    depe_rad_tp6 smallint
);


--
-- Name: dependencia_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.dependencia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: dependencia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.dependencia_id_seq OWNED BY public.dependencia.id;


--
-- Name: dependencia_visibilidad; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.dependencia_visibilidad (
    codigo_visibilidad numeric(18,0) NOT NULL,
    dependencia_visible numeric(5,0) NOT NULL,
    dependencia_observa numeric(5,0) NOT NULL
);


--
-- Name: estado; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.estado (
    esta_codi numeric(2,0) NOT NULL,
    esta_desc character varying(100) NOT NULL
);


--
-- Name: field_type_id_field_type_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.field_type_id_field_type_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: field_type; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.field_type (
    id_field_type integer DEFAULT nextval('public.field_type_id_field_type_seq'::regclass) NOT NULL,
    cod_field_type character varying(50),
    desc_field_type character varying(400)
);


--
-- Name: fields_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fields_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fields_idpk_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fields_idpk_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fields; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fields (
    idpk integer DEFAULT nextval('public.fields_idpk_seq'::regclass) NOT NULL,
    poll_id character varying(100),
    poll_date date DEFAULT now(),
    poll_version character varying(100),
    us_id integer,
    field_id character varying(100) NOT NULL,
    field_description character varying(2000),
    field_questiontype character varying(50),
    field_type character varying(50),
    field_null character varying(20),
    field_default character varying(20),
    field_answersoption character varying(2000),
    field_nextid character varying(20),
    field_save integer,
    id bigint DEFAULT nextval('public.fields_id_seq'::regclass) NOT NULL,
    field_description2 character varying(1130),
    fileld_date2 timestamp without time zone,
    field_date2 timestamp without time zone DEFAULT now(),
    field_appearence character varying(80),
    field_constraint character varying(80),
    field_relevant character varying(80),
    field_constraint_message character varying(180),
    field_choicefilter character varying(180),
    field_choicename character varying(100),
    project_id integer,
    field_view_web integer DEFAULT 1,
    field_order integer,
    field_menu character varying(60),
    type_plot text DEFAULT 'pie'::text,
    field_lime_survey character varying(200),
    choice_type_id integer,
    field_objecttype character varying(60),
    field_export integer DEFAULT 0,
    estado integer DEFAULT 1,
    field_condition_view character varying(1000),
    field_nested_condition character varying(1000),
    is_required boolean DEFAULT true,
    is_numeric boolean DEFAULT false,
    field_limit character varying(4) DEFAULT '20'::character varying,
    is_searchable boolean DEFAULT false,
    field_menu_samples character varying(60),
    asociar character varying(100),
    field_view_map integer DEFAULT 0,
    field_pattern character varying(50)
);


--
-- Name: COLUMN fields.field_menu_samples; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.fields.field_menu_samples IS 'Nombre menu (muestras)';


--
-- Name: COLUMN fields.field_pattern; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.fields.field_pattern IS 'Patron para validación.';


--
-- Name: frmf_frmfields; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.frmf_frmfields (
    frmf_code bigint NOT NULL,
    frm_code integer NOT NULL,
    frmf_name character varying(25) NOT NULL,
    frmf_description character varying(100),
    frmf_tablesave character varying(100),
    frmf_field character varying(45),
    frmf_null numeric(1,0),
    frmf_pk integer DEFAULT 0,
    frmf_colspan character varying(20),
    frmf_column character varying(45),
    frmf_step numeric(2,0),
    frmf_order numeric(4,0),
    frmf_mask character varying(100),
    frmf_sql character varying(1000),
    frmt_code integer,
    frmf_help character varying(1300),
    frmf_label character varying(1000),
    frmf_fieldpk character varying(50),
    frmf_tablepksearch character varying(100),
    frmf_fieldpksearch character varying(100),
    frmf_fieldpksave character varying,
    frmf_tablepksave character varying,
    id bigint NOT NULL,
    frmf_params character varying(1500),
    frmf_rowspan character varying(2) DEFAULT 0,
    frmf_default character varying,
    frmf_vars integer DEFAULT 0,
    frmf_varsparam character varying,
    frmf_table character varying(100)
);


--
-- Name: frmf_frmfields_frmf_code_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.frmf_frmfields_frmf_code_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: frmf_frmfields_frmf_code_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.frmf_frmfields_frmf_code_seq OWNED BY public.frmf_frmfields.frmf_code;


--
-- Name: frmf_frmfields_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.frmf_frmfields_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: frmf_frmfields_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.frmf_frmfields_id_seq OWNED BY public.frmf_frmfields.id;


--
-- Name: fun_funcionario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fun_funcionario (
    id integer NOT NULL,
    usua_doc character varying(14),
    usua_fech_crea timestamp without time zone NOT NULL,
    usua_esta character varying(10) NOT NULL,
    usua_nomb character varying(45),
    usua_ext numeric(4,0),
    usua_nacim date,
    usua_email character varying(50),
    usua_at character varying(15),
    usua_piso numeric(2,0),
    cedula_ok character(1) DEFAULT 'n'::bpchar,
    cedula_suip character varying(15),
    nombre_suip character varying(45),
    observa character(20)
);


--
-- Name: fun_funcionario_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fun_funcionario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fun_funcionario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fun_funcionario_id_seq OWNED BY public.fun_funcionario.id;


--
-- Name: hist_eventos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hist_eventos (
    id integer NOT NULL,
    depe_codi numeric(7,0) NOT NULL,
    hist_fech timestamp without time zone NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    radi_nume_radi numeric(20,0) NOT NULL,
    hist_obse character varying(600) NOT NULL,
    usua_codi_dest numeric(10,0),
    usua_doc character varying(14),
    usua_doc_old character varying(15),
    sgd_ttr_codigo numeric(3,0),
    hist_usua_autor character varying(14),
    hist_doc_dest character varying(14),
    depe_codi_dest numeric(7,0),
    usuario_id integer
);


--
-- Name: COLUMN hist_eventos.usuario_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.hist_eventos.usuario_id IS 'Identificador del usuario, que realiza la transaccion en el Historico.';


--
-- Name: hist_eventos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hist_eventos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hist_eventos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hist_eventos_id_seq OWNED BY public.hist_eventos.id;


--
-- Name: informados; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.informados (
    radi_nume_radi numeric(20,0) NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    depe_codi numeric(7,0) NOT NULL,
    info_desc character varying(600),
    info_fech timestamp without time zone NOT NULL,
    info_leido numeric(1,0) DEFAULT 0,
    usua_codi_info numeric(3,0),
    info_codi numeric(20,0),
    usua_doc character varying(14),
    info_conjunto integer DEFAULT 0,
    usua_doc_origen character varying(20)
);


--
-- Name: medio_recepcion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.medio_recepcion (
    mrec_codi numeric(2,0) NOT NULL,
    mrec_desc character varying(100) NOT NULL
);


--
-- Name: municipio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.municipio (
    muni_codi numeric(4,0) NOT NULL,
    dpto_codi numeric(4,0) NOT NULL,
    muni_nomb character varying(100) NOT NULL,
    id_cont numeric(2,0) DEFAULT 1,
    id_pais numeric(4,0) DEFAULT 170,
    homologa_muni character varying(10),
    homologa_idmuni numeric(4,0),
    activa numeric(1,0) DEFAULT 1
);


--
-- Name: par_serv_servicios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.par_serv_servicios (
    par_serv_secue numeric(8,0) NOT NULL,
    par_serv_codigo character varying(5),
    par_serv_nombre character varying(100),
    par_serv_estado character varying(1)
);


--
-- Name: prestamo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.prestamo (
    pres_id numeric(10,0) NOT NULL,
    radi_nume_radi numeric(20,0) NOT NULL,
    usua_login_actu character varying(20) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    usua_login_pres character varying(20),
    pres_desc character varying(200),
    pres_fech_pres timestamp without time zone,
    pres_fech_devo timestamp without time zone,
    pres_fech_pedi timestamp without time zone NOT NULL,
    pres_estado numeric(2,0),
    pres_requerimiento numeric(2,0),
    pres_depe_arch numeric(5,0),
    pres_fech_venc timestamp without time zone,
    dev_desc character varying(500),
    pres_fech_canc timestamp without time zone,
    usua_login_canc character varying(20),
    usua_login_rx character varying(20),
    sgd_exp_numero character varying(40)
);


--
-- Name: projects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: projects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.projects (
    id integer DEFAULT nextval('public.projects_id_seq'::regclass) NOT NULL,
    name text,
    description text,
    name_show text,
    name_dev text,
    samples numeric,
    style text,
    where_sql text,
    proyecto integer DEFAULT 0,
    activo integer DEFAULT 0,
    style_color text DEFAULT 'f80505'::text,
    proceso numeric(4,0),
    etapa integer
);


--
-- Name: radicado; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.radicado (
    id integer NOT NULL,
    radi_nume_radi numeric(20,0) NOT NULL,
    radi_fech_radi timestamp without time zone NOT NULL,
    fech_alertarad timestamp without time zone,
    tdoc_codi numeric(7,0) DEFAULT 0 NOT NULL,
    trte_codi numeric(2,0) DEFAULT 0,
    mrec_codi numeric(2,0),
    eesp_codi numeric(10,0),
    radi_fech_ofic timestamp without time zone,
    tdid_codi numeric(2,0) DEFAULT 0,
    radi_pais character varying(70),
    muni_codi numeric(5,0),
    cpob_codi numeric(4,0),
    carp_codi numeric(3,0),
    esta_codi numeric(2,0),
    dpto_codi numeric(2,0),
    cen_muni_codi numeric(4,0),
    cen_dpto_codi numeric(2,0),
    radi_nume_hoja numeric(5,0),
    radi_desc_anex character varying(600),
    radi_nume_deri numeric(20,0),
    radi_path character varying(300),
    radi_usua_actu numeric(10,0),
    radi_depe_actu numeric(5,0),
    ra_asun character varying(500),
    radi_usu_ante character varying(45),
    radi_depe_radi numeric(3,0),
    radi_usua_radi numeric(10,0),
    codi_nivel numeric(2,0) DEFAULT 1,
    flag_nivel numeric,
    carp_per numeric(1,0) DEFAULT 0,
    radi_leido numeric(1,0) DEFAULT 0,
    radi_cuentai character varying(100),
    radi_tipo_deri numeric(2,0) DEFAULT 0,
    listo character varying(2),
    sgd_tma_codigo numeric(4,0),
    sgd_mtd_codigo numeric(4,0),
    par_serv_secue numeric(8,0),
    sgd_fld_codigo numeric(3,0) DEFAULT 0,
    radi_agend numeric(1,0),
    radi_fech_agend timestamp without time zone,
    radi_fech_doc timestamp without time zone,
    sgd_doc_secuencia numeric(15,0),
    sgd_pnufe_codi numeric(4,0),
    sgd_eanu_codigo numeric(1,0),
    sgd_not_codi numeric(3,0),
    radi_fech_notif timestamp without time zone,
    sgd_tdec_codigo numeric(4,0),
    sgd_apli_codi numeric(4,0),
    sgd_ttr_codigo numeric,
    usua_doc_ante character varying(14),
    radi_fech_antetx timestamp without time zone,
    sgd_trad_codigo numeric(3,0),
    fech_vcmto timestamp without time zone,
    tdoc_vcmto numeric(4,0),
    sgd_termino_real numeric(4,0),
    id_cont numeric(2,0) DEFAULT 1,
    sgd_spub_codigo numeric(2,0) DEFAULT 0,
    id_pais numeric(4,0),
    radi_nrr numeric(2,0) DEFAULT 0,
    medio_m numeric(4,0),
    depe_codi numeric(7,0),
    radi_nume_folio numeric(5,0),
    radi_nume_anexo character varying(50),
    radi_nume_guia character varying(60),
    radi_nume_iden character varying(15),
    sgd_rad_codigoverificacion character varying(35),
    radi_nume_acapella character varying(32),
    radicador_id integer,
    id_tercero integer,
    id_contacto integer,
    medio_id integer,
    origen character varying,
    referenciados character varying,
    intencion character varying,
    sender_id integer,
    target_id integer,
    area_sender_id integer,
    area_target_id integer,
    numero_radicado character varying(50),
    id_radicado character varying(50),
    esta_fisico numeric(2,0) DEFAULT 0,
    radi_dato_001 character varying(200),
    radi_dato_002 character varying(200),
    eotra_codi numeric(10,0),
    radi_arch1 character varying(10),
    radi_arch2 character varying(10),
    radi_arch3 character varying(10),
    radi_arch4 character varying(10),
    radi_dire_corr character varying(100),
    radi_fech_asig timestamp without time zone,
    radi_nomb character varying(90),
    radi_prim_apel character varying(50),
    radi_rem character varying(60),
    radi_segu_apel character varying(50),
    radi_tele_cont numeric(20,0),
    radi_tipo_empr character varying(2),
    radi_usua_radiori character varying(10),
    radi_imagen_hash character varying(300),
    meta_datos json,
    radi_firma boolean
);


--
-- Name: COLUMN radicado.radi_nume_acapella; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.radicado.radi_nume_acapella IS 'Numero de radicado acapella';


--
-- Name: radicado_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.radicado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: radicado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.radicado_id_seq OWNED BY public.radicado.id;


--
-- Name: sec_ciu_ciudadano; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sec_ciu_ciudadano
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sec_dir_direcciones; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sec_dir_direcciones
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sec_dir_drecciones; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sec_dir_drecciones
    START WITH 528610
    INCREMENT BY 1
    MINVALUE 0
    MAXVALUE 999999999999
    CACHE 1;


--
-- Name: sec_edificio; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sec_edificio
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sec_oem_oempresas; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sec_oem_oempresas
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sec_prestamo; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sec_prestamo
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_planillas; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_planillas
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp1_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp1_900
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp2_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp2_900
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp3_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp3_900
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp4_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp4_900
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp5_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp5_900
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp6_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp6_900
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp7_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp7_900
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: secr_tp8_900; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.secr_tp8_900
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: series; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.series (
    depe_codi numeric(7,0) NOT NULL,
    seri_nume numeric(7,0) NOT NULL,
    seri_tipo numeric(2,0) NOT NULL,
    seri_ano numeric(4,0) NOT NULL,
    dpto_codi numeric(2,0) NOT NULL,
    bloq character varying(20)
);


--
-- Name: sgd_acl; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_acl (
    id integer NOT NULL,
    profile_id integer,
    hierarchy character varying(10)
);


--
-- Name: sgd_acl_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_acl_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_acl_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_acl_id_seq OWNED BY public.sgd_acl.id;


--
-- Name: sgd_acl_profiles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_acl_profiles (
    id integer NOT NULL,
    name text
);


--
-- Name: sgd_acl_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_acl_profiles_id_seq
    AS integer
    START WITH 13
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_acl_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_acl_profiles_id_seq OWNED BY public.sgd_acl_profiles.id;


--
-- Name: sgd_acm_acusemsg; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_acm_acusemsg (
    sgd_msg_codi numeric(15,0) NOT NULL,
    usua_doc character varying(14) NOT NULL,
    sgd_msg_leido numeric(3,0)
);


--
-- Name: sgd_agen_agendados; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_agen_agendados (
    id integer NOT NULL,
    sgd_agen_fech date,
    sgd_agen_observacion character varying(4000),
    radi_nume_radi numeric(15,0) NOT NULL,
    usua_doc character varying(18) NOT NULL,
    depe_codi character varying(4000),
    sgd_agen_codigo numeric,
    sgd_agen_fechplazo date,
    sgd_agen_activo numeric
);


--
-- Name: sgd_agen_agendados_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_agen_agendados_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_agen_agendados_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_agen_agendados_id_seq OWNED BY public.sgd_agen_agendados.id;


--
-- Name: sgd_anar_anexarg; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_anar_anexarg (
    sgd_anar_codi numeric(4,0) NOT NULL,
    anex_codigo character varying(20),
    sgd_argd_codi numeric(4,0),
    sgd_anar_argcod numeric(4,0)
);


--
-- Name: sgd_anu_anulados; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_anu_anulados (
    sgd_anu_id numeric(4,0) NOT NULL,
    sgd_anu_desc character varying(250),
    radi_nume_radi numeric,
    sgd_eanu_codi numeric(4,0),
    sgd_anu_sol_fech timestamp without time zone,
    sgd_anu_fech timestamp without time zone,
    depe_codi numeric(7,0),
    usua_doc character varying(14),
    usua_codi numeric(4,0),
    depe_codi_anu numeric(7,0),
    usua_doc_anu character varying(14),
    usua_codi_anu numeric(4,0),
    usua_anu_acta numeric(8,0),
    sgd_anu_path_acta character varying(200),
    sgd_trad_codigo numeric(2,0)
);


--
-- Name: sgd_anu_id; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_anu_id
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_aper_adminperfiles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_aper_adminperfiles (
    sgd_aper_codigo numeric(2,0) NOT NULL,
    sgd_aper_descripcion character varying(20)
);


--
-- Name: sgd_aplfad_plicfunadi; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_aplfad_plicfunadi (
    sgd_aplfad_codi numeric(4,0) NOT NULL,
    sgd_apli_codi numeric(4,0),
    sgd_aplfad_menu character varying(150) NOT NULL,
    sgd_aplfad_lk1 character varying(150) NOT NULL,
    sgd_aplfad_desc character varying(150) NOT NULL
);


--
-- Name: sgd_apli_aplintegra; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_apli_aplintegra (
    sgd_apli_codi numeric(4,0) NOT NULL,
    sgd_apli_descrip character varying(150),
    sgd_apli_lk1desc character varying(150),
    sgd_apli_lk1 character varying(150),
    sgd_apli_lk2desc character varying(150),
    sgd_apli_lk2 character varying(150),
    sgd_apli_lk3desc character varying(150),
    sgd_apli_lk3 character varying(150),
    sgd_apli_lk4desc character varying(150),
    sgd_apli_lk4 character varying(150)
);


--
-- Name: sgd_aplmen_aplimens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_aplmen_aplimens (
    sgd_aplmen_codi numeric(6,0) NOT NULL,
    sgd_apli_codi numeric(4,0),
    sgd_aplmen_ref character varying(20),
    sgd_aplmen_haciaorfeo numeric(4,0),
    sgd_aplmen_desdeorfeo numeric(4,0)
);


--
-- Name: sgd_aplus_plicusua; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_aplus_plicusua (
    sgd_aplus_codi numeric(4,0) NOT NULL,
    sgd_apli_codi numeric(4,0),
    usua_doc character varying(14),
    sgd_trad_codigo numeric(2,0),
    sgd_aplus_prioridad numeric(1,0)
);


--
-- Name: sgd_arg_pliego; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_arg_pliego (
    sgd_arg_codigo numeric(2,0) NOT NULL,
    sgd_arg_desc character varying(150) NOT NULL
);


--
-- Name: sgd_argd_argdoc; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_argd_argdoc (
    sgd_argd_codi numeric(4,0) NOT NULL,
    sgd_pnufe_codi numeric(4,0),
    sgd_argd_tabla character varying(100),
    sgd_argd_tcodi character varying(100),
    sgd_argd_tdes character varying(100),
    sgd_argd_llist character varying(150),
    sgd_argd_campo character varying(100)
);


--
-- Name: sgd_argup_argudoctop; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_argup_argudoctop (
    sgd_argup_codi numeric(4,0) NOT NULL,
    sgd_argup_desc character varying(50),
    sgd_tpr_codigo numeric(4,0)
);


--
-- Name: sgd_auditoria; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_auditoria (
    usua_doc character varying(50),
    tipo character varying(20),
    tabla character varying(50),
    isql text,
    fecha numeric(20,0),
    ip character varying(40)
);


--
-- Name: sgd_camexp_campoexpediente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_camexp_campoexpediente (
    sgd_camexp_codigo numeric(4,0) NOT NULL,
    sgd_camexp_campo character varying(30) NOT NULL,
    sgd_parexp_codigo numeric(4,0) NOT NULL,
    sgd_camexp_fk numeric DEFAULT 0,
    sgd_camexp_tablafk character varying(30),
    sgd_camexp_campofk character varying(30),
    sgd_camexp_campovalor character varying(30),
    sgd_campexp_orden numeric(1,0),
    sgd_camexp_orden numeric(1,0)
);


--
-- Name: sgd_carp_descripcion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_carp_descripcion (
    sgd_carp_depecodi numeric(7,0) NOT NULL,
    sgd_carp_tiporad numeric(2,0) NOT NULL,
    sgd_carp_descr character varying(40)
);


--
-- Name: sgd_cau_causal; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_cau_causal (
    sgd_cau_codigo numeric(4,0) NOT NULL,
    sgd_cau_descrip character varying(150)
);


--
-- Name: sgd_caux_causales; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_caux_causales (
    sgd_caux_codigo numeric(10,0) NOT NULL,
    radi_nume_radi numeric(20,0),
    sgd_dcau_codigo numeric(4,0),
    sgd_ddca_codigo numeric(4,0),
    sgd_caux_fecha timestamp without time zone,
    usua_doc character varying(14),
    sgd_cau_codigo numeric(4,0) DEFAULT 0,
    sgd_ddca_ddsgrgdo numeric(5,0)
);


--
-- Name: sgd_ciu_ciudadano; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_ciu_ciudadano (
    tdid_codi numeric(2,0),
    sgd_ciu_codigo numeric(10,0) NOT NULL,
    sgd_ciu_nombre character varying(300),
    sgd_ciu_direccion character varying(300),
    sgd_ciu_apell1 character varying(300),
    sgd_ciu_apell2 character varying(300),
    sgd_ciu_telefono character varying(300),
    sgd_ciu_email character varying(300),
    muni_codi numeric(4,0),
    dpto_codi numeric(4,0),
    sgd_ciu_cedula character varying(13),
    id_cont numeric(2,0) DEFAULT 1,
    id_pais numeric(4,0) DEFAULT 170
);


--
-- Name: sgd_clta_clstarif; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_clta_clstarif (
    sgd_fenv_codigo numeric(5,0),
    sgd_clta_codser numeric(5,0),
    sgd_tar_codigo numeric(5,0),
    sgd_clta_descrip character varying(100),
    sgd_clta_pesdes numeric(15,0),
    sgd_clta_peshast numeric(15,0)
);


--
-- Name: sgd_cob_campobliga; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_cob_campobliga (
    sgd_cob_codi numeric(4,0) NOT NULL,
    sgd_cob_desc character varying(150),
    sgd_cob_label character varying(50),
    sgd_tidm_codi numeric(4,0)
);


--
-- Name: sgd_config; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_config (
    conf_descripcion character varying(500) NOT NULL,
    conf_nombre character varying(50) NOT NULL,
    conf_valor character varying(500) NOT NULL,
    conf_constante character varying(50),
    conf_arreglo character varying(50),
    conf_imagen character varying(1)
);


--
-- Name: sgd_csop_coment; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_csop_coment (
    sgd_csop_id numeric(6,0) NOT NULL,
    sgd_sop_id numeric(6,0) NOT NULL,
    sgd_csop_coment character varying(1000) NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    sgd_csop_fecha date NOT NULL
);


--
-- Name: COLUMN sgd_csop_coment.sgd_csop_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_csop_coment.sgd_csop_id IS 'Identificacion del comentario realizado a un soporte';


--
-- Name: COLUMN sgd_csop_coment.sgd_sop_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_csop_coment.sgd_sop_id IS 'No de identificacion del soporte al cual se le realiza el comentario';


--
-- Name: COLUMN sgd_csop_coment.usua_codi; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_csop_coment.usua_codi IS 'Codigo del usuario que realiza la transacción ';


--
-- Name: COLUMN sgd_csop_coment.depe_codi; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_csop_coment.depe_codi IS 'Dependencia a la que pertenece el usuario que realiza la transacción';


--
-- Name: sgd_dcau_causal; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_dcau_causal (
    sgd_dcau_codigo numeric(4,0) NOT NULL,
    sgd_cau_codigo numeric(4,0),
    sgd_dcau_descrip character varying(150)
);


--
-- Name: sgd_ddca_ddsgrgdo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_ddca_ddsgrgdo (
    sgd_ddca_codigo numeric(4,0) NOT NULL,
    sgd_dcau_codigo numeric(4,0),
    par_serv_secue numeric(8,0),
    sgd_ddca_descrip character varying(150)
);


--
-- Name: sgd_def_contactos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_def_contactos (
    ctt_id numeric(15,0) NOT NULL,
    ctt_nombre character varying(60) NOT NULL,
    ctt_cargo character varying(60) NOT NULL,
    ctt_telefono character varying(25),
    ctt_id_tipo numeric(4,0) NOT NULL,
    ctt_id_empresa numeric(15,0) NOT NULL
);


--
-- Name: sgd_def_continentes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_def_continentes (
    id_cont numeric(2,0) NOT NULL,
    nombre_cont character varying(20) NOT NULL
);


--
-- Name: sgd_def_paises; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_def_paises (
    id_pais numeric(4,0) NOT NULL,
    id_cont numeric(2,0) DEFAULT 1 NOT NULL,
    nombre_pais character varying(30) NOT NULL,
    id_pais_1 numeric(3,0)
);


--
-- Name: sgd_deve_dev_envio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_deve_dev_envio (
    sgd_deve_codigo numeric(2,0) NOT NULL,
    sgd_deve_desc character varying(150) NOT NULL
);


--
-- Name: sgd_dir_direcciones; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_dir_direcciones
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_dir_drecciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_dir_drecciones (
    id integer NOT NULL,
    sgd_dir_codigo numeric(10,0) NOT NULL,
    sgd_dir_tipo numeric(4,0) NOT NULL,
    sgd_oem_codigo numeric(15,0),
    sgd_ciu_codigo numeric(15,0),
    radi_nume_radi numeric(20,0),
    sgd_esp_codi numeric(5,0),
    muni_codi numeric(4,0),
    dpto_codi numeric(4,0),
    sgd_dir_direccion character varying(200),
    sgd_dir_telefono character varying(50),
    sgd_dir_mail character varying(50),
    sgd_sec_codigo numeric(13,0),
    sgd_temporal_nombre character varying(250),
    anex_codigo numeric(20,0),
    sgd_anex_codigo character varying(20),
    sgd_dir_nombre character varying(250),
    sgd_doc_fun character varying(14),
    sgd_dir_nomremdes character varying(1000),
    sgd_trd_codigo numeric(1,0),
    sgd_dir_tdoc numeric(1,0),
    sgd_dir_doc character varying(14),
    id_pais numeric(4,0) DEFAULT 170,
    id_cont numeric(2,0) DEFAULT 1,
    id_tercero integer,
    id_contacto integer,
    departamento_id integer,
    ciudad_id integer,
    sender_id integer,
    target_id integer,
    area_sender_id integer,
    area_target_id integer,
    sgd_dir_apellido character varying(1000),
    sgd_dir_enviado numeric(1,0) DEFAULT 0,
    meta_datos json
);


--
-- Name: COLUMN sgd_dir_drecciones.sgd_dir_enviado; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_dir_drecciones.sgd_dir_enviado IS 'Codigo de envio, si este destinatario fue causa de un envio en el radicado indicado.';


--
-- Name: sgd_dir_drecciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_dir_drecciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_dir_drecciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_dir_drecciones_id_seq OWNED BY public.sgd_dir_drecciones.id;


--
-- Name: sgd_dnufe_docnufe; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_dnufe_docnufe (
    sgd_dnufe_codi numeric(4,0) NOT NULL,
    sgd_pnufe_codi numeric(4,0),
    sgd_tpr_codigo numeric(4,0),
    sgd_dnufe_label character varying(150),
    trte_codi numeric(2,0),
    sgd_dnufe_main character varying(1),
    sgd_dnufe_path character varying(150),
    sgd_dnufe_gerarq character varying(10),
    anex_tipo_codi numeric(4,0)
);


--
-- Name: sgd_eanu_estanulacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_eanu_estanulacion (
    sgd_eanu_desc character varying(150),
    sgd_eanu_codi numeric NOT NULL
);


--
-- Name: sgd_einv_inventario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_einv_inventario (
    sgd_einv_codigo numeric NOT NULL,
    sgd_depe_nomb character varying(400),
    sgd_depe_codi numeric(7,0),
    sgd_einv_expnum character varying(18),
    sgd_einv_titulo character varying(400),
    sgd_einv_unidad numeric,
    sgd_einv_fech date,
    sgd_einv_fechfin date,
    sgd_einv_radicados character varying(40),
    sgd_einv_folios numeric,
    sgd_einv_nundocu numeric,
    sgd_einv_nundocubodega numeric,
    sgd_einv_caja numeric,
    sgd_einv_cajabodega numeric,
    sgd_einv_srd numeric,
    sgd_einv_nomsrd character varying(400),
    sgd_einv_sbrd numeric,
    sgd_einv_nomsbrd character varying(400),
    sgd_einv_retencion character varying(400),
    sgd_einv_disfinal character varying(400),
    sgd_einv_ubicacion character varying(400),
    sgd_einv_observacion character varying(400)
);


--
-- Name: sgd_eit_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_eit_items (
    sgd_eit_codigo numeric NOT NULL,
    sgd_eit_cod_padre character varying(4) DEFAULT '0'::character varying,
    sgd_eit_nombre character varying(40),
    sgd_eit_sigla character varying(20),
    codi_dpto numeric(4,0),
    codi_muni numeric(5,0),
    sgd_eit_archivador character varying(4),
    sgd_eit_cajas numeric(6,0),
    sgd_eit_captol numeric(4,0),
    sgd_eit_dpto character varying(400),
    sgd_eit_edificio character varying(400),
    sgd_eit_estante numeric(4,0),
    sgd_eit_itemsn character varying(40),
    sgd_eit_itemso character varying(40),
    sgd_eit_muni character varying(400),
    sgd_eit_piso character varying(4),
    sgd_eit_pisonom character varying(40),
    sgd_eit_zona character varying(40)
);


--
-- Name: sgd_empus_empusuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_empus_empusuario (
    sgd_empus_codigo numeric(5,0) NOT NULL,
    sgd_empus_estado character(1),
    usua_login character varying(40) NOT NULL,
    identificador_empresa numeric(5,0) NOT NULL
);


--
-- Name: sgd_ent_entidades; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_ent_entidades (
    sgd_ent_nit character varying(13) NOT NULL,
    sgd_ent_codsuc character varying(4) NOT NULL,
    sgd_ent_pias numeric(5,0),
    dpto_codi numeric(2,0),
    muni_codi numeric(4,0),
    sgd_ent_descrip character varying(80),
    sgd_ent_direccion character varying(50),
    sgd_ent_telefono character varying(50)
);


--
-- Name: sgd_enve_envioespecial; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_enve_envioespecial (
    sgd_fenv_codigo numeric(4,0),
    sgd_enve_valorl character varying(30),
    sgd_enve_valorn character varying(30),
    sgd_enve_desc character varying(30)
);


--
-- Name: sgd_estc_estconsolid; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_estc_estconsolid (
    sgd_estc_codigo numeric,
    sgd_tpr_codigo numeric,
    dep_nombre character varying(30),
    depe_codi numeric(10,0),
    sgd_estc_ctotal numeric,
    sgd_estc_centramite numeric,
    sgd_estc_csinriesgo numeric,
    sgd_estc_criesgomedio numeric,
    sgd_estc_criesgoalto numeric,
    sgd_estc_ctramitados numeric,
    sgd_estc_centermino numeric,
    sgd_estc_cfueratermino numeric,
    sgd_estc_fechgen date,
    sgd_estc_fechini date,
    sgd_estc_fechfin date,
    sgd_estc_fechiniresp date,
    sgd_estc_fechfinresp date,
    sgd_estc_dsinriesgo numeric,
    sgd_estc_driesgomegio numeric,
    sgd_estc_driesgoalto numeric,
    sgd_estc_dtermino numeric,
    sgd_estc_grupgenerado numeric
);


--
-- Name: sgd_estinst_estadoinstancia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_estinst_estadoinstancia (
    sgd_estinst_codi numeric(4,0) NOT NULL,
    sgd_apli_codi numeric(4,0),
    sgd_instorf_codi numeric(4,0),
    sgd_estinst_valor numeric(4,0),
    sgd_estinst_habilita numeric(1,0),
    sgd_estinst_mensaje character varying(100)
);


--
-- Name: sgd_exp_expediente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_exp_expediente (
    id integer NOT NULL,
    sgd_exp_numero character varying(18) NOT NULL,
    radi_nume_radi numeric(20,0) NOT NULL,
    sgd_exp_fech timestamp without time zone,
    sgd_exp_fech_mod timestamp without time zone,
    depe_codi numeric(7,0),
    usua_codi numeric(10,0),
    usua_doc character varying(15),
    sgd_exp_estado numeric(1,0) DEFAULT 0,
    sgd_exp_titulo character varying(50),
    sgd_exp_asunto character varying(150),
    sgd_exp_carpeta character varying(30),
    sgd_exp_ufisica character varying(20),
    sgd_exp_isla character varying(10),
    sgd_exp_estante character varying(10),
    sgd_exp_caja character varying(10),
    sgd_exp_fech_arch date,
    sgd_srd_codigo numeric(3,0),
    sgd_sbrd_codigo numeric(3,0),
    sgd_fexp_codigo numeric(3,0) DEFAULT 0,
    sgd_exp_subexpediente numeric,
    sgd_exp_archivo numeric(1,0),
    sgd_exp_unicon numeric(1,0),
    sgd_exp_fechfin timestamp without time zone,
    sgd_exp_folios character varying(4),
    sgd_exp_rete numeric(2,0),
    sgd_exp_entrepa numeric(2,0),
    radi_usua_arch character varying(40),
    sgd_exp_edificio character varying(400),
    sgd_exp_caja_bodega character varying(40),
    sgd_exp_carro character varying(40),
    sgd_exp_carpeta_bodega character varying(40),
    sgd_exp_privado numeric(1,0),
    sgd_exp_cd character varying(10),
    sgd_exp_nref character varying(7),
    sgd_exp_fechafin timestamp without time zone,
    sgd_prd_codigo numeric(4,0)
);


--
-- Name: sgd_exp_expediente_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_exp_expediente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_exp_expediente_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_exp_expediente_id_seq OWNED BY public.sgd_exp_expediente.id;


--
-- Name: sgd_fars_faristas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_fars_faristas (
    sgd_fars_codigo numeric(5,0) NOT NULL,
    sgd_pexp_codigo numeric(4,0),
    sgd_fexp_codigoini numeric(6,0),
    sgd_fexp_codigofin numeric(6,0),
    sgd_fars_diasminimo numeric(3,0),
    sgd_fars_diasmaximo numeric(3,0),
    sgd_fars_desc character varying(100),
    sgd_trad_codigo numeric(2,0),
    sgd_srd_codigo numeric(3,0),
    sgd_sbrd_codigo numeric(3,0),
    sgd_fars_tipificacion numeric(1,0),
    sgd_tpr_codigo numeric,
    sgd_fars_automatico numeric,
    sgd_fars_rolgeneral numeric,
    sgd_fars_frmnombre character varying(500),
    sgd_fars_frmlink character varying,
    sgd_fars_frmlinkselect character varying(500),
    sgd_fars_frmsql character varying(500)
);


--
-- Name: sgd_fenv_frmenvio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_fenv_frmenvio (
    sgd_fenv_codigo numeric(5,0) NOT NULL,
    sgd_fenv_descrip character varying(40),
    sgd_fenv_estado numeric(1,0) DEFAULT 1 NOT NULL,
    sgd_fenv_planilla numeric(1,0) DEFAULT 0 NOT NULL
);


--
-- Name: sgd_fexp_flujoexpedientes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_fexp_flujoexpedientes (
    sgd_fexp_codigo numeric(6,0) NOT NULL,
    sgd_pexp_codigo numeric(6,0),
    sgd_fexp_orden numeric(4,0),
    sgd_fexp_terminos numeric(4,0),
    sgd_fexp_imagen character varying(50),
    sgd_fexp_descrip character varying(255),
    sgd_fld_posleft character varying(10),
    sgd_fld_postop character varying(10)
);


--
-- Name: sgd_firrad_firmarads; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_firrad_firmarads (
    sgd_firrad_id numeric(15,0) NOT NULL,
    radi_nume_radi numeric(20,0) NOT NULL,
    usua_doc character varying(14) NOT NULL,
    sgd_firrad_firma character varying(1),
    sgd_firrad_fecha timestamp without time zone,
    sgd_firrad_docsolic character varying(14) NOT NULL,
    sgd_firrad_fechsolic timestamp without time zone NOT NULL,
    sgd_firrad_pk character varying(1000)
);


--
-- Name: sgd_fld_flujodoc; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_fld_flujodoc (
    sgd_fld_codigo numeric(3,0),
    sgd_fld_desc character varying(100),
    sgd_tpr_codigo numeric(3,0),
    sgd_fld_imagen character varying(50),
    sgd_fld_grupoweb numeric
);


--
-- Name: sgd_fun_funciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_fun_funciones (
    sgd_fun_codigo numeric(4,0) NOT NULL,
    sgd_fun_descrip character varying(530),
    sgd_fun_fech_ini timestamp without time zone,
    sgd_fun_fech_fin timestamp without time zone
);


--
-- Name: sgd_hfld_histflujodoc; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_hfld_histflujodoc (
    id integer NOT NULL,
    sgd_hfld_codigo numeric(6,0),
    sgd_fexp_codigo numeric(3,0),
    sgd_exp_fechflujoant timestamp without time zone,
    sgd_hfld_fech timestamp without time zone,
    sgd_exp_numero character varying(18),
    radi_nume_radi numeric(20,0),
    usua_doc character varying(20),
    usua_codi numeric(10,0),
    depe_codi numeric(10,0),
    sgd_ttr_codigo numeric(3,0),
    sgd_fexp_observa character varying(500),
    sgd_hfld_observa character varying(500),
    sgd_fars_codigo numeric(10,0),
    sgd_hfld_automatico numeric(10,0)
);


--
-- Name: sgd_hfld_histflujodoc_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_hfld_histflujodoc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_hfld_histflujodoc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_hfld_histflujodoc_id_seq OWNED BY public.sgd_hfld_histflujodoc.id;


--
-- Name: sgd_hmtd_hismatdoc; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_hmtd_hismatdoc (
    sgd_hmtd_codigo numeric(6,0) NOT NULL,
    sgd_hmtd_fecha timestamp without time zone NOT NULL,
    radi_nume_radi numeric(20,0) NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    sgd_hmtd_obse character varying(600) NOT NULL,
    usua_doc numeric(13,0),
    depe_codi numeric(10,0),
    sgd_mtd_codigo numeric(4,0)
);


--
-- Name: sgd_instorf_instanciasorfeo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_instorf_instanciasorfeo (
    sgd_instorf_codi numeric(4,0) NOT NULL,
    sgd_instorf_desc character varying(100)
);


--
-- Name: sgd_lip_linkip; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_lip_linkip (
    sgd_lip_id numeric(4,0) NOT NULL,
    sgd_lip_ipini character varying(20) NOT NULL,
    sgd_lip_ipfin character varying(20),
    sgd_lip_dirintranet text NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    sgd_lip_arch text,
    sgd_lip_diascache numeric(5,0),
    sgd_lip_rutaftp character varying(150),
    sgd_lip_servftp character varying(50),
    sgd_lip_usbd character varying(20),
    sgd_lip_nombd character varying(20),
    sgd_lip_pwdbd character varying(20),
    sgd_lip_usftp character varying(20),
    sgd_lip_pwdftp character varying(30)
);


--
-- Name: sgd_masiva_excel; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_masiva_excel (
    sgd_masiva_dependencia numeric(3,0),
    sgd_masiva_usuario numeric(10,0),
    sgd_masiva_tiporadicacion numeric(1,0),
    sgd_masiva_codigo numeric(15,1) NOT NULL,
    sgd_masiva_radicada numeric(1,0),
    sgd_masiva_intervalo numeric(3,0),
    sgd_masiva_rangoini character varying(15),
    sgd_masiva_rangofin character varying(15)
);


--
-- Name: sgd_mat_matriz; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_mat_matriz (
    sgd_mat_codigo numeric(4,0) NOT NULL,
    depe_codi numeric(10,0),
    sgd_fun_codigo numeric(4,0),
    sgd_prc_codigo numeric(4,0),
    sgd_prd_codigo numeric(4,0),
    sgd_mat_fech_ini timestamp without time zone,
    sgd_mat_fech_fin timestamp without time zone,
    sgd_mat_peso_prd numeric(5,2)
);


--
-- Name: sgd_mpes_mddpeso; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_mpes_mddpeso (
    sgd_mpes_codigo numeric(5,0) NOT NULL,
    sgd_mpes_descrip character varying(10)
);


--
-- Name: sgd_mrd_matrird; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_mrd_matrird (
    sgd_mrd_codigo_old numeric(8,0),
    depe_codi numeric(10,0) NOT NULL,
    depe_codi_aplica character varying(100),
    sgd_srd_codigo numeric(7,0) NOT NULL,
    sgd_sbrd_codigo numeric(7,0) NOT NULL,
    sgd_tpr_codigo numeric(7,0) NOT NULL,
    soporte character varying(10),
    sgd_mrd_fechini timestamp without time zone,
    sgd_mrd_fechfin timestamp without time zone,
    sgd_mrd_esta character varying(10),
    sgd_mrd_codigo bigint NOT NULL,
    sgd_srd_id integer DEFAULT 0,
    sgd_sbrd_id integer DEFAULT 0
);


--
-- Name: sgd_mrd_matrird_sgd_mrd_codigo_new_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_mrd_matrird_sgd_mrd_codigo_new_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_mrd_matrird_sgd_mrd_codigo_new_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_mrd_matrird_sgd_mrd_codigo_new_seq OWNED BY public.sgd_mrd_matrird.sgd_mrd_codigo;


--
-- Name: sgd_msdep_msgdep; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_msdep_msgdep (
    sgd_msdep_codi numeric(15,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    sgd_msg_codi numeric(15,0) NOT NULL
);


--
-- Name: sgd_msg_mensaje; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_msg_mensaje (
    sgd_msg_codi numeric(15,0) NOT NULL,
    sgd_tme_codi numeric(3,0),
    sgd_msg_desc character varying(150),
    sgd_msg_fechdesp timestamp without time zone NOT NULL,
    sgd_msg_url character varying(150),
    sgd_msg_veces numeric(3,0),
    sgd_msg_ancho numeric(6,0),
    sgd_msg_largo numeric(6,0),
    sgd_msg_etiqueta character varying(20)
);


--
-- Name: COLUMN sgd_msg_mensaje.sgd_msg_etiqueta; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_msg_mensaje.sgd_msg_etiqueta IS 'Nombre corto para mostrar del mensaje';


--
-- Name: sgd_mtd_matriz_doc; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_mtd_matriz_doc (
    sgd_mtd_codigo numeric(4,0) NOT NULL,
    sgd_mat_codigo numeric(4,0),
    sgd_tpr_codigo numeric(4,0)
);


--
-- Name: sgd_nfn_notifijacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_nfn_notifijacion (
    radi_nume_radi numeric NOT NULL,
    sgd_tdf_codigo numeric NOT NULL,
    sgd_nfn_fechnotusu timestamp without time zone,
    sgd_nfn_fechnotemp timestamp without time zone,
    sgd_nfn_fechfiusu timestamp without time zone,
    sgd_nfn_fechfiemp timestamp without time zone,
    sgd_nfn_fechdesfiusu timestamp without time zone,
    sgd_nfn_fechdesfiemp timestamp without time zone,
    sgd_nfn_sspdapela character varying(2)
);


--
-- Name: sgd_noh_nohabiles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_noh_nohabiles (
    noh_fecha timestamp without time zone NOT NULL
);


--
-- Name: sgd_not_notificacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_not_notificacion (
    sgd_not_codi numeric(3,0) NOT NULL,
    sgd_not_descrip character varying(100) NOT NULL
);


--
-- Name: sgd_novedad_usuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_novedad_usuario (
    usua_doc character varying(20) NOT NULL,
    nov_infor character varying(255),
    nov_reasig character varying(255),
    nov_vobo character varying(255),
    nov_dev character varying(255),
    nov_entr character varying(255)
);


--
-- Name: sgd_ntrd_notifrad; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_ntrd_notifrad (
    radi_nume_radi numeric(20,0) NOT NULL,
    sgd_not_codi numeric(3,0) NOT NULL,
    sgd_ntrd_notificador character varying(150),
    sgd_ntrd_notificado character varying(150),
    sgd_ntrd_fecha_not timestamp without time zone,
    sgd_ntrd_num_edicto numeric(6,0),
    sgd_ntrd_fecha_fija timestamp without time zone,
    sgd_ntrd_fecha_desfija timestamp without time zone,
    sgd_ntrd_observaciones character varying(150)
);


--
-- Name: sgd_oem_oempresas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_oem_oempresas (
    sgd_oem_codigo numeric(15,0) NOT NULL,
    tdid_codi numeric(2,0),
    sgd_oem_oempresa character varying(150),
    sgd_oem_rep_legal character varying(150),
    sgd_oem_nit character varying(14),
    sgd_oem_sigla character varying(50),
    muni_codi numeric(4,0),
    dpto_codi numeric(2,0),
    sgd_oem_direccion character varying(150),
    sgd_oem_telefono character varying(50),
    id_cont numeric(2,0) DEFAULT 1,
    id_pais numeric(4,0) DEFAULT 170,
    sgd_oem_email character varying(100)
);


--
-- Name: sgd_panu_peranulados; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_panu_peranulados (
    sgd_panu_codi numeric(4,0) NOT NULL,
    sgd_panu_desc character varying(200)
);


--
-- Name: sgd_param_admin; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_param_admin (
    param_codigo character varying(20) NOT NULL,
    param_nombre character varying(255),
    param_desc character varying(255),
    param_valor character varying(255)
);


--
-- Name: sgd_parametro; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_parametro (
    param_nomb character varying(25) NOT NULL,
    param_codi numeric(5,0) NOT NULL,
    param_valor character varying(25) NOT NULL
);


--
-- Name: sgd_parexp_paramexpediente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_parexp_paramexpediente (
    sgd_parexp_codigo numeric(4,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    sgd_parexp_tabla character varying(30) NOT NULL,
    sgd_parexp_etiqueta character varying(32) NOT NULL,
    sgd_parexp_orden numeric(2,0),
    sgd_parexp_editable smallint DEFAULT 0,
    id integer NOT NULL
);


--
-- Name: sgd_parexp_paramexpediente_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_parexp_paramexpediente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_parexp_paramexpediente_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_parexp_paramexpediente_id_seq OWNED BY public.sgd_parexp_paramexpediente.id;


--
-- Name: sgd_pexp_procexpedientes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_pexp_procexpedientes (
    sgd_pexp_codigo numeric NOT NULL,
    sgd_pexp_descrip character varying(100),
    sgd_pexp_terminos numeric DEFAULT 0,
    sgd_srd_codigo numeric(10,0),
    sgd_sbrd_codigo numeric(10,0),
    sgd_pexp_automatico numeric(1,0) DEFAULT 1,
    sgd_pexp_tieneflujo numeric
);


--
-- Name: sgd_plan_plantillas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_plan_plantillas (
    id bigint NOT NULL,
    plan_path character varying(100),
    plan_nombre character varying(50),
    plan_fecha timestamp without time zone,
    depe_codi numeric(10,0),
    usua_codi numeric(10,0),
    plan_tipo numeric(4,0),
    plan_plantilla text
);


--
-- Name: sgd_plan_plantillas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_plan_plantillas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_plan_plantillas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_plan_plantillas_id_seq OWNED BY public.sgd_plan_plantillas.id;


--
-- Name: sgd_pnufe_procnumfe; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_pnufe_procnumfe (
    sgd_pnufe_codi numeric(4,0) NOT NULL,
    sgd_tpr_codigo numeric(4,0),
    sgd_pnufe_descrip character varying(150),
    sgd_pnufe_serie character varying(50)
);


--
-- Name: sgd_pnun_procenum; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_pnun_procenum (
    sgd_pnun_codi numeric(4,0) NOT NULL,
    sgd_pnufe_codi numeric(4,0),
    depe_codi numeric(10,0),
    sgd_pnun_prepone character varying(50)
);


--
-- Name: sgd_prc_proceso; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_prc_proceso (
    sgd_prc_codigo numeric(4,0) NOT NULL,
    sgd_prc_descrip character varying(150),
    sgd_prc_fech_ini timestamp without time zone,
    sgd_prc_fech_fin timestamp without time zone
);


--
-- Name: sgd_prd_prcdmentos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_prd_prcdmentos (
    sgd_prd_codigo numeric(4,0) NOT NULL,
    sgd_prc_codigo numeric(4,0),
    sgd_prd_descrip character varying(200),
    sgd_prd_fech_ini timestamp without time zone,
    sgd_prd_fech_fin timestamp without time zone
);


--
-- Name: sgd_rad_metadatos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_rad_metadatos (
    rad_meta_id integer NOT NULL,
    rad_meta_proceso numeric(4,0),
    rad_meta_datos json,
    rad_meta_etapa integer,
    radi_nume_radi numeric(20,0),
    sgd_exp_numero character varying(18)
);


--
-- Name: COLUMN sgd_rad_metadatos.rad_meta_etapa; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_rad_metadatos.rad_meta_etapa IS 'Representa la etapa de visualizacion de los metadatos 1=radicacion 2=Inf.General 3=expedientes';


--
-- Name: sgd_rad_metadatos_rad_meta_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_rad_metadatos_rad_meta_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_rad_metadatos_rad_meta_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_rad_metadatos_rad_meta_id_seq OWNED BY public.sgd_rad_metadatos.rad_meta_id;


--
-- Name: sgd_rda_retdoca; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_rda_retdoca (
    anex_radi_nume numeric(20,0) NOT NULL,
    anex_codigo character varying(20) NOT NULL,
    radi_nume_salida numeric(20,0),
    anex_borrado character varying(1),
    sgd_mrd_codigo numeric(5,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    usua_doc character varying(14) NOT NULL,
    sgd_rda_fech timestamp without time zone,
    sgd_deve_codigo numeric(2,0),
    anex_solo_lect character varying(1),
    anex_radi_fech timestamp without time zone,
    anex_estado numeric(1,0),
    anex_nomb_archivo character varying(50),
    anex_tipo numeric(4,0),
    sgd_dir_tipo numeric(4,0)
);


--
-- Name: sgd_rdf_retdocf; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_rdf_retdocf (
    id integer NOT NULL,
    sgd_mrd_codigo bigint NOT NULL,
    radi_nume_radi numeric(20,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    usua_doc character varying(14) NOT NULL,
    sgd_rdf_fech timestamp without time zone NOT NULL
);


--
-- Name: sgd_rdf_retdocf_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_rdf_retdocf_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_rdf_retdocf_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_rdf_retdocf_id_seq OWNED BY public.sgd_rdf_retdocf.id;


--
-- Name: sgd_renv_regenvio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_renv_regenvio (
    id integer NOT NULL,
    sgd_renv_codigo numeric(6,0) NOT NULL,
    sgd_fenv_codigo numeric(5,0),
    sgd_renv_fech timestamp without time zone,
    radi_nume_sal numeric(20,0),
    sgd_renv_destino character varying(150),
    sgd_renv_telefono character varying(50),
    sgd_renv_mail character varying(250),
    sgd_renv_peso character varying(10),
    sgd_renv_valor character varying(10),
    sgd_renv_certificado numeric(1,0),
    sgd_renv_estado numeric(1,0),
    usua_doc numeric(15,0),
    sgd_renv_nombre character varying(250),
    sgd_rem_destino numeric(1,0) DEFAULT 0,
    sgd_dir_codigo numeric(10,0),
    sgd_renv_planilla character varying(8),
    sgd_renv_fech_sal timestamp without time zone,
    depe_codi numeric(10,0),
    sgd_dir_tipo numeric(4,0) DEFAULT 0,
    radi_nume_grupo numeric(22,0),
    sgd_renv_dir character varying(500),
    sgd_renv_depto character varying(30),
    sgd_renv_mpio character varying(30),
    sgd_renv_tel character varying(20),
    sgd_renv_cantidad numeric(4,0) DEFAULT 0,
    sgd_renv_tipo numeric(1,0) DEFAULT 0,
    sgd_renv_observa character varying(200),
    sgd_renv_grupo numeric(14,0),
    sgd_deve_codigo numeric(2,0),
    sgd_deve_fech timestamp without time zone,
    sgd_renv_valortotal character varying(14) DEFAULT '0'::character varying,
    sgd_renv_valistamiento character varying(10) DEFAULT '0'::character varying,
    sgd_renv_vdescuento character varying(10) DEFAULT '0'::character varying,
    sgd_renv_vadicional character varying(14) DEFAULT '0'::character varying,
    sgd_depe_genera numeric(5,0),
    sgd_renv_pais character varying(30) DEFAULT 'colombia'::character varying
);


--
-- Name: sgd_renv_regenvio_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_renv_regenvio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_renv_regenvio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_renv_regenvio_id_seq OWNED BY public.sgd_renv_regenvio.id;


--
-- Name: sgd_rfax_reservafax; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_rfax_reservafax (
    sgd_rfax_codigo numeric(10,0),
    sgd_rfax_fax character varying(30),
    usua_login character varying(30),
    sgd_rfax_fech timestamp without time zone,
    sgd_rfax_fechradi timestamp without time zone,
    radi_nume_radi numeric(20,0),
    sgd_rfax_observa character varying(500),
    sgd_rfax_dhojas numeric
);


--
-- Name: sgd_rmr_radmasivre; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_rmr_radmasivre (
    sgd_rmr_grupo numeric(20,0) NOT NULL,
    sgd_rmr_radi numeric(20,0) NOT NULL
);


--
-- Name: sgd_sbrd_subserierd; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_sbrd_subserierd (
    sgd_srd_codigo numeric(7,0) NOT NULL,
    sgd_sbrd_codigo numeric(7,0) NOT NULL,
    sgd_sbrd_descrip character varying(255) NOT NULL,
    sgd_sbrd_fechini timestamp without time zone NOT NULL,
    sgd_sbrd_fechfin timestamp without time zone NOT NULL,
    sgd_sbrd_tiemag numeric(4,0),
    sgd_sbrd_tiemac numeric(4,0),
    sgd_sbrd_dispfin character varying(50),
    sgd_sbrd_soporte character varying(50),
    sgd_sbrd_procedi character varying(200),
    id integer NOT NULL,
    sgd_srd_id integer NOT NULL,
    sgd_sbrd_estado integer DEFAULT 1
);


--
-- Name: TABLE sgd_sbrd_subserierd; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE public.sgd_sbrd_subserierd IS 'Tabla que contiene  las Subseries documentales en OrfeoGPL. Modificado por Jloasada 20121';


--
-- Name: COLUMN sgd_sbrd_subserierd.sgd_srd_codigo; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sbrd_subserierd.sgd_srd_codigo IS 'Codigo de Serie Documental, desde la ver 3.8 se elimina el pk en este campo y se crea el Id.  Esto permite la modificacion de este codigo segun conveniencia de las Entidades/Empresas.';


--
-- Name: COLUMN sgd_sbrd_subserierd.sgd_sbrd_fechini; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sbrd_subserierd.sgd_sbrd_fechini IS 'Fecha en la cual inicia la ejecucion de esta subserie Documental.';


--
-- Name: COLUMN sgd_sbrd_subserierd.sgd_sbrd_fechfin; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sbrd_subserierd.sgd_sbrd_fechfin IS 'Fecha en la cual finaliza la ejecucion de esta subserie Documental. ';


--
-- Name: sgd_sbrd_subserierd_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_sbrd_subserierd_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_sbrd_subserierd_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_sbrd_subserierd_id_seq OWNED BY public.sgd_sbrd_subserierd.id;


--
-- Name: sgd_sed_sede; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_sed_sede (
    sgd_sed_codi numeric(4,0) NOT NULL,
    sgd_sed_desc character varying(50),
    sgd_tpr_codigo numeric(4,0)
);


--
-- Name: sgd_senuf_secnumfe; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_senuf_secnumfe (
    sgd_senuf_codi numeric(4,0) NOT NULL,
    sgd_pnufe_codi numeric(4,0),
    depe_codi numeric(10,0),
    sgd_senuf_sec character varying(50)
);


--
-- Name: sgd_sexp_secexpedientes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_sexp_secexpedientes (
    sgd_exp_numero character varying(18) NOT NULL,
    sgd_srd_codigo numeric,
    sgd_sbrd_codigo numeric,
    sgd_sexp_secuencia numeric,
    depe_codi numeric(10,0),
    usua_doc character varying(15),
    sgd_sexp_fech timestamp without time zone,
    sgd_fexp_codigo numeric DEFAULT 0,
    sgd_sexp_ano numeric,
    usua_doc_responsable character varying(18),
    sgd_sexp_parexp1 character varying(1400),
    sgd_sexp_parexp2 character varying(1400),
    sgd_sexp_parexp3 character varying(1400),
    sgd_sexp_parexp4 character varying(1400),
    sgd_sexp_parexp5 character varying(512),
    sgd_pexp_codigo numeric(3,0) DEFAULT 0 NOT NULL,
    sgd_exp_fech_arch timestamp without time zone,
    sgd_fld_codigo numeric(3,0),
    sgd_exp_fechflujoant timestamp without time zone,
    sgd_mrd_codigo numeric(5,0),
    sgd_exp_subexpediente numeric(18,0),
    sgd_exp_privado numeric(1,0),
    sgd_sexp_fechafin timestamp without time zone,
    sgd_exp_caja character varying(10),
    id integer NOT NULL,
    sgd_sexp_parexp6 character varying(1400),
    sgd_sexp_parexp7 character varying(1400),
    sgd_sexp_parexp8 character varying(1400),
    sgd_sexp_parexp9 character varying(1400),
    sgd_sexp_parexp10 character varying(1400),
    sgd_sexp_prestamo integer DEFAULT 0 NOT NULL,
    sgd_cerrado integer,
    sgd_sexp_estado numeric(1,0),
    sgd_srd_id numeric(5,0),
    sgd_sbrd_id numeric(5,0)
);


--
-- Name: COLUMN sgd_sexp_secexpedientes.sgd_sexp_prestamo; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sexp_secexpedientes.sgd_sexp_prestamo IS 'Boolenao para prestamo de expedientes';


--
-- Name: sgd_sexp_secexpedientes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_sexp_secexpedientes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_sexp_secexpedientes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_sexp_secexpedientes_id_seq OWNED BY public.sgd_sexp_secexpedientes.id;


--
-- Name: sgd_sop_soporte; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_sop_soporte (
    sgd_sop_id numeric(6,0) NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    sgd_sop_coment character varying(1000) NOT NULL,
    sgd_sop_est numeric(1,0) DEFAULT 0 NOT NULL,
    sgd_tsop_id numeric(6,0) NOT NULL,
    sgd_sop_fechaini date NOT NULL,
    sgd_sop_fechafin date
);


--
-- Name: COLUMN sgd_sop_soporte.sgd_sop_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.sgd_sop_id IS 'Identificador del numero del soporte';


--
-- Name: COLUMN sgd_sop_soporte.usua_codi; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.usua_codi IS 'Codigo del usaurio que crea el soporte';


--
-- Name: COLUMN sgd_sop_soporte.depe_codi; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.depe_codi IS 'Dependencia del usuario que crea el soporte';


--
-- Name: COLUMN sgd_sop_soporte.sgd_sop_coment; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.sgd_sop_coment IS 'comentario del soporte';


--
-- Name: COLUMN sgd_sop_soporte.sgd_sop_est; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.sgd_sop_est IS 'Estado del soporte, especifica si esta con o sin respuesta';


--
-- Name: COLUMN sgd_sop_soporte.sgd_tsop_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.sgd_tsop_id IS 'Tabla relacionada con el tipo de soporte.';


--
-- Name: COLUMN sgd_sop_soporte.sgd_sop_fechaini; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.sgd_sop_fechaini IS 'Fecha de inicio del soporte';


--
-- Name: COLUMN sgd_sop_soporte.sgd_sop_fechafin; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_sop_soporte.sgd_sop_fechafin IS 'Fecha de finalización del proceso';


--
-- Name: sgd_srd_seriesrd; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_srd_seriesrd (
    sgd_srd_codigo numeric(7,0) NOT NULL,
    sgd_srd_descrip character varying(500) NOT NULL,
    sgd_srd_fechini timestamp without time zone NOT NULL,
    sgd_srd_fechfin timestamp without time zone NOT NULL,
    id integer NOT NULL,
    sgd_srd_estado integer DEFAULT 1
);


--
-- Name: sgd_srd_seriesrd_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_srd_seriesrd_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_srd_seriesrd_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_srd_seriesrd_id_seq OWNED BY public.sgd_srd_seriesrd.id;


--
-- Name: sgd_tar_tarifas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tar_tarifas (
    sgd_fenv_codigo numeric(5,0),
    sgd_tar_codser numeric(5,0),
    sgd_tar_codigo numeric(5,0),
    sgd_tar_valenv1 numeric(15,0),
    sgd_tar_valenv2 numeric(15,0),
    sgd_tar_valenv1g1 numeric(15,0),
    sgd_clta_codser numeric(5,0),
    sgd_tar_valenv2g2 numeric(15,0),
    sgd_clta_descrip character varying(100)
);


--
-- Name: sgd_tdec_tipodecision; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tdec_tipodecision (
    sgd_apli_codi numeric(4,0) NOT NULL,
    sgd_tdec_codigo numeric(4,0) NOT NULL,
    sgd_tdec_descrip character varying(150),
    sgd_tdec_sancionar numeric(1,0),
    sgd_tdec_firmeza numeric(1,0),
    sgd_tdec_versancion numeric(1,0),
    sgd_tdec_showmenu numeric(1,0),
    sgd_tdec_updnotif numeric(1,0),
    sgd_tdec_veragota numeric(1,0)
);


--
-- Name: sgd_tdf_tipodefallos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tdf_tipodefallos (
    sgd_tdf_codigo numeric NOT NULL,
    sgd_tdf_nombre_fallo character varying(50) NOT NULL
);


--
-- Name: sgd_tid_tipdecision; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tid_tipdecision (
    sgd_tid_codi numeric(4,0) NOT NULL,
    sgd_tid_desc character varying(150),
    sgd_tpr_codigo numeric(4,0),
    sgd_pexp_codigo numeric(2,0),
    sgd_tpr_codigop numeric(2,0)
);


--
-- Name: sgd_tidm_tidocmasiva; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tidm_tidocmasiva (
    sgd_tidm_codi numeric(4,0) NOT NULL,
    sgd_tidm_desc character varying(150)
);


--
-- Name: sgd_tip3_tipotercero; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tip3_tipotercero (
    sgd_tip3_codigo numeric(2,0) NOT NULL,
    sgd_dir_tipo numeric(4,0),
    sgd_tip3_nombre character varying(15),
    sgd_tip3_desc character varying(30),
    sgd_tip3_imgpestana character varying(20),
    sgd_tpr_tp1 numeric(1,0) DEFAULT 0,
    sgd_tpr_tp2 numeric(1,0) DEFAULT 0,
    sgd_tpr_tp3 numeric(1,0) DEFAULT 0,
    sgd_tpr_tp4 smallint DEFAULT 1,
    sgd_tpr_tp5 smallint DEFAULT 1,
    sgd_tpr_tp9 smallint DEFAULT 1,
    sgd_tpr_tp7 smallint DEFAULT 1,
    sgd_tpr_tp8 smallint DEFAULT 1,
    sgd_tpr_tp6 smallint DEFAULT 1
);


--
-- Name: sgd_tma_temas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tma_temas (
    sgd_tma_codigo numeric(4,0) NOT NULL,
    depe_codi numeric(10,0),
    sgd_prc_codigo numeric(4,0),
    sgd_tma_descrip character varying(150)
);


--
-- Name: sgd_tme_tipmen; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tme_tipmen (
    sgd_tme_codi numeric(3,0) NOT NULL,
    sgd_tme_desc character varying(150)
);


--
-- Name: sgd_tpr_tpdcumento; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tpr_tpdcumento (
    sgd_tpr_codigo numeric(7,0) NOT NULL,
    sgd_tpr_descrip character varying(150),
    sgd_tpr_termino numeric(4,0),
    sgd_tpr_tpuso numeric(1,0),
    sgd_tpr_numera character(1),
    sgd_tpr_radica character(1),
    sgd_tpr_tp1 numeric(1,0) DEFAULT 0,
    sgd_tpr_tp2 numeric(1,0) DEFAULT 0,
    sgd_tpr_tp3 numeric(1,0) DEFAULT 0,
    sgd_tpr_estado numeric(1,0) DEFAULT 1,
    sgd_termino_real numeric(4,0),
    sgd_tpr_web numeric(1,0) DEFAULT 0,
    sgd_tpr_tp4 smallint,
    sgd_tpr_tp5 smallint,
    sgd_tpr_tp9 smallint,
    sgd_tpr_tp7 smallint,
    sgd_tpr_tp8 smallint,
    sgd_tpr_tp6 smallint,
    id integer NOT NULL
);


--
-- Name: sgd_tpr_tpdcumento_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sgd_tpr_tpdcumento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sgd_tpr_tpdcumento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sgd_tpr_tpdcumento_id_seq OWNED BY public.sgd_tpr_tpdcumento.id;


--
-- Name: sgd_trad_tiporad; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_trad_tiporad (
    sgd_trad_codigo numeric(2,0) NOT NULL,
    sgd_trad_descr character varying(30),
    sgd_trad_icono character varying(30),
    sgd_trad_genradsal numeric(1,0),
    sgd_trad_diasbloqueo numeric(2,0),
    sgd_trad_alerta numeric(1,0),
    sgd_trad_tiempo_alerta numeric(2,0)
);


--
-- Name: sgd_tsop_tiposoporte; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_tsop_tiposoporte (
    sgd_tsop_id numeric(6,0) DEFAULT NULL::numeric NOT NULL,
    sgd_tsop_descr character varying(250) NOT NULL,
    sgd_tsop_depe_codi numeric(10,0),
    sgd_tsop_usua_codi numeric(4,0),
    sgd_tsop_estado numeric(1,0) DEFAULT 0 NOT NULL
);


--
-- Name: COLUMN sgd_tsop_tiposoporte.sgd_tsop_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.sgd_tsop_tiposoporte.sgd_tsop_id IS 'Identificador del tipo de soporte que se secrea';


--
-- Name: sgd_ttr_transaccion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_ttr_transaccion (
    sgd_ttr_codigo numeric(3,0) NOT NULL,
    sgd_ttr_descrip character varying(100) NOT NULL
);


--
-- Name: sgd_ush_usuhistorico; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_ush_usuhistorico (
    sgd_ush_admcod numeric(10,0) NOT NULL,
    sgd_ush_admdep numeric(5,0) NOT NULL,
    sgd_ush_admdoc character varying(14) NOT NULL,
    sgd_ush_usucod numeric(10,0) NOT NULL,
    sgd_ush_usudep numeric(5,0) NOT NULL,
    sgd_ush_usudoc character varying(14) NOT NULL,
    sgd_ush_modcod numeric(5,0) NOT NULL,
    sgd_ush_fechevento timestamp without time zone NOT NULL,
    sgd_ush_usulogin character varying(20) NOT NULL
);


--
-- Name: sgd_usm_usumodifica; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sgd_usm_usumodifica (
    sgd_usm_modcod numeric(5,0) NOT NULL,
    sgd_usm_moddescr character varying(60) NOT NULL
);


--
-- Name: tbl_table_survey_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tbl_table_survey_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tbl_table_survey; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tbl_table_survey (
    id numeric DEFAULT nextval('public.tbl_table_survey_seq'::regclass) NOT NULL,
    id_proyecto character varying(50),
    name_table character varying(200)
);


--
-- Name: tipo_doc_identificacion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipo_doc_identificacion (
    tdid_codi numeric(2,0) NOT NULL,
    tdid_desc character varying(100) NOT NULL
);


--
-- Name: tipo_remitente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipo_remitente (
    trte_codi numeric(2,0) NOT NULL,
    trte_desc character varying(100) NOT NULL,
    sgd_edd_codi numeric(2,0)
);


--
-- Name: ubicacion_fisica; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ubicacion_fisica (
    ubic_depe_radi numeric(5,0) NOT NULL,
    ubic_depe_arch numeric(5,0),
    ubic_inv_piso character varying(2) NOT NULL,
    ubic_inv_piso_desc character varying(40),
    ubic_inv_itemso character varying(40),
    ubic_inv_itemsn character varying(40),
    ubic_inv_archivador character varying(4)
);


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.usuario (
    id integer NOT NULL,
    usua_codi numeric(10,0) NOT NULL,
    depe_codi numeric(10,0) NOT NULL,
    usua_login character varying(40) NOT NULL,
    usua_fech_crea timestamp without time zone NOT NULL,
    usua_pasw character varying(30) NOT NULL,
    usua_esta character varying(10) NOT NULL,
    usua_nomb character varying(45),
    perm_radi character(1) DEFAULT '0'::bpchar,
    usua_admin character(1) DEFAULT '0'::bpchar,
    usua_nuevo character(1) DEFAULT '0'::bpchar,
    usua_doc character varying(14) DEFAULT '0'::character varying,
    codi_nivel numeric(2,0) DEFAULT 1,
    usua_sesion character varying(100),
    usua_fech_sesion timestamp without time zone,
    usua_ext numeric(4,0),
    usua_nacim date,
    usua_email character varying(50),
    usua_at character varying(45),
    usua_piso numeric(5,0),
    perm_radi_sal numeric DEFAULT 0,
    usua_admin_archivo numeric(1,0) DEFAULT 0,
    usua_masiva numeric(1,0) DEFAULT 0,
    usua_perm_dev numeric(1,0) DEFAULT 0,
    usua_perm_numera_res character varying(1),
    usua_doc_suip character varying(15),
    usua_perm_numeradoc numeric(1,0),
    sgd_panu_codi numeric(4,0),
    usua_prad_tp1 numeric(1,0) DEFAULT 0,
    usua_prad_tp2 numeric(1,0) DEFAULT 0,
    usua_prad_tp3 numeric(1,0) DEFAULT 0,
    usua_perm_envios numeric(1,0) DEFAULT 0,
    usua_perm_modifica numeric(1,0) DEFAULT 0,
    usua_perm_impresion numeric(1,0) DEFAULT 0,
    usua_prad_tp9 numeric(1,0),
    sgd_aper_codigo numeric(2,0),
    usu_telefono1 character varying(14),
    usua_encuesta character varying(1),
    sgd_perm_estadistica numeric(2,0),
    usua_perm_sancionados numeric(1,0),
    usua_admin_sistema numeric(1,0),
    usua_perm_trd numeric(1,0),
    usua_perm_firma numeric(1,0),
    usua_perm_prestamo numeric(1,0),
    usuario_publico numeric(1,0),
    usuario_reasignar numeric(1,0),
    usua_perm_notifica numeric(1,0),
    usua_perm_expediente numeric,
    usua_login_externo character varying(15),
    id_pais numeric(4,0) DEFAULT 170,
    id_cont numeric(2,0) DEFAULT 1,
    perm_tipif_anexo numeric,
    perm_vobo character(1) DEFAULT '1'::bpchar,
    perm_archi character(1) DEFAULT '1'::bpchar,
    perm_borrar_anexo numeric,
    usua_perm_adminflujos numeric(1,0) DEFAULT 0 NOT NULL,
    usua_perm_comisiones numeric(1,0),
    usua_exp_trd numeric(1,0),
    usua_perm_rademail numeric(1,0),
    sgd_rol_codigo numeric(1,0) DEFAULT 0,
    usua_email_1 character varying(50),
    usua_email_2 character varying(50),
    usua_perm_respuesta numeric(1,0) DEFAULT 0,
    idacapella1 numeric,
    usua_prad_tp4 smallint,
    idacapella numeric(5,0),
    usua_prad_tp5 smallint,
    usua_prad_tp7 smallint,
    usua_archivo_dig numeric(1,0),
    usua_auth_ldap numeric,
    usua_login_ldap character varying(100),
    usua_prad_tpx1 numeric(1,0),
    usua_prad_tpx2 numeric(1,0),
    usua_prad_tpx3 numeric(1,0),
    usua_prad_tp8 smallint,
    usua_prad_tp6 smallint,
    usua_perm_td text DEFAULT ','::text,
    CONSTRAINT usuario_usua_perm_comisiones_check CHECK (((usua_perm_comisiones = (0)::numeric) OR (usua_perm_comisiones = (1)::numeric) OR (usua_perm_comisiones = (2)::numeric)))
);


--
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuario_id_seq OWNED BY public.usuario.id;


--
-- Name: vista_rad; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.vista_rad AS
 SELECT hist_eventos.radi_nume_radi,
    hist_eventos.hist_fech
   FROM public.hist_eventos
  WHERE (hist_eventos.sgd_ttr_codigo = (2)::numeric);


--
-- Name: vista_rad1; Type: MATERIALIZED VIEW; Schema: public; Owner: -
--

CREATE MATERIALIZED VIEW public.vista_rad1 AS
 SELECT hist_eventos.radi_nume_radi,
    hist_eventos.hist_fech
   FROM public.hist_eventos
  WHERE (hist_eventos.sgd_ttr_codigo = (2)::numeric)
  WITH NO DATA;


--
-- Name: anexos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.anexos ALTER COLUMN id SET DEFAULT nextval('public.anexos_id_seq'::regclass);


--
-- Name: dependencia id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.dependencia ALTER COLUMN id SET DEFAULT nextval('public.dependencia_id_seq'::regclass);


--
-- Name: frmf_frmfields frmf_code; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frmf_frmfields ALTER COLUMN frmf_code SET DEFAULT nextval('public.frmf_frmfields_frmf_code_seq'::regclass);


--
-- Name: frmf_frmfields id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frmf_frmfields ALTER COLUMN id SET DEFAULT nextval('public.frmf_frmfields_id_seq'::regclass);


--
-- Name: fun_funcionario id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fun_funcionario ALTER COLUMN id SET DEFAULT nextval('public.fun_funcionario_id_seq'::regclass);


--
-- Name: hist_eventos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hist_eventos ALTER COLUMN id SET DEFAULT nextval('public.hist_eventos_id_seq'::regclass);


--
-- Name: radicado id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.radicado ALTER COLUMN id SET DEFAULT nextval('public.radicado_id_seq'::regclass);


--
-- Name: sgd_acl id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_acl ALTER COLUMN id SET DEFAULT nextval('public.sgd_acl_id_seq'::regclass);


--
-- Name: sgd_acl_profiles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_acl_profiles ALTER COLUMN id SET DEFAULT nextval('public.sgd_acl_profiles_id_seq'::regclass);


--
-- Name: sgd_agen_agendados id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_agen_agendados ALTER COLUMN id SET DEFAULT nextval('public.sgd_agen_agendados_id_seq'::regclass);


--
-- Name: sgd_dir_drecciones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dir_drecciones ALTER COLUMN id SET DEFAULT nextval('public.sgd_dir_drecciones_id_seq'::regclass);


--
-- Name: sgd_exp_expediente id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_exp_expediente ALTER COLUMN id SET DEFAULT nextval('public.sgd_exp_expediente_id_seq'::regclass);


--
-- Name: sgd_hfld_histflujodoc id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_hfld_histflujodoc ALTER COLUMN id SET DEFAULT nextval('public.sgd_hfld_histflujodoc_id_seq'::regclass);


--
-- Name: sgd_mrd_matrird sgd_mrd_codigo; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mrd_matrird ALTER COLUMN sgd_mrd_codigo SET DEFAULT nextval('public.sgd_mrd_matrird_sgd_mrd_codigo_new_seq'::regclass);


--
-- Name: sgd_parexp_paramexpediente id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_parexp_paramexpediente ALTER COLUMN id SET DEFAULT nextval('public.sgd_parexp_paramexpediente_id_seq'::regclass);


--
-- Name: sgd_plan_plantillas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_plan_plantillas ALTER COLUMN id SET DEFAULT nextval('public.sgd_plan_plantillas_id_seq'::regclass);


--
-- Name: sgd_rad_metadatos rad_meta_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rad_metadatos ALTER COLUMN rad_meta_id SET DEFAULT nextval('public.sgd_rad_metadatos_rad_meta_id_seq'::regclass);


--
-- Name: sgd_rdf_retdocf id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rdf_retdocf ALTER COLUMN id SET DEFAULT nextval('public.sgd_rdf_retdocf_id_seq'::regclass);


--
-- Name: sgd_renv_regenvio id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_renv_regenvio ALTER COLUMN id SET DEFAULT nextval('public.sgd_renv_regenvio_id_seq'::regclass);


--
-- Name: sgd_sbrd_subserierd id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sbrd_subserierd ALTER COLUMN id SET DEFAULT nextval('public.sgd_sbrd_subserierd_id_seq'::regclass);


--
-- Name: sgd_sexp_secexpedientes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sexp_secexpedientes ALTER COLUMN id SET DEFAULT nextval('public.sgd_sexp_secexpedientes_id_seq'::regclass);


--
-- Name: sgd_srd_seriesrd id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_srd_seriesrd ALTER COLUMN id SET DEFAULT nextval('public.sgd_srd_seriesrd_id_seq'::regclass);


--
-- Name: sgd_tpr_tpdcumento id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tpr_tpdcumento ALTER COLUMN id SET DEFAULT nextval('public.sgd_tpr_tpdcumento_id_seq'::regclass);


--
-- Name: usuario id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id SET DEFAULT nextval('public.usuario_id_seq'::regclass);


--
-- Data for Name: anexos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.anexos (id, anex_radi_nume, anex_codigo, anex_tipo, anex_tamano, anex_solo_lect, anex_creador, anex_desc, anex_numero, anex_nomb_archivo, anex_borrado, anex_origen, anex_ubic, anex_salida, radi_nume_salida, anex_radi_fech, anex_estado, usua_doc, sgd_rem_destino, anex_fech_envio, sgd_dir_tipo, anex_fech_impres, anex_depe_creador, sgd_doc_secuencia, sgd_doc_padre, sgd_arg_codigo, sgd_tpr_codigo, sgd_deve_codigo, sgd_deve_fech, sgd_fech_impres, anex_fech_anex, anex_depe_codi, sgd_pnufe_codi, sgd_dnufe_codi, anex_usudoc_creador, sgd_fech_doc, sgd_apli_codi, sgd_trad_codigo, sgd_dir_direccion, muni_codi, dpto_codi, sgd_exp_numero, anex_tipo_envio, sgd_exp_prestamo, anex_carpeta, anex_desc2, anex_tipo_final, sgd_dir_mail, anex_adjuntos_rr, anex_env_email, anex_hash) FROM stdin;
\.


--
-- Data for Name: anexos_historico; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.anexos_historico (anex_hist_anex_codi, anex_hist_num_ver, anex_hist_tipo_mod, anex_hist_usua, anex_hist_fecha, usua_doc) FROM stdin;
\.


--
-- Data for Name: anexos_tipo; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.anexos_tipo (anex_tipo_codi, anex_tipo_ext, anex_tipo_desc, anex_tipo_mime) FROM stdin;
13	csv	csv (separado por comas)	\N
1	doc	.doc (Procesador de texto - Word)	\N
2	xls	.xls (Hoja de calculo)	\N
3	ppt	.ppt (Presentacion)	\N
4	tif	.tif (Imagen)	\N
5	jpg	.jpg (Imagen)	\N
6	gif	.gif (Imagen)	\N
7	pdf	.pdf (Acrobat Reader)	\N
8	txt	.txt (Documento texto)	\N
20	avi	.avi (Video)	\N
21	mpg	.mpg (video)	\N
16	xml	.xml (XML de Microsoft Word 2003)	\N
23	tar	.tar (Comprimido)	\N
9	zip	.zip (Comprimido)	\N
10	rtf	.rtf (Rich Text Format)	\N
11	dia	.dia (Diagrama)	\N
12	zargo	Argo(Diagrama)	\N
18	docx	.docx (Word > 2007)	\N
17	png	.png (Imagen)	\N
14	odt	.odt (Procesador de Texto - odf)	\N
15	ods	.ods (Hoja de Calculo - Odf)	\N
24	xlsx	Archivos de Excel 2014	\N
99	 	Otro Tipo	\N
30	msg	Mensaje de Correo.	\N
\.


--
-- Data for Name: autg_grupos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.autg_grupos (id, nombre, descripcion) FROM stdin;
19	Provicional - Errores	Gestion Documental - Arreglos
20	Radicacion Masivas	Radicacion de Masivas
22	Acuerdos	Radicacion de Acuerdos
23	Anulacion	Solicitar Anulacion
24	Abogados externos	Abogados Externos no conectados a LDAP
5	Radicación Entrada	Radicación del Sistema
26	Estadistica Maxima	Visualiza las Estadisticas de la Entidad
27	Modificacion	Modificacion de Documentos
28	Usuario Publico	Visibilidad de su usuario Publicamente
29	Admon Externo	Administrador Externo
1	Administrador	Administrador del sistema
30	Modificacion Imagenes 	Permite Modificar las imagenes cuando esta archivado el documento
31	Actas y Circulares	Radicardor Actas y Circulares
18	Estadisticas	visualizar estadisticas de un area
3	Digitalizacion	Digitaliza Documentos Entidad
4	Abogado - General	Radicacion Resoluciones - Salida - Autos
6	Envíos	Envios de Correspondencia Entidad
7	Notificación	Grupo Notificaciones Entidad
8	Radicador	tiene permisos para crear todos los tipos de radicado
9	Asesores	Radicación Resoluciones - Salida - Autos - Acuerdos
10	Informatica	Documentos Informatica
11	Archivo Fisico	Administrador de Archivo Fisico
12	reasigna	para reasignar radicado a otra dependencia
13	reasigna	reasigna radicados a otras dependencias
14	radicador interno	radicador interno
15	Enrutador	recepciona radicados de entrada
16	asociar imagen	asociar imagen a radicados
17	Impresion	permite marcar como impreso
2	Jefe de Área	Jefes de dependencia.
25	Administrador Correspondencia	Administrador Gestion Documental
21	Entrada	Entrada
32	Radicacion Respuesta Email	Radicacion Respuesta Email
\.


--
-- Data for Name: autm_membresias; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.autm_membresias (id, autg_id, autu_id) FROM stdin;
1	1	1
2	8	2
3	8	4
4	8	5
5	8	6
6	8	7
7	8	8
8	8	9
9	8	10
614	12	27
617	4	102
619	27	172
757	15	267
11	11	122
12	8	237
13	1	237
14	5	237
758	4	294
16	3	237
17	4	237
18	6	237
19	7	237
20	9	237
21	10	237
22	11	237
23	3	107
24	3	135
26	2	15
27	4	19
28	4	20
29	4	21
30	4	22
31	4	45
32	4	46
33	4	48
34	4	49
35	4	50
36	4	51
37	4	52
38	4	53
39	4	54
40	4	55
41	4	56
42	4	71
43	4	72
44	4	73
45	4	74
46	4	75
47	4	76
48	4	77
49	4	78
50	4	80
51	4	79
52	4	81
53	4	82
54	4	83
55	4	92
56	4	97
57	4	47
58	4	210
60	4	57
61	4	58
62	4	59
63	4	60
64	4	61
65	4	62
66	4	63
67	4	64
68	4	65
69	4	66
70	4	67
71	4	68
72	4	70
73	4	84
74	4	124
75	4	242
76	4	230
77	4	232
78	4	231
79	4	233
80	4	243
81	4	245
82	2	235
83	4	23
84	4	24
85	4	25
86	4	26
87	4	27
88	4	28
89	4	29
90	4	30
91	4	31
92	4	32
93	4	33
94	4	34
95	4	35
96	4	36
97	4	37
98	4	38
99	4	39
100	4	41
101	4	40
102	4	42
103	4	43
105	4	227
106	4	228
107	4	229
108	4	234
110	4	88
111	4	90
112	4	93
113	4	175
114	4	179
115	4	240
116	2	85
117	4	86
118	4	89
119	4	91
120	4	94
121	4	145
122	4	146
123	4	147
124	2	144
125	2	16
126	4	17
127	4	18
128	4	123
129	4	125
130	4	126
131	4	127
132	4	129
133	4	130
134	4	131
135	4	132
136	4	133
137	4	134
138	4	136
139	4	137
140	4	138
141	4	139
142	4	140
143	4	141
144	2	12
145	2	191
146	2	239
147	2	87
148	4	156
149	4	157
150	4	158
151	4	159
152	4	160
153	4	161
154	4	162
155	4	163
156	4	164
157	4	165
158	4	166
159	4	167
160	4	168
161	4	169
162	4	170
163	4	171
164	4	172
165	4	173
166	4	174
167	4	155
168	4	176
169	4	177
170	4	178
171	4	181
172	4	182
173	4	183
174	4	184
175	4	185
176	4	186
177	4	241
178	4	244
179	2	180
180	2	142
181	10	69
182	10	143
183	10	216
184	10	202
185	10	203
186	10	204
187	10	205
188	10	206
189	10	207
190	10	208
191	10	209
192	10	211
193	10	212
194	10	213
195	10	214
196	10	215
197	10	217
198	10	219
199	10	218
200	10	220
201	10	221
202	10	222
203	10	223
204	10	224
205	10	225
206	10	226
575	6	114
689	4	87
209	5	128
210	5	201
211	4	95
212	4	96
369	3	118
213	4	100
214	4	101
215	4	103
216	4	104
217	4	106
218	4	108
219	4	112
220	4	122
221	4	120
222	4	119
223	4	117
224	4	116
225	4	115
226	4	114
227	4	113
418	6	109
229	11	114
230	11	121
231	6	118
978	4	334
233	2	14
234	4	148
764	4	296
603	7	44
237	4	152
238	4	153
239	4	154
240	4	238
241	4	110
242	4	192
243	4	193
244	4	195
245	4	196
246	4	197
615	13	27
248	2	194
249	4	187
250	4	188
251	4	189
252	4	190
253	4	191
376	11	128
525	15	65
814	4	299
256	8	194
257	5	194
258	7	239
370	14	202
377	4	256
261	4	247
262	8	247
263	12	148
264	4	248
265	4	246
266	8	40
267	14	222
268	14	223
378	14	256
560	20	44
379	14	257
272	8	190
273	5	190
274	7	190
275	14	190
276	14	131
508	12	172
278	15	84
279	15	81
280	15	163
281	15	125
640	23	232
939	4	323
284	14	140
380	4	257
913	6	107
287	4	250
288	7	250
577	2	263
289	14	115
290	14	95
291	14	108
292	14	85
293	14	57
294	14	38
295	14	26
296	14	46
297	14	25
578	14	263
579	15	232
300	14	130
301	15	139
302	14	125
303	8	27
304	8	27
381	14	81
306	14	198
307	14	16
308	4	16
309	14	194
310	14	103
981	4	335
383	16	118
313	2	84
420	15	52
315	14	84
384	5	118
385	14	104
386	14	1
319	17	118
387	14	145
321	14	42
388	14	258
322	14	47
323	7	199
644	15	48
325	14	100
326	14	173
327	14	182
328	14	175
329	14	90
330	14	88
646	2	48
332	14	179
333	14	93
334	14	148
335	14	80
336	4	251
337	14	251
338	14	92
339	9	92
389	14	147
341	14	65
390	14	146
343	12	92
511	14	226
605	14	129
606	14	157
391	14	252
517	14	142
519	14	122
349	14	183
350	14	244
351	14	176
352	14	178
353	14	177
354	14	186
355	14	180
356	14	185
357	14	181
360	14	23
361	14	40
363	14	30
365	14	250
366	14	191
367	4	252
368	4	253
392	14	253
393	14	144
394	2	88
395	14	213
396	4	198
397	18	43
398	4	258
399	19	111
400	14	58
561	18	177
401	18	173
787	29	236
403	20	227
404	7	27
405	7	227
406	14	20
407	14	22
408	14	21
409	14	56
410	14	50
411	14	113
590	2	264
641	27	109
413	14	34
414	6	121
415	14	101
416	3	121
417	14	204
421	15	246
424	15	140
445	12	54
446	13	54
425	13	48
426	12	48
427	14	43
526	20	83
576	10	135
430	16	121
789	10	298
432	16	107
433	5	121
791	4	301
435	5	107
527	20	210
528	23	243
438	14	240
439	18	240
440	10	259
529	14	132
562	26	177
563	14	82
444	22	34
638	4	271
940	15	32
449	14	209
450	4	261
451	14	261
452	14	64
453	11	118
454	14	149
507	13	172
456	12	99
457	13	99
509	14	75
459	4	180
591	4	264
461	23	21
462	23	50
463	23	84
464	23	64
465	23	246
466	23	42
467	23	30
468	23	81
726	14	118
470	23	74
471	23	194
472	23	29
473	23	34
474	23	239
510	20	97
475	23	190
476	23	125
477	23	25
478	23	139
592	15	21
480	13	235
481	12	235
482	4	235
483	14	206
484	23	142
485	23	144
486	23	16
487	23	54
488	23	53
489	23	47
490	23	67
491	23	43
616	5	135
493	23	87
494	23	180
495	14	68
496	12	77
497	13	77
498	23	132
499	23	40
500	23	26
501	15	88
530	26	260
502	14	24
514	11	260
503	24	255
531	25	260
504	15	26
505	12	80
516	14	260
518	23	172
793	14	151
532	1	260
533	26	48
534	3	99
535	3	183
686	3	201
536	17	183
537	4	262
538	14	262
539	18	14
602	28	3
541	20	14
542	21	14
543	22	14
544	23	14
545	24	14
546	5	14
547	26	14
522	25	237
582	15	125
728	13	1
548	3	14
549	6	14
550	7	14
551	8	14
552	9	14
568	14	52
554	14	14
796	4	302
555	23	178
556	27	178
557	27	84
558	28	79
559	13	79
604	4	266
798	4	208
570	2	102
571	18	102
572	23	102
573	14	102
607	27	190
586	13	3
587	12	3
588	23	88
589	23	163
593	14	66
618	14	29
594	13	75
595	28	75
596	4	265
597	12	75
598	23	108
599	4	149
600	4	151
608	24	11
688	14	172
621	4	268
622	14	268
623	14	269
609	1	150
610	4	150
624	4	269
612	4	201
613	4	36
625	12	191
626	13	191
800	4	109
637	24	254
639	4	272
759	4	295
760	14	295
642	14	91
979	14	334
982	14	335
643	14	245
631	5	98
645	14	48
647	20	168
633	21	121
634	21	98
635	21	1
636	4	270
648	23	52
649	14	259
650	14	72
651	14	112
652	22	27
653	2	273
654	4	273
655	14	273
656	14	12
657	14	152
658	2	152
659	4	263
660	2	47
661	12	47
662	26	270
663	7	274
664	20	274
665	14	275
666	4	275
761	4	293
668	14	19
669	4	267
670	14	267
707	4	281
708	4	282
673	4	278
674	14	278
675	13	28
676	14	28
677	27	28
678	12	28
679	20	268
680	20	198
698	20	247
682	11	276
687	6	128
684	26	50
685	18	50
984	5	336
692	6	99
693	3	128
699	4	280
700	14	280
794	2	151
695	8	118
696	3	276
697	4	276
709	20	28
815	4	259
702	14	166
703	14	162
710	26	53
705	4	277
706	14	277
711	5	260
712	4	283
713	14	283
714	23	137
715	14	59
716	5	284
717	3	284
718	14	266
719	4	286
720	14	286
721	14	285
722	4	285
723	27	282
725	25	3
727	4	118
729	12	1
730	15	1
731	11	109
888	14	187
733	4	287
734	14	287
918	21	30
736	4	289
737	11	135
738	3	290
739	5	290
740	11	290
741	6	135
742	4	291
743	14	32
744	4	292
745	14	292
746	14	270
788	21	298
790	10	300
792	14	301
797	14	208
751	23	168
753	4	143
754	14	89
755	26	178
756	2	28
762	2	293
763	14	293
765	14	296
799	4	204
816	24	13
869	4	307
802	8	284
803	11	284
804	20	109
871	6	298
839	28	89
772	7	297
773	24	200
774	10	249
889	5	310
817	16	236
777	20	27
778	3	298
779	16	298
780	11	298
781	4	105
782	11	299
783	3	299
806	14	31
807	16	290
808	16	299
809	20	79
786	23	267
810	23	109
811	23	152
812	23	191
813	23	24
841	15	89
842	31	94
819	27	236
820	19	236
843	31	91
821	30	237
822	17	237
823	16	237
844	31	89
825	14	237
826	13	237
827	12	237
828	28	237
829	27	237
830	26	237
831	23	237
832	22	237
833	21	237
834	20	237
835	19	237
836	18	237
845	13	89
846	12	89
847	28	94
848	28	91
849	14	303
850	28	303
851	4	303
852	14	74
853	14	45
922	2	318
854	4	304
855	4	305
856	14	305
857	8	80
858	8	287
860	10	80
861	10	287
862	10	247
863	10	73
865	14	247
866	14	73
870	14	307
867	2	73
872	4	306
868	8	73
873	4	308
874	14	308
875	7	309
876	24	288
877	23	306
878	14	306
879	14	207
880	14	21
881	14	222
882	14	19
883	14	307
884	14	293
885	14	301
886	14	292
887	5	99
890	21	310
891	8	310
892	16	310
893	4	222
894	11	310
895	14	239
896	4	311
897	14	311
898	11	312
899	3	312
900	14	87
914	27	99
901	24	279
902	12	304
903	4	313
904	14	313
905	4	314
906	14	314
930	15	321
952	20	228
909	4	316
910	14	316
911	4	317
912	14	317
919	16	30
915	14	220
916	6	201
920	8	30
921	17	142
917	17	315
923	18	318
924	14	249
925	4	144
926	23	249
931	14	321
932	4	321
927	23	57
928	12	268
929	4	319
933	23	321
934	22	321
935	31	321
953	14	228
954	4	325
937	5	246
938	5	21
955	14	325
943	14	36
956	4	326
980	14	41
946	14	117
947	14	315
948	5	65
949	4	322
950	14	322
951	4	324
957	14	326
958	26	324
959	3	327
960	15	43
961	14	49
962	4	200
963	14	200
964	4	329
965	4	328
966	14	328
967	3	331
968	3	330
969	8	331
970	8	330
971	2	300
972	14	300
973	4	98
974	14	98
975	11	332
976	21	327
977	15	239
983	3	336
985	3	337
986	5	337
987	21	336
988	4	338
989	2	338
990	14	338
991	7	317
992	23	245
993	23	293
994	6	293
995	6	315
996	14	323
997	30	1
998	3	1
999	32	1
\.


--
-- Data for Name: autp_permisos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.autp_permisos (id, autg_id, nombre, descripcion, crud, dependencia) FROM stdin;
7	0	PERM_RADI	RADICACION	1	
1	0	USUA_ADM_PLANTILLA	USO DE PLANTILLAS	1	
32	\N	CODI_NIVEL	CODIGO DEL NIVEL	3	\N
28	0	PERM_VOBO	PERMISO VISTO BUENO	1	
18	0	USUARIO_REASIGNAR	REASIGNAR	1	
41	\N	USUA_PRAD_TP1	RADICA SALIDA	3	\N
47	\N	USUA_SCAN	PERMISO DIGITALIZACION 	2	\N
13	0	USUA_PERM_NUMERA_RES	NUMERACION	3	
48	\N	USUA_SCAN	PERMISO DIGITALIZACION 	2	\N
33	\N	USUA_PERM_OWNCLOUD	DIGITALIZADOR_OWNCLOUD	2	\N
10	0	PERM_BORRAR_ANEXO	BORRAR ANEXO	2	
31	\N	USUA_ADMIN_ARCHIVO	ARCHIVO	3	\N
27	0	PERM_ARCHI	PERMISO ARCHIVO	5	
5	0	USUA_ADMIN_ARCHIVO	ARCHIVO EDITAR	4	
4	0	SGD_PERM_ESTADISTICA	PERMISOS ESTADISTICA	2	
12	0	DEPE_CODI_PADRE	CODIGO PADRE	1	
11	0	USUA_MASIVA	RADICACION MASIVA	1	
45	\N	USUARIO_PUBLICO	HABILITA LA VISIBILIDAD DE UN USUARIO	1	\N
30	0	USUA_PERM_ADMINFLUJOS	FLUJOS	1	
20	0	USUA_PERM_INTERGAPPS	DESCRIPCION	1	
19	0	USUA_PERM_SANCIONADOS	DESCRIPCION	1	
38	\N	USUA_PERM_TRD	TRD	1	\N
21	0	USUA_PERM_FIRMA	FIRMA	1	
29	0	USUA_PERM_RESPUESTA	DESCRIPCION	1	
14	0	USUA_PERM_DEV	DESCRIPCION	2	
24	0	USUA_PERM_EXPEDIENTE	EXPEDIENTE	3	
40	\N	USUA_PRAD_REPRAD	PERMISO REPORTE DE RADICACIÓN DE ENTRADA	1	\N
26	0	USUA_PERM_RADFAX	DESCRIPCION	3	
50	\N	USUA_PRAD_TP4	PERMISO RADICACIÒN AUTOS	3	\N
49	\N	USUA_PERM_STICKER	PERMISO DE STICKER	1	\N
22	0	USUA_PERM_PRESTAMO	DESCRIPCION	1	
25	0	USUA_PERM_RADEMAIL	DESCRIPCION permite la radicacion de correos electronicos	3	
37	\N	USUA_NUEVO	EL USUARIO QUE TENGA ESTE PERMISO, SE LE RESETEA LA CONTRASEÑA	1	\N
56	\N	usuaPermRadEmail	PERMISO DE RADICACIÓN POR CORREO ELECTRÓNICO	1	\N
34	\N	SGD_PERM_ESTADISTICA	ESTADISTICA NIVEL 2	2	\N
17	0	USUA_PERM_MODIFICA	MODIFICACION	1	
54	\N	USUA_PRAD_TP7	PERMISO RADICACIÒN CIRCULARES	3	\N
46	\N	USUARIO_REASIGNA	USUARIO REASIGNA	1	\N
55	\N	USUA_PRAD_TP7	PERMISO RADICACIÒN CIRCULARES	3	\N
15	0	SGD_PANU_CODI	ANULACION	1	
44	\N	USUARIO_PRUEBA	PRUEBA	3	\N
23	0	USUA_PERM_NOTIFICA	DESCRIPCION	1	
9	0	PERM_TIPIF_ANEXO	ANEXO	3	
42	\N	USUA_PRAD_TP2	PERMISO RADICACIÓN DE ENTRADA	3	\N
35	\N	SGD_PERM_ESTADISTICA	ESTADISTICA NIVEL MAXIMO	3	\N
39	\N	USUA_PERM_TRD	TRD	3	\N
8	0	USUA_PERM_IMPRESION	PERMISO PARA MARCAR COMO IMPRESO	3	
3	0	USUA_PERM_TRD	ADMINISTRADOR TRD	2	
59	0	USUA_PERM_ENRUTADOR	USUARIO ENRUTADOR DE LA DEPENDENCIA	1	\N
58	\N	USUA_PRAD_TP8	PERMISO RADICACION ACTAS	3	\N
2	0	USUA_ADMIN_ARCHIVO	ADMINISTRADOR ARCHIVO	5	
6	0	USUA_ADMIN_SISTEMA	ADMINISTRADOR DEL SISTEMA	5	
57	\N	USUA_PRAD_TP8	PERMISO RADICACION ACTAS	3	\N
16	0	USUA_PERM_ENVIOS	ENVIOS	1	
51	\N	USUA_PRAD_TP5	RADICACIÒN RESOLUCION	1	\N
52	\N	USUA_PRAD_TP5	RADICACIÒN RESOLUCION	3	\N
53	\N	USUA_PRAD_TP6	RADICACIÒN ACUERDOS	3	\N
43	\N	USUA_PRAD_TP3	RADICAR INTERNO	3	\N
36	\N	USUA_AUTH_LDAP	AUTENTICA POR LDAP	0	\N
60	\N	PERM_SENDMAIL_RR	PERMISO ENVIO MAIL RESPUESTA RAPIDA	1	\N
61	0	USUA_PERM_ROOT	PERMISO MAXIMO PARA VISUALIZACION DE DOCUMENTOS	1	
69	0	PERM_DESCARGAEXP	Permiso que permite la descarga de los radicados y anexos cuyo formato sea diferente a docx odt doc	1	
\.


--
-- Data for Name: autr_restric_grupo; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.autr_restric_grupo (id, autg_id, autp_id) FROM stdin;
1041	5	36
1469	24	5
1470	25	5
1471	1	5
1472	30	5
1477	25	57
1042	2	36
1043	3	36
1478	29	57
1217	25	49
1044	4	36
1045	6	36
1046	7	36
1218	5	49
1219	29	49
1220	1	49
1221	11	49
1226	25	25
1227	5	25
1228	29	25
1229	1	25
1234	25	37
1047	8	36
1235	29	37
1236	1	37
1479	1	57
1480	31	57
1048	9	36
1049	10	36
1050	11	36
1073	24	41
1372	19	28
1373	24	28
1374	25	28
1375	29	28
1376	1	28
1377	2	28
1378	4	28
1072	15	59
1249	25	17
1250	27	17
1251	29	17
1252	1	17
1258	25	46
1074	25	41
1259	29	46
1260	1	46
1261	2	46
1075	29	41
1076	1	41
1077	2	41
1078	4	41
1262	12	46
1263	13	46
1079	7	41
1080	9	41
1264	15	46
1081	10	41
1090	18	4
1091	25	4
1092	29	4
1093	1	4
1094	2	4
1270	23	15
1271	25	15
1272	29	15
1273	1	15
1274	2	15
1379	7	28
1278	25	23
1279	29	23
1280	1	23
1380	9	28
1381	10	28
1281	7	23
1098	20	11
1099	25	11
1100	29	11
1412	25	47
1413	5	47
1101	1	11
1102	7	11
1105	25	30
1106	29	30
1107	1	30
1112	25	19
1113	29	19
1114	1	19
1118	19	21
1119	24	21
1120	25	21
1121	29	21
1414	29	47
1415	1	47
1416	30	47
1417	3	47
1122	1	21
1418	8	47
1419	16	47
1123	2	21
1124	4	21
1314	25	35
1315	29	35
1316	1	35
1125	7	21
1126	9	21
1127	10	21
1138	25	14
1139	29	14
1140	1	14
1168	25	26
1169	5	26
1170	29	26
1171	1	26
1178	25	3
1426	25	33
1427	29	33
1428	1	33
1429	30	33
1430	3	33
1431	8	33
1179	29	3
1180	1	3
1184	25	6
1432	11	33
1439	2	10
1440	4	10
1441	7	10
1442	9	10
1443	25	31
1444	29	31
1445	1	31
1446	30	31
1447	11	31
1452	19	27
1453	24	27
1454	25	27
1455	29	27
1185	29	6
1186	1	6
1191	24	51
1192	25	51
1193	29	51
1194	1	51
1195	4	51
1196	9	51
1203	22	53
1204	25	53
1205	29	53
1206	1	53
1207	9	53
1456	1	27
1457	2	27
1458	3	27
1459	4	27
1460	6	27
1461	7	27
1462	9	27
1463	10	27
1464	11	27
1465	25	2
1466	29	2
1467	1	2
1468	30	2
1382	24	18
1383	25	18
1384	29	18
1222	25	22
1223	29	22
1224	1	22
1225	11	22
1473	25	58
1474	29	58
1385	1	18
1386	2	18
1387	12	18
1388	13	18
1475	1	58
1476	31	58
1082	24	13
1083	25	13
1084	29	13
1085	1	13
1086	2	13
1087	4	13
1237	25	56
1238	29	56
1239	8	56
1245	25	34
1246	26	34
1088	7	13
1089	9	13
1095	25	12
1096	29	12
1247	29	34
1248	1	34
1253	22	54
1254	25	54
1255	29	54
1256	1	54
1257	9	54
1265	22	55
1097	1	12
1103	28	45
1104	29	45
1108	25	20
1266	25	55
1267	29	55
1268	1	55
1269	9	55
1275	25	44
1109	29	20
1276	29	44
1110	1	20
1111	7	20
1115	25	38
1116	29	38
1277	1	44
1282	19	9
1283	24	9
1284	25	9
1285	29	9
1286	1	9
1287	2	9
1288	4	9
1289	7	9
1290	8	9
1291	9	9
1292	10	9
1117	1	38
1128	19	29
1309	21	42
1310	25	42
1311	5	42
1312	29	42
1313	16	42
1317	25	39
1318	29	39
1319	1	39
1329	19	8
1330	24	8
1331	25	8
1129	24	29
1130	25	29
1332	29	8
1333	1	8
1131	29	29
1132	1	29
1133	2	29
1134	4	29
1135	7	29
1136	9	29
1137	10	29
1141	19	24
1142	24	24
1143	25	24
1144	29	24
1145	1	24
1146	2	24
1147	4	24
1148	7	24
1149	9	24
1150	11	24
1164	25	40
1165	29	40
1166	1	40
1167	2	40
1172	24	50
1173	25	50
1174	29	50
1175	1	50
1176	4	50
1177	9	50
1187	25	16
1188	29	16
1189	1	16
1190	6	16
1197	24	52
1198	25	52
1199	29	52
1200	1	52
1420	25	48
1421	5	48
1422	29	48
1423	30	48
1424	8	48
1425	16	48
1433	19	10
1434	24	10
1435	25	10
1436	29	10
1437	1	10
1438	30	10
1201	4	52
1202	9	52
1214	25	43
1215	29	43
1216	14	43
1334	2	8
1335	4	8
1336	7	8
1337	9	8
1338	10	8
1339	17	8
1340	19	7
1341	24	7
1342	25	7
1343	5	7
1344	29	7
1345	1	7
1346	2	7
1347	4	7
1348	7	7
1349	9	7
1350	10	7
1351	19	1
1352	24	1
1353	25	1
1354	29	1
1355	1	1
1356	2	1
1357	4	1
1358	7	1
1359	9	1
1360	10	1
1361	19	32
1362	24	32
1363	25	32
1364	5	32
1365	29	32
1366	1	32
1367	2	32
1368	4	32
1369	7	32
1370	9	32
1371	10	32
1481	32	60
\.


--
-- Data for Name: autu_usuarios; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.autu_usuarios (id, nombres, apellidos, correo, contrasena, usuario, estado) FROM stdin;
\.


--
-- Data for Name: bodega_empresas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.bodega_empresas (nombre_de_la_empresa, nuir, nit_de_la_empresa, sigla_de_la_empresa, direccion, codigo_del_departamento, codigo_del_municipio, telefono_1, telefono_2, email, nombre_rep_legal, cargo_rep_legal, identificador_empresa, are_esp_secue, id_cont, id_pais, activa, flag_rups) FROM stdin;
ALCALDIA MUNICIPIO DE LETICIA	\N	8907020342	\N	NO REGISTRA	11	1	0	\N	alcaldia@leticia-amazonas.gov.co	\N	\N	2	\N	1	170	1	\N
HOSPITAL SAN VICENTE DE PAUL DE PALMIRA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	1	\N	1	170	1	\N
BANCO DE LA REPUBLICA	\N	\N	\N	Carrera 7  14 - 78	11	1	0	\N	\N	\N	\N	4	\N	1	170	1	\N
OLEODUCTO DE COLOMBIA S.A.	\N	8000687138	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	6	\N	1	170	1	\N
HOTEL TEQUENDAMA INTERCONTINENTAL	\N	\N	\N	Carrera 10 No. 26-21	11	1	0	\N	\N	\N	\N	7	\N	1	170	1	\N
IMPRENTA NACIONAL DE COLOMBIA  -INC 	\N	830001113	\N	Diagonal 22 bis No 67-70	11	1	0	\N	\N	\N	\N	8	\N	1	170	1	\N
FONDO PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA  -FPS 	\N	800112806	\N	Calle 13 No 18-24 Estacion de la Sabana de Bogota D.C.	11	1	0	\N	quejasyreclamos@fps.gov.co	\N	\N	9	\N	1	170	1	\N
DEPARTAMENTO ADMINISTRATIVO DE LA PRESIDENCIA DE LA REPUBLICA  -DAPRE 	\N	899999083	\N	calle 7No  6 - 54	11	1	0	\N	\N	\N	\N	11	\N	1	170	1	\N
UNIVERSIDAD POPULAR DEL CESAR	\N	8923002856	\N	Balneario Hurtado 	11	1	0	\N	\N	\N	\N	15	\N	1	170	1	\N
MINISTERIO DE AMBIENTE Y DESARROLLO SOSTENIBLE  -MINAMBIENTE 	\N	830115395	\N	CALLE 37  N 8-40 BOGOTa D.C.	11	1	3323434	\N	NRINCON@MINAMBIENTE.GOV.CO	\N	\N	17	\N	1	170	1	\N
GOBERNACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	3	\N	1	170	1	\N
LEASING DE OCCIDENTE S.A. C.F.C.	\N	\N	\N	Carrera 13 No 93 - 12 piso 2	11	1	0	\N	\N	\N	\N	5	\N	1	170	1	\N
BANCO CAFETERO	\N	8600029621	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	10	\N	1	170	1	\N
ADMINISTRACION POSTAL NACIONAL E INTERNACIONAL	\N	8999994865	\N	Edificio Murillo Toro Cra. 8a y 7a. entre calles 12A y 13 - Bogota D. C.	11	1	0	\N	\N	\N	\N	12	\N	1	170	1	\N
EMPRESA DE DESARROLLO URBANO DE BOLIVAR	\N	8904811231	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	13	\N	1	170	1	\N
CENTRAL HIDROELECTRICA LA MIEL S.A. E.S.P. - NORCASIA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	14	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MONGUA	\N	8904806433	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	23	\N	1	170	1	\N
EMPRESA DE TRANSPORTE MASIVO DEL VALLE DE ABURRA LTDA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	18	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PAJARITO	\N	8918012401	\N	NO REGISTRA	11	1	0	\N	alcaldia@pajarito-boyaca.gov.co	\N	\N	24	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SATIVANORTE	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@sativanorte-boyaca.gov.co	\N	\N	25	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE POLONUEVO	\N	8000200324	\N	NO REGISTRA	11	1	0	\N	alcaldia@polonuevo-atlantico.gov.co	\N	\N	21	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE REGIDOR	\N	8001036613	\N	NO REGISTRA	11	1	0	\N	alcaldia@regidor-bolivar.gov.co	\N	\N	22	\N	1	170	1	\N
INSTITUTO TECNOLOGICO DEL PUTUMAYO	\N	8002479401	\N	Sede principal Aire Libre Barrio La Esmeralda	11	1	0	\N	\N	\N	\N	16	\N	1	170	1	\N
E.S.E. HOSPITAL SAN CARLOS CANASGORDAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	19	\N	1	170	1	\N
EMPRESA DE OBRAS SANITARIAS DEL LIBANO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	26	\N	1	170	1	\N
E.S.E. HOSPITAL VENANCIO DIAZ DIAZ DE SABANETA - MEDELLIN	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	20	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ROVIRA	\N	8000959834	\N	NO REGISTRA	11	1	0	\N	alcaldia@rovira-tolima.gov.co	\N	\N	27	\N	1	170	1	\N
BENEFICENCIA DEL VALLE	\N	8903990270	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	28	\N	1	170	1	\N
EMPRESA DE SERVICIOS VARIOS DE CALI	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	29	\N	1	170	1	\N
HOSPITAL INFANTIL CLUB NOEL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	30	\N	1	170	1	\N
EMPRESAS PUBLICAS MUNICIPALES DE CARTAGO	\N	\N	\N	Edificio de la Gobernacion	11	1	0	\N	\N	\N	\N	31	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE JAMUNDI	\N	8915010479	\N	NO REGISTRA	11	1	0	\N	alcaldia@jamundi-valle.gov.co	\N	\N	32	\N	1	170	1	\N
EMPRESAS PUBLICAS MUNICIPALES DE PALMIRA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	33	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VERSALLES	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@versalles-valle.gov.co	\N	\N	34	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CRAVO NORTE	\N	8918579202	\N	NO REGISTRA	11	1	0	\N	alcaldia@cravonorte-arauca.gov.co	\N	\N	35	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE HATO COROZAL	\N	8902104382	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	36	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TRINIDAD	\N	8000318745	\N	NO REGISTRA	11	1	0	\N	alcaldia@trinidad-casanare.gov.co	\N	\N	37	\N	1	170	1	\N
CAJA DE RETIRO DE LAS FUERZAS MILITARES  -CREMIL 	\N	899999118	\N	Carrera 10a No. 27 - 27    Centro Internacional	11	1	0	\N	\N	\N	\N	41	\N	1	170	1	\N
INSTITUTO COLOMBIANO DE ENERGIA ELECTRICA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	42	\N	1	170	1	\N
Z ENTIDAD DE PRUEBA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	55	\N	1	170	1	\N
MINISTERIO DE CULTURA  -MINCULTURA 	\N	830034348	\N	Calle No. 6-97 	11	1	0	\N	\N	\N	\N	56	\N	1	170	1	\N
UNIVERSIDAD MILITAR NUEVA GRANADA	\N	8002253408	\N	Carrera. 11No 101-80	11	1	0	\N	wmaster@umng.edu.co	\N	\N	58	\N	1	170	1	\N
DIRECCION OPERATIVA PARA LA DEFENSA DE LA LIBERTAD PERSONAL	\N	\N	\N	Cra 7 No 31- 10 Piso 7	11	1	0	\N	\N	\N	\N	59	\N	1	170	1	\N
DIRECCION GENERAL MARITIMA	\N	8300279041	\N	Tr. 41 no. 27 - 50 CAN	11	1	0	\N	mvelandia@dimar.mil.co	\N	\N	60	\N	1	170	1	\N
SUPERINTENDENCIA DE NOTARIADO Y REGISTRO  -SUPERNOTARIADO 	\N	899999007	\N	Calle 26 No 13 - 49  Interior 201	11	1	0	\N	\N	\N	\N	61	\N	1	170	1	\N
FONDO ROTATORIO DE LA ARMADA NACIONAL	\N	\N	\N	Carrera 50No 15 - 35	11	1	0	\N	\N	\N	\N	62	\N	1	170	1	\N
CLUB MILITAR  -CLUB MILITAR 	\N	860016951	\N	Carrera 50No 15 - 80	11	1	2905077	\N	direccion@clubmilitar.gov.co	\N	\N	66	\N	1	170	1	\N
DIRECCION DE SANIDAD DE LA FUERZA AEREA COLOMBIANA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	68	\N	1	170	1	\N
 ASOCIACION MUNICIPAL DE ORIENTE	\N	8002011890	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	76	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CAPARRAPI	\N	8002535261	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	77	\N	1	170	1	\N
 ASOCIACION DE MUNICIPIOS DEL ORIENTE	\N	8002011890	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	78	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE FOSCA	\N	8921700083	\N	NO REGISTRA	11	1	0	\N	alcaldia@fosca-cundinamarca.gov.co	\N	\N	79	\N	1	170	1	\N
 INSTITUTO DE VIVIENDA DE INTERES SOCIAL Y REFORMA URBANA DE FUSAGASUGA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	80	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LA CALERA	\N	8902106174	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	81	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MANTA	\N	8908010537	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	82	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PUERTO SALGAR	\N	8001027989	\N	NO REGISTRA	11	1	0	\N	alcaldia@puertosalgar-cundinamarca.gov.co	\N	\N	83	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN BERNARDO	\N	8922800544	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanbernardo-cundinamarca.gov.co	\N	\N	84	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TENA	\N	8911801270	\N	NO REGISTRA	11	1	0	\N	alcaldia@tena-cundinamarca.gov.co	\N	\N	85	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE VILLETA	\N	8911801872	\N	NO REGISTRA	11	1	0	\N	alcaldia@villeta-cundinamarca.gov.co	\N	\N	86	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE QUIBDO	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@quibdo-choco.gov.co	\N	\N	87	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ATRATO	\N	8999993186	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	88	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE UNGUIA	\N	8999993881	\N	NO REGISTRA	11	1	0	\N	alcaldia@unguia-choco.gov.co	\N	\N	89	\N	1	170	1	\N
 INSTITUTO DE TRANSITO Y TRANSPORTE DEL HUILA	\N	\N	\N	Calle 8a Carrera 4a	11	1	0	\N	\N	\N	\N	90	\N	1	170	1	\N
 EMPRESAS PUBLICAS DE NEIVA	\N	8911800108	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	91	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE OPORAPA	\N	8902081485	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	92	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN AGUSTIN	\N	8922800551	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanagustin-huila.gov.co	\N	\N	93	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE HATONUEVO	\N	8000126380	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	94	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LOS SANTOS	\N	8000441135	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	214	\N	1	170	1	\N
EMPRESA DE SERVICIOS PUBLICOS DOMICILIARIOS DE PUERTO LEGUIZAMO - EMPULEG	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	39	\N	1	170	1	\N
GOBERNACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	40	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SOTAQUIRA	\N	8000159097	\N	NO REGISTRA	11	1	0	\N	alcaldia@sotaquira-boyaca.gov.co	\N	\N	44	\N	1	170	1	\N
INSTITUTO DE FINANCIAMIENTO PROMOCION Y DESARROLLO DE CALDAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	45	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE FUQUENE	\N	8999994335	\N	NO REGISTRA	11	1	0	\N	alcaldia@fuquene-cundinamarca.gov.co	\N	\N	46	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LA VEGA	\N	8001284281	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	47	\N	1	170	1	\N
GOBERNACION	\N	8914800857	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	48	\N	1	170	1	\N
CONCEJO MUNICIPIO DE DON MATIAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	49	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PUEBLO RICO	\N	8000967667	\N	NO REGISTRA	11	1	0	\N	alcaldia@pueblorico-risaralda.gov.co	\N	\N	50	\N	1	170	1	\N
EMPRESA DE ACUEDUCTO Y ALCANTARILLADO DE SINCELEJO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	51	\N	1	170	1	\N
FABRICA DE LICORES DEL TOLIMA	\N	8907047632	\N	Carrera 3a Calle 10a Piso 7	11	1	0	\N	\N	\N	\N	52	\N	1	170	1	\N
EMPRESAS PUBLICAS MUNICIPALES DE BUENAVENTURA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	53	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CACOTA	\N	8905017766	\N	NO REGISTRA	11	1	0	\N	alcaldia@cacota-nortedesantander.gov.co	\N	\N	54	\N	1	170	1	\N
CORPORACION DE LA INDUSTRIA AERONAUTICA COLOMBIANA	\N	8999992781	\N	Aeropuerto Eldorado - Entrada 1 Interior 2 	11	1	0	\N	ciacs.a@etb.net.co	\N	\N	57	\N	1	170	1	\N
FONDO DE COFINANCIACION PARA LA INVERSION RURAL	\N	\N	\N	Cra 10 No. 27-27 Edificio Bachue Piso 7 	11	1	0	\N	\N	\N	\N	63	\N	1	170	1	\N
BANCO DE COMERCIO EXTERIOR DE COLOMBIA S.A.	\N	8001499236	\N	Cra 28 No. 13A - 15  Piso 40	11	1	0	\N	\N	\N	\N	64	\N	1	170	1	\N
PROGRAMA COMPARTEL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	65	\N	1	170	1	\N
FONDO DE DESARROLLO DE LA EDUCACION SUPERIOR	\N	8300189573	\N	Calle 57 No 8-69 Interior 32 Edificio La Previsora	11	1	0	\N	fodesep@fodesep.gov.co	\N	\N	67	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE HERVEO	\N	8000052929	\N	NO REGISTRA	11	1	0	\N	alcaldia@herveo-tolima.gov.co	\N	\N	69	\N	1	170	1	\N
TERMINAL DE TRANSPORTES DE AGUACHICA S.A.	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	70	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE RIO DE ORO	\N	8000991274	\N	NO REGISTRA	11	1	0	\N	alcaldia@riodeoro-cesar.gov.co	\N	\N	71	\N	1	170	1	\N
GOBERNACION	\N	8001039356	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	72	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CANALETE	\N	8000967406	\N	CARRERA 3  N 3-76 PALACIO MUNICIPAL  CANALETE - CoRDOBA	11	1	7601016-3142219258	\N	alcaldia@canalete-cordoba.gov.co - saludcanalete@hotmail.com	\N	\N	73	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN CARLOS	\N	8000099260	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	74	\N	1	170	1	\N
LOTERIA DE CUNDINAMARCA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	75	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE VILLANUEVA	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@villanueva-guajira.gov.co	\N	\N	95	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PEDRAZA	\N	8918550152	\N	NO REGISTRA	11	1	0	\N	alcaldia@pedraza-magdalena.gov.co	\N	\N	96	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SALAMINA	\N	8911801801	\N	NO REGISTRA	11	1	0	\N	alcaldia@salamina-magdalena.gov.co	\N	\N	97	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ACACIAS	\N	8909812511	\N	NO REGISTRA	11	1	0	\N	alcaldia@acacias-meta.gov.co	\N	\N	98	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE GUAMAL	\N	8000836727	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	99	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN JUAN DE ARAMA	\N	8000226188	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanjuandearama-meta.gov.co	\N	\N	100	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE IMUES	\N	8000990925	\N	NO REGISTRA	11	1	0	\N	alcaldia@imues-narino.gov.co	\N	\N	101	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE OSPINA	\N	8907009426	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	102	\N	1	170	1	\N
 EMPRESA DE OBRAS SANITARIAS DE NORTE DE SANTANDER	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	103	\N	1	170	1	\N
 EMPRESA DE OBRAS SANITARIAS DE PAMPLONA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	104	\N	1	170	1	\N
 INSTITUTO FINANCIERO PARA EL DESARROLLO DE RISARALDA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	105	\N	1	170	1	\N
 GOBERNACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	webmaster@gobernaciondesantander.gov.co	\N	\N	106	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CERRITO	\N	8918578053	\N	NO REGISTRA	11	1	0	\N	alcaldia@cerrito-santander.gov.co	\N	\N	107	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CONFINES	\N	8916800579	\N	NO REGISTRA	11	1	0	\N	alcaldia@confines-santander.gov.co	\N	\N	108	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN JOSE DE MIRANDA	\N	8000957820	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	109	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN JUAN DE BETULIA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	110	\N	1	170	1	\N
 EMPRESA DE OBRAS SANITARIAS DEL TOLIMA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	111	\N	1	170	1	\N
 INSTITUTO IBAGUERENO DE LA REFORMA URBANA Y VIVIENDA POPULAR DE INTERES SOCIAL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	112	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ALPUJARRA	\N	8918012813	\N	NO REGISTRA	11	1	0	\N	alcaldia@alpujarra-tolima.gov.co	\N	\N	113	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ALVARADO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	114	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE AMBALEMA	\N	8909815180	\N	NO REGISTRA	11	1	0	\N	alcaldia@ambalema-tolima.gov.co	\N	\N	115	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ATACO	\N	8923015411	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	116	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CARMEN DE APICALA	\N	8909853168	\N	NO REGISTRA	11	1	0	\N	alcaldia@carmendeapicala-tolima.gov.co	\N	\N	117	\N	1	170	1	\N
 EMPRESA DE SERVICIOS PUBLICOS DE CHAPARRAL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	118	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE COELLO	\N	8909846340	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	119	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CUNDAY	\N	8000990728	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	120	\N	1	170	1	\N
 CAJA DE PREVISION SOCIAL MUNICIPAL DE IBAGUE	\N	\N	\N	Cra 5 calle 60 invias	11	1	0	\N	\N	\N	\N	121	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE FLANDES	\N	8918562880	\N	NO REGISTRA	11	1	0	\N	alcaldia@flandes-tolima.gov.co	\N	\N	122	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE EL GUAMO	\N	8902054391	\N	NO REGISTRA	11	1	0	\N	alcaldia@elguamo-tolima.gov.co	\N	\N	123	\N	1	170	1	\N
 EMPRESA DE SERVICIOS PUBLICOS DE ACUEDUCTO  ALCANTARILLADO Y ASEO DEL GUAMO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	124	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE HONDA	\N	8911800193	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	125	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LERIDA	\N	8999993305	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	126	\N	1	170	1	\N
 EMPRESA DE ACUEDUCTO  ALCANTARILLADO Y ASEO DEL LIBANO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	127	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE NATAGAIMA	\N	8911028440	\N	NO REGISTRA	11	1	0	\N	alcaldia@natagaima-tolima.gov.co	\N	\N	128	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ORTEGA	\N	8920993924	\N	NO REGISTRA	11	1	0	\N	alcaldia@ortega-tolima.gov.co	\N	\N	129	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PRADO	\N	8000372324	\N	NO REGISTRA	11	1	0	\N	alcaldia@prado-tolima.gov.co	\N	\N	130	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PURIFICACION	\N	8915007210	\N	NO REGISTRA	11	1	0	\N	alcaldia@purificacion-tolima.gov.co	\N	\N	131	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE RIOBLANCO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	132	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN ANTONIO	\N	8000967818	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanantonio-tolima.gov.co	\N	\N	133	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN LUIS	\N	8000991425	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	134	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SUAREZ	\N	8901161590	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	135	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE VENADILLO	\N	8902056776	\N	NO REGISTRA	11	1	0	\N	alcaldia@venadillo-tolima.gov.co	\N	\N	136	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE VILLAHERMOSA	\N	8000542490	\N	NO REGISTRA	11	1	0	\N	alcaldia@villahermosa-tolima.gov.co	\N	\N	137	\N	1	170	1	\N
 GOBERNACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	138	\N	1	170	1	\N
 UNIVERSIDAD DEL VALLE	\N	8903990106	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	139	\N	1	170	1	\N
 INSTITUTO MUNICIPAL DE LA REFORMA URBANA Y VIVIENDA SOCIAL DE INTERES DE CALI	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	140	\N	1	170	1	\N
 EMPRESA REGIONAL DE TELECOMUNICACIONES VALLE DEL CAUCA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	141	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PEREIRA	\N	\N	\N	EDIFICIO DE LA LOTERIA DE RISARALDA CALLE 19 No 7-53 PISO 8 OFICINA 802	11	1	3359604 3359615	\N	jhernandez@pereira.gov.co	\N	\N	142	\N	1	170	1	\N
 AEROPUERTO INTERNACIONAL MATECANA DE PEREIRA	\N	8914800144	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	143	\N	1	170	1	\N
 EMPRESA DE ASEO DE PEREIRA S.A.	\N	8160020174	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	144	\N	1	170	1	\N
 HOSPITAL UNIVERSITARIO SAN JORGE DE PEREIRA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	145	\N	1	170	1	\N
 E.S.E. HOSPITAL SAN VICENTE DE PAUL - APIA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	146	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE COYAIMA	\N	8907020231	\N	NO REGISTRA	11	1	0	\N	alcaldia@coyaima-tolima.gov.co	\N	\N	147	\N	1	170	1	\N
 HOSPITAL SAN RAFAEL DE EL ESPINAL	\N	8907010330	\N	CALLE 4No 6 - 24	11	1	2482818	\N	hsrpersonal@yahoo.com - nelars01@hotmail.com	\N	\N	148	\N	1	170	1	\N
SUPERINTENDENCIA DE INDUSTRIA Y COMERCIO  -SUPERINDUSTRIA 	\N	800176089	\N	Sede Centro  Carrera 13 No. 27-00  Pisos 25 y 10 - Sede CAN Tr 40A No. 38-50	11	1	0	\N	info@sic.gov.co	\N	\N	149	\N	1	170	1	\N
ELECTRIFICADORA DEL ATLANTICO	\N	8901004721	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	150	\N	1	170	1	\N
CENTRALES ELECTRICAS DE NARINO	\N	8912002008	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	151	\N	1	170	1	\N
ELECTRIFICADORA DEL MAGDALENA	\N	8917800965	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	152	\N	1	170	1	\N
MINISTERIO DE EDUCACION NACIONAL  -MEN 	\N	899999001	\N	Avenida el Dorado - Centro Administrativo Nacional CAN	11	1	0	\N	\N	\N	\N	153	\N	1	170	1	\N
 UNIVERSIDAD DE CORDOBA - MONTERIA	\N	8910800313	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	154	\N	1	170	1	\N
 COMISION DE REGULACION DE COMUNICACIONES	\N	\N	\N	Carrera 11 No 93-46 piso 2	11	1	0	\N	\N	\N	\N	155	\N	1	170	1	\N
 EMPRESA DE TELECOMUNICACIONES DE CARTAGENA DE INDIAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	156	\N	1	170	1	\N
DEFENSORIA DEL PUEBLO  -DEFENSORIA 	\N	800186061	\N	Calle 55 No 10 - 32	11	1	0	\N	\N	\N	\N	157	\N	1	170	1	\N
CORTE CONSTITUCIONAL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	158	\N	1	170	1	\N
MINISTERIO DE COMERCIO  INDUSTRIA Y TURISMO  -MINCIT 	\N	830115297	\N	Calle 28 no. 13A - 15 Edificio Centro de Comercio Internacional	11	1	0	\N	\N	\N	\N	159	\N	1	170	1	\N
 CORPORACION PARA EL DESARROLLO SOSTENIBLE DEL CHOCO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	160	\N	1	170	1	\N
INSTITUTO COLOMBIANO DE ANTROPOLOGIA E HISTORIA  -ICANH 	\N	830067892	\N	Calle 12No 2 - 41	11	1	0	\N	\N	\N	\N	161	\N	1	170	1	\N
 BENEFICENCIA DE ANTIOQUIA	\N	8909800581	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	162	\N	1	170	1	\N
 EMPRESA MUNICIPAL DE MERCADEO EMMA MUNICIPIO DE APARTADO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	163	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ABRIAQUI	\N	8905046120	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	164	\N	1	170	1	\N
 HOSPITAL SAN RAFAEL DE ANDES	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	165	\N	1	170	1	\N
 EMPRESAS PUBLICAS DE APARTADO E.S.P.	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	166	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE BURITICA	\N	8919003531	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	167	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CAMPAMENTO	\N	8915012927	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	168	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE EL BAGRE	\N	8001005184	\N	NO REGISTRA	11	1	0	\N	alcaldia@elbagre-antioquia.gov.co	\N	\N	169	\N	1	170	1	\N
 EMPRESA DE SERVICIOS PUBLICOS DE GUARNE E.S.P.	\N	8110130600	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	170	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MARINILLA	\N	8000954668	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	171	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SABANETA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	172	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN JUAN DE URABA	\N	8999994224	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	173	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TOLEDO	\N	8000622550	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	174	\N	1	170	1	\N
 EMPRESAS PUBLICAS DE YARUMAL	\N	8001829631	\N	Calle 42 B 52-16	11	1	0	\N	\N	\N	\N	175	\N	1	170	1	\N
 SOCIEDAD DE ACUEDUCTO  ALCANTARILLADO Y ASEO DE BARRANQUILLA S.A.	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	176	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PIOJO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	177	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SABANALARGA	\N	8901159821	\N	NO REGISTRA	11	1	0	\N	alcaldia@sabanalarga-atlantico.gov.co	\N	\N	178	\N	1	170	1	\N
 E.S.E. HOSPITAL SAN PABLO DE CARTAGENA DE INDIAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	179	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN FERNANDO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	180	\N	1	170	1	\N
 GOBERNACION	\N	8918004981	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	181	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MONIQUIRA	\N	8918565552	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	182	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN LUIS DE GACENO	\N	8909843760	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanluisdegaceno-boyaca.gov.co	\N	\N	183	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PIAMONTE	\N	8918564640	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	184	\N	1	170	1	\N
 UNIDAD DE AGUA DEPARTAMENTAL DEL CAUCA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	185	\N	1	170	1	\N
 EMPRESAS MUNICIPALES DE SANTANDER DE QUILICHAO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	186	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TOPAGA	\N	8902055818	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	187	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MARULANDA	\N	8000993170	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	188	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN VICENTE CAGUAN	\N	8909825067	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	189	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MERCADERES	\N	8907019334	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	190	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LA JAGUA DE IBIRICO	\N	8000965993	\N	NO REGISTRA	11	1	0	\N	alcaldia@lajaguadeibirico-cesar.gov.co	\N	\N	191	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CHIMA	\N	8909809988	\N	NO REGISTRA	11	1	0	\N	alcaldia@chima-cordoba.gov.co	\N	\N	192	\N	1	170	1	\N
 ASOCIACION DE MUNICIPIOS DEL TEQUENDAMA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	193	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE FOMEQUE	\N	8902051768	\N	NO REGISTRA	11	1	0	\N	alcaldia@fomeque-cundinamarca.gov.co	\N	\N	194	\N	1	170	1	\N
 EMPRESAS PUBLICAS MUNICIPALES DE GIRARDOT	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	195	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE UBALA	\N	8000996354	\N	NO REGISTRA	11	1	0	\N	alcaldia@ubala-cundinamarca.gov.co	\N	\N	196	\N	1	170	1	\N
 EMPRESA DE ACUEDUCTO Y ALCANTARILLADO DE ZIPAQUIRA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	197	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE RIOHACHA	\N	8919003579	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	198	\N	1	170	1	\N
 UNIVERSIDAD DEL MAGDALENA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	199	\N	1	170	1	\N
 EMPRESA TURISTICA Y PROMOCIONAL DEL DISTRITO SE SANTA MARTA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	200	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTA ANA	\N	8000991385	\N	NO REGISTRA	11	1	0	\N	alcaldia@santaana-magdalena.gov.co	\N	\N	201	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE EL CALVARIO	\N	8001005150	\N	NO REGISTRA	11	1	0	\N	alcaldia@elcalvario-meta.gov.co	\N	\N	202	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PUERTO CONCORDIA	\N	8000943862	\N	NO REGISTRA	11	1	0	\N	alcaldia@puertoconcordia-meta.gov.co	\N	\N	203	\N	1	170	1	\N
 EMPRESA LICORERA DE NARINO	\N	8912003321	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	204	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ANCUYA	\N	8906800971	\N	NO REGISTRA	11	1	0	\N	alcaldia@ancuya-narino.gov.co	\N	\N	205	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE EL TABLON	\N	8000990790	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	206	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LA UNION	\N	8918562572	\N	NO REGISTRA	11	1	0	\N	alcaldia@launion-narino.gov.co	\N	\N	207	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAMANIEGO	\N	8908011495	\N	NO REGISTRA	11	1	0	\N	alcaldia@samaniego-narino.gov.co	\N	\N	208	\N	1	170	1	\N
 HOSPITAL JOSE SANTOS ILLERA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	209	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE OCANA	\N	8902051245	\N	NO REGISTRA	11	1	0	\N	alcaldia@ocana-nortedesantander.gov.co	\N	\N	210	\N	1	170	1	\N
 EMPRESA MINERA DE RISARALDA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	211	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE APIA	\N	8909800950	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	212	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE GUADALUPE	\N	\N	\N	Carrera 4a No 5 - 31 Parque Principal	11	1	0	\N	munguada@hotmail.com	\N	\N	213	\N	1	170	1	\N
 EMPRESA PIEDECUESTANA DE SERVICIOS PUBLICOS DOMICILIARIOS E.S.P.	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	215	\N	1	170	1	\N
 INSTITUTO IBAGUERENO DE DEPORTES  EDUCACION FISICA Y RECREACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	216	\N	1	170	1	\N
 EMPRESA DE SERVICIOS PUBLICOS DE FLANDES E.S.P.	\N	8001909214	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	217	\N	1	170	1	\N
 CORPORACION DE ABASTECIMIENTOS DEL VALLE DEL CAUCA	\N	8903042190	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	218	\N	1	170	1	\N
 HOSPITAL SAN RAFAEL DE ZARZAL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	219	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE AGUAZUL	\N	8908011320	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	220	\N	1	170	1	\N
DEFENSA CIVIL COLOMBIANA  -DCC 	\N	899999717	\N	Calle 52 No. 14-67	11	1	0	\N	\N	\N	\N	221	\N	1	170	1	\N
CORTE SUPREMA DE JUSTICIA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	222	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MANIZALES	\N	\N	\N	NO REGISTRA	11	1	0	\N	secosis@alcaldiamanizales.gov.co	\N	\N	223	\N	1	170	1	\N
FONDO ROTATORIO DE LA POLICIA NACIONAL  -FORPO 	\N	860020227	\N	Carrera 55 No 43-18	11	1	0	\N	\N	\N	\N	224	\N	1	170	1	\N
MINISTERIO DE TRANSPORTE  -MINTRANSPORTE 	\N	899999055	\N	Avenida El Dorado CAN Of.201	11	1	0	\N	mintrans@mintransporte.gov.co	\N	\N	225	\N	1	170	1	\N
DIRECCION NACIONAL DEL DERECHO DE AUTOR  -DNDA 	\N	800185929	\N	Carrera 13 No. 27 - 00   Piso 6o   Oficina 617	11	1	3418177 - 2816221	\N	derautor@col1.telecom.com.co	\N	\N	226	\N	1	170	1	\N
SUPERINTENDENCIA DE VALORES	\N	8909990576	\N	Calle 26 No 68B-85 Piso 2	11	1	0	\N	superval@supervalores.gov.co	\N	\N	227	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE FIRAVITOBA	\N	8900013395	\N	NO REGISTRA	11	1	0	\N	alcaldia@firavitoba-boyaca.gov.co	\N	\N	228	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MONTENEGRO	\N	8000967635	\N	NO REGISTRA	11	1	0	\N	alcaldia@montenegro-quindio.gov.co	\N	\N	229	\N	1	170	1	\N
 INSTITUTO DEPARTAMENTAL DE TRANSITO Y TRANSPORTE DE RISARALDA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	230	\N	1	170	1	\N
 AREA METROPOLITANA DEL CENTRO OCCIDENTE	\N	8914109020	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	231	\N	1	170	1	\N
 EMPRESA DE OBRAS SANITARIAS DE SANTANDER	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	232	\N	1	170	1	\N
 INSTITUTO INTEGRADO DE COMERCIO DE SANTANDER	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	233	\N	1	170	1	\N
 EMPRESAS PUBLICAS MUNICIPALES DE BUCARAMANGA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	234	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE OCAMONTE	\N	8919009023	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	235	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SABANA DE TORRES	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@sabanadetorres-santander.gov.co	\N	\N	236	\N	1	170	1	\N
 E.S.E. HOSPITAL REGIONAL DE SAN GIL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	237	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTA HELENA DEL OPON	\N	8000398039	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	238	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TONA	\N	8001007514	\N	NO REGISTRA	11	1	0	\N	alcaldia@tona-santander.gov.co	\N	\N	239	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE BUENAVISTA	\N	8903990453	\N	NO REGISTRA	11	1	0	\N	alcaldia@buenavista-sucre.gov.co	\N	\N	240	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CHALAN	\N	8999994002	\N	NO REGISTRA	11	1	0	\N	alcaldia@chalan-sucre.gov.co	\N	\N	241	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE OVEJAS	\N	8918013621	\N	NO REGISTRA	11	1	0	\N	alcaldia@ovejas-sucre.gov.co	\N	\N	242	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TOLU	\N	8905013620	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	243	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE IBAGUE	\N	8001000588	\N	NO REGISTRA	11	1	0	\N	alcaibe@bunde.tolinet.com.co	\N	\N	244	\N	1	170	1	\N
 BIBLIOTECA DEPARTAMENTAL JORGE GARCES ROMERO	\N	8903990399	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	245	\N	1	170	1	\N
 HOSPITAL MARIO CORREA RENGIFO DE CALI	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	246	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ARGELIA	\N	8060019374	\N	NO REGISTRA	11	1	0	\N	alcaldia@argelia-valle.gov.co	\N	\N	247	\N	1	170	1	\N
 INSTITUTO MUNICIPAL DE REFORMA URBANA Y VIVIENDA DE INTERES SOCIAL DE BUGA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	248	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE GUACARI	\N	8001263110	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	249	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PISBA	\N	8000944577	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	250	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN JOSE DE PARE	\N	8902048904	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanjosedepare-boyaca.gov.co	\N	\N	251	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTA SOFIA	\N	8001033181	\N	NO REGISTRA	11	1	0	\N	alcaldia@santasofia-boyaca.gov.co	\N	\N	252	\N	1	170	1	\N
 COMPANIA DE SERVICIOS PUBLICOS DE SOGAMOSO	\N	8918000314	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	253	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SORACA	\N	8000192779	\N	NO REGISTRA	11	1	0	\N	alcaldia@soraca-boyaca.gov.co	\N	\N	254	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TENZA	\N	8000951742	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	255	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TOCA	\N	8909807817	\N	NO REGISTRA	11	1	0	\N	alcaldia@toca-boyaca.gov.co	\N	\N	256	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ZETAQUIRA	\N	8919006240	\N	NO REGISTRA	11	1	0	\N	alcaldia@zetaquira-boyaca.gov.co	\N	\N	257	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SAN ROQUE	\N	8909821231	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanroque-antioquia.gov.co	\N	\N	258	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTA ROSA DE OSOS	\N	8904813433	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	259	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTO DOMINGO	\N	8000099262	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	260	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SONSON	\N	8000298265	\N	NO REGISTRA	11	1	0	\N	alcaldia@sonson-antioquia.gov.co	\N	\N	261	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TAMESIS	\N	8001028013	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	262	\N	1	170	1	\N
 E.S.E. HOSPITAL SAN JUAN DE DIOS DE TAMESIS	\N	8909808553	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	263	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TITIRIBI	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	264	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TURBO	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@turbo-antioquia.gov.co	\N	\N	265	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE URRAO	\N	8921151554	\N	NO REGISTRA	11	1	0	\N	alcaldia@urrao-antioquia.gov.co	\N	\N	266	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE VALPARAISO	\N	8000989118	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	267	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE VEGACHI	\N	8000504077	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	268	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE YARUMAL	\N	8908909648	\N	NO REGISTRA	11	1	0	\N	alcaldia@yarumal-antioquia.gov.co	\N	\N	269	\N	1	170	1	\N
 E.S.E. HOSPITAL SAN JUAN DE DIOS DE YARUMAL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	270	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE YOLOMBO	\N	8909800961	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	271	\N	1	170	1	\N
 HOSPITAL HECTOR ABAD GOMEZ DE YONDO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	272	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ZARAGOZA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	273	\N	1	170	1	\N
 INSTITUTO DEPARTAMENTAL DE TRANSPORTES Y TRANSITO DEL ATLANTICO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	274	\N	1	170	1	\N
 CORPORACION DE DESARROLLO POPULAR SOLEDAD	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	275	\N	1	170	1	\N
 CORPORACION AUTONOMA REGIONAL DEL ATLANTICO	\N	8020003390	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	276	\N	1	170	1	\N
 CENTRO DE ATENCION Y REHABILITACION INTEGRAL EN SALUD	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	277	\N	1	170	1	\N
 TERMINAL DE TRANSPORTE DE BARRANQUILLA	\N	8901060844	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	278	\N	1	170	1	\N
 EMPRESAS PUBLICAS MUNICIPALES DE BARRANQUILLA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	279	\N	1	170	1	\N
 METROFUTBOL BARRANQUILLA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	280	\N	1	170	1	\N
 FONDO ROTATORIO METROPOLITANO DE VALORIZACION DE BARRANQUILLA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	281	\N	1	170	1	\N
 HOSPITAL PEDIATRICO DE BARRANQUILLA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	282	\N	1	170	1	\N
 INSTITUTO DISTRITAL DE LA RECREACION Y EL DEPORTE DE BARRANQUILLA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	283	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CANDELARIA	\N	8909822388	\N	NO REGISTRA	11	1	0	\N	alcaldia@candelaria-atlantico.gov.co	\N	\N	284	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MALAMBO	\N	8902052291	\N	NO REGISTRA	11	1	0	\N	alcaldia@malambo-atlantico.gov.co	\N	\N	285	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PONEDERA	\N	8000767511	\N	NO REGISTRA	11	1	0	\N	alcaldia@ponedera-atlantico.gov.co	\N	\N	286	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTA LUCIA	\N	8900720441	\N	NO REGISTRA	11	1	0	\N	alcaldia@santalucia-atlantico.gov.co	\N	\N	287	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTO TOMAS	\N	8909838034	\N	NO REGISTRA	11	1	0	\N	alcaldia@santotomas-atlantico.gov.co	\N	\N	288	\N	1	170	1	\N
 EMPRESA DISTRITAL DE SERVICIOS PUBLICOS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	289	\N	1	170	1	\N
 EMPRESA DE ENERGIA ELECTRICA DE BOGOTA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	290	\N	1	170	1	\N
 FONDO DE EDUCACION Y SEGURIDAD VIAL DEL DATT EN LIQUIDACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	291	\N	1	170	1	\N
 ENTIDAD PROMOTORA DE SALUD CONVIDA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	292	\N	1	170	1	\N
 FONDO DE TRANSPORTES Y TRANSITO DE BOLIVAR	\N	\N	\N	Calle 31 Av. Pedro de Heredia Sector Armenia	11	1	0	\N	\N	\N	\N	293	\N	1	170	1	\N
 ALCALDIA DEL DISTRITO TURISTICO Y CULTURAL DE CARTAGENA DE INDIAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	informatica@alcaldiadecartagena.gov.co 	\N	\N	294	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ARROYOHONDO	\N	8907009820	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	295	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE BARRANCO DE LOBA	\N	8000992233	\N	NO REGISTRA	11	1	0	\N	alcaldia@barrancodeloba-bolivar.gov.co	\N	\N	296	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CARMEN DE BOLIVAR	\N	8001000501	\N	NO REGISTRA	11	1	0	\N	alcaldia@elcarmen-bolivar.gov.co	\N	\N	297	\N	1	170	1	\N
 TELEPSA - EMPRESA DE SERVICIOS PUBLICOS DOMICILIARIOS S.A.	\N	8160016091	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	298	\N	1	170	1	\N
 TERMINALES DE TRANSPORTE DE MEDELLIN S.A.	\N	8909192911	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	299	\N	1	170	1	\N
 EMPRESA MUNICIPAL DE SERVICIOS DE DOSQUEBRADAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	300	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MISTRATO	\N	8915008416	\N	NO REGISTRA	11	1	0	\N	alcaldia@mistrato-risaralda.gov.co	\N	\N	301	\N	1	170	1	\N
 EMPRESAS PUBLICAS MUNICIPALES DE QUINCHIA E.S.P.	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	302	\N	1	170	1	\N
 UNIVERSIDAD INDUSTRIAL DE SANTANDER  -UIS  - BUCARAMANGA	\N	8902012134	\N	CARRERA 27 calle 9 Ciudadela Universitaria Barrio la Universidad Departamento de Santander Municipio de Bucaramanga	11	1	6344000 Ext 2425	\N	rectoría@uis.edu.co	\N	\N	303	\N	1	170	1	\N
 INSTITUTO DE PREVISION SOCIAL DE SANTANDER	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	304	\N	1	170	1	\N
 CAJA DE PREVISION SOCIAL MUNICIPAL DE BUCARAMANGA	\N	8902048517	\N	Calle 36   12-76	11	1	0	\N	\N	\N	\N	305	\N	1	170	1	\N
 CENTRAL DE ABASTOS DE BUCARAMANGA S.A.	\N	8902083958	\N	NO REGISTRA	11	1	0	\N	cabastos@epm.net.co	\N	\N	306	\N	1	170	1	\N
 CONTRALORIA DEPARTAMENTAL DE SANTANDER	\N	8902017056	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	307	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE BARBOSA	\N	8000990617	\N	NO REGISTRA	11	1	0	\N	alcaldia@barbosa-santander.gov.co	\N	\N	308	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE BARICHARA	\N	8909804457	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	309	\N	1	170	1	\N
 DEPARTAMENTO DE VALORIZACION MUNICIPAL DE BARRANCABERMEJA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	310	\N	1	170	1	\N
 EMPRESA DE AGUA POTABLE Y SANEAMIENTO BASICO DE BARRANCABERMEJA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	311	\N	1	170	1	\N
 INSTITUTO UNIVERSITARIO DE LA PAZ DE BARRANCABERMEJA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	312	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE BOLIVAR	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@bolivar-santander.gov.co	\N	\N	313	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CALIFORNIA	\N	8903990113	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	314	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CAPITANEJO	\N	8999997100	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	315	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CHARALA	\N	8001000531	\N	NO REGISTRA	11	1	0	\N	alcaldia@charala-santander.gov.co	\N	\N	316	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CONCEPCION	\N	8918019320	\N	NO REGISTRA	11	1	0	\N	alcaldia@concepcion-santander.gov.co	\N	\N	317	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CONTRATACION	\N	8000990649	\N	NO REGISTRA	11	1	0	\N	alcaldia@contratacion-santander.gov.co	\N	\N	318	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE EL CARMEN DE CHUCURI	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@elcarmen-santander.gov.co	\N	\N	319	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE EL GUACAMAYO	\N	8000310732	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	320	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE FLORIAN	\N	8000263681	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	321	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE FLORIDABLANCA	\N	8001005191	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	322	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE GALAN	\N	8999993312	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	323	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE GIRON	\N	8909808071	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	324	\N	1	170	1	\N
 HOSPITAL SAN JUAN DE DIOS DE GIRON	\N	8902032427	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	325	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE GUAVATA	\N	8914800255	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	326	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE HATO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	327	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE JORDAN	\N	8001389593	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	328	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MACARAVITA	\N	8918011291	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	329	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MOLAGAVITA	\N	8902056325	\N	NO REGISTRA	11	1	0	\N	alcaldia@molagavita-santander.gov.co	\N	\N	330	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PALMA DEL SOCORRO	\N	8908011417	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	331	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PINCHOTE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	332	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE RIONEGRO	\N	8921150072	\N	NO REGISTRA	11	1	0	\N	alcaldia@rionegro-santander.gov.co	\N	\N	333	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ILES	\N	8001000595	\N	NO REGISTRA	11	1	0	\N	alcaldia@iles-narino.gov.co	\N	\N	334	\N	1	170	1	\N
 INSTITUTO DE SERVICIOS VARIOS DE IPIALES	\N	8002009992	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	335	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LA FLORIDA	\N	8909807820	\N	NO REGISTRA	11	1	0	\N	alcaldia@laflorida-narino.gov.co	\N	\N	336	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE OLAYA HERRERA	\N	8909841619	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	337	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE FRANCISCO PIZARRO	\N	8999994201	\N	NO REGISTRA	11	1	0	\N	alcaldia@franciscopizarro-narino.gov.co	\N	\N	338	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE PUPIALES	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@pupiales-narino.gov.co	\N	\N	339	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE SANTA BARBARA	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@santabarbara-narino.gov.co	\N	\N	340	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE TANGUA	\N	8000249776	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	341	\N	1	170	1	\N
 E.S.E. HOSPITAL SAN ANDRES DE TUMACO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	342	\N	1	170	1	\N
 UNIVERSIDAD DE PAMPLONA	\N	8905015104	\N	Campus Universitario Kms via Barrancabermeja	11	1	0	\N	\N	\N	\N	343	\N	1	170	1	\N
 AREA METROPOLITANA DE CUCUTA	\N	8001531970	\N	Avenida 5 11 - 20 Piso 3	11	1	0	\N	\N	\N	\N	344	\N	1	170	1	\N
 CENTRAL DE ABASTOS DE CUCUTA	\N	8905036140	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	345	\N	1	170	1	\N
 E.S.E. HOSPITAL REGIONAL OCCIDENTE	\N	8070088436	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	346	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE ARBOLEDAS	\N	8000990584	\N	NO REGISTRA	11	1	0	\N	alcaldia@arboledas-nortedesantander.gov.co	\N	\N	347	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE CACHIRA	\N	8000810919	\N	NO REGISTRA	11	1	0	\N	alcaldia@cachira-nortedesantander.gov.co	\N	\N	348	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE DURANIA	\N	8918551381	\N	NO REGISTRA	11	1	0	\N	alcaldia@durania-nortedesantander.gov.co	\N	\N	349	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE EL CARMEN	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@elcarmen-nortedesantander.gov.co	\N	\N	350	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LA ESPERANZA	\N	8908011306	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	351	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE LA PLAYA	\N	8911801557	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	352	\N	1	170	1	\N
 ALCALDIA MUNICIPIO DE MUTISCUA	\N	8909809505	\N	NO REGISTRA	11	1	0	\N	alcaldia@mutiscua-nortedesantander.gov.co	\N	\N	353	\N	1	170	1	\N
BENEFICENCIA DEL META	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	388	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TENERIFE	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@tenerife-magdalena.gov.co	\N	\N	387	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PIVIJAY	\N	8911800770	\N	NO REGISTRA	11	1	0	\N	alcaldia@pivijay-magdalena.gov.co	\N	\N	386	\N	1	170	1	\N
E.S.E. HOSPITAL DEPARTAMENTAL SAN RAFAEL DE FUNDACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	385	\N	1	170	1	\N
EMPRESAS DE SERVICIOS PUBLICOS DE EL BANCO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	384	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CIENAGA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	383	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ARIGUANI	\N	8909817868	\N	NO REGISTRA	11	1	0	\N	alcaldia@ariguani-magdalena.gov.co	\N	\N	382	\N	1	170	1	\N
HOSPITAL CENTRAL JULIO MENDEZ BARRENECHE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	381	\N	1	170	1	\N
INSTITUTO TRANSITO Y TRANSPORTE DE SANTA MARTA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	380	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ZAPAYAN	\N	8902041383	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	379	\N	1	170	1	\N
BENEFICENCIA Y ASISTENCIA PUBLICA DEL MAGDALENA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	378	\N	1	170	1	\N
EMPRESA DE ACUEDUCTO  ALCANTARILLADO Y ASEO DE SAN JUAN DEL CESAR	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	377	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MANAURE	\N	8000192184	\N	NO REGISTRA	11	1	0	\N	alcaldia@manaure-laguajira.gov.co	\N	\N	376	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MAICAO	\N	8000955143	\N	NO REGISTRA	11	1	0	\N	alcaldia@maicao-laguajira.gov.co	\N	\N	375	\N	1	170	1	\N
EMPRESA DE SERVICIO DE ACUEDUCTO Y ALCANTARILLADO DE RISARALDA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	374	\N	1	170	1	\N
COOPERATIVA DE ENTIDADES DE SALUD DE RISARALDA	\N	\N	\N	Avenida 30 de Agosto  87 -298	11	1	0	\N	\N	\N	\N	373	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GUAITARILLA	\N	8999997014	\N	NO REGISTRA	11	1	0	\N	alcaldia@guaitarilla-narino.gov.co	\N	\N	372	\N	1	170	1	\N
SERVICIO SECCIONAL DE SALUD DEL RISARALDA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	371	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SALENTO	\N	8001001404	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	370	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LA TEBAIDA	\N	8915021693	\N	NO REGISTRA	11	1	0	\N	alcaldia@latebaida-quindio.gov.co	\N	\N	369	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE FILANDIA	\N	8908011449	\N	NO REGISTRA	11	1	0	\N	alcaldia@filandia-quindio.gov.co	\N	\N	368	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CALARCA	\N	8001914311	\N	NO REGISTRA	11	1	0	\N	alcaldia@calarca-quindio.gov.co	\N	\N	366	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BUENAVISTA	\N	8900018790	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	365	\N	1	170	1	\N
MERCADOS DE ARMENIA S.A.	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	364	\N	1	170	1	\N
EMPRESAS PUBLICAS DE ARMENIA	\N	8900004399	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	363	\N	1	170	1	\N
EMPRESA SANITARIA DEL QUINDIO	\N	8000638237	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	362	\N	1	170	1	\N
UNIVERSIDAD DEL QUINDIO	\N	8900004328	\N	Carrera 15 calle 12 norte	11	1	0	\N	\N	\N	\N	361	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VILLACARO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	360	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TOLEDO	\N	8905013620	\N	NO REGISTRA	11	1	0	\N	alcaldia@toledo-nortedesantander.gov.co	\N	\N	359	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SARDINATA	\N	8001027996	\N	NO REGISTRA	11	1	0	\N	alcaldia@sardinata-nortedesantander.gov.co	\N	\N	358	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN CAYETANO	\N	8000982031	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	357	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE RAGONVALIA	\N	8999994310	\N	NO REGISTRA	11	1	0	\N	alcaldia@ragonvalia-nortedesantander.gov.co	\N	\N	356	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PAMPLONITA	\N	8000076526	\N	NO REGISTRA	11	1	0	\N	alcaldia@pamplonita-nortedesantander.gov.co	\N	\N	355	\N	1	170	1	\N
E.S.E. HOSPITAL REGIONAL NORTE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	354	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PALERMO	\N	8000655937	\N	NO REGISTRA	11	1	0	\N	alcaldia@palermo-huila.gov.co	\N	\N	446	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ISNOS	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@isnos-huila.gov.co	\N	\N	445	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE IQUIRA	\N	8000990957	\N	NO REGISTRA	11	1	0	\N	alcaldia@iquira-huila.gov.co	\N	\N	444	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GIGANTE	\N	8900008646	\N	NO REGISTRA	11	1	0	\N	alcaldia@gigante-huila.gov.co	\N	\N	443	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GARZON	\N	8000256080	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	442	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE COLOMBIA	\N	8999994668	\N	NO REGISTRA	11	1	0	\N	alcaldia@colombia-huila.gov.co	\N	\N	441	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BARAYA	\N	8901123718	\N	NO REGISTRA	11	1	0	\N	alcaldia@baraya-huila.gov.co	\N	\N	440	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE AIPE	\N	8000965581	\N	NO REGISTRA	11	1	0	\N	alcaldia@aipe-huila.gov.co	\N	\N	439	\N	1	170	1	\N
INSTITUTO DE DESARROLLO MUNICIPAL DEL HUILA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	438	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE NEIVA	\N	\N	\N	NO REGISTRA	11	1	0	\N	webmaster@alcaldianeiva.gov.co	\N	\N	437	\N	1	170	1	\N
CORPORACION AUTONOMA REGIONAL DEL ALTO MAGDALENA	\N	8002555807	\N	Carrera 1 60 - 79 Barrio Las Mercedes 	11	1	0	\N	camhuila@cam.gov.co 	\N	\N	436	\N	1	170	1	\N
EMPRESA DE OBRAS SANITARIAS DEL HUILA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	435	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TADO	\N	8999994439	\N	NO REGISTRA	11	1	0	\N	alcaldia@tado-choco.gov.co	\N	\N	434	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE RIOSUCIO	\N	8902046463	\N	NO REGISTRA	11	1	0	\N	alcaldia@riosucio-choco.gov.co	\N	\N	433	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LLORO	\N	8000991052	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	432	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BAHIA SOLANO	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@bahiasolano-choco.gov.co	\N	\N	431	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BAGADO	\N	8000967373	\N	NO REGISTRA	11	1	0	\N	alcaldia@bagado-choco.gov.co	\N	\N	430	\N	1	170	1	\N
CAJA DE PREVISION SOCIAL DEL MAGISTERIO DEL CHOCO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	429	\N	1	170	1	\N
LOTERIA DEL CHOCO	\N	8916800468	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	428	\N	1	170	1	\N
METALES PRECIOSOS DEL CHOCO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	427	\N	1	170	1	\N
GOBERNACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	426	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VIANI	\N	8902109511	\N	NO REGISTRA	11	1	0	\N	alcaldia@viani-cundinamarca.gov.co	\N	\N	425	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VERGARA	\N	8918009862	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	424	\N	1	170	1	\N
\N	\N	8000955680	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	423	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE UBAQUE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	422	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TOCANCIPA	\N	8000934391	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	421	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TAUSA	\N	8000128737	\N	NO REGISTRA	11	1	0	\N	alcaldia@tausa-cundinamarca.gov.co	\N	\N	420	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SUBACHOQUE	\N	8911801912	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	419	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SASAIMA	\N	8000992638	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	418	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN JUAN DE RIOSECO	\N	8000982056	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanjuanderioseco-cundinamarca.gov.co	\N	\N	417	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE APULO - RAFAEL REYES	\N	8914800223	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	416	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE QUIPILE	\N	8000295135	\N	NO REGISTRA	11	1	0	\N	alcaldia@quipile-cundinamarca.gov.co	\N	\N	415	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PANDI	\N	8905061168	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	414	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PACHO	\N	8000284616	\N	NO REGISTRA	11	1	0	\N	alcaldia@pacho-cundinamarca.gov.co	\N	\N	413	\N	1	170	1	\N
HOSPITAL SANTA TERESA DE JESUS DE AVILA	\N	8250010371	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	412	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GUACHUCAL	\N	8999993620	\N	NO REGISTRA	11	1	0	\N	alcaldia@guachucal-narino.gov.co	\N	\N	411	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE EL ROSARIO	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@elrosario-narino.gov.co	\N	\N	410	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CONTADERO	\N	8000190006	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	409	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CONSACA	\N	8902089473	\N	NO REGISTRA	11	1	0	\N	alcaldia@consaca-narino.gov.co	\N	\N	408	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BARBACOAS	\N	8911801833	\N	NO REGISTRA	11	1	0	\N	alcaldia@barbacoas-narino.gov.co	\N	\N	407	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ALDANA	\N	8919010790	\N	Edificio Alcaldia	11	1	0	\N	alcaldia@aldana-narino.gov.co	\N	\N	406	\N	1	170	1	\N
TERMINAL DE TRANSPORTES DE PASTO S.A.	\N	8000570197	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	405	\N	1	170	1	\N
INSTITUTO DE VALORIZACION MUNICIPAL DE PASTO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	404	\N	1	170	1	\N
INSTITUTO DEPARTAMENTAL DE SALUD	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	403	\N	1	170	1	\N
EMPRESA DE OBRAS SANITARIAS DE NARINO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	402	\N	1	170	1	\N
GOBERNACION	\N	8001039238	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	401	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VISTA HERMOSA	\N	8918013470	\N	NO REGISTRA	11	1	0	\N	alcaldia@vistahermosa-meta.gov.co	\N	\N	400	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN CARLOS DE GUAROA	\N	8909837409	\N	NO REGISTRA	11	1	0	\N	alcaldia@sancarlosdeguaroa-meta.gov.co	\N	\N	399	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PUERTO RICO	\N	8000605253	\N	NO REGISTRA	11	1	0	\N	alcaldia@puertorico-meta.gov.co	\N	\N	398	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LEJANIAS	\N	8000191115	\N	NO REGISTRA	11	1	0	\N	alcaldia@lejanias-meta.gov.co	\N	\N	397	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LA URIBE	\N	8000503319	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	396	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MESETAS	\N	8915023976	\N	NO REGISTRA	11	1	0	\N	alcaldia@mesetas-meta.gov.co	\N	\N	395	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE FUENTE DE ORO	\N	8909837068	\N	NO REGISTRA	11	1	0	\N	alcaldia@fuentedeoro-meta.gov.co	\N	\N	394	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE EL DORADO	\N	8000957609	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	393	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CASTILLA LA NUEVA	\N	8907020217	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	392	\N	1	170	1	\N
CORPORACION FORESTAL DE VILLAVICENCIO	\N	\N	\N	Calle 40  33 - 64	11	1	0	\N	\N	\N	\N	391	\N	1	170	1	\N
INSTITUTO DE VALORIZACION MUNICIPAL DE VILLAVICENCIO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	390	\N	1	170	1	\N
EMPRESAS PUBLICAS MUNICIPALES DE MONTERIA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	508	\N	1	170	1	\N
COLVATEL	\N	8001962998	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	507	\N	1	170	1	\N
HOSPITAL LAZARO ALFONSO HERNANDEA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	505	\N	1	170	1	\N
DEPARTAMENTO ADMINISTRATIVO DE SALUD DE LA GUAJIRA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	504	\N	1	170	1	\N
UNIVERSIDAD DE LA GUAJIRA	\N	8921150294	\N	Calle 1A Carrera 6a Edif. Gobernacion	11	1	0	\N	\N	\N	\N	503	\N	1	170	1	\N
EMPRESA DE OBRAS SANITARIAS DE LA GUAJIRA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	502	\N	1	170	1	\N
GOBERNACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	501	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TELLO	\N	8999994819	\N	NO REGISTRA	11	1	0	\N	alcaldia@tello-huila.gov.co	\N	\N	500	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TESALIA	\N	8911801819	\N	NO REGISTRA	11	1	0	\N	alcaldia@tesalia-huila.gov.co	\N	\N	499	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SANTA MARIA	\N	8000192541	\N	NO REGISTRA	11	1	0	\N	alcaldia@santamaria-huila.gov.co	\N	\N	498	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PALESTINA	\N	8911800219	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	497	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PANQUEBA	\N	8906801731	\N	NO REGISTRA	11	1	0	\N	alcaldia@panqueba-boyaca.gov.co	\N	\N	496	\N	1	170	1	\N
INSTITUTO DE TURISMO Y RECREACION DE PAIPA	\N	8260002146	\N	Kilometro 4 Via Paipa Pantano de Vargas	11	1	0	\N	itppaipa@hotmail.com	\N	\N	495	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PACHAVITA	\N	8001007291	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	494	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MARIPI	\N	8909837161	\N	NO REGISTRA	11	1	0	\N	alcaldia@maripi-boyaca.gov.co	\N	\N	493	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LABRANZAGRANDE	\N	8905036807	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	492	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GARAGOA	\N	8918577641	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	491	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE FLORESTA	\N	8000957282	\N	NO REGISTRA	11	1	0	\N	alcaldia@floresta-boyaca.gov.co	\N	\N	490	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHIVOR	\N	8000149891	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	489	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHIQUIZA	\N	8918004750	\N	NO REGISTRA	11	1	0	\N	alcaldia@chiquiza-boyaca.gov.co	\N	\N	488	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CORRALES	\N	8922800322	\N	NO REGISTRA	11	1	0	\N	alcaldia@corrales-boyaca.gov.co	\N	\N	487	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHITARAQUE	\N	8905014224	\N	NO REGISTRA	11	1	0	\N	alcaldia@chitaraque-boyaca.gov.co	\N	\N	486	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHISCAS	\N	8000965850	\N	NO REGISTRA	11	1	0	\N	alcaldia@chiscas-boyaca.gov.co	\N	\N	485	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CAMPOHERMOSO	\N	8911181199	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	484	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CALDAS	\N	8900004414	\N	NO REGISTRA	11	1	0	\N	alcaldia@caldas-boyaca.gov.co	\N	\N	483	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BETEITIVA	\N	8909808023	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	482	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BELEN	\N	8908026509	\N	NO REGISTRA	11	1	0	\N	alcaldia@belen-boyaca.gov.co	\N	\N	481	\N	1	170	1	\N
EMPRESA DE OBRAS SANITARIAS DE TUNJA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	480	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TUNJA	\N	8919002721	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	479	\N	1	170	1	\N
EMPRESA DE OBRAS SANITARIAS DE BOYACA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	478	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VILLANUEVA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	476	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TIQUISIO	\N	8000991876	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	475	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TALAIGUA NUEVO	\N	8916800810	\N	NO REGISTRA	11	1	0	\N	alcaldia@talaiguanuevo-bolivar.gov.co	\N	\N	474	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SANTA ROSA DEL SUR	\N	8000392133	\N	NO REGISTRA	11	1	0	\N	alcaldia@santarosadelsur-bolivar.gov.co	\N	\N	473	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SANTA ROSA DE LIMA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	472	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN MARTIN DE LOBA	\N	8923010933	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	471	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN JACINTO	\N	8000998241	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanjacinto-bolivar.gov.co	\N	\N	470	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE RIO VIEJO	\N	8923001231	\N	NO REGISTRA	11	1	0	\N	alcaldia@rioviejo-bolivar.gov.co	\N	\N	469	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MAHATES	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@mahates-bolivar.gov.co	\N	\N	468	\N	1	170	1	\N
HOSPITAL SAN JUAN DE DIOS DE MAGANGUE	\N	8060012599	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	467	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PELAYA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	466	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GONZALEZ	\N	8909839381	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	465	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE EL COPEY	\N	8906801620	\N	NO REGISTRA	11	1	0	\N	alcaldia@elcopey-cesar.gov.co	\N	\N	464	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHIRIGUANA	\N	8000997234	\N	NO REGISTRA	11	1	0	\N	alcaldia@chiriguana-cesar.gov.co	\N	\N	463	\N	1	170	1	\N
EMPRESAS PUBLICAS MUNICIPALES DE IBAGUE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	462	\N	1	170	1	\N
BENEFICENCIA DEL TOLIMA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	461	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAMPUES	\N	8000991360	\N	NO REGISTRA	11	1	0	\N	alcaldia@sampues-sucre.gov.co	\N	\N	460	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MORROA	\N	8000957734	\N	NO REGISTRA	11	1	0	\N	alcaldia@morroa-sucre.gov.co	\N	\N	459	\N	1	170	1	\N
FONDO ROTATORIO MUNICIPAL DE VALORIZACION DE SINCELEJO - FOMVAS	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	458	\N	1	170	1	\N
CONTRALORIA DEPARTAMENTAL DE SUCRE	\N	8922800171	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	457	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ZAPATOCA	\N	8904811777	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	456	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VILLANUEVA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	455	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN VICENTE CHUCURI	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	454	\N	1	170	1	\N
INSTITUTO DE VIVIENDA DE INTERES SOCIAL Y REFORMA URBANA DE SAN GIL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	453	\N	1	170	1	\N
TERMINAL DE TRANSPORTES DE SAN GIL	\N	\N	\N	Calle 37 No. 10 - 30	11	1	0	\N	\N	\N	\N	452	\N	1	170	1	\N
GOBERNACION	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	451	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PROVIDENCIA Y SANTA CATALINA	\N	8002224989	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	450	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VALLE GUAMUEZ	\N	8001001436	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	449	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE URIBIA	\N	8909845754	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	566	\N	1	170	1	\N
CENTRAL DE TRANSPORTES DE MAICAO	\N	8001486480	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	565	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE YAGUARA	\N	8000991536	\N	NO REGISTRA	11	1	0	\N	alcaldia@yaguara-huila.gov.co	\N	\N	564	\N	1	170	1	\N
SOCIEDAD DEL TERMINAL DE TRANSPORTE DE PITALITO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	563	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE HOBO	\N	8909849868	\N	NO REGISTRA	11	1	0	\N	alcaldia@hobo-huila.gov.co	\N	\N	562	\N	1	170	1	\N
EMPRESAS PUBLICAS MUNICIPALES DE GARZON	\N	\N	\N	Centro cial. Paseo El Rosario 3 piso	11	1	0	\N	\N	\N	\N	561	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TOPAIPI	\N	8918566251	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	560	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SUTATAUSA	\N	8000309881	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	559	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SUESCA	\N	8170034405	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	558	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE QUEBRADANEGRA	\N	8000791627	\N	NO REGISTRA	11	1	0	\N	alcaldia@quebradanegra-cundinamarca.gov.co	\N	\N	556	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PASCA	\N	8000741205	\N	NO REGISTRA	11	1	0	\N	alcaldia@pasca-cundinamarca.gov.co	\N	\N	555	\N	1	170	1	\N
EMPRESA DE ACUEDUCTO  ALCANTARILLADO Y ASEO DE MADRID - EAAAM ESP	\N	8320015122	\N	CALLE 9  N 7 - 99  MADRID - CUNDINAMARCA	11	1	8250145 - 8254899	\N	eaaam_esp@madrid-cundinamarca.gov.co	\N	\N	554	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GUAYABAL DE SIQUIMA	\N	8902109455	\N	NO REGISTRA	11	1	0	\N	alcaldia@guayabaldesiquima-cundinamarca.gov.co	\N	\N	553	\N	1	170	1	\N
EMPRESA DE TELEFONOS DE GIRARDOT	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	552	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GAMA	\N	8000498260	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	551	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LA APARTADA	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@laapartada-cordoba.gov.co	\N	\N	550	\N	1	170	1	\N
FONDO DE VIVIENDA MUNICIPAL DE INTERES SOCIAL Y REFORMA URBANA DE MONTERIA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	549	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TAMALAMEQUE	\N	8000955301	\N	NO REGISTRA	11	1	0	\N	alcaldia@tamalameque-cesar.gov.co	\N	\N	548	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LA GLORIA	\N	8000991006	\N	NO REGISTRA	11	1	0	\N	alcaldia@lagloria-cesar.gov.co	\N	\N	547	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE EL PASO	\N	8000927880	\N	NO REGISTRA	11	1	0	\N	alcaldia@elpaso-cesar.gov.co	\N	\N	546	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BECERRIL	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@becerril-cesar.gov.co	\N	\N	545	\N	1	170	1	\N
LOTERIA DEL CESAR LA VALLENATA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	544	\N	1	170	1	\N
EMPRESA DE SERVICIOS DE VALLEDUPAR	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	543	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SUAREZ	\N	8907009780	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	542	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PUERTO TEJADA	\N	8002508531	\N	NO REGISTRA	11	1	0	\N	alcaldia@puertotejada-cauca.gov.co	\N	\N	541	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PAEZ	\N	8000959787	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	540	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE FILADELFIA	\N	8001000549	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	539	\N	1	170	1	\N
E.S.E. HOSPITAL DE CALDAS - MANIZALES	\N	8001556331	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	538	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TUTA	\N	8918017878	\N	NO REGISTRA	11	1	0	\N	alcaldia@tuta-boyaca.gov.co	\N	\N	537	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TIPACOQUE	\N	8000284361	\N	NO REGISTRA	11	1	0	\N	alcaldia@tipacoque-boyaca.gov.co	\N	\N	536	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TIBANA	\N	8000186895	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	535	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SOATA	\N	8000947557	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	534	\N	1	170	1	\N
FONDO MUNICIPAL DE VIVIENDA DE INTERES SOCIAL Y REFORMA URBANA DE GIRARDOT	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	533	\N	1	170	1	\N
ACUEDUCTO Y ALCANTARILLADO DE GIRARDOT	\N	8906000036	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	532	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GACHETA	\N	8000200459	\N	NO REGISTRA	11	1	0	\N	alcaldia@gacheta-cundinamarca.gov.co	\N	\N	531	\N	1	170	1	\N
EMPRESA DE OBRAS PUBLICAS DE FOMEQUE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	530	\N	1	170	1	\N
EMPRESAS SERVICIOS PUBLICOS FOMEQUE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	529	\N	1	170	1	\N
EMPRESAS PUBLICAS DE FACATATIVA EPF	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	528	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHOCONTA	\N	8999994145	\N	NO REGISTRA	11	1	0	\N	alcaldia@choconta-cundinamarca.gov.co	\N	\N	527	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHIPAQUE	\N	8000967531	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	526	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CHAGUANI	\N	8001999594	\N	NO REGISTRA	11	1	0	\N	alcaldia@chaguani-cundinamarca.gov.co	\N	\N	525	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BITUIMA	\N	8902081191	\N	NO REGISTRA	11	1	0	\N	alcaldia@bituima-cundinamarca.gov.co	\N	\N	524	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ANAPOIMA	\N	8001000484	\N	NO REGISTRA	11	1	0	\N	alcaldia@anapoima-cundinamarca.gov.co	\N	\N	523	\N	1	170	1	\N
EMPRESA DE ACUEDUCTO Y ALCANTARILLADO CIUDAD DE FACATATIVA E.S.P.	\N	8001886600	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	522	\N	1	170	1	\N
INSTITUTO PARA EL DESARROLLO DE CUNDINAMARCA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	521	\N	1	170	1	\N
ASOCIACION DE MUNICIPIOS DE GUALIVA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	520	\N	1	170	1	\N
ASOCIACION DE MUNICIPIOS DEL MAGDALENA CENTRO	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	519	\N	1	170	1	\N
ASOCIACION DE MUNICIPIOS DEL VALLE DE UBATE	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	518	\N	1	170	1	\N
BENEFICENCIA DE CUNDINAMARCA	\N	8999990721	\N	Calle 49 13-33 Mezz	11	1	0	\N	\N	\N	\N	517	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE VALENCIA	\N	8909811061	\N	NO REGISTRA	11	1	0	\N	alcaldia@valencia-cordoba.gov.co	\N	\N	516	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN ANTERO	\N	8912009162	\N	NO REGISTRA	11	1	0	\N	alcaldia@sanantero-cordoba.gov.co	\N	\N	515	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN ANDRES DE SOTAVENTO	\N	8909818683	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	514	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PUEBLO NUEVO	\N	8240016241	\N	Calle 12 No 10-25	11	1	0	\N	alcaldia@pueblonuevo-cordoba.gov.co	\N	\N	513	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PLANETA RICA	\N	8001001371	\N	NO REGISTRA	11	1	0	\N	alcaldia@planetarica-cordoba.gov.co	\N	\N	512	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE COTORRA	\N	8999997053	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	511	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE BUENAVISTA	\N	8900018790	\N	NO REGISTRA	11	1	0	\N	alcaldia@buenavista-cordoba.gov.co	\N	\N	510	\N	1	170	1	\N
INSTITUTO DE FINANCIAMIENTO Y DESARROLLO COOPERATIVO DE COLOMBIA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	576	\N	1	170	1	\N
MINISTERIO DE AGRICULTURA Y DESARROLLO RURAL  -MINAGRICULTURA 	\N	899999028	\N	Avenida Jimenez No 7-65	11	1	0	\N	sistemas@minagricultura.gov.co	\N	\N	577	\N	1	170	1	\N
SUPERINTENDENCIA DEL SUBSIDIO FAMILIAR	\N	8605036009	\N	Calle 45ANo 9-46	11	1	0	\N	ssf@supersubsidio.gov.co	\N	\N	578	\N	1	170	1	\N
SUPERINTENDENCIA NACIONAL DE SALUD  -SUPERSALUD 	\N	860062187	\N	Carrera 13  32 - 76 piso 8	11	1	0	\N	\N	\N	\N	579	\N	1	170	1	\N
CENTRAL HIDROELECTRICA DE CALDAS	\N	8908001286	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	581	\N	1	170	1	\N
ELECTRIFICADORA DE BOLIVAR	\N	8904800015	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	582	\N	1	170	1	\N
ELECTRIFICADORA DEL TOLIMA	\N	8907017908	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	583	\N	1	170	1	\N
ELECTRIFICADORA DE LA GUAJIRA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	584	\N	1	170	1	\N
SOCIEDAD REGIONAL DE TELEVISION TELECAFE LTDA.	\N	8908077248	\N	Carrera  19 A Calle 43  Sacatin - U. Autonoma	11	1	0	\N	\N	\N	\N	585	\N	1	170	1	\N
CONTRALORIA GENERAL DE LA REPUBLICA  -CGR 	\N	899999067	\N	Carrera 10No 17 - 18 Torre Colseguros 	11	1	0	\N	\N	\N	\N	586	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ORITO	\N	8911801793	\N	NO REGISTRA	11	1	0	\N	alcaldia@orito-putumayo.gov.co	\N	\N	38	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE CLEMENCIA	\N	8909109133	\N	NO REGISTRA	11	1	0	\N	alcaldia@clemencia-bolivar.gov.co	\N	\N	43	\N	1	170	1	\N
E.S.E. HOSPITAL LA ANUNCIACION DE MUTATA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	602	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MONTEBELLO	\N	8000654749	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	601	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LIBORINA	\N	8001000610	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	600	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ITUANGO	\N	8909800938	\N	NO REGISTRA	11	1	0	\N	alcaldia@ituango-antioquia.gov.co	\N	\N	599	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GRANADA	\N	8905014041	\N	NO REGISTRA	11	1	0	\N	alcaldia@granada-antioquia.gov.co	\N	\N	598	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE GIRARDOTA	\N	8906803784	\N	C.A.  Simon Bolivar	11	1	0	\N	sadministrativo@girardota.gov.co	\N	\N	597	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE FREDONIA	\N	8000990853	\N	NO REGISTRA	11	1	0	\N	alcaldia@fredonia-antioquia.gov.co	\N	\N	596	\N	1	170	1	\N
INSTITUTO METROPOLITANO DE VALORIZACION - MEDELLIN	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	595	\N	1	170	1	\N
EMPRESAS PUBLICAS DE YARUMAL E.S.P.	\N	8001829631	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	594	\N	1	170	1	\N
EMPRESA ANTIOQUENA DE ENERGIA	\N	8909034624	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	593	\N	1	170	1	\N
CORPORACION AUTONOMA REGIONAL DEL VALLE DEL CAUCA	\N	8903990027	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	592	\N	1	170	1	\N
CORPORACION AUTONOMA REGIONAL DEL SUR DE BOLIVAR	\N	8060003277	\N	Calle 16 No. 10-27 Avenida Colombia	11	1	0	\N	\N	\N	\N	591	\N	1	170	1	\N
CORPORACION AUTONOMA REGIONAL DEL QUINDIO	\N	8900004478	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	590	\N	1	170	1	\N
CORPORACION AUTONOMA REGIONAL DE CUNDINAMARCA	\N	8999990626	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	589	\N	1	170	1	\N
CORPORACION PARA EL DESARROLLO SOSTENIBLE DEL NORTE Y ORIENTE DE LA AMAZONIA	\N	\N	\N	Calle 26 No. 11 - 131 Barrio Cinco de Diciembre 	11	1	0	\N	\N	\N	\N	588	\N	1	170	1	\N
CORPORACION AUTONOMA REGIONAL DE NARINO	\N	8912223222	\N	NO REGISTRA	11	1	0	\N	webmaster@corponarino.gov.co	\N	\N	587	\N	1	170	1	\N
ISA - INTERCONEXION ELECTRICA S.A. E.S.P.	\N	8600166103	\N	Calle 12 sur  No 18 - 168	11	1	0	\N	\N	\N	\N	580	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE LA PRIMAVERA	\N	\N	\N	NO REGISTRA	11	1	0	\N	alcaldia@laprimavera-vichada.gov.co	\N	\N	575	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE TARAIRA	\N	8000991511	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	574	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SANTA CRUZ GUACHAVEZ	\N	8904800695	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	573	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN LORENZO	\N	8920992467	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	572	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE MOSQUERA	\N	8922012962	\N	NO REGISTRA	11	1	0	\N	alcaldia@mosquera-narino.gov.co	\N	\N	571	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE EL CHARCO	\N	8902098899	\N	NO REGISTRA	11	1	0	\N	alcaldia@elcharco-narino.gov.co	\N	\N	570	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ARBOLEDA  -BERRUECOS 	\N	8000933868	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	569	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ALBAN	\N	8911800701	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	568	\N	1	170	1	\N
EMPRESA DE OBRAS SANITARIAS DE SANTA MARTA	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	567	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN CAYETANO	\N	8905018764	\N	NO REGISTRA	11	1	0	\N	alcaldia@sancayetano-cundinamarca.gov.co	\N	\N	557	\N	1	170	1	\N
M. MONTERIA VALORIZACION MUNICIPAL	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	509	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SAN MARTIN	\N	8922005916	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	506	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE ZAMBRANO	\N	8903990256	\N	NO REGISTRA	11	1	0	\N	alcaldia@zambrano-bolivar.gov.co	\N	\N	477	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE SIBUNDOY	\N	8999993720	\N	NO REGISTRA	11	1	0	\N	alcaldia@sibundoy-putumayo.gov.co	\N	\N	448	\N	1	170	1	\N
ALCALDIA MUNICIPIO DE PUERTO LEGUIZAMO	\N	8002224892	\N	NO REGISTRA	11	1	0	\N	alcaldia@puertoleguizamo-putumayo.gov.co	\N	\N	447	\N	1	170	1	\N
INSTITUTO DE VALORIZACION DEL META	\N	\N	\N	NO REGISTRA	11	1	0	\N	\N	\N	\N	389	\N	1	170	1	\N
EMPRESAS PUBLICAS DE CALARCA EMCA E.S.P.	\N	8900003770	\N	CARRERA 24  N 39 - 54 PISO 2  CALARCa - QUINDiO	11	1	7421903-7421114-7431105	\N	emcaesp@telecom.com.co	\N	\N	367	\N	1	170	1	\N
\.


--
-- Data for Name: carpeta; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.carpeta (carp_codi, carp_desc) FROM stdin;
0	Entrada
12	Devueltos
11	Vo.Bo.
1	Salida
3	Memorandos
\.


--
-- Data for Name: carpeta_per; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.carpeta_per (usua_codi, depe_codi, nomb_carp, desc_carp, codi_carp) FROM stdin;
\.


--
-- Data for Name: centro_poblado; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.centro_poblado (cpob_codi, muni_codi, dpto_codi, cpob_nomb, cpob_nomb_anterior) FROM stdin;
0	1	5	MEDELLIN	\N
1	1	5	PALMITAS	\N
4	1	5	SANTA ELENA	\N
9	1	5	ALTAVISTA	\N
10	1	5	AGUAS FRIAS	\N
12	1	5	LA LOMA	\N
13	1	5	SAN JOSE DEL MANZANILLO	\N
14	1	5	BARRO BLANCO	\N
15	1	5	EL CERRO	\N
17	1	5	EL PATIO	\N
18	1	5	EL PLACER	\N
19	1	5	EL PLAN	\N
22	1	5	LA ALDEA	\N
23	1	5	LA CUCHILLA	\N
25	1	5	LA PALMA	\N
27	1	5	LAS PLAYAS	\N
29	1	5	PIEDRA GORDA	\N
31	1	5	POTRERITO	\N
32	1	5	TRAVESIAS	\N
33	1	5	URQUITA	\N
35	1	5	BOQUERON	\N
39	1	5	EL LLANO 1	\N
40	1	5	EL LLANO	\N
48	1	5	LA VERDE	\N
52	1	5	MATASANO	\N
54	1	5	MATASANO 2	\N
55	1	5	MAZO	\N
57	1	5	MEDIA LUNA	\N
66	1	5	LAS CAMELIAS	\N
0	2	5	ABEJORRAL	\N
2	2	5	PANTANILLO	\N
4	2	5	NARANJAL - LAS FONDAS	\N
16	2	5	LAS CANOAS	\N
0	4	5	ABRIAQUI	\N
3	4	5	POTREROS SECTOR 1	\N
0	21	5	ALEJANDRIA	\N
0	30	5	AMAGA	\N
1	30	5	CAMILO C	\N
3	30	5	LA CLARITA	\N
4	30	5	LA FERREIRA	\N
5	30	5	LA GUALI	\N
6	30	5	PIEDECUESTA	\N
11	30	5	MINAS	\N
12	30	5	CAMILO C - ALTO DE LA VIRGEN	\N
13	30	5	PIEDECUESTA - MANI DE LAS CASAS	\N
0	31	5	AMALFI	\N
4	31	5	PORTACHUELO	\N
0	34	5	ANDES	\N
1	34	5	BUENOS AIRES	\N
3	34	5	SAN JOSE	\N
6	34	5	SANTA RITA	\N
7	34	5	TAPARTO	\N
12	34	5	SANTA INES	\N
14	34	5	SAN BARTOLO	\N
15	34	5	LA CHAPARRALA - LA UNION	\N
0	36	5	ANGELOPOLIS	\N
1	36	5	LA ESTACION	\N
6	36	5	SANTA RITA	\N
11	36	5	CIENAGUITA	\N
0	38	5	ANGOSTURA	\N
8	38	5	LLANOS DE CUIBA	\N
0	40	5	ANORI	\N
2	40	5	LIBERIA	\N
3	40	5	MONTEFRIO	\N
0	42	5	SANTA FE DE ANTIOQUIA	\N
2	42	5	LAURELES	\N
6	42	5	EL PESCADO	\N
7	42	5	SABANAS	\N
8	42	5	KILOMETRO 2	\N
9	42	5	PASO REAL	\N
0	44	5	ANZA	\N
1	44	5	GUINTAR	\N
5	44	5	LA CEJITA	\N
6	44	5	LA HIGUINA	\N
0	45	5	APARTADO	\N
1	45	5	SAN JOSE DE APARTADO	\N
2	45	5	CHURIDO	\N
3	45	5	ZUNGO CARRETERA	\N
7	45	5	LOS NARANJALES	\N
8	45	5	VIJAGUAL	\N
9	45	5	EL REPOSO	\N
10	45	5	BAJO DEL OSO	\N
11	45	5	EL SALVADOR	\N
14	45	5	PUERTO GIRON	\N
15	45	5	LOMA VERDE	\N
16	45	5	SAN PABLO	\N
0	51	5	ARBOLETES	\N
1	51	5	BUENOS AIRES	\N
3	51	5	EL CARMELO	\N
5	51	5	LAS NARANJITAS	\N
8	51	5	EL YESO	\N
9	51	5	LA TRINIDAD	\N
10	51	5	LAS PLATAS (SANTAFE)	\N
11	51	5	LA CANDELARIA	\N
14	51	5	EL GUADUAL	\N
0	55	5	ARGELIA	\N
0	59	5	ARMENIA	\N
1	59	5	LA HERRADURA	\N
3	59	5	EL SOCORRO	\N
6	59	5	FILO SECO	\N
9	59	5	PALMICHAL	\N
0	79	5	BARBOSA	\N
1	79	5	HATILLO	\N
5	79	5	PLATANITO	\N
8	79	5	ISAZA	\N
14	79	5	POPALITO	\N
16	79	5	YARUMITO	\N
17	79	5	TABLAZO - HATILLO	\N
18	79	5	EL PARAISO	\N
19	79	5	EL SALADITO	\N
20	79	5	LOMITA 1	\N
21	79	5	LOMITA 2	\N
22	79	5	LA PRIMAVERA	\N
23	79	5	TAMBORCITO	\N
0	86	5	BELMIRA	\N
1	86	5	LABORES	\N
3	86	5	RIO ARRIBA	\N
0	88	5	BELLO	\N
8	88	5	POTRERITO	\N
13	88	5	SAN FELIX	\N
18	88	5	EL PINAR	\N
20	88	5	EL ALBERGUE	\N
22	88	5	LA CHINA	\N
23	88	5	LA UNION	\N
0	91	5	BETANIA	\N
3	91	5	SAN LUIS	\N
0	93	5	BETULIA	\N
1	93	5	ALTAMIRA	\N
0	101	5	CIUDAD BOLIVAR	\N
2	101	5	SAN BERNARDO DE LOS FARALLONES	\N
6	101	5	ALFONSO LOPEZ (SAN GREGORIO)	\N
8	101	5	VILLA ALEGRIA	\N
10	101	5	EL CABRERO - BOLIVAR ARRIBA	\N
0	107	5	BRICENO	\N
1	107	5	BERLIN (PUEBLO NUEVO)	\N
2	107	5	EL ROBLAL	\N
3	107	5	LAS AURAS	\N
4	107	5	TRAVESIAS	\N
0	113	5	BURITICA	\N
1	113	5	EL NARANJO	\N
2	113	5	GUARCO	\N
3	113	5	TABACAL	\N
4	113	5	LLANOS DE URARCO	\N
5	113	5	LA ANGELINA	\N
8	113	5	LA MARIELA	\N
0	120	5	CACERES	\N
2	120	5	EL JARDIN (TAMANA)	\N
3	120	5	GUARUMO	\N
4	120	5	MANIZALES	\N
6	120	5	PUERTO BELGICA	\N
10	120	5	PIAMONTE	\N
12	120	5	RIO MAN	\N
13	120	5	LAS PAMPAS	\N
14	120	5	NICARAGUA	\N
15	120	5	PUERTO SANTO	\N
0	125	5	CAICEDO	\N
0	129	5	CALDAS	\N
1	129	5	EL CANO	\N
2	129	5	LA RAYA	\N
4	129	5	LA MIEL	\N
5	129	5	LA CORRALITA	\N
6	129	5	LA PRIMAVERA	\N
7	129	5	EL RAIZAL	\N
8	129	5	LA CLARA	\N
9	129	5	LA QUIEBRA	\N
10	129	5	LA SALADA PARTE BAJA	\N
11	129	5	LA TOLVA	\N
12	129	5	LA VALERIA	\N
13	129	5	LA AGUACATALA	\N
15	129	5	LA CHUSCALA	\N
16	129	5	SALINAS	\N
0	134	5	CAMPAMENTO	\N
1	134	5	LA CHIQUITA	\N
2	134	5	LA SOLITA	\N
3	134	5	LLANADAS	\N
0	138	5	CANASGORDAS	\N
1	138	5	BUENOS AIRES - PARTE ALTA	\N
2	138	5	CESTILLAL	\N
3	138	5	JUNTAS DE URAMITA	\N
5	138	5	SAN PASCUAL	\N
9	138	5	VILLA VICTORIA	\N
0	142	5	CARACOLI	\N
0	145	5	CARAMANTA	\N
1	145	5	ALEGRIAS	\N
2	145	5	SUCRE	\N
0	147	5	CAREPA	\N
3	147	5	PIEDRAS BLANCAS	\N
4	147	5	ZUNGO EMBARCADERO - PUEBLO NUEVO	\N
5	147	5	ZUNGO EMBARCADERO - 11 DE NOVIEMBRE	\N
6	147	5	CASA VERDE	\N
7	147	5	EL ENCANTO	\N
8	147	5	ZUNGO EMBARCADERO - 28 DE OCTUBRE	\N
9	147	5	BELENCITO	\N
10	147	5	BOSQUES DE LOS ALMENDROS	\N
11	147	5	CAREPITA CANALUNO	\N
12	147	5	SACRAMENTO LA LUCHA	\N
13	147	5	LOS NARANJALES	\N
14	147	5	VIJAGUAL	\N
0	148	5	EL CARMEN DE VIBORAL	\N
3	148	5	AGUAS CLARAS	\N
5	148	5	LA CHAPA	\N
6	148	5	CAMPO ALEGRE	\N
8	148	5	LA AURORA - LAS BRISAS	\N
0	150	5	CAROLINA DEL PRINCIPE	\N
0	154	5	CAUCASIA	\N
3	154	5	CUTURU	\N
6	154	5	MARGENTO	\N
8	154	5	PALANCA	\N
9	154	5	PALOMAR	\N
12	154	5	SANTA ROSITA	\N
20	154	5	PUERTO TRIANA	\N
22	154	5	LA ILUSION	\N
23	154	5	CACERI	\N
24	154	5	EL PANDO	\N
25	154	5	CAMPO ALEGRE	\N
26	154	5	EL CHINO	\N
27	154	5	LA ESMERALDA	\N
31	154	5	VILLA DEL SOCORRO	\N
32	154	5	CASERIO CONJUNTO CANA FISTULA	\N
33	154	5	PUEBLO NUEVO	\N
0	172	5	CHIGORODO	\N
3	172	5	BARRANQUILLITA	\N
7	172	5	GUAPA CARRETERAS	\N
13	172	5	JURADO	\N
14	172	5	CAMPITAS	\N
15	172	5	CHAMPITA SECTOR LA GRANJA	\N
0	190	5	CISNEROS	\N
0	197	5	COCORNA	\N
5	197	5	LA PINUELA	\N
13	197	5	EL MOLINO	\N
0	206	5	CONCEPCION	\N
0	209	5	CONCORDIA	\N
1	209	5	EL SOCORRO	\N
6	209	5	EL GOLPE	\N
7	209	5	SALAZAR	\N
0	212	5	COPACABANA	\N
5	212	5	EL SALADO	\N
8	212	5	CABUYAL	\N
17	212	5	EL LLANO	\N
0	234	5	DABEIBA	\N
4	234	5	SAN JOSE DE URAMA	\N
8	234	5	ARMENIA - CAMPARRUSIA	\N
10	234	5	LAS CRUCES DE URAMA	\N
12	234	5	CHIMIADO LLANO GRANDE	\N
14	234	5	EL BOTON	\N
17	234	5	BETANIA PUENTE NUEVO	\N
18	234	5	CARA COLON	\N
19	234	5	LA BALSITA	\N
0	237	5	DONMATIAS	\N
1	237	5	BELLAVISTA	\N
3	237	5	ARENALES	\N
4	237	5	MONTERA	\N
5	237	5	PRADERA	\N
0	240	5	EBEJICO	\N
1	240	5	BRASIL	\N
3	240	5	SEVILLA	\N
8	240	5	EL ZARZAL	\N
11	240	5	LA CLARA	\N
12	240	5	FATIMA	\N
0	250	5	EL BAGRE	\N
2	250	5	PUERTO CLAVER	\N
4	250	5	PUERTO LOPEZ	\N
5	250	5	EL REAL	\N
6	250	5	LA CORONA	\N
7	250	5	LAS NEGRITAS	\N
8	250	5	LAS SARDINAS EL PUENTE	\N
9	250	5	SANTA BARBARA	\N
10	250	5	MUQUI	\N
11	250	5	BORRACHERA	\N
12	250	5	CANO CLARO	\N
13	250	5	LOS ALMENDROS	\N
0	264	5	ENTRERRIOS	\N
0	266	5	ENVIGADO	\N
1	266	5	LAS PALMAS	\N
5	266	5	EL CRISTO	\N
6	266	5	EL CHINGUI  2	\N
7	266	5	LA ULTIMA COPA	\N
8	266	5	PARCELACION LA ACUARELA	\N
9	266	5	PARCELACION ALAMOS DEL ESCOBERO	\N
10	266	5	PARCELACION ALDEA DE PALMA VERDE	\N
11	266	5	PARCELACION CONDOMINIO CAMPESTRE SERRANIA	\N
12	266	5	PARCELACION CASAS BELLO MONTE	\N
13	266	5	PARCELACION FIORE CASAS DE CAMPO	\N
14	266	5	PARCELACION CONJUNTO RESIDENCIAL BELLA TIERRA	\N
15	266	5	PARCELACION ENCENILLOS	\N
16	266	5	PARCELACION ESCOBERO	\N
17	266	5	PARCELACION HACIENDA ARRAYANES	\N
18	266	5	PARCELACION LAS PALMITAS	\N
19	266	5	PARCELACION LEMONT	\N
20	266	5	PARCELACION PRADO LARGO	\N
21	266	5	PARCELACION SAN SEBASTIAN	\N
22	266	5	PARCELACION TORRE LUNERA	\N
23	266	5	PARCELACION URBANIZACION PAPIROS	\N
24	266	5	PARCELACION VERANDA	\N
25	266	5	PARCELACION VILLAS DE LA CANDELARIA	\N
0	282	5	FREDONIA	\N
2	282	5	LOS PALOMOS	\N
3	282	5	MINAS	\N
4	282	5	PUENTE IGLESIAS	\N
5	282	5	MARSELLA	\N
8	282	5	EL ZANCUDO	\N
0	284	5	FRONTINO	\N
1	284	5	CARAUTA	\N
4	284	5	MURRI - LA BLANQUITA	\N
5	284	5	MUSINGA - TABLADITO	\N
6	284	5	NUTIBARA	\N
7	284	5	PONTON	\N
10	284	5	SAN LAZARO	\N
12	284	5	JENGAMECODA	\N
0	306	5	GIRALDO	\N
1	306	5	MANGLAR	\N
0	308	5	GIRARDOTA	\N
2	308	5	SAN ANDRES	\N
4	308	5	LA PALMA	\N
9	308	5	CABILDO	\N
10	308	5	LAS CUCHILLAS	\N
11	308	5	JAMUNDI - ESCUELAS	\N
12	308	5	JUAN COJO	\N
13	308	5	LA CALLE	\N
14	308	5	SAN ESTEBAN	\N
15	308	5	EL PARAISO	\N
16	308	5	JAMUNDI - RIELES	\N
17	308	5	LOMA DE LOS OCHOA	\N
0	310	5	GOMEZ PLATA	\N
1	310	5	EL SALTO	\N
2	310	5	SAN MATIAS	\N
4	310	5	VEGA DE BOTERO	\N
0	313	5	GRANADA	\N
1	313	5	SANTA ANA	\N
4	313	5	LOS MEDIOS	\N
0	315	5	GUADALUPE	\N
2	315	5	GUANTEROS	\N
7	315	5	GUADALUPE IV	\N
8	315	5	BARRIO NUEVO	\N
9	315	5	EL MACHETE	\N
0	318	5	GUARNE	\N
6	318	5	CHAPARRAL	\N
7	318	5	SAN IGNACIO	\N
0	321	5	GUATAPE	\N
1	321	5	EL ROBLE	\N
0	347	5	HELICONIA	\N
1	347	5	ALTO DEL CORRAL	\N
2	347	5	PUEBLITO	\N
3	347	5	LLANOS DE SAN JOSE	\N
9	347	5	GUAMAL	\N
0	353	5	HISPANIA	\N
0	360	5	ITAGUI	\N
1	360	5	LOS GOMEZ	\N
3	360	5	EL AJIZAL	\N
6	360	5	EL PEDREGAL	\N
12	360	5	EL PORVENIR	\N
13	360	5	EL PROGRESO	\N
14	360	5	LA MARIA	\N
0	361	5	ITUANGO	\N
2	361	5	EL ARO - BUILOPOLIS	\N
3	361	5	LA GRANJA	\N
4	361	5	PASCUITA	\N
5	361	5	SANTA ANA	\N
6	361	5	SANTA LUCIA	\N
7	361	5	SANTA RITA	\N
18	361	5	PIO X	\N
0	364	5	JARDIN	\N
1	364	5	CRISTIANIA	\N
2	364	5	LA ARBOLEDA - LAS MACANAS	\N
6	364	5	QUEBRADA BONITA	\N
0	368	5	JERICO	\N
5	368	5	GUACAMAYAL	\N
7	368	5	LOS PATIOS	\N
0	376	5	LA CEJA	\N
2	376	5	EL TAMBO	\N
3	376	5	SAN JOSE	\N
5	376	5	SAN NICOLAS	\N
6	376	5	SAN JUDAS	\N
8	376	5	TOLEDO	\N
0	380	5	LA ESTRELLA	\N
7	380	5	LA TABLACITA	\N
8	380	5	SAGRADA FAMILIA	\N
9	380	5	SAN JOSE - MELEGUINDO	\N
10	380	5	LA RAYA	\N
11	380	5	SAN ISIDRO	\N
12	380	5	TARAPACA	\N
13	380	5	SAN MIGUEL	\N
19	380	5	LA BERMEJALA	\N
21	380	5	PENAS BLANCAS	\N
0	390	5	LA PINTADA	\N
1	390	5	LA BOCANA	\N
0	400	5	LA UNION	\N
1	400	5	MESOPOTAMIA	\N
4	400	5	LA CONCHA	\N
0	411	5	LIBORINA	\N
1	411	5	EL CARMEN - LA VENTA	\N
2	411	5	LA HONDA	\N
3	411	5	LA MERCED (PLAYON)	\N
4	411	5	SAN DIEGO (PLACITA)	\N
5	411	5	CURITI	\N
7	411	5	CRISTOBAL	\N
12	411	5	PORVENIR	\N
13	411	5	PROVINCIAL	\N
0	425	5	MACEO	\N
1	425	5	LA SUSANA	\N
3	425	5	LA FLORESTA	\N
7	628	5	MEMBRILLAL	\N
5	425	5	SAN JOSE DEL NUS (JOSE DE NUESTRA SENORA)	\N
0	440	5	MARINILLA	\N
0	467	5	MONTEBELLO	\N
1	467	5	SABALETAS	\N
6	467	5	LA GRANJA	\N
8	467	5	PIEDRA GALANA	\N
0	475	5	MURINDO	\N
1	475	5	OPOGADO	\N
5	475	5	JEDEGA	\N
6	475	5	TADIA	\N
7	475	5	BEBARAMENO	\N
0	480	5	MUTATA	\N
1	480	5	BEJUQUILLO	\N
2	480	5	PAVARANDOCITO	\N
3	480	5	VILLA ARTEAGA	\N
4	480	5	PAVARANDO GRANDE	\N
6	480	5	CAUCHERAS	\N
0	483	5	NARINO	\N
1	483	5	PUERTO VENUS	\N
0	490	5	NECOCLI	\N
1	490	5	EL TOTUMO	\N
2	490	5	MULATOS	\N
3	490	5	PUEBLO NUEVO	\N
4	490	5	ZAPATA	\N
5	490	5	CARIBIA	\N
6	490	5	VEREDA CASA BLANCA	\N
7	490	5	VEREDA EL BOBAL	\N
8	490	5	LAS CHANGAS	\N
9	490	5	EL MELLITO	\N
10	490	5	BRISAS DEL RIO	\N
11	490	5	CARLOS ARRIBA	\N
12	490	5	EL VOLAO	\N
13	490	5	LA COMARCA	\N
14	490	5	LOMA DE PIEDRA	\N
15	490	5	MELLO VILLAVICENCIO	\N
16	490	5	TULAPITA	\N
18	490	5	VALE PAVA	\N
0	495	5	NECHI	\N
1	495	5	BIJAGUAL	\N
2	495	5	COLORADO	\N
3	495	5	LA CONCHA	\N
4	495	5	LAS FLORES	\N
5	495	5	CARGUEROS	\N
0	501	5	OLAYA	\N
1	501	5	LLANADAS	\N
2	501	5	SUCRE	\N
4	501	5	QUEBRADA SECA	\N
0	541	5	PENOL	\N
0	543	5	PEQUE	\N
1	543	5	BARBACOAS	\N
3	543	5	LOS LLANOS	\N
5	543	5	TOLDAS	\N
0	576	5	PUEBLORRICO	\N
0	579	5	PUERTO BERRIO	\N
1	579	5	PUERTO MURILLO	\N
2	579	5	VIRGINIAS	\N
3	579	5	CABANAS	\N
4	579	5	EL BRASIL	\N
5	579	5	LA CRISTALINA	\N
9	579	5	MALENA	\N
10	579	5	CALERA	\N
11	579	5	BODEGAS	\N
12	579	5	DORADO - CALAMAR	\N
13	579	5	LA CARLOTA	\N
14	579	5	MINAS DEL VAPOR	\N
15	579	5	SANTA MARTINA	\N
0	585	5	PUERTO NARE	\N
1	585	5	ARABIA	\N
2	585	5	LOS DELIRIOS	\N
3	585	5	LA SIERRA	\N
4	585	5	LA UNION	\N
6	585	5	LA PESCA	\N
8	585	5	LAS ANGELITAS	\N
9	585	5	LA CLARA	\N
10	585	5	EL PRODIGIO	\N
0	591	5	PUERTO TRIUNFO	\N
2	591	5	PUERTO PERALES NUEVO	\N
3	591	5	ESTACION COCORNA	\N
4	591	5	DORADAL	\N
5	591	5	LA MERCEDES	\N
7	591	5	ESTACION PITA	\N
8	591	5	LA FLORIDA	\N
9	591	5	SANTIAGO BERRIO	\N
10	591	5	EL ALTO DEL POLLO	\N
11	591	5	TRES RANCHOS	\N
0	604	5	REMEDIOS	\N
1	604	5	LA CRUZADA	\N
3	604	5	SANTA ISABEL	\N
5	604	5	OTU	\N
7	604	5	CANAVERAL	\N
8	604	5	MARTANA	\N
9	604	5	RIO BAGRE	\N
10	604	5	CAMPO VIJAO	\N
11	604	5	CHORRO DE LAGRIMAS	\N
0	607	5	RETIRO	\N
3	607	5	ALTO DE CARRIZALES	\N
4	607	5	DON DIEGO	\N
5	607	5	EL CHUSCAL LA CAMPANITA	\N
7	607	5	LOS SALADOS	\N
9	607	5	EL PORTENTO	\N
13	607	5	CARRIZALES LA BORRASCOSA	\N
0	615	5	RIONEGRO	\N
2	615	5	EL TABLAZO	\N
9	615	5	CABECERAS DE LLANO GRANDE	\N
13	615	5	PONTEZUELA	\N
14	615	5	ALTO BONITO	\N
17	615	5	LA MOSCA	\N
25	615	5	BARRO BLANCO	\N
26	615	5	CONDOMINIO CAMPESTRE LAGO GRANDE	\N
27	615	5	CONDOMINIO EL REMANSO	\N
28	615	5	CONDOMINIO SIERRAS DE MAYORI	\N
29	615	5	CONDOMINIO VILLAS DE LLANO GRANDE	\N
30	615	5	GALICIA PARTE ALTA	\N
31	615	5	GALICIA PARTE BAJA	\N
32	615	5	JAMAICA PARCELACION CAMPESTRE	\N
33	615	5	PARCELACION AGUA LUNA DE ORIENTE	\N
34	615	5	PARCELACION ANDALUCIA	\N
35	615	5	PARCELACION CAMELOT	\N
36	615	5	PARCELACION COCUYO	\N
37	615	5	PARCELACION COLINAS DE PAIMADO	\N
38	615	5	PARCELACION CONJUNTO CAMPESTRE LLANO GRANDE	\N
39	615	5	PARCELACION LA QUERENCIA	\N
40	615	5	PARCELACION LAS BRUMAS	\N
41	615	5	PARCELACION LLANOS DE NORMANDIA	\N
42	615	5	PARCELACION NORMANDIA	\N
43	615	5	PARCELACION SANTA MARIA DEL LLANO	\N
44	615	5	PARCELACION SIERRA ALTA	\N
45	615	5	PARCELACION TORRE MOLINOS	\N
46	615	5	PARCELACION TOSCANA	\N
47	615	5	PARCELACION VEGAS DE GUADALCANAL	\N
0	628	5	SABANALARGA	\N
1	628	5	EL JUNCO	\N
2	628	5	EL ORO	\N
4	628	5	EL SOCORRO	\N
0	631	5	SABANETA	\N
1	631	5	MARIA AUXILIADORA	\N
2	631	5	CANAVERALEJO	\N
6	631	5	SAN ISIDRO	\N
7	631	5	LA INMACULADA	\N
8	631	5	PAN DE AZUCAR	\N
9	631	5	LA DOCTORA	\N
10	631	5	LAS LOMITAS	\N
13	631	5	LOMA DE LOS HENAO	\N
0	642	5	SALGAR	\N
1	642	5	EL CONCILIO	\N
2	642	5	LA CAMARA	\N
3	642	5	LA MARGARITA	\N
10	642	5	PENALISA	\N
0	647	5	SAN ANDRES DE CUERQUIA	\N
0	649	5	SAN CARLOS	\N
1	649	5	EL JORDAN	\N
2	649	5	SAMANA	\N
5	649	5	PUERTO GARZA	\N
12	649	5	DOS QUEBRADAS	\N
13	649	5	LA HONDITA	\N
14	649	5	VALLEJUELO	\N
0	652	5	SAN FRANCISCO	\N
1	652	5	AQUITANIA	\N
8	652	5	PAILANIA	\N
10	652	5	RIO CLARO	\N
0	656	5	SAN JERONIMO	\N
6	656	5	MESTIZAL	\N
7	656	5	POLEAL	\N
11	656	5	EL POMAR	\N
0	658	5	SAN JOSE DE LA MONTANA	\N
0	659	5	SAN JUAN DE URABA	\N
1	659	5	DAMAQUIEL	\N
2	659	5	SAN JUANCITO	\N
3	659	5	UVEROS	\N
4	659	5	SIETE VUELTAS	\N
5	659	5	SAN NICOLAS DEL RIO	\N
0	660	5	SAN LUIS	\N
1	660	5	EL SILENCIO PERLA VERDE	\N
6	660	5	EL PRODIGIO	\N
7	660	5	BUENOS AIRES	\N
11	660	5	MONTELORO (LA JOSEFINA)	\N
12	660	5	LA TEBAIDA	\N
13	660	5	SOPETRAN	\N
14	660	5	EL SILENCIO - EL VENTIADERO	\N
15	660	5	MONTELORO	\N
0	664	5	SAN PEDRO DE LOS MILAGROS	\N
5	664	5	OVEJAS	\N
0	665	5	SAN PEDRO DE URABA	\N
2	665	5	SANTA CATALINA	\N
3	665	5	ARENAS MONAS	\N
4	665	5	ZAPINDONGA	\N
8	665	5	EL TOMATE	\N
0	667	5	SAN RAFAEL	\N
1	667	5	SAN JULIAN	\N
0	670	5	SAN ROQUE	\N
1	670	5	CRISTALES	\N
2	670	5	PROVIDENCIA	\N
3	670	5	SAN JOSE DEL NUS	\N
0	674	5	SAN VICENTE	\N
1	674	5	CORRIENTES	\N
0	679	5	SANTA BARBARA	\N
1	679	5	DAMASCO	\N
4	679	5	VERSALLES	\N
9	679	5	YARUMALITO	\N
10	679	5	LA LIBORIANA	\N
11	679	5	ZARCITOS PARTE ALTA	\N
0	686	5	SANTA ROSA DE OSOS	\N
1	686	5	ARAGON	\N
3	686	5	HOYORRICO	\N
4	686	5	SAN ISIDRO	\N
6	686	5	SAN PABLO	\N
8	686	5	RIO GRANDE	\N
0	690	5	SANTO DOMINGO	\N
1	690	5	BOTERO	\N
3	690	5	PORCECITO	\N
4	690	5	SANTIAGO	\N
5	690	5	VERSALLES	\N
0	697	5	EL SANTUARIO	\N
0	736	5	SEGOVIA	\N
1	736	5	FRAGUAS	\N
2	736	5	PUERTO CALAVERA	\N
3	736	5	CAMPO ALEGRE	\N
4	736	5	EL CENIZO	\N
5	736	5	EL CRISTO	\N
6	736	5	EL CHISPERO	\N
7	736	5	LA CALIENTE	\N
0	756	5	SONSON	\N
1	756	5	ALTO DE SABANAS	\N
5	756	5	SAN MIGUEL	\N
30	756	5	LA DANTA	\N
33	756	5	JERUSALEN	\N
0	761	5	SOPETRAN	\N
1	761	5	CORDOBA	\N
3	761	5	HORIZONTES	\N
5	761	5	SAN NICOLAS	\N
11	761	5	LA MIRANDA	\N
12	761	5	SANTA BARBARA	\N
0	789	5	TAMESIS	\N
1	789	5	PALERMO	\N
2	789	5	SAN PABLO	\N
0	790	5	TARAZA	\N
1	790	5	BARRO BLANCO	\N
2	790	5	EL DOCE	\N
3	790	5	PUERTO ANTIOQUIA	\N
4	790	5	LA CAUCANA	\N
5	790	5	EL GUAIMARO	\N
6	790	5	PIEDRAS	\N
0	792	5	TARSO	\N
3	792	5	TOCA MOCHO	\N
6	792	5	EL CEDRON	\N
0	809	5	TITIRIBI	\N
1	809	5	LA MESETA	\N
2	809	5	ALBANIA	\N
3	809	5	OTRAMINA	\N
4	809	5	SITIO VIEJO	\N
14	809	5	PORVENIR	\N
16	809	5	PUERTO ESCONDIDO	\N
18	809	5	VOLCAN	\N
0	819	5	TOLEDO	\N
1	819	5	BUENAVISTA	\N
2	819	5	EL VALLE	\N
5	819	5	EL BRUGO	\N
0	837	5	TURBO, DISTRITO PORTUARIO, LOGISTICO, INDUSTRIAL, TURISTICO Y COMERCIAL	\N
1	837	5	CURRULAO	\N
2	837	5	NUEVA COLONIA	\N
3	837	5	EL TRES	\N
5	837	5	SAN VICENTE DEL CONGO	\N
6	837	5	TIE	\N
7	837	5	LOMAS AISLADAS	\N
8	837	5	RIO GRANDE	\N
9	837	5	BOCAS DEL RIO ATRATO	\N
10	837	5	EL DOS	\N
12	837	5	PUEBLO BELLO	\N
13	837	5	SAN JOSE DE MULATOS	\N
14	837	5	PUERTO RICO	\N
18	837	5	NUEVO ANTIOQUIA	\N
20	837	5	ALTO DE MULATOS	\N
23	837	5	CASANOVA	\N
24	837	5	LAS GARZAS	\N
25	837	5	VILLA MARIA	\N
26	837	5	CODELSA	\N
27	837	5	EL PORVENIR	\N
28	837	5	NUEVA GRANADA	\N
29	837	5	PUNTA DE PIEDRA	\N
30	837	5	AMSTERCOL I	\N
31	837	5	AMSTERCOL II	\N
32	837	5	CIRILO	\N
33	837	5	CONGO ARRIBA	\N
34	837	5	EL UNO	\N
35	837	5	GUADUALITO	\N
36	837	5	LAS BABILLAS	\N
37	837	5	LOS ENAMORADOS	\N
38	837	5	MAKENCAL	\N
39	837	5	PIEDRECITAS	\N
40	837	5	SANTIAGO DE URABA	\N
41	837	5	SIETE DE AGOSTO	\N
42	837	5	SINAI	\N
43	837	5	EL ROTO	\N
0	842	5	URAMITA	\N
3	842	5	EL PITAL	\N
4	842	5	EL MADERO	\N
5	842	5	LIMON CHUPADERO	\N
0	847	5	URRAO	\N
3	847	5	LA ENCARNACION	\N
17	847	5	SAN JOSE	\N
0	854	5	VALDIVIA	\N
2	854	5	PUERTO VALDIVIA	\N
3	854	5	RAUDAL VIEJO	\N
10	854	5	PUERTO RAUDAL	\N
0	856	5	VALPARAISO	\N
0	858	5	VEGACHI	\N
1	858	5	EL TIGRE	\N
3	858	5	EL CINCO	\N
0	861	5	VENECIA	\N
2	861	5	BOLOMBOLO	\N
6	861	5	PALENQUE	\N
8	861	5	LA AMALIA	\N
10	861	5	VENTIADERO	\N
0	873	5	VIGIA DEL FUERTE	\N
1	873	5	SAN ANTONIO DE PADUA	\N
2	873	5	VEGAEZ	\N
4	873	5	SAN ALEJANDRO	\N
5	873	5	SAN MIGUEL	\N
6	873	5	PUERTO ANTIOQUIA	\N
7	873	5	BUCHADO	\N
9	873	5	PALO BLANCO	\N
10	873	5	BAJO MURRI	\N
11	873	5	EL ARENAL	\N
12	873	5	GUADUALITO	\N
13	873	5	LOMA MURRY	\N
14	873	5	SAN MARTIN	\N
15	873	5	SANTA MARIA	\N
0	885	5	YALI	\N
27	885	5	VILLA ANITA	\N
0	887	5	YARUMAL	\N
3	887	5	CEDENO	\N
4	887	5	EL CEDRO	\N
6	887	5	OCHALI	\N
7	887	5	LLANOS DE CUIVA	\N
9	887	5	EL PUEBLITO	\N
21	887	5	LA LOMA	\N
22	887	5	MINA VIEJA	\N
0	890	5	YOLOMBO	\N
1	890	5	LA FLORESTA	\N
4	890	5	EL RUBI	\N
9	890	5	VILLANUEVA	\N
0	893	5	YONDO	\N
1	893	5	CIENAGA DE BARBACOA - LA PUNTA	\N
2	893	5	SAN LUIS BELTRAN	\N
3	893	5	SAN MIGUEL DEL TIGRE	\N
4	893	5	CUATRO BOCAS	\N
5	893	5	BOCAS DE SAN FRANCISCO	\N
8	893	5	EL BAGRE	\N
9	893	5	BOCAS DE BARBACOAS	\N
14	893	5	PUERTO LOS MANGOS	\N
15	893	5	PUERTO MATILDE	\N
17	893	5	PUERTO TOMAS	\N
18	893	5	PUERTO CASABE	\N
19	893	5	LA CONDOR	\N
20	893	5	LA RINCONADA	\N
21	893	5	CHORRO DE LAGRIMAS	\N
0	895	5	ZARAGOZA	\N
3	895	5	BUENOS AIRES	\N
4	895	5	PATO	\N
8	895	5	VEGAS DE SEGOVIA	\N
9	895	5	EL CENIZO	\N
10	895	5	EL CRISTO	\N
11	895	5	LA CALIENTE	\N
0	1	8	BARRANQUILLA, DISTRITO ESPECIAL, INDUSTRIAL Y PORTUARIO	\N
0	78	8	BARANOA	\N
1	78	8	CAMPECHE	\N
2	78	8	PITAL	\N
3	78	8	SIBARCO	\N
0	137	8	CAMPO DE LA CRUZ	\N
1	137	8	BOHORQUEZ	\N
0	141	8	CANDELARIA	\N
1	141	8	SAN JOSE DEL CARRETAL	\N
2	141	8	BUENAVENTURA DE LENA	\N
0	296	8	GALAPA	\N
1	296	8	PALUATO	\N
0	372	8	JUAN DE ACOSTA	\N
1	372	8	BOCATOCINO	\N
2	372	8	CHORRERA	\N
3	372	8	SAN JOSE DE SACO	\N
4	372	8	SANTA VERONICA	\N
7	372	8	URBANIZACION PUNTA CANGREJO	\N
0	421	8	LURUACO	\N
1	421	8	ARROYO DE PIEDRA	\N
2	421	8	PALMAR DE CANDELARIA	\N
3	421	8	LOS PENDALES	\N
4	421	8	SAN JUAN DE TOCAGUA	\N
5	421	8	SANTA CRUZ	\N
6	421	8	LOS LIMITES	\N
7	421	8	LA PUNTICA	\N
12	421	8	BARRIGON	\N
13	421	8	SOCAVON	\N
0	433	8	MALAMBO	\N
1	433	8	CARACOLI	\N
4	433	8	LA AGUADA	\N
5	433	8	PITALITO	\N
0	436	8	MANATI	\N
1	436	8	EL PORVENIR (LAS COMPUERTAS)	\N
2	436	8	VILLA JUANA	\N
0	520	8	PALMAR DE VARELA	\N
1	520	8	BURRUSCOS	\N
0	549	8	PIOJO	\N
1	549	8	AGUAS VIVAS	\N
2	549	8	EL CERRITO	\N
3	549	8	HIBACHARO	\N
4	549	8	PUNTA ASTILLEROS	\N
0	558	8	POLONUEVO	\N
1	558	8	PITAL DEL CARLIN (PITALITO)	\N
0	560	8	PONEDERA	\N
1	560	8	LA RETIRADA	\N
2	560	8	MARTILLO	\N
3	560	8	PUERTO GIRALDO	\N
4	560	8	SANTA RITA	\N
7	560	8	CASCAJAL	\N
0	573	8	PUERTO COLOMBIA	\N
2	573	8	SALGAR	\N
3	573	8	SABANILLA (MONTE CARMELO)	\N
0	606	8	REPELON	\N
1	606	8	ARROYO NEGRO	\N
2	606	8	CIEN PESOS	\N
3	606	8	LAS TABLAS	\N
4	606	8	ROTINET	\N
5	606	8	VILLA ROSA	\N
6	606	8	EL PORVENIR (LAS COMPUERTAS)	\N
9	606	8	PITA	\N
0	634	8	SABANAGRANDE	\N
0	638	8	SABANALARGA	\N
1	638	8	AGUADA DE PABLO	\N
2	638	8	CASCAJAL	\N
3	638	8	COLOMBIA	\N
4	638	8	ISABEL LOPEZ	\N
5	638	8	LA PENA	\N
6	638	8	MOLINERO	\N
7	638	8	MIRADOR	\N
8	638	8	GALLEGO	\N
10	638	8	PATILLA	\N
0	675	8	SANTA LUCIA	\N
1	675	8	ALGODONAL	\N
0	685	8	SANTO TOMAS	\N
0	758	8	SOLEDAD	\N
0	770	8	SUAN	\N
0	832	8	TUBARA	\N
1	832	8	CUATRO BOCAS	\N
2	832	8	EL MORRO	\N
3	832	8	GUAIMARAL	\N
4	832	8	JUARUCO	\N
7	832	8	CORRAL DE SAN LUIS	\N
10	832	8	PLAYA MENDOZA	\N
11	832	8	PLAYAS DE EDRIMAN	\N
12	832	8	VILLAS DE PALMARITO	\N
13	832	8	NUEVA ESPERANZA	\N
14	832	8	PUERTO CAIMAN	\N
0	849	8	USIACURI	\N
0	1	11	BOGOTA, DISTRITO CAPITAL	\N
2	1	11	NAZARETH	\N
7	1	11	PASQUILLA	\N
8	1	11	SAN JUAN	\N
9	1	11	BETANIA	\N
10	1	11	LA UNION	\N
11	1	11	MOCHUELO ALTO	\N
12	1	11	CHORRILLOS	\N
13	1	11	EL DESTINO	\N
14	1	11	NUEVA GRANADA	\N
15	1	11	QUIBA BAJO	\N
16	1	11	SANTO DOMINGO	\N
17	1	11	TIERRA NUEVA	\N
0	1	13	CARTAGENA DE INDIAS, DISTRITO TURISTICO Y CULTURAL	\N
1	1	13	ARROYO DE PIEDRA	\N
2	1	13	ARROYO GRANDE	\N
3	1	13	BARU	\N
4	1	13	BAYUNCA	\N
5	1	13	BOCACHICA	\N
6	1	13	CANO DEL ORO	\N
7	1	13	ISLA FUERTE	\N
8	1	13	LA BOQUILLA	\N
9	1	13	PASACABALLOS	\N
10	1	13	PUNTA CANOA	\N
12	1	13	SANTA ANA	\N
13	1	13	TIERRA BOMBA	\N
14	1	13	PUNTA ARENA	\N
15	1	13	ARARCA	\N
16	1	13	LETICIA	\N
17	1	13	SANTA CRUZ DEL ISLOTE (ARCHIPIELAGO DE SAN BERNARDO)	\N
18	1	13	EL RECREO	\N
19	1	13	PUERTO REY	\N
20	1	13	PONTEZUELA	\N
26	1	13	ARROYO DE LAS CANOAS	\N
27	1	13	EL PUEBLITO	\N
28	1	13	LAS EUROPAS	\N
29	1	13	MANZANILLO DEL MAR	\N
30	1	13	TIERRA BAJA	\N
33	1	13	MEMBRILLAL	\N
34	1	13	BARCELONA DE INDIAS	\N
35	1	13	CARTAGENA LAGUNA CLUB	\N
36	1	13	CASAS DEL MAR	\N
37	1	13	MUCURA	\N
38	1	13	PUERTO BELLO	\N
0	6	13	ACHI	\N
2	6	13	BOYACA	\N
3	6	13	BUENAVISTA	\N
5	6	13	ALGARROBO	\N
7	6	13	GUACAMAYO	\N
11	6	13	PLAYA ALTA	\N
15	6	13	TACUYA ALTA	\N
17	6	13	TRES CRUCES	\N
19	6	13	PAYANDE	\N
20	6	13	RIO NUEVO	\N
21	6	13	BUENOS AIRES	\N
22	6	13	PUERTO ISABEL	\N
30	6	13	CENTRO ALEGRE	\N
33	6	13	PUERTO VENECIA	\N
35	6	13	SANTA LUCIA	\N
39	6	13	LOS NISPEROS	\N
41	6	13	PARAISO SINCERIN	\N
42	6	13	PROVIDENCIA	\N
0	30	13	ALTOS DEL ROSARIO	\N
1	30	13	EL RUBIO	\N
2	30	13	LA PACHA	\N
3	30	13	SAN ISIDRO	\N
4	30	13	SANTA LUCIA	\N
5	30	13	SAN ISIDRO 2	\N
0	42	13	ARENAL	\N
1	42	13	BUENAVISTA	\N
2	42	13	CARNIZALA	\N
3	42	13	SAN RAFAEL	\N
7	42	13	SANTO DOMINGO	\N
0	52	13	ARJONA	\N
1	52	13	PUERTO BADEL (CANO SALADO)	\N
2	52	13	GAMBOTE	\N
3	52	13	ROCHA	\N
4	52	13	SINCERIN	\N
5	52	13	SAN RAFAEL DE LA CRUZ	\N
7	52	13	CONDOMINIO HACIENDA	\N
8	52	13	EL REMANSO	\N
0	62	13	ARROYOHONDO	\N
1	62	13	MACHADO	\N
2	62	13	MONROY	\N
3	62	13	PILON	\N
4	62	13	SATO	\N
6	62	13	SAN FRANCISCO (SOLABANDA)	\N
0	74	13	BARRANCO DE LOBA	\N
3	74	13	RIONUEVO	\N
4	74	13	SAN ANTONIO	\N
5	74	13	LOS CERRITOS	\N
6	74	13	LAS DELICIAS	\N
0	140	13	CALAMAR	\N
2	140	13	BARRANCA NUEVA	\N
3	140	13	BARRANCA VIEJA	\N
4	140	13	HATO VIEJO	\N
9	140	13	YUCAL	\N
11	140	13	EL PROGRESO	\N
0	160	13	CANTAGALLO	\N
1	160	13	SAN LORENZO	\N
2	160	13	BRISAS DE BOLIVAR	\N
8	160	13	LA ESPERANZA	\N
11	160	13	LA VICTORIA	\N
13	160	13	LEJANIAS	\N
15	160	13	LOS PATICOS	\N
17	160	13	NO HAY COMO DIOS	\N
19	160	13	SINZONA	\N
20	160	13	YANACUE	\N
21	160	13	BUENOS AIRES	\N
22	160	13	LA PENA	\N
23	160	13	PATICO BAJO	\N
24	160	13	CUATRO BOCAS	\N
0	188	13	CICUCO	\N
1	188	13	CAMPO SERENO	\N
2	188	13	LA PENA	\N
3	188	13	SAN FRANCISCO DE LOBA	\N
5	188	13	PUEBLO NUEVO	\N
6	188	13	BODEGA	\N
0	212	13	CORDOBA	\N
1	212	13	GUAIMARAL	\N
3	212	13	LA MONTANA DE ALONSO (MARTIN ALONSO)	\N
5	212	13	SAN ANDRES	\N
6	212	13	SINCELEJITO	\N
7	212	13	TACAMOCHITO	\N
8	212	13	TACAMOCHO	\N
9	212	13	SANTA LUCIA	\N
10	212	13	LA SIERRA	\N
11	212	13	LAS MARIAS	\N
12	212	13	PUEBLO NUEVO	\N
13	212	13	SANAHUARE	\N
14	212	13	SOCORRO 1	\N
15	212	13	BELLAVISTA	\N
16	212	13	LAS LOMITAS	\N
0	222	13	CLEMENCIA	\N
1	222	13	LAS CARAS	\N
2	222	13	EL PENIQUE	\N
0	244	13	EL CARMEN DE BOLIVAR	\N
1	244	13	BAJO GRANDE	\N
2	244	13	CARACOLI GRANDE	\N
3	244	13	EL SALADO	\N
4	244	13	JESUS DEL MONTE	\N
5	244	13	MACAYEPOS	\N
6	244	13	SAN CARLOS	\N
7	244	13	SAN ISIDRO	\N
8	244	13	HATO NUEVO	\N
11	244	13	EL RAIZAL	\N
14	244	13	SANTA LUCIA	\N
17	244	13	SANTO DOMINGO DE MEZA	\N
18	244	13	EL HOBO	\N
19	244	13	ARROYO ARENA	\N
20	244	13	LAZARO	\N
21	244	13	PADULA	\N
22	244	13	VERDUN	\N
0	248	13	EL GUAMO	\N
1	248	13	LA ENEA	\N
2	248	13	SAN JOSE DE LATA	\N
3	248	13	NERVITI	\N
4	248	13	ROBLES	\N
5	248	13	TASAJERA	\N
0	268	13	EL PENON	\N
3	268	13	BUENOS AIRES	\N
4	268	13	CASTANAL	\N
6	268	13	LA CHAPETONA	\N
8	268	13	JAPON	\N
13	268	13	LA HUMAREDA	\N
17	268	13	PENONCITO	\N
0	300	13	HATILLO DE LOBA	\N
1	300	13	EL POZON	\N
2	300	13	JUANA SANCHEZ	\N
3	300	13	LA RIBONA	\N
4	300	13	LA VICTORIA	\N
5	300	13	PUEBLO NUEVO	\N
6	300	13	SAN MIGUEL	\N
7	300	13	CERRO DE LAS AGUADAS	\N
8	300	13	LAS BRISAS	\N
9	300	13	GUALI	\N
10	300	13	LAS PALMAS	\N
0	430	13	MAGANGUE	\N
1	430	13	BARBOSA	\N
2	430	13	BARRANCA DE YUCA	\N
3	430	13	BETANIA	\N
4	430	13	BOCA DE SAN ANTONIO	\N
6	430	13	CASCAJAL	\N
7	430	13	CEIBAL	\N
8	430	13	COYONGAL	\N
9	430	13	EL RETIRO	\N
10	430	13	GUAZO	\N
11	430	13	HENEQUEN	\N
13	430	13	JUAN ARIAS	\N
14	430	13	LA PASCUALA	\N
15	430	13	LA VENTURA	\N
16	430	13	LAS BRISAS	\N
17	430	13	MADRID	\N
18	430	13	PALMARITO	\N
19	430	13	PANSEGUITA	\N
20	430	13	PINALITO	\N
21	430	13	SAN RAFAEL DE CORTINA	\N
22	430	13	SAN JOSE DE LAS MARTAS	\N
23	430	13	SAN SEBASTIAN DE BUENAVISTA	\N
24	430	13	SANTA FE	\N
25	430	13	SANTA LUCIA	\N
26	430	13	SANTA MONICA	\N
27	430	13	SANTA PABLA	\N
28	430	13	SITIO NUEVO	\N
29	430	13	PUERTO KENNEDY	\N
30	430	13	TACALOA	\N
31	430	13	TACASALUMA	\N
32	430	13	TOLU	\N
36	430	13	PLAYA DE LAS FLORES	\N
38	430	13	EL CUATRO	\N
39	430	13	BOCA DE GUAMAL	\N
40	430	13	TRES PUNTAS	\N
41	430	13	EMAUS	\N
47	430	13	PUERTO NARINO	\N
48	430	13	PUNTA DE CARTAGENA	\N
49	430	13	ROMA	\N
51	430	13	SAN ANTONITO	\N
52	430	13	SANTA COITA	\N
53	430	13	FLORENCIA	\N
0	433	13	MAHATES	\N
1	433	13	EVITAR	\N
2	433	13	GAMERO	\N
3	433	13	MALAGANA	\N
4	433	13	SAN BASILIO DE PALENQUE	\N
5	433	13	SAN JOAQUIN	\N
9	433	13	MANDINGA	\N
10	433	13	CRUZ DEL VIZO	\N
11	433	13	LA MANGA	\N
0	440	13	MARGARITA	\N
1	440	13	BOTON DE LEIVA	\N
2	440	13	CANTERA	\N
3	440	13	CAUSADO	\N
4	440	13	CHILLOA	\N
5	440	13	DONA JUANA	\N
7	440	13	MAMONCITO	\N
8	440	13	SANDOVAL	\N
10	440	13	SAN JOSE DE LOS TRAPICHES	\N
11	440	13	COROCITO	\N
12	440	13	GUATAQUITA	\N
14	440	13	CANO MONO	\N
17	440	13	LA MONTANA	\N
0	442	13	MARIA LA BAJA	\N
1	442	13	CORREA	\N
2	442	13	EL NISPERO	\N
3	442	13	FLAMENCO	\N
4	442	13	MANPUJAN	\N
5	442	13	NANGUMA	\N
6	442	13	RETIRO NUEVO	\N
7	442	13	SAN JOSE DEL PLAYON	\N
8	442	13	SAN PABLO	\N
9	442	13	EL MAJAGUA	\N
11	442	13	LOS BELLOS	\N
12	442	13	MATUYA	\N
14	442	13	COLU	\N
15	442	13	EL FLORIDO	\N
16	442	13	NUEVO RETEN	\N
17	442	13	ARROYO GRANDE	\N
19	442	13	NUEVA ESPERANZA	\N
20	442	13	PUEBLO NUEVO	\N
22	442	13	PRIMERO DE JULIO	\N
23	442	13	EL SENA	\N
24	442	13	LA CURVA	\N
25	442	13	LA PISTA	\N
26	442	13	MARQUEZ	\N
27	442	13	MUNGUIA	\N
30	442	13	CEDRITO	\N
31	442	13	EL GUAMO	\N
34	442	13	GUARISMO	\N
35	442	13	LA SUPREMA	\N
40	442	13	NUEVO PORVENIR	\N
43	442	13	SUCESION	\N
44	442	13	TOMA RAZON	\N
45	442	13	EL PUEBLITO	\N
0	458	13	MONTECRISTO	\N
1	458	13	BETANIA	\N
2	458	13	LA DORADA	\N
3	458	13	PARAISO	\N
4	458	13	PUEBLO LINDO	\N
5	458	13	PUEBLO NUEVO - REGENCIA	\N
6	458	13	PUERTO ESPANA	\N
7	458	13	PLATANAL	\N
8	458	13	SAN AGUSTIN	\N
12	458	13	VILLA URIBE	\N
0	468	13	SANTA CRUZ DE MOMPOX. DISTRITO ESPECIAL, TURISTICO, CULTURAL E HISTORICO	\N
1	468	13	CALDERA	\N
2	468	13	CANDELARIA	\N
8	468	13	GUAIMARAL	\N
9	468	13	GUATACA	\N
10	468	13	LA JAGUA	\N
11	468	13	LA LOBATA	\N
13	468	13	LA RINCONADA	\N
14	468	13	LAS BOQUILLAS	\N
15	468	13	LOMA DE SIMON	\N
16	468	13	LOS PINONES	\N
20	468	13	SAN IGNACIO	\N
22	468	13	SAN NICOLAS	\N
23	468	13	SANTA CRUZ	\N
24	468	13	SANTA ROSA	\N
25	468	13	SANTA TERESITA	\N
28	468	13	ANCON	\N
30	468	13	LA TRAVESIA	\N
31	468	13	PUEBLO NUEVO	\N
33	468	13	BOMBA	\N
36	468	13	EL ROSARIO	\N
38	468	13	SANTA ELENA	\N
39	468	13	SAN LUIS	\N
40	468	13	VILLA NUEVA	\N
0	473	13	MORALES	\N
2	473	13	BODEGA CENTRAL	\N
3	473	13	EL DIQUE	\N
4	473	13	LAS PAILAS	\N
12	473	13	BOCA DE LA HONDA	\N
13	473	13	MICOAHUMADO	\N
14	473	13	PAREDES DE ORORIA	\N
15	473	13	EL CORCOVADO	\N
16	473	13	LA ESMERALDA	\N
17	473	13	LA PALMA	\N
19	473	13	BOCA DE LA CIENAGA	\N
0	490	13	NOROSI	\N
1	490	13	BUENA SENA	\N
2	490	13	CASA DE BARRO	\N
3	490	13	LAS NIEVES	\N
4	490	13	MINA BRISA	\N
5	490	13	MINA ESTRELLA	\N
6	490	13	OLIVARES	\N
7	490	13	SANTA ELENA	\N
8	490	13	VILLA ARIZA	\N
0	549	13	PINILLOS	\N
1	549	13	ARMENIA	\N
4	549	13	LA RUFINA	\N
5	549	13	LA UNION	\N
7	549	13	LAS FLORES	\N
9	549	13	MANTEQUERA	\N
10	549	13	PALENQUITO	\N
11	549	13	PALOMINO	\N
12	549	13	PUERTO LOPEZ	\N
14	549	13	SANTA COA	\N
15	549	13	SANTA ROSA	\N
18	549	13	RUFINA NUEVA	\N
24	549	13	LA VICTORIA	\N
25	549	13	LOS LIMONES	\N
32	549	13	TAPOA	\N
34	549	13	LA UNION CABECERA	\N
0	580	13	REGIDOR	\N
1	580	13	PINAL	\N
3	580	13	LOS CAIMANES	\N
4	580	13	SAN ANTONIO	\N
5	580	13	SAN CAYETANO	\N
6	580	13	SANTA LUCIA	\N
7	580	13	SANTA TERESA	\N
0	600	13	RIO VIEJO	\N
7	600	13	CAIMITAL	\N
9	600	13	COBADILLO	\N
10	600	13	HATILLO	\N
11	600	13	MACEDONIA	\N
14	600	13	SIERPETUERTA	\N
0	620	13	SAN CRISTOBAL	\N
1	620	13	HIGUERETAL	\N
2	620	13	LAS CRUCES	\N
0	647	13	SAN ESTANISLAO DE KOSTKA	\N
2	647	13	LAS PIEDRAS	\N
0	650	13	SAN FERNANDO	\N
1	650	13	GUASIMAL	\N
2	650	13	MENCHIQUEJO	\N
4	650	13	PUNTA DE HORNOS	\N
5	650	13	SANTA ROSA	\N
6	650	13	EL PALMAR	\N
8	650	13	EL PORVENIR	\N
9	650	13	CUATRO BOCAS	\N
10	650	13	EL CONTADERO	\N
13	650	13	LA GUADUA	\N
14	650	13	LAS CUEVAS	\N
15	650	13	PAMPANILLO	\N
0	654	13	SAN JACINTO	\N
1	654	13	ARENAS	\N
2	654	13	BAJO GRANDE	\N
3	654	13	LAS PALMAS	\N
5	654	13	SAN CRISTOBAL	\N
6	654	13	LAS CHARQUITAS	\N
7	654	13	PARAISO	\N
8	654	13	LAS MERCEDES	\N
10	654	13	CASA DE PIEDRA	\N
0	655	13	SAN JACINTO DEL CAUCA	\N
1	655	13	TENCHE	\N
2	655	13	BERMUDEZ	\N
3	655	13	CAIMITAL	\N
4	655	13	LA RAYA	\N
5	655	13	GALINDO	\N
6	655	13	MEJICO	\N
7	655	13	ASTILLEROS	\N
0	657	13	SAN JUAN NEPOMUCENO	\N
1	657	13	CORRALITO	\N
2	657	13	LA HAYA	\N
3	657	13	SAN JOSE DEL PENON (LAS PORQUERAS)	\N
4	657	13	SAN AGUSTIN	\N
5	657	13	SAN CAYETANO	\N
6	657	13	SAN PEDRO CONSOLADO	\N
0	667	13	SAN MARTIN DE LOBA	\N
2	667	13	CHIMI	\N
9	667	13	PAPAYAL	\N
10	667	13	LAS PLAYITAS	\N
14	667	13	PUEBLO NUEVO CERRO DE JULIO	\N
15	667	13	EL JOBO	\N
16	667	13	EL VARAL	\N
17	667	13	LOS PUEBLOS	\N
0	670	13	SAN PABLO	\N
2	670	13	CANALETAL	\N
3	670	13	SANTO DOMINGO	\N
4	670	13	EL CARMEN	\N
5	670	13	EL SOCORRO	\N
7	670	13	POZO AZUL	\N
9	670	13	CANABRAVAL	\N
10	670	13	AGUA SUCIA	\N
11	670	13	CERRO AZUL	\N
12	670	13	VALLECITO	\N
13	670	13	VILLA NUEVA	\N
14	670	13	LA VIRGENCITA	\N
17	670	13	EL ROSARIO	\N
18	670	13	LA FRIA	\N
19	670	13	LA UNION	\N
20	670	13	LOS CAGUISES	\N
22	670	13	LA YE	\N
0	673	13	SANTA CATALINA	\N
3	673	13	GALERAZAMBA	\N
5	673	13	LOMA DE ARENA	\N
6	673	13	PUEBLO NUEVO	\N
7	673	13	COLORADO	\N
9	673	13	EL HOBO	\N
10	673	13	PALMARITO	\N
0	683	13	SANTA ROSA DE LIMA	\N
0	688	13	SANTA ROSA DEL SUR	\N
2	688	13	BUENAVISTA	\N
5	688	13	FATIMA	\N
7	688	13	CANELOS	\N
9	688	13	SAN JOSE	\N
11	688	13	SAN LUCAS	\N
13	688	13	VILLA FLOR	\N
15	688	13	SAN FRANCISCO	\N
16	688	13	SAN ISIDRO	\N
19	688	13	SANTA ISABEL	\N
20	688	13	SAN BENITO	\N
21	688	13	SANTA LUCIA	\N
0	744	13	SIMITI	\N
1	744	13	CAMPO PALLARES	\N
6	744	13	VERACRUZ	\N
7	744	13	SAN BLAS	\N
8	744	13	SAN LUIS	\N
10	744	13	LAS BRISAS	\N
11	744	13	MONTERREY	\N
13	744	13	ANIMAS ALTAS	\N
14	744	13	ANIMAS BAJAS	\N
15	744	13	DIAMANTE	\N
16	744	13	GARZAL	\N
17	744	13	PARAISO	\N
19	744	13	SAN JOAQUIN	\N
21	744	13	LAS ACEITUNAS	\N
22	744	13	EL PINAL	\N
23	744	13	LAS PALMERAS	\N
24	744	13	PATA PELA	\N
25	744	13	SABANA DE SAN LUIS	\N
0	760	13	SOPLAVIENTO	\N
0	780	13	TALAIGUA NUEVO	\N
5	780	13	CANOHONDO	\N
9	780	13	PORVENIR	\N
11	780	13	VESUBIO	\N
17	780	13	PATICO	\N
23	780	13	TALAIGUA VIEJO	\N
24	780	13	LADERA DE SAN MARTIN	\N
25	780	13	PENON DE DURAN	\N
26	780	13	LOS MANGOS	\N
27	780	13	TUPE	\N
0	810	13	PUERTO RICO	\N
2	810	13	BOCAS DE SOLIS	\N
3	810	13	COLORADO	\N
4	810	13	DOS BOCAS	\N
5	810	13	EL SUDAN	\N
6	810	13	LA VENTURA	\N
8	810	13	PUERTO COCA	\N
9	810	13	QUEBRADA DEL MEDIO	\N
10	810	13	SABANAS DEL FIRME	\N
11	810	13	TIQUISIO NUEVO	\N
12	810	13	PUERTO GAITAN	\N
0	836	13	TURBACO	\N
1	836	13	CANAVERAL	\N
2	836	13	SAN JOSE DE CHIQUITO	\N
6	836	13	PUEBLO NUEVO	\N
7	836	13	URBANIZACION VILLA DE CALATRAVA	\N
8	836	13	URBANIZACION CAMPESTRE	\N
9	836	13	URBANIZACION CATALINA	\N
10	836	13	URBANIZACION ZAPOTE	\N
0	838	13	TURBANA	\N
1	838	13	BALLESTAS	\N
2	838	13	LOMAS DE MATUNILLA	\N
0	873	13	VILLANUEVA	\N
1	873	13	ZIPACOA	\N
2	873	13	ALGARROBO	\N
0	894	13	ZAMBRANO	\N
2	894	13	CAPACA	\N
0	1	15	TUNJA	\N
0	22	15	ALMEIDA	\N
0	47	15	AQUITANIA	\N
4	47	15	SAN JUAN DE MOMBITA	\N
7	47	15	TOQUILLA	\N
10	47	15	DAITO	\N
12	47	15	PRIMAVERA	\N
14	47	15	PEREZ	\N
0	51	15	ARCABUCO	\N
0	87	15	BELEN	\N
0	90	15	BERBEO	\N
0	92	15	BETEITIVA	\N
1	92	15	OTENGA	\N
0	97	15	BOAVITA	\N
0	104	15	BOYACA	\N
0	106	15	BRICENO	\N
0	109	15	BUENAVISTA	\N
0	114	15	BUSBANZA	\N
0	131	15	CALDAS	\N
1	131	15	NARINO	\N
0	135	15	CAMPOHERMOSO	\N
2	135	15	VISTAHERMOSA	\N
3	135	15	LOS CEDROS	\N
0	162	15	CERINZA	\N
0	172	15	CHINAVITA	\N
0	176	15	CHIQUINQUIRA	\N
0	180	15	CHISCAS	\N
5	180	15	MERCEDES	\N
0	183	15	CHITA	\N
0	185	15	CHITARAQUE	\N
0	187	15	CHIVATA	\N
0	189	15	CIENEGA	\N
0	204	15	COMBITA	\N
1	204	15	EL BARNE	\N
6	204	15	SAN ONOFRE	\N
0	212	15	COPER	\N
0	215	15	CORRALES	\N
0	218	15	COVARACHIA	\N
7	218	15	NOGONTOVA - LA CAPILLA DE SAN LUIS	\N
0	223	15	CUBARA	\N
5	223	15	EL GUAMO	\N
11	223	15	GIBRALTAR	\N
12	223	15	PUENTE DE BOJABA	\N
0	224	15	CUCAITA	\N
0	226	15	CUITIVA	\N
1	226	15	LLANO DE ALARCON	\N
0	232	15	SAN PEDRO DE IGUAQUE	\N
1	232	15	CHIQUIZA	\N
0	236	15	CHIVOR	\N
0	238	15	DUITAMA	\N
8	238	15	SAN LORENZO ABAJO	\N
9	238	15	SAN ANTONIO NORTE	\N
11	238	15	LA TRINIDAD	\N
12	238	15	CIUDADELA INDUSTRIAL	\N
13	238	15	SANTA CLARA	\N
14	238	15	TOCOGUA	\N
15	238	15	PUEBLITO BOYACENSE	\N
0	244	15	EL COCUY	\N
0	248	15	EL ESPINO	\N
0	272	15	FIRAVITOBA	\N
0	276	15	FLORESTA	\N
1	276	15	TOBASIA	\N
0	293	15	GACHANTIVA	\N
0	296	15	GAMEZA	\N
0	299	15	GARAGOA	\N
0	317	15	GUACAMAYAS	\N
0	322	15	GUATEQUE	\N
0	325	15	GUAYATA	\N
0	332	15	GUICAN DE LA SIERRA	\N
0	362	15	IZA	\N
0	367	15	JENESANO	\N
0	368	15	JERICO	\N
1	368	15	CHEVA	\N
0	377	15	LABRANZAGRANDE	\N
0	380	15	LA CAPILLA	\N
0	401	15	LA VICTORIA	\N
0	403	15	LA UVITA	\N
1	403	15	CUSAGUI	\N
0	407	15	VILLA DE LEYVA	\N
3	407	15	EL ROBLE	\N
0	425	15	MACANAL	\N
4	425	15	SAN PEDRO DE MUCENO	\N
0	442	15	MARIPI	\N
1	442	15	SANTA ROSA	\N
2	442	15	ZULIA	\N
8	442	15	GUARUMAL	\N
0	455	15	MIRAFLORES	\N
0	464	15	MONGUA	\N
0	466	15	MONGUI	\N
0	469	15	MONIQUIRA	\N
7	469	15	LOS CAYENOS	\N
0	476	15	MOTAVITA	\N
0	480	15	MUZO	\N
0	491	15	NOBSA	\N
1	491	15	BELENCITO	\N
2	491	15	CHAMEZA MAYOR	\N
3	491	15	DICHO	\N
4	491	15	PUNTA LARGA	\N
5	491	15	UCUENGA	\N
6	491	15	CALERAS	\N
7	491	15	NAZARETH	\N
9	491	15	CHAMEZA MENOR	\N
10	491	15	GUAQUIRA	\N
12	491	15	SANTANA	\N
0	494	15	NUEVO COLON	\N
0	500	15	OICATA	\N
0	507	15	OTANCHE	\N
1	507	15	BETANIA	\N
2	507	15	BUENAVISTA	\N
4	507	15	PIZARRA	\N
9	507	15	SAN JOSE DE NAZARETH	\N
10	507	15	BUENOS AIRES	\N
11	507	15	MIRADOR	\N
0	511	15	PACHAVITA	\N
0	514	15	PAEZ	\N
1	514	15	LA URURIA	\N
2	514	15	SIRASI	\N
0	516	15	PAIPA	\N
1	516	15	PALERMO	\N
5	516	15	PANTANO DE VARGAS	\N
0	518	15	PAJARITO	\N
1	518	15	CORINTO	\N
2	518	15	CURISI	\N
0	522	15	PANQUEBA	\N
0	531	15	PAUNA	\N
0	533	15	PAYA	\N
1	533	15	MORCOTE	\N
0	537	15	PAZ DE RIO	\N
1	537	15	PAZ VIEJA	\N
0	542	15	PESCA	\N
0	550	15	PISBA	\N
0	572	15	PUERTO BOYACA	\N
1	572	15	GUANEGRO	\N
5	572	15	PUERTO GUTIERREZ	\N
6	572	15	CRUCE PALAGUA	\N
7	572	15	PUERTO SERVIEZ	\N
8	572	15	EL PESCADO	\N
9	572	15	KILOMETRO DOS Y MEDIO	\N
10	572	15	KILOMETRO 25	\N
11	572	15	EL MARFIL	\N
12	572	15	PUERTO PINZON	\N
13	572	15	PUERTO ROMERO	\N
17	572	15	CRUCE EL CHAPARRO	\N
18	572	15	EL ERMITANO	\N
19	572	15	EL OKAL	\N
20	572	15	EL TRIQUE	\N
21	572	15	KILOMETRO 11	\N
23	572	15	MUELLE VELASQUEZ	\N
24	572	15	PUERTO NINO	\N
26	572	15	KILOMETRO UNO Y MEDIO	\N
27	572	15	LA CEIBA	\N
28	572	15	PALAGUA SEGUNDO SECTOR	\N
0	580	15	QUIPAMA	\N
1	580	15	CORMAL	\N
2	580	15	EL PARQUE	\N
3	580	15	HUMBO	\N
5	580	15	EL MANGO (LA YE)	\N
0	599	15	RAMIRIQUI	\N
1	599	15	GUAYABAL (FATIMA)	\N
4	599	15	EL ESCOBAL	\N
5	599	15	SAN ANTONIO	\N
6	599	15	VILLA TOSCANA	\N
0	600	15	RAQUIRA	\N
2	600	15	LA CANDELARIA	\N
0	621	15	RONDON	\N
1	621	15	RANCHOGRANDE	\N
0	632	15	SABOYA	\N
1	632	15	GARAVITO	\N
0	638	15	SACHICA	\N
0	646	15	SAMACA	\N
1	646	15	LA CUMBRE	\N
2	646	15	LA FABRICA	\N
0	660	15	SAN EDUARDO	\N
0	664	15	SAN JOSE DE PARE	\N
0	667	15	SAN LUIS DE GACENO	\N
1	667	15	SANTA TERESA	\N
2	667	15	GUAMAL	\N
3	667	15	HORIZONTES	\N
4	667	15	LA MESA DEL GUAVIO	\N
5	667	15	SAN CARLOS DEL GUAVIO	\N
6	667	15	LA FRONTERA (CORREDOR VIAL)	\N
0	673	15	SAN MATEO	\N
0	676	15	SAN MIGUEL DE SEMA	\N
0	681	15	SAN PABLO DE BORBUR	\N
5	681	15	SANTA BARBARA	\N
6	681	15	SAN MARTIN	\N
7	681	15	COSCUEZ	\N
0	686	15	SANTANA	\N
2	686	15	CASABLANCA	\N
3	686	15	MATEGUADUA	\N
0	690	15	SANTA MARIA	\N
0	693	15	SANTA ROSA DE VITERBO	\N
2	693	15	EL IMPERIO	\N
0	696	15	SANTA SOFIA	\N
0	720	15	SATIVANORTE	\N
1	720	15	SATIVA VIEJO	\N
0	723	15	SATIVASUR	\N
0	740	15	SIACHOQUE	\N
0	753	15	SOATA	\N
0	755	15	SOCOTA	\N
4	755	15	LOS PINOS	\N
0	757	15	SOCHA	\N
1	757	15	SANTA TERESA	\N
3	757	15	SOCHA VIEJO	\N
0	759	15	SOGAMOSO	\N
1	759	15	MORCA	\N
3	759	15	VANEGAS	\N
6	759	15	ALCAPARRAL	\N
7	759	15	MILAGRO Y PLAYITA	\N
0	761	15	SOMONDOCO	\N
0	762	15	SORA	\N
0	763	15	SOTAQUIRA	\N
2	763	15	BOSIGAS	\N
3	763	15	CARRENO	\N
0	764	15	SORACA	\N
0	774	15	SUSACON	\N
0	776	15	SUTAMARCHAN	\N
0	778	15	SUTATENZA	\N
0	790	15	TASCO	\N
1	790	15	LIBERTADORES	\N
2	790	15	LA CHAPA	\N
3	790	15	EL CASTILLO	\N
0	798	15	TENZA	\N
0	804	15	TIBANA	\N
0	806	15	TIBASOSA	\N
2	806	15	EL PARAISO	\N
3	806	15	SANTA TERESA	\N
0	808	15	TINJACA	\N
0	810	15	TIPACOQUE	\N
5	810	15	JEQUE	\N
0	814	15	TOCA	\N
0	816	15	TOGUI	\N
0	820	15	TOPAGA	\N
1	820	15	BADO CASTRO	\N
0	822	15	TOTA	\N
0	832	15	TUNUNGUA	\N
0	835	15	TURMEQUE	\N
0	837	15	TUTA	\N
0	839	15	TUTAZA	\N
4	839	15	LA CAPILLA	\N
0	842	15	UMBITA	\N
0	861	15	VENTAQUEMADA	\N
1	861	15	PARROQUIA VIEJA	\N
2	861	15	CASA VERDE	\N
5	861	15	MONTOYA	\N
6	861	15	ESTANCIA GRANDE	\N
7	861	15	PUENTE BOYACA	\N
8	861	15	TIERRA NEGRA	\N
9	861	15	EL CARPI	\N
10	861	15	EL MANZANO	\N
0	879	15	VIRACACHA	\N
0	897	15	ZETAQUIRA	\N
0	1	17	MANIZALES	\N
1	1	17	ALTO DE LISBOA	\N
2	1	17	KILOMETRO 41 - COLOMBIA	\N
3	1	17	BAJO TABLAZO	\N
4	1	17	LA CABANA	\N
5	1	17	LA CUCHILLA DEL SALADO	\N
8	1	17	LAS PAVAS	\N
9	1	17	SAN PEREGRINO	\N
10	1	17	ALTO TABLAZO	\N
11	1	17	ALTO DEL NARANJO	\N
12	1	17	EL ARENILLO	\N
15	1	17	LA AURORA	\N
22	1	17	ALTO BONITO	\N
23	1	17	MINA RICA	\N
24	1	17	LA GARRUCHA	\N
29	1	17	AGUA BONITA	\N
34	1	17	EL AGUILA	\N
43	1	17	BUENA VISTA	\N
44	1	17	CONDOMINIO PORTAL DE LOS CEREZOS	\N
45	1	17	CONDOMINIO RESERVA DE LOS ALAMOS	\N
46	1	17	CONDOMINIO SAN BERNARDO DEL VIENTO	\N
47	1	17	EL NARANJO	\N
0	13	17	AGUADAS	\N
1	13	17	ARMA	\N
4	13	17	LA MERMITA	\N
10	13	17	EDEN	\N
11	13	17	SAN NICOLAS	\N
12	13	17	ALTO DE PITO	\N
14	13	17	ALTO DE LA MONTANA	\N
15	13	17	BOCAS	\N
16	13	17	VIBORAL	\N
17	13	17	PORE	\N
0	42	17	ANSERMA	\N
4	42	17	MARAPRA	\N
7	42	17	SAN PEDRO	\N
0	50	17	ARANZAZU	\N
6	50	17	SAN RAFAEL	\N
12	50	17	LA HONDA	\N
0	88	17	BELALCAZAR	\N
1	88	17	EL MADRONO	\N
2	88	17	LA HABANA	\N
3	88	17	SAN ISIDRO	\N
8	88	17	ASENTAMIENTO INDIGENA TOTUMAL	\N
0	174	17	CHINCHINA	\N
1	174	17	EL TREBOL	\N
2	174	17	LA FLORESTA	\N
6	174	17	ALTO DE LA MINA	\N
7	174	17	LA QUIEBRA DEL NARANJAL	\N
11	174	17	LA ESTRELLA	\N
12	174	17	EL REPOSO	\N
15	174	17	SAN ANDRES	\N
0	272	17	FILADELFIA	\N
2	272	17	EL VERSO	\N
3	272	17	LA PAILA	\N
5	272	17	SAMARIA	\N
7	272	17	SAN LUIS	\N
8	272	17	BALMORAL	\N
9	272	17	LA MARINA	\N
0	380	17	LA DORADA	\N
1	380	17	BUENAVISTA	\N
2	380	17	GUARINOCITO	\N
3	380	17	PURNIO	\N
4	380	17	LA ATARRAYA	\N
7	380	17	CAMELIAS	\N
8	380	17	DONA JUANA	\N
9	380	17	HORIZONTE	\N
10	380	17	LA AGUSTINA	\N
12	380	17	LA HABANA	\N
15	380	17	PROSOCIAL LA HUMAREDA	\N
0	388	17	LA MERCED	\N
1	388	17	EL LIMON	\N
2	388	17	LA FELISA	\N
7	388	17	LLANADAS	\N
8	388	17	SAN JOSE	\N
9	388	17	EL TAMBOR	\N
0	433	17	MANZANARES	\N
1	433	17	AGUABONITA	\N
3	433	17	LA CEIBA	\N
4	433	17	LAS MARGARITAS	\N
5	433	17	LOS PLANES	\N
0	442	17	MARMATO	\N
1	442	17	CABRAS	\N
2	442	17	EL LLANO	\N
3	442	17	SAN JUAN	\N
4	442	17	LA MIEL	\N
5	442	17	LA CUCHILLA	\N
7	442	17	LA GARRUCHA	\N
8	442	17	JIMENEZ ALTO	\N
9	442	17	JIMENEZ BAJO	\N
12	442	17	LOAIZA	\N
13	442	17	EL GUAYABITO	\N
14	442	17	LA PORTADA	\N
15	442	17	LA QUEBRADA	\N
0	444	17	MARQUETALIA	\N
1	444	17	SANTA ELENA	\N
0	446	17	MARULANDA	\N
1	446	17	MONTEBONITO	\N
0	486	17	NEIRA	\N
4	486	17	PUEBLO RICO	\N
5	486	17	TAPIAS	\N
9	486	17	BARRIO MEDELLIN	\N
10	486	17	LA ISLA	\N
11	486	17	AGROVILLA	\N
12	486	17	SAN JOSE	\N
13	486	17	JUNTAS	\N
0	495	17	NORCASIA	\N
1	495	17	KILOMETRO 40	\N
2	495	17	LA QUIEBRA	\N
3	495	17	MONTEBELLO	\N
4	495	17	MOSCOVITA	\N
0	513	17	PACORA	\N
1	513	17	CASTILLA	\N
2	513	17	LAS COLES	\N
3	513	17	SAN BARTOLOME	\N
8	513	17	LOMA HERMOSA	\N
11	513	17	PALMA ALTA	\N
12	513	17	BUENOS AIRES	\N
13	513	17	EL MORRO	\N
14	513	17	SAN LORENZO	\N
15	513	17	FILO BONITO	\N
16	513	17	LOS CAMBULOS	\N
0	524	17	PALESTINA	\N
1	524	17	ARAUCA	\N
2	524	17	EL JARDIN (REPOSO)	\N
3	524	17	LA PLATA	\N
5	524	17	CARTAGENA	\N
9	524	17	LA BASTILLA	\N
0	541	17	PENSILVANIA	\N
1	541	17	ARBOLEDA	\N
2	541	17	BOLIVIA	\N
4	541	17	EL HIGUERON	\N
6	541	17	LA LINDA	\N
8	541	17	LA RIOJA	\N
9	541	17	PUEBLONUEVO	\N
11	541	17	SAN DANIEL	\N
15	541	17	LA SOLEDAD ALTA	\N
16	541	17	AGUABONITA	\N
0	614	17	RIOSUCIO	\N
1	614	17	BONAFONT	\N
3	614	17	EL SALADO	\N
4	614	17	FLORENCIA	\N
5	614	17	QUIEBRALOMO	\N
6	614	17	SAN LORENZO	\N
8	614	17	IBERIA	\N
10	614	17	SIPIRRA	\N
14	614	17	SAN JERONIMO	\N
17	614	17	PUEBLO VIEJO	\N
21	614	17	LAS ESTANCIAS	\N
22	614	17	LA PLAYA - IMURRA	\N
23	614	17	TUMBABARRETO	\N
24	614	17	AGUAS CLARAS	\N
0	616	17	RISARALDA	\N
4	616	17	QUIEBRA SANTA BARBARA	\N
8	616	17	QUIEBRA VARILLAS	\N
11	616	17	CALLE LARGA	\N
0	653	17	SALAMINA	\N
4	653	17	VEREDA LA UNION	\N
7	653	17	SAN FELIX	\N
23	653	17	LA LOMA	\N
0	662	17	SAMANA	\N
1	662	17	BERLIN	\N
3	662	17	FLORENCIA	\N
4	662	17	ENCIMADAS	\N
5	662	17	LOS POMOS	\N
7	662	17	SAN DIEGO	\N
8	662	17	RANCHOLARGO	\N
17	662	17	PUEBLO NUEVO	\N
18	662	17	DULCE NOMBRE	\N
0	665	17	SAN JOSE	\N
2	665	17	PRIMAVERA ALTA	\N
4	665	17	CONDOMINIOS VALLES DE ACAPULCO Y LOS SEIS Y PUNTO	\N
0	777	17	SUPIA	\N
3	777	17	LA QUINTA	\N
5	777	17	HOJAS ANCHAS	\N
8	777	17	GUAMAL	\N
10	777	17	PUERTO NUEVO	\N
11	777	17	PALMA SOLA	\N
0	867	17	VICTORIA	\N
1	867	17	CANAVERAL	\N
3	867	17	ISAZA	\N
4	867	17	LA PRADERA	\N
5	867	17	EL LLANO	\N
7	867	17	LA FE	\N
8	867	17	SAN MATEO	\N
9	867	17	VILLA ESPERANZA	\N
0	873	17	VILLAMARIA	\N
1	873	17	ALTO DE LA CRUZ - LOS CUERVOS	\N
3	873	17	LLANITOS	\N
4	873	17	RIOCLARO	\N
6	873	17	SAN JULIAN	\N
7	873	17	MIRAFLORES	\N
8	873	17	ALTO VILLARAZO	\N
10	873	17	GALLINAZO	\N
11	873	17	LA NUEVA PRIMAVERA	\N
13	873	17	BELLAVISTA	\N
14	873	17	LA FLORESTA	\N
16	873	17	COROZAL	\N
17	873	17	PARTIDAS	\N
18	873	17	GRANJA AGRICOLA LA PAZ	\N
19	873	17	NUEVO RIO CLARO	\N
0	877	17	VITERBO	\N
0	1	18	FLORENCIA	\N
4	1	18	SAN ANTONIO DE ATENAS	\N
5	1	18	SANTANA LAS HERMOSAS	\N
6	1	18	LA ESPERANZA	\N
7	1	18	NORCACIA	\N
8	1	18	VENECIA	\N
9	1	18	EL PARA	\N
11	1	18	CARANO	\N
18	1	18	CAPITOLIO	\N
24	1	18	PUERTO ARANGO	\N
25	1	18	SEBASTOPOL	\N
0	29	18	ALBANIA	\N
3	29	18	DORADO	\N
4	29	18	VERSALLES	\N
5	29	18	EL PARAISO	\N
0	94	18	BELEN DE LOS ANDAQUIES	\N
1	94	18	EL PORTAL LA MONO	\N
3	94	18	PUERTO TORRES	\N
5	94	18	PUEBLO NUEVO LOS ANGELES	\N
8	94	18	ALETONES	\N
9	94	18	SAN ANTONIO DE PADUA	\N
0	150	18	CARTAGENA DEL CHAIRA	\N
1	150	18	REMOLINO DEL CAGUAN	\N
2	150	18	SANTA FE DEL CAGUAN	\N
3	150	18	MONSERRATE	\N
4	150	18	PENAS COLORADAS	\N
5	150	18	EL GUAMO	\N
6	150	18	PUERTO CAMELIA	\N
7	150	18	SANTO DOMINGO	\N
8	150	18	LOS CRISTALES	\N
9	150	18	RISARALDA	\N
11	150	18	CUMARALES	\N
12	150	18	EL CAFE	\N
13	150	18	NAPOLES (PUERTO NAPOLES)	\N
14	150	18	PENAS BLANCAS	\N
15	150	18	LAS ANIMAS	\N
0	205	18	CURILLO	\N
1	205	18	SALAMINA	\N
2	205	18	NOVIA PUERTO VALDIVIA	\N
3	205	18	PALIZADAS	\N
0	247	18	EL DONCELLO	\N
1	247	18	MAGUARE	\N
2	247	18	PUERTO MANRIQUE	\N
4	247	18	PUERTO HUNGRIA	\N
5	247	18	RIONEGRO	\N
0	256	18	EL PAUJIL	\N
1	256	18	VERSALLES	\N
2	256	18	BOLIVIA	\N
0	410	18	LA MONTANITA	\N
1	410	18	SANTUARIO	\N
2	410	18	LA UNION PENEYA	\N
5	410	18	EL TRIUNFO	\N
6	410	18	MATEGUADUA	\N
7	410	18	SAN ISIDRO	\N
8	410	18	MIRAMAR	\N
9	410	18	PUERTO BRASILIA	\N
10	410	18	PUERTO GAITAN	\N
12	410	18	REINA BAJA	\N
13	410	18	PALMERAS	\N
14	410	18	EL BERLIN	\N
0	460	18	MILAN	\N
1	460	18	SAN ANTONIO DE GETUCHA	\N
3	460	18	MATICURU - GRANARIO	\N
4	460	18	LA RASTRA	\N
5	460	18	REMOLINOS DE ARICUNTI	\N
8	460	18	AGUA BLANCA	\N
10	460	18	AGUA NEGRA	\N
0	479	18	MORELIA	\N
0	592	18	PUERTO RICO	\N
3	592	18	LUSITANIA	\N
4	592	18	SANTANA RAMOS	\N
5	592	18	LA ESMERALDA	\N
6	592	18	LA AGUILILLA	\N
0	610	18	SAN JOSE DEL FRAGUA	\N
1	610	18	FRAGUITA	\N
2	610	18	YURAYACO	\N
3	610	18	SABALETA	\N
0	753	18	SAN VICENTE DEL CAGUAN	\N
2	753	18	GUACAMAYAS	\N
3	753	18	BALSILLAS	\N
4	753	18	CAMPO HERMOSO	\N
7	753	18	TRES ESQUINAS	\N
9	753	18	PUERTO BETANIA	\N
10	753	18	GIBRALTAR	\N
12	753	18	SANTA ROSA	\N
13	753	18	TRONCALES	\N
15	753	18	GUAYABAL	\N
29	753	18	LA CAMPANA	\N
30	753	18	LA CHORRERA	\N
31	753	18	LOS ANDES	\N
32	753	18	PUERTO AMOR	\N
33	753	18	VILLA LOBOS	\N
0	756	18	SOLANO	\N
1	756	18	ARARACUARA	\N
4	756	18	EL DANUBIO - CAMPO ALEGRE	\N
5	756	18	PENAS BLANCAS	\N
6	756	18	CUEMANI	\N
7	756	18	MONONGUETE	\N
8	756	18	PUERTO TEJADA	\N
9	756	18	PENA ROJA DEL CAGUAN	\N
10	756	18	LA MANA	\N
11	756	18	PUERTO LAS MERCEDES	\N
0	785	18	SOLITA	\N
1	785	18	KILOMETRO 28 (LA ARGELIA)	\N
2	785	18	KILOMETRO 30 (CAMPO LEJANO)	\N
5	785	18	UNION SINCELEJO	\N
6	785	18	KILOMETRO 36	\N
0	860	18	VALPARAISO	\N
1	860	18	SANTIAGO DE LA SELVA	\N
2	860	18	KILOMETRO 18	\N
5	860	18	PLAYA RICA	\N
0	1	19	POPAYAN	\N
1	1	19	CAJETE	\N
2	1	19	CALIBIO	\N
7	1	19	JULUMITO	\N
8	1	19	LA REJOYA	\N
13	1	19	PUEBLILLO	\N
14	1	19	PUELENJE	\N
19	1	19	SANTA ROSA	\N
25	1	19	POBLAZON	\N
26	1	19	SAMUEL SILVERIO	\N
27	1	19	CRUCERO DE PUELENJE	\N
28	1	19	EL SALVADOR	\N
29	1	19	EL TUNEL	\N
30	1	19	JULUMITO ALTO	\N
31	1	19	LA CABUYERA	\N
32	1	19	LA PLAYA	\N
33	1	19	LAME	\N
34	1	19	RIO BLANCO	\N
35	1	19	VEREDA DE TORRES	\N
37	1	19	LA ESPERANZA (JARDINES DE PAZ)	\N
38	1	19	LA FORTALEZA	\N
39	1	19	PARCELACION ATARDECERES DE LA PRADERA	\N
40	1	19	LOS LLANOS	\N
0	22	19	ALMAGUER	\N
1	22	19	CAQUIONA	\N
3	22	19	TABLON	\N
4	22	19	LLACUANAS	\N
13	22	19	SAN JORGE HERRADURA	\N
14	22	19	LA HONDA	\N
0	50	19	ARGELIA	\N
1	50	19	EL MANGO	\N
2	50	19	LA BELLEZA	\N
5	50	19	EL DIVISO	\N
6	50	19	EL PLATEADO	\N
7	50	19	SINAI	\N
16	50	19	PUERTO RICO	\N
17	50	19	SAN JUAN GUADUA	\N
0	75	19	BALBOA	\N
1	75	19	LA PLANADA	\N
2	75	19	OLAYA	\N
3	75	19	SAN ALFONSO	\N
5	75	19	LA BERMEJA	\N
6	75	19	PURETO	\N
8	75	19	LA LOMITA	\N
9	75	19	EL VIJAL	\N
11	75	19	PARAISO	\N
0	100	19	BOLIVAR	\N
1	100	19	CAPELLANIAS	\N
5	100	19	EL CARMEN	\N
6	100	19	EL RODEO	\N
7	100	19	GUACHICONO	\N
13	100	19	LERMA	\N
15	100	19	LOS MILAGROS	\N
18	100	19	MELCHOR	\N
20	100	19	SAN JUAN	\N
21	100	19	SAN LORENZO	\N
41	100	19	LA CARBONERA	\N
42	100	19	EL MORRO	\N
0	110	19	BUENOS AIRES	\N
6	110	19	EL PORVENIR	\N
7	110	19	HONDURAS	\N
8	110	19	LA BALSA	\N
12	110	19	TIMBA	\N
13	110	19	EL CERAL	\N
16	110	19	SAN FRANCISCO	\N
25	110	19	PALOBLANCO	\N
29	110	19	LA ESPERANZA	\N
30	110	19	MUNCHIQUE	\N
0	130	19	CAJIBIO	\N
4	130	19	EL CARMELO	\N
5	130	19	EL ROSARIO	\N
6	130	19	LA CAPILLA	\N
7	130	19	LA PEDREGOSA	\N
8	130	19	LA VENTA	\N
9	130	19	SANTA TERESA DE CASAS BAJAS	\N
10	130	19	ORTEGA	\N
17	130	19	EL CAIRO	\N
18	130	19	EL COFRE	\N
19	130	19	ISLA DEL PONTON	\N
20	130	19	LA LAGUNA DINDE	\N
21	130	19	RESGUARDO INDIGENA DEL GUAYABAL CXAYUGE FXIW CXAB	\N
22	130	19	URBANIZACION LAS MARGARITAS	\N
0	137	19	CALDONO	\N
1	137	19	CERRO ALTO	\N
2	137	19	EL PITAL	\N
4	137	19	PESCADOR	\N
7	137	19	PUEBLO NUEVO	\N
8	137	19	SIBERIA	\N
13	137	19	CRUCERO DE PESCADOR	\N
0	142	19	CALOTO	\N
4	142	19	EL PALO	\N
7	142	19	HUASANO	\N
11	142	19	QUINTERO	\N
15	142	19	LA ARROBLEDA	\N
30	142	19	CRUCERO DE GUALI	\N
31	142	19	HUELLAS	\N
32	142	19	ALTO EL PALO	\N
34	142	19	BODEGA ARRIBA	\N
38	142	19	EL NILO	\N
39	142	19	EL GUASIMO	\N
45	142	19	TOEZ	\N
50	142	19	LOPEZ ADENTRO	\N
51	142	19	MORALES	\N
56	142	19	PILAMO	\N
0	212	19	CORINTO	\N
1	212	19	EL JAGUAL	\N
4	212	19	MEDIA NARANJA	\N
5	212	19	RIONEGRO	\N
8	212	19	SAN RAFAEL	\N
9	212	19	EL BARRANCO	\N
10	212	19	QUEBRADITAS	\N
0	256	19	EL TAMBO	\N
1	256	19	ALTO DEL REY	\N
4	256	19	CUATRO ESQUINAS	\N
5	256	19	CHAPA	\N
7	256	19	EL PLACER	\N
9	256	19	EL ZARZAL	\N
12	256	19	HUISITO	\N
13	256	19	LA ALIANZA	\N
14	256	19	LA PAZ	\N
15	256	19	LOS ANAYES	\N
16	256	19	LOS ANDES	\N
19	256	19	PANDIGUANDO	\N
20	256	19	PIAGUA	\N
22	256	19	QUILCACE	\N
25	256	19	SAN JOAQUIN	\N
27	256	19	SEGUENGUE	\N
28	256	19	URIBE	\N
29	256	19	FONDAS	\N
31	256	19	BUENA VISTA	\N
32	256	19	LAS BOTAS	\N
33	256	19	CABUYAL	\N
34	256	19	EL CRUCERO DEL PUEBLO	\N
36	256	19	PLAYA RICA	\N
58	256	19	AIRES DE OCCIDENTE	\N
59	256	19	EL CRUCERO DE PANDIGUANDO	\N
60	256	19	EL RECUERDO	\N
61	256	19	LA CHICUENA	\N
62	256	19	PUENTE DEL RIO TIMBIO	\N
0	290	19	FLORENCIA	\N
1	290	19	EL ROSARIO	\N
2	290	19	MARSELLA	\N
0	300	19	GUACHENE	\N
4	300	19	BARRAGAN	\N
5	300	19	CAMPOALEGRE	\N
6	300	19	CAPONERA 1	\N
7	300	19	CAPONERA SECTOR PALO BLANCO	\N
8	300	19	CIENAGA HONDA	\N
9	300	19	GUABAL	\N
10	300	19	GUABAL 1	\N
11	300	19	GUABAL 2	\N
12	300	19	LA CABANA	\N
13	300	19	LA CABANITA	\N
14	300	19	LA DOMINGA	\N
15	300	19	LLANO DE TAULA ALTO	\N
16	300	19	LLANO DE TAULA BAJO	\N
17	300	19	MINGO	\N
18	300	19	OBANDO	\N
19	300	19	OBANDO SECTOR LA ESPERANZA	\N
20	300	19	SABANETA	\N
21	300	19	SAN ANTONIO	\N
22	300	19	SAN JACINTO	\N
23	300	19	SAN JOSE	\N
0	318	19	GUAPI	\N
2	318	19	BENJAMIN HERRERA (SAN VICENTE)	\N
3	318	19	CALLELARGA	\N
5	318	19	EL CARMELO	\N
8	318	19	LIMONES	\N
11	318	19	EL ROSARIO	\N
12	318	19	SAN AGUSTIN	\N
13	318	19	SAN ANTONIO DE GUAJUI	\N
15	318	19	URIBE URIBE (EL NARANJO)	\N
24	318	19	QUIROGA	\N
25	318	19	CHUARE	\N
26	318	19	SAN JOSE DE GUARE	\N
27	318	19	BELEN	\N
28	318	19	CAIMITO	\N
29	318	19	SANTA ANA	\N
0	355	19	INZA	\N
1	355	19	CALDERAS	\N
2	355	19	PEDREGAL	\N
3	355	19	PUERTO VALENCIA	\N
4	355	19	SAN ANDRES	\N
8	355	19	TUMBICHUCUE	\N
9	355	19	TURMINA	\N
16	355	19	LA MILAGROSA	\N
17	355	19	YAQUIVA	\N
0	364	19	JAMBALO	\N
0	392	19	LA SIERRA	\N
1	392	19	LA DEPRESION	\N
5	392	19	LA CUCHILLA	\N
6	392	19	LA CUCHILLA ALTA	\N
0	397	19	LA VEGA	\N
1	397	19	ALTAMIRA	\N
2	397	19	ARBELA	\N
4	397	19	EL PALMAR	\N
5	397	19	GUACHICONO	\N
6	397	19	LOS UVOS	\N
7	397	19	PANCITARA	\N
9	397	19	SAN MIGUEL	\N
10	397	19	SANTA BARBARA	\N
11	397	19	SANTA JUANA	\N
12	397	19	ALBANIA	\N
18	397	19	BARBILLAS	\N
19	397	19	SANTA RITA	\N
0	418	19	LOPEZ	\N
9	418	19	NOANAMITO	\N
10	418	19	PLAYA GRANDE	\N
12	418	19	SAN ANTONIO DE CHUARE	\N
15	418	19	SAN ISIDRO	\N
16	418	19	SAN PEDRO DE NAYA	\N
18	418	19	TAPARAL	\N
19	418	19	ZARAGOZA	\N
24	418	19	BETANIA	\N
32	418	19	SAN ANTONIO DE GURUMENDY	\N
33	418	19	BOCA GRANDE	\N
35	418	19	SANTA CRUZ DE SIGUI	\N
36	418	19	CABECITAS	\N
37	418	19	CASAS VIEJAS	\N
38	418	19	ISLA DE GALLO	\N
39	418	19	JUAN COBO	\N
0	450	19	MERCADERES	\N
2	450	19	ARBOLEDAS	\N
3	450	19	EL PILON	\N
4	450	19	ESMERALDAS	\N
6	450	19	SAN JOAQUIN	\N
7	450	19	SAN JUANITO	\N
10	450	19	CURACAS	\N
12	450	19	LA DESPENSA	\N
14	450	19	SOMBRERILLOS	\N
15	450	19	EL BADO	\N
16	450	19	TABLONCITO	\N
19	450	19	MOJARRAS	\N
20	450	19	LOS LLANOS	\N
23	450	19	BUENOS AIRES	\N
24	450	19	EL CANGREJO	\N
25	450	19	EL COCAL	\N
26	450	19	ESPERANZAS DE MAYO	\N
27	450	19	PUEBLO NUEVO	\N
0	455	19	MIRANDA	\N
5	455	19	ORTIGAL	\N
7	455	19	SANTA ANA	\N
8	455	19	TIERRADURA	\N
9	455	19	TULIPAN	\N
10	455	19	GUATEMALA	\N
11	455	19	SAN ANDRES	\N
13	455	19	LA LINDOSA	\N
0	473	19	MORALES	\N
2	473	19	CARPINTERO	\N
9	473	19	SAN ISIDRO	\N
12	473	19	SANTA ROSA	\N
14	473	19	LA ESTACION	\N
17	473	19	EL ROSARIO	\N
0	513	19	PADILLA	\N
1	513	19	YARUMALES	\N
3	513	19	LA PAILA	\N
4	513	19	EL CHAMIZO	\N
7	513	19	LOS ROBLES	\N
8	513	19	CUERNAVACA	\N
0	517	19	BELALCAZAR	\N
2	517	19	AVIRAMA	\N
3	517	19	COHETANDO	\N
7	517	19	ITAIBE	\N
12	517	19	RICAURTE	\N
13	517	19	RIOCHIQUITO	\N
14	517	19	SAN LUIS (POTRERILLO)	\N
15	517	19	TALAGA	\N
16	517	19	TOEZ	\N
17	517	19	LA MESA DE TOGOIMA	\N
29	517	19	MINUTO DE DIOS	\N
30	517	19	COQUIYO	\N
32	517	19	EL RODEO	\N
33	517	19	GUADUALEJO	\N
34	517	19	GUAPIO	\N
35	517	19	GUAQUIYO	\N
37	517	19	LA MARIA	\N
39	517	19	MESA DE CALOTO	\N
40	517	19	MESA DE TALAGA	\N
43	517	19	VICANENGA	\N
44	517	19	LA MESA DE AVIRAMA	\N
45	517	19	LA MESA DE BELALCAZAR	\N
46	517	19	SANTA ROSA	\N
0	532	19	EL BORDO	\N
1	532	19	BRISAS	\N
3	532	19	DON ALONSO	\N
4	532	19	GALINDEZ	\N
5	532	19	LA FONDA	\N
6	532	19	LA MESA	\N
8	532	19	PATIA	\N
9	532	19	PIEDRASENTADA	\N
10	532	19	PAN DE AZUCAR	\N
12	532	19	SAJANDI	\N
13	532	19	EL ESTRECHO	\N
14	532	19	EL HOYO	\N
25	532	19	SANTA CRUZ	\N
32	532	19	PALO MOCHO	\N
0	533	19	PIAMONTE	\N
2	533	19	EL REMANSO	\N
3	533	19	MIRAFLOR	\N
4	533	19	YAPURA	\N
5	533	19	LAS PALMERAS 1	\N
6	533	19	LAS PALMERAS 2	\N
7	533	19	NAPOLES	\N
0	548	19	PIENDAMO	\N
1	548	19	TUNIA	\N
0	573	19	PUERTO TEJADA	\N
1	573	19	BOCAS DEL PALO	\N
2	573	19	LAS BRISAS	\N
3	573	19	SAN CARLOS	\N
4	573	19	ZANJON RICO	\N
5	573	19	PERICO NEGRO	\N
6	573	19	VUELTA LARGA	\N
8	573	19	LOS BANCOS	\N
9	573	19	GUENGUE	\N
10	573	19	CIUDAD SUR	\N
0	585	19	COCONUCO	\N
4	585	19	PURACE	\N
7	585	19	SANTA LETICIA	\N
8	585	19	JUAN TAMA	\N
9	585	19	PALETARA	\N
10	585	19	CHAPIO	\N
0	622	19	ROSAS	\N
2	622	19	PARRAGA	\N
7	622	19	CEFIRO	\N
11	622	19	SAUCE	\N
0	693	19	SAN SEBASTIAN	\N
1	693	19	EL ROSAL	\N
4	693	19	SANTIAGO	\N
5	693	19	VALENCIA	\N
6	693	19	VENECIA	\N
0	698	19	SANTANDER DE QUILICHAO	\N
1	698	19	EL PALMAR	\N
2	698	19	EL TURCO	\N
4	698	19	LA ARROBLEDA	\N
7	698	19	MONDOMO	\N
8	698	19	PARAMILLO 1	\N
9	698	19	SAN RAFAEL	\N
10	698	19	TRES QUEBRADAS	\N
13	698	19	SAN ANTONIO	\N
14	698	19	SAN PEDRO	\N
17	698	19	DOMINGUILLO	\N
18	698	19	EL CRUCERO	\N
20	698	19	QUINAMAYO	\N
22	698	19	LLANO DE ALEGRIAS	\N
23	698	19	CABECERA DOMINGUILLO	\N
24	698	19	CAMBALACHE	\N
25	698	19	EL BROCHE	\N
26	698	19	EL LLANITO	\N
27	698	19	EL TAJO	\N
28	698	19	LA AGUSTINA	\N
29	698	19	LA CAPILLA	\N
30	698	19	LA CHAPA	\N
31	698	19	LA PALOMERA	\N
32	698	19	LA QUEBRADA	\N
33	698	19	LOMITAS ABAJO	\N
34	698	19	LOMITAS ARRIBA	\N
35	698	19	LOURDES	\N
36	698	19	MANDIVA	\N
37	698	19	SAN JOSE	\N
42	698	19	VILACHI	\N
43	698	19	BELLAVISTA	\N
44	698	19	PARAMILLO 2	\N
0	701	19	SANTA ROSA	\N
1	701	19	DESCANSE	\N
2	701	19	EL CARMELO	\N
5	701	19	SANTA MARTHA	\N
6	701	19	SAN JUAN DE VILLALOBOS	\N
15	701	19	SECTOR MANDIYACO	\N
0	743	19	SILVIA	\N
2	743	19	PITAYO	\N
3	743	19	QUICHAYA	\N
5	743	19	USENDA	\N
0	760	19	PAISPAMBA	\N
1	760	19	CHAPA	\N
7	760	19	RIO BLANCO	\N
12	760	19	LAS VEGAS	\N
0	780	19	SUAREZ	\N
7	780	19	LA TOMA	\N
8	780	19	LA BETULIA	\N
11	780	19	ALTAMIRA	\N
0	785	19	SUCRE	\N
1	785	19	EL PARAISO	\N
8	785	19	LA CEJA	\N
0	807	19	TIMBIO	\N
7	807	19	CRUCES	\N
9	807	19	ALTO SAN JOSE	\N
16	807	19	LAS HUACAS	\N
0	809	19	TIMBIQUI	\N
1	809	19	BUBUEY	\N
2	809	19	CAMARONES	\N
3	809	19	COTEJE	\N
6	809	19	SAN BERNARDO	\N
7	809	19	SAN JOSE	\N
8	809	19	SANTA MARIA	\N
9	809	19	SANTA ROSA DE SAIJA	\N
10	809	19	CHETE	\N
11	809	19	BOCA DE PATIA	\N
12	809	19	EL CHARCO	\N
13	809	19	EL REALITO	\N
18	809	19	CUPI	\N
20	809	19	SAN MIGUEL	\N
21	809	19	COROZAL	\N
22	809	19	CABECITAL	\N
23	809	19	PUERTO SAIJA	\N
24	809	19	ANGOSTURA	\N
25	809	19	GUANGUI	\N
26	809	19	LOS BRASOS	\N
27	809	19	PIZARE	\N
0	821	19	TORIBIO	\N
5	821	19	SAN FRANCISCO	\N
7	821	19	TACUEYO	\N
9	821	19	CALOTO NUEVO	\N
10	821	19	EL HUILA	\N
0	824	19	TOTORO	\N
2	824	19	GABRIEL LOPEZ	\N
4	824	19	PANIQUITA	\N
0	845	19	VILLA RICA	\N
5	845	19	JUAN IGNACIO	\N
6	845	19	PRIMAVERA	\N
7	845	19	PERICO NEGRO	\N
0	1	20	VALLEDUPAR	\N
1	1	20	AGUAS BLANCAS	\N
2	1	20	ATANQUEZ	\N
3	1	20	BADILLO	\N
5	1	20	CARACOLI	\N
6	1	20	CHEMESQUEMENA	\N
7	1	20	GUATAPURI	\N
9	1	20	GUACOCHE	\N
10	1	20	GUAYMARAL	\N
11	1	20	LA MINA	\N
12	1	20	LOS VENADOS	\N
13	1	20	MARIANGOLA	\N
14	1	20	PATILLAL	\N
18	1	20	VALENCIA DE JESUS	\N
19	1	20	CAMPERUCHO	\N
22	1	20	GUACOCHITO	\N
24	1	20	LOS CALABAZOS	\N
25	1	20	LOS CORAZONES	\N
26	1	20	LOS HATICOS  I	\N
27	1	20	LA MESA - AZUCAR BUENA	\N
28	1	20	RAICES	\N
30	1	20	RANCHO DE GOYA	\N
31	1	20	RIO SECO	\N
32	1	20	LA VEGA  ARRIBA	\N
33	1	20	VERACRUZ	\N
34	1	20	VILLA GERMANIA	\N
36	1	20	EL JABO	\N
37	1	20	EL ALTO DE LA VUELTA	\N
38	1	20	HATICOS II	\N
39	1	20	EL PERRO	\N
40	1	20	LAS MERCEDES	\N
41	1	20	SABANA DE CRESPO	\N
42	1	20	LAS CASITAS	\N
44	1	20	MARUAMAQUE	\N
45	1	20	PONTON	\N
47	1	20	EL MOJAO	\N
48	1	20	RAMALITO	\N
51	1	20	VILLA RUEDA	\N
0	11	20	AGUACHICA	\N
1	11	20	BARRANCALEBRIJA	\N
6	11	20	LOMA DE CORREDOR	\N
9	11	20	PUERTO PATINO	\N
10	11	20	BUTURAMA	\N
11	11	20	NOREAN	\N
12	11	20	SANTA LUCIA	\N
14	11	20	VILLA DE SAN ANDRES	\N
25	11	20	EL JUNCAL	\N
26	11	20	LA CAMPANA	\N
29	11	20	LA YE	\N
0	13	20	AGUSTIN CODAZZI	\N
2	13	20	CASACARA	\N
3	13	20	LLERASCA	\N
6	13	20	PUNTA ARRECHA	\N
7	13	20	SAN RAMON	\N
0	32	20	ASTREA	\N
1	32	20	ARJONA	\N
3	32	20	EL YUCAL	\N
5	32	20	SANTA CECILIA	\N
6	32	20	EL HEBRON	\N
7	32	20	EL JOBO	\N
8	32	20	LA Y	\N
9	32	20	MONTECRISTO	\N
10	32	20	NUEVA COLOMBIA	\N
0	45	20	BECERRIL	\N
1	45	20	ESTADOS UNIDOS	\N
4	45	20	LA GUAJIRITA	\N
0	60	20	BOSCONIA	\N
4	60	20	LOMA COLORADA	\N
10	60	20	PUERTO LAJAS	\N
0	175	20	CHIMICHAGUA	\N
4	175	20	CANDELARIA	\N
5	175	20	EL GUAMO	\N
8	175	20	LAS FLORES	\N
9	175	20	LAS VEGAS	\N
10	175	20	MANDINGUILLA	\N
11	175	20	SALOA	\N
13	175	20	SEMPEGUA	\N
14	175	20	SOLEDAD	\N
16	175	20	LA MATA	\N
17	175	20	EL CANAL	\N
18	175	20	SANTO DOMINGO	\N
20	175	20	PLATA PERDIDA	\N
21	175	20	SABANAS DE JUAN MARCOS	\N
22	175	20	HIGO AMARILLO	\N
23	175	20	BETEL	\N
24	175	20	BUENOS AIRES	\N
25	175	20	CUATRO ESQUINAS	\N
26	175	20	DARDANELOS DOS	\N
29	175	20	PIEDRAS BLANCAS	\N
30	175	20	PUEBLITO	\N
31	175	20	ULTIMO CASO	\N
32	175	20	ZAPATI	\N
33	175	20	CABECERA	\N
34	175	20	CORRALITO	\N
35	175	20	DIOS ME VE	\N
36	175	20	EL PROGRESO	\N
37	175	20	LA INVERNA	\N
38	175	20	LA SABANA DEL TREBOL	\N
39	175	20	LA UNION	\N
40	175	20	MATA DE GUILLIN	\N
41	175	20	NUEVA VICTORIA	\N
42	175	20	PAJARITO	\N
0	178	20	CHIRIGUANA	\N
6	178	20	POPONTE	\N
8	178	20	RINCON HONDO	\N
14	178	20	LA AURORA	\N
15	178	20	ESTACION CHIRIGUANA	\N
16	178	20	LA SIERRA	\N
17	178	20	AGUA FRIA	\N
18	178	20	EL CRUCE DE LA SIERRA	\N
19	178	20	ARENAS BLANCAS	\N
21	178	20	CERRAJONES	\N
0	228	20	CURUMANI	\N
1	228	20	SABANAGRANDE	\N
2	228	20	SAN ROQUE	\N
3	228	20	SAN SEBASTIAN	\N
4	228	20	SANTA ISABEL	\N
5	228	20	CHAMPAN	\N
7	228	20	GUAIMARAL	\N
8	228	20	BARRIO ACOSTA	\N
9	228	20	HOJANCHA	\N
11	228	20	EL MAMEY	\N
12	228	20	CHINELA	\N
14	228	20	NUEVO HORIZONTE	\N
0	238	20	EL COPEY	\N
2	238	20	CARACOLICITO	\N
3	238	20	CHIMILA	\N
4	238	20	SAN FRANCISCO DE ASIS	\N
0	250	20	EL PASO	\N
1	250	20	EL VALLITO	\N
2	250	20	LA LOMA	\N
3	250	20	POTRERILLO	\N
4	250	20	CUATRO VIENTOS	\N
6	250	20	EL CARMEN	\N
0	295	20	GAMARRA	\N
1	295	20	LA ESTACION	\N
2	295	20	EL CONTENTO	\N
4	295	20	PALENQUILLO	\N
5	295	20	PUERTO MOSQUITO	\N
6	295	20	PUERTO VIEJO	\N
7	295	20	CASCAJAL	\N
0	310	20	GONZALEZ	\N
2	310	20	BURBURA	\N
3	310	20	CULEBRITA	\N
6	310	20	MONTERA	\N
7	310	20	SAN ISIDRO	\N
0	383	20	LA GLORIA	\N
1	383	20	AYACUCHO	\N
2	383	20	CAROLINA	\N
3	383	20	MOLINA	\N
5	383	20	SIMANA	\N
6	383	20	BESOTE	\N
10	383	20	LA MATA	\N
11	383	20	ESTACION FERROCARRIL	\N
12	383	20	LAS PUNTAS	\N
0	400	20	LA JAGUA DE IBIRICO	\N
1	400	20	LAS PALMITAS	\N
2	400	20	LA VICTORIA DE SAN ISIDRO	\N
3	400	20	BOQUERON	\N
0	443	20	MANAURE BALCON DEL CESAR	\N
0	517	20	PAILITAS	\N
1	517	20	LA FLORESTA	\N
2	517	20	RIVERA	\N
4	517	20	PALESTINA	\N
6	517	20	EL BURRO	\N
7	517	20	MATA DE BARRO	\N
0	550	20	PELAYA	\N
1	550	20	COSTILLA	\N
12	550	20	SAN BERNARDO	\N
0	570	20	PUEBLO BELLO	\N
1	570	20	LA CAJA	\N
2	570	20	LAS MINAS DE IRACAL	\N
3	570	20	NUEVO COLON	\N
4	570	20	NABUSIMAKE	\N
5	570	20	PALMARITO	\N
0	614	20	RIO DE ORO	\N
1	614	20	EL MARQUEZ	\N
3	614	20	EL SALOBRE	\N
4	614	20	LOS ANGELES	\N
6	614	20	MONTECITOS	\N
10	614	20	PUERTO NUEVO	\N
12	614	20	MORRISON	\N
0	621	20	ROBLES	\N
1	621	20	LOS ENCANTOS	\N
6	621	20	SAN JOSE DEL ORIENTE	\N
11	621	20	VARAS BLANCAS	\N
12	621	20	SAN JOSE DE ORIENTE - BETANIA	\N
13	621	20	MINGUILLO	\N
15	621	20	RABO LARGO	\N
16	621	20	SABANA ALTA	\N
0	710	20	SAN ALBERTO	\N
1	710	20	LA LLANA	\N
2	710	20	LA PALMA	\N
5	710	20	LIBANO	\N
8	710	20	PUERTO CARRENO	\N
0	750	20	SAN DIEGO	\N
1	750	20	LOS TUPES	\N
2	750	20	MEDIA LUNA	\N
6	750	20	EL RINCON	\N
7	750	20	LAS PITILLAS	\N
9	750	20	LOS BRASILES	\N
11	750	20	TOCAIMO	\N
12	750	20	NUEVAS FLORES	\N
0	770	20	SAN MARTIN	\N
1	770	20	AGUAS BLANCAS	\N
3	770	20	MINAS	\N
4	770	20	PUERTO OCULTO	\N
5	770	20	SAN JOSE DE LAS AMERICAS	\N
6	770	20	CANDELIA	\N
7	770	20	TERRAPLEN	\N
8	770	20	LA CURVA	\N
9	770	20	LA BANCA TORCOROMA	\N
10	770	20	CUATRO BOCAS	\N
11	770	20	LOS BAGRES	\N
12	770	20	PITA LIMON	\N
16	770	20	CAMPO AMALIA	\N
0	787	20	TAMALAMEQUE	\N
1	787	20	PALESTINA LA NUEVA	\N
2	787	20	LA BOCA	\N
5	787	20	ZAPATOSA	\N
7	787	20	ANTEQUERA	\N
9	787	20	LAS PALMAS	\N
11	787	20	LAS BRISAS	\N
12	787	20	PASACORRIENDO	\N
13	787	20	PUEBLO NUEVO	\N
14	787	20	MUNDO ALREVEZ	\N
15	787	20	EL DOCE	\N
16	787	20	SITIO NUEVO	\N
17	787	20	TOTUMITO	\N
0	1	23	MONTERIA	\N
1	1	23	BUENOS AIRES	\N
2	1	23	PALOTAL	\N
3	1	23	EL CERRITO	\N
4	1	23	EL SABANAL	\N
5	1	23	GUASIMAL	\N
6	1	23	JARAQUIEL	\N
7	1	23	LA MANTA	\N
8	1	23	LAS PALOMAS	\N
9	1	23	LETICIA - EL TRONCO	\N
10	1	23	LOMA VERDE	\N
11	1	23	LOS GARZONES	\N
12	1	23	NUEVO PARAISO	\N
13	1	23	NUEVA LUCIA	\N
14	1	23	PATIO BONITO	\N
15	1	23	SAN ISIDRO	\N
16	1	23	PUEBLO BUHO	\N
17	1	23	SAN ANTERITO	\N
18	1	23	SANTA CLARA	\N
19	1	23	SANTA ISABEL	\N
20	1	23	SANTA LUCIA	\N
21	1	23	TRES PALMAS	\N
22	1	23	TRES PIEDRAS	\N
28	1	23	EL BARSAL	\N
29	1	23	NUEVA ESPERANZA	\N
30	1	23	EL COCUELO	\N
31	1	23	MARTINICA	\N
32	1	23	GUATEQUE	\N
33	1	23	TENERIFE	\N
34	1	23	LA VICTORIA	\N
35	1	23	MORINDO CENTRAL	\N
36	1	23	BOCA DE LA CEIBA	\N
37	1	23	BROQUELITO	\N
38	1	23	EL LIMON	\N
39	1	23	EL QUINCE	\N
40	1	23	EL VIDRIAL	\N
41	1	23	ENSENADA DE LA HAMACA	\N
42	1	23	GALILEA	\N
43	1	23	LA ESPERANZA	\N
44	1	23	LA FLORIDA	\N
45	1	23	MAQUENCAL	\N
46	1	23	MARACAYO	\N
47	1	23	MATAMOROS	\N
48	1	23	MOCHILAS	\N
49	1	23	NUEVOS HORIZONTES	\N
50	1	23	PALMITO PICAO	\N
51	1	23	PEREIRA	\N
52	1	23	VILLAVICENCIO	\N
53	1	23	YA LA LLEVA	\N
54	1	23	AGUA VIVAS	\N
55	1	23	ARENAL	\N
62	1	23	EL TAPAO	\N
63	1	23	LA LUCHA	\N
64	1	23	LA POZA	\N
65	1	23	LOS CEDROS	\N
66	1	23	LOS PANTANOS	\N
68	1	23	EL DOCE	\N
69	1	23	PUEBLO SECO	\N
70	1	23	SAN FRANCISCO	\N
72	1	23	EL FLORAL	\N
73	1	23	MEDELLIN - SAPO	\N
0	68	23	AYAPEL	\N
1	68	23	ALFONSO LOPEZ	\N
3	68	23	CECILIA	\N
4	68	23	EL CEDRO	\N
6	68	23	NARINO	\N
7	68	23	PALOTAL	\N
9	68	23	SINCELEJITO	\N
12	68	23	MARRALU	\N
14	68	23	PUEBLO NUEVO - POPALES	\N
15	68	23	LAS DELICIAS	\N
16	68	23	SEHEVE	\N
0	79	23	BUENAVISTA	\N
1	79	23	TIERRA SANTA	\N
2	79	23	VILLA FATIMA	\N
3	79	23	BELEN	\N
4	79	23	NUEVA ESTACION	\N
5	79	23	PUERTO CORDOBA	\N
10	79	23	MEJOR ESQUINA	\N
12	79	23	EL VIAJANO	\N
14	79	23	VERACRUZ	\N
15	79	23	SANTA CLARA	\N
16	79	23	SANTA FE DEL ARCIAL 1	\N
17	79	23	COYONPO	\N
19	79	23	LAS MARIAS	\N
20	79	23	SANTA FE DE ARCIAL 2	\N
0	90	23	CANALETE	\N
1	90	23	EL LIMON	\N
2	90	23	POPAYAN	\N
7	90	23	CADILLO	\N
13	90	23	EL GUINEO	\N
20	90	23	TIERRADENTRO	\N
21	90	23	QUEBRADA DE URANGO	\N
22	90	23	BUENOS AIRES - LAS PAVAS	\N
23	90	23	EL TOMATE	\N
0	162	23	CERETE	\N
1	162	23	MARTINEZ	\N
2	162	23	MATEO GOMEZ	\N
3	162	23	RABOLARGO	\N
4	162	23	SEVERA	\N
5	162	23	CUERO CURTIDO	\N
6	162	23	RETIRO DE LOS INDIOS	\N
10	162	23	EL CHORRILLO	\N
12	162	23	SAN ANTONIO	\N
13	162	23	EL CEDRO	\N
14	162	23	ZARZALITO	\N
16	162	23	MANGUELITO	\N
18	162	23	EL QUEMADO	\N
23	162	23	LA ESMERALDA	\N
25	162	23	BUENAVISTA DEL RETIRO	\N
26	162	23	BUENAVISTA EL QUEMADO	\N
27	162	23	EL CARMEN	\N
31	162	23	CONDOMINIO LAGOS DE SANTA RITA	\N
32	162	23	RUSIA	\N
0	168	23	CHIMA	\N
1	168	23	ARACHE	\N
2	168	23	CAMPO BELLO	\N
3	168	23	CAROLINA	\N
4	168	23	COROZALITO	\N
5	168	23	PUNTA VERDE	\N
6	168	23	SITIO VIEJO	\N
8	168	23	SABANACOSTA	\N
17	168	23	PIMENTAL SECTOR BURRO MUERTO	\N
0	182	23	CHINU	\N
1	182	23	AGUAS VIVAS	\N
2	182	23	CACAOTAL	\N
3	182	23	CARBONERO	\N
5	182	23	HEREDIA	\N
6	182	23	LOS ANGELES	\N
7	182	23	NUEVO ORIENTE	\N
8	182	23	SAN MATEO	\N
9	182	23	SAN RAFAEL	\N
11	182	23	SANTA FE	\N
12	182	23	SANTA ROSA	\N
13	182	23	FLECHAS SEVILLA	\N
14	182	23	TIERRA GRATA	\N
15	182	23	FLECHAS SABANAS	\N
16	182	23	GARBADO	\N
17	182	23	LA PANAMA	\N
18	182	23	LA PILONA	\N
19	182	23	RETIRO DE LOS PEREZ	\N
20	182	23	ANDALUCIA	\N
21	182	23	LOS ALGARROBOS	\N
22	182	23	EL TIGRE	\N
24	182	23	VILLA FATIMA	\N
27	182	23	EL DESEO	\N
31	182	23	PARAISO	\N
33	182	23	LAS JARABAS	\N
35	182	23	PAJONAL	\N
36	182	23	PISA BONITO	\N
41	182	23	LOMAS DE PIEDRA	\N
43	182	23	BAJO DE PIEDRA	\N
0	189	23	CIENAGA DE ORO	\N
1	189	23	BERASTEGUI	\N
3	189	23	LAGUNETA	\N
4	189	23	LOS MIMBRES	\N
5	189	23	PUNTA DE YANEZ	\N
7	189	23	PUERTO DE LA CRUZ	\N
8	189	23	MALAGANA	\N
13	189	23	SUAREZ	\N
16	189	23	EL SALADO	\N
18	189	23	LAS PIEDRAS	\N
19	189	23	PIJIGUAYAL	\N
21	189	23	SANTIAGO POBRE	\N
23	189	23	SAN ANTONIO DEL TACHIRA	\N
24	189	23	ROSA VIEJA	\N
25	189	23	LAS PALMITAS	\N
28	189	23	LAS BALSAS	\N
29	189	23	EGIPTO	\N
30	189	23	BARRO PRIETO	\N
34	189	23	LA DRAGA	\N
44	189	23	LA ESPERANZA	\N
45	189	23	SANTIAGUITO	\N
0	300	23	COTORRA	\N
2	300	23	LOS GOMEZ	\N
3	300	23	LOS CEDROS	\N
6	300	23	PASO DE LAS FLORES	\N
7	300	23	VILLA NUEVA	\N
8	300	23	ABROJAL	\N
10	300	23	EL BINDE	\N
11	300	23	CAIMAN	\N
15	300	23	LAS AREPAS	\N
16	300	23	TREMENTINO	\N
20	300	23	MORALITO	\N
21	300	23	SAN JOSE	\N
26	300	23	PUEBLO NUEVO	\N
27	300	23	SAN PABLO	\N
0	350	23	LA APARTADA	\N
3	350	23	LA BALSA	\N
7	350	23	SITIO NUEVO	\N
15	350	23	CAMPO ALEGRE	\N
17	350	23	PUERTO CORDOBA	\N
0	417	23	SANTA CRUZ DE LORICA	\N
2	417	23	EL CARITO	\N
3	417	23	LA DOCTRINA	\N
4	417	23	LAS FLORES	\N
5	417	23	LOS GOMEZ	\N
6	417	23	LOS MONOS	\N
7	417	23	NARINO	\N
8	417	23	PALO DE AGUA	\N
9	417	23	SAN SEBASTIAN	\N
10	417	23	TIERRALTA	\N
11	417	23	SAN ANTERITO	\N
12	417	23	EL LAZO	\N
14	417	23	CAMPO ALEGRE	\N
17	417	23	EL CAMPANO DE LOS INDIOS	\N
18	417	23	COTOCA ARRIBA	\N
19	417	23	EL RODEO	\N
21	417	23	REMOLINO	\N
22	417	23	VILLA CONCEPCION	\N
23	417	23	MATA DE CANA	\N
24	417	23	CASTILLERAL	\N
25	417	23	COTOCA ABAJO	\N
27	417	23	SAN NICOLAS DE BARI	\N
29	417	23	LA SUBIDA	\N
30	417	23	EL PLAYON	\N
31	417	23	LA PEINADA	\N
34	417	23	SANTA LUCIA	\N
36	417	23	LA PALMA	\N
37	417	23	LOS MORALES	\N
39	417	23	EL GUANABANO	\N
42	417	23	JUAN DE DIOS GARI	\N
0	419	23	LOS CORDOBAS	\N
2	419	23	EL EBANO	\N
4	419	23	PUERTO REY	\N
5	419	23	SANTA ROSA LA CANA	\N
10	419	23	BUENAVISTA	\N
11	419	23	LA SALADA	\N
12	419	23	MORINDO SANTANA	\N
13	419	23	JALISCO	\N
19	419	23	EL GUAIMARO	\N
20	419	23	LA APONDERANCIA	\N
25	419	23	LOS ESQUIMALES	\N
26	419	23	MINUTO DE DIOS	\N
30	419	23	NUEVO NARINO	\N
0	464	23	MOMIL	\N
1	464	23	SABANETA	\N
2	464	23	SACANA	\N
3	464	23	TREMENTINO	\N
5	464	23	PUEBLECITO	\N
6	464	23	GUAYMARAL	\N
7	464	23	BETULIA	\N
0	466	23	MONTELIBANO	\N
1	466	23	EL ANCLAR	\N
5	466	23	SAN FRANCISCO DEL RAYO	\N
6	466	23	TIERRADENTRO	\N
8	466	23	PICA PICA NUEVO	\N
21	466	23	PUERTO ANCHICA	\N
23	466	23	CORDOBA	\N
28	466	23	EL PALMAR	\N
31	466	23	LAS MARGARITAS	\N
33	466	23	PUERTO NUEVO	\N
37	466	23	VILLA CARMINIA	\N
0	500	23	MONITOS	\N
1	500	23	RIO CEDRO	\N
2	500	23	SANTANDER DE LA CRUZ	\N
3	500	23	LA UNION	\N
4	500	23	BAJO DEL LIMON	\N
5	500	23	BELLA COHITA	\N
6	500	23	BROQUELES	\N
10	500	23	LA RADA	\N
11	500	23	LAS MUJERES	\N
16	500	23	NARANJAL	\N
23	500	23	PERPETUO SOCORRO	\N
24	500	23	PUEBLITO	\N
29	500	23	SAN ANTERITO	\N
0	555	23	PLANETA RICA	\N
1	555	23	ARENOSO	\N
2	555	23	CAMPO BELLO	\N
3	555	23	CAROLINA	\N
4	555	23	SANTANA (CENTRO ALEGRE)	\N
5	555	23	EL ALMENDRO	\N
6	555	23	MARANONAL	\N
7	555	23	PLAZA BONITA	\N
8	555	23	PROVIDENCIA	\N
10	555	23	MEDIO RANCHO	\N
11	555	23	PAMPLONA	\N
12	555	23	EL REPARO	\N
13	555	23	LOS CERROS	\N
15	555	23	LAS PELONAS	\N
19	555	23	NUEVO PARAISO	\N
20	555	23	SANTA ROSA	\N
24	555	23	ARROYO ARENA	\N
29	555	23	SAN JERONIMO (GOLERO)	\N
30	555	23	EL GUAYABO	\N
35	555	23	LOMA DE PIEDRA	\N
44	555	23	PUNTA VERDE	\N
47	555	23	SANTA ANA	\N
0	570	23	PUEBLO NUEVO	\N
2	570	23	SAN JOSE DE CINTURA	\N
3	570	23	CORCOVAO	\N
4	570	23	EL VARAL	\N
5	570	23	EL POBLADO	\N
6	570	23	LA GRANJITA	\N
7	570	23	LOS LIMONES	\N
8	570	23	PUERTO SANTO	\N
9	570	23	LA MAGDALENA	\N
11	570	23	PALMIRA	\N
13	570	23	NEIVA	\N
14	570	23	ARROYO DE ARENAS	\N
16	570	23	EL CONTENTO	\N
17	570	23	PRIMAVERA	\N
18	570	23	BETANIA	\N
20	570	23	EL CAMPANO	\N
24	570	23	NUEVA ESPERANZA	\N
26	570	23	CAFE PISAO	\N
28	570	23	LOMA DE PIEDRA	\N
29	570	23	APARTADA DE BETULIA	\N
30	570	23	EL CORRAL	\N
31	570	23	EL CHIPAL	\N
32	570	23	EL DESEO	\N
33	570	23	EL TOCHE	\N
34	570	23	VILLA ESPERANZA	\N
0	574	23	PUERTO ESCONDIDO	\N
1	574	23	CRISTO REY	\N
2	574	23	EL PANTANO	\N
3	574	23	SAN JOSE DE CANALETE	\N
4	574	23	VILLA ESTER	\N
5	574	23	ARIZAL	\N
6	574	23	SAN LUIS	\N
9	574	23	LAS MUJERES	\N
11	574	23	EL SILENCIO	\N
12	574	23	SAN MIGUEL	\N
14	574	23	SANTA ISABEL	\N
0	580	23	PUERTO LIBERTADOR	\N
1	580	23	LA RICA	\N
2	580	23	PICA PICA VIEJO	\N
3	580	23	VILLANUEVA	\N
4	580	23	JUAN JOSE	\N
6	580	23	BUENOS AIRES	\N
9	580	23	SANTA FE DE LAS CLARAS	\N
10	580	23	SAN JUAN	\N
11	580	23	PUERTO BELEN	\N
12	580	23	EL BRILLANTE	\N
14	580	23	PUERTO CAREPA	\N
15	580	23	TORNO ROJO	\N
16	580	23	CENTRO AMERICA	\N
17	580	23	COROSALITO	\N
18	580	23	NUEVA ESPERANZA	\N
19	580	23	SIETE DE AGOSTO	\N
20	580	23	VILLA ESPERANZA	\N
0	586	23	PURISIMA DE LA CONCEPCION	\N
1	586	23	ASERRADERO	\N
2	586	23	EL HUESO	\N
3	586	23	LOS CORRALES	\N
4	586	23	SAN PEDRO DE ARROYO HONDO	\N
5	586	23	ARENAL	\N
6	586	23	COMEJEN	\N
7	586	23	CERROPETRONA	\N
0	660	23	SAHAGUN	\N
1	660	23	ARENAS DEL NORTE	\N
2	660	23	BAJO GRANDE	\N
3	660	23	CATALINA	\N
4	660	23	COLOMBOY	\N
5	660	23	EL CRUCERO	\N
6	660	23	EL VIAJANO	\N
7	660	23	LLANADAS	\N
8	660	23	LA YE	\N
9	660	23	MORROCOY	\N
10	660	23	RODANIA	\N
11	660	23	SALITRAL	\N
12	660	23	SAN ANTONIO	\N
13	660	23	SANTIAGO ABAJO	\N
14	660	23	SABANETA	\N
15	660	23	AGUAS VIVAS	\N
16	660	23	LAS BOCAS	\N
17	660	23	PISA FLORES	\N
20	660	23	EL ROBLE	\N
22	660	23	EL OLIVO	\N
23	660	23	BRUSELAS	\N
24	660	23	LOS BARRILES	\N
25	660	23	EL REMOLINO	\N
28	660	23	GUAIMARITO	\N
29	660	23	TREMENTINO	\N
30	660	23	GUAIMARO	\N
31	660	23	LA BALSA	\N
32	660	23	LAS AGUADITAS	\N
34	660	23	SAN ANDRESITO	\N
37	660	23	DIVIDIVI	\N
40	660	23	SALGUERITO	\N
47	660	23	KILOMETRO 32	\N
48	660	23	KILOMETRO 34	\N
54	660	23	LA MUSICA	\N
61	660	23	SAN FRANCISCO	\N
64	660	23	LA QUEBRADA	\N
66	660	23	LOS CHIBOLOS	\N
67	660	23	NUEVA ESPERANZA	\N
68	660	23	SABANA DE LA FUENTE	\N
0	670	23	SAN ANDRES DE SOTAVENTO	\N
2	670	23	CALLE LARGA	\N
3	670	23	EL BANCO	\N
5	670	23	LOS CARRETOS	\N
9	670	23	PUEBLECITO SUR	\N
13	670	23	PLAZA BONITA	\N
14	670	23	LAS CASITAS	\N
15	670	23	LOS CASTILLOS	\N
21	670	23	EL CONTENTO	\N
23	670	23	JEJEN	\N
25	670	23	CRUZ DE GUAYABO	\N
26	670	23	EL HOYAL	\N
28	670	23	BERLIN	\N
29	670	23	GARDENIA	\N
30	670	23	PATIO BONITO NORTE	\N
31	670	23	PATIO BONITO SUR	\N
32	670	23	SAN GREGORIO	\N
0	672	23	SAN ANTERO	\N
1	672	23	EL PORVENIR	\N
3	672	23	NUEVO AGRADO	\N
4	672	23	SANTA ROSA DEL BALSAMO	\N
5	672	23	TIJERETAS	\N
6	672	23	BIJAITO	\N
9	672	23	CALAO	\N
11	672	23	CISPATA	\N
12	672	23	EL NARANJO	\N
13	672	23	EL PROGRESO	\N
14	672	23	EL TRIBUTO	\N
15	672	23	GRAU	\N
16	672	23	LA BONGUITA	\N
17	672	23	LA PARRILLA	\N
19	672	23	LAS NUBES	\N
20	672	23	LETICIA	\N
21	672	23	PLAYA BLANCA	\N
22	672	23	PUNTA BOLIVAR	\N
23	672	23	SAN JOSE	\N
24	672	23	SANTA CRUZ	\N
25	672	23	BERNARDO ESCOBAR	\N
26	672	23	MIRIAM PARDO	\N
27	672	23	SAN MARTIN 1	\N
28	672	23	SAN MARTIN 2	\N
29	672	23	NARANJO 1	\N
0	675	23	SAN BERNARDO DEL VIENTO	\N
1	675	23	JOSE MANUEL DE ALTAMIRA	\N
3	675	23	PASO NUEVO	\N
5	675	23	PLAYAS DEL VIENTO	\N
7	675	23	TREMENTINO	\N
9	675	23	SAN BLAS DE JUNIN	\N
12	675	23	CHIQUI	\N
13	675	23	PAJONAL	\N
14	675	23	SAN JOSE DE LAS CANAS	\N
15	675	23	MIRAMAR	\N
17	675	23	BARCELONA	\N
18	675	23	CAMINO REAL	\N
19	675	23	EL CASTILLO	\N
21	675	23	TINAJONES	\N
23	675	23	EL DARIEN	\N
24	675	23	SANTA INES DE MONTERO	\N
0	678	23	SAN CARLOS	\N
1	678	23	EL CAMPANO	\N
2	678	23	CARRIZAL	\N
3	678	23	GUACHARACAL	\N
4	678	23	SANTA ROSA	\N
5	678	23	REMEDIO POBRE	\N
6	678	23	CABUYA	\N
8	678	23	CALLEMAR	\N
9	678	23	CIENAGUITA	\N
10	678	23	EL HATO	\N
11	678	23	SAN MIGUEL	\N
12	678	23	EL CHARCO	\N
16	678	23	CALLE LARGA	\N
17	678	23	CAROLINA	\N
18	678	23	LAS TINAS	\N
19	678	23	LOS CANOS	\N
0	682	23	SAN JOSE DE URE	\N
2	682	23	BOCAS DE URE	\N
3	682	23	BRAZO IZQUIERDO	\N
4	682	23	PUEBLO FLECHAS	\N
5	682	23	LA DORADA	\N
6	682	23	VERSALLES	\N
7	682	23	VIERA ABAJO	\N
9	682	23	PUERTO COLOMBIA	\N
0	686	23	SAN PELAYO	\N
1	686	23	BUENOS AIRES	\N
2	686	23	CARRILLO	\N
3	686	23	LA MADERA	\N
4	686	23	LAS GUAMAS	\N
5	686	23	SABANA NUEVA	\N
6	686	23	SAN ISIDRO	\N
7	686	23	VALPARAISO	\N
8	686	23	PUERTO NUEVO	\N
9	686	23	PELAYITO	\N
11	686	23	LAS LAURAS	\N
12	686	23	EL BONGO	\N
18	686	23	EL CHIQUI	\N
20	686	23	RETIRO	\N
21	686	23	EL OBLIGADO	\N
22	686	23	BONGAS MELLAS	\N
25	686	23	COROCITO	\N
27	686	23	EL BALSAMO	\N
29	686	23	EL COROZO	\N
34	686	23	MORROCOY	\N
36	686	23	PROVIDENCIA	\N
0	807	23	TIERRALTA	\N
1	807	23	CALLEJAS	\N
2	807	23	CARAMELO	\N
4	807	23	MANTAGORDAL	\N
5	807	23	NUEVA GRANADA	\N
6	807	23	EL SAIZA	\N
7	807	23	SANTA FE RALITO	\N
8	807	23	SEVERINERA	\N
10	807	23	VOLADOR	\N
17	807	23	FRASQUILLO NUEVO	\N
19	807	23	CARRIZOLA	\N
20	807	23	EL AGUILA - BATATA	\N
26	807	23	LOS MORALES	\N
27	807	23	SANTA MARTA	\N
28	807	23	VILLA PROVIDENCIA	\N
29	807	23	CRUCITO	\N
32	807	23	PUEBLO CEDRO	\N
34	807	23	BONITO VIENTO	\N
40	807	23	CAMPO BELLO	\N
41	807	23	LAS DELICIAS	\N
42	807	23	SAN RAFAEL	\N
43	807	23	EL ROSARIO	\N
44	807	23	VIRGILIO VARGAS	\N
45	807	23	NUEVA ESPERANZA	\N
0	815	23	TUCHIN	\N
1	815	23	BARBACOAS	\N
2	815	23	CRUZ CHIQUITA	\N
4	815	23	FLECHAS	\N
6	815	23	MOLINA	\N
7	815	23	NUEVA ESTRELLA	\N
9	815	23	SAN JUAN DE LA CRUZ	\N
10	815	23	VIDALES	\N
12	815	23	ANDES	\N
13	815	23	BELEN	\N
14	815	23	BELLA VISTA	\N
15	815	23	BOMBA	\N
16	815	23	CARRETAL	\N
17	815	23	EL CARINITO	\N
18	815	23	EL CARMEN	\N
19	815	23	EL CHUZO	\N
20	815	23	EL PORVENIR	\N
21	815	23	EL ROBLE	\N
22	815	23	GUAYACANES	\N
23	815	23	LOVERAN	\N
24	815	23	NUEVA ESPERANZA	\N
25	815	23	NUEVA VIDA	\N
26	815	23	SABANA NUEVA	\N
27	815	23	SABANAL	\N
28	815	23	SANTA CLARA	\N
29	815	23	SANTANDER	\N
30	815	23	TOLIMA	\N
31	815	23	VILLANUEVA	\N
32	815	23	EL BARZAL	\N
33	815	23	LA GRANJA	\N
34	815	23	SAN MARTIN	\N
0	855	23	VALENCIA	\N
1	855	23	RIO NUEVO	\N
3	855	23	VILLANUEVA	\N
6	855	23	MATA DE MAIZ	\N
9	855	23	EL REPOSO	\N
14	855	23	MIELES ABAJO	\N
15	855	23	SANTO DOMINGO	\N
16	855	23	MANZANARES	\N
17	855	23	SAN RAFAEL	\N
19	855	23	GUADUAL CENTRAL	\N
20	855	23	JERICO	\N
21	855	23	LA LIBERTAD	\N
23	855	23	LAS NUBES	\N
0	1	25	AGUA DE DIOS	\N
0	19	25	ALBAN	\N
1	19	25	CHIMBE (DANUBIO)	\N
2	19	25	PANTANILLO	\N
3	19	25	LA MARIA	\N
0	35	25	ANAPOIMA	\N
1	35	25	LA PAZ	\N
2	35	25	SAN ANTONIO DE ANAPOIMA	\N
3	35	25	PATIO BONITO	\N
0	40	25	ANOLAIMA	\N
2	40	25	LA FLORIDA	\N
3	40	25	REVENTONES	\N
5	40	25	BOQUERON DE ILO	\N
6	40	25	CORRALEJAS	\N
0	53	25	ARBELAEZ	\N
3	53	25	TISINCE	\N
0	86	25	BELTRAN	\N
1	86	25	PAQUILO	\N
2	86	25	LA POPA	\N
3	86	25	PUERTO GRAMALOTAL	\N
0	95	25	BITUIMA	\N
2	95	25	BOQUERON DE ILO	\N
3	95	25	LA SIERRA	\N
0	99	25	BOJACA	\N
2	99	25	SANTA BARBARA	\N
0	120	25	CABRERA	\N
0	123	25	CACHIPAY	\N
1	123	25	PENA NEGRA	\N
3	123	25	URBANIZACION TIERRA DE ENSUENO	\N
0	126	25	CAJICA	\N
3	126	25	RINCON SANTO	\N
4	126	25	RIO GRANDE	\N
5	126	25	CANELON	\N
6	126	25	LOS SERENEOS	\N
7	126	25	LOS PASOS	\N
8	126	25	LA FLORIDA	\N
9	126	25	CALAHORRA	\N
10	126	25	AGUANICA	\N
11	126	25	LA PALMA	\N
13	126	25	LA ESPERANZA	\N
14	126	25	CAMINO LOS VARGAS	\N
15	126	25	LOS LEON	\N
16	126	25	PRADO	\N
17	126	25	PABLO HERRERA	\N
18	126	25	SANTA INES	\N
19	126	25	BOSQUE MADERO	\N
20	126	25	QUINTAS DEL MOLINO	\N
21	126	25	VERDE VIVO	\N
22	126	25	VILLA DE LOS PINOS	\N
0	148	25	CAPARRAPI	\N
1	148	25	CAMBRAS	\N
3	148	25	EL DINDAL	\N
5	148	25	SAN PEDRO	\N
6	148	25	TATI	\N
7	148	25	CORDOBA	\N
9	148	25	SAN CARLOS	\N
10	148	25	CAMBULO	\N
12	148	25	SAN PABLO	\N
16	148	25	SAN RAMON ALTO	\N
0	151	25	CAQUEZA	\N
0	154	25	CARMEN DE CARUPA	\N
0	168	25	CHAGUANI	\N
0	175	25	CHIA	\N
2	175	25	SINDAMANOY I	\N
3	175	25	CUATRO ESQUINAS	\N
5	175	25	CERCA DE PIEDRA	\N
6	175	25	RINCON DE FAGUA	\N
10	175	25	CHIQUILINDA	\N
13	175	25	LA PAZ	\N
19	175	25	EL ESPEJO	\N
20	175	25	PUEBLO FUERTE	\N
21	175	25	PUENTE CACIQUE	\N
23	175	25	SANTA BARBARA	\N
25	175	25	VILLA JULIANA	\N
26	175	25	ENCENILLOS DE SINDAMANOY	\N
0	178	25	CHIPAQUE	\N
12	178	25	LLANO DE CHIPAQUE	\N
13	178	25	ABASTICOS	\N
0	181	25	CHOACHI	\N
1	181	25	ALTO DEL PALO	\N
3	181	25	POTRERO GRANDE	\N
0	183	25	CHOCONTA	\N
0	200	25	COGUA	\N
2	200	25	RODAMONTAL	\N
4	200	25	EL MORTINO	\N
5	200	25	LA PLAZUELA	\N
6	200	25	LA CHAPA	\N
8	200	25	EL CASCAJAL	\N
9	200	25	EL DURAZNO	\N
10	200	25	EL OLIVO	\N
12	200	25	SAN ANTONIO	\N
13	200	25	EL ATICO - SECTOR ALVAREZ	\N
14	200	25	RINCON SANTO - SECTOR ZAMORA	\N
0	214	25	COTA	\N
0	224	25	CUCUNUBA	\N
0	245	25	EL COLEGIO	\N
1	245	25	EL TRIUNFO	\N
2	245	25	LA VICTORIA	\N
3	245	25	PRADILLA	\N
4	245	25	LA PAZ	\N
0	258	25	EL PENON	\N
1	258	25	GUAYABAL DE TOLEDO	\N
2	258	25	TALAUTA	\N
0	260	25	EL ROSAL	\N
3	260	25	CRUZ VERDE	\N
4	260	25	PUENTE EL ROSAL	\N
5	260	25	SAN ANTONIO	\N
0	269	25	FACATATIVA	\N
1	269	25	SAN RAFAEL  BAJO	\N
7	269	25	LOS ANDES	\N
8	269	25	LA YERBABUENA	\N
9	269	25	ALTO DE CORDOBA	\N
10	269	25	LOS ARRAYANES	\N
12	269	25	LOS MANZANOS	\N
13	269	25	PASO ANCHO	\N
14	269	25	PUEBLO VIEJO	\N
17	269	25	SANTA MARTHA - LA ESPERANZA	\N
19	269	25	SAGRADO CORAZON	\N
20	269	25	VILLA MYRIAM	\N
21	269	25	LOS ROBLES	\N
22	269	25	SAN ISIDRO	\N
23	269	25	TIERRA GRATA ALTA	\N
24	269	25	TIERRA GRATA (EL CRUCE)	\N
35	269	25	SAN JOSE	\N
0	279	25	FOMEQUE	\N
1	279	25	LA UNION	\N
0	281	25	FOSCA	\N
2	281	25	SANAME	\N
0	286	25	FUNZA	\N
6	286	25	EL COCLI	\N
7	286	25	EL PAPAYO	\N
8	286	25	SAN ANTONIO LOS PINOS	\N
9	286	25	TIENDA NUEVA	\N
0	288	25	FUQUENE	\N
1	288	25	CAPELLANIA	\N
3	288	25	NUEVO FUQUENE	\N
0	290	25	FUSAGASUGA	\N
1	290	25	LA AGUADITA	\N
12	290	25	EL TRIUNFO BOQUERON	\N
14	290	25	LA CASCADA	\N
15	290	25	RIO BLANCO - LOS PUENTES	\N
16	290	25	CHINAUTA	\N
17	290	25	LAS PIRAMIDES	\N
0	293	25	GACHALA	\N
2	293	25	MONTECRISTO	\N
3	293	25	SANTA RITA DEL RIO NEGRO	\N
6	293	25	PALOMAS	\N
0	295	25	GACHANCIPA	\N
5	295	25	EL ROBLE SUR	\N
0	297	25	GACHETA	\N
8	297	25	LOS LOPEZ	\N
0	299	25	GAMA	\N
1	299	25	SAN ROQUE	\N
0	307	25	GIRARDOT	\N
1	307	25	SAN LORENZO	\N
4	307	25	BERLIN	\N
5	307	25	BARZALOSA	\N
6	307	25	PIAMONTE	\N
0	312	25	GRANADA	\N
7	312	25	LA VEINTIDOS	\N
13	312	25	SAN RAIMUNDO	\N
0	317	25	GUACHETA	\N
0	320	25	GUADUAS	\N
1	320	25	GUADUERO	\N
2	320	25	LA PAZ DE CALAMOIMA	\N
3	320	25	PUERTO BOGOTA	\N
8	320	25	ALTO DEL TRIGO	\N
9	320	25	LA CABANA	\N
0	322	25	GUASCA	\N
2	322	25	LA CABRERITA	\N
4	322	25	GAMBOA (EL PLACER)	\N
0	324	25	GUATAQUI	\N
1	324	25	EL PORVENIR	\N
2	324	25	LAS ISLAS	\N
0	326	25	GUATAVITA	\N
0	328	25	GUAYABAL DE SIQUIMA	\N
1	328	25	ALTO DEL TRIGO	\N
0	335	25	GUAYABETAL	\N
2	335	25	MONTERREDONDO	\N
3	335	25	LAS MESAS	\N
4	335	25	LIMONCITOS	\N
5	335	25	SAN ANTONIO	\N
6	335	25	SAN MIGUEL	\N
8	335	25	LAS MESETAS	\N
0	339	25	GUTIERREZ	\N
1	339	25	PASCOTE	\N
2	339	25	SAN ANTONIO	\N
0	368	25	JERUSALEN	\N
0	372	25	JUNIN	\N
1	372	25	CLARAVAL	\N
2	372	25	CHUSCALES	\N
4	372	25	SUEVA	\N
6	372	25	PUENTE LISIO	\N
7	372	25	RAMAL	\N
8	372	25	SAN FRANCISCO	\N
0	377	25	LA CALERA	\N
2	377	25	MUNDONUEVO	\N
3	377	25	EL SALITRE	\N
8	377	25	TREINTA Y SEIS	\N
10	377	25	ALTAMAR	\N
11	377	25	EL MANZANO	\N
12	377	25	LA AURORA ALTA	\N
13	377	25	LA CAPILLA	\N
14	377	25	MARQUEZ	\N
15	377	25	SAN CAYETANO	\N
16	377	25	SAN JOSE DEL TRIUNFO	\N
0	386	25	LA MESA	\N
1	386	25	LA ESPERANZA	\N
2	386	25	SAN JAVIER	\N
3	386	25	SAN JOAQUIN	\N
0	394	25	LA PALMA	\N
0	398	25	LA PENA	\N
0	402	25	LA VEGA	\N
2	402	25	EL VINO	\N
0	407	25	LENGUAZAQUE	\N
0	426	25	MACHETA	\N
0	430	25	MADRID	\N
1	430	25	LA CUESTA	\N
3	430	25	EL CORZO	\N
4	430	25	PUENTE DE PIEDRA	\N
5	430	25	CHAUTA	\N
6	430	25	MOYANO	\N
0	436	25	MANTA	\N
0	438	25	MEDINA	\N
3	438	25	SAN PEDRO DE GUAJARAY	\N
4	438	25	SANTA TERESITA	\N
5	438	25	MESA DE LOS REYES	\N
6	438	25	LOS ALPES	\N
10	438	25	LA ESMERALDA	\N
11	438	25	GAZADUJE	\N
0	473	25	MOSQUERA	\N
4	473	25	LOS PUENTES	\N
7	473	25	PARCELAS	\N
8	473	25	PENCAL	\N
9	473	25	QUINTAS DE SERREZUELA	\N
0	483	25	NARINO	\N
0	486	25	NEMOCON	\N
1	486	25	PATIO BONITO	\N
2	486	25	EL ORATORIO	\N
3	486	25	LA PUERTA	\N
5	486	25	CAMACHO	\N
6	486	25	EL PLAN	\N
0	488	25	NILO	\N
1	488	25	LA ESMERALDA	\N
2	488	25	PUEBLO NUEVO	\N
3	488	25	EL REDIL	\N
0	489	25	NIMAIMA	\N
1	489	25	TOBIA	\N
0	491	25	NOCAIMA	\N
1	491	25	TOBIA CHICA	\N
0	506	25	VENECIA	\N
1	506	25	APOSENTOS	\N
5	506	25	EL TREBOL	\N
0	513	25	PACHO	\N
1	513	25	PASUNCHA	\N
0	518	25	PAIME	\N
1	518	25	CUATRO CAMINOS	\N
2	518	25	TUDELA	\N
4	518	25	VENECIA	\N
0	524	25	PANDI	\N
0	530	25	PARATEBUENO	\N
1	530	25	MAYA	\N
2	530	25	SANTA CECILIA	\N
3	530	25	EL ENGANO	\N
6	530	25	EL JAPON	\N
7	530	25	CABULLARITO	\N
0	535	25	PASCA	\N
2	535	25	GUCHIPAS	\N
0	572	25	PUERTO SALGAR	\N
1	572	25	COLORADOS	\N
3	572	25	PUERTO LIBRE	\N
5	572	25	MORRO COLORADO	\N
0	580	25	PULI	\N
2	580	25	PALESTINA	\N
0	592	25	QUEBRADANEGRA	\N
1	592	25	LA MAGDALENA	\N
3	592	25	TOBIA - LA MILAGROSA	\N
0	594	25	QUETAME	\N
2	594	25	PUENTE QUETAME	\N
3	594	25	GUACAPATE	\N
0	596	25	QUIPILE	\N
1	596	25	LA SIERRA	\N
2	596	25	LA VIRGEN	\N
3	596	25	SANTA MARTA	\N
4	596	25	LA BOTICA	\N
0	599	25	APULO	\N
0	612	25	RICAURTE	\N
1	612	25	MANUEL SUR	\N
2	612	25	EL PASO	\N
3	612	25	EL PORTAL	\N
4	612	25	LAS VARAS	\N
6	612	25	SAN MARCOS POBLADO	\N
7	612	25	SAN MARTIN	\N
0	645	25	SAN ANTONIO DEL TEQUENDAMA	\N
1	645	25	SANTANDERCITO	\N
11	645	25	PUEBLO NUEVO	\N
16	645	25	BELLAVISTA	\N
17	645	25	PRADILLA	\N
18	645	25	LOS NARANJOS	\N
19	645	25	VILLA PRADILLA	\N
20	645	25	VILLA SHYN (CASAS MOVILES)	\N
0	649	25	SAN BERNARDO	\N
3	649	25	PORTONES	\N
0	653	25	PUEBLO NUEVO	\N
1	653	25	CAMANCHA	\N
2	653	25	CUIBUCO	\N
3	653	25	LAS MERCEDES	\N
5	653	25	ALBERGUE	\N
0	658	25	SAN FRANCISCO	\N
0	662	25	SAN JUAN DE RIOSECO	\N
1	662	25	CAMBAO	\N
2	662	25	SAN NICOLAS	\N
0	718	25	SASAIMA	\N
1	718	25	SANTA INES	\N
2	718	25	SANTA CRUZ	\N
0	736	25	SESQUILE	\N
2	736	25	LA PLAYA	\N
3	736	25	BOITIVA SAN ROQUE	\N
4	736	25	SIATOYA	\N
0	740	25	SIBATE	\N
4	740	25	SAN BENITO CENTRO	\N
5	740	25	CHACUA CENTRO	\N
7	740	25	PERICO SECTOR LA HONDA	\N
8	740	25	PERICO SECTOR LA MACARENA	\N
10	740	25	SAN FORTUNATO SECTOR LOS ZORROS	\N
11	740	25	SAN MIGUEL	\N
12	740	25	LA UNION SECTOR LA UNION	\N
13	740	25	LA UNION SECTOR PIE DE ALTO	\N
14	740	25	SAN BENITO SECTOR JAZMIN	\N
0	743	25	SILVANIA	\N
2	743	25	AZAFRANAL	\N
5	743	25	SUBIA	\N
6	743	25	AGUA BONITA	\N
0	745	25	SIMIJACA	\N
3	745	25	EL RETEN	\N
5	745	25	SANTA LUCIA	\N
0	754	25	SOACHA	\N
1	754	25	CHARQUITO	\N
11	754	25	CHACUA CABRERA	\N
0	758	25	SOPO	\N
8	758	25	HATOGRANDE	\N
9	758	25	GRATAMIRA	\N
10	758	25	MERCENARIO	\N
11	758	25	LA DIANA	\N
12	758	25	PUEBLO VIEJO SECTOR NINO	\N
0	769	25	SUBACHOQUE	\N
2	769	25	LA PRADERA	\N
3	769	25	GALDAMEZ	\N
6	769	25	CASCAJAL	\N
7	769	25	LLANITOS	\N
0	772	25	SUESCA	\N
1	772	25	HATO GRANDE	\N
2	772	25	SANTA ROSITA	\N
4	772	25	CACICAZGO	\N
0	777	25	SUPATA	\N
1	777	25	LA MAGOLA	\N
0	779	25	SUSA	\N
0	781	25	SUTATAUSA	\N
2	781	25	LAS PENAS	\N
3	781	25	CHIRCAL DE SANTA BARBARA	\N
4	781	25	LA QUINTA	\N
0	785	25	TABIO	\N
1	785	25	CARRON	\N
2	785	25	EL PENCIL	\N
3	785	25	PARCELACION TERMALES	\N
5	785	25	CHICU	\N
7	785	25	EL BOTE	\N
10	785	25	TERPEL	\N
11	785	25	LOURDES	\N
0	793	25	TAUSA	\N
2	793	25	ROMA (TAUSA VIEJO)	\N
3	793	25	BOQUERON	\N
5	793	25	DIVINO NINO	\N
0	797	25	TENA	\N
1	797	25	LA GRAN VIA	\N
0	799	25	TENJO	\N
1	799	25	LA PUNTA	\N
7	799	25	PAN DE AZUCAR	\N
8	799	25	EL PALMAR	\N
9	799	25	GRATAMIRA	\N
10	799	25	BARRIO LOS CATADI	\N
11	799	25	CASCAJERA	\N
12	799	25	LOS PINOS	\N
14	799	25	JUAICA	\N
15	799	25	ZOQUE	\N
0	805	25	TIBACUY	\N
1	805	25	BATEAS	\N
2	805	25	CUMACA	\N
0	807	25	TIBIRITA	\N
0	815	25	TOCAIMA	\N
1	815	25	PUBENZA	\N
3	815	25	LA SALADA	\N
7	815	25	LA COLORADA	\N
8	815	25	SAN CARLOS	\N
0	817	25	TOCANCIPA	\N
1	817	25	CHAUTA	\N
2	817	25	DULCINEA	\N
3	817	25	PELPAK	\N
4	817	25	SAN JAVIER	\N
5	817	25	TOLIMA - MILENION	\N
6	817	25	LA FUENTE	\N
7	817	25	CETINA	\N
8	817	25	ANTONIA SANTOS	\N
12	817	25	CHICALA	\N
13	817	25	LAS QUINTAS	\N
15	817	25	COLPAPEL	\N
17	817	25	SAN VICTORINO	\N
18	817	25	CAMINOS DE SIE	\N
19	817	25	CHICO NORTE	\N
20	817	25	BUENOS AIRES	\N
21	817	25	EL PORVENIR	\N
22	817	25	EL DIVINO NINO	\N
23	817	25	LOS MANZANOS	\N
0	823	25	TOPAIPI	\N
1	823	25	SAN ANTONIO DE AGUILERA	\N
2	823	25	EL NARANJAL	\N
0	839	25	UBALA	\N
2	839	25	LAGUNA AZUL	\N
3	839	25	MAMBITA	\N
4	839	25	SAN PEDRO DE JAGUA	\N
5	839	25	SANTA ROSA	\N
6	839	25	LA PLAYA	\N
9	839	25	SOYA	\N
10	839	25	SANTA BARBARA	\N
0	841	25	UBAQUE	\N
0	843	25	VILLA DE SAN DIEGO DE UBATE	\N
1	843	25	GUATANCUY	\N
5	843	25	VOLCAN BAJO	\N
7	843	25	SAN LUIS	\N
9	843	25	PALOGORDO	\N
10	843	25	CENTRO DEL LLANO	\N
11	843	25	TAUSAVITA BAJO	\N
14	843	25	TAUSAVITA ALTO	\N
0	845	25	UNE	\N
2	845	25	TIMASITA	\N
0	851	25	UTICA	\N
0	862	25	VERGARA	\N
2	862	25	GUACAMAYAS	\N
4	862	25	VILLA OLARTE	\N
5	862	25	CERINZA	\N
6	862	25	CORCEGA	\N
0	867	25	VIANI	\N
1	867	25	ALTO EL ROSARIO	\N
0	871	25	VILLAGOMEZ	\N
0	873	25	VILLAPINZON	\N
1	873	25	SOATAMA	\N
0	875	25	VILLETA	\N
1	875	25	BAGAZAL	\N
4	875	25	EL PUENTE	\N
0	878	25	VIOTA	\N
2	878	25	SAN GABRIEL	\N
3	878	25	EL PINAL	\N
4	878	25	LIBERIA	\N
0	885	25	YACOPI	\N
2	885	25	GUADUALITO	\N
4	885	25	IBAMA	\N
6	885	25	LLANO MATEO	\N
8	885	25	TERAN	\N
9	885	25	APOSENTOS	\N
18	885	25	PATEVACA	\N
21	885	25	EL CASTILLO	\N
0	898	25	ZIPACON	\N
1	898	25	EL OCASO	\N
4	898	25	LA CAPILLA	\N
5	898	25	LA ESTACION	\N
6	898	25	CARTAGENA	\N
7	898	25	LA CABANA	\N
0	899	25	ZIPAQUIRA	\N
1	899	25	LA GRANJA	\N
2	899	25	BARANDILLAS	\N
5	899	25	RIO FRIO	\N
6	899	25	PASOANCHO	\N
7	899	25	SAN JORGE PALO BAJO	\N
8	899	25	SAN JORGE PALO ALTO	\N
9	899	25	ALTO DEL AGUILA	\N
10	899	25	APOSENTOS ALTOS	\N
11	899	25	BOLIVAR 83	\N
12	899	25	BOSQUES DE SILECIA	\N
14	899	25	EL EMPALIZADO	\N
15	899	25	EL RUDAL	\N
17	899	25	LOTEO LA PAZ - BOMBA TERPEL - LOTEO SUSAGUA	\N
19	899	25	LOTEO SANTA ISABEL	\N
20	899	25	PORTACHUELO	\N
23	899	25	SAN GABRIEL	\N
25	899	25	SAN MIGUEL	\N
26	899	25	SANTIAGO PEREZ	\N
28	899	25	LA MARIELA	\N
29	899	25	EL CODITO	\N
30	899	25	EL KIOSKO LA GRANJA	\N
31	899	25	LA ESCUELA	\N
34	899	25	ARGELIA	\N
35	899	25	ARGELIA II	\N
36	899	25	ARGELIA III	\N
37	899	25	MALAGON	\N
0	1	27	SAN FRANCISCO DE QUIBDO	\N
8	1	27	BOCA DE TANANDO	\N
11	1	27	CALAHORRA	\N
13	1	27	CAMPOBONITO	\N
15	1	27	GUARANDO	\N
16	1	27	GUAYABAL	\N
17	1	27	LA TROJE	\N
18	1	27	LAS MERCEDES	\N
20	1	27	SAN RAFAEL DE NEGUA	\N
24	1	27	SAN FRANCISCO DE ICHO	\N
29	1	27	TAGACHI	\N
32	1	27	TUTUNENDO	\N
35	1	27	GUADALUPE	\N
36	1	27	GITRADO	\N
37	1	27	MOJAUDO	\N
38	1	27	SANCENO	\N
44	1	27	BOCA DE NAURITA	\N
47	1	27	EL FUERTE	\N
48	1	27	SAN ANTONIO DE ICHO	\N
52	1	27	BOCA DE NEMOTA (NEMOTA)	\N
54	1	27	PACURITA (CABI)	\N
60	1	27	VILLA DEL ROSARIO	\N
61	1	27	WINANDO	\N
63	1	27	BARRANCO	\N
66	1	27	SAN JOAQUIN	\N
67	1	27	EL 21	\N
0	6	27	ACANDI	\N
3	6	27	CAPURGANA	\N
5	6	27	LA CALETA	\N
7	6	27	SAN FRANCISCO	\N
8	6	27	SAN MIGUEL	\N
9	6	27	CHIGANDI	\N
10	6	27	SAPZURRO	\N
16	6	27	PENALOSA	\N
0	25	27	PIE DE PATO	\N
2	25	27	AMPARRADO	\N
3	25	27	APARTADO	\N
4	25	27	CHACHAJO	\N
6	25	27	NAUCAS	\N
7	25	27	SAN FRANCISCO DE CUGUCHO	\N
8	25	27	SANTA CATALINA DE CATRU	\N
10	25	27	YUCAL	\N
11	25	27	BATATAL	\N
12	25	27	BELLA VISTA	\N
13	25	27	CHIGORODO	\N
14	25	27	EL SALTO (BELLA LUZ)	\N
15	25	27	DOCACINA	\N
16	25	27	DOMINICO	\N
17	25	27	GEANDO	\N
18	25	27	IRUTO	\N
19	25	27	LA DIVISA	\N
20	25	27	LA FELICIA	\N
21	25	27	LA LOMA	\N
22	25	27	PAVARANDO (PUREZA)	\N
23	25	27	LAS DELICIAS	\N
24	25	27	MOJAUDO	\N
25	25	27	NUNCIDO	\N
27	25	27	PUESTO INDIO	\N
28	25	27	SANTA MARIA DE CONDOTO	\N
30	25	27	AMPARRAIDA (SANTA RITA)	\N
31	25	27	PUERTO MARTINEZ	\N
32	25	27	GUINEO	\N
33	25	27	MIACORA	\N
34	25	27	PLAYITA	\N
35	25	27	PUERTO ALEGRE	\N
36	25	27	PUERTO CORDOBA URUDO	\N
37	25	27	PUERTO ECHEVERRY	\N
38	25	27	PUERTO LIBIA	\N
39	25	27	PUNTO CAIMINTO	\N
40	25	27	BOCA DE LEON	\N
41	25	27	NUEVO PLATANARES	\N
0	50	27	YUTO	\N
1	50	27	ARENAL	\N
2	50	27	DONA JOSEFA	\N
3	50	27	SAMURINDO	\N
4	50	27	REAL DE TANANDO	\N
5	50	27	MOTOLDO	\N
6	50	27	SAN JOSE DE PURRE	\N
7	50	27	SAN MARTIN DE PURRE	\N
8	50	27	LA MOLANA	\N
9	50	27	PUENTE DE TANANDO	\N
10	50	27	PUENTE DE PAIMADO	\N
13	50	27	LA TOMA	\N
17	50	27	REAL DE TANANDO (2DO)	\N
18	50	27	VARIANTE DE MOTOLDO	\N
0	73	27	BAGADO	\N
3	73	27	DABAIBE	\N
4	73	27	ENGRIVADO	\N
5	73	27	LA SIERRA	\N
6	73	27	PIEDRA HONDA	\N
7	73	27	SAN MARINO	\N
9	73	27	EL SALTO	\N
10	73	27	PLAYA BONITA	\N
11	73	27	VIVICORA	\N
12	73	27	PESCADITO	\N
13	73	27	CUAJANDO	\N
14	73	27	MUCHICHI	\N
15	73	27	OCHOA	\N
0	75	27	CIUDAD MUTIS	\N
1	75	27	CUPICA	\N
2	75	27	EL VALLE	\N
3	75	27	HUACA	\N
4	75	27	HUINA	\N
6	75	27	MECANA	\N
7	75	27	NABUGA	\N
9	75	27	PLAYITA POTE	\N
0	77	27	PIZARRO	\N
2	77	27	BELEN DE DOCAMPODO	\N
5	77	27	CUEVITA	\N
6	77	27	DOTENEDO	\N
7	77	27	HIJUA	\N
8	77	27	ORPUA	\N
9	77	27	PAVASA	\N
11	77	27	PILIZA	\N
12	77	27	PLAYITA	\N
14	77	27	PUNTA PURRICHA	\N
16	77	27	SIVIRU	\N
19	77	27	VIRUDO	\N
22	77	27	PUNTA DE IGUA	\N
24	77	27	VILLA MARIA	\N
26	77	27	GUINEAL	\N
29	77	27	USARAGA	\N
32	77	27	PUERTO ABADIA	\N
0	99	27	BELLAVISTA	\N
1	99	27	ALFONSO LOPEZ	\N
2	99	27	LA LOMA DE BOJAYA	\N
3	99	27	ISLA DE LOS PALACIOS	\N
4	99	27	LA BOBA	\N
5	99	27	NAPIPI	\N
6	99	27	BOCA DE OPOGADO	\N
8	99	27	PUERTO CONTO	\N
9	99	27	SAN JOSE	\N
11	99	27	VERACRUZ	\N
12	99	27	POGUE	\N
13	99	27	MESOPOTAMIA	\N
15	99	27	EL TIGRE	\N
21	99	27	CORAZON DE JESUS	\N
29	99	27	PICHICORA	\N
30	99	27	PIEDRA CANDELA	\N
0	135	27	MANAGRU	\N
1	135	27	BOCA DE RASPADURA	\N
3	135	27	PUERTO PERVEL	\N
4	135	27	TARIDO	\N
5	135	27	GUAPANDO	\N
7	135	27	LA ISLA	\N
0	150	27	CURBARADO	\N
1	150	27	BRISAS	\N
2	150	27	DOMINGODO	\N
3	150	27	LA GRANDE	\N
4	150	27	PUERTO LLERAS	\N
5	150	27	TURRIQUITADO	\N
6	150	27	VIGIA DE CURBADO	\N
7	150	27	VILLA NUEVA DE MONTANO	\N
8	150	27	APARTADO BUENA VISTA	\N
12	150	27	CHICAO	\N
14	150	27	LA MADRE	\N
17	150	27	CHINTADO MEDIO	\N
0	160	27	CERTEGUI	\N
1	160	27	LA TOMA	\N
2	160	27	PARECITO	\N
3	160	27	PAREDES	\N
6	160	27	MEMERA	\N
7	160	27	OGODO	\N
8	160	27	LA VUELTA	\N
9	160	27	LAS HAMACAS	\N
10	160	27	SAN JORGE	\N
0	205	27	CONDOTO	\N
6	205	27	MANDINGA	\N
7	205	27	OPOGODO	\N
8	205	27	SANTA ANA	\N
14	205	27	LA PLANTA	\N
15	205	27	ILARIA	\N
16	205	27	CONSUELO ANDRAPEDA	\N
17	205	27	EL PASO	\N
0	245	27	EL CARMEN DE ATRATO	\N
4	245	27	LA MANSA	\N
5	245	27	EL PORVENIR	\N
6	245	27	EL SIETE	\N
8	245	27	EL 18	\N
9	245	27	EL 21	\N
10	245	27	LA PAZ	\N
0	250	27	SANTA GENOVEVA DE DOCORDO	\N
1	250	27	COPOMA	\N
2	250	27	CUCURRUPI	\N
3	250	27	QUEBRADA DE TOGOROMA	\N
4	250	27	CORRIENTE PALO	\N
5	250	27	PICHIMA	\N
6	250	27	PALESTINA	\N
7	250	27	CHARAMBIRA	\N
9	250	27	DESCOLGADERO	\N
10	250	27	EL COCO	\N
12	250	27	QUICHARO	\N
17	250	27	LAS PENITAS	\N
18	250	27	LOS PEREA	\N
19	250	27	MUNGUIDO	\N
20	250	27	CHAPPIEN	\N
21	250	27	BURUJON	\N
22	250	27	PANGALITA	\N
23	250	27	PAPAYO	\N
25	250	27	CARRA	\N
26	250	27	PUERTO MURILLO	\N
28	250	27	QUEBRADA DE PICHIMA	\N
29	250	27	PANGALA	\N
30	250	27	SAN JOSE	\N
31	250	27	TAPARAL	\N
32	250	27	TAPARALITO	\N
34	250	27	TORDO	\N
35	250	27	TROJITA	\N
36	250	27	VENADO	\N
39	250	27	UNION VALSALITO	\N
40	250	27	BARRIOS UNIDOS	\N
41	250	27	CABECERA	\N
42	250	27	LAS DELICIAS	\N
43	250	27	UNION GUAIMIA	\N
0	361	27	ISTMINA	\N
4	361	27	BASURU	\N
9	361	27	PRIMAVERA	\N
29	361	27	SAN ANTONIO	\N
32	361	27	SURUCO SANTA MONICA	\N
37	361	27	CARMELITA	\N
40	361	27	GUINIGUINI	\N
41	361	27	JUANA MARCELA	\N
42	361	27	PAITO	\N
43	361	27	PLAYA GRANDE	\N
44	361	27	CHIGORODO (PUERTO SALAZAR)	\N
49	361	27	PRIMERA MOJARRA	\N
0	372	27	JURADO	\N
4	372	27	PUNTA ARDITA	\N
7	372	27	PUNTA PINA	\N
0	413	27	LLORO	\N
3	413	27	LA VUELTA	\N
4	413	27	LAS HAMACAS	\N
7	413	27	BORAUDO	\N
9	413	27	NIPORDU	\N
10	413	27	OGODO	\N
11	413	27	VILLA CLARET	\N
13	413	27	BOCA DE CAPA	\N
14	413	27	BOCAS DE TUMUTUMBUDO	\N
15	413	27	CANCHIDO	\N
16	413	27	LA PLAYA	\N
17	413	27	PLAYA ALTA	\N
18	413	27	PUERTO MORENO	\N
19	413	27	SAN JORGE	\N
0	425	27	BETE	\N
1	425	27	BOCA DE AME	\N
2	425	27	BOCA DE BEBARA	\N
3	425	27	CAMPOALEGRE	\N
4	425	27	EL LLANO DE BEBARA	\N
5	425	27	EL LLANO DE BEBARAMA	\N
6	425	27	SAN ANTONIO DEL BUEY (CAMPO SANTO)	\N
7	425	27	SAN JOSE DE BUEY	\N
8	425	27	SAN ROQUE	\N
9	425	27	TANGUI	\N
10	425	27	AGUA CLARA	\N
11	425	27	BAUDO GRANDE	\N
12	425	27	MEDIO BETE	\N
13	425	27	PUERTO SALAZAR	\N
14	425	27	PUNE	\N
16	425	27	SAN FRANCISCO DE TAUCHIGADO	\N
0	430	27	PUERTO MELUK	\N
2	430	27	ARENAL	\N
3	430	27	BOCA DE BAUDOCITO	\N
4	430	27	BELLA VISTA	\N
5	430	27	BERIGUADO	\N
6	430	27	CURUNDO LA BANCA	\N
7	430	27	PUERTO PLATANARES	\N
9	430	27	PIE DE PEPE	\N
10	430	27	PUERTO ADAN	\N
13	430	27	SAN MIGUEL BAUDOCITO	\N
23	430	27	QUERA	\N
24	430	27	BOCA DE PEPE	\N
26	430	27	UNION MISARA	\N
28	430	27	BERRECUY	\N
29	430	27	BOCA DE CURUNDO	\N
30	430	27	CURUNDO LA LOMA	\N
31	430	27	PUERTO CORDOBA	\N
32	430	27	PUERTO LIBIA	\N
0	450	27	ANDAGOYA	\N
1	450	27	BEBEDO	\N
2	450	27	BOCA DE SURUCO	\N
3	450	27	CHIQUICHOQUI	\N
4	450	27	DIPURDU EL GUASIMO	\N
5	450	27	EL GUAMO	\N
7	450	27	LA RANCHA	\N
8	450	27	NOANAMA	\N
12	450	27	SAN MIGUEL	\N
13	450	27	FUJIADO	\N
14	450	27	ISLA DE CRUZ	\N
15	450	27	LA UNION	\N
16	450	27	MACEDONIA	\N
17	450	27	PUERTO MURILLO	\N
18	450	27	SAN JERONIMO	\N
19	450	27	UNION WAUNAAN	\N
0	491	27	NOVITA	\N
1	491	27	EL CAJON	\N
2	491	27	EL TIGRE	\N
3	491	27	IRABUBU	\N
4	491	27	JUNTAS DE TAMANA	\N
6	491	27	SAN LORENZO	\N
7	491	27	SESEGO	\N
8	491	27	URABARA	\N
9	491	27	CURUNDO	\N
10	491	27	EL TAMBITO	\N
13	491	27	CARMEN DE SURAMA	\N
14	491	27	SANTA ROSA	\N
15	491	27	LA PUENTE	\N
16	491	27	PINDAZA	\N
17	491	27	QUEBRADA LARGA	\N
18	491	27	TORRA	\N
0	495	27	NUQUI	\N
1	495	27	ARUSI	\N
2	495	27	COQUI	\N
3	495	27	JURUBIRA	\N
4	495	27	PANGUI	\N
5	495	27	TRIBUGA	\N
7	495	27	PARTADO	\N
8	495	27	JOVI	\N
10	495	27	TERMALES	\N
11	495	27	BOCA DE JAGUA	\N
12	495	27	PUERTO INDIO	\N
13	495	27	VILLA NUEVA	\N
0	580	27	SANTA RITA	\N
1	580	27	ALTO CHATO	\N
4	580	27	ENCHARCAZON	\N
5	580	27	SAN JOSE DE VIRO VIRO	\N
6	580	27	SANTA BARBARA	\N
7	580	27	CHARA	\N
8	580	27	EL BUEY	\N
9	580	27	LA GUAMA	\N
10	580	27	TODOSITICO	\N
11	580	27	VIRO	\N
0	600	27	PAIMADO	\N
1	600	27	BOCA DE APARTADO	\N
3	600	27	SAN ISIDRO	\N
4	600	27	VILLA CONTO	\N
6	600	27	CHIGUARANDO ALTO	\N
7	600	27	CHIVIGUIDO	\N
10	600	27	LA SOLEDAD	\N
11	600	27	LOMA DE LOS GAMBOA	\N
0	615	27	RIOSUCIO	\N
6	615	27	LA HONDA	\N
12	615	27	TRUANDO	\N
19	615	27	LA RAYA	\N
22	615	27	PERANCHITO	\N
23	615	27	BELEN DE BAJIRA	\N
24	615	27	LA ISLETA	\N
27	615	27	PUENTE AMERICA - CACARICA	\N
30	615	27	PEDEGUITA	\N
32	615	27	BRASITO	\N
33	615	27	BLANQUISET	\N
34	615	27	CAMPO ALEGRE	\N
37	615	27	MACONDO	\N
38	615	27	NUEVO ORIENTE	\N
39	615	27	PLAYA ROJA	\N
45	615	27	7 DE AGOSTO	\N
46	615	27	LA PUNTA	\N
47	615	27	SANTA MARIA	\N
48	615	27	BRISAS	\N
49	615	27	CHINTADO MEDIO	\N
0	660	27	SAN JOSE DEL PALMAR	\N
3	660	27	SAN PEDRO INGARA	\N
6	660	27	LA ITALIA	\N
0	745	27	SIPI	\N
1	745	27	CANAVERAL	\N
3	745	27	SAN AGUSTIN	\N
4	745	27	TAPARAL	\N
9	745	27	TANANDO	\N
10	745	27	BUENAS BRISAS	\N
11	745	27	CHAMBACU	\N
12	745	27	LOMA DE CHUPEY	\N
13	745	27	MARQUEZA	\N
14	745	27	SANTA ROSA	\N
15	745	27	TEATINO	\N
16	745	27	BARRANCON	\N
17	745	27	BARRANCONCITO	\N
18	745	27	CHARCO HONDO	\N
19	745	27	CHARCO LARGO	\N
20	745	27	PLAYA RICA	\N
0	787	27	TADO	\N
2	787	27	CARMELO	\N
4	787	27	TAPON	\N
5	787	27	GUARATO	\N
9	787	27	MUMBU	\N
10	787	27	PLAYA DE ORO	\N
15	787	27	CORCOBADO	\N
16	787	27	MANUNGARA	\N
21	787	27	TABOR	\N
22	787	27	ANGOSTURA	\N
26	787	27	GINGARABA	\N
0	800	27	UNGUIA	\N
1	800	27	BALBOA	\N
2	800	27	GILGAL	\N
3	800	27	SANTA MARIA DEL DARIEN	\N
4	800	27	TANELA	\N
5	800	27	TITUMATE	\N
7	800	27	BETECITO	\N
8	800	27	MARRIAGA	\N
11	800	27	EL PUERTO	\N
12	800	27	EL ROTO	\N
15	800	27	ARQUIA	\N
0	810	27	ANIMAS	\N
1	810	27	EL PLAN DE RASPADURA	\N
2	810	27	LA YE	\N
3	810	27	SAN RAFAEL DEL DOS	\N
4	810	27	SAN PABLO ADENTRO	\N
0	1	41	NEIVA	\N
1	1	41	CAGUAN	\N
2	1	41	CHAPINERO	\N
3	1	41	FORTALECILLAS	\N
4	1	41	GUACIRCO	\N
6	1	41	ORGANOS	\N
7	1	41	PALACIOS	\N
8	1	41	PENAS BLANCAS	\N
11	1	41	SAN LUIS	\N
12	1	41	VEGALARGA	\N
13	1	41	EL TRIUNFO	\N
14	1	41	SAN FRANCISCO	\N
16	1	41	EL COLEGIO	\N
17	1	41	SAN ANTONIO DE ANACONIA	\N
18	1	41	AIPECITO	\N
22	1	41	EL VENADO	\N
24	1	41	PIEDRA MARCADA	\N
29	1	41	CEDRALITO	\N
30	1	41	LA MATA	\N
31	1	41	PRADERA	\N
32	1	41	CEDRAL	\N
33	1	41	LA JULIA	\N
34	1	41	SAN JORGE	\N
35	1	41	SANTA BARBARA	\N
36	1	41	MOSCOVIA	\N
0	6	41	ACEVEDO	\N
1	6	41	SAN ADOLFO	\N
3	6	41	PUEBLO VIEJO	\N
5	6	41	SAN MARCOS	\N
11	6	41	EL CARMEN	\N
0	13	41	AGRADO	\N
1	13	41	LA CANADA	\N
3	13	41	SAN JOSE DE BELEN	\N
0	16	41	AIPE	\N
1	16	41	PRAGA	\N
2	16	41	SANTA RITA	\N
3	16	41	EL PATA	\N
4	16	41	CRUCE DE GUACIRCO	\N
5	16	41	LA CEJA - MESITAS	\N
0	20	41	ALGECIRAS	\N
1	20	41	EL PARAISO VIEJO	\N
2	20	41	LA ARCADIA	\N
3	20	41	EL TORO	\N
6	20	41	EL PARAISO NUEVO	\N
0	26	41	ALTAMIRA	\N
4	26	41	LLANO DE LA VIRGEN	\N
5	26	41	PUENTE	\N
0	78	41	BARAYA	\N
10	78	41	LA UNION	\N
11	78	41	TURQUESTAN	\N
0	132	41	CAMPOALEGRE	\N
1	132	41	LA VEGA	\N
2	132	41	OTAS	\N
3	132	41	BAJO PIRAVANTE	\N
4	132	41	RIO NEIVA	\N
5	132	41	LA ESPERANZA	\N
6	132	41	LOS ROSALES	\N
0	206	41	COLOMBIA	\N
2	206	41	SANTANA	\N
5	206	41	SAN MARCOS	\N
0	244	41	ELIAS	\N
1	244	41	EL VISO	\N
2	244	41	ORITOGUAZ	\N
0	298	41	GARZON	\N
1	298	41	EL RECREO	\N
2	298	41	LA JAGUA	\N
3	298	41	SAN ANTONIO DEL PESCADO	\N
4	298	41	ZULUAGA	\N
5	298	41	EL PARAISO	\N
7	298	41	PROVIDENCIA	\N
8	298	41	EL MESON	\N
12	298	41	PLAZUELA	\N
13	298	41	CAGUANCITO	\N
14	298	41	EL DESCANSO	\N
15	298	41	MAJO	\N
16	298	41	SAN GERARDO	\N
17	298	41	SANTA MARTA	\N
19	298	41	JAGUALITO	\N
20	298	41	LA CABANA	\N
21	298	41	SAN LUIS	\N
0	306	41	GIGANTE	\N
1	306	41	LA CHIQUITA	\N
2	306	41	LA GRAN VIA	\N
3	306	41	POTRERILLOS	\N
4	306	41	RIOLORO	\N
6	306	41	EL MESON	\N
7	306	41	PUEBLO NUEVO	\N
9	306	41	VUELTAS ARRIBA	\N
10	306	41	SILVANIA	\N
11	306	41	TRES ESQUINAS	\N
12	306	41	EL JARDIN	\N
13	306	41	LA GRAN VIA EL PORVENIR	\N
14	306	41	EL RECREO	\N
15	306	41	LA BODEGA	\N
16	306	41	LA VEGA	\N
0	319	41	GUADALUPE	\N
1	319	41	RESINA	\N
2	319	41	MIRAFLORES	\N
3	319	41	LOS CAUCHOS	\N
4	319	41	POTRERILLOS	\N
5	319	41	CACHIMBAL	\N
6	319	41	SAN JOSE	\N
7	319	41	SARTENEJAL	\N
0	349	41	HOBO	\N
0	357	41	IQUIRA	\N
3	357	41	RIO NEGRO	\N
4	357	41	VALENCIA LA PAZ	\N
5	357	41	SAN LUIS	\N
0	359	41	SAN JOSE DE ISNOS	\N
3	359	41	EL SALTO DE BORDONES	\N
6	359	41	BAJO JUNIN	\N
7	359	41	BUENOS AIRES	\N
8	359	41	CIENAGA GRANDE	\N
0	378	41	LA ARGENTINA	\N
1	378	41	BUENOS AIRES	\N
2	378	41	EL PENSIL	\N
0	396	41	LA PLATA	\N
1	396	41	BELEN	\N
2	396	41	MONSERRATE	\N
3	396	41	MOSCOPAN	\N
4	396	41	SAN ANDRES	\N
5	396	41	VILLA LOSADA	\N
6	396	41	SAN VICENTE	\N
9	396	41	GALLEGO	\N
0	483	41	NATAGA	\N
1	483	41	PATIO BONITO	\N
2	483	41	LLANO BUCO	\N
3	483	41	YARUMAL	\N
0	503	41	OPORAPA	\N
1	503	41	SAN ROQUE	\N
2	503	41	EL CARMEN	\N
3	503	41	SAN CIRO	\N
4	503	41	PARAGUAY	\N
0	518	41	PAICOL	\N
1	518	41	LA REFORMA	\N
2	518	41	LAS LAJITAS	\N
0	524	41	PALERMO	\N
1	524	41	BETANIA	\N
4	524	41	OSPINA PEREZ	\N
5	524	41	SAN JUAN	\N
6	524	41	EL JUNCAL	\N
9	524	41	AMBORCO	\N
0	530	41	PALESTINA	\N
0	548	41	PITAL	\N
1	548	41	EL SOCORRO	\N
2	548	41	MINAS	\N
0	551	41	PITALITO	\N
1	551	41	BRUSELAS	\N
2	551	41	GUACACAYO	\N
3	551	41	LA LAGUNA	\N
5	551	41	REGUEROS	\N
6	551	41	CHILLURCO (VILLAS DEL NORTE)	\N
8	551	41	CRIOLLO	\N
9	551	41	CHARGUAYACO	\N
10	551	41	PALMARITO	\N
15	551	41	LOS ARRAYANES	\N
0	615	41	RIVERA	\N
1	615	41	LA ULLOA	\N
2	615	41	RIVERITA	\N
6	615	41	RIO FRIO	\N
7	615	41	EL GUADUAL	\N
0	660	41	SALADOBLANCO	\N
1	660	41	LA CABANA	\N
7	660	41	MORELIA	\N
0	668	41	SAN AGUSTIN	\N
1	668	41	ALTO DEL OBISPO	\N
2	668	41	OBANDO	\N
3	668	41	VILLA FATIMA	\N
4	668	41	PUERTO QUINCHANA	\N
6	668	41	EL PALMAR	\N
7	668	41	PRADERA	\N
8	668	41	LOS CAUCHOS	\N
9	668	41	EL ROSARIO	\N
0	676	41	SANTA MARIA	\N
1	676	41	SAN JOAQUIN	\N
0	770	41	SUAZA	\N
1	770	41	GALLARDO	\N
2	770	41	GUAYABAL	\N
11	770	41	CRUCE ACEVEDO	\N
12	770	41	SAN JOSE	\N
0	791	41	TARQUI	\N
1	791	41	EL VERGEL	\N
2	791	41	MAITO	\N
3	791	41	QUITURO	\N
0	797	41	TESALIA	\N
1	797	41	PACARNI	\N
0	799	41	TELLO	\N
1	799	41	ANACLETO GARCIA	\N
2	799	41	SIERRA DEL GRAMAL	\N
3	799	41	SAN ANDRES TELLO	\N
4	799	41	SIERRA DE LA CANADA	\N
0	801	41	TERUEL	\N
0	807	41	TIMANA	\N
1	807	41	NARANJAL	\N
4	807	41	SAN ANTONIO	\N
5	807	41	MONTANITA	\N
6	807	41	QUINCHE	\N
7	807	41	COSANZA	\N
9	807	41	SAN ISIDRO	\N
10	807	41	AGUAS CLARAS	\N
11	807	41	ALTO NARANJAL	\N
13	807	41	PANTANOS	\N
14	807	41	SANTA BARBARA	\N
0	872	41	VILLAVIEJA	\N
1	872	41	POTOSI	\N
2	872	41	SAN ALFONSO	\N
3	872	41	HATO NUEVO	\N
4	872	41	POLONIA	\N
5	872	41	LA VICTORIA	\N
0	885	41	YAGUARA	\N
0	1	44	RIOHACHA, DISTRITO ESPECIAL, TURISTICO Y CULTURAL	\N
1	1	44	ARROYO ARENA	\N
2	1	44	BARBACOA	\N
3	1	44	CAMARONES	\N
4	1	44	CASCAJALITO	\N
5	1	44	COTOPRIX	\N
8	1	44	GALAN	\N
11	1	44	MATITAS	\N
12	1	44	MONGUI	\N
16	1	44	TOMARRAZON (TREINTA)	\N
17	1	44	VILLA MARTIN (MACHO VALLO)	\N
18	1	44	LAS PALMAS	\N
20	1	44	CHOLES	\N
21	1	44	COMEJENES	\N
22	1	44	EL ABRA	\N
23	1	44	LAS CASITAS	\N
24	1	44	LOS MORENEROS	\N
25	1	44	PELECHUA	\N
26	1	44	PERICO	\N
27	1	44	TIGRERA	\N
28	1	44	ANAIME	\N
31	1	44	CERRILLO	\N
32	1	44	CUCURUMANA	\N
33	1	44	EBANAL	\N
35	1	44	JUAN Y MEDIO	\N
36	1	44	LA ARENA	\N
37	1	44	LA GLORIA	\N
40	1	44	PUENTE BOMBA	\N
41	1	44	EL CARMEN	\N
42	1	44	LA COMPANIA	\N
43	1	44	PUERTO COLOMBIA	\N
44	1	44	VILLA COMPI	\N
0	35	44	ALBANIA	\N
2	35	44	WARE WAREN	\N
3	35	44	LOS REMEDIOS	\N
4	35	44	LOS RANCHOS	\N
5	35	44	PITURUMANA	\N
6	35	44	PORCIOSA	\N
0	78	44	BARRANCAS	\N
1	78	44	CARRETALITO	\N
6	78	44	PAPAYAL	\N
7	78	44	ROCHE	\N
8	78	44	SAN PEDRO	\N
9	78	44	GUAYACANAL	\N
11	78	44	POZO HONDO	\N
13	78	44	NUEVO OREGANAL	\N
14	78	44	PATILLA	\N
15	78	44	CHANCLETA	\N
16	78	44	LAS CASITAS	\N
0	90	44	DIBULLA	\N
1	90	44	LA PUNTA DE LOS REMEDIOS	\N
2	90	44	LAS FLORES	\N
3	90	44	MINGUEO	\N
4	90	44	PALOMINO	\N
5	90	44	CAMPANA NUEVO	\N
6	90	44	RIO ANCHO	\N
7	90	44	CASA DE ALUMINIO	\N
8	90	44	RIO JEREZ	\N
9	90	44	RIO NEGRO	\N
10	90	44	SANTA RITA DE LA SIERRA	\N
0	98	44	DISTRACCION	\N
1	98	44	BUENAVISTA	\N
2	98	44	CHORRERAS	\N
3	98	44	CAIMITO (RESGUARDO)	\N
5	98	44	LA DUDA	\N
7	98	44	LA CEIBA (RESGUARDO)	\N
8	98	44	LOS HORNITOS	\N
11	98	44	POTRERITO	\N
12	98	44	PULGAR	\N
0	110	44	EL MOLINO	\N
0	279	44	FONSECA	\N
2	279	44	CONEJO	\N
5	279	44	EL HATICO	\N
6	279	44	SITIONUEVO	\N
7	279	44	CARDONAL	\N
8	279	44	BANGANITAS	\N
11	279	44	EL CONFUSO	\N
13	279	44	LOS ALTOS	\N
14	279	44	QUEBRACHAL	\N
15	279	44	POTRERITO	\N
16	279	44	GUAMACHAL	\N
22	279	44	LA LAGUNA	\N
23	279	44	LOS TORQUITOS	\N
0	378	44	HATONUEVO	\N
2	378	44	CERRO ALTO	\N
3	378	44	EL PARAISO	\N
4	378	44	EL POZO	\N
5	378	44	GUAIMARITO	\N
6	378	44	GUAMACHITO	\N
8	378	44	LA GLORIA	\N
9	378	44	LA LOMITA	\N
10	378	44	LOMA MATO	\N
11	378	44	MANANTIAL GRANDE	\N
12	378	44	YAGUARITO	\N
0	420	44	LA JAGUA DEL PILAR	\N
1	420	44	EL PLAN	\N
0	430	44	MAICAO	\N
2	430	44	CARRAIPIA	\N
5	430	44	LA PAZ	\N
6	430	44	LA MAJAYURA	\N
7	430	44	PARAGUACHON	\N
10	430	44	MARANAMANA	\N
12	430	44	EL LIMONCITO	\N
13	430	44	YOTOJOROY	\N
14	430	44	GARRAPATERO	\N
15	430	44	MAKU	\N
16	430	44	SANTA CRUZ	\N
17	430	44	SANTA ROSA	\N
18	430	44	DIVINO NINO	\N
19	430	44	LA ESPERANZA	\N
20	430	44	MONTE LARA	\N
0	560	44	MANAURE	\N
1	560	44	AREMASAHIN	\N
2	560	44	MUSICHI	\N
3	560	44	EL PAJARO	\N
4	560	44	SAN ANTONIO	\N
6	560	44	SHIRURIA	\N
7	560	44	MAYAPO	\N
8	560	44	MANZANA	\N
9	560	44	LA GLORIA	\N
10	560	44	LA PAZ	\N
11	560	44	AIMARAL	\N
12	560	44	ARROYO LIMON	\N
13	560	44	POROMANA	\N
0	650	44	SAN JUAN DEL CESAR	\N
1	650	44	CANAVERALES	\N
2	650	44	CARACOLI	\N
3	650	44	CORRAL DE PIEDRA	\N
4	650	44	EL HATICO DE LOS INDIOS	\N
5	650	44	EL TABLAZO	\N
6	650	44	EL TOTUMO	\N
7	650	44	GUAYACANAL	\N
8	650	44	LA JUNTA	\N
9	650	44	LA PENA	\N
10	650	44	LA SIERRITA	\N
11	650	44	LOS HATICOS	\N
12	650	44	LOS PONDORES	\N
13	650	44	ZAMBRANO	\N
14	650	44	CORRALEJAS	\N
15	650	44	LA PENA DE LOS INDIOS	\N
16	650	44	PONDORITOS	\N
17	650	44	VILLA DEL RIO	\N
18	650	44	LAGUNITA	\N
19	650	44	LOS POZOS	\N
20	650	44	POTRERITO	\N
21	650	44	CURAZAO	\N
22	650	44	BOCA DEL MONTE	\N
23	650	44	LOS CARDONES	\N
24	650	44	EL PLACER	\N
25	650	44	GUAMACHAL	\N
26	650	44	LOS TUNALES	\N
27	650	44	VERACRUZ	\N
0	847	44	URIBIA	\N
3	847	44	CABO DE LA VELA	\N
4	847	44	CARRIZAL	\N
7	847	44	EL CARDON	\N
12	847	44	NAZARETH	\N
13	847	44	PUERTO ESTRELLA	\N
24	847	44	TAGUAYRA	\N
26	847	44	COMUNIDAD ETDANA	\N
27	847	44	LECHIMANA	\N
28	847	44	MEDIA LUNA	\N
29	847	44	PARAISO	\N
30	847	44	PASADENA	\N
31	847	44	PUERTO NUEVO	\N
32	847	44	SANTA ANA	\N
33	847	44	SANTA FE DE SIAPANA	\N
34	847	44	VILLA FATIMA	\N
35	847	44	WARPANA	\N
36	847	44	WARRUTAMANA	\N
37	847	44	WOSOSOPO	\N
38	847	44	YORIJARU	\N
0	855	44	URUMITA	\N
0	874	44	VILLANUEVA	\N
0	1	47	SANTA MARTA, DISTRITO TURISTICO, CULTURAL E HISTORICO	\N
1	1	47	BONDA	\N
2	1	47	CALABAZO	\N
3	1	47	DON DIEGO	\N
6	1	47	GUACHACA	\N
9	1	47	MINCA	\N
10	1	47	TAGANGA	\N
11	1	47	BURITACA	\N
12	1	47	LA QUININA	\N
13	1	47	TIGRERA	\N
22	1	47	CABANAS DE BURITACA	\N
23	1	47	CANAVERAL (AGUA FRIA)	\N
25	1	47	CURVALITO	\N
26	1	47	GUACOCHE (LA LLANTA)	\N
27	1	47	MARKETALIA (PALOMINO)	\N
28	1	47	PAZ DEL CARIBE	\N
29	1	47	PERICO AGUAO	\N
32	1	47	LA REVUELTA	\N
34	1	47	EL TROMPITO	\N
35	1	47	LA AGUACATERA	\N
36	1	47	MACHETE PELAO	\N
37	1	47	NUEVO MEJICO	\N
38	1	47	VALLE DE GAIRA	\N
39	1	47	LINDEROS	\N
40	1	47	LOS COCOS	\N
41	1	47	MENDIHUACA	\N
42	1	47	QUEBRADA VALENCIA	\N
43	1	47	SAN TROPEL	\N
44	1	47	LOS NARANJOS	\N
45	1	47	NUEVO HORIZONTE (SAN RAFAEL)	\N
0	30	47	ALGARROBO	\N
1	30	47	BELLA VISTA	\N
2	30	47	ESTACION DEL FERROCARRIL	\N
3	30	47	ESTACION LLERAS	\N
4	30	47	LOMA DEL BALSAMO	\N
6	30	47	RIOMAR	\N
0	53	47	ARACATACA	\N
1	53	47	BUENOS AIRES	\N
11	53	47	CAUCA	\N
13	53	47	SAMPUES	\N
16	53	47	EL TIGRE	\N
17	53	47	GUNMAKU	\N
18	53	47	RIO DE PIEDRA II	\N
0	58	47	EL DIFICIL	\N
1	58	47	ALEJANDRIA	\N
3	58	47	PUEBLO NUEVO	\N
5	58	47	SAN JOSE DE ARIGUANI	\N
8	58	47	VADELCO	\N
9	58	47	CARMEN DE ARIGUANI	\N
0	161	47	CERRO DE SAN ANTONIO	\N
2	161	47	CANDELARIA (CAIMAN)	\N
3	161	47	CONCEPCION (COCO)	\N
5	161	47	JESUS DEL MONTE (MICO)	\N
6	161	47	PUERTO NINO (CHARANGA)	\N
0	170	47	CHIVOLO	\N
1	170	47	LA CHINA	\N
2	170	47	PUEBLO NUEVO	\N
3	170	47	LA ESTRELLA	\N
4	170	47	LA POLA	\N
5	170	47	PLAN	\N
0	189	47	CIENAGA	\N
4	189	47	SAN PEDRO DE LA SIERRA	\N
6	189	47	SEVILLANO	\N
18	189	47	PALMOR	\N
22	189	47	CORDOBITA	\N
23	189	47	SIBERIA	\N
24	189	47	LA ISABEL	\N
25	189	47	MAYA	\N
26	189	47	SAN JAVIER	\N
0	205	47	CONCORDIA	\N
1	205	47	BALSAMO	\N
2	205	47	BELLAVISTA	\N
3	205	47	ROSARIO DEL CHENGUE	\N
0	245	47	EL BANCO	\N
1	245	47	AGUAESTRADA	\N
2	245	47	ALGARROBAL	\N
3	245	47	EL BARRANCO DE CHILLOA	\N
4	245	47	LOS NEGRITOS	\N
5	245	47	BELEN	\N
6	245	47	CANO DE PALMA	\N
7	245	47	EL CERRITO	\N
8	245	47	EL TREBOL	\N
10	245	47	MENCHIQUEJO	\N
11	245	47	HATILLO DE LA SABANA	\N
12	245	47	SAN JOSE	\N
13	245	47	SAN ROQUE	\N
14	245	47	TAMALAMEQUITO	\N
16	245	47	SAN FELIPE Y SAN EDUARDO	\N
18	245	47	GUACAMAYAL	\N
19	245	47	MALPICA	\N
20	245	47	GARZON	\N
22	245	47	ISLITAS	\N
25	245	47	BOTILLERO	\N
28	245	47	PUEBLO NUEVO	\N
32	245	47	MATA DE CANA	\N
33	245	47	EL CEDRO	\N
0	258	47	EL PINON	\N
1	258	47	CAMPO ALEGRE	\N
2	258	47	CANTAGALLAR	\N
3	258	47	CARRETO	\N
4	258	47	PLAYON DE OROZCO	\N
5	258	47	SABANAS	\N
6	258	47	SAN BASILIO	\N
7	258	47	TIO GOLLO	\N
9	258	47	VERANILLO	\N
10	258	47	LOS PATOS	\N
11	258	47	VASQUEZ	\N
12	258	47	LAS PALMAS	\N
13	258	47	LAS PAVITAS	\N
0	268	47	EL RETEN	\N
1	268	47	SAN SEBASTIAN DEL BONGO	\N
2	268	47	LA COLOMBIA	\N
3	268	47	LAS FLORES	\N
6	268	47	LA POLVORITA	\N
7	268	47	PARATE BIEN (EL PLEITO)	\N
8	268	47	SAN JOSE DE HONDURAS	\N
9	268	47	LAS CABANITAS	\N
10	268	47	SALITRE	\N
0	288	47	FUNDACION	\N
3	288	47	DONA MARIA	\N
4	288	47	SANTA ROSA	\N
13	288	47	SANTA CLARA	\N
14	288	47	EL CINCUENTA	\N
15	288	47	EL CABRERO	\N
16	288	47	LA CRISTALINA	\N
17	288	47	SACRAMENTO	\N
0	318	47	GUAMAL	\N
1	318	47	CASA DE TABLA	\N
2	318	47	GUAIMARAL	\N
3	318	47	HATO VIEJO	\N
4	318	47	PEDREGOSA	\N
5	318	47	LOS ANDES	\N
6	318	47	MURILLO	\N
8	318	47	RICAURTE	\N
9	318	47	SALVADORA	\N
10	318	47	HURQUIJO	\N
11	318	47	PLAYAS BLANCAS	\N
12	318	47	SITIO NUEVO	\N
13	318	47	CARRETERO	\N
14	318	47	BELLAVISTA	\N
16	318	47	SANTA TERESITA	\N
17	318	47	SAN PEDRO	\N
18	318	47	LAS FLORES	\N
19	318	47	SAN ANTONIO	\N
20	318	47	LA CEIBA	\N
23	318	47	SAN ISIDRO	\N
24	318	47	VILLA NUEVA	\N
25	318	47	EL VEINTIOCHO	\N
0	460	47	GRANADA	\N
1	460	47	EL BAJO	\N
2	460	47	LA GLORIA	\N
3	460	47	LAS TINAS	\N
4	460	47	LOS ANDES	\N
6	460	47	SAN JOSE DE BALLESTERO	\N
7	460	47	EL CORRAL	\N
8	460	47	EL PALMAR (EL CHUZO)	\N
0	541	47	PEDRAZA	\N
1	541	47	BAHIA HONDA	\N
3	541	47	BOMBA	\N
7	541	47	GUAQUIRI	\N
8	541	47	HEREDIA	\N
0	545	47	PIJINO	\N
1	545	47	CABRERA	\N
2	545	47	FILADELFIA	\N
3	545	47	SAN JOSE DE PREVENCION	\N
4	545	47	CASA BLANCA	\N
5	545	47	LA LUCHA	\N
7	545	47	EL DIVIDIVI	\N
8	545	47	EL BRILLANTE	\N
0	551	47	PIVIJAY	\N
1	551	47	LA AVIANCA	\N
2	551	47	CARABALLO	\N
3	551	47	CHINOBLAS	\N
5	551	47	SAN JOSE DE LA MONTANA (GARRAPATA)	\N
6	551	47	LAS CANOAS	\N
7	551	47	LAS PIEDRAS	\N
8	551	47	MEDIALUNA	\N
10	551	47	CARMEN DEL MAGDALENA (PARACO)	\N
11	551	47	PARAISO	\N
12	551	47	PINUELAS	\N
13	551	47	PLACITAS	\N
17	551	47	LA RETIRADA	\N
0	555	47	PLATO	\N
1	555	47	APURE	\N
2	555	47	CARMEN DEL MAGDALENA	\N
5	555	47	ZARATE	\N
6	555	47	AGUAS VIVAS	\N
7	555	47	CIENEGUETA	\N
8	555	47	CERRO GRANDE	\N
11	555	47	SAN JOSE DEL PURGATORIO	\N
15	555	47	DISCIPLINA	\N
17	555	47	SAN ANTONIO DEL RIO	\N
18	555	47	BUENA VISTA	\N
20	555	47	LOS POZOS	\N
21	555	47	CINCO Y SEIS	\N
22	555	47	LAS PLANADAS	\N
0	570	47	PUEBLOVIEJO	\N
2	570	47	ISLA DEL ROSARIO	\N
3	570	47	PALMIRA	\N
4	570	47	TASAJERA	\N
5	570	47	TIERRA NUEVA	\N
7	570	47	EL TRIUNFO	\N
8	570	47	ISLA DE CATAQUITA	\N
9	570	47	NUEVA FRONTERA	\N
10	570	47	SAN JUAN DE PALOS PRIETOS (LA MONTANA)	\N
0	605	47	REMOLINO	\N
2	605	47	CORRAL VIEJO	\N
3	605	47	EL DIVIDIVI	\N
4	605	47	SAN RAFAEL DE BUENAVISTA	\N
5	605	47	SANTA RITA	\N
6	605	47	EL SALAO	\N
7	605	47	MARTINETE	\N
8	605	47	LAS CASITAS	\N
0	660	47	SAN ANGEL	\N
2	660	47	CASA DE TABLA	\N
3	660	47	CESPEDES	\N
5	660	47	FLORES DE MARIA	\N
7	660	47	LA HORQUETA	\N
9	660	47	SAN ROQUE	\N
10	660	47	EL MANANTIAL	\N
11	660	47	PUEBLITO DE LOS BARRIOS	\N
13	660	47	ESTACION VILLA	\N
14	660	47	MONTERRUBIO	\N
0	675	47	SALAMINA	\N
1	675	47	GUAIMARO	\N
5	675	47	EL SALAO	\N
6	675	47	LA LOMA	\N
7	675	47	LA LOMITA	\N
0	692	47	SAN SEBASTIAN DE BUENAVISTA	\N
1	692	47	BUENAVISTA	\N
2	692	47	EL COCO	\N
3	692	47	LA PACHA	\N
4	692	47	LAS MARGARITAS	\N
5	692	47	LOS GALVIS	\N
6	692	47	MARIA ANTONIA	\N
7	692	47	SAN RAFAEL	\N
8	692	47	SANTA ROSA	\N
9	692	47	TRONCOSITO	\N
10	692	47	TRONCOSO	\N
11	692	47	VENERO	\N
13	692	47	EL SEIS	\N
18	692	47	SAN VALENTIN	\N
19	692	47	PUEBLO NUEVO	\N
0	703	47	SAN ZENON	\N
1	703	47	ANGOSTURA	\N
2	703	47	BERMEJAL	\N
3	703	47	EL PALOMAR	\N
4	703	47	JANEIRO	\N
5	703	47	LA MONTANA	\N
6	703	47	PENONCITO	\N
7	703	47	SANTA TERESA	\N
8	703	47	GUINEA	\N
9	703	47	EL HORNO	\N
10	703	47	PUERTO ARTURO	\N
0	707	47	SANTA ANA	\N
1	707	47	BARRO BLANCO	\N
6	707	47	SAN FERNANDO	\N
9	707	47	JARABA	\N
11	707	47	SANTA ROSA	\N
0	720	47	SANTA BARBARA DE PINTO	\N
1	720	47	CUNDINAMARCA	\N
2	720	47	SAN PEDRO	\N
3	720	47	VELADERO	\N
4	720	47	CARRETAL	\N
5	720	47	CIENAGUETA	\N
0	745	47	SITIONUEVO	\N
1	745	47	BUENAVISTA	\N
2	745	47	NUEVA VENECIA	\N
3	745	47	PALERMO	\N
6	745	47	SAN ANTONIO	\N
0	798	47	TENERIFE	\N
4	798	47	REAL DEL OBISPO	\N
5	798	47	SAN LUIS	\N
7	798	47	EL JUNCAL	\N
8	798	47	SANTA INES	\N
0	960	47	PUNTA DE PIEDRAS	\N
1	960	47	CANO DE AGUAS	\N
2	960	47	CAPUCHO	\N
3	960	47	PIEDRAS DE MOLER	\N
4	960	47	PIEDRAS PINTADAS	\N
5	960	47	LOS CERRITOS	\N
6	960	47	EL BONGO	\N
0	980	47	PRADO - SEVILLA	\N
1	980	47	GUACAMAYAL	\N
2	980	47	GUAMACHITO	\N
3	980	47	LA GRAN VIA	\N
4	980	47	ORIHUECA	\N
5	980	47	PALOMAR	\N
6	980	47	RIO FRIO	\N
7	980	47	SANTA ROSALIA	\N
9	980	47	SOPLADOR	\N
10	980	47	TUCURINCA	\N
11	980	47	VARELA	\N
12	980	47	ZAWADY	\N
13	980	47	ESTACION SEVILLA	\N
14	980	47	LA CANDELARIA	\N
15	980	47	SAN JOSE DE KENNEDY	\N
16	980	47	CANO MOCHO	\N
17	980	47	EL MAMON	\N
18	980	47	AGUSTINA	\N
19	980	47	CARITAL	\N
20	980	47	CASABLANCA	\N
21	980	47	CIUDAD PERDIDA	\N
22	980	47	EL REPOSO	\N
23	980	47	IBERIA	\N
24	980	47	MONTERIA	\N
25	980	47	PATUCA	\N
26	980	47	PAULINA	\N
27	980	47	PILOTO	\N
28	980	47	SALON CONCEPCION	\N
0	1	50	VILLAVICENCIO	\N
1	1	50	CONCEPCION	\N
2	1	50	RINCON DE POMPEYA	\N
3	1	50	SANTA ROSA DE RIO NEGRO	\N
4	1	50	BUENAVISTA	\N
5	1	50	COCUY	\N
7	1	50	SERVITA	\N
13	1	50	PIPIRAL	\N
14	1	50	SAN LUIS DE OCOA	\N
15	1	50	ALTO POMPEYA	\N
16	1	50	CECILIA	\N
17	1	50	LA NOHORA	\N
19	1	50	APIAY	\N
20	1	50	BARCELONA	\N
21	1	50	ARGENTINA	\N
23	1	50	BELLA SUIZA	\N
24	1	50	CONDOMINIO DE LOS ODONTOLOGOS	\N
25	1	50	CONDOMINIO SANTA BARBARA	\N
26	1	50	LLANERITA	\N
27	1	50	NATURALIA	\N
28	1	50	PARCELAS DEL PROGRESO	\N
31	1	50	SAN CARLOS	\N
0	6	50	ACACIAS	\N
1	6	50	DINAMARCA	\N
3	6	50	SAN ISIDRO DE CHICHIMENE	\N
6	6	50	CONDOMINIO LA BONANZA	\N
7	6	50	LA CECILITA	\N
8	6	50	QUEBRADITAS	\N
9	6	50	SANTA ROSA	\N
10	6	50	EL DIAMANTE	\N
0	110	50	BARRANCA DE UPIA	\N
1	110	50	SAN IGNACIO	\N
0	124	50	CABUYARO	\N
2	124	50	GUAYABAL DE UPIA	\N
3	124	50	VISO DE UPIA	\N
4	124	50	LOS MANGOS	\N
0	150	50	CASTILLA LA NUEVA	\N
1	150	50	SAN LORENZO	\N
2	150	50	PUEBLO VIEJO	\N
3	150	50	EL TORO	\N
4	150	50	LAS VIOLETAS	\N
5	150	50	CASA BLANCA	\N
0	223	50	CUBARRAL	\N
3	223	50	PUERTO ARIARI	\N
0	226	50	CUMARAL	\N
2	226	50	GUACAVIA	\N
4	226	50	SAN NICOLAS	\N
5	226	50	VERACRUZ	\N
10	226	50	PRESENTADO	\N
0	245	50	EL CALVARIO	\N
1	245	50	MONTFORT	\N
2	245	50	SAN FRANCISCO	\N
6	245	50	SAN JOSE	\N
0	251	50	EL CASTILLO	\N
1	251	50	MEDELLIN DEL ARIARI	\N
3	251	50	MIRAVALLES	\N
4	251	50	PUERTO ESPERANZA	\N
0	270	50	EL DORADO	\N
1	270	50	PUEBLO SANCHEZ	\N
2	270	50	SAN ISIDRO	\N
0	287	50	FUENTE DE ORO	\N
1	287	50	PUERTO ALJURE	\N
2	287	50	PUERTO LIMON	\N
3	287	50	PUERTO SANTANDER	\N
4	287	50	UNION DEL ARIARI	\N
5	287	50	LA COOPERATIVA	\N
6	287	50	CANO BLANCO	\N
7	287	50	PUERTO NUEVO	\N
8	287	50	BARRANCO COLORADO CANO VENADO	\N
0	313	50	GRANADA	\N
1	313	50	CANAGUARO	\N
2	313	50	DOS QUEBRADAS	\N
4	313	50	LA PLAYA	\N
5	313	50	PUERTO CALDAS	\N
6	313	50	AGUAS CLARAS	\N
7	313	50	PUNTA BRAVA	\N
0	318	50	GUAMAL	\N
1	318	50	HUMADEA	\N
0	325	50	MAPIRIPAN	\N
1	325	50	PUERTO ALVIRA	\N
2	325	50	MIELON	\N
4	325	50	ANZUELO	\N
5	325	50	GUACAMAYAS	\N
6	325	50	LA COOPERATIVA	\N
7	325	50	PUERTO SIARE	\N
9	325	50	EL SILENCIO	\N
10	325	50	LA JUNGLA	\N
11	325	50	RINCON DEL INDIO	\N
0	330	50	MESETAS	\N
2	330	50	JARDIN DE LAS PENAS	\N
3	330	50	BRISAS DEL DUDA	\N
4	330	50	MIRADOR	\N
5	330	50	ORIENTE	\N
7	330	50	LA ARGENTINA	\N
9	330	50	PUERTO NARINO	\N
10	330	50	SAN ISIDRO	\N
0	350	50	LA MACARENA	\N
1	350	50	SAN FRANCISCO DE LA SOMBRA	\N
2	350	50	LOS POZOS	\N
3	350	50	SAN JUAN DEL LOSADA	\N
7	350	50	LA CRISTALINA	\N
8	350	50	EL RUBI	\N
9	350	50	ALTO MORROCOY (NUEVO HORIZONTE)	\N
10	350	50	EL VERGEL	\N
11	350	50	LA TUNIA	\N
12	350	50	LAS DELICIAS	\N
13	350	50	LAURELES	\N
14	350	50	PLAYA RICA	\N
15	350	50	PUERTO LOZADA	\N
16	350	50	VILLA CARDONA	\N
0	370	50	URIBE	\N
1	370	50	LA JULIA	\N
2	370	50	EL DIVISO	\N
0	400	50	LEJANIAS	\N
1	400	50	CACAYAL	\N
2	400	50	ANGOSTURAS DEL GUAPE	\N
0	450	50	PUERTO CONCORDIA	\N
1	450	50	EL PORORIO	\N
2	450	50	LINDENAI	\N
3	450	50	SAN FERNANDO	\N
0	568	50	PUERTO GAITAN	\N
1	568	50	DOMO PLANAS	\N
2	568	50	SAN PEDRO DE ARIMENA	\N
4	568	50	SAN MIGUEL	\N
5	568	50	EL PORVENIR	\N
6	568	50	PUERTO TRUJILLO	\N
7	568	50	PUENTE ARIMENA	\N
8	568	50	ALTO TILLAVA	\N
10	568	50	LA CRISTALINA	\N
12	568	50	MURUJUY	\N
0	573	50	PUERTO LOPEZ	\N
1	573	50	LA BALSA	\N
3	573	50	PACHAQUIARO	\N
4	573	50	ALTAMIRA	\N
6	573	50	PUERTO GUADALUPE	\N
7	573	50	PUERTO PORFIA	\N
8	573	50	REMOLINO	\N
10	573	50	BOCAS DEL GUAYURIBA	\N
11	573	50	GUICHIRAL	\N
12	573	50	CHAVIVA	\N
13	573	50	EL TIGRE	\N
0	577	50	PUERTO LLERAS	\N
3	577	50	CASIBARE	\N
4	577	50	CANO RAYADO	\N
5	577	50	VILLA LA PAZ	\N
6	577	50	TIERRA GRATA	\N
7	577	50	LA UNION	\N
8	577	50	VILLA PALMERAS	\N
0	590	50	PUERTO RICO	\N
3	590	50	LA LINDOSA	\N
4	590	50	BARRANCO COLORADO	\N
5	590	50	PUERTO TOLEDO	\N
6	590	50	CHARCO DANTO	\N
7	590	50	LA TIGRA	\N
8	590	50	PUERTO CHISPAS	\N
0	606	50	RESTREPO	\N
0	680	50	SAN CARLOS DE GUAROA	\N
1	680	50	PAJURE	\N
2	680	50	SURIMENA	\N
3	680	50	LA PALMERA	\N
0	683	50	SAN JUAN DE ARAMA	\N
1	683	50	EL VERGEL	\N
5	683	50	MESA FERNANDEZ	\N
10	683	50	CAMPO ALEGRE	\N
11	683	50	CERRITO	\N
12	683	50	MIRAFLOREZ	\N
13	683	50	PENAS BLANCAS	\N
15	683	50	BELLA VISTA	\N
0	686	50	SAN JUANITO	\N
2	686	50	LA CANDELARIA	\N
0	689	50	SAN MARTIN	\N
1	689	50	EL MEREY	\N
6	689	50	EL PARAISO MEJOR VIVIR	\N
0	711	50	VISTAHERMOSA	\N
1	711	50	CAMPO ALEGRE	\N
2	711	50	PINALITO	\N
3	711	50	MARACAIBO	\N
4	711	50	CANO AMARILLO	\N
5	711	50	PUERTO LUCAS MARGEN IZQUIERDO	\N
6	711	50	PUERTO LUCAS MARGEN DERECHO	\N
8	711	50	PUERTO ESPERANZA MARGEN IZQUIERDO	\N
12	711	50	COSTA RICA	\N
14	711	50	PALESTINA	\N
16	711	50	SANTO DOMINGO	\N
17	711	50	TRES ESQUINAS	\N
18	711	50	ALBANIA	\N
19	711	50	BUENOS AIRES	\N
20	711	50	EL TRIUNFO	\N
21	711	50	LA REFORMA	\N
22	711	50	PALMERAS	\N
0	1	52	SAN JUAN DE PASTO	\N
1	1	52	CATAMBUCO	\N
3	1	52	EL ENCANO	\N
4	1	52	GENOY	\N
5	1	52	LA LAGUNA	\N
7	1	52	OBONUCO	\N
8	1	52	SANTA BARBARA	\N
9	1	52	JONGOVITO	\N
10	1	52	GUALMATAN	\N
12	1	52	MAPACHICO - ATICANCE	\N
13	1	52	EL SOCORRO CIMARRON	\N
16	1	52	MOTILON	\N
19	1	52	CEROTAL	\N
21	1	52	LA VICTORIA	\N
24	1	52	SAN JOSE	\N
25	1	52	EL PUERTO	\N
27	1	52	CABRERA	\N
29	1	52	DOLORES	\N
30	1	52	BUESAQUILLO	\N
33	1	52	CUJACAL	\N
36	1	52	TESCUAL	\N
39	1	52	ANGANOY	\N
42	1	52	DAZA	\N
51	1	52	CUBIJAN BAJO	\N
52	1	52	SAN FERNANDO	\N
53	1	52	MOCONDINO	\N
55	1	52	CANCHALA	\N
56	1	52	LOS ANGELES	\N
58	1	52	EL ROSARIO	\N
59	1	52	JAMONDINO	\N
63	1	52	BOTANILLA	\N
64	1	52	CHARGUAYACO	\N
65	1	52	CRUZ DE AMARILLO	\N
66	1	52	EL CAMPANERO	\N
68	1	52	JURADO	\N
71	1	52	LA MERCED	\N
72	1	52	LAS ENCINAS	\N
73	1	52	MAPACHICO ALTO	\N
74	1	52	MAPACHICO SAN JOSE	\N
76	1	52	SAN FRANCISCO	\N
78	1	52	SAN JUAN DE ANGANOY	\N
79	1	52	SANTA LUCIA	\N
80	1	52	VILLA MARIA	\N
86	1	52	GUALMATAN ALTO	\N
87	1	52	LA CALDERA	\N
89	1	52	PUERRES	\N
0	19	52	SAN JOSE	\N
4	19	52	CAMPOBELLO	\N
9	19	52	CARMELO ASENTAMIENTO 1	\N
13	19	52	FATIMA	\N
0	22	52	ALDANA	\N
1	22	52	PAMBA ROSA	\N
3	22	52	SAN LUIS	\N
0	36	52	ANCUYA	\N
17	36	52	INDO SANTA ROSA	\N
0	51	52	BERRUECOS	\N
5	51	52	EL EMPATE	\N
7	51	52	ROSA FLORIDA SUR - SECTOR LA CAPILLA	\N
12	51	52	ROSAFLORIDA NORTE	\N
15	51	52	EL PEDREGAL	\N
0	79	52	BARBACOAS	\N
1	79	52	ALTAQUER	\N
3	79	52	CHALCHAL	\N
5	79	52	DIAGUILLO	\N
6	79	52	JUNIN	\N
9	79	52	LOS BRAZOS	\N
11	79	52	MONGON	\N
13	79	52	PAMBANA	\N
14	79	52	SUCRE GUINULTE	\N
18	79	52	SAN MIGUEL NAMBI	\N
20	79	52	TERAIMBE	\N
22	79	52	SAN JUAN PALACIO	\N
23	79	52	NAMBI	\N
24	79	52	CARGAZON	\N
25	79	52	CASCAJERO	\N
26	79	52	EL DIVISO	\N
27	79	52	LA PLAYA	\N
28	79	52	PAUNDE	\N
29	79	52	SALI	\N
30	79	52	YALARE	\N
35	79	52	PALO SECO	\N
0	83	52	BELEN	\N
1	83	52	SANTA ROSA	\N
0	110	52	BUESACO	\N
1	110	52	PALASINOY	\N
2	110	52	ROSAL DEL MONTE	\N
3	110	52	SAN ANTONIO	\N
4	110	52	SAN IGNACIO	\N
5	110	52	SANTAFE	\N
6	110	52	SANTAMARIA	\N
7	110	52	VILLAMORENO	\N
8	110	52	VERACRUZ	\N
9	110	52	ALTACLARA	\N
22	110	52	JUANAMBU	\N
30	110	52	SAN MIGUEL SANTAFE	\N
0	203	52	GENOVA	\N
1	203	52	GUAITARILLA	\N
2	203	52	LA PLATA	\N
4	203	52	VILLANUEVA	\N
0	207	52	CONSACA	\N
4	207	52	BOMBONA	\N
13	207	52	EL HATILLO	\N
23	207	52	RUMIPAMBA	\N
0	210	52	CONTADERO	\N
1	210	52	ALDEA DE MARIA	\N
2	210	52	LA JOSEFINA	\N
0	215	52	CORDOBA	\N
1	215	52	LOS ARRAYANES	\N
4	215	52	SANTANDER	\N
5	215	52	PUEBLO BAJO	\N
0	224	52	CARLOSAMA	\N
0	227	52	CUMBAL	\N
1	227	52	CHILES	\N
2	227	52	MAYASQUER	\N
4	227	52	PANAN	\N
6	227	52	NAZATE	\N
9	227	52	EL CHOTA	\N
10	227	52	LA POMA	\N
0	233	52	CUMBITARA	\N
1	233	52	DAMASCO	\N
2	233	52	EL DESIERTO	\N
3	233	52	PISANDA	\N
4	233	52	SIDON	\N
7	233	52	LA ESPERANZA	\N
8	233	52	SANTA ROSA	\N
11	233	52	SANTA ANA	\N
12	233	52	CAMPO BELLO	\N
0	240	52	CHACHAGUI	\N
1	240	52	ARIZONA	\N
2	240	52	AGRARIO	\N
3	240	52	CANO ALTO	\N
4	240	52	CANO BAJO	\N
5	240	52	CHORRILLO	\N
6	240	52	GUAIRABAMBA	\N
8	240	52	LA LOMA	\N
11	240	52	SANTA MONICA	\N
12	240	52	COCHA CANO	\N
13	240	52	PEDREGAL	\N
0	250	52	EL CHARCO	\N
3	250	52	SAN PEDRO	\N
22	250	52	EL CUIL	\N
23	250	52	BAZAN	\N
0	254	52	EL PENOL	\N
1	254	52	LAS COCHAS	\N
2	254	52	SAN FRANCISCO	\N
3	254	52	PENOL VIEJO	\N
4	254	52	SAN FRANCISCO BAJO	\N
0	256	52	EL ROSARIO	\N
3	256	52	LA ESMERALDA	\N
4	256	52	LA SIERRA	\N
8	256	52	EL VADO	\N
12	256	52	MARTIN PEREZ	\N
24	256	52	EL RINCON	\N
25	256	52	EL SUSPIRO	\N
0	258	52	EL TABLON DE GOMEZ	\N
1	258	52	APONTE	\N
2	258	52	LA CUEVA	\N
3	258	52	LAS MESAS	\N
5	258	52	LA VICTORIA	\N
15	258	52	SAN RAFAEL	\N
0	260	52	EL TAMBO	\N
0	287	52	FUNES	\N
1	287	52	CHAPAL	\N
0	317	52	GUACHUCAL	\N
1	317	52	COLIMBA	\N
2	317	52	EL CONSUELO DE CHILLANQUER	\N
3	317	52	SAN DIEGO DE MUELLAMUES	\N
4	317	52	SAN JOSE DE CHILLANQUER	\N
5	317	52	LA VICTORIA	\N
7	317	52	ARVELA	\N
8	317	52	QUETAMBUD	\N
0	320	52	GUAITARILLA	\N
16	320	52	EL ROSAL	\N
0	323	52	GUALMATAN	\N
1	323	52	CUATIS	\N
0	352	52	ILES	\N
3	352	52	SAN FRANCISCO	\N
6	352	52	EL CAPULI	\N
9	352	52	EL PORVENIR	\N
10	352	52	EL PORVENIR 1	\N
0	354	52	IMUES	\N
2	354	52	PILCUAN LA RECTA	\N
4	354	52	PILCUAN VIEJO	\N
7	354	52	EL PEDREGAL	\N
12	354	52	SANTA ANA	\N
0	356	52	IPIALES	\N
1	356	52	LA VICTORIA	\N
2	356	52	LAS LAJAS	\N
3	356	52	SAN JUAN	\N
4	356	52	YARAMAL	\N
5	356	52	LOMAS DE SURAS	\N
8	356	52	ZAGUARAN	\N
9	356	52	LAS CRUCES	\N
11	356	52	EL PLACER	\N
12	356	52	LOS CHILCOS	\N
13	356	52	YANALA	\N
14	356	52	JARDINES DE SUCUMBIOS	\N
0	378	52	LA CRUZ	\N
5	378	52	SAN GERARDO	\N
9	378	52	TAJUMBINA	\N
13	378	52	CABUYALES	\N
17	378	52	LA ESTANCIA	\N
0	381	52	LA FLORIDA	\N
1	381	52	MATITUY	\N
2	381	52	ROBLES	\N
3	381	52	TUNJA LA GRANDE	\N
8	381	52	EL RODEO	\N
11	381	52	ACHUPAYAS	\N
12	381	52	PANCHINDO	\N
0	385	52	LA LLANADA	\N
1	385	52	VERGEL	\N
15	385	52	BOLIVAR	\N
0	390	52	LA TOLA	\N
3	390	52	VIGIA DE LA MAR	\N
7	390	52	PIOJA	\N
8	390	52	PANGAMOSA	\N
9	390	52	MULATOS	\N
12	390	52	NERETE	\N
13	390	52	AMARALES	\N
14	390	52	BAJO PALOMINO	\N
15	390	52	PUEBLITO	\N
16	390	52	SAN PABLO MAR	\N
0	399	52	LA UNION	\N
1	399	52	SANTANDER	\N
3	399	52	LA CALDERA	\N
12	399	52	LA PLAYA	\N
18	399	52	OLIVOS	\N
29	399	52	LA BETULIA	\N
30	399	52	QUIROZ ALTO	\N
35	399	52	EL MANGO	\N
36	399	52	LA BETULIA BAJO	\N
0	405	52	LEIVA	\N
1	405	52	EL PALMAR	\N
2	405	52	LAS DELICIAS	\N
5	405	52	NARINO	\N
6	405	52	PUERTO NUEVO	\N
7	405	52	SANTA LUCIA	\N
8	405	52	LA FLORIDA	\N
9	405	52	EL TABLON	\N
13	405	52	FLORIDA BAJA	\N
14	405	52	VILLA BAJA	\N
0	411	52	LINARES	\N
1	411	52	SAN FRANCISCO	\N
2	411	52	TABILES	\N
3	411	52	TAMBILLO BRAVOS	\N
4	411	52	BELLA FLORIDA	\N
5	411	52	ARBOLEDAS	\N
6	411	52	BELLA VISTA	\N
0	418	52	SOTOMAYOR	\N
0	427	52	PAYAN	\N
3	427	52	NANSALBID	\N
5	427	52	GUILPI PIRAGUA	\N
6	427	52	RICAURTE	\N
15	427	52	BRISAS DE HAMBURGO	\N
16	427	52	BELLA VISTA	\N
17	427	52	CAMPO ALEGRE	\N
0	435	52	PIEDRANCHA	\N
2	435	52	CHUCUNES	\N
8	435	52	SAN MIGUEL	\N
9	435	52	EL CARMELO	\N
10	435	52	EL ARCO	\N
11	435	52	EL ARENAL	\N
0	473	52	MOSQUERA	\N
1	473	52	COCALITO JIMENEZ (GABRIEL TURBAY)	\N
4	473	52	COCAL DE LOS PAYANES	\N
6	473	52	FIRME CIFUENTES	\N
7	473	52	BOCAS DE GUANDIPA	\N
8	473	52	PUEBLO NUEVO	\N
9	473	52	EL GARCERO	\N
10	473	52	EL BAJITO DE ECHANDIA	\N
14	473	52	PAMPA CHAPILA	\N
15	473	52	PLAYA NUEVA	\N
18	473	52	EL TORTUGO	\N
26	473	52	EL CANTIL	\N
27	473	52	EL NARANJO	\N
33	473	52	PAMPA QUINONES	\N
0	480	52	NARINO	\N
0	490	52	BOCAS DE SATINGA	\N
3	490	52	EL CARMEN	\N
8	490	52	SAN JOSE CALABAZAL	\N
11	490	52	ALTO ZAPANQUE	\N
12	490	52	BAJO ZAPANQUE	\N
13	490	52	LA TOLITA	\N
14	490	52	ZAPOTAL	\N
15	490	52	BOCA DE VIBORA	\N
16	490	52	EL NATO	\N
17	490	52	CAROLINA	\N
18	490	52	LAS PALMAS	\N
19	490	52	SAMARITANO	\N
20	490	52	SANTAMARIA	\N
0	506	52	OSPINA	\N
2	506	52	SAN ISIDRO	\N
5	506	52	CUNCHILA O MORENO	\N
0	520	52	SALAHONDA	\N
11	520	52	LUIS AVELINO PEREZ	\N
12	520	52	LA PLAYA	\N
13	520	52	SAN PEDRO DEL VINO	\N
14	520	52	VUELTA DEL GALLO	\N
0	540	52	POLICARPA	\N
1	540	52	ALTAMIRA	\N
2	540	52	MADRIGAL	\N
3	540	52	SAN ROQUE (BUENAVISTA)	\N
4	540	52	SANCHEZ	\N
5	540	52	EL EJIDO	\N
6	540	52	SANTA CRUZ	\N
7	540	52	RESTREPO	\N
8	540	52	EL CERRO	\N
9	540	52	LA VEGA	\N
10	540	52	SAN PABLO	\N
0	560	52	POTOSI	\N
1	560	52	CARDENAS	\N
2	560	52	BAJO SINAI	\N
6	560	52	SAN PEDRO	\N
11	560	52	CUASPUD NUCLEO	\N
0	565	52	PROVIDENCIA	\N
1	565	52	GUADRAHUMA	\N
0	573	52	PUERRES	\N
1	573	52	EL PARAMO	\N
2	573	52	MONOPAMBA	\N
3	573	52	SAN MATEO	\N
5	573	52	MAICIRA	\N
6	573	52	EL LLANO	\N
10	573	52	SAN MIGUEL	\N
13	573	52	YANALE	\N
14	573	52	LOS ALIZALES	\N
0	585	52	PUPIALES	\N
4	585	52	JOSE MARIA HERNANDEZ	\N
0	612	52	RICAURTE	\N
3	612	52	OSPINA PEREZ	\N
4	612	52	SAN ISIDRO	\N
8	612	52	CHAMBU	\N
11	612	52	SAN FRANCISCO	\N
12	612	52	VILLA NUEVA	\N
13	612	52	PALMAR	\N
0	621	52	SAN JOSE	\N
9	621	52	LAS LAJAS PUMBI	\N
13	621	52	PALOSECO	\N
16	621	52	SAN ANTONIO - BOCA TELEMBI	\N
28	621	52	LAS MERCEDES - CHIMBUZA	\N
0	678	52	SAMANIEGO	\N
7	678	52	TANAMA	\N
12	678	52	CHUGULDI	\N
13	678	52	TURUPAMBA	\N
15	678	52	PUERCHAG	\N
17	678	52	CARTAGENA	\N
24	678	52	LA MESA	\N
25	678	52	OBANDO	\N
29	678	52	BONETE	\N
30	678	52	MIRADOR DE SARACONCHO	\N
0	683	52	SANDONA	\N
1	683	52	BOLIVAR	\N
2	683	52	EL INGENIO	\N
3	683	52	SAN BERNARDO	\N
4	683	52	SANTA BARBARA	\N
5	683	52	SANTA ROSA	\N
7	683	52	ROMA CHAVEZ	\N
8	683	52	BOHORQUEZ	\N
9	683	52	PARAGUAY	\N
10	683	52	EL VERGEL	\N
11	683	52	SAN GABRIEL	\N
12	683	52	SAN MIGUEL	\N
14	683	52	ALTAMIRA CRUZ DE ARADA	\N
16	683	52	CHAVEZ	\N
17	683	52	TAMBILLO	\N
18	683	52	LA LOMA	\N
19	683	52	LA REGADERA	\N
22	683	52	SAN FERNANDO	\N
23	683	52	SAN FRANCISCO ALTO	\N
24	683	52	20 DE JULIO	\N
27	683	52	URBANIZACION VILLA CAFELINA	\N
0	685	52	SAN BERNARDO	\N
1	685	52	LA VEGA	\N
2	685	52	PUEBLO VIEJO	\N
0	687	52	SAN LORENZO	\N
1	687	52	EL CARMEN	\N
2	687	52	SANTA CECILIA	\N
3	687	52	SANTA MARTHA	\N
5	687	52	SAN VICENTE	\N
8	687	52	EL CHEPE	\N
0	693	52	SAN PABLO	\N
2	693	52	BRICENO	\N
4	693	52	LA CANADA	\N
17	693	52	CHILCAL ALTO	\N
0	694	52	SAN PEDRO DE CARTAGO	\N
1	694	52	LA COMUNIDAD	\N
0	696	52	ISCUANDE	\N
9	696	52	PALOMINO	\N
12	696	52	CUERBAL	\N
13	696	52	JUANCHILLO	\N
14	696	52	LA ENSENADA	\N
15	696	52	CHICO PEREZ	\N
16	696	52	LAS MARIAS	\N
17	696	52	SANTA RITA	\N
18	696	52	BOCA DE CHANZARA	\N
19	696	52	LAS VARAS	\N
20	696	52	QUIGUPI	\N
21	696	52	RODEA	\N
22	696	52	SECADERO SEGUIHONDA	\N
23	696	52	SOLEDAD PUEBLITO	\N
0	699	52	GUACHAVES	\N
1	699	52	BALALAIKA	\N
4	699	52	MANCHAG	\N
0	720	52	SAPUYES	\N
1	720	52	EL ESPINO	\N
2	720	52	URIBE	\N
0	786	52	TAMINANGO	\N
2	786	52	EL TABLON	\N
3	786	52	CURIACO	\N
6	786	52	ALTO DE DIEGO	\N
7	786	52	EL MANZANO	\N
14	786	52	PARAMO	\N
18	786	52	LA GRANADA	\N
19	786	52	EL REMOLINO	\N
21	786	52	GUAYACANAL	\N
22	786	52	SAN ISIDRO	\N
23	786	52	EL DIVISO	\N
24	786	52	VIENTO LIBRE	\N
25	786	52	PANOYA	\N
0	788	52	TANGUA	\N
1	788	52	SANTANDER	\N
0	835	52	SAN ANDRES DE TUMACO, DISTRITO ESPECIAL, INDUSTRIAL, PORTUARIO, BIODIVERSO Y ECOTURISTICO	\N
9	835	52	CAUNAPI	\N
10	835	52	COLORADO	\N
11	835	52	DESCOLGADERO	\N
12	835	52	CHAJAL	\N
15	835	52	LA CALETA	\N
16	835	52	PITAL	\N
17	835	52	ESPRIELLA	\N
20	835	52	BARRO COLORADO	\N
21	835	52	SAN JOSE DEL GUAYABO	\N
30	835	52	GUAYACANA	\N
31	835	52	LLORENTE	\N
36	835	52	PALAMBI	\N
37	835	52	IMBILI MIRASPALMAS	\N
40	835	52	EL PROGRESO SANTO DOMINGO	\N
42	835	52	SAN LUIS ROBLES	\N
47	835	52	SALISVI	\N
50	835	52	VILLA SAN JUAN	\N
51	835	52	SAN ANTONIO	\N
55	835	52	BOCAS DE CURAY	\N
58	835	52	TEHERAN	\N
59	835	52	URIBE URIBE (CHILVI)	\N
63	835	52	EL BAJITO	\N
64	835	52	PAPAYAL LA PLAYA	\N
65	835	52	EL PINDE	\N
68	835	52	CALETA VIENTO LIBRE	\N
69	835	52	CEIBITO	\N
71	835	52	EL CARMEN KM 36	\N
75	835	52	BOCANA NUEVA	\N
77	835	52	CHILVICITO	\N
83	835	52	LA SIRENA	\N
85	835	52	PALAY	\N
87	835	52	PULGANDE	\N
88	835	52	RETONO	\N
91	835	52	SANTA ROSA	\N
92	835	52	ALTO AGUA CLARA	\N
93	835	52	IMBILPI DEL CARMEN	\N
99	835	52	INGUAPI DEL CARMEN	\N
100	835	52	SANTA MARIA ROSARIO	\N
101	835	52	LA BARCA	\N
102	835	52	EL COCO	\N
104	835	52	ALBANIA	\N
107	835	52	BAJO JAGUA	\N
108	835	52	BRISAS DEL ACUEDUCTO	\N
109	835	52	CACAGUAL	\N
111	835	52	CORRIENTE GRANDE	\N
114	835	52	GUABAL	\N
115	835	52	GUACHAL	\N
116	835	52	GUALTAL	\N
118	835	52	EL RETORNO	\N
119	835	52	JUAN DOMINGO	\N
120	835	52	KILOMETRO 28	\N
121	835	52	KILOMETRO 35	\N
123	835	52	KILOMETRO 58	\N
125	835	52	LA CHORRERA	\N
127	835	52	PINUELA RIO MIRA	\N
129	835	52	LA VEGA	\N
130	835	52	MAJAGUA	\N
132	835	52	MILAGROS	\N
133	835	52	PACORA	\N
134	835	52	PINDALES	\N
136	835	52	PUEBLO NUEVO	\N
140	835	52	TAMBILLO	\N
141	835	52	TANGAREAL CARRETERA	\N
144	835	52	VUELTA CANDELILLA	\N
146	835	52	FIRME DE LOS COIMES	\N
148	835	52	TABLON DULCE LA PAMPA	\N
149	835	52	LAS MERCEDES	\N
150	835	52	BELLAVISTA	\N
151	835	52	VAQUERIA COLOMBIA GRANDE	\N
152	835	52	INGUAPI EL GUADUAL	\N
153	835	52	CONGAL	\N
154	835	52	BAJO GUABAL	\N
155	835	52	PENA COLORADA	\N
156	835	52	BUCHELY	\N
157	835	52	CAJAPI	\N
158	835	52	VARIANTE	\N
159	835	52	DOS QUEBRADAS	\N
160	835	52	CANDELILLA	\N
161	835	52	PINAL SALADO	\N
162	835	52	CHONTAL	\N
163	835	52	IMBILI	\N
164	835	52	SAN PEDRO DEL VINO	\N
165	835	52	VUELTA DEL GALLO	\N
166	835	52	SAN SEBASTIAN	\N
167	835	52	BOCA DE TULMO	\N
168	835	52	SAGUMBITA	\N
174	835	52	LA BRAVA RIO CAUNAPI	\N
177	835	52	ACHOTAL	\N
178	835	52	AGUACATE	\N
179	835	52	ALTO BUENOS AIRES	\N
180	835	52	ALTO JAGUA (RIO MIRA)	\N
181	835	52	ALTO SANTO DOMINGO	\N
182	835	52	ALTO VILLARICA	\N
183	835	52	BAJO BUENOS AIRES (TABLON SALADO)	\N
184	835	52	BOCAGRANDE	\N
185	835	52	BUCHELY 2	\N
186	835	52	CAJAPI DEL MIRA	\N
187	835	52	CANDELILLAS DE LA MAR	\N
188	835	52	CHIMBUZAL	\N
189	835	52	EL PROGRESO - SANTO DOMINGO	\N
190	835	52	GUACHIRI	\N
191	835	52	GUADUAL	\N
192	835	52	IMBILI EL GUABO	\N
193	835	52	INDA ZABALETA	\N
194	835	52	INGUAPI DEL CARMEN 2	\N
195	835	52	INGUAPI DEL GUAYABO	\N
196	835	52	INGUAPI LA CHIRICANA	\N
197	835	52	ISLA GRANDE	\N
198	835	52	LA BALSA	\N
199	835	52	LA BRAVA	\N
200	835	52	LA CONCHA (TABLON SALADO)	\N
201	835	52	13 DE MAYO	\N
202	835	52	LAS BRISAS	\N
203	835	52	NERETE (RIO MIRA)	\N
204	835	52	NUEVA REFORMA	\N
205	835	52	NUEVO PUERTO NIDIA	\N
206	835	52	OLIVO CURAY	\N
207	835	52	PARAISO	\N
208	835	52	PINAL DULCE	\N
209	835	52	PITAL (CHIMBUZAL)	\N
210	835	52	PORVENIR	\N
211	835	52	PUEBLO NUEVO (RIO MIRA)	\N
212	835	52	PUEBLO NUEVO (TABLON SALADO)	\N
213	835	52	RESTREPO	\N
214	835	52	SAN AGUSTIN	\N
215	835	52	SAN FRANCISCO	\N
216	835	52	SAN JUAN PUEBLO NUEVO	\N
217	835	52	SAN JUAN (RIO MIRA)	\N
218	835	52	SAN PABLO	\N
219	835	52	SAN VICENTE (LAS VARAS)	\N
220	835	52	SANDER CURAY	\N
221	835	52	SEIS DE AGOSTO	\N
222	835	52	SOLEDAD CURAY I	\N
223	835	52	SOLEDAD CURAY II	\N
224	835	52	TANGAREAL DEL MIRA	\N
225	835	52	TRUJILLO	\N
226	835	52	VIGUARAL	\N
227	835	52	VUELTA CANDELILLAS 2	\N
228	835	52	VUELTA DEL CARMEN	\N
229	835	52	VUELTA LARGA (RIO GUANAPI)	\N
230	835	52	ZAPOTAL	\N
231	835	52	CRISTO REY	\N
232	835	52	KILOMETRO 63	\N
233	835	52	KILOMETRO 75 LA INVASION	\N
234	835	52	LA VINA	\N
235	835	52	VAQUERIA LA TORRE	\N
0	838	52	TUQUERRES	\N
1	838	52	ALBAN	\N
2	838	52	CUATRO ESQUINAS	\N
4	838	52	PINZON	\N
7	838	52	SANTANDER	\N
9	838	52	YASCUAL	\N
12	838	52	LOS ARRAYANES	\N
0	885	52	YACUANQUER	\N
6	885	52	MEJIA	\N
7	885	52	LA AGUADA	\N
0	1	54	SAN JOSE DE CUCUTA	\N
1	1	54	AGUA CLARA	\N
2	1	54	BANCO DE ARENAS	\N
3	1	54	BUENA ESPERANZA	\N
7	1	54	PATILLALES	\N
11	1	54	PUERTO VILLAMIZAR	\N
15	1	54	RICAURTE	\N
17	1	54	SAN FAUSTINO	\N
18	1	54	SAN PEDRO	\N
25	1	54	ARRAYAN	\N
28	1	54	ALTO VIENTO	\N
29	1	54	EL PRADO	\N
30	1	54	PORTICO	\N
33	1	54	LA JARRA	\N
36	1	54	PALMARITO	\N
38	1	54	PUERTO LEON	\N
39	1	54	PUERTO NUEVO	\N
41	1	54	GUARAMITO	\N
42	1	54	LA FLORESTA	\N
43	1	54	LA PUNTA	\N
44	1	54	VIIGILANCIA	\N
45	1	54	PUERTO LLERAS	\N
46	1	54	SANTA CECILIA	\N
47	1	54	CARMEN DE TONCHALA	\N
48	1	54	LOS NEGROS	\N
49	1	54	ORIPAYA	\N
50	1	54	LAS VACAS	\N
53	1	54	BELLA VISTA	\N
54	1	54	EL PLOMO	\N
55	1	54	EL SUSPIRO	\N
56	1	54	LA SABANA	\N
57	1	54	NUEVO MADRID	\N
58	1	54	SAN AGUSTIN DE LOS POZOS	\N
61	1	54	AGUA BLANCA	\N
67	1	54	LONDRES	\N
68	1	54	BANCO DE ARENAS 2	\N
0	3	54	ABREGO	\N
2	3	54	CASITAS	\N
0	51	54	ARBOLEDAS	\N
2	51	54	CASTRO	\N
5	51	54	VILLA SUCRE	\N
0	99	54	BOCHALEMA	\N
2	99	54	LA DONJUANA	\N
5	99	54	LA ESMERALDA	\N
0	109	54	BUCARASICA	\N
2	109	54	LA CURVA	\N
3	109	54	LA SANJUANA	\N
0	125	54	CACOTA	\N
0	128	54	CACHIRA	\N
1	128	54	LA CARRERA	\N
3	128	54	LA VEGA	\N
11	128	54	LOS MANGOS	\N
15	128	54	SAN JOSE DEL LLANO	\N
0	172	54	CHINACOTA	\N
1	172	54	LA NUEVA DONJUANA	\N
5	172	54	EL NUEVO DIAMANTE	\N
6	172	54	CHITACOMAR	\N
7	172	54	RECTA LOS ALAMOS	\N
8	172	54	TENERIA	\N
0	174	54	CHITAGA	\N
2	174	54	SAN LUIS DE CHUCARIMA	\N
5	174	54	LLANO GRANDE	\N
6	174	54	PRESIDENTE	\N
8	174	54	CARRILLO	\N
0	206	54	CONVENCION	\N
1	206	54	BALCONES	\N
2	206	54	CARTAGENITA	\N
4	206	54	EL GUAMAL	\N
5	206	54	LAS MERCEDES	\N
8	206	54	SOLEDAD	\N
11	206	54	LA LIBERTAD	\N
12	206	54	LA VEGA	\N
13	206	54	PIEDECUESTA	\N
14	206	54	HONDURAS LA MOTILONA	\N
15	206	54	LA TRINIDAD	\N
16	206	54	MIRAFLORES	\N
0	223	54	CUCUTILLA	\N
2	223	54	SAN JOSE DE LA MONTANA	\N
4	223	54	TIERRA GRATA	\N
5	223	54	SAN MIGUEL	\N
0	239	54	DURANIA	\N
2	239	54	HATOVIEJO	\N
3	239	54	LA CUCHILLA	\N
4	239	54	LA MONTUOSA	\N
7	239	54	LAS AGUADAS	\N
0	245	54	EL CARMEN	\N
3	245	54	LA CULEBRITA	\N
6	245	54	GUAMALITO	\N
0	250	54	EL TARRA	\N
1	250	54	BELLA VISTA	\N
2	250	54	ORU	\N
3	250	54	FILO GRINGO	\N
5	250	54	EL PASO	\N
6	250	54	LAS TORRES	\N
7	250	54	LA CAMPANA	\N
8	250	54	LA MOTILANDIA	\N
0	261	54	EL ZULIA	\N
6	261	54	LAS PIEDRAS	\N
7	261	54	ASTILLEROS LA YE	\N
8	261	54	CAMILANDIA	\N
9	261	54	CRISTO REY	\N
10	261	54	EL TABLAZO	\N
11	261	54	LAS BRISAS	\N
12	261	54	SANTA ROSA	\N
13	261	54	7 DE AGOSTO	\N
0	313	54	GRAMALOTE	\N
5	313	54	LA LOMITA	\N
6	313	54	LA ESTRELLA	\N
7	313	54	POMARROSO	\N
0	344	54	HACARI	\N
3	344	54	MARACAIBO	\N
8	344	54	SAN JOSE DEL TARRA	\N
9	344	54	LAS JUNTAS	\N
11	344	54	LA ESTACION O MESITAS	\N
0	347	54	HERRAN	\N
0	377	54	LABATECA	\N
0	385	54	LA ESPERANZA	\N
1	385	54	LA PEDREGOSA	\N
2	385	54	LEON XIII	\N
3	385	54	PUEBLO NUEVO	\N
5	385	54	EL TROPEZON	\N
7	385	54	LOS CEDROS	\N
8	385	54	VILLAMARIA	\N
9	385	54	CAMPO ALEGRE	\N
10	385	54	LA CANCHA	\N
0	398	54	LA PLAYA	\N
2	398	54	ASPASICA	\N
6	398	54	LA VEGA DE SAN ANTONIO	\N
0	405	54	LOS PATIOS	\N
1	405	54	LA GARITA	\N
3	405	54	LOS VADOS	\N
4	405	54	AGUA LINDA	\N
6	405	54	EL TRAPICHE	\N
7	405	54	COROZAL VEREDA CALIFORNIA	\N
8	405	54	LAGOS DE PALUJAN	\N
9	405	54	RECTA COROZAL	\N
10	405	54	VILLA KATHERINE	\N
11	405	54	VILLAS DE COROZAL	\N
0	418	54	LOURDES	\N
0	480	54	MUTISCUA	\N
1	480	54	LA LAGUNA	\N
3	480	54	LA CALDERA	\N
0	498	54	OCANA	\N
2	498	54	AGUASCLARAS	\N
5	498	54	OTARE	\N
6	498	54	BUENAVISTA	\N
8	498	54	LA ERMITA	\N
9	498	54	LA FLORESTA	\N
12	498	54	PUEBLO NUEVO	\N
25	498	54	ALGODONAL	\N
26	498	54	CLUB ALL STAR	\N
0	518	54	PAMPLONA	\N
0	520	54	PAMPLONITA	\N
1	520	54	EL DIAMANTE	\N
2	520	54	EL TREVEJO	\N
0	553	54	PUERTO SANTANDER	\N
1	553	54	EL DIAMANTE	\N
0	599	54	RAGONVALIA	\N
5	599	54	CALICHES	\N
0	660	54	SALAZAR DE LAS PALMAS	\N
1	660	54	EL CARMEN DE NAZARETH	\N
2	660	54	LA LAGUNA	\N
5	660	54	SAN JOSE DEL AVILA	\N
7	660	54	EL SALADO	\N
0	670	54	SAN CALIXTO	\N
4	670	54	VISTA HERMOSA	\N
10	670	54	PALMARITO	\N
16	670	54	LA QUINA	\N
17	670	54	LAGUNITAS	\N
0	673	54	SAN CAYETANO	\N
2	673	54	CORNEJO	\N
5	673	54	URIMACO	\N
8	673	54	SAN ISIDRO	\N
0	680	54	SANTIAGO	\N
0	720	54	SARDINATA	\N
2	720	54	EL CARMEN	\N
3	720	54	LA VICTORIA	\N
4	720	54	LAS MERCEDES	\N
5	720	54	LUIS VERO	\N
6	720	54	SAN MARTIN DE LOBA	\N
7	720	54	SAN ROQUE	\N
0	743	54	SILOS	\N
1	743	54	BABEGA	\N
3	743	54	LOS RINCON	\N
4	743	54	LA LAGUNA	\N
6	743	54	PACHAGUAL	\N
0	800	54	TEORAMA	\N
11	800	54	LA CECILIA	\N
22	800	54	SAN PABLO	\N
25	800	54	QUINCE LETRAS	\N
26	800	54	EL ASERRIO	\N
0	810	54	TIBU	\N
1	810	54	BARCO LA SILLA	\N
2	810	54	LA GABARRA	\N
3	810	54	PACCELLY	\N
6	810	54	TRES BOCAS	\N
8	810	54	PETROLEA	\N
9	810	54	VERSALLES	\N
11	810	54	CAMPO GILES	\N
13	810	54	LA LLANA	\N
16	810	54	VETAS DE ORIENTE	\N
17	810	54	CAMPO DOS	\N
18	810	54	LA CUATRO	\N
0	820	54	TOLEDO	\N
8	820	54	SAN BERNARDO DE BATA	\N
15	820	54	SAMORE	\N
0	871	54	VILLA CARO	\N
0	874	54	VILLA DEL ROSARIO	\N
1	874	54	JUAN FRIO	\N
8	874	54	PALOGORDO NORTE	\N
0	1	63	ARMENIA	\N
1	1	63	EL CAIMO	\N
2	1	63	MURILLO	\N
8	1	63	CASERIO SANTA HELENA	\N
9	1	63	CONDOMINIO EL EDEN	\N
10	1	63	CONDOMINIOS LAS VEGAS, IRAKA Y LAGOS DE IRAKA	\N
11	1	63	CONDOMINIO PALO DE AGUA	\N
12	1	63	CONDOMINIO PONTEVEDRA	\N
13	1	63	CONDOMINIO SENIORS CLUB	\N
15	1	63	NUEVO HORIZONTE - SAPERA	\N
16	1	63	SECTOR CENEXPO	\N
0	111	63	BUENAVISTA	\N
1	111	63	RIO VERDE	\N
0	130	63	CALARCA	\N
1	130	63	BARCELONA	\N
3	130	63	LA BELLA	\N
4	130	63	LA VIRGINIA	\N
5	130	63	QUEBRADANEGRA	\N
8	130	63	LA PRADERA	\N
10	130	63	LA MARIA	\N
13	130	63	BARRAGAN	\N
15	130	63	CONDOMINIO LOS ALMENDROS	\N
16	130	63	CONDOMINIO VALLE DEL SOL	\N
18	130	63	CONDOMINIO AGUA BONITA	\N
19	130	63	CONDOMINIO LA MICAELA	\N
0	190	63	CIRCASIA	\N
1	190	63	LA POLA	\N
2	190	63	LA SIRIA	\N
3	190	63	PIAMONTE	\N
4	190	63	SANTA RITA	\N
6	190	63	VILLARAZO - SAN LUIS	\N
7	190	63	LA JULIA	\N
8	190	63	LA FRONTERA	\N
9	190	63	EL TRIUNFO	\N
10	190	63	EL PLANAZO	\N
11	190	63	LA 18 GUAYABAL	\N
12	190	63	URBANIZACION EL CANEY	\N
13	190	63	CONDOMINIO BOSQUES DE TOSCANA	\N
14	190	63	CONDOMINIO LA ALDEA	\N
16	190	63	CONDOMINIO LOS ABEDULES Y YARUMOS I	\N
17	190	63	CONDOMINIO LOS ROBLES	\N
18	190	63	CONDOMINIO LOS ROSALES	\N
20	190	63	CONDOMINIO QUINTAS DEL BOSQUE	\N
22	190	63	VILLA LIGIA	\N
24	190	63	LA CABANA	\N
28	190	63	CONDOMINIO MONTERREY	\N
30	190	63	CONDOMINIO ANGELES DEL BOSQUE	\N
31	190	63	CONDOMINIO CEDRO NEGRO	\N
32	190	63	CONDOMINIO RESERVAS DEL BOSQUE	\N
33	190	63	EL CONGAL	\N
34	190	63	LA CRISTALINA	\N
0	212	63	CORDOBA	\N
0	272	63	FILANDIA	\N
2	272	63	LA INDIA	\N
0	302	63	GENOVA	\N
0	401	63	LA TEBAIDA	\N
3	401	63	LA SILVIA	\N
4	401	63	FUNDACION AMANECER	\N
5	401	63	CONDOMINIO LA ESTACION	\N
6	401	63	CONDOMINIO MORACAWA	\N
7	401	63	MURILLO	\N
0	470	63	MONTENEGRO	\N
1	470	63	EL CUZCO	\N
3	470	63	PUEBLO TAPADO	\N
4	470	63	ONCE CASAS	\N
7	470	63	EL CASTILLO	\N
8	470	63	EL GIGANTE	\N
9	470	63	BARAYA	\N
10	470	63	LA MONTANA	\N
13	470	63	CALLE LARGA	\N
14	470	63	CONDOMINIO LA HACIENDA	\N
0	548	63	PIJAO	\N
1	548	63	BARRAGAN	\N
2	548	63	LA MARIELA	\N
0	594	63	QUIMBAYA	\N
2	594	63	EL LAUREL	\N
3	594	63	LA ESPANOLA	\N
5	594	63	PUEBLO RICO	\N
6	594	63	PUERTO ALEJANDRIA	\N
7	594	63	EL NARANJAL	\N
8	594	63	TROCADEROS	\N
9	594	63	CONDOMINIO CAMPESTRE INCAS PANACA	\N
0	690	63	SALENTO	\N
1	690	63	BOQUIA	\N
5	690	63	LA EXPLANACION	\N
7	690	63	CONDOMINIO LAS COLINAS	\N
9	690	63	SAN JUAN DE CAROLINA	\N
10	690	63	SAN ANTONIO	\N
11	690	63	LOS PINOS	\N
0	1	66	PEREIRA	\N
1	1	66	ALTAGRACIA	\N
2	1	66	ARABIA	\N
3	1	66	BETULIA	\N
4	1	66	CAIMALITO	\N
5	1	66	EL PLACER (COMBIA)	\N
6	1	66	LA CONVENCION	\N
7	1	66	EL ROCIO	\N
9	1	66	LA BELLA	\N
10	1	66	LA FLORIDA	\N
13	1	66	LA SELVA	\N
15	1	66	MORELIA	\N
16	1	66	MUNDO NUEVO	\N
18	1	66	PUERTO CALDAS	\N
20	1	66	SAN JOSE	\N
21	1	66	TRIBUNAS CORCEGA	\N
25	1	66	NUEVA SIRIA	\N
26	1	66	SAN VICENTE	\N
28	1	66	YARUMAL	\N
29	1	66	LA BANANERA	\N
30	1	66	ALTO ALEGRIAS	\N
31	1	66	ALEGRIAS	\N
32	1	66	PEREZ ALTO	\N
35	1	66	HUERTAS	\N
36	1	66	PITAL DE COMBIA	\N
37	1	66	EL CHOCHO	\N
39	1	66	BARRIO EL BOSQUE	\N
40	1	66	LA CABANITA	\N
41	1	66	BELMONTE BAJO	\N
43	1	66	BETANIA	\N
44	1	66	BRISAS DE CONDINA (LA GRAMINEA)	\N
45	1	66	CALLE LARGA	\N
46	1	66	CARACOL LA CURVA	\N
47	1	66	CESTILLAL	\N
48	1	66	EL CONTENTO	\N
49	1	66	EL CRUCERO DE COMBIA	\N
50	1	66	EL JAZMIN	\N
51	1	66	EL MANZANO	\N
52	1	66	EL PORVENIR	\N
53	1	66	ESPERANZA GALICIA	\N
54	1	66	ESTACION AZUFRAL	\N
55	1	66	ESTRELLA MORRON	\N
56	1	66	CONDINA GUACARY	\N
57	1	66	LAGUNETA	\N
58	1	66	LA ESTRELLA	\N
59	1	66	NUEVO SOL	\N
60	1	66	PLAN DE VIVIENDA LA UNION	\N
61	1	66	PLAN DE VIVIENDA YARUMAL	\N
62	1	66	PUEBLO NUEVO	\N
63	1	66	SAN CARLOS	\N
64	1	66	YARUMITO	\N
65	1	66	PENJAMO	\N
66	1	66	ALTO ERAZO	\N
67	1	66	CANCELES	\N
69	1	66	EL CONGOLO	\N
70	1	66	EL JARDIN	\N
71	1	66	ESTACION VILLEGAS	\N
72	1	66	GALICIA ALTA	\N
73	1	66	GILIPINAS	\N
74	1	66	HERIBERTO HERRERA	\N
75	1	66	LA CARBONERA	\N
77	1	66	LA RENTA	\N
78	1	66	LA SUIZA	\N
79	1	66	LA YE	\N
80	1	66	LIBARE	\N
81	1	66	PEREZ BAJO	\N
82	1	66	SAN MARINO	\N
83	1	66	TRIBUNAS CONSOTA	\N
84	1	66	EL JORDAN	\N
85	1	66	SANTANDER	\N
89	1	66	CONDOMINIO ANDALUZ	\N
90	1	66	CONDOMINIO EL PARAISO	\N
91	1	66	CONDOMINIO MACONDO	\N
92	1	66	CONDOMINIO MARACAY	\N
93	1	66	CONDOMINIO PALMAR	\N
94	1	66	GAITAN LA PLAYA	\N
0	45	66	APIA	\N
4	45	66	JORDANIA	\N
9	45	66	LA MARIA	\N
0	75	66	BALBOA	\N
4	75	66	TAMBORES	\N
5	75	66	SAN ANTONIO	\N
9	75	66	TRES ESQUINAS	\N
0	88	66	BELEN DE UMBRIA	\N
2	88	66	COLUMBIA	\N
4	88	66	PUENTE UMBRIA	\N
5	88	66	TAPARCAL	\N
10	88	66	EL AGUACATE	\N
11	88	66	EL CONGO	\N
0	170	66	DOSQUEBRADAS	\N
1	170	66	ALTO DEL TORO	\N
6	170	66	LA UNION	\N
8	170	66	AGUAZUL	\N
9	170	66	BUENA VISTA	\N
10	170	66	COMUNEROS	\N
11	170	66	EL ESTANQUILLO	\N
12	170	66	GAITAN	\N
13	170	66	LA DIVISA	\N
14	170	66	LA ESMERALDA	\N
17	170	66	LA PLAYITA	\N
20	170	66	NARANJALES	\N
21	170	66	SANTANA BAJA	\N
22	170	66	VILLA CAROLA	\N
24	170	66	EL COFRE	\N
25	170	66	GAITAN LA PLAYA	\N
26	170	66	LA DIVISA PARTE ALTA	\N
0	318	66	GUATICA	\N
2	318	66	SAN CLEMENTE	\N
3	318	66	SANTA ANA	\N
6	318	66	TRAVESIAS	\N
0	383	66	LA CELIA	\N
1	383	66	PATIO BONITO	\N
0	400	66	LA VIRGINIA	\N
1	400	66	LA PALMA	\N
0	440	66	MARSELLA	\N
1	440	66	ALTO CAUCA	\N
2	440	66	BELTRAN	\N
3	440	66	LA ARGENTINA	\N
8	440	66	PLAN DE VIVIENDA EL RAYO	\N
9	440	66	ESTACION PEREIRA	\N
13	440	66	PLAN DE VIVIENDA TACURRUMBI	\N
0	456	66	MISTRATO	\N
2	456	66	PUERTO DE ORO	\N
3	456	66	SAN ANTONIO DEL CHAMI	\N
5	456	66	ALTO PUEBLO RICO	\N
8	456	66	MAMPAY	\N
10	456	66	PINAR DEL RIO	\N
11	456	66	QUEBRADA ARRIBA	\N
12	456	66	RIO MISTRATO	\N
0	572	66	PUEBLO RICO	\N
1	572	66	SANTA CECILIA	\N
2	572	66	VILLA CLARET	\N
0	594	66	QUINCHIA	\N
1	594	66	BATERO	\N
2	594	66	IRRA	\N
6	594	66	NARANJAL	\N
7	594	66	SANTA ELENA	\N
12	594	66	MORETA	\N
14	594	66	SAN JOSE	\N
19	594	66	VILLA RICA	\N
0	682	66	SANTA ROSA DE CABAL	\N
3	682	66	EL ESPANOL	\N
6	682	66	GUACAS	\N
8	682	66	LA CAPILLA	\N
9	682	66	LA ESTRELLA	\N
10	682	66	LAS MANGAS	\N
12	682	66	SANTA BARBARA	\N
16	682	66	GUAIMARAL	\N
17	682	66	EL LEMBO	\N
19	682	66	BAJO SAMARIA	\N
20	682	66	LA FLORIDA	\N
22	682	66	EL JAZMIN	\N
24	682	66	LA LEONA	\N
25	682	66	SAN JUANITO	\N
0	687	66	SANTUARIO	\N
2	687	66	LA MARINA	\N
3	687	66	PERALONSO	\N
14	687	66	PLAYA RICA	\N
15	687	66	EL ROSAL	\N
0	1	68	BUCARAMANGA	\N
15	1	68	EL NOGAL	\N
16	1	68	EL PEDREGAL	\N
17	1	68	VIJAGUAL	\N
18	1	68	VILLA CARMELO	\N
19	1	68	VILLA LUZ	\N
0	13	68	AGUADA	\N
0	20	68	ALBANIA	\N
1	20	68	CARRETERO	\N
2	20	68	EL HATILLO	\N
3	20	68	LA MESA	\N
0	51	68	ARATOCA	\N
1	51	68	CHIFLAS	\N
12	51	68	EL HOYO	\N
13	51	68	BRISAS DEL CHICAMOCHA	\N
0	77	68	BARBOSA	\N
1	77	68	CITE	\N
3	77	68	BUENAVISTA	\N
5	77	68	CRISTALES	\N
6	77	68	FRANCISCO DE PAULA	\N
0	79	68	BARICHARA	\N
1	79	68	GUANE	\N
0	81	68	BARRANCABERMEJA, DISTRITO ESPECIAL, PORTUARIO, BIODIVERSO, INDUSTRIAL Y TURISTICO	\N
1	81	68	EL CENTRO	\N
2	81	68	EL LLANITO	\N
4	81	68	MESETA SAN RAFAEL	\N
6	81	68	SAN RAFAEL DE CHUCURI	\N
9	81	68	LOS LAURELES	\N
10	81	68	LA FORTUNA	\N
12	81	68	CAMPO 16	\N
13	81	68	CAMPO 23	\N
15	81	68	CAMPO 6	\N
16	81	68	CAMPO GALAN	\N
18	81	68	CIENAGA DE OPON	\N
20	81	68	CRETACEO	\N
23	81	68	GALAN BERLIN	\N
27	81	68	LA FOREST	\N
31	81	68	PROGRESO	\N
32	81	68	PUEBLO REGAO	\N
34	81	68	QUEMADERO	\N
36	81	68	EL PALMAR	\N
0	92	68	BETULIA	\N
8	92	68	TIENDA NUEVA	\N
10	92	68	LA PLAYA	\N
12	92	68	EL PEAJE	\N
0	101	68	BOLIVAR	\N
2	101	68	BERBEO	\N
7	101	68	FLOREZ	\N
10	101	68	SANTA ROSA	\N
12	101	68	TRAPAL	\N
14	101	68	LA HERMOSURA	\N
15	101	68	LA MELONA	\N
16	101	68	GALLEGOS	\N
25	101	68	SAN MARCOS	\N
0	121	68	CABRERA	\N
0	132	68	CALIFORNIA	\N
2	132	68	LA BAJA	\N
0	147	68	CAPITANEJO	\N
0	152	68	CARCASI	\N
1	152	68	EL TOBAL	\N
0	160	68	CEPITA	\N
0	162	68	CERRITO	\N
7	162	68	SERVITA	\N
0	167	68	CHARALA	\N
3	167	68	RIACHUELO	\N
5	167	68	VIROLIN	\N
0	169	68	CHARTA	\N
0	176	68	CHIMA	\N
0	179	68	CHIPATA	\N
10	179	68	PUENTE GRANDE	\N
0	190	68	CIMITARRA	\N
2	190	68	PUERTO ARAUJO	\N
3	190	68	PUERTO OLAYA	\N
4	190	68	ZAMBITO	\N
5	190	68	DOS HERMANOS	\N
6	190	68	SANTA ROSA	\N
9	190	68	LA VERDE	\N
10	190	68	GUAYABITO BAJO	\N
12	190	68	CAMPO SECO	\N
14	190	68	SAN FERNANDO	\N
18	190	68	EL ATERRADO	\N
19	190	68	LA CARRILERA - KM 28	\N
21	190	68	PALMAS DEL GUAYABITO	\N
22	190	68	PRIMAVERA	\N
23	190	68	SAN JUAN DE LA CARRETERA	\N
24	190	68	SAN PEDRO DE LA PAZ	\N
25	190	68	CAMPO PADILLA	\N
28	190	68	CASCAJERO	\N
30	190	68	LA TRAVIATA	\N
32	190	68	LA YE DE LA TORRE	\N
0	207	68	CONCEPCION	\N
0	209	68	CONFINES	\N
0	211	68	CONTRATACION	\N
1	211	68	SAN PABLO	\N
0	217	68	COROMORO	\N
1	217	68	CINCELADA	\N
0	229	68	CURITI	\N
0	235	68	EL CARMEN DE CHUCURI	\N
1	235	68	ANGOSTURAS DE LOS ANDES	\N
4	235	68	EL CENTENARIO	\N
9	235	68	SANTO DOMINGO DEL RAMO	\N
17	235	68	LA EXPLANACION	\N
22	235	68	EL 27	\N
0	245	68	EL GUACAMAYO	\N
3	245	68	SANTA RITA	\N
0	250	68	EL PENON	\N
2	250	68	CRUCES	\N
3	250	68	OTOVAL - RIO BLANCO	\N
6	250	68	EL GODO	\N
7	250	68	GIRON	\N
9	250	68	SAN FRANCISCO	\N
0	255	68	EL PLAYON	\N
1	255	68	BARRIO NUEVO	\N
2	255	68	BETANIA	\N
7	255	68	ESTACION LAGUNA	\N
14	255	68	SAN PEDRO DE TIGRA	\N
0	264	68	ENCINO	\N
0	266	68	ENCISO	\N
2	266	68	PENA COLORADA	\N
0	271	68	FLORIAN	\N
1	271	68	LA VENTA	\N
3	271	68	SAN ANTONIO DE LEONES	\N
0	276	68	FLORIDABLANCA	\N
11	276	68	EL MORTINO	\N
12	276	68	MONTEARROYO CONDOMINIO	\N
13	276	68	RUITOQUE COUNTRY CLUB CONDOMINIO	\N
14	276	68	VALLE DE RUITOQUE	\N
16	276	68	VILLAS DE GUADALQUIVIR	\N
18	276	68	ALTOS DE ORIENTE	\N
19	276	68	BELLAVISTA	\N
20	276	68	KM 14	\N
21	276	68	LA CIDRA	\N
22	276	68	TRINITARIOS	\N
23	276	68	CALATRAVA	\N
0	296	68	GALAN	\N
0	298	68	GAMBITA	\N
2	298	68	LA PALMA	\N
0	307	68	GIRON	\N
2	307	68	BOCAS	\N
3	307	68	MARTA	\N
7	307	68	ACAPULCO	\N
18	307	68	CHOCOITA	\N
20	307	68	EL LAGUITO	\N
21	307	68	PUEBLITO VIEJO	\N
22	307	68	RIO DE ORO	\N
0	318	68	GUACA	\N
1	318	68	BARAYA	\N
0	320	68	GUADALUPE	\N
5	320	68	EL TIRANO	\N
0	322	68	GUAPOTA	\N
0	324	68	GUAVATA	\N
0	327	68	GUEPSA	\N
0	344	68	HATO	\N
0	368	68	JESUS MARIA	\N
0	370	68	JORDAN SUBE	\N
0	377	68	LA BELLEZA	\N
1	377	68	LA QUITAZ	\N
3	377	68	EL RUBI	\N
5	377	68	LOS VALLES	\N
0	385	68	LANDAZURI	\N
1	385	68	BAJO JORDAN	\N
5	385	68	PLAN DE ARMAS	\N
6	385	68	SAN IGNACIO DEL OPON	\N
7	385	68	MIRALINDO	\N
8	385	68	KILOMETRO 15	\N
9	385	68	LA INDIA	\N
14	385	68	RIO BLANCO	\N
0	397	68	LA PAZ	\N
1	397	68	EL HATO	\N
2	397	68	LA LOMA	\N
5	397	68	TROCHAL	\N
0	406	68	LEBRIJA	\N
3	406	68	EL CONCHAL	\N
9	406	68	PORTUGAL	\N
13	406	68	URIBE URIBE	\N
14	406	68	VANEGAS	\N
26	406	68	CONDOMINIO VILLAS DE PALO NEGRO	\N
0	418	68	LOS SANTOS	\N
6	418	68	MAJADAL ALTO	\N
0	425	68	MACARAVITA	\N
3	425	68	LA BRICHA	\N
0	432	68	MALAGA	\N
9	432	68	ASODEMA	\N
0	444	68	MATANZA	\N
6	444	68	SANTA CRUZ DE LA COLINA	\N
0	464	68	MOGOTES	\N
2	464	68	PITIGUAO	\N
16	464	68	LOS CAUCHOS	\N
0	468	68	MOLAGAVITA	\N
2	468	68	EL JUNCO	\N
0	498	68	OCAMONTE	\N
0	500	68	OIBA	\N
6	500	68	PUENTE LLANO	\N
0	502	68	ONZAGA	\N
1	502	68	PADUA	\N
2	502	68	SUSA	\N
4	502	68	EL CARMEN	\N
0	522	68	PALMAR	\N
0	524	68	PALMAS DEL SOCORRO	\N
0	533	68	PARAMO	\N
0	547	68	PIEDECUESTA	\N
3	547	68	SEVILLA	\N
7	547	68	UMPALA	\N
8	547	68	PESCADERO	\N
10	547	68	LA COLINA	\N
11	547	68	LOS CUROS	\N
16	547	68	CONDOMINIO RUITOQUE COUNTRY CLUB	\N
17	547	68	LA ESPERANZA	\N
18	547	68	BUENOS AIRES MESA RUITOQUE	\N
19	547	68	LA VEGA	\N
20	547	68	LA DIVA	\N
21	547	68	ALTAMIRA	\N
22	547	68	CAMPO CAMPINA	\N
23	547	68	CIUDAD TEYUNA	\N
24	547	68	EDEN	\N
25	547	68	MIRADOR DEL LAGO	\N
26	547	68	NUEVA COLOMBIA	\N
28	547	68	PARAMITO	\N
0	549	68	PINCHOTE	\N
3	549	68	BARRIO PORTAL DEL CONDE	\N
0	572	68	PUENTE NACIONAL	\N
2	572	68	CAPILLA	\N
3	572	68	PROVIDENCIA	\N
4	572	68	QUEBRADA NEGRA	\N
12	572	68	PENA BLANCA	\N
0	573	68	PUERTO PARRA	\N
1	573	68	CAMPO CAPOTE	\N
2	573	68	LAS MONTOYAS	\N
3	573	68	BOCAS DE CARARE O CARARE VIEJO	\N
5	573	68	EL CRUCE	\N
0	575	68	PUERTO WILCHES	\N
1	575	68	BADILLO	\N
3	575	68	BOCAS ROSARIO	\N
4	575	68	CARPINTERO	\N
5	575	68	CHINGALE	\N
6	575	68	EL GUAYABO	\N
7	575	68	EL PEDRAL	\N
11	575	68	KILOMETRO 20 - COMUNEROS	\N
13	575	68	PATURIA	\N
14	575	68	PRADILLA	\N
15	575	68	PUENTE SOGAMOSO	\N
18	575	68	VIJAGUAL	\N
19	575	68	PUERTO CAYUMBA	\N
22	575	68	SITIO NUEVO	\N
23	575	68	KILOMETRO OCHO	\N
24	575	68	SAN CLAVER	\N
25	575	68	GARCIA CADENA	\N
26	575	68	CAMPO ALEGRE	\N
27	575	68	CAMPO DURO	\N
28	575	68	CURUMITA	\N
29	575	68	SANTA TERESA	\N
30	575	68	TALADRO II	\N
0	615	68	RIONEGRO	\N
2	615	68	CUESTA RICA	\N
9	615	68	LA CEIBA	\N
11	615	68	LLANO DE PALMAS	\N
12	615	68	MISIJUAY	\N
13	615	68	PAPAYAL	\N
17	615	68	VEINTE DE JULIO	\N
27	615	68	LOS CHORROS (SAN JOSE)	\N
31	615	68	SAN RAFAEL	\N
36	615	68	EL BAMBU	\N
0	655	68	SABANA DE TORRES	\N
1	655	68	LA GOMEZ	\N
2	655	68	SABANETA	\N
4	655	68	PROVINCIA	\N
6	655	68	VERACRUZ KILOMETRO 80	\N
7	655	68	SAN LUIS DE MAGARA	\N
8	655	68	PAYOA CINCO	\N
9	655	68	PUERTO SANTOS	\N
11	655	68	CERRITO	\N
13	655	68	KILOMETRO 36	\N
14	655	68	LA PAMPA	\N
15	655	68	SAN LUIS DE RIO SUCIO	\N
0	669	68	SAN ANDRES	\N
5	669	68	LAGUNA DE ORTICES	\N
7	669	68	PANGOTE	\N
0	673	68	SAN BENITO	\N
3	673	68	SAN BENITO NUEVO	\N
4	673	68	LA CARRERA	\N
5	673	68	LAS CASITAS	\N
0	679	68	SAN GIL	\N
0	682	68	SAN JOAQUIN	\N
1	682	68	RICAURTE	\N
0	684	68	SAN JOSE DE MIRANDA	\N
9	684	68	VILLA JARDIN	\N
0	686	68	SAN MIGUEL	\N
0	689	68	SAN VICENTE DE CHUCURI	\N
1	689	68	ALBANIA	\N
12	689	68	YARIMA	\N
28	689	68	LAS ACACIAS	\N
0	705	68	SANTA BARBARA	\N
0	720	68	SANTA HELENA DEL OPON	\N
1	720	68	LA ARAGUA	\N
2	720	68	CACHIPAY	\N
4	720	68	PLAN DE ALVAREZ	\N
5	720	68	SAN JUAN BOSCO DE LA VERDE	\N
0	745	68	SIMACOTA	\N
6	745	68	LA LLANITA	\N
9	745	68	LA ROCHELA	\N
11	745	68	EL GUAMO	\N
12	745	68	PUERTO NUEVO	\N
0	755	68	SOCORRO	\N
2	755	68	BERLIN	\N
0	770	68	SUAITA	\N
1	770	68	OLIVAL	\N
2	770	68	SAN JOSE DE SUAITA	\N
3	770	68	VADO REAL	\N
8	770	68	TOLOTA	\N
0	773	68	SUCRE	\N
1	773	68	LA GRANJA	\N
2	773	68	LA PRADERA	\N
3	773	68	SABANA GRANDE	\N
14	773	68	SAN ISIDRO	\N
15	773	68	EL LIBANO	\N
0	780	68	SURATA	\N
1	780	68	CACHIRI	\N
8	780	68	TURBAY	\N
0	820	68	TONA	\N
1	820	68	BERLIN	\N
2	820	68	LA CORCOVA	\N
7	820	68	VILLAVERDE	\N
0	855	68	VALLE DE SAN JOSE	\N
0	861	68	VELEZ	\N
1	861	68	ALTO JORDAN	\N
2	861	68	GUALILO	\N
17	861	68	EL LIMON	\N
22	861	68	LOMALTA	\N
24	861	68	LOS GUAYABOS	\N
25	861	68	LA VICINIA	\N
26	861	68	PENA BLANCA	\N
0	867	68	VETAS	\N
0	872	68	VILLANUEVA	\N
0	895	68	ZAPATOCA	\N
1	895	68	LA FUENTE	\N
0	1	70	SINCELEJO	\N
1	1	70	BUENAVISTA	\N
2	1	70	CRUZ DEL BEQUE	\N
3	1	70	CHOCHO	\N
4	1	70	CERRITO DE LA PALMA	\N
5	1	70	LA ARENA	\N
6	1	70	LA CHIVERA	\N
7	1	70	LA GALLERA	\N
9	1	70	LAGUNA FLOR	\N
10	1	70	LAS HUERTAS	\N
11	1	70	LAS MAJAGUAS	\N
13	1	70	SABANAS DEL POTRERO	\N
14	1	70	SAN ANTONIO	\N
15	1	70	SAN MARTIN	\N
16	1	70	SAN RAFAEL	\N
17	1	70	BUENAVISTICA	\N
18	1	70	LAS PALMAS	\N
20	1	70	POLICARPA	\N
21	1	70	SAN JACINTO	\N
22	1	70	SAN NICOLAS	\N
23	1	70	VILLA ROSITA	\N
27	1	70	CERRO DEL NARANJO	\N
28	1	70	SANTA CRUZ	\N
0	110	70	BUENAVISTA	\N
2	110	70	CALIFORNIA	\N
4	110	70	LAS CHICHAS	\N
5	110	70	LOS ANONES	\N
6	110	70	PROVIDENCIA	\N
7	110	70	COSTA RICA	\N
0	124	70	CAIMITO	\N
1	124	70	EL MAMON	\N
2	124	70	SIETE PALMAS	\N
4	124	70	LOS CAYITOS	\N
6	124	70	LA SOLERA	\N
7	124	70	TOFEME	\N
8	124	70	CEDENO	\N
9	124	70	MOLINERO	\N
10	124	70	NUEVA ESTACION	\N
11	124	70	LA MEJIA	\N
12	124	70	LAS PAVITAS	\N
13	124	70	NUEVA ESTRELLA	\N
14	124	70	NUEVA FE	\N
15	124	70	POMPUMA	\N
16	124	70	AGUILAR	\N
17	124	70	LOS OSSAS	\N
18	124	70	PUEBLO BUHO	\N
19	124	70	PUEBLO NUEVO	\N
20	124	70	PUNTA ALFEREZ	\N
21	124	70	TANGA SOLA	\N
0	204	70	RICAURTE (COLOSO)	\N
2	204	70	CHINULITO	\N
4	204	70	EL CERRO	\N
5	204	70	BAJO DON JUAN	\N
6	204	70	EL OJITO	\N
8	204	70	CALLE LARGA	\N
9	204	70	CORAZA	\N
10	204	70	DESBARRANCADO	\N
11	204	70	EL PARAISO	\N
12	204	70	LA CEIBA	\N
13	204	70	LA ESTACION	\N
14	204	70	MARATHON	\N
15	204	70	PUEBLO NUEVO	\N
0	215	70	COROZAL	\N
1	215	70	CANTAGALLO	\N
4	215	70	CHAPINERO	\N
5	215	70	DON ALONSO	\N
6	215	70	EL MAMON	\N
9	215	70	HATO NUEVO	\N
10	215	70	LAS LLANADAS	\N
11	215	70	LAS TINAS	\N
14	215	70	SAN JOSE DE PILETA	\N
16	215	70	EL RINCON DE LAS FLORES	\N
18	215	70	LAS PENAS	\N
19	215	70	CALLE NUEVA	\N
21	215	70	LAS BRUJAS	\N
22	215	70	VILLA NUEVA	\N
23	215	70	MILAN	\N
24	215	70	PALMA SOLA	\N
0	221	70	COVENAS	\N
1	221	70	BOCA DE LA CIENAGA	\N
2	221	70	EL REPARO	\N
4	221	70	PUNTA SECA	\N
5	221	70	EL MAMEY	\N
6	221	70	BELLAVISTA	\N
0	230	70	CHALAN	\N
1	230	70	LA CEIBA	\N
3	230	70	NUEVO MANZANARES	\N
4	230	70	DESBARRANCADO	\N
6	230	70	MONTEBELLO	\N
0	233	70	EL ROBLE	\N
1	233	70	CALLEJON	\N
2	233	70	CAYO DE PALMA	\N
3	233	70	CORNETA	\N
4	233	70	EL SITIO	\N
5	233	70	LAS TABLITAS	\N
6	233	70	PALMITAL	\N
7	233	70	PATILLAL	\N
8	233	70	GRILLO ALEGRE	\N
9	233	70	RANCHO DE LA CRUZ	\N
10	233	70	SAN FRANCISCO	\N
11	233	70	SANTA ROSA	\N
12	233	70	TIERRA SANTA	\N
13	233	70	VILLAVICENCIO	\N
0	235	70	GALERAS	\N
1	235	70	BARAYA	\N
2	235	70	SAN ANDRES DE PALOMO	\N
3	235	70	SAN JOSE DE RIVERA	\N
7	235	70	PUEBLO NUEVO II	\N
9	235	70	PUEBLO NUEVO I (JUNIN)	\N
11	235	70	PUERTO FRANCO	\N
12	235	70	ABRE EL OJO	\N
13	235	70	MATA DE GUASIMO	\N
0	265	70	GUARANDA	\N
2	265	70	DIAZGRANADOS	\N
4	265	70	GAVALDA	\N
6	265	70	LA CONCORDIA	\N
8	265	70	PALMARITICO	\N
9	265	70	PUERTO LOPEZ	\N
10	265	70	LA CEJA	\N
11	265	70	LAS PAVAS	\N
12	265	70	NUEVA ESPERANZA	\N
13	265	70	TIERRA SANTA	\N
0	400	70	LA UNION	\N
1	400	70	CAYO DELGADO	\N
2	400	70	PAJARITO	\N
4	400	70	LAS PALMITAS	\N
5	400	70	SABANETA	\N
6	400	70	BOCA NEGRA	\N
7	400	70	CONGUITOS	\N
8	400	70	LA GLORIA	\N
9	400	70	VILLA FATIMA	\N
10	400	70	LA CONCEPCION	\N
0	418	70	LOS PALMITOS	\N
1	418	70	EL COLEY	\N
2	418	70	EL PINAL	\N
3	418	70	PALMAS DE VINO	\N
4	418	70	SABANAS DE BELTRAN	\N
5	418	70	SABANAS DE PEDRO	\N
6	418	70	EL HATILLO	\N
10	418	70	CHARCON	\N
11	418	70	PALMITO	\N
12	418	70	SAN JAIME	\N
0	429	70	MAJAGUAL	\N
2	429	70	EL NARANJO	\N
5	429	70	LA SIERPE	\N
6	429	70	LAS PALMITAS	\N
8	429	70	PIZA	\N
9	429	70	PUEBLONUEVO	\N
10	429	70	SAN ROQUE	\N
11	429	70	SANTANDER	\N
13	429	70	ZAPATA	\N
14	429	70	SINCELEJITO	\N
16	429	70	LEON BLANCO	\N
23	429	70	LOS PATOS	\N
25	429	70	PALMARITO	\N
26	429	70	BOCA DE LAS MUJERES	\N
27	429	70	EL INDIO NUEVO	\N
28	429	70	EL PALOMAR	\N
29	429	70	RIO FRIO	\N
30	429	70	TOTUMAL	\N
31	429	70	CORONCORO	\N
0	473	70	MORROA	\N
2	473	70	EL RINCON	\N
3	473	70	EL YESO	\N
4	473	70	LAS FLORES	\N
5	473	70	SABANETA	\N
6	473	70	TUMBATORO	\N
9	473	70	SABANAS DE CALI	\N
11	473	70	BREMEN	\N
12	473	70	EL RECREO	\N
13	473	70	EL TOLIMA	\N
14	473	70	LA VICTORIA	\N
15	473	70	PICHILIN	\N
0	508	70	OVEJAS	\N
1	508	70	ALMAGRA	\N
2	508	70	CANUTAL	\N
3	508	70	CANUTALITO	\N
4	508	70	CHENGUE	\N
5	508	70	DAMASCO	\N
6	508	70	DON GABRIEL	\N
7	508	70	EL FLORAL	\N
9	508	70	FLOR DEL MONTE	\N
11	508	70	LA PENA	\N
12	508	70	OSOS	\N
13	508	70	PIJIGUAY	\N
14	508	70	SAN RAFAEL	\N
15	508	70	SALITRAL	\N
18	508	70	SAN RAFAEL ALTO	\N
26	508	70	PEDREGAL	\N
27	508	70	SAN FRANCISCO	\N
31	508	70	ZAPATO # 2 PIJIGUAY	\N
32	508	70	ALMAGRA SECTOR LAS PASAS	\N
33	508	70	BUENOS AIRES	\N
34	508	70	ALEMANIA	\N
0	523	70	PALMITO	\N
1	523	70	ALGODONCILLO	\N
2	523	70	GUAIMARAL	\N
3	523	70	GUAMI	\N
6	523	70	CHUMPUNDUN	\N
7	523	70	EL MARTILLO	\N
8	523	70	EL PALMAR BRILLANTE	\N
10	523	70	LOS CASTILLOS	\N
11	523	70	MEDIA SOMBRA	\N
12	523	70	PUEBLECITO	\N
13	523	70	PUEBLO NUEVO	\N
14	523	70	SAN MIGUEL	\N
17	523	70	LA GRANVIA	\N
18	523	70	LOS OLIVOS	\N
0	670	70	SAMPUES	\N
1	670	70	BOSSA NAVARRO	\N
2	670	70	CEJA DEL MANGO	\N
3	670	70	ESCOBAR ABAJO	\N
4	670	70	ESCOBAR ARRIBA	\N
7	670	70	MATEO PEREZ	\N
8	670	70	SANTA INES DE PALITO	\N
9	670	70	PIEDRAS BLANCAS	\N
10	670	70	SABANALARGA	\N
11	670	70	SAN LUIS	\N
12	670	70	SEGOVIA	\N
13	670	70	ACHIOTE ARRIBA	\N
18	670	70	MATA DE CANA	\N
23	670	70	ACHIOTE ABAJO	\N
24	670	70	SANTA TERESA	\N
0	678	70	SAN BENITO ABAD	\N
2	678	70	CUIVA	\N
3	678	70	JEGUA	\N
4	678	70	LA CEIBA	\N
6	678	70	LOS ANGELES	\N
7	678	70	PUNTA DE BLANCO	\N
9	678	70	SAN ROQUE	\N
10	678	70	SANTIAGO APOSTOL	\N
11	678	70	DONA ANA	\N
12	678	70	GUAYABAL	\N
13	678	70	EL LIMON	\N
15	678	70	LA VENTURA	\N
19	678	70	HONDURAS	\N
20	678	70	PUNTA NUEVA	\N
25	678	70	CISPATACA	\N
26	678	70	SAN ISIDRO	\N
27	678	70	VILLA NUEVA	\N
28	678	70	CORRAL VIEJO - LOS ANGELES	\N
32	678	70	LA MOLINA	\N
33	678	70	LAS CHISPAS	\N
34	678	70	RANCHO LA TIA	\N
35	678	70	CALLE NUEVA	\N
36	678	70	EMPRESA COLOMBIA	\N
37	678	70	LA PLAZA	\N
38	678	70	LAS POZAS	\N
39	678	70	REMOLINO	\N
0	702	70	BETULIA	\N
1	702	70	ALBANIA	\N
2	702	70	HATO VIEJO	\N
4	702	70	SABANETA	\N
5	702	70	VILLA LOPEZ	\N
6	702	70	LAS CRUCES	\N
7	702	70	LOMA ALTA	\N
8	702	70	SANTO TOMAS	\N
9	702	70	EL SOCORRO	\N
10	702	70	GARRAPATERO	\N
11	702	70	LOMA DEL LATIGO	\N
0	708	70	SAN MARCOS	\N
1	708	70	BELEN	\N
2	708	70	BUENAVISTA	\N
3	708	70	CANDELARIA	\N
4	708	70	CANO PRIETO	\N
5	708	70	CUENCA	\N
6	708	70	EL LIMON	\N
7	708	70	EL TABLON	\N
9	708	70	LAS FLORES	\N
10	708	70	MONTEGRANDE	\N
11	708	70	PALO ALTO	\N
12	708	70	EL PITAL	\N
13	708	70	SANTA INES	\N
14	708	70	LA QUEBRADA	\N
15	708	70	EL LLANO	\N
17	708	70	NEIVA	\N
18	708	70	BUENOS AIRES	\N
20	708	70	CASTILLERA	\N
21	708	70	CAYO DE LA CRUZ	\N
22	708	70	CUATRO BOCAS	\N
24	708	70	EL OASIS	\N
25	708	70	LA COSTERA	\N
27	708	70	RINCON GUERRANO	\N
28	708	70	SAN FELIPE	\N
29	708	70	SEHEBE	\N
30	708	70	CAIMITICO	\N
31	708	70	CANO CARATE	\N
32	708	70	CEJA LARGA	\N
33	708	70	EL REPARO	\N
34	708	70	MEDIA TAPA	\N
35	708	70	NUEVA ESPERANZA	\N
36	708	70	PAJONAL	\N
0	713	70	SAN ONOFRE	\N
1	713	70	AGUACATE	\N
2	713	70	BERLIN	\N
3	713	70	BERRUGAS	\N
5	713	70	LABARCES	\N
6	713	70	LIBERTAD	\N
7	713	70	PAJONAL	\N
8	713	70	PALO ALTO	\N
9	713	70	PLANPAREJO	\N
10	713	70	RINCON DEL MAR	\N
11	713	70	SABANAS DE MUCACAL	\N
12	713	70	SAN ANTONIO	\N
14	713	70	HIGUERON	\N
15	713	70	EL CHICHO	\N
16	713	70	BARRANCAS	\N
17	713	70	CERRO DE LAS CASAS	\N
18	713	70	PAJONALITO	\N
19	713	70	EL PUEBLITO	\N
20	713	70	AGUAS NEGRAS	\N
21	713	70	PALACIOS	\N
22	713	70	BOCACERRADA	\N
23	713	70	PALMIRA	\N
24	713	70	ALTOS DE JULIO	\N
25	713	70	ARROYO SECO	\N
26	713	70	LAS BRISAS	\N
27	713	70	PISISI	\N
28	713	70	SABANAS DE RINCON	\N
29	713	70	SABANETICA	\N
30	713	70	BUENAVENTURA	\N
31	713	70	EL CAMPAMENTO	\N
32	713	70	EMBOCADA H PAJONAL	\N
0	717	70	SAN PEDRO	\N
1	717	70	SAN MATEO	\N
2	717	70	ROVIRA	\N
3	717	70	NUMANCIA	\N
4	717	70	EL BAJO DE LA ALEGRIA	\N
6	717	70	CALABOZO	\N
7	717	70	EL CARMEN	\N
8	717	70	LOS CHIJETES	\N
9	717	70	MANIZALES	\N
10	717	70	RANCHO LARGO	\N
11	717	70	SAN FRANCISCO	\N
0	742	70	SINCE	\N
2	742	70	BAZAN	\N
4	742	70	COCOROTE	\N
5	742	70	GRANADA	\N
8	742	70	LOS LIMONES	\N
10	742	70	VALENCIA	\N
11	742	70	VELEZ	\N
13	742	70	LA VIVIENDA	\N
14	742	70	PERENDENGUE	\N
15	742	70	GALAPAGO	\N
16	742	70	MORALITO	\N
17	742	70	PORVENIR	\N
0	771	70	SUCRE	\N
1	771	70	ARBOLEDA	\N
5	771	70	CAMPO ALEGRE	\N
6	771	70	CORDOBA	\N
8	771	70	EL CONGRESO	\N
12	771	70	LA VENTURA	\N
13	771	70	MONTERIA	\N
15	771	70	NARANJAL	\N
16	771	70	NARINO	\N
17	771	70	OREJERO	\N
18	771	70	SAN LUIS	\N
19	771	70	TRAVESIA	\N
20	771	70	HATO NUEVO	\N
21	771	70	PAMPANILLA	\N
24	771	70	LA PALMA	\N
27	771	70	SAN MATEO	\N
0	820	70	SANTIAGO DE TOLU	\N
3	820	70	NUEVA ERA	\N
7	820	70	PITA EN MEDIO	\N
8	820	70	PUERTO VIEJO	\N
10	820	70	PITA ABAJO	\N
13	820	70	SANTA LUCIA	\N
0	823	70	TOLUVIEJO	\N
1	823	70	CARACOL	\N
2	823	70	LAS PIEDRAS	\N
3	823	70	MACAJAN	\N
4	823	70	PALMIRA	\N
5	823	70	VARSOVIA	\N
6	823	70	LA PICHE	\N
7	823	70	CIENAGUITA	\N
9	823	70	MOQUEN	\N
10	823	70	GUALON	\N
11	823	70	CANITO	\N
12	823	70	LA SIRIA	\N
13	823	70	LA FLORESTA	\N
14	823	70	LOS ALTOS	\N
15	823	70	NUEVA ESPERANZA	\N
0	1	73	IBAGUE	\N
1	1	73	BUENOS AIRES	\N
4	1	73	DANTAS	\N
6	1	73	JUNTAS	\N
7	1	73	LAURELES	\N
9	1	73	SAN BERNARDO	\N
10	1	73	SAN JUAN DE LA CHINA	\N
11	1	73	TAPIAS	\N
12	1	73	TOCHE	\N
13	1	73	VILLARESTREPO	\N
14	1	73	LLANITOS	\N
15	1	73	EL TOTUMO	\N
16	1	73	LLANO DEL COMBEIMA	\N
17	1	73	CARMEN DE BULIRA	\N
18	1	73	EL RODEO	\N
20	1	73	COELLO - COCORA	\N
24	1	73	SANTA TERESA	\N
25	1	73	PASTALES VIEJO	\N
26	1	73	CHARCO RICO	\N
27	1	73	PASTALES NUEVO	\N
28	1	73	LA FLOR	\N
30	1	73	EL CAY	\N
32	1	73	ALTO DE GUALANDAY	\N
34	1	73	APARCO	\N
36	1	73	BRICENO	\N
38	1	73	CHEMBE	\N
39	1	73	CHUCUNI	\N
47	1	73	LA HELENA	\N
49	1	73	LA MIEL	\N
50	1	73	LA PALMILLA	\N
57	1	73	LOS TUNELES	\N
58	1	73	PICO DE ORO	\N
59	1	73	TRES ESQUINAS	\N
65	1	73	INVASION BELLA ISLA DE LLANITOS	\N
66	1	73	SALITRE	\N
0	24	73	ALPUJARRA	\N
1	24	73	LA ARADA	\N
2	24	73	EL CARMEN	\N
3	24	73	AMESES	\N
0	26	73	ALVARADO	\N
1	26	73	CALDAS VIEJO	\N
4	26	73	RINCON CHIPALO	\N
5	26	73	VERACRUZ	\N
8	26	73	LA TEBAIDA	\N
11	26	73	TOTARITO	\N
0	30	73	AMBALEMA	\N
2	30	73	CHORRILLO	\N
4	30	73	PAJONALES	\N
6	30	73	LA ALDEA EL DANUBIO	\N
7	30	73	BOQUERON	\N
0	43	73	ANZOATEGUI	\N
1	43	73	LISBOA	\N
2	43	73	PALOMAR	\N
3	43	73	SANTA BARBARA	\N
0	55	73	GUAYABAL	\N
2	55	73	MENDEZ	\N
3	55	73	SAN PEDRO	\N
4	55	73	SAN FELIPE	\N
6	55	73	FUNDADORES	\N
7	55	73	NUEVO HORIZONTE	\N
0	67	73	ATACO	\N
1	67	73	CAMPOHERMOSO	\N
3	67	73	CASA DE ZINC	\N
5	67	73	MESA DE POLE	\N
6	67	73	POLECITO	\N
7	67	73	SANTIAGO PEREZ	\N
12	67	73	MONTELORO	\N
14	67	73	EL PAUJIL	\N
16	67	73	CONDOR	\N
20	67	73	EL BALSO	\N
21	67	73	LA LAGUNA	\N
0	124	73	CAJAMARCA	\N
1	124	73	ANAIME	\N
5	124	73	EL ROSAL	\N
0	148	73	CARMEN DE APICALA	\N
0	152	73	CASABIANCA	\N
2	152	73	SAN JERONIMO	\N
0	168	73	CHAPARRAL	\N
4	168	73	EL LIMON	\N
5	168	73	LA MARINA	\N
6	168	73	LA PROFUNDA	\N
7	168	73	SAN JOSE DE LAS HERMOSAS	\N
0	200	73	COELLO	\N
1	200	73	GUALANDAY	\N
2	200	73	LA BARRIALOSA	\N
3	200	73	LLANO DE LA VIRGEN	\N
4	200	73	POTRERILLO	\N
5	200	73	VEGA LOS PADRES	\N
16	200	73	VINDI	\N
17	200	73	CALABOZO	\N
0	217	73	COYAIMA	\N
1	217	73	CASTILLA	\N
5	217	73	TOTARCO DINDE	\N
11	217	73	GUAYAQUIL	\N
12	217	73	MESA DE INCA	\N
13	217	73	SAN MIGUEL	\N
0	226	73	CUNDAY	\N
1	226	73	LA AURORA	\N
2	226	73	SAN PABLO	\N
3	226	73	TRES ESQUINAS	\N
4	226	73	VALENCIA	\N
5	226	73	VARSOVIA	\N
8	226	73	EL REVES	\N
0	236	73	DOLORES	\N
4	236	73	RIONEGRO	\N
5	236	73	SAN ANDRES	\N
7	236	73	LOS LLANITOS	\N
10	236	73	LA SOLEDAD	\N
11	236	73	SAN PEDRO	\N
0	268	73	EL ESPINAL	\N
1	268	73	CHICORAL	\N
3	268	73	SAN FRANCISCO	\N
0	270	73	FALAN	\N
1	270	73	FRIAS	\N
4	270	73	PIEDECUESTA	\N
0	275	73	FLANDES	\N
1	275	73	EL COLEGIO	\N
5	275	73	PARADERO I	\N
9	275	73	CONDOMINIO SANTA ANA Y PALMA REAL	\N
10	275	73	CONDOMINIO VILLA ESPERANZA	\N
0	283	73	FRESNO	\N
1	283	73	BETANIA	\N
3	283	73	EL TABLAZO	\N
4	283	73	LA AGUADITA	\N
8	283	73	SAN BERNARDO	\N
13	283	73	PARTIDAS	\N
0	319	73	GUAMO	\N
2	319	73	LA CHAMBA	\N
4	319	73	RINCON SANTO CENTRO	\N
5	319	73	CHIPUELO ORIENTE	\N
9	319	73	LA TROJA	\N
10	319	73	LOMA DE LUISA	\N
13	319	73	CANADA EL RODEO	\N
15	319	73	PUEBLO NUEVO	\N
16	319	73	CEREZUELA LAS GARZAS	\N
0	347	73	HERVEO	\N
1	347	73	BRASIL	\N
3	347	73	LETRAS	\N
4	347	73	MESONES	\N
5	347	73	PADUA	\N
0	349	73	HONDA	\N
1	349	73	PERICO	\N
0	352	73	ICONONZO	\N
1	352	73	BALCONCITOS	\N
2	352	73	BOQUERON	\N
3	352	73	MUNDO NUEVO	\N
5	352	73	PATECUINDE	\N
7	352	73	EL TRIUNFO	\N
0	408	73	LERIDA	\N
1	408	73	DELICIAS	\N
2	408	73	SAN FRANCISCO DE LA SIERRA	\N
3	408	73	PADILLA	\N
5	408	73	IGUASITOS	\N
0	411	73	LIBANO	\N
2	411	73	CONVENIO	\N
6	411	73	SAN FERNANDO	\N
7	411	73	SANTA TERESA	\N
8	411	73	TIERRADENTRO	\N
9	411	73	CAMPO ALEGRE	\N
0	443	73	SAN SEBASTIAN DE MARIQUITA	\N
1	443	73	EL HATILLO	\N
2	443	73	LA CABANA	\N
3	443	73	PITALITO	\N
4	443	73	LA ALBANIA	\N
6	443	73	CAMELIAS	\N
8	443	73	LA PARROQUIA	\N
9	443	73	LAS MARIAS	\N
0	449	73	MELGAR	\N
1	449	73	CUALAMANA	\N
4	449	73	AGUILA	\N
6	449	73	BALCONES DEL SUMAPAZ	\N
8	449	73	EL RUBY	\N
9	449	73	LA ESTANCIA	\N
12	449	73	QUEBRADITAS 1	\N
14	449	73	SAN JOSE DE LA COLORADA	\N
16	449	73	CUALAMANA 2	\N
17	449	73	EL PALMAR	\N
18	449	73	PEDRO GOMEZ	\N
0	461	73	MURILLO	\N
1	461	73	EL BOSQUE	\N
0	483	73	NATAGAIMA	\N
1	483	73	LA PALMITA	\N
2	483	73	VELU	\N
8	483	73	RINCON ANCHIQUE	\N
12	483	73	LAS BRISAS	\N
0	504	73	ORTEGA	\N
3	504	73	GUAIPA	\N
4	504	73	HATO DE IGLESIA	\N
7	504	73	LA MESA DE ORTEGA	\N
8	504	73	OLAYA HERRERA	\N
9	504	73	EL VERGEL	\N
17	504	73	LOS GUAYABOS	\N
0	520	73	PALOCABILDO	\N
1	520	73	ASTURIAS	\N
2	520	73	BUENOS AIRES	\N
3	520	73	GUADUALITO	\N
0	547	73	PIEDRAS	\N
1	547	73	CHICALA	\N
2	547	73	DOIMA	\N
3	547	73	GUATAQUISITO	\N
5	547	73	PARADERO CHIPALO	\N
0	555	73	PLANADAS	\N
1	555	73	BILBAO	\N
2	555	73	GAITANIA	\N
3	555	73	LA ESTRELLA	\N
4	555	73	SUR DE ATA	\N
5	555	73	RIO CLARO	\N
6	555	73	BRUSELAS	\N
7	555	73	SAN MIGUEL	\N
0	563	73	PRADO	\N
1	563	73	ACO	\N
4	563	73	MONTOSO	\N
0	585	73	PURIFICACION	\N
1	585	73	CHENCHE ASOLEADO	\N
2	585	73	LOZANIA	\N
6	585	73	VILLA ESPERANZA	\N
7	585	73	VILLA COLOMBIA	\N
9	585	73	EL BAURA	\N
13	585	73	LA MATA	\N
15	585	73	BUENAVISTA	\N
18	585	73	CHENCHE UNO	\N
0	616	73	RIOBLANCO	\N
1	616	73	HERRERA	\N
2	616	73	PUERTO SALDANA	\N
4	616	73	PALONEGRO	\N
5	616	73	GAITAN	\N
6	616	73	MARACAIBO	\N
9	616	73	JUNTAS	\N
0	622	73	RONCESVALLES	\N
1	622	73	SANTA ELENA	\N
2	622	73	EL CEDRO	\N
0	624	73	ROVIRA	\N
1	624	73	EL CORAZON	\N
3	624	73	LOS ANDES - LA BELLA	\N
4	624	73	RIOMANSO	\N
5	624	73	SAN PEDRO	\N
7	624	73	GUADUALITO	\N
8	624	73	LA FLORIDA	\N
10	624	73	LA SELVA	\N
12	624	73	LA LUISA	\N
0	671	73	SALDANA	\N
1	671	73	JABALCON	\N
2	671	73	SANTA INES	\N
8	671	73	LA ESPERANZA	\N
9	671	73	NORMANDIA	\N
0	675	73	SAN ANTONIO	\N
1	675	73	LA FLORIDA	\N
2	675	73	PLAYARRICA	\N
4	675	73	VILLA HERMOSA	\N
0	678	73	SAN LUIS	\N
4	678	73	PAYANDE	\N
0	686	73	SANTA ISABEL	\N
1	686	73	COLON	\N
3	686	73	SAN RAFAEL	\N
0	770	73	SUAREZ	\N
1	770	73	HATO VIEJO	\N
3	770	73	CANAVERALES	\N
5	770	73	AGUA BLANCA	\N
0	854	73	VALLE DE SAN JUAN	\N
0	861	73	VENADILLO	\N
1	861	73	JUNIN	\N
2	861	73	LA SIERRITA	\N
3	861	73	MALABAR	\N
4	861	73	PALMAROSA	\N
0	870	73	VILLAHERMOSA	\N
0	873	73	VILLARRICA	\N
2	873	73	LA COLONIA	\N
3	873	73	LOS ALPES	\N
4	873	73	PUERTO LLERAS	\N
0	1	76	SANTIAGO DE CALI, DISTRITO ESPECIAL, DEPORTIVO, CULTURAL, TURISTICO, EMPRESARIAL Y DE SERVICIOS	\N
1	1	76	EL SALADITO	\N
2	1	76	FELIDIA	\N
3	1	76	GOLONDRINAS	\N
4	1	76	EL HORMIGUERO	\N
5	1	76	LA BUITRERA	\N
6	1	76	LA CASTILLA	\N
7	1	76	LA ELVIRA	\N
8	1	76	LA LEONERA	\N
9	1	76	LA PAZ	\N
10	1	76	LOS ANDES	\N
12	1	76	NAVARRO	\N
13	1	76	PANCE	\N
14	1	76	PICHINDE	\N
16	1	76	MONTEBELLO	\N
19	1	76	CASCAJAL II	\N
20	1	76	VILLACARMELO	\N
22	1	76	BRISAS DE MONTEBELLO	\N
23	1	76	CAMPO ALEGRE	\N
24	1	76	CASCAJAL I	\N
25	1	76	CRUCERO ALTO DE LOS MANGOS	\N
26	1	76	EL FILO	\N
27	1	76	EL PORTENTO	\N
28	1	76	LA FRAGUA	\N
29	1	76	LA VORAGINE	\N
30	1	76	LAS PALMAS	\N
31	1	76	LOS CERROS	\N
32	1	76	MONTANITAS	\N
34	1	76	PIZAMOS	\N
35	1	76	PUEBLO NUEVO	\N
36	1	76	SAN FRANCISCO	\N
37	1	76	SAN ISIDRO	\N
38	1	76	VILLA FLAMENCO	\N
41	1	76	CASCAJAL III	\N
42	1	76	EL ESTERO	\N
43	1	76	LA LUISA	\N
44	1	76	LA SIRENA	\N
45	1	76	LAS PALMAS - LA CASTILLA	\N
46	1	76	SILOE	\N
48	1	76	LOS LIMONES	\N
50	1	76	CAUCA VIEJO	\N
51	1	76	CONDOMINIO MARANON	\N
52	1	76	CHORRO DE PLATA	\N
53	1	76	PARCELACION CANTACLARO 1	\N
54	1	76	PARCELACION CANTACLARO 2	\N
55	1	76	PARCELACION LA TRINIDAD	\N
56	1	76	PIAMONTE	\N
57	1	76	CALLEJON TABARES	\N
58	1	76	DUQUELANDIA	\N
59	1	76	LA COLINA	\N
60	1	76	LOS GIRASOLES	\N
61	1	76	PILAS DEL CABUYAL	\N
62	1	76	VILLA DEL ROSARIO	\N
0	20	76	ALCALA	\N
6	20	76	LA FLORESTA	\N
7	20	76	LA POLONIA	\N
0	36	76	ANDALUCIA	\N
2	36	76	CAMPOALEGRE	\N
3	36	76	EL SALTO	\N
6	36	76	TAMBORAL	\N
7	36	76	ZANJON DE PIEDRA	\N
9	36	76	MONTE HERMOSO	\N
10	36	76	MADRE VIEJA	\N
11	36	76	LA PAZ	\N
0	41	76	ANSERMANUEVO	\N
1	41	76	ANACARO	\N
3	41	76	EL BILLAR	\N
6	41	76	EL VERGEL	\N
13	41	76	GRAMALOTE	\N
21	41	76	SALAZAR	\N
0	54	76	ARGELIA	\N
4	54	76	EL RAIZAL	\N
5	54	76	LA AURORA	\N
0	100	76	BOLIVAR	\N
1	100	76	BETANIA	\N
2	100	76	CERRO AZUL	\N
6	100	76	LA HERRADURA	\N
7	100	76	LA TULIA	\N
8	100	76	NARANJAL	\N
9	100	76	PRIMAVERA	\N
10	100	76	RICAURTE	\N
12	100	76	AGUAS LINDAS	\N
14	100	76	SAN FERNANDO	\N
0	109	76	BUENAVENTURA, DISTRITO ESPECIAL, INDUSTRIAL, PORTUARIO, BIODIVERSO Y ECOTURISTICO	\N
1	109	76	AGUACLARA	\N
2	109	76	BARCO	\N
3	109	76	LA BOCANA	\N
6	109	76	BAJO CALIMA	\N
8	109	76	CISNEROS	\N
9	109	76	CORDOBA	\N
12	109	76	PITAL	\N
17	109	76	TRIANA	\N
18	109	76	CONCEPCION	\N
19	109	76	LA PLATA	\N
21	109	76	LADRILLEROS	\N
22	109	76	LLANO BAJO	\N
24	109	76	BOCAS DE MAYORQUIN	\N
28	109	76	PUERTO MERIZALDE	\N
30	109	76	PUNTA SOLDADO	\N
31	109	76	SAN ANTONIO (YURUMANGUI)	\N
32	109	76	SAN FRANCISCO DE NAYA	\N
33	109	76	SAN FRANCISCO JAVIER	\N
34	109	76	SAN ISIDRO	\N
36	109	76	SAN LORENZO	\N
37	109	76	SAN PEDRO	\N
38	109	76	SILVA	\N
39	109	76	TAPARAL	\N
40	109	76	VENERAL	\N
41	109	76	SAN JOSE	\N
42	109	76	SABALETAS	\N
43	109	76	ZACARIAS	\N
44	109	76	CABECERA RIO SAN JUAN	\N
45	109	76	LA BARRA	\N
46	109	76	JUANCHACO	\N
47	109	76	PIANGUITA	\N
52	109	76	CALLE LARGA	\N
53	109	76	CHAMUSCADO	\N
58	109	76	EL BARRANCO	\N
61	109	76	GUAIMIA	\N
62	109	76	JUNTAS	\N
64	109	76	BARTOLA	\N
65	109	76	LA BREA	\N
66	109	76	LA DELFINA	\N
69	109	76	PAPAYAL	\N
71	109	76	SAN CIPRIANO	\N
72	109	76	SAN JOAQUIN	\N
74	109	76	SAN JOSE DE NAYA	\N
76	109	76	SAN MARCOS	\N
77	109	76	SANTA CRUZ	\N
78	109	76	ZARAGOZA	\N
79	109	76	AGUAMANSA	\N
80	109	76	CASCAJITA	\N
81	109	76	PUNTA BONITA	\N
82	109	76	HORIZONTE	\N
83	109	76	BENDICIONES	\N
84	109	76	EL CACAO	\N
85	109	76	CALLE LARGA - AEROPUERTO	\N
86	109	76	CAMINO VIEJO - KM 40	\N
87	109	76	CAMPO HERMOSO	\N
88	109	76	EL CRUCERO	\N
89	109	76	EL ENCANTO	\N
90	109	76	EL LLANO	\N
91	109	76	EL SALTO	\N
92	109	76	GUADUALITO	\N
93	109	76	JOAQUINCITO RESGUARDO INDIGENA	\N
94	109	76	KATANGA	\N
95	109	76	LA BALASTRERA	\N
96	109	76	LA COMBA	\N
97	109	76	LA CONTRA	\N
98	109	76	LA FRAGUA	\N
99	109	76	PRIMAVERA	\N
100	109	76	LA VUELTA	\N
101	109	76	LAS PALMAS	\N
102	109	76	EL LIMONES	\N
104	109	76	PAPAYAL 2	\N
105	109	76	PITAL	\N
106	109	76	SAGRADA FAMILIA	\N
107	109	76	SAN ANTONIO	\N
108	109	76	SAN ANTONITO (YURUMANGUI)	\N
109	109	76	SAN ISIDRO (CAJAMBRE)	\N
110	109	76	SANTA MARIA	\N
111	109	76	SECADERO	\N
112	109	76	UMANE	\N
113	109	76	VILLA ESTELA	\N
114	109	76	CALLE LARGA - RIO MAYORQUIN	\N
115	109	76	ALTO ZARAGOZA	\N
116	109	76	BARRIO BUENOS AIRES	\N
117	109	76	BETANIA	\N
118	109	76	BRISAS	\N
119	109	76	EL CREDO	\N
120	109	76	EL EDEN	\N
121	109	76	EL PALITO	\N
122	109	76	JUAQUINCITO	\N
123	109	76	LA BOCANA (VISTA HERMOSA)	\N
124	109	76	LA CAUCANA	\N
125	109	76	LA LAGUNA	\N
126	109	76	PLAYA LARGA	\N
127	109	76	SAN ANTONIO 1	\N
128	109	76	SAN ANTONIO 2	\N
129	109	76	ZARAGOZA ALTO 1	\N
130	109	76	ZARAGOZA PUENTE SAN MARTIN 1	\N
131	109	76	ZARAGOZA PUENTE SAN MARTIN 2	\N
0	111	76	GUADALAJARA DE BUGA	\N
1	111	76	LA CAMPINA	\N
2	111	76	EL PLACER	\N
5	111	76	EL VINCULO	\N
6	111	76	LA HABANA	\N
7	111	76	LA MARIA	\N
12	111	76	QUEBRADASECA	\N
14	111	76	ZANJON HONDO	\N
16	111	76	EL PORVENIR	\N
18	111	76	PUEBLO NUEVO	\N
20	111	76	LA MAGDALENA	\N
21	111	76	EL MANANTIAL	\N
22	111	76	ALASKA	\N
24	111	76	LA PALOMERA	\N
25	111	76	LA UNIDAD	\N
26	111	76	PUERTO BERTIN	\N
27	111	76	SAN ANTONIO	\N
29	111	76	GUADUALEJO	\N
30	111	76	LA GRANJITA	\N
0	113	76	BUGALAGRANDE	\N
1	113	76	CEILAN	\N
4	113	76	EL OVERO (SECTOR POBLADO)	\N
8	113	76	GALICIA	\N
10	113	76	MESTIZAL	\N
11	113	76	PAILA ARRIBA	\N
13	113	76	URIBE URIBE	\N
16	113	76	EL OVERO (SECTOR LA MARIA)	\N
0	122	76	CAICEDONIA	\N
6	122	76	SAMARIA	\N
9	122	76	BARRAGAN	\N
10	122	76	LAS DELICIAS	\N
11	122	76	VILLA AURES	\N
0	126	76	DARIEN	\N
3	126	76	JIGUALES	\N
7	126	76	LA GAVIOTA	\N
13	126	76	PUENTE TIERRA	\N
18	126	76	LA PLAYA	\N
0	130	76	CANDELARIA	\N
1	130	76	BUCHITOLO	\N
2	130	76	EL ARENAL	\N
3	130	76	EL CABUYAL	\N
4	130	76	EL CARMELO	\N
5	130	76	EL LAURO	\N
6	130	76	EL TIPLE	\N
7	130	76	JUANCHITO	\N
8	130	76	VILLA GORGONA	\N
9	130	76	LA REGINA	\N
11	130	76	MADRE VIEJA	\N
12	130	76	SAN JOAQUIN	\N
14	130	76	EL OTONO	\N
15	130	76	EL GUALI	\N
16	130	76	EL POBLADO CAMPESTRE	\N
17	130	76	BRISAS DEL FRAILE	\N
18	130	76	CANTALOMOTA	\N
19	130	76	CAUCASECO	\N
20	130	76	DOMINGO LARGO	\N
21	130	76	LA ALBANIA	\N
22	130	76	LA GLORIA	\N
23	130	76	TRES TUSAS	\N
27	130	76	EL SILENCIO	\N
38	130	76	PATIO BONITO	\N
39	130	76	SAN ANDRESITO	\N
0	147	76	CARTAGO	\N
5	147	76	MODIN	\N
6	147	76	PIEDRA DE MOLER	\N
12	147	76	GUANABANO	\N
13	147	76	GUAYABITO	\N
14	147	76	ZANJON CAUCA	\N
0	233	76	DAGUA	\N
1	233	76	ATUNCELA	\N
2	233	76	BORRERO AYERBE	\N
4	233	76	CISNEROS	\N
5	233	76	EL CARMEN	\N
7	233	76	EL LIMONAR	\N
8	233	76	EL NARANJO	\N
9	233	76	EL PALMAR	\N
10	233	76	EL PINAL	\N
11	233	76	EL QUEREMAL	\N
12	233	76	EL SALADO	\N
17	233	76	LOBO GUERRERO	\N
19	233	76	SAN BERNARDO	\N
20	233	76	SAN VICENTE	\N
21	233	76	SANTA MARIA	\N
23	233	76	TOCOTA	\N
25	233	76	ZABALETAS	\N
27	233	76	JUNTAS	\N
33	233	76	PUEBLO NUEVO	\N
35	233	76	EL CHILCAL	\N
36	233	76	KILOMETRO 26	\N
39	233	76	LA VIRGEN	\N
41	233	76	LAS CAMELIAS	\N
44	233	76	VERGEL	\N
45	233	76	EL GALPON	\N
46	233	76	EL RODEO	\N
47	233	76	KATANGA	\N
48	233	76	EL CEDRO	\N
0	243	76	EL AGUILA	\N
2	243	76	LA ESPARTA	\N
5	243	76	LA MARIA - QUEBRADAGRANDE	\N
6	243	76	SAN JOSE	\N
8	243	76	CANAVERAL - VILLANUEVA	\N
30	243	76	LA QUIEBRA DE SAN PABLO	\N
31	243	76	EL GUAYABO	\N
0	246	76	EL CAIRO	\N
1	246	76	ALBAN	\N
0	248	76	EL CERRITO	\N
3	248	76	EL CASTILLO	\N
5	248	76	EL PLACER	\N
6	248	76	EL POMO	\N
8	248	76	SAN ANTONIO	\N
9	248	76	SANTA ELENA	\N
10	248	76	SANTA LUISA	\N
11	248	76	TENERIFE	\N
14	248	76	CAMPOALEGRE	\N
18	248	76	LA HONDA	\N
0	250	76	EL DOVIO	\N
3	250	76	BITACO	\N
5	250	76	LA CABANA	\N
7	250	76	LITUANIA	\N
12	250	76	PLAYA RICA	\N
13	250	76	LA PRADERA	\N
15	250	76	MATECANA	\N
0	275	76	FLORIDA	\N
4	275	76	CHOCOCITO	\N
6	275	76	LA DIANA	\N
10	275	76	REMOLINO	\N
11	275	76	SAN ANTONIO DE LOS CABALLEROS	\N
12	275	76	SAN FRANCISCO (EL LLANITO)	\N
14	275	76	TARRAGONA	\N
15	275	76	EL PEDREGAL	\N
19	275	76	LAS GUACAS	\N
21	275	76	LOS CALENOS	\N
24	275	76	EL INGENIO	\N
25	275	76	EL TAMBORAL	\N
28	275	76	SIMON BOLIVAR	\N
0	306	76	GINEBRA	\N
1	306	76	COSTA RICA	\N
2	306	76	LA FLORESTA	\N
5	306	76	SABALETAS	\N
8	306	76	VILLA VANEGAS	\N
0	318	76	GUACARI	\N
3	318	76	GUABAS	\N
4	318	76	GUABITAS	\N
6	318	76	PICHICHI	\N
7	318	76	SANTA ROSA DE TAPIAS	\N
8	318	76	SONSO	\N
9	318	76	ALTO DE GUACAS	\N
10	318	76	PUENTE ROJO	\N
11	318	76	CANANGUA	\N
12	318	76	EL PLACER	\N
13	318	76	EL TRIUNFO	\N
14	318	76	GUACAS	\N
0	364	76	JAMUNDI	\N
1	364	76	AMPUDIA	\N
2	364	76	BOCAS DEL PALO	\N
3	364	76	GUACHINTE	\N
4	364	76	LA LIBERIA	\N
5	364	76	PASO DE LA BOLSA	\N
6	364	76	POTRERITO	\N
8	364	76	PUENTE VELEZ	\N
9	364	76	QUINAMAYO	\N
10	364	76	ROBLES	\N
11	364	76	SAN ANTONIO	\N
12	364	76	SAN VICENTE	\N
13	364	76	TIMBA	\N
14	364	76	VILLA COLOMBIA	\N
15	364	76	VILLAPAZ	\N
16	364	76	LA ESTRELLA	\N
18	364	76	LA MESETA	\N
19	364	76	LA VENTURA	\N
21	364	76	SAN ISIDRO	\N
22	364	76	EL GUAVAL	\N
23	364	76	EL TRIUNFO	\N
24	364	76	CASCARILLAL	\N
26	364	76	GATO DE MONTE	\N
32	364	76	PUEBLO NUEVO	\N
35	364	76	LA CARCEL	\N
36	364	76	CONDOMINIO	\N
0	377	76	LA CUMBRE	\N
1	377	76	BITACO	\N
2	377	76	LA MARIA	\N
3	377	76	LOMITAS	\N
4	377	76	PAVAS	\N
5	377	76	PUENTE PALO	\N
8	377	76	ARBOLEDAS	\N
9	377	76	JIGUALES	\N
10	377	76	PAVITAS	\N
11	377	76	TRES ESQUINAS	\N
12	377	76	LA VENTURA	\N
0	400	76	LA UNION	\N
5	400	76	QUEBRADA GRANDE	\N
6	400	76	SAN LUIS	\N
8	400	76	SABANAZO	\N
9	400	76	CAMPO ALEGRE	\N
10	400	76	EL GUASIMO	\N
11	400	76	EL LUCERO	\N
12	400	76	LA CAMPESINA	\N
13	400	76	PAJARO DE ORO	\N
0	403	76	LA VICTORIA	\N
3	403	76	HOLGUIN	\N
4	403	76	MIRAVALLES	\N
5	403	76	RIVERALTA	\N
6	403	76	SAN JOSE	\N
7	403	76	SAN PEDRO	\N
9	403	76	TAGUALES	\N
0	497	76	OBANDO	\N
1	497	76	CRUCES	\N
2	497	76	EL CHUZO	\N
3	497	76	JUAN DIAZ	\N
5	497	76	PUERTO MOLINA	\N
6	497	76	PUERTO SAMARIA	\N
7	497	76	SAN ISIDRO	\N
8	497	76	VILLA RODAS	\N
0	520	76	PALMIRA	\N
1	520	76	AGUACLARA	\N
2	520	76	AMAIME	\N
4	520	76	BARRANCAS	\N
5	520	76	BOLO ALIZAL	\N
6	520	76	BOLO LA ITALIA	\N
7	520	76	BOLO SAN ISIDRO	\N
8	520	76	BOYACA	\N
9	520	76	CALUCE - PLAN DE VIVIENDA LOS GUAYABOS	\N
10	520	76	CAUCASECO	\N
11	520	76	COMBIA	\N
13	520	76	CHONTADURO	\N
14	520	76	GUANABANAL	\N
15	520	76	GUAYABAL	\N
16	520	76	JUANCHITO	\N
17	520	76	LA ACEQUIA	\N
18	520	76	LA HERRADURA	\N
19	520	76	LA QUISQUINA	\N
20	520	76	LA TORRE	\N
22	520	76	MATAPALO	\N
23	520	76	OBANDO	\N
24	520	76	PALMASECA	\N
25	520	76	POTRERILLO	\N
26	520	76	ROZO	\N
27	520	76	TABLONES	\N
28	520	76	TENJO	\N
29	520	76	TIENDA NUEVA	\N
32	520	76	LA BUITRERA	\N
33	520	76	LA PAMPA	\N
35	520	76	LA BOLSA	\N
38	520	76	LA DOLORES	\N
39	520	76	LA CASCADA	\N
41	520	76	BOLO BARRIO NUEVO	\N
43	520	76	BOLOMADRE VIEJA	\N
44	520	76	LA UNION	\N
45	520	76	PILES	\N
47	520	76	SAN ANTONIO DE LAS PALMAS	\N
48	520	76	TRES TUSAS	\N
49	520	76	BOLO ITALIA 1	\N
50	520	76	BOLO ITALIA 2	\N
51	520	76	CONDOMINIO CAMPESTRE LA GONZALEZ	\N
52	520	76	LA BUITRERA 1	\N
53	520	76	PUEBLO NUEVO	\N
0	563	76	PRADERA	\N
11	563	76	LA GRANJA	\N
13	563	76	LA TUPIA	\N
14	563	76	LOMITAS	\N
18	563	76	POTRERITO	\N
19	563	76	SAN ISIDRO	\N
24	563	76	EL RECREO	\N
25	563	76	LA FERIA	\N
28	563	76	LA CRUZ	\N
0	606	76	RESTREPO	\N
8	606	76	SAN SALVADOR	\N
16	606	76	BARRIO LA INDEPENDENCIA	\N
0	616	76	RIOFRIO	\N
2	616	76	FENICIA	\N
3	616	76	PALMA - LA CUCHILLA	\N
5	616	76	LA ZULIA	\N
6	616	76	MADRIGAL	\N
7	616	76	PORTUGAL DE PIEDRAS	\N
9	616	76	SALONICA	\N
10	616	76	EL JAGUAL	\N
13	616	76	PUERTO FENICIA	\N
14	616	76	LOS ALPES	\N
15	616	76	LA SULTANA	\N
16	616	76	LAS BRISAS	\N
17	616	76	LOS ESTRECHOS	\N
0	622	76	ROLDANILLO	\N
1	622	76	CAJAMARCA	\N
2	622	76	EL RETIRO	\N
3	622	76	HIGUERONCITO	\N
7	622	76	MORELIA	\N
9	622	76	SANTA RITA	\N
20	622	76	PALMAR GUAYABAL	\N
22	622	76	TIERRA BLANCA	\N
0	670	76	SAN PEDRO	\N
2	670	76	BUENOS AIRES	\N
7	670	76	PRESIDENTE	\N
8	670	76	SAN JOSE	\N
9	670	76	TODOS LOS SANTOS	\N
10	670	76	GUAYABAL	\N
11	670	76	MONTE GRANDE	\N
0	736	76	SEVILLA	\N
4	736	76	COROZAL	\N
5	736	76	CUMBARCO	\N
8	736	76	LA CUCHILLA	\N
14	736	76	SAN ANTONIO	\N
18	736	76	QUEBRADANUEVA	\N
30	736	76	BUENOS AIRES	\N
0	823	76	TORO	\N
1	823	76	BOHIO	\N
3	823	76	LA PRADERA	\N
6	823	76	SAN ANTONIO	\N
7	823	76	SAN FRANCISCO	\N
8	823	76	LA QUIEBRA	\N
0	828	76	TRUJILLO	\N
2	828	76	ANDINAPOLIS	\N
3	828	76	CRISTALES	\N
4	828	76	DOS QUEBRADAS	\N
6	828	76	HUASANO	\N
7	828	76	ROBLEDO	\N
8	828	76	SAN ISIDRO	\N
10	828	76	VENECIA	\N
13	828	76	LA SONORA	\N
0	834	76	TULUA	\N
1	834	76	AGUACLARA	\N
3	834	76	BARRAGAN	\N
4	834	76	BOCAS DE TULUA	\N
5	834	76	EL PICACHO	\N
7	834	76	PUERTO FRAZADAS	\N
9	834	76	LA IBERIA	\N
10	834	76	LA MARINA	\N
11	834	76	LA MORALIA	\N
12	834	76	LA PALMERA	\N
15	834	76	MONTELORO	\N
16	834	76	NARINO	\N
21	834	76	SANTA LUCIA	\N
23	834	76	TRES ESQUINAS	\N
25	834	76	CAMPOALEGRE	\N
26	834	76	LA RIVERA	\N
29	834	76	CIENEGUETA	\N
30	834	76	GATO NEGRO	\N
32	834	76	PALOMESTIZO	\N
33	834	76	LA COLINA	\N
0	845	76	ULLOA	\N
2	845	76	MOCTEZUMA	\N
5	845	76	DINAMARCA	\N
0	863	76	VERSALLES	\N
1	863	76	CAMPOALEGRE	\N
2	863	76	EL BALSAL	\N
7	863	76	LA FLORIDA	\N
12	863	76	PUERTO NUEVO	\N
14	863	76	MURRAPAL	\N
17	863	76	LA PLAYA	\N
19	863	76	LA CABANA	\N
0	869	76	VIJES	\N
1	869	76	CACHIMBAL	\N
3	869	76	EL PORVENIR	\N
4	869	76	LA FRESNEDA	\N
7	869	76	LA RIVERA	\N
8	869	76	EL TAMBOR	\N
10	869	76	VIDAL	\N
0	890	76	YOTOCO	\N
1	890	76	EL CANEY	\N
3	890	76	JIGUALES	\N
4	890	76	RAYITO	\N
5	890	76	LAS DELICIAS	\N
6	890	76	MEDIACANOA	\N
7	890	76	MIRAVALLE	\N
8	890	76	PUENTETIERRA	\N
9	890	76	SAN ANTONIO DE PIEDRAS	\N
11	890	76	CAMPOALEGRE	\N
13	890	76	LOS PLANES	\N
14	890	76	PUNTA BRAVA	\N
0	892	76	YUMBO	\N
2	892	76	DAPA LA VEGA	\N
4	892	76	MONTANITAS	\N
5	892	76	MULALO	\N
8	892	76	SAN MARCOS	\N
9	892	76	SANTA INES	\N
13	892	76	DAPA EL RINCON	\N
14	892	76	EL PEDREGAL	\N
15	892	76	MIRAVALLE NORTE	\N
17	892	76	ARROYOHONDO	\N
18	892	76	EL CHOCHO	\N
19	892	76	MANGA VIEJA	\N
20	892	76	MIRAVALLE DAPA	\N
21	892	76	PILAS DE DAPA	\N
0	895	76	ZARZAL	\N
2	895	76	LA PAILA	\N
3	895	76	LIMONES	\N
4	895	76	QUEBRADANUEVA	\N
5	895	76	VALLEJUELO	\N
8	895	76	ESTACION CAICEDONIA	\N
0	1	81	ARAUCA	\N
1	1	81	CLARINETERO	\N
17	1	81	EL CARACOL	\N
20	1	81	MONSERRATE	\N
21	1	81	LAS NUBES	\N
22	1	81	MANHATAN	\N
0	65	81	ARAUQUITA	\N
1	65	81	CARRETERO	\N
2	65	81	EL TRONCAL	\N
3	65	81	LOS ANGELITOS	\N
4	65	81	SAN LORENZO	\N
5	65	81	LA PAZ	\N
7	65	81	LA REINERA (GAVIOTA)	\N
8	65	81	LA ESMERALDA (JUJUA)	\N
9	65	81	AGUACHICA	\N
10	65	81	EL CAUCHO	\N
16	65	81	LOS CHORROS	\N
17	65	81	PANAMA DE ARAUCA	\N
19	65	81	BRISAS DEL CARANAL	\N
20	65	81	EL OASIS	\N
21	65	81	EL TRIUNFO	\N
22	65	81	LA PESQUERA	\N
23	65	81	EL CAMPAMENTO	\N
25	65	81	ARENOSA	\N
26	65	81	BOCAS DEL ELE	\N
27	65	81	CANO HONDO	\N
28	65	81	EL CAMPING	\N
29	65	81	FILIPINAS	\N
30	65	81	JARDINES	\N
31	65	81	LOS COLONOS	\N
32	65	81	MATECANA	\N
33	65	81	PUEBLO NUEVO	\N
34	65	81	SITIO NUEVO	\N
0	220	81	CRAVO NORTE	\N
0	300	81	FORTUL	\N
2	300	81	CARANAL	\N
3	300	81	LA VEINTE	\N
4	300	81	MATECANA	\N
6	300	81	EL MORDISCO	\N
7	300	81	PALMARITO	\N
9	300	81	SITIO NUEVO	\N
11	300	81	TOLUA	\N
0	591	81	PUERTO RONDON	\N
6	591	81	SAN IGNACIO	\N
0	736	81	SARAVENA	\N
2	736	81	LA YE DEL CHARO	\N
5	736	81	PUENTE DE BOJABA	\N
6	736	81	PUERTO LLERAS	\N
7	736	81	AGUA SANTA	\N
8	736	81	PUERTO NARINO	\N
9	736	81	BARRANCONES	\N
10	736	81	BARRIO LOCO	\N
11	736	81	CANO SECO	\N
12	736	81	LA PAJUILA	\N
13	736	81	LA YE DEL GARROTAZO	\N
14	736	81	PUERTO CONTRERAS	\N
16	736	81	QUESQUALITO	\N
17	736	81	REMOLINO	\N
19	736	81	TINAJAS	\N
0	794	81	TAME	\N
1	794	81	BETOYES	\N
4	794	81	COROCITO	\N
6	794	81	PUERTO GAITAN	\N
9	794	81	PUERTO SAN SALVADOR	\N
14	794	81	LA HOLANDA	\N
15	794	81	PUENTE TABLA	\N
19	794	81	BOTALON	\N
20	794	81	PUERTO MIRANDA	\N
21	794	81	ALTO CAUCA	\N
22	794	81	FLOR AMARILLO	\N
23	794	81	LA ARENOSA	\N
24	794	81	LAS MALVINAS	\N
26	794	81	PUEBLO SECO	\N
27	794	81	SANTO DOMINGO	\N
28	794	81	PUEBLO SUCIO	\N
0	1	85	YOPAL	\N
1	1	85	EL MORRO	\N
2	1	85	LA CHAPARRERA	\N
3	1	85	TILODIRAN	\N
5	1	85	EL CHARTE	\N
6	1	85	SANTAFE DE MORICHAL	\N
7	1	85	QUEBRADA SECA	\N
10	1	85	LA GUAFILLA	\N
11	1	85	LA LLANERITA	\N
12	1	85	LA NIATA	\N
13	1	85	PUNTO NUEVO	\N
0	10	85	AGUAZUL	\N
1	10	85	CUPIAGUA	\N
2	10	85	MONTERRALO	\N
3	10	85	SAN BENITO	\N
5	10	85	SAN JOSE	\N
6	10	85	UNETE	\N
10	10	85	PUENTE CUSIANA	\N
11	10	85	TURUA	\N
14	10	85	LLANO LINDO	\N
15	10	85	PLAN BRISAS	\N
0	15	85	CHAMEZA	\N
0	125	85	HATO COROZAL	\N
1	125	85	CORRALITO	\N
2	125	85	CHIRE	\N
3	125	85	LA FRONTERA - LA CHAPA	\N
4	125	85	MANARE	\N
5	125	85	PUERTO COLOMBIA	\N
10	125	85	SANTA RITA	\N
11	125	85	SAN JOSE DE ARIPORO	\N
12	125	85	SANTA BARBARA	\N
14	125	85	EL GUAFAL	\N
15	125	85	LAS CAMELIAS	\N
16	125	85	ROSA BLANCA	\N
17	125	85	PUEBLO NUEVO	\N
0	136	85	LA SALINA	\N
0	139	85	MANI	\N
1	139	85	GUAFALPINTADO	\N
3	139	85	GAVIOTAS	\N
5	139	85	SANTA HELENA DE CUSIVA	\N
6	139	85	SAN JOAQUIN DE GARIBAY	\N
7	139	85	CHAVINAVE	\N
0	162	85	MONTERREY	\N
1	162	85	PALONEGRO	\N
2	162	85	BRISAS DE LLANO	\N
4	162	85	EL PORVENIR	\N
6	162	85	VILLA CAROLA	\N
7	162	85	LA HORQUETA	\N
8	162	85	LA ESTRELLA	\N
0	225	85	NUNCHIA	\N
16	225	85	LA YOPALOSA	\N
0	230	85	OROCUE	\N
3	230	85	EL ALGARROBO	\N
11	230	85	CARRIZALES	\N
12	230	85	DUYA (RESGUARDO)	\N
0	250	85	PAZ DE ARIPORO	\N
1	250	85	BOCAS DE LA HERMOSA	\N
2	250	85	CENTRO GAITAN	\N
3	250	85	CANO CHIQUITO	\N
4	250	85	LA AGUADA	\N
6	250	85	MONTANA DEL TOTUMO	\N
7	250	85	LAS GUAMAS	\N
8	250	85	RINCON HONDO	\N
0	263	85	PORE	\N
1	263	85	EL BANCO	\N
2	263	85	LA PLATA	\N
0	279	85	RECETOR	\N
2	279	85	PUEBLO NUEVO	\N
0	300	85	SABANALARGA	\N
1	300	85	AGUACLARA	\N
3	300	85	EL SECRETO	\N
0	315	85	SACAMA	\N
0	325	85	SAN LUIS DE PALENQUE	\N
2	325	85	MIRAMAR DE GUANAPALO	\N
4	325	85	EL PALMAR DE GUANAPALO	\N
5	325	85	JAGUEYES	\N
0	400	85	TAMARA	\N
2	400	85	TABLON DE TAMARA	\N
4	400	85	TEISLANDIA	\N
0	410	85	TAURAMENA	\N
3	410	85	CARUPANA	\N
4	410	85	TUNUPE	\N
5	410	85	PASO CUSIANA	\N
6	410	85	RAIZAL	\N
0	430	85	TRINIDAD	\N
1	430	85	BOCAS DEL PAUTO	\N
2	430	85	GUAMAL	\N
7	430	85	EL CONVENTO	\N
8	430	85	SANTA IRENE	\N
0	440	85	VILLANUEVA	\N
1	440	85	CARIBAYONA	\N
2	440	85	SANTA HELENA DE UPIA	\N
3	440	85	SAN AGUSTIN	\N
0	1	86	MOCOA	\N
2	1	86	EL PEPINO	\N
3	1	86	PUEBLO VIEJO	\N
4	1	86	PUERTO LIMON	\N
6	1	86	SAN ANTONIO	\N
9	1	86	YUNGUILLO	\N
14	1	86	LA TEBAIDA	\N
16	1	86	ALTO AFAN	\N
17	1	86	BRISAS DEL SOL	\N
18	1	86	PLANADAS	\N
19	1	86	RUMIYACO	\N
20	1	86	SAN ANTONIO 2	\N
0	219	86	COLON	\N
1	219	86	SAN PEDRO	\N
2	219	86	LAS PALMAS	\N
3	219	86	MICHUACAN	\N
0	320	86	ORITO	\N
1	320	86	TESALIA	\N
4	320	86	LUCITANIA	\N
8	320	86	BUENOS AIRES	\N
9	320	86	SAN VICENTE DE LUZON	\N
10	320	86	SIBERIA	\N
11	320	86	SIMON BOLIVAR	\N
13	320	86	EL ACHIOTE	\N
14	320	86	EL LIBANO	\N
15	320	86	EL PARAISO	\N
16	320	86	EL YARUMO	\N
17	320	86	MONSERRATE	\N
0	568	86	PUERTO ASIS	\N
19	568	86	SANTANA	\N
20	568	86	PUERTO VEGA	\N
23	568	86	CANA BRAVA	\N
26	568	86	LA CARMELITA	\N
27	568	86	LA LIBERTAD	\N
29	568	86	CAMPO ALEGRE	\N
31	568	86	SINAI (ACHAPOS)	\N
32	568	86	BRISAS DEL HONG KONG	\N
33	568	86	LA CABANA	\N
34	568	86	PLANADAS	\N
0	569	86	PUERTO CAICEDO	\N
1	569	86	SAN PEDRO	\N
2	569	86	VILLA FLOR	\N
3	569	86	EL CEDRAL	\N
4	569	86	EL VENADO	\N
0	571	86	PUERTO GUZMAN	\N
1	571	86	EL CEDRO	\N
2	571	86	SANTA LUCIA	\N
3	571	86	JOSE MARIA	\N
4	571	86	MAYOYOGUE	\N
5	571	86	EL GALLINAZO	\N
6	571	86	SAN ROQUE	\N
7	571	86	EL JUANO	\N
8	571	86	PUERTO ROSARIO	\N
9	571	86	GALILEA	\N
10	571	86	EL RECREO	\N
11	571	86	EL BOMBON	\N
12	571	86	EL MUELLE	\N
13	571	86	LA PATRIA	\N
0	573	86	PUERTO LEGUIZAMO	\N
1	573	86	LA TAGUA	\N
2	573	86	PUERTO OSPINA	\N
3	573	86	SENSELLA	\N
5	573	86	EL MECAYA	\N
8	573	86	LA VICTORIA	\N
9	573	86	PINUNA NEGRO	\N
10	573	86	NUEVA APAYA	\N
11	573	86	PUERTO NARINO	\N
0	749	86	SIBUNDOY	\N
8	749	86	SAGRADO CORAZON DE JESUS	\N
9	749	86	LAS COCHAS	\N
10	749	86	VILLA FLOR	\N
0	755	86	SAN FRANCISCO	\N
1	755	86	SAN ANTONIO	\N
3	755	86	SAN SILVESTRE	\N
4	755	86	MINCHOY	\N
0	757	86	LA DORADA	\N
1	757	86	PUERTO COLON	\N
4	757	86	AGUA BLANCA	\N
13	757	86	EL CHIGUACO	\N
15	757	86	EL MAIZAL	\N
18	757	86	JORDAN ORTIZ	\N
25	757	86	LA GUISITA	\N
28	757	86	MESAS DEL SABALITO	\N
32	757	86	NUEVA RISARALDA	\N
43	757	86	SAN LUIS DE LA FRONTERA	\N
49	757	86	EL PARAISO	\N
50	757	86	LA INVASION	\N
51	757	86	LOS UVOS	\N
0	760	86	SANTIAGO	\N
1	760	86	SAN ANDRES	\N
0	865	86	LA HORMIGA	\N
3	865	86	EL TIGRE	\N
4	865	86	EL PLACER	\N
5	865	86	SAN ANTONIO	\N
8	865	86	JORDAN DE GUISIA	\N
10	865	86	BRISAS DEL PALMAR	\N
11	865	86	EL CAIRO	\N
12	865	86	EL VENADO	\N
14	865	86	LA CONCORDIA	\N
18	865	86	NUEVA PALESTINA	\N
21	865	86	VILLADUARTE	\N
22	865	86	LA ISLA	\N
0	885	86	VILLAGARZON	\N
1	885	86	PUERTO UMBRIA	\N
2	885	86	LA CASTELLANA	\N
4	885	86	ALBANIA	\N
7	885	86	KOFANIA	\N
8	885	86	NARANJITO	\N
9	885	86	PORVENIR	\N
11	885	86	SANTA ROSA DE JUANAMBU	\N
13	885	86	CANANGUCHO	\N
14	885	86	RIO BLANCO	\N
0	1	88	SAN ANDRES	\N
1	1	88	LA LOMA	\N
2	1	88	SAN LUIS	\N
5	1	88	PUNTA SUR	\N
0	564	88	SANTA ISABEL	\N
1	564	88	FRESH WATER BAY	\N
2	564	88	SOUTH WEST BAY	\N
3	564	88	BOTTON HOUSE	\N
4	564	88	SAN FELIPE	\N
5	564	88	ROCKY POINT	\N
6	564	88	SANTA CATALINA	\N
0	1	91	LETICIA	\N
1	1	91	COMUNIDAD INDIGENA SANTA SOFIA	\N
2	1	91	COMUNIDAD INDIGENA NAZARETH	\N
5	1	91	COMUNIDAD INDIGENA TIKUNA DE ARARA	\N
7	1	91	COMUNIDAD INDIGENA SAN MARTIN DE AMACAYACU	\N
9	1	91	COMUNIDAD INDIGENA ZARAGOZA	\N
11	1	91	COMUNIDAD INDIGENA EL PROGRESO	\N
12	1	91	COMUNIDAD INDIGENA EL VERGEL	\N
13	1	91	COMUNIDAD INDIGENA PATIO DE CIENCIA DULCE  KM 11	\N
15	1	91	COMUNIDAD INDIGENA LA LIBERTAD	\N
16	1	91	COMUNIDAD INDIGENA LA MILAGROSA	\N
17	1	91	COMUNIDAD INDIGENA SECTOR LA PLAYA	\N
18	1	91	COMUNIDAD INDIGENA MALOKA YAGUAS	\N
19	1	91	COMUNIDAD INDIGENA LOMA LINDA	\N
20	1	91	COMUNIDAD INDIGENA MACEDONIA	\N
21	1	91	COMUNIDAD INDIGENA MOCAGUA	\N
22	1	91	COMUNIDAD INDIGENA JUSSY MONILLA AMENA	\N
23	1	91	ASENTAMIENTO HUMANO TAKANA  KM 11	\N
24	1	91	COMUNIDAD INDIGENA NUEVO JARDIN	\N
25	1	91	COMUNIDAD INDIGENA PALMERAS	\N
26	1	91	COMUNIDAD INDIGENA PUERTO TRIUNFO	\N
27	1	91	COMUNIDAD INDIGENA ISLA DE RONDA	\N
28	1	91	COMUNIDAD INDIGENA SAN ANTONIO DE LOS LAGOS	\N
29	1	91	COMUNIDAD INDIGENA ZIERA AMENA	\N
30	1	91	BARRIO SAN MIGUEL	\N
31	1	91	COMUNIDAD INDIGENA CANAAN	\N
32	1	91	COMUNIDAD INDIGENA PICHUNA KM 18	\N
33	1	91	COMUNIDAD INDIGENA SAN JOSE DEL RIO	\N
34	1	91	COMUNIDAD INDIGENA SAN JUAN DE LOS PARENTES	\N
35	1	91	COMUNIDAD INDIGENA SAN PEDRO DE LOS LAGOS	\N
0	263	91	EL ENCANTO	\N
0	405	91	LA CHORRERA	\N
0	407	91	LA PEDRERA	\N
0	430	91	PACOA	\N
0	460	91	MIRITI	\N
0	530	91	PUERTO ALEGRIA	\N
0	536	91	PUERTO ARICA	\N
0	540	91	PUERTO NARINO	\N
1	540	91	SAN JUAN DE ATACUARI	\N
2	540	91	BOYAHUAZU	\N
3	540	91	DOCE DE OCTUBRE	\N
4	540	91	NARANJALES	\N
5	540	91	PUERTO ESPERANZA	\N
6	540	91	PUERTO RICO	\N
7	540	91	SAN FRANCISCO	\N
8	540	91	SAN JUAN DEL SOCO	\N
9	540	91	SIETE DE AGOSTO	\N
10	540	91	SAN PEDRO DE TIPISCA	\N
11	540	91	VEINTE DE JULIO	\N
12	540	91	NUEVO PARAISO	\N
13	540	91	PATRULLEROS	\N
14	540	91	SAN JOSE DE VILLA ANDREA	\N
15	540	91	SANTA TERESITA	\N
16	540	91	SANTAREN	\N
17	540	91	VALENCIA	\N
0	669	91	PUERTO SANTANDER	\N
0	798	91	TARAPACA	\N
0	1	94	INIRIDA	\N
3	1	94	COCO VIEJO	\N
9	1	94	RESGUARDO CACAHUAL RIO ATABAPO	\N
10	1	94	COCO NUEVO	\N
11	1	94	INSPECCION BARRANCO TIGRE	\N
12	1	94	COAYARE	\N
13	1	94	YURI	\N
14	1	94	SANTA ROSA	\N
0	343	94	BARRANCOMINAS	\N
1	343	94	MAPIRIPANA	\N
2	343	94	ARRECIFAL	\N
4	343	94	MINITAS	\N
5	343	94	PUERTO ZANCUDO	\N
0	883	94	SAN FELIPE	\N
0	884	94	PUERTO COLOMBIA	\N
1	884	94	SEJAL (MAHIMACHI)	\N
0	885	94	GALILEA	\N
0	886	94	CACAHUAL	\N
3	886	94	MEREY	\N
4	886	94	SAN JUAN	\N
0	887	94	CAMPO ALEGRE	\N
1	887	94	BOCAS DE YARI	\N
2	887	94	VENADO ISANA	\N
0	888	94	MORICHAL - GARZA	\N
0	1	95	SAN JOSE DEL GUAVIARE	\N
1	1	95	RAUDAL DEL GUAYABERO	\N
2	1	95	SABANAS DE LA FUGA	\N
6	1	95	GUACAMAYAS	\N
9	1	95	PUERTO NUEVO	\N
10	1	95	PUERTO ARTURO	\N
11	1	95	PUERTO OSPINA	\N
12	1	95	CACHICAMO	\N
16	1	95	EL CAPRICHO	\N
17	1	95	CHARRAS	\N
18	1	95	CARACOL	\N
19	1	95	TOMACHIPAN	\N
20	1	95	MOCUARE	\N
23	1	95	LA CARPA	\N
24	1	95	BOQUERON	\N
27	1	95	LAS ACACIAS	\N
29	1	95	RESBALON	\N
30	1	95	CANO BLANCO II	\N
31	1	95	CERRO AZUL	\N
32	1	95	EL DIAMANTE	\N
34	1	95	EL REFUGIO	\N
35	1	95	EL TRIUNFO	\N
36	1	95	LA ESMERALDA	\N
37	1	95	PICALOJO	\N
39	1	95	SANTO DOMINGO	\N
41	1	95	TIENDA NUEVA	\N
42	1	95	EL MORRO	\N
43	1	95	VILLA ALEJANDRA	\N
44	1	95	VILLA ALEJANDRA 2	\N
45	1	95	MIRALINDO	\N
46	1	95	LA CATALINA	\N
0	15	95	CALAMAR	\N
2	15	95	LA UNION	\N
3	15	95	LAS DAMAS	\N
0	25	95	EL RETORNO	\N
1	25	95	LA LIBERTAD	\N
2	25	95	EL UNILLA	\N
3	25	95	CERRITOS	\N
4	25	95	MORICHAL VIEJO	\N
5	25	95	SAN LUCAS	\N
6	25	95	LA FORTALEZA	\N
7	25	95	MIROLINDO	\N
8	25	95	LA CRISTALINA	\N
9	25	95	LA NUEVA PRIMAVERA	\N
10	25	95	LA PAZ	\N
11	25	95	PUEBLO NUEVO	\N
0	200	95	MIRAFLORES	\N
1	200	95	BARRANQUILLITA	\N
2	200	95	LAGOS DEL DORADO	\N
3	200	95	LAS PAVAS CANO TIGRE	\N
4	200	95	BUENOS AIRES	\N
5	200	95	LA YE	\N
6	200	95	LAGOS DEL PASO	\N
9	200	95	PUERTO NARE	\N
10	200	95	PUERTO SANTANDER	\N
14	200	95	LA HACIENDA	\N
15	200	95	PUERTO CORDOBA	\N
16	200	95	PUERTO MANDU	\N
0	1	97	MITU	\N
1	1	97	BOCAS DE QUERARI	\N
2	1	97	CAMANAOS	\N
3	1	97	MACUANA	\N
4	1	97	TRINIDAD DEL TIQUIE	\N
5	1	97	ACARICUARA	\N
6	1	97	VILLAFATIMA	\N
7	1	97	MANDI	\N
8	1	97	PIRAMIRI	\N
9	1	97	YAPU	\N
10	1	97	YURUPARI	\N
0	161	97	CARURU	\N
0	511	97	PACOA	\N
0	666	97	TARAIRA	\N
1	666	97	COMUNIDAD DE CURUPIRA	\N
0	777	97	PUERTO SOLANO (PAPUNAHUA)	\N
0	889	97	YAVARATE	\N
1	889	97	PAPURI	\N
0	1	99	PUERTO CARRENO	\N
1	1	99	LA VENTUROSA	\N
2	1	99	CASUARITO	\N
3	1	99	PUERTO MURILLO	\N
4	1	99	ACEITICO	\N
5	1	99	GARCITAS	\N
6	1	99	GUARIPA	\N
7	1	99	MORICHADA	\N
0	524	99	LA PRIMAVERA	\N
1	524	99	NUEVA ANTIOQUIA	\N
2	524	99	SANTA BARBARA	\N
7	524	99	SAN TEODORO (LA PASCUA)	\N
0	624	99	SANTA ROSALIA	\N
1	624	99	GUACACIAS	\N
0	773	99	CUMARIBO	\N
1	773	99	PALMARITO	\N
2	773	99	EL VIENTO	\N
3	773	99	TRES MATAS	\N
4	773	99	AMANAVEN	\N
5	773	99	CHUPAVE	\N
8	773	99	GUANAPE	\N
10	773	99	PUERTO PRINCIPE	\N
13	773	99	PUERTO NARINO	\N
15	773	99	SANTA RITA	\N
17	773	99	CHAPARRAL	\N
20	773	99	EL PROGRESO	\N
21	773	99	EL TUPARRO	\N
24	773	99	BRISA	\N
25	773	99	GUATURIBA	\N
26	773	99	MATSULDANI	\N
27	773	99	REMANSO	\N
\.


--
-- Data for Name: choices; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.choices (list_name, list_value, list_label, list_generadora, project_id, id, list_order, description, image, generic) FROM stdin;
\.


--
-- Data for Name: choices_table; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.choices_table (id, name_table, key_table, value_table, name_description) FROM stdin;
\.


--
-- Data for Name: date_seleccionpredios; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.date_seleccionpredios (barmanpre, chip, expediente) FROM stdin;
\.


--
-- Data for Name: departamento; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.departamento (dpto_codi, dpto_nomb, id_cont, id_pais, depto_acapella) FROM stdin;
0	NO EXISTE	1	170	\N
1	TODOS	1	170	\N
5	ANTIOQUIA	1	170	\N
8	ATLANTICO	1	170	\N
11	BOGOTA, D. C.	1	170	\N
13	BOLIVAR	1	170	\N
15	BOYACA	1	170	\N
17	CALDAS	1	170	\N
18	CAQUETA	1	170	\N
19	CAUCA	1	170	\N
20	CESAR	1	170	\N
23	CORDOBA	1	170	\N
25	CUNDINAMARCA	1	170	\N
27	CHOCO	1	170	\N
41	HUILA	1	170	\N
44	LA GUAJIRA	1	170	\N
47	MAGDALENA	1	170	\N
50	META	1	170	\N
52	NARINO	1	170	\N
54	NORTE DE SANTANDER	1	170	\N
63	QUINDIO	1	170	\N
66	RISARALDA	1	170	\N
68	SANTANDER	1	170	\N
70	SUCRE	1	170	\N
73	TOLIMA	1	170	\N
76	VALLE DEL CAUCA	1	170	\N
81	ARAUCA	1	170	\N
85	CASANARE	1	170	\N
86	PUTUMAYO	1	170	\N
88	ARCHIPIELAGO DE SAN ANDRES, PROVIDENCIA Y SANTA CATALINA	1	170	\N
91	AMAZONAS	1	170	\N
94	GUAINIA	1	170	\N
95	GUAVIARE	1	170	\N
97	VAUPES	1	170	\N
99	VICHADA	1	170	\N
\.


--
-- Data for Name: dependencia; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.dependencia (id, depe_codi, depe_nomb, dpto_codi, depe_codi_padre, muni_codi, depe_codi_territorial, dep_sigla, dep_central, dep_direccion, depe_num_interna, depe_num_resolucion, depe_rad_tp1, depe_rad_tp2, depe_rad_tp3, acto_admon, id_cont, id_pais, depe_estado, depe_rad_tp4, depe_rad_tp5, depe_rad_tp9, depe_rad_tp7, depe_rad_tp8, depe_rad_tp6) FROM stdin;
17	900	Adminstracion Sistema	11	900	1	100	pb	\N	CARR 16 No 96-64 P.7	900	\N	900	900	900		1	170	1	900	900	900	900	900	900
18	999	ARCHIVO VIRTUAL	11	900	1	100	ARCHIVO	\N	CARR 16 No 96-64 P.7	\N	\N	100	\N	100		1	170	1	\N	\N	\N	\N	\N	\N
\.


--
-- Data for Name: dependencia_visibilidad; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.dependencia_visibilidad (codigo_visibilidad, dependencia_visible, dependencia_observa) FROM stdin;
637	905	905
2626	200	100
2627	200	101
2628	200	102
2629	200	110
2630	200	120
2631	200	130
2632	200	140
2633	200	170
2634	200	200
2635	200	201
2636	200	202
2637	200	300
2638	200	301
2639	200	320
2640	200	500
2641	200	600
2642	200	900
2643	200	999
2690	232	100
2691	232	101
2692	232	102
2693	232	110
2694	232	120
2695	232	130
2696	232	140
2697	232	170
2698	232	200
2699	232	201
2700	232	202
2701	232	300
2702	232	301
2703	232	320
2704	232	500
2705	232	600
2706	232	900
2707	232	999
2734	997	900
1254	301	100
1255	301	110
1256	301	120
1257	301	130
1258	301	200
1259	301	300
1260	301	301
1261	301	500
1262	301	600
1263	301	900
1264	301	999
566	460	100
567	460	110
568	460	120
569	460	130
570	460	200
571	460	300
572	460	400
573	460	410
574	460	420
575	460	430
576	460	440
577	460	450
578	460	460
579	460	500
580	460	600
581	460	605
582	460	900
583	460	905
584	460	999
2524	120	100
2525	120	110
2526	120	120
2527	120	130
2528	120	200
2529	120	300
2530	120	400
2531	120	500
2532	120	600
2533	120	900
2534	120	999
337	450	100
338	450	110
339	450	120
340	450	130
341	450	200
342	450	300
343	450	400
344	450	410
345	450	420
346	450	430
347	450	440
348	450	450
349	450	460
350	450	500
351	450	600
352	450	605
353	450	900
354	450	905
355	450	999
1293	320	100
1294	320	101
1295	320	102
1296	320	110
1297	320	120
1298	320	130
1299	320	140
1300	320	170
1301	320	200
1302	320	201
1303	320	202
1304	320	211
1305	320	212
1306	320	213
1307	320	214
1308	320	221
1309	320	222
1310	320	231
1311	320	232
1312	320	233
1313	320	234
1314	320	300
1315	320	301
1316	320	320
1317	320	500
1318	320	600
1713	500	100
1714	500	101
1715	500	102
1716	500	110
1717	500	120
1718	500	130
1719	500	140
1720	500	170
1721	500	200
1722	500	201
1723	500	202
1724	500	211
1725	500	212
1726	500	213
1727	500	214
1728	500	221
1729	500	222
450	605	100
451	605	110
452	605	120
453	605	130
454	605	200
455	605	300
456	605	400
457	605	410
458	605	420
459	605	430
460	605	440
461	605	450
1730	500	223
1731	500	231
1732	500	232
1733	500	233
1734	500	300
1735	500	301
1736	500	320
1737	500	500
1738	500	600
462	605	460
463	605	500
464	605	600
465	605	605
466	605	900
467	605	905
468	605	999
1275	234	100
1276	234	101
1277	234	102
1278	234	110
1279	234	120
1280	234	130
1281	234	140
1282	234	170
1283	234	200
1284	234	201
1285	234	202
1286	234	300
1287	234	301
1288	234	320
1289	234	500
1290	234	600
1291	234	900
1292	234	999
1739	500	900
1740	500	999
1793	102	100
528	430	100
529	430	110
530	430	120
531	430	130
532	430	200
533	430	300
534	430	400
535	430	410
536	430	420
537	430	430
538	430	440
539	430	450
540	430	460
541	430	500
542	430	600
543	430	605
544	430	900
545	430	905
546	430	999
547	440	100
548	440	110
549	440	120
550	440	130
551	440	200
552	440	300
553	440	400
554	440	410
555	440	420
556	440	430
557	440	440
558	440	450
559	440	460
560	440	500
561	440	600
562	440	605
563	440	900
564	440	905
565	440	999
1794	102	101
1795	102	102
1796	102	110
1797	102	120
1798	102	130
1799	102	140
1800	102	170
1801	102	200
1802	102	201
1803	102	202
1804	102	211
1805	102	212
1806	102	213
683	411	100
684	411	110
685	411	120
686	411	130
687	411	200
688	411	300
689	411	400
690	411	410
691	411	900
692	411	905
693	411	999
694	410	100
695	410	110
696	410	120
697	410	130
698	410	200
699	410	300
700	410	400
701	410	410
702	410	900
703	410	905
704	410	999
705	412	100
706	412	110
707	412	120
708	412	130
709	412	200
710	412	300
711	412	400
712	412	410
713	412	900
714	412	905
715	412	999
717	430	300
718	440	100
719	440	110
720	440	120
721	440	130
722	440	200
723	440	300
724	440	400
725	440	410
726	440	900
727	440	905
728	440	999
729	450	440
730	460	100
731	460	110
732	460	120
733	460	130
734	460	200
735	460	300
736	460	400
737	460	410
738	460	420
739	460	430
740	460	440
741	460	450
742	460	900
743	460	905
744	460	999
745	470	100
746	470	110
747	470	120
748	470	130
749	470	200
750	470	300
751	470	400
752	470	410
753	470	420
754	470	430
755	470	440
756	470	450
757	470	460
758	470	900
759	470	905
760	470	999
1807	102	214
1808	102	221
1809	102	222
1810	102	223
1811	102	231
1812	102	232
1813	102	233
1814	102	300
1815	102	301
1816	102	320
1817	102	500
1818	102	600
2537	202	100
2538	202	101
2539	202	102
2540	202	110
2541	202	120
2542	202	130
2543	202	140
2544	202	170
2545	202	200
829	590	100
830	590	110
831	590	120
832	590	130
833	590	200
834	590	300
835	590	400
836	590	410
837	590	420
838	590	430
839	590	440
840	590	450
841	590	460
842	590	500
843	590	900
844	590	905
845	590	999
846	700	410
847	700	411
848	700	412
849	510	100
850	510	110
851	510	120
852	510	130
853	510	200
854	510	300
855	510	400
856	510	410
857	510	420
858	510	430
859	510	440
860	510	450
861	510	460
862	510	500
863	510	900
864	510	905
865	510	999
2546	202	201
2547	202	202
2548	202	300
2549	202	301
2550	202	320
2551	202	500
2552	202	600
2575	222	100
2576	222	101
2577	222	102
2578	222	110
2579	222	120
2306	173	100
2307	173	101
2308	173	102
2309	173	110
2310	173	120
2311	173	130
903	420	100
904	420	400
905	420	411
906	420	420
907	420	700
908	420	999
2580	222	130
2312	173	140
2581	222	140
2582	222	170
2583	222	200
2584	222	201
2585	222	202
2586	222	211
2587	222	212
2588	222	213
2589	222	214
2590	222	221
2591	222	300
2592	222	301
2593	222	320
927	600	100
928	600	101
929	600	102
930	600	110
931	600	120
932	600	130
933	600	140
934	600	170
935	600	200
936	600	201
937	600	202
938	600	300
939	600	301
940	600	320
941	600	500
942	600	600
943	600	900
944	600	999
945	130	100
946	130	101
947	130	102
948	130	110
949	130	120
950	130	130
951	130	140
952	130	170
953	130	200
954	130	201
955	130	202
956	130	300
957	130	301
958	130	320
959	130	500
960	130	600
961	130	900
962	130	999
981	201	100
982	201	101
983	201	102
984	201	110
985	201	120
986	201	130
987	201	140
988	201	170
989	201	200
990	201	201
991	201	202
992	201	300
993	201	301
994	201	320
995	201	500
996	201	600
997	201	900
1541	213	100
1542	213	101
1543	213	102
1544	213	110
1545	213	120
1546	213	130
1547	213	140
1548	213	170
1549	213	200
1550	213	201
1551	213	202
1552	213	211
1553	213	212
1554	213	300
1555	213	301
1556	213	320
1557	213	500
1558	213	600
1559	213	900
1741	140	100
1742	140	101
1743	140	102
1744	140	110
1745	140	120
1746	140	130
1747	140	140
1748	140	170
1749	140	200
1750	140	201
1751	140	202
1752	140	211
1753	140	212
1754	140	213
1755	140	214
1756	140	221
1757	140	222
1758	140	223
1759	140	231
1760	140	232
1560	213	999
2594	222	500
2595	222	600
2596	222	900
2597	222	999
1761	140	233
1762	140	300
1763	140	301
1764	140	320
1765	140	500
1766	140	600
1819	110	100
1820	110	101
1821	110	102
1822	110	110
1823	110	120
1824	110	130
1825	110	140
1826	110	170
1827	110	200
1828	110	201
1829	110	202
1830	110	211
1831	110	212
1832	110	213
1833	110	214
1834	110	221
1835	110	222
1836	110	223
1837	110	231
1838	110	232
1839	110	233
1840	110	300
1841	110	301
1842	110	320
1843	110	500
1844	110	600
2313	173	170
2314	173	171
2315	173	172
2316	173	200
2317	173	201
2318	173	202
2319	173	211
2320	173	212
2321	173	213
2322	173	214
2323	173	221
2324	173	222
2325	173	223
2326	173	231
2327	173	232
2328	173	233
2329	173	300
2330	173	301
2331	173	320
2332	173	500
2333	173	600
1522	212	100
1523	212	101
1524	212	102
1525	212	110
1526	212	120
1527	212	130
1528	212	140
1529	212	170
1530	212	200
1531	212	201
1532	212	202
1533	212	211
1534	212	300
1535	212	301
1536	212	320
1537	212	500
1538	212	600
1539	212	900
1540	212	999
2644	221	100
2645	221	101
2646	221	102
2647	221	110
2648	221	120
2649	221	130
2650	221	140
2651	221	170
2652	221	200
2653	221	201
2654	221	202
2655	221	211
2656	221	212
2657	221	213
2658	221	214
2659	221	300
2660	221	301
2661	221	320
2662	221	500
2663	221	600
1767	101	100
1768	101	101
1769	101	102
1770	101	110
1771	101	120
1772	101	130
1773	101	140
1774	101	170
1775	101	200
1776	101	201
1777	101	202
1778	101	211
1779	101	212
1780	101	213
1781	101	214
1782	101	221
1783	101	222
1784	101	223
2664	221	900
2665	221	999
1785	101	231
1786	101	232
1787	101	233
1788	101	300
1789	101	301
1790	101	320
1791	101	500
1792	101	600
2735	900	100
2736	900	101
2737	900	102
2738	900	110
2739	900	120
2740	900	130
2741	900	140
2742	900	170
2743	900	172
2744	900	173
2745	900	200
2746	900	201
2747	900	202
2748	900	211
2749	900	212
2750	900	213
2751	900	214
2752	900	215
2753	900	221
2754	900	222
2755	900	223
2756	900	231
1903	300	100
1904	300	101
1905	300	102
1906	300	110
1907	300	120
1908	300	130
1909	300	140
1910	300	170
1911	300	200
1912	300	201
1913	300	202
1914	300	211
1915	300	212
1916	300	213
1917	300	214
1918	300	221
1919	300	222
1920	300	223
1921	300	231
1922	300	232
1923	300	233
1924	300	300
1925	300	301
1926	300	320
1927	300	500
1928	300	600
2757	900	232
2758	900	233
2759	900	300
2760	900	301
2598	223	100
2599	223	101
2600	223	102
2601	223	110
2602	223	120
2603	223	130
2604	223	140
2605	223	170
2606	223	200
2607	223	201
2608	223	202
2609	223	211
2610	223	212
2611	223	213
2612	223	214
2613	223	221
2614	223	222
2615	223	223
2616	223	231
2617	223	232
2618	223	233
2619	223	300
2620	223	301
2621	223	320
2622	223	500
2623	223	600
2761	900	320
2762	900	400
2763	900	500
2764	900	600
2765	900	900
2766	900	999
2503	214	100
2504	214	101
2505	214	102
2506	214	110
2507	214	120
2508	214	130
2509	214	140
2510	214	170
2511	214	200
2512	214	201
2513	214	202
2514	214	211
2515	214	212
2516	214	213
2517	214	300
2518	214	301
2519	214	320
2520	214	500
2521	214	600
2522	214	900
2523	214	999
2624	215	232
2666	231	100
2667	231	101
2668	231	102
2669	231	110
2670	231	120
2671	231	130
2672	231	140
2673	231	170
2674	231	200
2675	231	201
2676	231	202
2677	231	211
2678	231	212
2679	231	213
2680	231	214
2681	231	221
2682	231	222
2683	231	300
2684	231	301
2685	231	320
2686	231	500
2687	231	600
2688	231	900
2689	231	999
2708	233	100
2709	233	101
2710	233	102
2711	233	110
2712	233	120
2713	233	130
2714	233	140
2715	233	170
2280	171	100
2281	171	101
2282	171	102
2283	171	110
2284	171	120
2285	171	130
2286	171	140
2287	171	170
2288	171	200
2289	171	201
2290	171	202
2291	171	211
2292	171	212
2293	171	213
2294	171	214
2295	171	221
2296	171	222
2297	171	223
2298	171	231
2299	171	232
2300	171	233
2301	171	300
2302	171	301
2303	171	320
2304	171	500
2305	171	600
2334	400	100
2335	400	101
2336	400	102
2337	400	110
2338	400	120
2339	400	130
2340	400	140
2341	400	170
2342	400	171
2343	400	172
2344	400	173
2345	400	200
2346	400	201
2347	400	202
2348	400	211
2349	400	212
2350	400	213
2351	400	214
2352	400	221
2353	400	222
2354	400	223
2355	400	231
2356	400	232
2357	400	233
2358	400	300
2359	400	301
2360	400	320
2361	400	500
2362	400	600
2716	233	200
2717	233	201
2718	233	202
2719	233	211
2720	233	212
2721	233	213
2722	233	214
2723	233	221
2724	233	222
2389	172	100
2390	172	101
2391	172	102
2392	172	110
2393	172	120
2394	172	130
2395	172	140
2396	172	170
2397	172	171
2398	172	200
2399	172	201
2400	172	202
2401	172	211
2402	172	212
2403	172	213
2404	172	214
2405	172	221
2406	172	222
2407	172	223
2408	172	231
2409	172	232
2410	172	233
2411	172	300
2412	172	301
2413	172	320
2414	172	500
2415	172	600
2416	211	100
2417	211	101
2418	211	102
2419	211	110
2420	211	120
2421	211	130
2422	211	140
2423	211	170
2424	211	200
2425	211	201
2426	211	202
2427	211	300
2428	211	301
2429	211	320
2430	211	500
2431	211	600
2432	211	900
2433	211	999
2725	233	223
2726	233	231
2727	233	232
2728	233	233
2729	233	300
2730	233	301
2731	233	320
2732	233	500
2733	233	600
2767	100	100
2768	100	900
2769	100	999
2770	101	100
2771	101	900
2772	101	999
\.


--
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.estado (esta_codi, esta_desc) FROM stdin;
9	Documento de Orfeo
\.


--
-- Data for Name: field_type; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.field_type (id_field_type, cod_field_type, desc_field_type) FROM stdin;
1	note	\N
2	text	\N
3	select_one	\N
4	select_multiple	\N
5	geopoint	\N
6	begin group	\N
7	end group	\N
8	get phone number	\N
9	get subscriber id	\N
10	get end time	\N
11	get today	\N
12	get sim id	\N
13	get start time	\N
14	get device id	\N
15	integer	\N
16	decimal	\N
17	image	\N
18	textarea	\N
19	aveas	\N
20	file	\N
\.


--
-- Data for Name: fields; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.fields (idpk, poll_id, poll_date, poll_version, us_id, field_id, field_description, field_questiontype, field_type, field_null, field_default, field_answersoption, field_nextid, field_save, id, field_description2, fileld_date2, field_date2, field_appearence, field_constraint, field_relevant, field_constraint_message, field_choicefilter, field_choicename, project_id, field_view_web, field_order, field_menu, type_plot, field_lime_survey, choice_type_id, field_objecttype, field_export, estado, field_condition_view, field_nested_condition, is_required, is_numeric, field_limit, is_searchable, field_menu_samples, asociar, field_view_map, field_pattern) FROM stdin;
\.


--
-- Data for Name: frmf_frmfields; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.frmf_frmfields (frmf_code, frm_code, frmf_name, frmf_description, frmf_tablesave, frmf_field, frmf_null, frmf_pk, frmf_colspan, frmf_column, frmf_step, frmf_order, frmf_mask, frmf_sql, frmt_code, frmf_help, frmf_label, frmf_fieldpk, frmf_tablepksearch, frmf_fieldpksearch, frmf_fieldpksave, frmf_tablepksave, id, frmf_params, frmf_rowspan, frmf_default, frmf_vars, frmf_varsparam, frmf_table) FROM stdin;
50	5	TablaParametro	Tabla del parametro - Solo para tipo Select	frmf_frmfields	frmf_tablepksearch	0	0	\N	0	\N	11	\N	\N	1	Tabla en la cual se busca el parametro a seleccionar	\N	\N	\N	\N	\N	\N	19	\N	0	\N	\N	\N	\N
55	5	OrdenCampo	Orden del campo	frmf_frmfields	frmf_order	1	0	\N	3	\N	3	\N	\N	7	Numero de Orden en las Filas, si hay saltos no se tienen en cuenta.	Orden Fila	\N	\N	\N	\N	\N	24	0->0->*||1||2||3||4||5||6||7||8||9||10||11||12||13||14||15	0	\N	\N	\N	\N
61	5	Descripcion	Descripcion del campo	frmf_frmfields	frmf_description	1	0	3	2	\N	1	\N	\N	1	\N	\N	\N	\N	\N	\N	\N	30	\N	0	\N	\N	\N	\N
57	5	Tabla	Tabla Destino	frmf_frmfields	frmf_tablesave	1	0	\N	1	\N	3	\N	\N	1	\N	Tabla de la Bd	\N	\N	\N	\N	\N	26	\N	2	\N	\N	\N	\N
74	5	CampoBusqueda	Campo sobre el cual se aplica el criterio de Busqueda	frmf_frmfields	frmf_fieldpk	0	0	1	1	\N	11	\N	\N	1	Campo sobre el que se aplica la Busqueda	Nombre de Campo de la BD a Buscar	\N	\N	\N	\N	\N	45	\N	0	\N	\N	\N	\N
49	5	Campo	Campo destino	frmf_frmfields	frmf_field	1	0	\N	2	\N	3	\N	\N	1	Campo Destino en el cual se grabara por defecto en la Base de Datos.	Campo  BD 	\N	\N	\N	\N	\N	18	\N	0	\N	\N	\N	\N
48	5	TipoDato	Tipo de dato	frmf_frmfields	frmt_code	1	0	0	2	\N	4	\N	select frmt_code||' '||frmt_name, frmt_code FROM frmt_fieldtype order by frmt_code	8	Escoja el tipo de dato	Tipo de Dato	\N	\N	\N	\N	\N	17	\N	0	\N	\N	\N	\N
8	1	FechaCP	Fecha de compra del Predio en ....	f_dataex	f_datep	0	0	\N	3	4	3	\N	\N	4	Fecha en la cual compro el predio.  Es la misma que apacrece en la FIcha predial....	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	\N	\N	\N
46	5	Sql	Consulta SQL	frmf_frmfields	frmf_sql	0	0	5	4	\N	12	\N	\N	3	Consulta SQL estandar. <BR>Ej.<BR> SELECT USUA_NOMB, USUA_CODI FROM USUARIOS	Consulta SQL	\N	\N	\N	\N	\N	15	\N	0	\N	\N	\N	\N
10	1	MedioRec	Medio de Recepcion	f_dataex	f_mr	0	0	\N	\N	\N	9	\N	select mrec_desc, mrec_codi from medio_recepcion	8	Escoja el Medio de recepcion	Medio de Recepcion de Los documentos	\N	\N	\N	\N	\N	3	select mrec_desc, mrec_codi from medio_recepcion	\N	\N	\N	\N	\N
47	5	Mascara	Mascara del campo	frmf_frmfields	frmf_mask	0	0	1	1	\N	8	\N	\N	1	Mascara del campo<br>Ej. 9 indica numeros y X Letras<br>999.999,99<BR>(XX)(XX)(XXXXX)	\N	\N	\N	\N	\N	\N	16	\N	0	\N	\N	\N	\N
59	5	Label	Label 	frmf_frmfields	frmf_label	0	0	1	5	\N	4	\N	\N	1	\N	Label	\N	\N	\N	\N	\N	28	\N	0	\N	\N	\N	\N
52	5	codigo	Formulario	frmf_frmfields	frm_code	1	1	\N	0	\N	1	\N	select frm_code||' '||frm_name,frm_code FROM frm_forms	8	Codigo de campo	Codigo de Formulario	\N	\N	\N	\N	\N	21	select frm_code||' '||frm_name,frm_code FROM frm_forms	0	\N	\N	\N	\N
63	5	Configuracion	Parametros de configuracion de Consulta de datos en tablas alternas	\N	\N	0	0	5	\N	\N	10	\N	\N	5	\N	PARAMETROS DE BUSQUEDA DE DATOS EN TABLAS ALTERNAS<br>Debe seleccionar una tabla y el campo sobe el cual se realizara la Busqueda.			\N	\N	\N	32	\N	0	\N	\N	\N	\N
56	5	Ayuda	Ayuda del campo a crear	frmf_frmfields	frmf_help	0	0	5	1	\N	14	\N	\N	3	Escriba un texto Ayuda del campo a crear	\N	\N	\N	\N	\N	\N	25	\N	0	\N	\N	\N	\N
58	5	CampoTablaParametro	Campo a buscar en la tabla seleccionada	frmf_frmfields	frmf_fieldpksearch	0	0	3	2	\N	11	\N	\N	1	Campo a buscar en la tabla seleccionada en Tabla parametro.<br><br>\r\nSe usa cuando quiero colocar un criterio que depende de un Objeto que contiene este mismo formulario.<br>\r\n<br><br>Ejemplo. <br>\r\n    frm_code->codigo\r\n<br> En el cual frm_code es el nombre de campo de la base de datos<br>\r\ny codigo es el nombre exacto del objeto a buscar.	\N	\N	\N	\N	\N	\N	27	\N	0	\N	\N	\N	\N
51	5	Null	Define si el campo es nulo	frmf_frmfields	frmf_null	1	0	2	3	\N	8	\N	\N	7	\N	Valor Nulo ?	\N	\N	\N	\N	\N	20	\N	0	\N	\N	\N	\N
9	1	Genero	Genero	f_dataex	f_genero	1	0	0	0	\N	8		MASCULINO->0||FEMENINO->1->*||NS/NR->2	7	Escoja  el genero.	Genero				\N	\N	2	MASCULINO->0||FEMENINO->1->*||NS/NR->2	0	\N	0	genero	\N
53	5	pk	Llave primaria - si -no	frmf_frmfields	frmf_pk	1	0	\N	5	\N	8	\N	\N	7	Escriba si el campo es llave principal	Campo Llave (Pk)	\N	\N	\N	\N	\N	22	Si->1||No->0->*	0	\N	\N	\N	\N
11	1	Propietarios	Predio de ...	f_dataex	f_propietarios	0	1	\N	\N	\N	10	\N	\N	1	Escoja el chip a agregar	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	\N	\N	\N
14	2	TipoRadicado	Escoja el TIpo de Radicado	dat_predios_proyectos	tiporad	0	0	\N	\N	\N	5	\N	select sgd_trad_descr,sgd_trad_codigo from sgd_trad_tiporad	8	\N	\N	\N	\N	\N	\N	\N	5	select sgd_trad_descr,sgd_trad_codigo from sgd_trad_tiporad	\N	\N	\N	\N	\N
15	2	usuario	Persona que Aprueba	dat_predios_proyectos	usuario	0	0	\N	\N	\N	6	\N	USUA_NOMB->usuario||USUA_CODI->usua_codi||USUA_EMAIL->Observacion||USUA_DOC->Chip	9	\N	\N	usua_nomb	usuario	usua_nomb	\N	\N	6	USUA_NOMB->usuario||USUA_CODI->usua_codi||USUA_EMAIL->Observacion||USUA_DOC->Chip	\N	\N	\N	\N	\N
13	2	Observacion	Conmentarios adicionales...	dat_predios_proyectos	observa	0	0	\N	\N	\N	2	\N	\N	3	xxxxxx	\N	\N	\N	\N	\N	\N	7	\N	\N	\N	\N	\N	\N
12	2	FechaSel	Fecha de Seleccion	dat_predios_proyectos	fechasel	1	0	\N	\N	\N	3	\N	\N	4	\N	\N	\N	\N	\N	\N	\N	8	\N	\N	\N	\N	\N	\N
3	1	Persona	Estrato Economico	f_dataex	f_estrato	0	0	\N	3	\N	2	\N	\N	1	asdfasdfasdf	\N	\N	\N	\N	\N	\N	9	\N	\N	\N	\N	\N	\N
6	1	Telefono	Telefono de l personaje	f_dataex	f_telefono	0	0	\N	1	\N	3	(XXX) XXX-XXXX	\N	1	Seleccione el Telefono	\N	\N	\N	\N	\N	\N	10	\N	\N	\N	\N	\N	\N
17	3	Codigo	Codigo del Formulario	frm_forms	frm_code	1	1	\N	\N	\N	0	\N	\N	2	\N	\N	\N	\N	\N	\N	\N	12	\N	\N	\N	\N	\N	\N
13	2	Estrato	Estrato economico		estrato	1	0	0		\N	2		BAJO->1||2||3||4||5_Alto->5->*	0	Seleccionar el Estrato economico de la persona.	Estrato Social/E	\N			\N	\N	13	BAJO->1||2||3||4||5_Alto->5->*	\N	\N	\N	\N	\N
2	2	Direccion	Direccion de Residencia de la Presona		f_adress	1	0	0	2	2	2	9999999	SI||NO||NS/NR	3	asdfasdfasdf		\N			\N	\N	14	SI||NO||NS/NR	\N	\N	\N	\N	\N
7	1	Matricula	Chip del PRedio		f_matricula	1	1	1	2	\N	3	999.999		2			\N			\N	\N	11		\N	\N	\N	\N	\N
18	3	Descripcion	Descripcion del Formulario	frm_forms	frm_description	0	0	2	\N	\N	2	\N	\N	1	\N	\N	\N	\N	\N	\N	\N	33	\N	\N	\N	\N	\N	\N
72	104	VR TOTAL TERRENO	valor total del terreno		VR_TOTAL_TERRENO	1	0	0	3	\N	4	$		1	valor total del terreno en millones					\N	\N	43		\N	\N	\N	\N	\N
83	104	HONORARIOS_VENTAS	honorarios ventas	data_estructuracostos	honorarios_ventas	1	0	0	1	\N	13			1		HONORARIOS VENTAS				\N	\N	54		\N	\N	\N	\N	\N
54	5	Colspan	ColSpan. Columnas que Ocupa	frmf_frmfields	frmf_colspan	0	0	\N	4	\N	4	\N	\N	7	\N	Columnas a Combinar			\N	\N	\N	23	0->0->*||1||2||3||4||5||6||7||8||9||10||11||12	0	\N	\N	\N	\N
88	104	DERE_CONEX_SER_PUB	derechos  conexion servicios pub	data_estructuracostos	DERE_CONEX_SER_PUB	1	0	0	1	\N	18			1	derechos de conexión servicios públicos	DERECHOS CONEXIÓN SERVICIOS PUB				\N	\N	59		\N	\N	\N	\N	\N
95	104	ASESORIA_LEGAL	asesorias legales	data_estructuracostos	ASESORIA_LEGAL	1	0	0	1	\N	25			1		ASESORÍAS LEGALES				\N	\N	66		\N	\N	\N	\N	\N
73	104	COSTO URBANISMO	costo del urbanismo en m2 	data_estructuracostos	costo_urbanismo	1	0	0	1	\N	6			1	costo del urbanismo en m2		\N			\N	\N	44		\N	\N	\N	\N	\N
84	104	HONORARIOS_CONSTRUCCION	HONORARIOS DE CONSTRUCCION	data_estructuracostos	honorarios_construc	1	0	0	1	\N	14			1		HONORARIOS DE CONSTRUCCIÓN				\N	\N	55		\N	\N	\N	\N	\N
75	104	VR TERRENO URBANIZADO	valor del terreno urbanizado	data_estructuracostos	vr_terreno_urb	1	0	0	1	\N	7			1	valor del terreno urbanizado		\N			\N	\N	46		\N	\N	\N	\N	\N
76	104	PPTO COSTOS DIRECTOS	presupuesto costos directos	data_estructuracostos	ppto_costos_direc	1	0	0	1	\N	8			1	presupuesto costos directos					\N	\N	47		\N	\N	\N	\N	\N
77	104	% INCREMENTOS 	%INCREMENTOS(PERIODO ANUAL,MENSUAl)	data_estructuracostos	%_INCREMENTOS 	1	0	0	1	\N	9			1	%incrementos(periodo anual,Mensual)					\N	\N	48		\N	\N	\N	\N	\N
79	104	COSTOS POSVENTAS	COSTOS POSVENTAS	data_estructuracostos	costos_posventas	1	0	0	1	\N	10			1						\N	\N	50		\N	\N	\N	\N	\N
80	104	% IMPREVISTOS	porcentaje imprevistos	data_estructuracostos	%_imprevistos	1	0	0	1	\N	11			1	porcentaje imprevistos					\N	\N	51		\N	\N	\N	\N	\N
85	104	HONORARIOS_GERENCIA	honorarios de gerencia	data_estructuracostos	honorarios_geren	1	0	0	1	\N	15			1		HONORARIOS DE GERENCIA				\N	\N	56		\N	\N	\N	\N	\N
16	3	Nombre	Nombre del Formulario	frm_forms	frm_name	1	0	\N	\N	\N	1	\N	FRM_NAME->Nombre||FRM_CODE->Codigo||FRM_DESCRIPTION->Descripcion	9	\N	\N	frmf_name	frm_forms	frmf_name	\N	\N	34	FRM_NAME->Nombre||FRM_CODE->Codigo||FRM_DESCRIPTION->Descripcion	\N	\N	\N	\N	\N
266	5	Parametros	Parametros del Objeto	frmf_frmfields	frmf_params	0	0	5	4	\N	13	\N	\N	3	Consulta Parametros que se requieren para armar tipos de objetos tipo ajax y/o conformar una consulta.	Parametros	\N	\N	\N	\N	\N	237	Si->1||No->0->*	0	\N	0	\N	\N
140	105	valora_sueloyconstrc 		data_catastral	valora_sueloyconstrc 	0	0	0	1	\N	20			0		VALORACIÓN SUELO Y CONSTRUCCIONES				\N	\N	111		\N	\N	\N	\N	\N
231	101	act_trasl_dom		data_seleccionpredios	act_trasl_dom	0	0	0	2	\N	11			1		Acto traslaticio de dominio				\N	\N	202		\N	\N	\N	\N	\N
105	101	elaboro		data_seleccionpredios	elaboro	0	0	0	1	\N	27			5		ELABORO				\N	\N	76		\N	\N	\N	\N	\N
241	101	incorpo_topog		data_seleccionpredios	incorpo_topog	0	0	0	1	\N	19			1		Incorporación Topográfica				\N	\N	212		\N	\N	\N	\N	\N
249	101	observa_recomen		data_seleccionpredios	observa_recomen	0	0	0	1	\N	26			1		OBSERVACIONES Y RECOMENDACIONES				\N	\N	220		\N	\N	\N	\N	\N
263	5	Vars	Recibe Variablesa	frmf_frmfields	frmf_vars	0	0	\N	3	\N	4	\N	\N	7	Seleccionar si la Variable llega por Post/GET/SESSION	Variable  post/GET/SESSION	\N	\N	\N	\N	\N	234	Todas->0||Get->1||Post->2||Session->3	0	\N	\N	\N	\N
265	5	Rowspan	Filas a Combinar	frmf_frmfields	frmf_rowspan	\N	0	\N	4	\N	4	\N	\N	7	Filas a combinar, en html es e rowspan	Filas a Combinar Rowspan	\N	\N	\N	\N	\N	236	0->0->*||1||2||3||4||5||6||7||8||9||10||11||12	0	\N	\N	\N	\N
264	5	Varsparam	Nombre de variable Post/Get/Session	frmf_frmfields	frmf_varsparam	0	0	\N	3	\N	8	\N	\N	1	Nombre de variable que llega por Get/Post/Session	Variable ..	\N	\N	\N	\N	\N	235	\N	0	\N	\N	\N	\N
271	101	Expediente	Dirección del Predio a buscar	data_seleccionpredios	numExpediente	1	1	2	2	\N	3			1	Numero de expediente que llega desde formulario anterior.	Expediente / Proyecto Asociado				\N	\N	238	numExpediente->Expediente	0	\N	0	numExpediente	\N
272	101	FichaPrejuridica	Ficha Prejuridica			0	0	0	3	\N	1			15	Muestra la ficha prejuridica del predio.	Expediente / Proyecto Asociado				\N	\N	239	http://siim2.infometrika.net:8080/birt/frameset?__report=fichaPrejuridica.rptdesign&||chip->CHIP||expediente->Expediente	0	\N	0		\N
214	106	no_vivi_propu		data_urbanistica	no_vivi_propu	1	0	0	1	\N	30			1		No Viviendas Propuestas				\N	\N	185		\N	\N	\N	\N	\N
70	101	Dirección	Dirección del Predio a buscar	data_seleccionpredios	direccion	0	0	0	1	\N	3		FUENTE_DIRECCION->Dirección||BARMANPRE->BarManPre||CHIP->CHIP	9	Escriba la dirección del Predio a buscar		fuente_direccion	lote4686		\N	\N	41	FUENTE_DIRECCION->Dirección||BARMANPRE->BarManPre||CHIP->CHIP	\N	\N	\N	\N	\N
64	101	CHIP	CHIP	data_seleccionpredios	chip	1	1	0	2	\N	1	XXX-XXXXXXXXXX	CHIP->CHIP||FUENTE_DIRECCION->Dirección||BARMANPRE->BarManPre	9	Chip del predio		fuente_direccion	lote4686	chip	\N	\N	35	CHIP->CHIP||FUENTE_DIRECCION->Dirección||BARMANPRE->BarManPre	\N	\N	\N	\N	\N
257	102	area_visvip_trasla			area_visvip_trasla	1	0	0	1	\N	5			1		AREA VIS/VIP A TRASLADAR PROYECTO ORIGEN				\N	\N	228		\N	\N	\N	\N	\N
67	104	proyecto	Nombre del Proyecto		proyecto	1	0	0	1	\N	1	Nombre del Proyecto		1	Escriba aquí el nombre del proyecto - Es la etiqueta del expediente	Proyecto				\N	\N	38		\N	\N	\N	\N	\N
71	104	VR/m2	valor del m2 		VR/m2	1	0	0	2	\N	4			1	valor del m2 en millones					\N	\N	42		\N	\N	\N	\N	\N
69	104	Area terreno m2	area del terreno en m2	data_estructuracostos	area_terreno	1	0	0	1	\N	4	area_terreno_m2		1	area del terreno en m2					\N	\N	40		\N	\N	\N	\N	\N
68	104	fecha	fecha de elaboracion	data_estructuracostos	fecha_elaboro	1	0	0	3	\N	2			4		Fecha de Elaboración				\N	\N	39		\N	\N	\N	\N	\N
86	104	HONORARIOS_DISEÑOS	honorarios de diseños	data_estructuracostos	honorarios_dise	1	0	0	1	\N	16			1		HONORARIOS DISEÑOS				\N	\N	57		\N	\N	\N	\N	\N
87	104	T_COSTOS_INDIRECTOS	total de costos indirectos	data_estructuracostos	T_COSTOS_INDIRECTOS	1	0	0	1	\N	17			1		TOTAL COSTOS INDIRECTOS				\N	\N	58		\N	\N	\N	\N	\N
132	105	chip		data_catastral	chip	1	0	0	1	\N	16			1		Chip:				\N	\N	103		\N	\N	\N	\N	\N
82	104	T_COSTOSEDIFICACION	costos totales directos edificacion	data_estructuracostos	totalcostosedificacion	1	0	0	1	\N	12			1		total costos  directos edificación				\N	\N	53		\N	\N	\N	\N	\N
134	105	propietario		data_catastral	propietario	1	0	0	1	\N	17			1		Propietario:				\N	\N	105		\N	\N	\N	\N	\N
89	104	CURADURIA_URBANA	curaduria urbana	data_estructuracostos	CURADURIA_URBANA	1	0	0	1	\N	19			1		CURADURÍA URBANA				\N	\N	60		\N	\N	\N	\N	\N
100	104	TOTAL_COSTOS	total costos	data_estructuracostos	TOTAL_COSTOS	1	0	0	1	\N	30			1		TOTAL COSTOS				\N	\N	71		\N	\N	\N	\N	\N
96	104	INTERESES_CREDITO	intereses credito 	data_estructuracostos	INTERESES_CREDITO	1	0	0	1	\N	26			1		INTERESES CRÉDITO (CORPORACIÓN)				\N	\N	67		\N	\N	\N	\N	\N
97	104	GASTO_CREDITO	gastos de credito	data_estructuracostos	GASTO_CREDITO	1	0	0	1	\N	27			1		GASTO DE CRÉDITO				\N	\N	68		\N	\N	\N	\N	\N
90	104	IMPUES_DELINEACION_URB	impuesto delineacion urbana	data_estructuracostos	IMPUES_DELINEACION_URB	1	0	0	1	\N	20			1		IMPUESTO DELINEACIÓN URBANA				\N	\N	61		\N	\N	\N	\N	\N
91	104	IMPUESTO_DEL_4/ooo	IMPUESTO DEL 4/ooo	data_estructuracostos	IMPUESTO_DEL_4/ooo	1	0	0	1	\N	21			1		IMPUESTO DEL 4/ooo				\N	\N	62		\N	\N	\N	\N	\N
92	104	IMPU_DE_INDUSyCOMER	impuesto de industria y comercio	data_estructuracostos	IMPU_DE_INDUSyCOMER	1	0	0	1	\N	22			1		IMPUESTO DE INDUSTRIA Y COMERCIO				\N	\N	63		\N	\N	\N	\N	\N
93	104	COST_ESCRITURA_DIFE_VIVIE	costos escrituracion diferente a vivenda	data_estructuracostos	COST_ESCRITURA_DIFE_VIVIE	1	0	0	1	\N	23			1		COSTOS ESCRITURACION DIFERENTE A VIVIENDA				\N	\N	64		\N	\N	\N	\N	\N
94	104	REG_PROP_HORIZ	reglamento propiedad horizontal	data_estructuracostos	REG_PROP_HORIZ	1	0	0	1	\N	24			1		REGLAMENTO PROPIEDAD HORIZONTAL				\N	\N	65		\N	\N	\N	\N	\N
98	104	GASTO_BANCO	gastos banco	data_estructuracostos	GASTO_BANCO	1	0	0		\N	28			1		GASTOS BANCO				\N	\N	69		\N	\N	\N	\N	\N
101	104	UTILIDAD_BRUTA	utilidad bruta	data_estructuracostos	UTILIDAD_BRUTA	1	0	0	1	\N	31			1		UTILIDAD BRUTA				\N	\N	72		\N	\N	\N	\N	\N
103	104	UTILIDAD_PROYECTO	utilidad del proyecto	data_estructuracostos	UTILIDAD_PROYECTO	1	0	0	1	\N	33			1		UTILIDAD DEL PROYECTO				\N	\N	74		\N	\N	\N	\N	\N
99	104	TOTAL_COSTOS_FINANCI	total costos financieros	data_estructuracostos	TOTAL_COSTOS_FINANCI	1	0	0	1	\N	29			1		TOTAL COSTOS FINANCIEROS				\N	\N	70		\N	\N	\N	\N	\N
102	104	DEVOLUCION_IVA	devolucion iva	data_estructuracostos	DEVOLUCION_IVA	1	0	0	1	\N	32			1		DEVOLUCION IVA				\N	\N	73		\N	\N	\N	\N	\N
104	104	Concepto	concepto	data_estructuracostos	Concepto	1	0	0	1	\N	34			1	concepto de quien elabora el formulario	Concepto:				\N	\N	75		\N	\N	\N	\N	\N
258	102	valor_liquida			valor_liquida	1	0	0	1	\N	6			1		VALOR LIQUIDACIÓN				\N	\N	229		\N	\N	\N	\N	\N
259	102	resolucion			resolucion	1	0	0	1	\N	7			1		RESOLUCIÓN 				\N	\N	230		\N	\N	\N	\N	\N
260	102	recaudo			recaudo	1	0	0	1	\N	8			1		RECAUDO				\N	\N	231		\N	\N	\N	\N	\N
261	102	total_liquida			total_liquida	1	0	0	1	\N	9			2		TOTAL LIQUIDADO 				\N	\N	232		\N	\N	\N	\N	\N
122	105	localizacion		data_catastral	localizacion	0	0	0	1	\N	1			0		Localización 				\N	\N	93		\N	\N	\N	\N	\N
106	104	TERRENO	Título		terreno	0	0	0	1	\N	3			0		TERRENO				\N	\N	77		\N	\N	\N	\N	\N
107	105	nombre_proyecto	nombre del proyecto	data_catastral	nombre_proyecto	1	0	0	1	\N	1			1		Nombre Proyecto:				\N	\N	78		\N	\N	\N	\N	\N
141	105	vigen_infoavrefe		data_catastral	vigen_infoavrefe	1	0	0	1	\N	21			1		Vigencia Información AV.Referencia:				\N	\N	112		\N	\N	\N	\N	\N
142	105	vl_totalterre		data_catastral	vl_totalterre	1	0	0	2	\N	21			1		VL Total Terreno (catastral):				\N	\N	113		\N	\N	\N	\N	\N
113	105	considera_ambi			considera_ambi	1	0	0	2	\N	4			10		Consideraciones Ambientales 				\N	\N	84		\N	\N	\N	\N	\N
143	105	vm2_terreno		data_catastral	vm2_terreno	1	0	0	1	\N	22			1		V m2 Terreno (catastral):				\N	\N	114		\N	\N	\N	\N	\N
111	1	VR Total Terreno	valor total del terreno		VR_TOTAL_TERRENO	1	0	0	3	\N	4	$		0	valor total del terreno en millones					\N	\N	82		\N	\N	\N	\N	\N
114	105	info_gestyfinan	informacion para gestion y financiacion 	data_catastral	info_gestyfinan	0	0	0	1	\N	5			0		Información Para Gestión y Financiación 				\N	\N	85		\N	\N	\N	\N	\N
115	105	fuente_proyec	fuente proyecto	data_catastral	fuente_proyec	1	0	0	1	\N	6			1		Fuente Proyecto:				\N	\N	86		\N	\N	\N	\N	\N
116	105	tipo_predio	tipo de predio	data_catastral	tipo_predio	1	0	0	2	\N	6			1		Tipo Predio:				\N	\N	87		\N	\N	\N	\N	\N
117	105	año_gest	año de gestion	data_catastral	año_gest	1	0	0	1	\N	7			1		Año Gestión:				\N	\N	88		\N	\N	\N	\N	\N
118	105	tipo_gest	tipo de gestion	data_catastral	tipo_gest	1	0	0	2	\N	7			1		Tipo Gestión:				\N	\N	89		\N	\N	\N	\N	\N
119	105	fuente_recursos	fuente de recursos	data_catastral	fuente_recursos	1	0	0	1	\N	8			1		Fuente Recursos:				\N	\N	90		\N	\N	\N	\N	\N
120	105	decla_desapriori	declarat.desarrollo prioritario	data_catastral	decla_desapriori	1	0	0	2	\N	8			1		Declarat. Desarrollo Prioritario:				\N	\N	91		\N	\N	\N	\N	\N
121	105	fecha_vencidp		data_catastral	fecha_vencidp	1	0	0	1	\N	9			4		Fecha Vencimiento DP:				\N	\N	92		\N	\N	\N	\N	\N
123	105	territo_habi		data_catastral	territo_habi	1	0	0	1	\N	11			1		Territorio Hábitat: 				\N	\N	94		\N	\N	\N	\N	\N
124	105	linea_interv		data_catastral	linea_interv	1	0	0	2	\N	11			1		Linea de Intervención:				\N	\N	95		\N	\N	\N	\N	\N
125	105	localidad		data_catastral	localidad	1	0	0	1	\N	12			1		Localidad:				\N	\N	96		\N	\N	\N	\N	\N
126	105	upz		data_catastral	upz	1	0	0	2	\N	12			1		UPZ:				\N	\N	97		\N	\N	\N	\N	\N
127	105	barrio		data_catastral	barrio	1	0	0	1	\N	13			1		Barrio:				\N	\N	98		\N	\N	\N	\N	\N
129	105	info_catastral		data_catastral	info_catastral	0	0	0	1	\N	14			0		Información catastral				\N	\N	100		\N	\N	\N	\N	\N
130	105	vigen_infocatast		data_catastral	vigen_infocatast	1	0	0	1	\N	15			1		Vigencia Información Catastral:				\N	\N	101		\N	\N	\N	\N	\N
131	105	barmanpre		data_catastral	barmanpre	1	0	0	2	\N	15			1		BARMANPRE:				\N	\N	102		\N	\N	\N	\N	\N
133	105	direccion		data_catastral	direccion	1	0	0	2	\N	16			1		Dirección: 				\N	\N	104		\N	\N	\N	\N	\N
135	105	matric_inmo		data_catastral	matric_inmo	1	0	0	2	\N	17			1		Matricula Inmobiliaria:				\N	\N	106		\N	\N	\N	\N	\N
136	105	area_terreno		data_catastral	area_terre	1	0	0	1	\N	18			1		Area Terreno (catastral):				\N	\N	107		\N	\N	\N	\N	\N
137	105	area_constru		data_catastral	area_constru	1	0	0	2	\N	18			1		Area Construida:				\N	\N	108		\N	\N	\N	\N	\N
138	105	altura		data_catastral	altura	1	0	0	1	\N	19			1		Altura (pisos actuales):				\N	\N	109		\N	\N	\N	\N	\N
139	105	dest_econo		data_catastral	dest_econo	1	0	0	2	\N	19			1		Destino Económico:				\N	\N	110		\N	\N	\N	\N	\N
144	105	vrtotal_areaconst		data_catastral	vrtotal_areaconst	1	0	0	2	\N	22			1		VR. Total Area Constru.(catastral)				\N	\N	115		\N	\N	\N	\N	\N
145	105	vr_m2constru		data_catastral	vr_m2constru	1	0	0	1	\N	23			1		VR m2 Construido (catastral):				\N	\N	116		\N	\N	\N	\N	\N
146	105	avcatastral_totl		data_catastral	avcatastral_totl	1	0	0	1	\N	23			1		Avaluo Catastral Total:				\N	\N	117		\N	\N	\N	\N	\N
147	105	valor_m2av		data_catastral	valor_m2av	1	0	0	1	\N	24			1		Valor m2 AV.(catastral):				\N	\N	118		\N	\N	\N	\N	\N
148	105	vr_m2avaluoref		data_catastral	vr_m2avaluoref	1	0	0	2	\N	24			1		VR m2 Avaluo Referencia:				\N	\N	119		\N	\N	\N	\N	\N
149	105	av_comertotal		data_catastral	av_comertotal	1	0	0	1	\N	25			1		Avaluo Comercial Total:				\N	\N	120		\N	\N	\N	\N	\N
150	105	vr_m2terrenocomer		data_catastral	vr_m2terrenocomer	1	0	0	2	\N	25			1		V m2 Terreno Comercial:				\N	\N	121		\N	\N	\N	\N	\N
151	105	areas		data_catastral	areas	0	0	0	1	\N	26			0		AREAS				\N	\N	122		\N	\N	\N	\N	\N
152	105	Area_SIG/m²		data_catastral	Area_SIG/m²	1	0	0	1	\N	27			1		Area SIG/m²				\N	\N	123		\N	\N	\N	\N	\N
153	105	Area_Bruta/m²		data_catastral	Area_Bruta/m²	1	0	0	2	\N	27			1		Area Bruta/m²				\N	\N	124		\N	\N	\N	\N	\N
154	105	Area_Neta/m²		data_catastral	Area_Neta/m²	1	0	0	1	\N	28			1		Area Neta/m²				\N	\N	125		\N	\N	\N	\N	\N
155	105	Area_Util/m²		data_catastral	Area_Util/m²	1	0	0	2	\N	28			1		Area Util/m²				\N	\N	126		\N	\N	\N	\N	\N
110	105	topografia	topografia	data_catastral	topografia	1	0	0	2	\N	3			10		Topografía				\N	\N	81		\N	\N	\N	\N	\N
108	105	imagenes_proyecto			imegenes_proyecto	0	0	0	1	\N	2			5		Imágenes Generales del Proyecto				\N	\N	79		\N	\N	\N	\N	\N
112	105	imagen_sat	imagen del satelite	data_catastral	imagen_sat	1	0	0	1	\N	4			10		Imagen del satélite 				\N	\N	83		\N	\N	\N	\N	\N
109	105	localizacion_general		data_catastral	localizacion_general	1	0	0	1	\N	3			10		Localización  General				\N	\N	80		\N	\N	\N	\N	\N
128	105	observaciones	observaciones		observaciones	0	0	0	1	\N	29			1		Observaciones:				\N	\N	99		\N	\N	\N	\N	\N
156	105	condi_fisiyambi_pred		data_catastral	condi_fisiyambi_pred	0	0	0	1	\N	30			0		CONDICIONES FÍSICAS Y AMBIENTALES DEL PREDIO				\N	\N	127		\N	\N	\N	\N	\N
157	105	estruc_eco_princ		data_catastral	estruc_eco_princ	0	0	0	1	\N	31			0		ESTRUCTURA ECOLÓGICA PRINCIPAL				\N	\N	128		\N	\N	\N	\N	\N
158	105	estruc_eco_princ1		data_catastral	estruc_eco_princ1	0	0	0	1	\N	32			1		ESTRUCTURA ECOLÓGICA PRINCIPAL				\N	\N	129		\N	\N	\N	\N	\N
159	105	area_proteg		data_catastral	area_proteg	0	0	0	2	\N	32			1		AREAS PROTEGIDAS				\N	\N	130		\N	\N	\N	\N	\N
160	105	parq_urba		data_catastral	parq_urba	0	0	0	1	\N	33			1		PARQUES URBANOS				\N	\N	131		\N	\N	\N	\N	\N
161	105	corredores_eco		data_catastral	corredores_eco	0	0	0	2	\N	33			1		CORREDORES ECOLÓGICOS 				\N	\N	132		\N	\N	\N	\N	\N
162	105	ame_rio_bog		data_catastral	ame_rio_bog	0	0	0	2	\N	34			1		AME RIO BOGOTA				\N	\N	133		\N	\N	\N	\N	\N
164	105	afecta_riesgo		data_catastral	afecta_riesgo	0	0	0	1	\N	35			0		AFECTACIÓN POR RIESGO				\N	\N	135		\N	\N	\N	\N	\N
165	105	aglomeraciones		data_catastral	aglomeraciones	0	0	0	1	\N	36			1		AGLOMERACIONES				\N	\N	136		\N	\N	\N	\N	\N
166	105	deslizamientos		data_catastral	deslizamientos	0	0	0	2	\N	36			1		DESLIZAMIENTOS				\N	\N	137		\N	\N	\N	\N	\N
167	105	incendios_forest		data_catastral	incendios_forest	0	0	0	1	\N	37			1		INCENDIOS FORESTALES				\N	\N	138		\N	\N	\N	\N	\N
168	105	inundacion		data_catastral	inundacion	0	0	0	2	\N	37			1		INUNDACIONES				\N	\N	139		\N	\N	\N	\N	\N
169	105	riesgo_tecnogico		data_catastral	riesgo_tecnogico	0	0	0	1	\N	38			1		RIESGOS TECNOLÓGICOS				\N	\N	140		\N	\N	\N	\N	\N
170	105	sismos		data_catastral	sismos	0	0	0	2	\N	38			1		SISMOS				\N	\N	141		\N	\N	\N	\N	\N
171	105	afecta_vias_redes		data_catastral	afecta_vias_redes	0	0	0	1	\N	39			0		AFECTACIÓN POR VÍAS O REDES				\N	\N	142		\N	\N	\N	\N	\N
172	105	malla_vial_arterial		data_catastral	malla_vial_arterial	0	0	0	1	\N	40			1		MALLA VIAL ARTERIAL				\N	\N	143		\N	\N	\N	\N	\N
173	105	red_acueducto		data_catastral	red_acueducto	0	0	0	2	\N	40			1		RED DE ACUEDUCTO				\N	\N	144		\N	\N	\N	\N	\N
174	105	red_alcantarillado		data_catastral	red_alcantarillado	0	0	0	1	\N	41			1		RED DE ALCANTARILLADO				\N	\N	145		\N	\N	\N	\N	\N
175	105	red_gasnatural		data_catastral	red_gasnatural	0	0	0	2	\N	41			1		RED DE GAS NATURAL				\N	\N	146		\N	\N	\N	\N	\N
176	105	red_energia		data_catastral	red_energia	0	0	0	1	\N	42			1		RED ENERGÍA				\N	\N	147		\N	\N	\N	\N	\N
194	106	otras_cesiones_propu		data_urbanistica	otras_cesiones_propu	0	0	0	1	\N	9			1		Otras Cesiones Propuestas				\N	\N	165		\N	\N	\N	\N	\N
177	105	condi_industri_zona		data_catastral	condi_industri_zona	0	0	0	1	\N	43			0		CONDICIONES INDUSTRIALES DE LA ZONA				\N	\N	148		\N	\N	\N	\N	\N
178	105	zopra		data_catastral	zopra	0	0	0	1	\N	44			1		ZOPRA				\N	\N	149		\N	\N	\N	\N	\N
179	105	residu_solid_peligro		data_catastral	residu_solid_peligro	0	0	0	2	\N	44			1		RESIDUOS SOLIDOS PELIGROSOS				\N	\N	150		\N	\N	\N	\N	\N
180	105	pasivos_ambientales		data_catastral	pasivos_ambientales	0	0	0	2	\N	45			1		PASIVOS AMBIENTALES				\N	\N	151		\N	\N	\N	\N	\N
163	105	otros		data_catastral	otros	0	0	0	2	\N	45			1		Otros				\N	\N	134		\N	\N	\N	\N	\N
181	105	calidad_aireyruido		data_catastral	calidad_aireyruido	0	0	0	1	\N	46			0		CALIDAD DE AIRE Y RUIDO				\N	\N	152		\N	\N	\N	\N	\N
182	105	calidad_aire		data_catastral	calidad_aire	0	0	0	1	\N	47			1		CALIDAD DE AIRE				\N	\N	153		\N	\N	\N	\N	\N
183	105	calidad_ruido		data_catastral	calidad_ruido	0	0	0	2	\N	47			1		CALIDAD DE RUIDO				\N	\N	154		\N	\N	\N	\N	\N
184	105	reviso		data_catastral	reviso	0	0	0	1	\N	49			1		REVISO:				\N	\N	155		\N	\N	\N	\N	\N
185	106	area_bruta		data_urbanistica	area_bruta	1	0	0	1	\N	1			1		Área bruta (predial)				\N	\N	156		\N	\N	\N	\N	\N
186	106	Afectaciones		data_urbanistica	Afectaciones	0	0	0	1	\N	2			1		Afectaciones				\N	\N	157		\N	\N	\N	\N	\N
187	106	area_terr_net		data_urbanistica	area_terr_net	1	0	0	1	\N	3			1		Area De Terreno o Area Neta Urbanizable				\N	\N	158		\N	\N	\N	\N	\N
196	106	area_util_vip		data_urbanistica	area_util_vip	0	0	0	1	\N	11			1		Area Útil Uso Principal Residencial VIP				\N	\N	167		\N	\N	\N	\N	\N
190	106	area_total_constru_prop		data_urbanistica	area_total_constru_prop	1	0	0	1	\N	20			1		Area Total Construida Propuesta				\N	\N	161		\N	\N	\N	\N	\N
189	106	area_util		data_urbanistica	area_util	1	0	0	1	\N	10			1		Area Útil 				\N	\N	160		\N	\N	\N	\N	\N
197	106	area_util_vis		data_urbanistica	area_util_vis	0	0	0	1	\N	12			1		Area Útil Uso Principal Residencial VIS				\N	\N	168		\N	\N	\N	\N	\N
199	106	area_util_comer		data_urbanistica	area_util_comer	0	0	0	1	\N	14			1		Area Útil Uso Principal Comercio				\N	\N	170		\N	\N	\N	\N	\N
198	106	area_util_otro		data_urbanistica	area_util_otro	0	0	0	1	\N	13			1		Area Útil Uso Principal Residencial Otro Estrato				\N	\N	169		\N	\N	\N	\N	\N
191	106	Parque_propuesto		data_urbanistica	Parque_propuesto	0	0	0	1	\N	6			1		Parque propuesto				\N	\N	162		\N	\N	\N	\N	\N
188	106	total_cesiones_proyec		data_urbanistica	total_cesiones_proyec	1	0	0	1	\N	5			1		Total Cesiones Del Proyecto				\N	\N	159		\N	\N	\N	\N	\N
193	106	Vías_locales_prop		data_urbanistica	Vías_locales_prop	0	0	0	1	\N	8			1		Vías Locales Propuestas 				\N	\N	164		\N	\N	\N	\N	\N
200	106	area_util_servi		data_urbanistica	area_util_servi	0	0	0	1	\N	15			1		Area Útil Uso Principal Servicios				\N	\N	171		\N	\N	\N	\N	\N
195	106	control_ambie		data_urbanistica	control_ambie	0	0	0	1	\N	4			1		Control Ambiental				\N	\N	166		\N	\N	\N	\N	\N
192	106	equipamiento_propuesto		data_urbanistica	equipamiento_propuesto	0	0	0	1	\N	7			1		Equipamiento Propuesto				\N	\N	163		\N	\N	\N	\N	\N
201	106	area_util_comeryserv		data_urbanistica	area_util_comeryserv	0	0	0	1	\N	16			1		Area Útil Uso Principal Comercio y Servicios				\N	\N	172		\N	\N	\N	\N	\N
202	106	area_util_multi		data_urbanistica	area_util_multi	0	0	0	1	\N	17			1		Area Útil Uso Principal Múltiple  				\N	\N	173		\N	\N	\N	\N	\N
203	106	area_util_dota_pri		data_urbanistica	area_util_dota_pri	0	0	0	1	\N	18			1		Area Útil Uso Principal Dotacional Privado				\N	\N	174		\N	\N	\N	\N	\N
204	106	area_util_otros		data_urbanistica	area_util_otros	0	0	0	1	\N	19			1		Area Útil Uso Principal Otros				\N	\N	175		\N	\N	\N	\N	\N
205	106	area_tot_cons_vivi_vip		data_urbanistica	area_tot_cons_vivi_vip	0	0	0	1	\N	21			1		Area Total Construida Vivienda VIP				\N	\N	176		\N	\N	\N	\N	\N
206	106	area_tot_cons_vivi_vis		data_urbanistica	area_tot_cons_vivi_vis	0	0	0	1	\N	22			1		Area Total Construida Vivienda VIS				\N	\N	177		\N	\N	\N	\N	\N
207	106	area_tot_cons_vivi_otro		data_urbanistica	area_tot_cons_vivi_otro	0	0	0	1	\N	23			1		Area Total Construida Vivienda Otro Estrato				\N	\N	178		\N	\N	\N	\N	\N
208	106	area_tot_cons_comer_p1		data_urbanistica	area_tot_cons_comer_p1	0	0	0	1	\N	24			1		Area Total Construida Comercio en Primer Piso				\N	\N	179		\N	\N	\N	\N	\N
209	106	area_tot_cons_ofi_comer		data_urbanistica	area_tot_cons_ofi_comer	0	0	0	1	\N	25			1		Area Total Construida Oficinas o Comercio				\N	\N	180		\N	\N	\N	\N	\N
210	106	area_tot_cons_dot_pri		data_urbanistica	area_tot_cons_dot_pri	0	0	0	1	\N	26			1		Area Total Construida Dotacional Privado				\N	\N	181		\N	\N	\N	\N	\N
211	106	area_tot_cons_pl_comser		data_urbanistica	area_tot_cons_pl_comser	0	0	0	1	\N	27			1		Area Total Construida Plataforma de Comercio y Servicios				\N	\N	182		\N	\N	\N	\N	\N
212	106	area_tot_cons_otros		data_urbanistica	area_tot_cons_otros	0	0	0	1	\N	28			1		Area Total Construida Otros Usos				\N	\N	183		\N	\N	\N	\N	\N
213	106	area_equip_com_pri		data_urbanistica	area_equip_com_pri	0	0	0	1	\N	29			1		Area Equipamiento Comunal Privado				\N	\N	184		\N	\N	\N	\N	\N
215	106	no_vivi_vip		data_urbanistica	no_vivi_vip	1	0	0	1	\N	31			1		No Viviendas VIP				\N	\N	186		\N	\N	\N	\N	\N
216	106	no_vivi_VIS		data_urbanistica	no_vivi_vis	1	0	0	1	\N	32			1		No Viviendas VIS				\N	\N	187		\N	\N	\N	\N	\N
217	106	no_vivi_novis		data_urbanistica	no_vivi_novis	1	0	0	1	\N	33			1		No Viviendas NO VIS				\N	\N	188		\N	\N	\N	\N	\N
218	106	area_estacion		data_urbanistica	area_estacion	1	0	0	1	\N	34			1		Area de Estacionamientos				\N	\N	189		\N	\N	\N	\N	\N
219	106	no_estacion_defin		data_urbanistica	no_estacion_defin	0	0	0	1	\N	35			1		No Estacionamientos Definidos Por La Modelacion				\N	\N	190		\N	\N	\N	\N	\N
220	106	notas		data_urbanistica	notas	0	0	0	1	\N	36			1		Notas:				\N	\N	191		\N	\N	\N	\N	\N
247	101	area_catast		data_seleccionpredios	area_catast	0	0	0	1	\N	24			1		Área catastral				\N	\N	218		\N	\N	\N	\N	\N
246	101	area_registr		data_seleccionpredios	area_registr	0	0	0	1	\N	23			5		Área registral				\N	\N	217		\N	\N	\N	\N	\N
248	101	area_topo		data_seleccionpredios	area_topo	0	0	0	1	\N	25			1		Área topográfica				\N	\N	219		\N	\N	\N	\N	\N
234	101	cab_superf		data_seleccionpredios	cab_superf	0	0	0	2	\N	14			1		Cabida superficiaria				\N	\N	205		\N	\N	\N	\N	\N
232	101	ced_catas		data_seleccionpredios	ced_catas	0	0	0	2	\N	12			1		Cédula Catastral 				\N	\N	203		\N	\N	\N	\N	\N
245	101	comp_espac		data_seleccionpredios	comp_espac	0	0	0	1	\N	22			5		COMPONENTE ESPACIAL				\N	\N	216		\N	\N	\N	\N	\N
221	101	datos_basicpropi		data_seleccionpredios	datos_basicpropi	0	0	0	1	\N	4			5		DATOS BÁSICOS DEL PROPIETARIO:				\N	\N	192		\N	\N	\N	\N	\N
227	101	direcc_notifica		data_seleccionpredios	direcc_notifica	0	0	0	1	\N	9			1		Dirección de Notificación				\N	\N	198		\N	\N	\N	\N	\N
225	101	doc_identifi		data_seleccionpredios	doc_identifi	0	0	0	1	\N	7			1		Documento de identificación				\N	\N	196		\N	\N	\N	\N	\N
235	101	linderos		data_seleccionpredios	linderos	0	0	0	1	\N	15			5		linderos				\N	\N	206		\N	\N	\N	\N	\N
230	101	matric_inmob		data_seleccionpredios	matric_inmob	1	0	0	1	\N	12			1		Matrícula Inmobiliaria				\N	\N	201		\N	\N	\N	\N	\N
244	101	vigencia_info		data_seleccionpredios	vigencia_info	0	0	0	1	\N	21			1		Vigencia de la información				\N	\N	215		\N	\N	\N	\N	\N
243	101	tradicion		data_seleccionpredios	tradicion	0	0	0	1	\N	20			1		Tradición				\N	\N	214		\N	\N	\N	\N	\N
233	101	titu_adqui		data_seleccionpredios	titu_adqui	0	0	0	2	\N	13			1		Título de adquisición				\N	\N	204		\N	\N	\N	\N	\N
222	101	tipo_propi		data_seleccionpredios	tipo_propi	0	0	0	1	\N	5			1		Tipo de propietario				\N	\N	193		\N	\N	\N	\N	\N
223	101	tipo_perso		data_seleccionpredios	tipo_perso	0	0	0	2	\N	5			1		Tipo de persona				\N	\N	194		\N	\N	\N	\N	\N
240	101	tipo_linde		data_seleccionpredios	tipo_linde	0	0	0	1	\N	18			1		Tipo de linderos				\N	\N	211		\N	\N	\N	\N	\N
237	101	sur		data_seleccionpredios	sur	0	0	0	2	\N	16			1		sur				\N	\N	208		\N	\N	\N	\N	\N
242	101	reg_topog		data_seleccionpredios	reg_topog	0	0	0	2	\N	19			1		Registro Topográfico				\N	\N	213		\N	\N	\N	\N	\N
250	101	Profesional		data_seleccionpredios	Profesional	0	0	0	1	\N	28			1		Profesional				\N	\N	221		\N	\N	\N	\N	\N
226	101	predi_sector		data_seleccionpredios	predi_sector	0	0	0	1	\N	8			1		Predio del Sector				\N	\N	197		\N	\N	\N	\N	\N
229	101	predio		data_seleccionpredios	predio	0	0	0	1	\N	11			1		Predio				\N	\N	200		\N	\N	\N	\N	\N
238	101	Oriente		data_seleccionpredios	Oriente	0	0	0	1	\N	17			1		Oriente				\N	\N	209		\N	\N	\N	\N	\N
239	101	occidente		data_seleccionpredios	occidente	0	0	0	2	\N	17			1		occidente				\N	\N	210		\N	\N	\N	\N	\N
228	101	no_tel		data_seleccionpredios	no_tel	0	0	0	1	\N	10			1		NO. De Teléfono				\N	\N	199		\N	\N	\N	\N	\N
251	101	no_tarje_profe		data_seleccionpredios	no_tarje_profe	0	0	0	1	\N	29			1		No. tarjeta Profesional				\N	\N	222		\N	\N	\N	\N	\N
236	101	norte		data_seleccionpredios	norte	0	0	0	1	\N	16			1		norte				\N	\N	207		\N	\N	\N	\N	\N
224	101	propi_actu_inscr		data_seleccionpredios	propi_actu_inscr	0	0	0	1	\N	6			1		Propietario actual inscrito				\N	\N	195		\N	\N	\N	\N	\N
255	102	estrato			estrato	0	0	0	1	\N	3			9		ESTRATO				\N	\N	226		\N	\N	\N	\N	\N
254	102	nombre_proyec			nombre_proyec	0	0	0	1	\N	2			9		NOMBRE DEL PROYECTO				\N	\N	225		\N	\N	\N	\N	\N
253	102	propietario_proyec			propietario_proyec	0	0	0	1	\N	1			9		PROPIETARIO DEL PROYECTO				\N	\N	224		\N	\N	\N	\N	\N
256	102	local_zona			local_zona	0	0	0	1	\N	4			9		LOCALIDAD / ZONA				\N	\N	227		\N	\N	\N	\N	\N
252	101	firma			firma	0	0	0	1	\N	30			5		Firma				\N	\N	223		\N	\N	\N	\N	\N
262	100	predios	predios seleccionados	data_seleccionpredios		0	0	0	1	\N	10		barmanpre||chip||expediente	12	Predios que se han seleccionado actualmente....	Predios Seleccionados		data_seleccionpredios		\N	\N	233	barmanpre||chip||expediente	\N	\N	\N	\N	\N
78	101	BarManPre	Barrio - Manzana - Predio	data_seleccionpredios	barmanpre	0	0	0	1	\N	1		BARMANPRE->BarManPre||CHIP->CHIP||FUENTE_DIRECCION->Dirección	9	Campo de la base de datos correspondiente a Barrio - Manzana - Predio		fuente_direccion	lote4686		\N	\N	49	BARMANPRE->BarManPre||CHIP->CHIP||FUENTE_DIRECCION->Dirección	\N	\N	\N	\N	\N
65	100	BarManPre	Barrio - Manzana - Predio	data_seleccionpredios	barmanpre	0	0	0	1	\N	2		BARMANPRE->BarManPre||CHIP->CHIP||FUENTE_DIRECCION->Dirección	9	Campo de la base de datos correspondiente a Barrio - Manzana - Predio		fuente_direccion	lote4686		\N	\N	36	BARMANPRE->BarManPre||CHIP->CHIP||FUENTE_DIRECCION->Dirección	\N	\N	\N	\N	\N
66	100	Expediente	Expediente del Proyecto	data_seleccionpredios	expediente	1	1	0	1	\N	1			1	Muestra el expediente del proyecto donde se seleccionarán los predios.	Numero de Expediente				\N	\N	37		0	\N	0	numeroExpediente	\N
60	5	Col	Orden de Columna en la Tabla	frmf_frmfields	frmf_column	0	0	2	4	\N	3	\N	\N	7	Orden del campo en la Columna	Orden Columna	\N	\N	\N	\N	\N	29	0->0->*||1||2||3||4||5||6||7||8||9||10||11||12||13||14||15	0	\N	\N	\N	\N
81	100	Chip	CHIP		chip	1	1	0	0	\N	2			9	CHIP del predio		Chip		chip	\N	\N	52	CHIP->Chip||FUENTE_DIRECCION->Dirección||BARMANPRE->BarManPre	0	\N	0		\N
62	5	Nombre	Nombre del Campo	frmf_frmfields	frmf_name	1	1	0	1	\N	1	\N	\N	9	Nombre del Objeto, Debe ir sin espacios.	Nombre de Campo	frmf_name	frmf_frmfields	frm_code->codigo	\N	\N	31	FRMF_NAME->Nombre||FRMF_DESCRIPTION->Descripcion||FRM_CODE->codigo||FRMT_CODE->TipoDato||FRMF_ROWSPAN->Rowspan||FRMF_LABEL->Label||FRMF_SQL->Sql||FRMF_PARAMS->Parametros||FRMF_NULL->Null||FRMF_MASK->Mascara||FRMF_COLUMN->Col||FRMF_ORDER->OrdenCampo||FRMF_COLSPAN->colspan||FRMF_FIELD->Campo||FRMF_TABLEPKSEARCH->TablaParametro||FRMF_TABLESAVE->Tabla||FRMF_FIELDPKSEARCH->CampoTablaParametro||FRMF_PK->pk||FRMF_HELP->Ayuda||FRMF_VARS->Vars||FRMF_VARSPARAM->Varsparam||frmf_fieldpk->CampoBusqueda	0	\N	\N	\N	\N
\.


--
-- Data for Name: fun_funcionario; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.fun_funcionario (id, usua_doc, usua_fech_crea, usua_esta, usua_nomb, usua_ext, usua_nacim, usua_email, usua_at, usua_piso, cedula_ok, cedula_suip, nombre_suip, observa) FROM stdin;
\.


--
-- Data for Name: hist_eventos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.hist_eventos (id, depe_codi, hist_fech, usua_codi, radi_nume_radi, hist_obse, usua_codi_dest, usua_doc, usua_doc_old, sgd_ttr_codigo, hist_usua_autor, hist_doc_dest, depe_codi_dest, usuario_id) FROM stdin;
493	900	2017-06-09 18:25:32.785542	1	20179000000012	 	1	10153900001	\N	2	\N	10153900001	900	\N
494	900	2017-06-09 18:30:23.601278	1	20179000000012	*TRD*/ (Asigancion tipo documental.)	1	10153900001	\N	32	\N	10153900001	900	\N
495	900	2019-07-30 12:08:24.694865	1	20199000000022	 	1	10153900001	\N	2	\N	10153900001	900	\N
496	900	2019-07-30 12:08:34.238426	1	20199000000032	 	1	10153900001	\N	2	\N	10153900001	900	\N
497	900	2019-07-30 12:10:08.26732	1	20199000000042	 	1	10153900001	\N	2	\N	10153900001	900	\N
\.


--
-- Data for Name: informados; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.informados (radi_nume_radi, usua_codi, depe_codi, info_desc, info_fech, info_leido, usua_codi_info, info_codi, usua_doc, info_conjunto, usua_doc_origen) FROM stdin;
\.


--
-- Data for Name: medio_recepcion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.medio_recepcion (mrec_codi, mrec_desc) FROM stdin;
2	Fax
9	Chat
8	Call Center
1	Personal
3	Sitio Web
5	Mensajería
7	Atención Personalizada
4	Correo electrónico
6	Telefónico
0	--
\.


--
-- Data for Name: municipio; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.municipio (muni_codi, dpto_codi, muni_nomb, id_cont, id_pais, homologa_muni, homologa_idmuni, activa) FROM stdin;
0	0	No Existe	1	170	\N	\N	1
1	5	MEDELLIN	1	170	\N	\N	1
2	5	ABEJORRAL	1	170	\N	\N	1
4	5	ABRIAQUI	1	170	\N	\N	1
21	5	ALEJANDRIA	1	170	\N	\N	1
30	5	AMAGA	1	170	\N	\N	1
31	5	AMALFI	1	170	\N	\N	1
34	5	ANDES	1	170	\N	\N	1
36	5	ANGELOPOLIS	1	170	\N	\N	1
38	5	ANGOSTURA	1	170	\N	\N	1
40	5	ANORI	1	170	\N	\N	1
42	5	SANTA FE DE ANTIOQUIA	1	170	\N	\N	1
44	5	ANZA	1	170	\N	\N	1
45	5	APARTADO	1	170	\N	\N	1
51	5	ARBOLETES	1	170	\N	\N	1
55	5	ARGELIA	1	170	\N	\N	1
59	5	ARMENIA	1	170	\N	\N	1
79	5	BARBOSA	1	170	\N	\N	1
86	5	BELMIRA	1	170	\N	\N	1
88	5	BELLO	1	170	\N	\N	1
91	5	BETANIA	1	170	\N	\N	1
93	5	BETULIA	1	170	\N	\N	1
101	5	CIUDAD BOLIVAR	1	170	\N	\N	1
107	5	BRICENO	1	170	\N	\N	1
113	5	BURITICA	1	170	\N	\N	1
120	5	CACERES	1	170	\N	\N	1
125	5	CAICEDO	1	170	\N	\N	1
129	5	CALDAS	1	170	\N	\N	1
134	5	CAMPAMENTO	1	170	\N	\N	1
138	5	CANASGORDAS	1	170	\N	\N	1
142	5	CARACOLI	1	170	\N	\N	1
145	5	CARAMANTA	1	170	\N	\N	1
147	5	CAREPA	1	170	\N	\N	1
148	5	EL CARMEN DE VIBORAL	1	170	\N	\N	1
150	5	CAROLINA	1	170	\N	\N	1
154	5	CAUCASIA	1	170	\N	\N	1
172	5	CHIGORODO	1	170	\N	\N	1
190	5	CISNEROS	1	170	\N	\N	1
197	5	COCORNA	1	170	\N	\N	1
206	5	CONCEPCION	1	170	\N	\N	1
209	5	CONCORDIA	1	170	\N	\N	1
212	5	COPACABANA	1	170	\N	\N	1
234	5	DABEIBA	1	170	\N	\N	1
237	5	DONMATIAS	1	170	\N	\N	1
240	5	EBEJICO	1	170	\N	\N	1
250	5	EL BAGRE	1	170	\N	\N	1
264	5	ENTRERRIOS	1	170	\N	\N	1
266	5	ENVIGADO	1	170	\N	\N	1
282	5	FREDONIA	1	170	\N	\N	1
284	5	FRONTINO	1	170	\N	\N	1
306	5	GIRALDO	1	170	\N	\N	1
308	5	GIRARDOTA	1	170	\N	\N	1
310	5	GOMEZ PLATA	1	170	\N	\N	1
313	5	GRANADA	1	170	\N	\N	1
315	5	GUADALUPE	1	170	\N	\N	1
318	5	GUARNE	1	170	\N	\N	1
321	5	GUATAPE	1	170	\N	\N	1
347	5	HELICONIA	1	170	\N	\N	1
353	5	HISPANIA	1	170	\N	\N	1
360	5	ITAGUI	1	170	\N	\N	1
361	5	ITUANGO	1	170	\N	\N	1
364	5	JARDIN	1	170	\N	\N	1
368	5	JERICO	1	170	\N	\N	1
376	5	LA CEJA	1	170	\N	\N	1
380	5	LA ESTRELLA	1	170	\N	\N	1
390	5	LA PINTADA	1	170	\N	\N	1
400	5	LA UNION	1	170	\N	\N	1
411	5	LIBORINA	1	170	\N	\N	1
425	5	MACEO	1	170	\N	\N	1
440	5	MARINILLA	1	170	\N	\N	1
467	5	MONTEBELLO	1	170	\N	\N	1
475	5	MURINDO	1	170	\N	\N	1
480	5	MUTATA	1	170	\N	\N	1
483	5	NARINO	1	170	\N	\N	1
490	5	NECOCLI	1	170	\N	\N	1
495	5	NECHI	1	170	\N	\N	1
501	5	OLAYA	1	170	\N	\N	1
541	5	PENOL	1	170	\N	\N	1
543	5	PEQUE	1	170	\N	\N	1
576	5	PUEBLORRICO	1	170	\N	\N	1
579	5	PUERTO BERRIO	1	170	\N	\N	1
585	5	PUERTO NARE	1	170	\N	\N	1
591	5	PUERTO TRIUNFO	1	170	\N	\N	1
604	5	REMEDIOS	1	170	\N	\N	1
607	5	RETIRO	1	170	\N	\N	1
615	5	RIONEGRO	1	170	\N	\N	1
628	5	SABANALARGA	1	170	\N	\N	1
631	5	SABANETA	1	170	\N	\N	1
642	5	SALGAR	1	170	\N	\N	1
647	5	SAN ANDRES DE CUERQUIA	1	170	\N	\N	1
649	5	SAN CARLOS	1	170	\N	\N	1
652	5	SAN FRANCISCO	1	170	\N	\N	1
656	5	SAN JERONIMO	1	170	\N	\N	1
658	5	SAN JOSE DE LA MONTANA	1	170	\N	\N	1
659	5	SAN JUAN DE URABA	1	170	\N	\N	1
660	5	SAN LUIS	1	170	\N	\N	1
664	5	SAN PEDRO DE LOS MILAGROS	1	170	\N	\N	1
665	5	SAN PEDRO DE URABA	1	170	\N	\N	1
667	5	SAN RAFAEL	1	170	\N	\N	1
670	5	SAN ROQUE	1	170	\N	\N	1
674	5	SAN VICENTE FERRER	1	170	\N	\N	1
679	5	SANTA BARBARA	1	170	\N	\N	1
686	5	SANTA ROSA DE OSOS	1	170	\N	\N	1
690	5	SANTO DOMINGO	1	170	\N	\N	1
697	5	EL SANTUARIO	1	170	\N	\N	1
736	5	SEGOVIA	1	170	\N	\N	1
756	5	SONSON	1	170	\N	\N	1
761	5	SOPETRAN	1	170	\N	\N	1
789	5	TAMESIS	1	170	\N	\N	1
790	5	TARAZA	1	170	\N	\N	1
792	5	TARSO	1	170	\N	\N	1
809	5	TITIRIBI	1	170	\N	\N	1
819	5	TOLEDO	1	170	\N	\N	1
837	5	TURBO	1	170	\N	\N	1
842	5	URAMITA	1	170	\N	\N	1
847	5	URRAO	1	170	\N	\N	1
854	5	VALDIVIA	1	170	\N	\N	1
856	5	VALPARAISO	1	170	\N	\N	1
858	5	VEGACHI	1	170	\N	\N	1
861	5	VENECIA	1	170	\N	\N	1
873	5	VIGIA DEL FUERTE	1	170	\N	\N	1
885	5	YALI	1	170	\N	\N	1
887	5	YARUMAL	1	170	\N	\N	1
890	5	YOLOMBO	1	170	\N	\N	1
893	5	YONDO	1	170	\N	\N	1
895	5	ZARAGOZA	1	170	\N	\N	1
1	8	BARRANQUILLA	1	170	\N	\N	1
78	8	BARANOA	1	170	\N	\N	1
137	8	CAMPO DE LA CRUZ	1	170	\N	\N	1
141	8	CANDELARIA	1	170	\N	\N	1
296	8	GALAPA	1	170	\N	\N	1
372	8	JUAN DE ACOSTA	1	170	\N	\N	1
421	8	LURUACO	1	170	\N	\N	1
433	8	MALAMBO	1	170	\N	\N	1
436	8	MANATI	1	170	\N	\N	1
520	8	PALMAR DE VARELA	1	170	\N	\N	1
549	8	PIOJO	1	170	\N	\N	1
558	8	POLONUEVO	1	170	\N	\N	1
560	8	PONEDERA	1	170	\N	\N	1
573	8	PUERTO COLOMBIA	1	170	\N	\N	1
606	8	REPELON	1	170	\N	\N	1
634	8	SABANAGRANDE	1	170	\N	\N	1
638	8	SABANALARGA	1	170	\N	\N	1
675	8	SANTA LUCIA	1	170	\N	\N	1
685	8	SANTO TOMAS	1	170	\N	\N	1
758	8	SOLEDAD	1	170	\N	\N	1
770	8	SUAN	1	170	\N	\N	1
832	8	TUBARA	1	170	\N	\N	1
849	8	USIACURI	1	170	\N	\N	1
1	11	BOGOTA, D.C.	1	170	\N	\N	1
1	13	CARTAGENA DE INDIAS	1	170	\N	\N	1
6	13	ACHI	1	170	\N	\N	1
30	13	ALTOS DEL ROSARIO	1	170	\N	\N	1
42	13	ARENAL	1	170	\N	\N	1
52	13	ARJONA	1	170	\N	\N	1
62	13	ARROYOHONDO	1	170	\N	\N	1
74	13	BARRANCO DE LOBA	1	170	\N	\N	1
140	13	CALAMAR	1	170	\N	\N	1
160	13	CANTAGALLO	1	170	\N	\N	1
188	13	CICUCO	1	170	\N	\N	1
212	13	CORDOBA	1	170	\N	\N	1
222	13	CLEMENCIA	1	170	\N	\N	1
244	13	EL CARMEN DE BOLIVAR	1	170	\N	\N	1
248	13	EL GUAMO	1	170	\N	\N	1
268	13	EL PENON	1	170	\N	\N	1
300	13	HATILLO DE LOBA	1	170	\N	\N	1
430	13	MAGANGUE	1	170	\N	\N	1
433	13	MAHATES	1	170	\N	\N	1
440	13	MARGARITA	1	170	\N	\N	1
442	13	MARIA LA BAJA	1	170	\N	\N	1
458	13	MONTECRISTO	1	170	\N	\N	1
468	13	SANTA CRUZ DE MOMPOX	1	170	\N	\N	1
473	13	MORALES	1	170	\N	\N	1
490	13	NOROSI	1	170	\N	\N	1
549	13	PINILLOS	1	170	\N	\N	1
580	13	REGIDOR	1	170	\N	\N	1
600	13	RIO VIEJO	1	170	\N	\N	1
620	13	SAN CRISTOBAL	1	170	\N	\N	1
647	13	SAN ESTANISLAO	1	170	\N	\N	1
650	13	SAN FERNANDO	1	170	\N	\N	1
654	13	SAN JACINTO	1	170	\N	\N	1
655	13	SAN JACINTO DEL CAUCA	1	170	\N	\N	1
657	13	SAN JUAN NEPOMUCENO	1	170	\N	\N	1
667	13	SAN MARTIN DE LOBA	1	170	\N	\N	1
670	13	SAN PABLO	1	170	\N	\N	1
673	13	SANTA CATALINA	1	170	\N	\N	1
683	13	SANTA ROSA	1	170	\N	\N	1
688	13	SANTA ROSA DEL SUR	1	170	\N	\N	1
744	13	SIMITI	1	170	\N	\N	1
760	13	SOPLAVIENTO	1	170	\N	\N	1
780	13	TALAIGUA NUEVO	1	170	\N	\N	1
810	13	TIQUISIO	1	170	\N	\N	1
836	13	TURBACO	1	170	\N	\N	1
838	13	TURBANA	1	170	\N	\N	1
873	13	VILLANUEVA	1	170	\N	\N	1
894	13	ZAMBRANO	1	170	\N	\N	1
1	15	TUNJA	1	170	\N	\N	1
22	15	ALMEIDA	1	170	\N	\N	1
47	15	AQUITANIA	1	170	\N	\N	1
51	15	ARCABUCO	1	170	\N	\N	1
87	15	BELEN	1	170	\N	\N	1
90	15	BERBEO	1	170	\N	\N	1
92	15	BETEITIVA	1	170	\N	\N	1
97	15	BOAVITA	1	170	\N	\N	1
104	15	BOYACA	1	170	\N	\N	1
106	15	BRICENO	1	170	\N	\N	1
109	15	BUENAVISTA	1	170	\N	\N	1
114	15	BUSBANZA	1	170	\N	\N	1
131	15	CALDAS	1	170	\N	\N	1
135	15	CAMPOHERMOSO	1	170	\N	\N	1
162	15	CERINZA	1	170	\N	\N	1
172	15	CHINAVITA	1	170	\N	\N	1
176	15	CHIQUINQUIRA	1	170	\N	\N	1
180	15	CHISCAS	1	170	\N	\N	1
183	15	CHITA	1	170	\N	\N	1
185	15	CHITARAQUE	1	170	\N	\N	1
187	15	CHIVATA	1	170	\N	\N	1
189	15	CIENEGA	1	170	\N	\N	1
204	15	COMBITA	1	170	\N	\N	1
212	15	COPER	1	170	\N	\N	1
215	15	CORRALES	1	170	\N	\N	1
218	15	COVARACHIA	1	170	\N	\N	1
223	15	CUBARA	1	170	\N	\N	1
224	15	CUCAITA	1	170	\N	\N	1
226	15	CUITIVA	1	170	\N	\N	1
232	15	CHIQUIZA	1	170	\N	\N	1
236	15	CHIVOR	1	170	\N	\N	1
238	15	DUITAMA	1	170	\N	\N	1
244	15	EL COCUY	1	170	\N	\N	1
248	15	EL ESPINO	1	170	\N	\N	1
272	15	FIRAVITOBA	1	170	\N	\N	1
276	15	FLORESTA	1	170	\N	\N	1
293	15	GACHANTIVA	1	170	\N	\N	1
296	15	GAMEZA	1	170	\N	\N	1
299	15	GARAGOA	1	170	\N	\N	1
317	15	GUACAMAYAS	1	170	\N	\N	1
322	15	GUATEQUE	1	170	\N	\N	1
325	15	GUAYATA	1	170	\N	\N	1
332	15	GUICAN DE LA SIERRA	1	170	\N	\N	1
362	15	IZA	1	170	\N	\N	1
367	15	JENESANO	1	170	\N	\N	1
368	15	JERICO	1	170	\N	\N	1
377	15	LABRANZAGRANDE	1	170	\N	\N	1
380	15	LA CAPILLA	1	170	\N	\N	1
401	15	LA VICTORIA	1	170	\N	\N	1
403	15	LA UVITA	1	170	\N	\N	1
407	15	VILLA DE LEYVA	1	170	\N	\N	1
425	15	MACANAL	1	170	\N	\N	1
442	15	MARIPI	1	170	\N	\N	1
455	15	MIRAFLORES	1	170	\N	\N	1
464	15	MONGUA	1	170	\N	\N	1
466	15	MONGUI	1	170	\N	\N	1
469	15	MONIQUIRA	1	170	\N	\N	1
476	15	MOTAVITA	1	170	\N	\N	1
480	15	MUZO	1	170	\N	\N	1
491	15	NOBSA	1	170	\N	\N	1
494	15	NUEVO COLON	1	170	\N	\N	1
500	15	OICATA	1	170	\N	\N	1
507	15	OTANCHE	1	170	\N	\N	1
511	15	PACHAVITA	1	170	\N	\N	1
514	15	PAEZ	1	170	\N	\N	1
516	15	PAIPA	1	170	\N	\N	1
518	15	PAJARITO	1	170	\N	\N	1
522	15	PANQUEBA	1	170	\N	\N	1
531	15	PAUNA	1	170	\N	\N	1
533	15	PAYA	1	170	\N	\N	1
537	15	PAZ DE RIO	1	170	\N	\N	1
542	15	PESCA	1	170	\N	\N	1
550	15	PISBA	1	170	\N	\N	1
572	15	PUERTO BOYACA	1	170	\N	\N	1
580	15	QUIPAMA	1	170	\N	\N	1
599	15	RAMIRIQUI	1	170	\N	\N	1
600	15	RAQUIRA	1	170	\N	\N	1
621	15	RONDON	1	170	\N	\N	1
632	15	SABOYA	1	170	\N	\N	1
638	15	SACHICA	1	170	\N	\N	1
646	15	SAMACA	1	170	\N	\N	1
660	15	SAN EDUARDO	1	170	\N	\N	1
664	15	SAN JOSE DE PARE	1	170	\N	\N	1
667	15	SAN LUIS DE GACENO	1	170	\N	\N	1
673	15	SAN MATEO	1	170	\N	\N	1
676	15	SAN MIGUEL DE SEMA	1	170	\N	\N	1
681	15	SAN PABLO DE BORBUR	1	170	\N	\N	1
686	15	SANTANA	1	170	\N	\N	1
690	15	SANTA MARIA	1	170	\N	\N	1
693	15	SANTA ROSA DE VITERBO	1	170	\N	\N	1
696	15	SANTA SOFIA	1	170	\N	\N	1
720	15	SATIVANORTE	1	170	\N	\N	1
723	15	SATIVASUR	1	170	\N	\N	1
740	15	SIACHOQUE	1	170	\N	\N	1
753	15	SOATA	1	170	\N	\N	1
755	15	SOCOTA	1	170	\N	\N	1
757	15	SOCHA	1	170	\N	\N	1
759	15	SOGAMOSO	1	170	\N	\N	1
761	15	SOMONDOCO	1	170	\N	\N	1
762	15	SORA	1	170	\N	\N	1
763	15	SOTAQUIRA	1	170	\N	\N	1
764	15	SORACA	1	170	\N	\N	1
774	15	SUSACON	1	170	\N	\N	1
776	15	SUTAMARCHAN	1	170	\N	\N	1
778	15	SUTATENZA	1	170	\N	\N	1
790	15	TASCO	1	170	\N	\N	1
798	15	TENZA	1	170	\N	\N	1
804	15	TIBANA	1	170	\N	\N	1
806	15	TIBASOSA	1	170	\N	\N	1
808	15	TINJACA	1	170	\N	\N	1
810	15	TIPACOQUE	1	170	\N	\N	1
814	15	TOCA	1	170	\N	\N	1
816	15	TOGUI	1	170	\N	\N	1
820	15	TOPAGA	1	170	\N	\N	1
822	15	TOTA	1	170	\N	\N	1
832	15	TUNUNGUA	1	170	\N	\N	1
835	15	TURMEQUE	1	170	\N	\N	1
837	15	TUTA	1	170	\N	\N	1
839	15	TUTAZA	1	170	\N	\N	1
842	15	UMBITA	1	170	\N	\N	1
861	15	VENTAQUEMADA	1	170	\N	\N	1
879	15	VIRACACHA	1	170	\N	\N	1
897	15	ZETAQUIRA	1	170	\N	\N	1
1	17	MANIZALES	1	170	\N	\N	1
13	17	AGUADAS	1	170	\N	\N	1
42	17	ANSERMA	1	170	\N	\N	1
50	17	ARANZAZU	1	170	\N	\N	1
88	17	BELALCAZAR	1	170	\N	\N	1
174	17	CHINCHINA	1	170	\N	\N	1
272	17	FILADELFIA	1	170	\N	\N	1
380	17	LA DORADA	1	170	\N	\N	1
388	17	LA MERCED	1	170	\N	\N	1
433	17	MANZANARES	1	170	\N	\N	1
442	17	MARMATO	1	170	\N	\N	1
444	17	MARQUETALIA	1	170	\N	\N	1
446	17	MARULANDA	1	170	\N	\N	1
486	17	NEIRA	1	170	\N	\N	1
495	17	NORCASIA	1	170	\N	\N	1
513	17	PACORA	1	170	\N	\N	1
524	17	PALESTINA	1	170	\N	\N	1
541	17	PENSILVANIA	1	170	\N	\N	1
614	17	RIOSUCIO	1	170	\N	\N	1
616	17	RISARALDA	1	170	\N	\N	1
653	17	SALAMINA	1	170	\N	\N	1
662	17	SAMANA	1	170	\N	\N	1
665	17	SAN JOSE	1	170	\N	\N	1
777	17	SUPIA	1	170	\N	\N	1
867	17	VICTORIA	1	170	\N	\N	1
873	17	VILLAMARIA	1	170	\N	\N	1
877	17	VITERBO	1	170	\N	\N	1
1	18	FLORENCIA	1	170	\N	\N	1
29	18	ALBANIA	1	170	\N	\N	1
94	18	BELEN DE LOS ANDAQUIES	1	170	\N	\N	1
150	18	CARTAGENA DEL CHAIRA	1	170	\N	\N	1
205	18	CURILLO	1	170	\N	\N	1
247	18	EL DONCELLO	1	170	\N	\N	1
256	18	EL PAUJIL	1	170	\N	\N	1
410	18	LA MONTANITA	1	170	\N	\N	1
460	18	MILAN	1	170	\N	\N	1
479	18	MORELIA	1	170	\N	\N	1
592	18	PUERTO RICO	1	170	\N	\N	1
610	18	SAN JOSE DEL FRAGUA	1	170	\N	\N	1
753	18	SAN VICENTE DEL CAGUAN	1	170	\N	\N	1
756	18	SOLANO	1	170	\N	\N	1
785	18	SOLITA	1	170	\N	\N	1
860	18	VALPARAISO	1	170	\N	\N	1
1	19	POPAYAN	1	170	\N	\N	1
22	19	ALMAGUER	1	170	\N	\N	1
50	19	ARGELIA	1	170	\N	\N	1
75	19	BALBOA	1	170	\N	\N	1
100	19	BOLIVAR	1	170	\N	\N	1
110	19	BUENOS AIRES	1	170	\N	\N	1
130	19	CAJIBIO	1	170	\N	\N	1
137	19	CALDONO	1	170	\N	\N	1
142	19	CALOTO	1	170	\N	\N	1
212	19	CORINTO	1	170	\N	\N	1
256	19	EL TAMBO	1	170	\N	\N	1
290	19	FLORENCIA	1	170	\N	\N	1
300	19	GUACHENE	1	170	\N	\N	1
318	19	GUAPI	1	170	\N	\N	1
355	19	INZA	1	170	\N	\N	1
364	19	JAMBALO	1	170	\N	\N	1
392	19	LA SIERRA	1	170	\N	\N	1
397	19	LA VEGA	1	170	\N	\N	1
418	19	LOPEZ DE MICAY	1	170	\N	\N	1
450	19	MERCADERES	1	170	\N	\N	1
455	19	MIRANDA	1	170	\N	\N	1
473	19	MORALES	1	170	\N	\N	1
513	19	PADILLA	1	170	\N	\N	1
517	19	PAEZ	1	170	\N	\N	1
532	19	PATIA	1	170	\N	\N	1
533	19	PIAMONTE	1	170	\N	\N	1
548	19	PIENDAMO - TUNIA	1	170	\N	\N	1
573	19	PUERTO TEJADA	1	170	\N	\N	1
585	19	PURACE	1	170	\N	\N	1
622	19	ROSAS	1	170	\N	\N	1
693	19	SAN SEBASTIAN	1	170	\N	\N	1
698	19	SANTANDER DE QUILICHAO	1	170	\N	\N	1
701	19	SANTA ROSA	1	170	\N	\N	1
743	19	SILVIA	1	170	\N	\N	1
760	19	SOTARA PAISPAMBA	1	170	\N	\N	1
780	19	SUAREZ	1	170	\N	\N	1
785	19	SUCRE	1	170	\N	\N	1
807	19	TIMBIO	1	170	\N	\N	1
809	19	TIMBIQUI	1	170	\N	\N	1
821	19	TORIBIO	1	170	\N	\N	1
824	19	TOTORO	1	170	\N	\N	1
845	19	VILLA RICA	1	170	\N	\N	1
1	20	VALLEDUPAR	1	170	\N	\N	1
11	20	AGUACHICA	1	170	\N	\N	1
13	20	AGUSTIN CODAZZI	1	170	\N	\N	1
32	20	ASTREA	1	170	\N	\N	1
45	20	BECERRIL	1	170	\N	\N	1
60	20	BOSCONIA	1	170	\N	\N	1
175	20	CHIMICHAGUA	1	170	\N	\N	1
178	20	CHIRIGUANA	1	170	\N	\N	1
228	20	CURUMANI	1	170	\N	\N	1
238	20	EL COPEY	1	170	\N	\N	1
250	20	EL PASO	1	170	\N	\N	1
295	20	GAMARRA	1	170	\N	\N	1
310	20	GONZALEZ	1	170	\N	\N	1
383	20	LA GLORIA	1	170	\N	\N	1
400	20	LA JAGUA DE IBIRICO	1	170	\N	\N	1
443	20	MANAURE BALCON DEL CESAR	1	170	\N	\N	1
517	20	PAILITAS	1	170	\N	\N	1
550	20	PELAYA	1	170	\N	\N	1
570	20	PUEBLO BELLO	1	170	\N	\N	1
614	20	RIO DE ORO	1	170	\N	\N	1
621	20	LA PAZ	1	170	\N	\N	1
710	20	SAN ALBERTO	1	170	\N	\N	1
750	20	SAN DIEGO	1	170	\N	\N	1
770	20	SAN MARTIN	1	170	\N	\N	1
787	20	TAMALAMEQUE	1	170	\N	\N	1
1	23	MONTERIA	1	170	\N	\N	1
68	23	AYAPEL	1	170	\N	\N	1
79	23	BUENAVISTA	1	170	\N	\N	1
90	23	CANALETE	1	170	\N	\N	1
162	23	CERETE	1	170	\N	\N	1
168	23	CHIMA	1	170	\N	\N	1
182	23	CHINU	1	170	\N	\N	1
189	23	CIENAGA DE ORO	1	170	\N	\N	1
300	23	COTORRA	1	170	\N	\N	1
350	23	LA APARTADA	1	170	\N	\N	1
417	23	LORICA	1	170	\N	\N	1
419	23	LOS CORDOBAS	1	170	\N	\N	1
464	23	MOMIL	1	170	\N	\N	1
466	23	MONTELIBANO	1	170	\N	\N	1
500	23	MONITOS	1	170	\N	\N	1
555	23	PLANETA RICA	1	170	\N	\N	1
570	23	PUEBLO NUEVO	1	170	\N	\N	1
574	23	PUERTO ESCONDIDO	1	170	\N	\N	1
580	23	PUERTO LIBERTADOR	1	170	\N	\N	1
586	23	PURISIMA DE LA CONCEPCION	1	170	\N	\N	1
660	23	SAHAGUN	1	170	\N	\N	1
670	23	SAN ANDRES DE SOTAVENTO	1	170	\N	\N	1
672	23	SAN ANTERO	1	170	\N	\N	1
675	23	SAN BERNARDO DEL VIENTO	1	170	\N	\N	1
678	23	SAN CARLOS	1	170	\N	\N	1
682	23	SAN JOSE DE URE	1	170	\N	\N	1
686	23	SAN PELAYO	1	170	\N	\N	1
807	23	TIERRALTA	1	170	\N	\N	1
815	23	TUCHIN	1	170	\N	\N	1
855	23	VALENCIA	1	170	\N	\N	1
1	25	AGUA DE DIOS	1	170	\N	\N	1
19	25	ALBAN	1	170	\N	\N	1
35	25	ANAPOIMA	1	170	\N	\N	1
40	25	ANOLAIMA	1	170	\N	\N	1
53	25	ARBELAEZ	1	170	\N	\N	1
86	25	BELTRAN	1	170	\N	\N	1
95	25	BITUIMA	1	170	\N	\N	1
99	25	BOJACA	1	170	\N	\N	1
120	25	CABRERA	1	170	\N	\N	1
123	25	CACHIPAY	1	170	\N	\N	1
126	25	CAJICA	1	170	\N	\N	1
148	25	CAPARRAPI	1	170	\N	\N	1
151	25	CAQUEZA	1	170	\N	\N	1
154	25	CARMEN DE CARUPA	1	170	\N	\N	1
168	25	CHAGUANI	1	170	\N	\N	1
175	25	CHIA	1	170	\N	\N	1
178	25	CHIPAQUE	1	170	\N	\N	1
181	25	CHOACHI	1	170	\N	\N	1
183	25	CHOCONTA	1	170	\N	\N	1
200	25	COGUA	1	170	\N	\N	1
214	25	COTA	1	170	\N	\N	1
224	25	CUCUNUBA	1	170	\N	\N	1
245	25	EL COLEGIO	1	170	\N	\N	1
258	25	EL PENON	1	170	\N	\N	1
260	25	EL ROSAL	1	170	\N	\N	1
269	25	FACATATIVA	1	170	\N	\N	1
279	25	FOMEQUE	1	170	\N	\N	1
281	25	FOSCA	1	170	\N	\N	1
286	25	FUNZA	1	170	\N	\N	1
288	25	FUQUENE	1	170	\N	\N	1
290	25	FUSAGASUGA	1	170	\N	\N	1
293	25	GACHALA	1	170	\N	\N	1
295	25	GACHANCIPA	1	170	\N	\N	1
297	25	GACHETA	1	170	\N	\N	1
299	25	GAMA	1	170	\N	\N	1
307	25	GIRARDOT	1	170	\N	\N	1
312	25	GRANADA	1	170	\N	\N	1
317	25	GUACHETA	1	170	\N	\N	1
320	25	GUADUAS	1	170	\N	\N	1
322	25	GUASCA	1	170	\N	\N	1
324	25	GUATAQUI	1	170	\N	\N	1
326	25	GUATAVITA	1	170	\N	\N	1
328	25	GUAYABAL DE SIQUIMA	1	170	\N	\N	1
335	25	GUAYABETAL	1	170	\N	\N	1
339	25	GUTIERREZ	1	170	\N	\N	1
368	25	JERUSALEN	1	170	\N	\N	1
372	25	JUNIN	1	170	\N	\N	1
377	25	LA CALERA	1	170	\N	\N	1
386	25	LA MESA	1	170	\N	\N	1
394	25	LA PALMA	1	170	\N	\N	1
398	25	LA PENA	1	170	\N	\N	1
402	25	LA VEGA	1	170	\N	\N	1
407	25	LENGUAZAQUE	1	170	\N	\N	1
426	25	MACHETA	1	170	\N	\N	1
430	25	MADRID	1	170	\N	\N	1
436	25	MANTA	1	170	\N	\N	1
438	25	MEDINA	1	170	\N	\N	1
473	25	MOSQUERA	1	170	\N	\N	1
483	25	NARINO	1	170	\N	\N	1
486	25	NEMOCON	1	170	\N	\N	1
488	25	NILO	1	170	\N	\N	1
489	25	NIMAIMA	1	170	\N	\N	1
491	25	NOCAIMA	1	170	\N	\N	1
506	25	VENECIA	1	170	\N	\N	1
513	25	PACHO	1	170	\N	\N	1
518	25	PAIME	1	170	\N	\N	1
524	25	PANDI	1	170	\N	\N	1
530	25	PARATEBUENO	1	170	\N	\N	1
535	25	PASCA	1	170	\N	\N	1
572	25	PUERTO SALGAR	1	170	\N	\N	1
580	25	PULI	1	170	\N	\N	1
592	25	QUEBRADANEGRA	1	170	\N	\N	1
594	25	QUETAME	1	170	\N	\N	1
596	25	QUIPILE	1	170	\N	\N	1
599	25	APULO	1	170	\N	\N	1
612	25	RICAURTE	1	170	\N	\N	1
645	25	SAN ANTONIO DEL TEQUENDAMA	1	170	\N	\N	1
649	25	SAN BERNARDO	1	170	\N	\N	1
653	25	SAN CAYETANO	1	170	\N	\N	1
658	25	SAN FRANCISCO	1	170	\N	\N	1
662	25	SAN JUAN DE RIOSECO	1	170	\N	\N	1
718	25	SASAIMA	1	170	\N	\N	1
736	25	SESQUILE	1	170	\N	\N	1
740	25	SIBATE	1	170	\N	\N	1
743	25	SILVANIA	1	170	\N	\N	1
745	25	SIMIJACA	1	170	\N	\N	1
754	25	SOACHA	1	170	\N	\N	1
758	25	SOPO	1	170	\N	\N	1
769	25	SUBACHOQUE	1	170	\N	\N	1
772	25	SUESCA	1	170	\N	\N	1
777	25	SUPATA	1	170	\N	\N	1
779	25	SUSA	1	170	\N	\N	1
781	25	SUTATAUSA	1	170	\N	\N	1
785	25	TABIO	1	170	\N	\N	1
793	25	TAUSA	1	170	\N	\N	1
797	25	TENA	1	170	\N	\N	1
799	25	TENJO	1	170	\N	\N	1
805	25	TIBACUY	1	170	\N	\N	1
807	25	TIBIRITA	1	170	\N	\N	1
815	25	TOCAIMA	1	170	\N	\N	1
817	25	TOCANCIPA	1	170	\N	\N	1
823	25	TOPAIPI	1	170	\N	\N	1
839	25	UBALA	1	170	\N	\N	1
841	25	UBAQUE	1	170	\N	\N	1
843	25	VILLA DE SAN DIEGO DE UBATE	1	170	\N	\N	1
845	25	UNE	1	170	\N	\N	1
851	25	UTICA	1	170	\N	\N	1
862	25	VERGARA	1	170	\N	\N	1
867	25	VIANI	1	170	\N	\N	1
871	25	VILLAGOMEZ	1	170	\N	\N	1
873	25	VILLAPINZON	1	170	\N	\N	1
875	25	VILLETA	1	170	\N	\N	1
878	25	VIOTA	1	170	\N	\N	1
885	25	YACOPI	1	170	\N	\N	1
898	25	ZIPACON	1	170	\N	\N	1
899	25	ZIPAQUIRA	1	170	\N	\N	1
1	27	QUIBDO	1	170	\N	\N	1
6	27	ACANDI	1	170	\N	\N	1
25	27	ALTO BAUDO	1	170	\N	\N	1
50	27	ATRATO	1	170	\N	\N	1
73	27	BAGADO	1	170	\N	\N	1
75	27	BAHIA SOLANO	1	170	\N	\N	1
77	27	BAJO BAUDO	1	170	\N	\N	1
99	27	BOJAYA	1	170	\N	\N	1
135	27	EL CANTON DEL SAN PABLO	1	170	\N	\N	1
150	27	CARMEN DEL DARIEN	1	170	\N	\N	1
160	27	CERTEGUI	1	170	\N	\N	1
205	27	CONDOTO	1	170	\N	\N	1
245	27	EL CARMEN DE ATRATO	1	170	\N	\N	1
250	27	EL LITORAL DEL SAN JUAN	1	170	\N	\N	1
361	27	ISTMINA	1	170	\N	\N	1
372	27	JURADO	1	170	\N	\N	1
413	27	LLORO	1	170	\N	\N	1
425	27	MEDIO ATRATO	1	170	\N	\N	1
430	27	MEDIO BAUDO	1	170	\N	\N	1
450	27	MEDIO SAN JUAN	1	170	\N	\N	1
491	27	NOVITA	1	170	\N	\N	1
495	27	NUQUI	1	170	\N	\N	1
580	27	RIO IRO	1	170	\N	\N	1
600	27	RIO QUITO	1	170	\N	\N	1
615	27	RIOSUCIO	1	170	\N	\N	1
660	27	SAN JOSE DEL PALMAR	1	170	\N	\N	1
745	27	SIPI	1	170	\N	\N	1
787	27	TADO	1	170	\N	\N	1
800	27	UNGUIA	1	170	\N	\N	1
810	27	UNION PANAMERICANA	1	170	\N	\N	1
1	41	NEIVA	1	170	\N	\N	1
6	41	ACEVEDO	1	170	\N	\N	1
13	41	AGRADO	1	170	\N	\N	1
16	41	AIPE	1	170	\N	\N	1
20	41	ALGECIRAS	1	170	\N	\N	1
26	41	ALTAMIRA	1	170	\N	\N	1
78	41	BARAYA	1	170	\N	\N	1
132	41	CAMPOALEGRE	1	170	\N	\N	1
206	41	COLOMBIA	1	170	\N	\N	1
244	41	ELIAS	1	170	\N	\N	1
298	41	GARZON	1	170	\N	\N	1
306	41	GIGANTE	1	170	\N	\N	1
319	41	GUADALUPE	1	170	\N	\N	1
349	41	HOBO	1	170	\N	\N	1
357	41	IQUIRA	1	170	\N	\N	1
359	41	ISNOS	1	170	\N	\N	1
378	41	LA ARGENTINA	1	170	\N	\N	1
396	41	LA PLATA	1	170	\N	\N	1
483	41	NATAGA	1	170	\N	\N	1
503	41	OPORAPA	1	170	\N	\N	1
518	41	PAICOL	1	170	\N	\N	1
524	41	PALERMO	1	170	\N	\N	1
530	41	PALESTINA	1	170	\N	\N	1
548	41	PITAL	1	170	\N	\N	1
551	41	PITALITO	1	170	\N	\N	1
615	41	RIVERA	1	170	\N	\N	1
660	41	SALADOBLANCO	1	170	\N	\N	1
668	41	SAN AGUSTIN	1	170	\N	\N	1
676	41	SANTA MARIA	1	170	\N	\N	1
770	41	SUAZA	1	170	\N	\N	1
791	41	TARQUI	1	170	\N	\N	1
797	41	TESALIA	1	170	\N	\N	1
799	41	TELLO	1	170	\N	\N	1
801	41	TERUEL	1	170	\N	\N	1
807	41	TIMANA	1	170	\N	\N	1
872	41	VILLAVIEJA	1	170	\N	\N	1
885	41	YAGUARA	1	170	\N	\N	1
1	44	RIOHACHA	1	170	\N	\N	1
35	44	ALBANIA	1	170	\N	\N	1
78	44	BARRANCAS	1	170	\N	\N	1
90	44	DIBULLA	1	170	\N	\N	1
98	44	DISTRACCION	1	170	\N	\N	1
110	44	EL MOLINO	1	170	\N	\N	1
279	44	FONSECA	1	170	\N	\N	1
378	44	HATONUEVO	1	170	\N	\N	1
420	44	LA JAGUA DEL PILAR	1	170	\N	\N	1
430	44	MAICAO	1	170	\N	\N	1
560	44	MANAURE	1	170	\N	\N	1
650	44	SAN JUAN DEL CESAR	1	170	\N	\N	1
847	44	URIBIA	1	170	\N	\N	1
855	44	URUMITA	1	170	\N	\N	1
874	44	VILLANUEVA	1	170	\N	\N	1
1	47	SANTA MARTA	1	170	\N	\N	1
30	47	ALGARROBO	1	170	\N	\N	1
53	47	ARACATACA	1	170	\N	\N	1
58	47	ARIGUANI	1	170	\N	\N	1
161	47	CERRO DE SAN ANTONIO	1	170	\N	\N	1
170	47	CHIVOLO	1	170	\N	\N	1
189	47	CIENAGA	1	170	\N	\N	1
205	47	CONCORDIA	1	170	\N	\N	1
245	47	EL BANCO	1	170	\N	\N	1
258	47	EL PINON	1	170	\N	\N	1
268	47	EL RETEN	1	170	\N	\N	1
288	47	FUNDACION	1	170	\N	\N	1
318	47	GUAMAL	1	170	\N	\N	1
460	47	NUEVA GRANADA	1	170	\N	\N	1
541	47	PEDRAZA	1	170	\N	\N	1
545	47	PIJINO DEL CARMEN	1	170	\N	\N	1
551	47	PIVIJAY	1	170	\N	\N	1
555	47	PLATO	1	170	\N	\N	1
570	47	PUEBLOVIEJO	1	170	\N	\N	1
605	47	REMOLINO	1	170	\N	\N	1
660	47	SABANAS DE SAN ANGEL	1	170	\N	\N	1
675	47	SALAMINA	1	170	\N	\N	1
692	47	SAN SEBASTIAN DE BUENAVISTA	1	170	\N	\N	1
703	47	SAN ZENON	1	170	\N	\N	1
707	47	SANTA ANA	1	170	\N	\N	1
720	47	SANTA BARBARA DE PINTO	1	170	\N	\N	1
745	47	SITIONUEVO	1	170	\N	\N	1
798	47	TENERIFE	1	170	\N	\N	1
960	47	ZAPAYAN	1	170	\N	\N	1
980	47	ZONA BANANERA	1	170	\N	\N	1
1	50	VILLAVICENCIO	1	170	\N	\N	1
6	50	ACACIAS	1	170	\N	\N	1
110	50	BARRANCA DE UPIA	1	170	\N	\N	1
124	50	CABUYARO	1	170	\N	\N	1
150	50	CASTILLA LA NUEVA	1	170	\N	\N	1
223	50	CUBARRAL	1	170	\N	\N	1
226	50	CUMARAL	1	170	\N	\N	1
245	50	EL CALVARIO	1	170	\N	\N	1
251	50	EL CASTILLO	1	170	\N	\N	1
270	50	EL DORADO	1	170	\N	\N	1
287	50	FUENTEDEORO	1	170	\N	\N	1
313	50	GRANADA	1	170	\N	\N	1
318	50	GUAMAL	1	170	\N	\N	1
325	50	MAPIRIPAN	1	170	\N	\N	1
330	50	MESETAS	1	170	\N	\N	1
350	50	LA MACARENA	1	170	\N	\N	1
370	50	URIBE	1	170	\N	\N	1
400	50	LEJANIAS	1	170	\N	\N	1
450	50	PUERTO CONCORDIA	1	170	\N	\N	1
568	50	PUERTO GAITAN	1	170	\N	\N	1
573	50	PUERTO LOPEZ	1	170	\N	\N	1
577	50	PUERTO LLERAS	1	170	\N	\N	1
590	50	PUERTO RICO	1	170	\N	\N	1
606	50	RESTREPO	1	170	\N	\N	1
680	50	SAN CARLOS DE GUAROA	1	170	\N	\N	1
683	50	SAN JUAN DE ARAMA	1	170	\N	\N	1
686	50	SAN JUANITO	1	170	\N	\N	1
689	50	SAN MARTIN	1	170	\N	\N	1
711	50	VISTAHERMOSA	1	170	\N	\N	1
1	52	PASTO	1	170	\N	\N	1
19	52	ALBAN	1	170	\N	\N	1
22	52	ALDANA	1	170	\N	\N	1
36	52	ANCUYA	1	170	\N	\N	1
51	52	ARBOLEDA	1	170	\N	\N	1
79	52	BARBACOAS	1	170	\N	\N	1
83	52	BELEN	1	170	\N	\N	1
110	52	BUESACO	1	170	\N	\N	1
203	52	COLON	1	170	\N	\N	1
207	52	CONSACA	1	170	\N	\N	1
210	52	CONTADERO	1	170	\N	\N	1
215	52	CORDOBA	1	170	\N	\N	1
224	52	CUASPUD CARLOSAMA	1	170	\N	\N	1
227	52	CUMBAL	1	170	\N	\N	1
233	52	CUMBITARA	1	170	\N	\N	1
240	52	CHACHAGUI	1	170	\N	\N	1
250	52	EL CHARCO	1	170	\N	\N	1
254	52	EL PENOL	1	170	\N	\N	1
256	52	EL ROSARIO	1	170	\N	\N	1
258	52	EL TABLON DE GOMEZ	1	170	\N	\N	1
260	52	EL TAMBO	1	170	\N	\N	1
287	52	FUNES	1	170	\N	\N	1
317	52	GUACHUCAL	1	170	\N	\N	1
320	52	GUAITARILLA	1	170	\N	\N	1
323	52	GUALMATAN	1	170	\N	\N	1
352	52	ILES	1	170	\N	\N	1
354	52	IMUES	1	170	\N	\N	1
356	52	IPIALES	1	170	\N	\N	1
378	52	LA CRUZ	1	170	\N	\N	1
381	52	LA FLORIDA	1	170	\N	\N	1
385	52	LA LLANADA	1	170	\N	\N	1
390	52	LA TOLA	1	170	\N	\N	1
399	52	LA UNION	1	170	\N	\N	1
405	52	LEIVA	1	170	\N	\N	1
411	52	LINARES	1	170	\N	\N	1
418	52	LOS ANDES	1	170	\N	\N	1
427	52	MAGUI	1	170	\N	\N	1
435	52	MALLAMA	1	170	\N	\N	1
473	52	MOSQUERA	1	170	\N	\N	1
480	52	NARINO	1	170	\N	\N	1
490	52	OLAYA HERRERA	1	170	\N	\N	1
506	52	OSPINA	1	170	\N	\N	1
520	52	FRANCISCO PIZARRO	1	170	\N	\N	1
540	52	POLICARPA	1	170	\N	\N	1
560	52	POTOSI	1	170	\N	\N	1
565	52	PROVIDENCIA	1	170	\N	\N	1
573	52	PUERRES	1	170	\N	\N	1
585	52	PUPIALES	1	170	\N	\N	1
612	52	RICAURTE	1	170	\N	\N	1
621	52	ROBERTO PAYAN	1	170	\N	\N	1
678	52	SAMANIEGO	1	170	\N	\N	1
683	52	SANDONA	1	170	\N	\N	1
685	52	SAN BERNARDO	1	170	\N	\N	1
687	52	SAN LORENZO	1	170	\N	\N	1
693	52	SAN PABLO	1	170	\N	\N	1
694	52	SAN PEDRO DE CARTAGO	1	170	\N	\N	1
696	52	SANTA BARBARA	1	170	\N	\N	1
699	52	SANTACRUZ	1	170	\N	\N	1
720	52	SAPUYES	1	170	\N	\N	1
786	52	TAMINANGO	1	170	\N	\N	1
788	52	TANGUA	1	170	\N	\N	1
835	52	SAN ANDRES DE TUMACO	1	170	\N	\N	1
838	52	TUQUERRES	1	170	\N	\N	1
885	52	YACUANQUER	1	170	\N	\N	1
1	54	CUCUTA	1	170	\N	\N	1
3	54	ABREGO	1	170	\N	\N	1
51	54	ARBOLEDAS	1	170	\N	\N	1
99	54	BOCHALEMA	1	170	\N	\N	1
109	54	BUCARASICA	1	170	\N	\N	1
125	54	CACOTA	1	170	\N	\N	1
128	54	CACHIRA	1	170	\N	\N	1
172	54	CHINACOTA	1	170	\N	\N	1
174	54	CHITAGA	1	170	\N	\N	1
206	54	CONVENCION	1	170	\N	\N	1
223	54	CUCUTILLA	1	170	\N	\N	1
239	54	DURANIA	1	170	\N	\N	1
245	54	EL CARMEN	1	170	\N	\N	1
250	54	EL TARRA	1	170	\N	\N	1
261	54	EL ZULIA	1	170	\N	\N	1
313	54	GRAMALOTE	1	170	\N	\N	1
344	54	HACARI	1	170	\N	\N	1
347	54	HERRAN	1	170	\N	\N	1
377	54	LABATECA	1	170	\N	\N	1
385	54	LA ESPERANZA	1	170	\N	\N	1
398	54	LA PLAYA	1	170	\N	\N	1
405	54	LOS PATIOS	1	170	\N	\N	1
418	54	LOURDES	1	170	\N	\N	1
480	54	MUTISCUA	1	170	\N	\N	1
498	54	OCANA	1	170	\N	\N	1
518	54	PAMPLONA	1	170	\N	\N	1
520	54	PAMPLONITA	1	170	\N	\N	1
553	54	PUERTO SANTANDER	1	170	\N	\N	1
599	54	RAGONVALIA	1	170	\N	\N	1
660	54	SALAZAR	1	170	\N	\N	1
670	54	SAN CALIXTO	1	170	\N	\N	1
673	54	SAN CAYETANO	1	170	\N	\N	1
680	54	SANTIAGO	1	170	\N	\N	1
720	54	SARDINATA	1	170	\N	\N	1
743	54	SILOS	1	170	\N	\N	1
800	54	TEORAMA	1	170	\N	\N	1
810	54	TIBU	1	170	\N	\N	1
820	54	TOLEDO	1	170	\N	\N	1
871	54	VILLA CARO	1	170	\N	\N	1
874	54	VILLA DEL ROSARIO	1	170	\N	\N	1
1	63	ARMENIA	1	170	\N	\N	1
111	63	BUENAVISTA	1	170	\N	\N	1
130	63	CALARCA	1	170	\N	\N	1
190	63	CIRCASIA	1	170	\N	\N	1
212	63	CORDOBA	1	170	\N	\N	1
272	63	FILANDIA	1	170	\N	\N	1
302	63	GENOVA	1	170	\N	\N	1
401	63	LA TEBAIDA	1	170	\N	\N	1
470	63	MONTENEGRO	1	170	\N	\N	1
548	63	PIJAO	1	170	\N	\N	1
594	63	QUIMBAYA	1	170	\N	\N	1
690	63	SALENTO	1	170	\N	\N	1
1	66	PEREIRA	1	170	\N	\N	1
45	66	APIA	1	170	\N	\N	1
75	66	BALBOA	1	170	\N	\N	1
88	66	BELEN DE UMBRIA	1	170	\N	\N	1
170	66	DOSQUEBRADAS	1	170	\N	\N	1
318	66	GUATICA	1	170	\N	\N	1
383	66	LA CELIA	1	170	\N	\N	1
400	66	LA VIRGINIA	1	170	\N	\N	1
440	66	MARSELLA	1	170	\N	\N	1
456	66	MISTRATO	1	170	\N	\N	1
572	66	PUEBLO RICO	1	170	\N	\N	1
594	66	QUINCHIA	1	170	\N	\N	1
682	66	SANTA ROSA DE CABAL	1	170	\N	\N	1
687	66	SANTUARIO	1	170	\N	\N	1
1	68	BUCARAMANGA	1	170	\N	\N	1
13	68	AGUADA	1	170	\N	\N	1
20	68	ALBANIA	1	170	\N	\N	1
51	68	ARATOCA	1	170	\N	\N	1
77	68	BARBOSA	1	170	\N	\N	1
79	68	BARICHARA	1	170	\N	\N	1
81	68	BARRANCABERMEJA	1	170	\N	\N	1
92	68	BETULIA	1	170	\N	\N	1
101	68	BOLIVAR	1	170	\N	\N	1
121	68	CABRERA	1	170	\N	\N	1
132	68	CALIFORNIA	1	170	\N	\N	1
147	68	CAPITANEJO	1	170	\N	\N	1
152	68	CARCASI	1	170	\N	\N	1
160	68	CEPITA	1	170	\N	\N	1
162	68	CERRITO	1	170	\N	\N	1
167	68	CHARALA	1	170	\N	\N	1
169	68	CHARTA	1	170	\N	\N	1
176	68	CHIMA	1	170	\N	\N	1
179	68	CHIPATA	1	170	\N	\N	1
190	68	CIMITARRA	1	170	\N	\N	1
207	68	CONCEPCION	1	170	\N	\N	1
209	68	CONFINES	1	170	\N	\N	1
211	68	CONTRATACION	1	170	\N	\N	1
217	68	COROMORO	1	170	\N	\N	1
229	68	CURITI	1	170	\N	\N	1
235	68	EL CARMEN DE CHUCURI	1	170	\N	\N	1
245	68	EL GUACAMAYO	1	170	\N	\N	1
250	68	EL PENON	1	170	\N	\N	1
255	68	EL PLAYON	1	170	\N	\N	1
264	68	ENCINO	1	170	\N	\N	1
266	68	ENCISO	1	170	\N	\N	1
271	68	FLORIAN	1	170	\N	\N	1
276	68	FLORIDABLANCA	1	170	\N	\N	1
296	68	GALAN	1	170	\N	\N	1
298	68	GAMBITA	1	170	\N	\N	1
307	68	GIRON	1	170	\N	\N	1
318	68	GUACA	1	170	\N	\N	1
320	68	GUADALUPE	1	170	\N	\N	1
322	68	GUAPOTA	1	170	\N	\N	1
324	68	GUAVATA	1	170	\N	\N	1
327	68	GUEPSA	1	170	\N	\N	1
344	68	HATO	1	170	\N	\N	1
368	68	JESUS MARIA	1	170	\N	\N	1
370	68	JORDAN	1	170	\N	\N	1
377	68	LA BELLEZA	1	170	\N	\N	1
385	68	LANDAZURI	1	170	\N	\N	1
397	68	LA PAZ	1	170	\N	\N	1
406	68	LEBRIJA	1	170	\N	\N	1
418	68	LOS SANTOS	1	170	\N	\N	1
425	68	MACARAVITA	1	170	\N	\N	1
432	68	MALAGA	1	170	\N	\N	1
444	68	MATANZA	1	170	\N	\N	1
464	68	MOGOTES	1	170	\N	\N	1
468	68	MOLAGAVITA	1	170	\N	\N	1
498	68	OCAMONTE	1	170	\N	\N	1
500	68	OIBA	1	170	\N	\N	1
502	68	ONZAGA	1	170	\N	\N	1
522	68	PALMAR	1	170	\N	\N	1
524	68	PALMAS DEL SOCORRO	1	170	\N	\N	1
533	68	PARAMO	1	170	\N	\N	1
547	68	PIEDECUESTA	1	170	\N	\N	1
549	68	PINCHOTE	1	170	\N	\N	1
572	68	PUENTE NACIONAL	1	170	\N	\N	1
573	68	PUERTO PARRA	1	170	\N	\N	1
575	68	PUERTO WILCHES	1	170	\N	\N	1
615	68	RIONEGRO	1	170	\N	\N	1
655	68	SABANA DE TORRES	1	170	\N	\N	1
669	68	SAN ANDRES	1	170	\N	\N	1
673	68	SAN BENITO	1	170	\N	\N	1
679	68	SAN GIL	1	170	\N	\N	1
682	68	SAN JOAQUIN	1	170	\N	\N	1
684	68	SAN JOSE DE MIRANDA	1	170	\N	\N	1
686	68	SAN MIGUEL	1	170	\N	\N	1
689	68	SAN VICENTE DE CHUCURI	1	170	\N	\N	1
705	68	SANTA BARBARA	1	170	\N	\N	1
720	68	SANTA HELENA DEL OPON	1	170	\N	\N	1
745	68	SIMACOTA	1	170	\N	\N	1
755	68	SOCORRO	1	170	\N	\N	1
770	68	SUAITA	1	170	\N	\N	1
773	68	SUCRE	1	170	\N	\N	1
780	68	SURATA	1	170	\N	\N	1
820	68	TONA	1	170	\N	\N	1
855	68	VALLE DE SAN JOSE	1	170	\N	\N	1
861	68	VELEZ	1	170	\N	\N	1
867	68	VETAS	1	170	\N	\N	1
872	68	VILLANUEVA	1	170	\N	\N	1
895	68	ZAPATOCA	1	170	\N	\N	1
1	70	SINCELEJO	1	170	\N	\N	1
110	70	BUENAVISTA	1	170	\N	\N	1
124	70	CAIMITO	1	170	\N	\N	1
204	70	COLOSO	1	170	\N	\N	1
215	70	COROZAL	1	170	\N	\N	1
221	70	COVENAS	1	170	\N	\N	1
230	70	CHALAN	1	170	\N	\N	1
233	70	EL ROBLE	1	170	\N	\N	1
235	70	GALERAS	1	170	\N	\N	1
265	70	GUARANDA	1	170	\N	\N	1
400	70	LA UNION	1	170	\N	\N	1
418	70	LOS PALMITOS	1	170	\N	\N	1
429	70	MAJAGUAL	1	170	\N	\N	1
473	70	MORROA	1	170	\N	\N	1
508	70	OVEJAS	1	170	\N	\N	1
523	70	PALMITO	1	170	\N	\N	1
670	70	SAMPUES	1	170	\N	\N	1
678	70	SAN BENITO ABAD	1	170	\N	\N	1
702	70	SAN JUAN DE BETULIA	1	170	\N	\N	1
708	70	SAN MARCOS	1	170	\N	\N	1
713	70	SAN ONOFRE	1	170	\N	\N	1
717	70	SAN PEDRO	1	170	\N	\N	1
742	70	SAN LUIS DE SINCE	1	170	\N	\N	1
771	70	SUCRE	1	170	\N	\N	1
820	70	SANTIAGO DE TOLU	1	170	\N	\N	1
823	70	SAN JOSE DE TOLUVIEJO	1	170	\N	\N	1
1	73	IBAGUE	1	170	\N	\N	1
24	73	ALPUJARRA	1	170	\N	\N	1
26	73	ALVARADO	1	170	\N	\N	1
30	73	AMBALEMA	1	170	\N	\N	1
43	73	ANZOATEGUI	1	170	\N	\N	1
55	73	ARMERO	1	170	\N	\N	1
67	73	ATACO	1	170	\N	\N	1
124	73	CAJAMARCA	1	170	\N	\N	1
148	73	CARMEN DE APICALA	1	170	\N	\N	1
152	73	CASABIANCA	1	170	\N	\N	1
168	73	CHAPARRAL	1	170	\N	\N	1
200	73	COELLO	1	170	\N	\N	1
217	73	COYAIMA	1	170	\N	\N	1
226	73	CUNDAY	1	170	\N	\N	1
236	73	DOLORES	1	170	\N	\N	1
268	73	ESPINAL	1	170	\N	\N	1
270	73	FALAN	1	170	\N	\N	1
275	73	FLANDES	1	170	\N	\N	1
283	73	FRESNO	1	170	\N	\N	1
319	73	GUAMO	1	170	\N	\N	1
347	73	HERVEO	1	170	\N	\N	1
349	73	HONDA	1	170	\N	\N	1
352	73	ICONONZO	1	170	\N	\N	1
408	73	LERIDA	1	170	\N	\N	1
411	73	LIBANO	1	170	\N	\N	1
443	73	SAN SEBASTIAN DE MARIQUITA	1	170	\N	\N	1
449	73	MELGAR	1	170	\N	\N	1
461	73	MURILLO	1	170	\N	\N	1
483	73	NATAGAIMA	1	170	\N	\N	1
504	73	ORTEGA	1	170	\N	\N	1
520	73	PALOCABILDO	1	170	\N	\N	1
547	73	PIEDRAS	1	170	\N	\N	1
555	73	PLANADAS	1	170	\N	\N	1
563	73	PRADO	1	170	\N	\N	1
585	73	PURIFICACION	1	170	\N	\N	1
616	73	RIOBLANCO	1	170	\N	\N	1
622	73	RONCESVALLES	1	170	\N	\N	1
624	73	ROVIRA	1	170	\N	\N	1
671	73	SALDANA	1	170	\N	\N	1
675	73	SAN ANTONIO	1	170	\N	\N	1
678	73	SAN LUIS	1	170	\N	\N	1
686	73	SANTA ISABEL	1	170	\N	\N	1
770	73	SUAREZ	1	170	\N	\N	1
854	73	VALLE DE SAN JUAN	1	170	\N	\N	1
861	73	VENADILLO	1	170	\N	\N	1
870	73	VILLAHERMOSA	1	170	\N	\N	1
873	73	VILLARRICA	1	170	\N	\N	1
1	76	CALI	1	170	\N	\N	1
20	76	ALCALA	1	170	\N	\N	1
36	76	ANDALUCIA	1	170	\N	\N	1
41	76	ANSERMANUEVO	1	170	\N	\N	1
54	76	ARGELIA	1	170	\N	\N	1
100	76	BOLIVAR	1	170	\N	\N	1
109	76	BUENAVENTURA	1	170	\N	\N	1
111	76	GUADALAJARA DE BUGA	1	170	\N	\N	1
113	76	BUGALAGRANDE	1	170	\N	\N	1
122	76	CAICEDONIA	1	170	\N	\N	1
126	76	CALIMA	1	170	\N	\N	1
130	76	CANDELARIA	1	170	\N	\N	1
147	76	CARTAGO	1	170	\N	\N	1
233	76	DAGUA	1	170	\N	\N	1
243	76	EL AGUILA	1	170	\N	\N	1
246	76	EL CAIRO	1	170	\N	\N	1
248	76	EL CERRITO	1	170	\N	\N	1
250	76	EL DOVIO	1	170	\N	\N	1
275	76	FLORIDA	1	170	\N	\N	1
306	76	GINEBRA	1	170	\N	\N	1
318	76	GUACARI	1	170	\N	\N	1
364	76	JAMUNDI	1	170	\N	\N	1
377	76	LA CUMBRE	1	170	\N	\N	1
400	76	LA UNION	1	170	\N	\N	1
403	76	LA VICTORIA	1	170	\N	\N	1
497	76	OBANDO	1	170	\N	\N	1
520	76	PALMIRA	1	170	\N	\N	1
563	76	PRADERA	1	170	\N	\N	1
606	76	RESTREPO	1	170	\N	\N	1
616	76	RIOFRIO	1	170	\N	\N	1
622	76	ROLDANILLO	1	170	\N	\N	1
670	76	SAN PEDRO	1	170	\N	\N	1
736	76	SEVILLA	1	170	\N	\N	1
823	76	TORO	1	170	\N	\N	1
828	76	TRUJILLO	1	170	\N	\N	1
834	76	TULUA	1	170	\N	\N	1
845	76	ULLOA	1	170	\N	\N	1
863	76	VERSALLES	1	170	\N	\N	1
869	76	VIJES	1	170	\N	\N	1
890	76	YOTOCO	1	170	\N	\N	1
892	76	YUMBO	1	170	\N	\N	1
895	76	ZARZAL	1	170	\N	\N	1
1	81	ARAUCA	1	170	\N	\N	1
65	81	ARAUQUITA	1	170	\N	\N	1
220	81	CRAVO NORTE	1	170	\N	\N	1
300	81	FORTUL	1	170	\N	\N	1
591	81	PUERTO RONDON	1	170	\N	\N	1
736	81	SARAVENA	1	170	\N	\N	1
794	81	TAME	1	170	\N	\N	1
1	85	YOPAL	1	170	\N	\N	1
10	85	AGUAZUL	1	170	\N	\N	1
15	85	CHAMEZA	1	170	\N	\N	1
125	85	HATO COROZAL	1	170	\N	\N	1
136	85	LA SALINA	1	170	\N	\N	1
139	85	MANI	1	170	\N	\N	1
162	85	MONTERREY	1	170	\N	\N	1
225	85	NUNCHIA	1	170	\N	\N	1
230	85	OROCUE	1	170	\N	\N	1
250	85	PAZ DE ARIPORO	1	170	\N	\N	1
263	85	PORE	1	170	\N	\N	1
279	85	RECETOR	1	170	\N	\N	1
300	85	SABANALARGA	1	170	\N	\N	1
315	85	SACAMA	1	170	\N	\N	1
325	85	SAN LUIS DE PALENQUE	1	170	\N	\N	1
400	85	TAMARA	1	170	\N	\N	1
410	85	TAURAMENA	1	170	\N	\N	1
430	85	TRINIDAD	1	170	\N	\N	1
440	85	VILLANUEVA	1	170	\N	\N	1
1	86	MOCOA	1	170	\N	\N	1
219	86	COLON	1	170	\N	\N	1
320	86	ORITO	1	170	\N	\N	1
568	86	PUERTO ASIS	1	170	\N	\N	1
569	86	PUERTO CAICEDO	1	170	\N	\N	1
571	86	PUERTO GUZMAN	1	170	\N	\N	1
573	86	PUERTO LEGUIZAMO	1	170	\N	\N	1
749	86	SIBUNDOY	1	170	\N	\N	1
755	86	SAN FRANCISCO	1	170	\N	\N	1
757	86	SAN MIGUEL	1	170	\N	\N	1
760	86	SANTIAGO	1	170	\N	\N	1
865	86	VALLE DEL GUAMUEZ	1	170	\N	\N	1
885	86	VILLAGARZON	1	170	\N	\N	1
1	88	SAN ANDRES	1	170	\N	\N	1
564	88	PROVIDENCIA	1	170	\N	\N	1
1	91	LETICIA	1	170	\N	\N	1
263	91	EL ENCANTO (ANM)	1	170	\N	\N	1
405	91	LA CHORRERA (ANM)	1	170	\N	\N	1
407	91	LA PEDRERA (ANM)	1	170	\N	\N	1
430	91	LA VICTORIA (ANM)	1	170	\N	\N	1
460	91	MIRITI - PARANA (ANM)	1	170	\N	\N	1
530	91	PUERTO ALEGRIA (ANM)	1	170	\N	\N	1
536	91	PUERTO ARICA (ANM)	1	170	\N	\N	1
540	91	PUERTO NARINO	1	170	\N	\N	1
669	91	PUERTO SANTANDER (ANM)	1	170	\N	\N	1
798	91	TARAPACA (ANM)	1	170	\N	\N	1
1	94	INIRIDA	1	170	\N	\N	1
343	94	BARRANCOMINAS	1	170	\N	\N	1
883	94	SAN FELIPE (ANM)	1	170	\N	\N	1
884	94	PUERTO COLOMBIA (ANM)	1	170	\N	\N	1
885	94	LA GUADALUPE (ANM)	1	170	\N	\N	1
886	94	CACAHUAL (ANM)	1	170	\N	\N	1
887	94	PANA PANA (ANM)	1	170	\N	\N	1
888	94	MORICHAL (ANM)	1	170	\N	\N	1
1	95	SAN JOSE DEL GUAVIARE	1	170	\N	\N	1
15	95	CALAMAR	1	170	\N	\N	1
25	95	EL RETORNO	1	170	\N	\N	1
200	95	MIRAFLORES	1	170	\N	\N	1
1	97	MITU	1	170	\N	\N	1
161	97	CARURU	1	170	\N	\N	1
511	97	PACOA (ANM)	1	170	\N	\N	1
666	97	TARAIRA	1	170	\N	\N	1
777	97	PAPUNAHUA (ANM)	1	170	\N	\N	1
889	97	YAVARATE (ANM)	1	170	\N	\N	1
1	99	PUERTO CARRENO	1	170	\N	\N	1
524	99	LA PRIMAVERA	1	170	\N	\N	1
624	99	SANTA ROSALIA	1	170	\N	\N	1
773	99	CUMARIBO	1	170	\N	\N	1
\.


--
-- Data for Name: par_serv_servicios; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.par_serv_servicios (par_serv_secue, par_serv_codigo, par_serv_nombre, par_serv_estado) FROM stdin;
2	2	ALCANTARILLADO	A
3	3	ASEO	A
4	4	ENERGIA ELECTRICA	A
5	5	GAS NATURAL	A
6	6	TELEFONIA	A
7	7	GAS LICUADO DEL PETROLEO	A
8	8	SERVICIOS NO COMPETENTES 	A
9	9	CONSOLIDADO	\N
1	1	NOTIFIQUESE	A
\.


--
-- Data for Name: prestamo; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.prestamo (pres_id, radi_nume_radi, usua_login_actu, depe_codi, usua_login_pres, pres_desc, pres_fech_pres, pres_fech_devo, pres_fech_pedi, pres_estado, pres_requerimiento, pres_depe_arch, pres_fech_venc, dev_desc, pres_fech_canc, usua_login_canc, usua_login_rx, sgd_exp_numero) FROM stdin;
\.


--
-- Data for Name: projects; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.projects (id, name, description, name_show, name_dev, samples, style, where_sql, proyecto, activo, style_color, proceso, etapa) FROM stdin;
\.


--
-- Data for Name: radicado; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.radicado (id, radi_nume_radi, radi_fech_radi, fech_alertarad, tdoc_codi, trte_codi, mrec_codi, eesp_codi, radi_fech_ofic, tdid_codi, radi_pais, muni_codi, cpob_codi, carp_codi, esta_codi, dpto_codi, cen_muni_codi, cen_dpto_codi, radi_nume_hoja, radi_desc_anex, radi_nume_deri, radi_path, radi_usua_actu, radi_depe_actu, ra_asun, radi_usu_ante, radi_depe_radi, radi_usua_radi, codi_nivel, flag_nivel, carp_per, radi_leido, radi_cuentai, radi_tipo_deri, listo, sgd_tma_codigo, sgd_mtd_codigo, par_serv_secue, sgd_fld_codigo, radi_agend, radi_fech_agend, radi_fech_doc, sgd_doc_secuencia, sgd_pnufe_codi, sgd_eanu_codigo, sgd_not_codi, radi_fech_notif, sgd_tdec_codigo, sgd_apli_codi, sgd_ttr_codigo, usua_doc_ante, radi_fech_antetx, sgd_trad_codigo, fech_vcmto, tdoc_vcmto, sgd_termino_real, id_cont, sgd_spub_codigo, id_pais, radi_nrr, medio_m, depe_codi, radi_nume_folio, radi_nume_anexo, radi_nume_guia, radi_nume_iden, sgd_rad_codigoverificacion, radi_nume_acapella, radicador_id, id_tercero, id_contacto, medio_id, origen, referenciados, intencion, sender_id, target_id, area_sender_id, area_target_id, numero_radicado, id_radicado, esta_fisico, radi_dato_001, radi_dato_002, eotra_codi, radi_arch1, radi_arch2, radi_arch3, radi_arch4, radi_dire_corr, radi_fech_asig, radi_nomb, radi_prim_apel, radi_rem, radi_segu_apel, radi_tele_cont, radi_tipo_empr, radi_usua_radiori, radi_imagen_hash, meta_datos) FROM stdin;
162	20179000000012	2017-06-09 18:25:32.765227	\N	1	0	1	0	2017-06-09 00:00:00	0	\N	\N	\N	0	\N	\N	\N	\N	\N		0	\N	1	900	RADICADO DE PUREBAS GENERADO POR CORRELIBRE 	\N	900	1	5	\N	0	1		0	\N	\N	\N	\N	0	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	2017-06-16 00:00:00	\N	\N	1	0	170	0	\N	900	\N	\N		\N	c86ca	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	0			\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
\.


--
-- Data for Name: series; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.series (depe_codi, seri_nume, seri_tipo, seri_ano, dpto_codi, bloq) FROM stdin;
\.


--
-- Data for Name: sgd_acl; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_acl (id, profile_id, hierarchy) FROM stdin;
1	1	1
2	3	1.1
3	6	1.1.1
4	8	1.1.2
5	9	1.1.3
6	10	1.1.4
7	11	1.1.5
8	12	1.1.6
9	4	1.2
10	6	1.2.1
11	9	1.2.2
12	11	1.2.3
13	5	1.3
14	9	1.3.1
15	11	1.3.2
16	2	2
17	3	2.1
18	6	2.1.1
19	7	2.1.2
20	8	2.1.3
21	9	2.1.4
22	10	2.1.5
23	11	2.1.6
24	12	2.1.7
25	4	2.2
26	6	2.2.1
27	7	2.2.2
28	11	2.2.3
29	5	2.3
30	7	2.3.1
31	11	2.3.2
\.


--
-- Data for Name: sgd_acl_profiles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_acl_profiles (id, name) FROM stdin;
1	is_archived
2	is_in_management
3	is_public
4	is_private
5	is_confidential
6	is_trd_avalible
7	is_owner
8	is_informed
9	is_previous_owner
10	is_owner_of_exp
11	is_a_root_user
12	is_user_has_higher_level
\.


--
-- Data for Name: sgd_acm_acusemsg; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_acm_acusemsg (sgd_msg_codi, usua_doc, sgd_msg_leido) FROM stdin;
\.


--
-- Data for Name: sgd_agen_agendados; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_agen_agendados (id, sgd_agen_fech, sgd_agen_observacion, radi_nume_radi, usua_doc, depe_codi, sgd_agen_codigo, sgd_agen_fechplazo, sgd_agen_activo) FROM stdin;
\.


--
-- Data for Name: sgd_anar_anexarg; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_anar_anexarg (sgd_anar_codi, anex_codigo, sgd_argd_codi, sgd_anar_argcod) FROM stdin;
\.


--
-- Data for Name: sgd_anu_anulados; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_anu_anulados (sgd_anu_id, sgd_anu_desc, radi_nume_radi, sgd_eanu_codi, sgd_anu_sol_fech, sgd_anu_fech, depe_codi, usua_doc, usua_codi, depe_codi_anu, usua_doc_anu, usua_codi_anu, usua_anu_acta, sgd_anu_path_acta, sgd_trad_codigo) FROM stdin;
\.


--
-- Data for Name: sgd_aper_adminperfiles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_aper_adminperfiles (sgd_aper_codigo, sgd_aper_descripcion) FROM stdin;
\.


--
-- Data for Name: sgd_aplfad_plicfunadi; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_aplfad_plicfunadi (sgd_aplfad_codi, sgd_apli_codi, sgd_aplfad_menu, sgd_aplfad_lk1, sgd_aplfad_desc) FROM stdin;
\.


--
-- Data for Name: sgd_apli_aplintegra; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_apli_aplintegra (sgd_apli_codi, sgd_apli_descrip, sgd_apli_lk1desc, sgd_apli_lk1, sgd_apli_lk2desc, sgd_apli_lk2, sgd_apli_lk3desc, sgd_apli_lk3, sgd_apli_lk4desc, sgd_apli_lk4) FROM stdin;
\.


--
-- Data for Name: sgd_aplmen_aplimens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_aplmen_aplimens (sgd_aplmen_codi, sgd_apli_codi, sgd_aplmen_ref, sgd_aplmen_haciaorfeo, sgd_aplmen_desdeorfeo) FROM stdin;
\.


--
-- Data for Name: sgd_aplus_plicusua; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_aplus_plicusua (sgd_aplus_codi, sgd_apli_codi, usua_doc, sgd_trad_codigo, sgd_aplus_prioridad) FROM stdin;
\.


--
-- Data for Name: sgd_arg_pliego; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_arg_pliego (sgd_arg_codigo, sgd_arg_desc) FROM stdin;
\.


--
-- Data for Name: sgd_argd_argdoc; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_argd_argdoc (sgd_argd_codi, sgd_pnufe_codi, sgd_argd_tabla, sgd_argd_tcodi, sgd_argd_tdes, sgd_argd_llist, sgd_argd_campo) FROM stdin;
2	2	sgd_tid_tipdecision	sgd_tid_codi	sgd_tid_desc	Seleccione la desicion	DECISION
3	3	sgd_tid_tipdecision	sgd_tid_codi	sgd_tid_desc	Seleccione la desicion	DECISION
4	4	sgd_tid_tipdecision	sgd_tid_codi	sgd_tid_desc	Seleccione la desicion	DECISION
5	5	sgd_argup_argudoctop	sgd_argup_codi	sgd_argup_desc	Seleccione el argumento	ARGUMENTO
6	5	sgd_sed_sede	sgd_sed_codI	sgd_sed_desc	Seleccione la sede	SEDE
7	6	sgd_tid_tipdecision	sgd_tid_codi	sgd_tid_desc	Seleccione la desicion	DECISION
1	1	sgd_tid_tipdecision	sgd_tid_codi	sgd_tid_desc	Seleccione la desicion	DECISION
\.


--
-- Data for Name: sgd_argup_argudoctop; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_argup_argudoctop (sgd_argup_codi, sgd_argup_desc, sgd_tpr_codigo) FROM stdin;
2	Sin respuesta	34
1	Omitir respuesta de fondo\r\n	34
3	Respuesta extemporanea\r\n	34
\.


--
-- Data for Name: sgd_auditoria; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_auditoria (usua_doc, tipo, tabla, isql, fecha, ip) FROM stdin;
\.


--
-- Data for Name: sgd_camexp_campoexpediente; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_camexp_campoexpediente (sgd_camexp_codigo, sgd_camexp_campo, sgd_parexp_codigo, sgd_camexp_fk, sgd_camexp_tablafk, sgd_camexp_campofk, sgd_camexp_campovalor, sgd_campexp_orden, sgd_camexp_orden) FROM stdin;
1	IDENTIFICADOR_EMPRESA	1	0	\N	\N	\N	1	\N
4	NOMBRE_DE_LA_EMPRESA	2	0	\N	\N	\N	2	\N
5	NOMBRE_DE_LA_EMPRESA	3	0	\N	\N	\N	2	\N
6	nit_de_la_empresa	3	0	\N	\N	\N	1	\N
8	dpto_nomb	4	0	\N	\N	\N	1	\N
9	dpto_codi	4	0	\N	\N	\N	2	\N
7	NOMBRE_DE_LA_EMPRESA	5	0	\N	\N	\N	1	\N
10	IDENTIFICADOR_EMPRESA	6	0	\N	\N	\N	1	\N
11	NOMBRE_DE_LA_EMPRESA	6	0	\N	\N	\N	2	\N
2	NOMBRE_DE_LA_EMPRESA	1	0	\N	\N	\N	2	\N
3	IDENTIFICADOR_EMPRESA	2	0	\N	\N	\N	1	\N
\.


--
-- Data for Name: sgd_carp_descripcion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_carp_descripcion (sgd_carp_depecodi, sgd_carp_tiporad, sgd_carp_descr) FROM stdin;
\.


--
-- Data for Name: sgd_cau_causal; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_cau_causal (sgd_cau_codigo, sgd_cau_descrip) FROM stdin;
2	Instalacion
3	Prestacion
0	---
1	b
\.


--
-- Data for Name: sgd_caux_causales; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_caux_causales (sgd_caux_codigo, radi_nume_radi, sgd_dcau_codigo, sgd_ddca_codigo, sgd_caux_fecha, usua_doc, sgd_cau_codigo, sgd_ddca_ddsgrgdo) FROM stdin;
\.


--
-- Data for Name: sgd_ciu_ciudadano; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_ciu_ciudadano (tdid_codi, sgd_ciu_codigo, sgd_ciu_nombre, sgd_ciu_direccion, sgd_ciu_apell1, sgd_ciu_apell2, sgd_ciu_telefono, sgd_ciu_email, muni_codi, dpto_codi, sgd_ciu_cedula, id_cont, id_pais) FROM stdin;
0	0	No existe	\N	\N	\N	\N	\N	\N	\N	\N	1	170
0	2	PRUEBAS	CRA 9 N 99 99 999	APELLIDO		5719999999	99999@correlibre.org	1	11	99999999	1	170
\.


--
-- Data for Name: sgd_clta_clstarif; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_clta_clstarif (sgd_fenv_codigo, sgd_clta_codser, sgd_tar_codigo, sgd_clta_descrip, sgd_clta_pesdes, sgd_clta_peshast) FROM stdin;
115	1	1	Llevar via mensajero.	0	1000
103	1	1	de 0 a 500 gr	0	500
109	1	2	PRUEBA	500	100
101	1	6	prueba2	1000	1500
101	2	5	prueba2	1500	200
101	1	65	DESDE 500 GRAMOS HASTA 1000 GRAMOS 	500	1000
101	1	5	Envio normal de 1500 a 2000 Gr	1500	2000
101	1	500	DESDE 2500 GRS HASTA 3000 GRS	2500	3000
101	1	20163	DESDE 1000 GRAMOS HASTA 1500 GRAMOS 	1000	1500
101	1	10	DESDE 6000 GRAMO HASTA6 500 GRAMOS 	6000	6500
15	1	15	GRAMOS	1000	2500
101	1	152	DESDE 6000 HASTA 6500 GRAMOS	6000	6500
101	1	25	DE 4000 HASTA 4500	4000	4500
101	1	1	Envio normal de 1 a 500 Gr	1	500
109	1	12	DESDE 1500 GRAMOS HASTA 2000 GRAMOS	1500	2000
20	1	99	Envio Motorizado	5	20
106	1	1	Valor de correo electronico 0	0	500
105	1	10	desde 0 a 100 gr	0	100
102	1	102	desde 0 a 200 gr	0	200
71	1	3	0 A 500 gr	0	500
\.


--
-- Data for Name: sgd_cob_campobliga; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_cob_campobliga (sgd_cob_codi, sgd_cob_desc, sgd_cob_label, sgd_tidm_codi) FROM stdin;
1	NOMBRE	NOMBRE	2
2	DIRECCION	DIR	2
3	MUNICIPIO	MUNI_NOMBRE	2
4	DEPARTAMENTO	DEPTO_NOMBRE	2
5	TIPO	TIPO	2
6	PAIS	PAIS_NOMBRE	2
\.


--
-- Data for Name: sgd_config; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_config (conf_descripcion, conf_nombre, conf_valor, conf_constante, conf_arreglo, conf_imagen) FROM stdin;
Variable que define en que dirección se encuentra la ayuda	url_ayuda	http://proyectos.correlibre.org:81/wiki/	\N	\N	\N
	servidorBirt	http://siim2.infometrika.net:8080/birt/frameset?__report=	\N	\N	\N
Autenticacion LDAP	usrBind	1	\N	\N	\N
	apiFirmaDigital		\N	\N	\N
Autenticacion LDAP	ldapPort	389	\N	\N	\N
Autenticacion LDAP	campoBusqLDAP	samaccountname	\N	\N	\N
	reasigna_requiere_exp	false	\N	\N	\N
Color de Fondo	colorFondo	8cacc1	\N	\N	\N
Dirección fisica de la entidad	entidad_dir	Calle ## No. ## - ## CIUDAD	\N	\N	\N
Bodega de imagenes, ruta que podemos ajustar a un almacenamiento diferente	CONTENT_PATH	/var/www/html/argogpl/bodega/	\N	\N	\N
Ruta para las operaciones con archivos. Esta es la ubicacion de nuestra carpeta orfeo	ABSOL_PATH	/var/www/html/argogpl/	\N	\N	\N
Guarda el codigo de la dependencia de salida por defecto al radicar dcto de salida	entidad_depsal	0	\N	\N	\N
Selecciona el tipo de ambiente instalado, si es desarrollo, pruebas, produccion que es orfeo	ambiente	PRODUCCION	\N	\N	\N
Si esta variable va en 1 mostrara en informacion geneal el menu de Procedimental, resolucion, sector, causal y detalle. en cero Omite este menu	menuAdicional	0	\N	\N	\N
	MODULO_RADICACION_DOCS_ANEXOS	1	\N	\N	\N
Pais Empresa o Entidad	pais	Colombia	\N	\N	\N
Autenticacion LDAP	cadenaBusqLDAP	DC=xxxx,DC=gov,DC=co	\N	\N	\N
Correo Contacto o Administrador del Sistema	administrador	sunombre@dominio.com	\N	\N	\N
Correo electronico envio	passwordCorreoSaliente	elpassworf	\N	\N	\N
Libreria de plantillas	SMARTY_DIR	/var/www/html/argogpl/include/Smarty/libs/	1	\N	\N
Carpeta temporal donde adodb realiza tareas	ADODB_CACHE_DIR	/tmp	\N	\N	\N
Libreria de plantillas	SMARTY_LIBRARY	/var/www/html/argogpl/include/Smarty/libs/Smarty.class.php	1	\N	\N
Directorio de estilos a Usar... Si no se establece una Ruta el sistema usara el que posee por Defecto en el directorio estilos.  orfeo.css para usarlo cree una carpeta con su personalizacion y luego copie el archivo orfeo.css y cambie sus colores.\r\n	ESTILOS_PATH	orfeo	\N	\N	\N
Autenticacion LDAP	dominioLdap	cnsc.net	\N	\N	\N
Correo electronico Entrada	server_name	outlook	\N	\N	\N
Variable en la q se indican los codigos de los Tipos de Radicados que se excluiran o no se permitiran crear por medio de Respuesta en linea.	excluidosRR		\N	\N	\N
Correo electronico envio	puertoSmtp	25	\N	\N	\N
Correo electronico envio	correoSaliente	usuarioEntriad@entidad.gov.co	\N	\N	\N
Correo electronico envio	passwordCorreoSalienteRR	elpassworf	\N	\N	\N
Correo electronico envio	correoSalienteRR	usuarioEntriad@entidad.gov.co	\N	\N	\N
Correo electronico envio	emailRespaldo	usaurioCopiaEmails@correlibre.org	\N	\N	\N
 Cambia los colores del sistema, si se crea otra plantilla: CorrelibreSimpleDash, CorrelibreNavBarUp	theme	CorrelibreSimpleDash	\N	\N	\N
Correo electronico envio	debugPHPMailer	1	\N	\N	\N
Correo electronico Entrada	puerto_mail	993	\N	\N	\N
Correo electronico envio	servidorSmtp	smtp.office365.com	\N	\N	\N
Correo electronico Entrada	servidor_mail	192.168.0.1	\N	\N	\N
	archivado_requiere_exp	true	\N	\N	\N
Correo electronico Entrada	protocolo_mail	imap	\N	\N	\N
Correo electronico Entrada	SMTPSecure	tls	\N	\N	\N
Variables que se usan para la radicacion del correo electronio. Sitio en el que encontramos la libreria pear instalada	PEAR_PATH	/var/www/html/argogpl/pear/	\N	\N	\N
Datos que se usan en el formulario web disponible a los usuarios. Es radicado en la Dependencia	depeRadicaFormularioWeb	900	\N	\N	\N
Esta variable si esta en 1 no discrimina seris por dependencia, todas las deps veran la msma	seriesVistaTodos	1	\N	\N	\N
El Objetivo es que al independizar ADODB de ORFEO, este (ADODB) se pueda actualizar sin causar traumatismos en el resto del codigo de ORFEO. En adelante se utilizara esta variable para hacer referencia donde se encuentre ADODB	ADODB_PATH	/var/www/html/argogpl/include/class/adodb	\N	\N	\N
	depe_codi_territorial		\N	\N	\N
Variable que se usa para enviar correos al radicar o reasignar	enviarMailMovimientos	1	\N	\N	\N
Autenticacion LDAP	ldapServer	192.168.0.2	\N	\N	\N
Datos que se usan en el formulario web disponible a los usuarios. Usuario que Recibe los Documentos Web	usuaRecibeWeb	1	\N	\N	\N
Ruta del servidor Owncloud	dir_owncloud		\N	\N	\N
Nombre a mostrar en alguanas pantallas	entidad_largo	FUNDACION PARA EL DESARROLLO DEL CONOCIMIENTO LIBRE	\N	\N	\N
Datos que se usan en el formulario web disponible a los usuarios	secRadicaFormularioWeb	900	\N	\N	\N
Variable de acceso a ORfeo Local	httpOrfeoLocal	http://localhost/orfeocore/	\N	\N	\N
Variable para Activar Tramite conjunto, esta variable cumple la misma funcion de informados pero con mas responsabilidad	varTramiteConjunto	0	\N	\N	\N
	httpOrfeoRemoto	 Por el momento OrfeoGPL no tiene Acceso Por Web Externa.	\N	\N	\N
Procesador de documentos externo, como libreoffice	servProcDocs	127.0.0.1:8000	\N	\N	\N
Nombre de la entidad a mostrar en los pdf y banner de la aplicación	entidad	CORRELIBRE - ARGO PROJECT	\N	\N	\N
Ruta del servidor Owncloud	ruta_owonclod	/var/www/html/owncloud/data/	\N	\N	\N
Imagen, encabezado que se incorpora en el pdf	headerRtaPdf	/sys_img/CNSC.headerPDF.png	\N	\N	1
Imagen, pie de pagina que se incorpora en el pdf	footerRtaPdf	/sys_img/CNSC.footerPDF.png	\N	\N	1
Icono de la aplicación, utilizar archivo .ico de tañamaño 16 x 16	favicon	/sys_img/favicon.ico	\N	\N	1
Numero telefonico	entidad_tel	23423	\N	\N	\N
Variable de la Web Oficial de la Entidad	httpWebOficial	http://www.correlibre.org	\N	\N	\N
Digitos de la Dependencia Minimo 1 maximo 5	digitosDependencia	3	\N	\N	\N
Logo de la entidad	logoEntidad	/sys_img/cc.png	\N	\N	1
Imagen de fondo inicial de la aplicación	background	/sys_img/fondo.jpeg	\N	\N	1
\.


--
-- Data for Name: sgd_csop_coment; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_csop_coment (sgd_csop_id, sgd_sop_id, sgd_csop_coment, usua_codi, depe_codi, sgd_csop_fecha) FROM stdin;
\.


--
-- Data for Name: sgd_dcau_causal; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_dcau_causal (sgd_dcau_codigo, sgd_cau_codigo, sgd_dcau_descrip) FROM stdin;
2	1	Cobro desconocido
3	1	Cobro sin prestacion
4	1	Cobros inoportunos
5	1	Cobros por cruce o fuga
6	1	Desviacion significativa
7	1	Direccion Incorrecta
8	1	Doble cobro
9	1	Estrato incorrecto
10	1	Fraude
11	1	Lectura incorrecta
12	1	Llamadas a celulares
13	1	Llamadas a lineas comerciales
14	1	Llamadas Larga Distancia
15	1	Mala financiacion
16	1	No envio de factura
17	1	Pago no reportado
18	1	Predio desocupado
19	1	Solidaridad
1	1	Clase de uso incorrecto
20	1	Tarifa incorrecta
21	2	Direccion Incorrecta
22	2	Fallas en la instalacion
23	2	Instalacion no solicitada
24	2	Pago sin instalacion
25	3	Sin servicio
26	3	Cruce o fuga
27	3	Sin continuidad
28	3	Interferencia
29	3	No reconectado
30	3	Suspension ilegal
31	3	Traslado
32	3	Cambio de numero
33	3	Desprogramacion de discados
34	3	Telefonos publicos
35	3	Suspension temporal
36	3	Servicio no solicitado
37	3	No es competencia
38	3	Cambio de medidor
39	3	Solicitud de retiro
0	0	---
40	1	Cobro de consumos dejados de facuturar
41	1	Cobros por promedio
42	1	Planes Tarifarios
43	1	Ciclo I
44	1	Cobro por revision
45	1	Unidad Habitacional
\.


--
-- Data for Name: sgd_ddca_ddsgrgdo; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_ddca_ddsgrgdo (sgd_ddca_codigo, sgd_dcau_codigo, par_serv_secue, sgd_ddca_descrip) FROM stdin;
\.


--
-- Data for Name: sgd_def_contactos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_def_contactos (ctt_id, ctt_nombre, ctt_cargo, ctt_telefono, ctt_id_tipo, ctt_id_empresa) FROM stdin;
\.


--
-- Data for Name: sgd_def_continentes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_def_continentes (id_cont, nombre_cont) FROM stdin;
1	America
\.


--
-- Data for Name: sgd_def_paises; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_def_paises (id_pais, id_cont, nombre_pais, id_pais_1) FROM stdin;
170	1	COLOMBIA	\N
\.


--
-- Data for Name: sgd_deve_dev_envio; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_deve_dev_envio (sgd_deve_codigo, sgd_deve_desc) FROM stdin;
1	CASA DESOCUPADA
2	CAMBIO DE DOMICILIO
99	SOBREPASO TIEMPO DE ESPERA
3	CERRADO
4	DESCONOCIDO
5	DEVUELTO DE PORTERIA
6	DIRECCION DEFICIENTE
7	FALLECIDO
8	NO EXISTE NUMERO
9	NO RESIDE
10	NO RECLAMADO
11	REHUSADO
12	SE TRASLADO
13	NO EXISTE EMPRESA
14	ZONA DE ALTO RIESGO
15	SOBRE DESOCUPADO
16	FUERA PERIMETRO URBANO
17	ENVIADO A ADPOSTAL, CONTROL DE CALIDAD
18	SIN SELLO
90	DOCUMENTO MAL RADICADO
\.


--
-- Data for Name: sgd_dir_drecciones; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_dir_drecciones (id, sgd_dir_codigo, sgd_dir_tipo, sgd_oem_codigo, sgd_ciu_codigo, radi_nume_radi, sgd_esp_codi, muni_codi, dpto_codi, sgd_dir_direccion, sgd_dir_telefono, sgd_dir_mail, sgd_sec_codigo, sgd_temporal_nombre, anex_codigo, sgd_anex_codigo, sgd_dir_nombre, sgd_doc_fun, sgd_dir_nomremdes, sgd_trd_codigo, sgd_dir_tdoc, sgd_dir_doc, id_pais, id_cont, id_tercero, id_contacto, departamento_id, ciudad_id, sender_id, target_id, area_sender_id, area_target_id, sgd_dir_apellido, sgd_dir_enviado, meta_datos) FROM stdin;
2	163	1	0	2	20179000000012	\N	1	11	CRA 9 N 99 99 999	5719999999	99999@correlibre.org	0	\N	\N	\N	PRUEBAS	0	6666	0	\N	99999999	170	1	\N	\N	\N	\N	\N	\N	\N	\N	APELLIDO	0	\N
7	168	1	0	2	20199000000042	\N	1	11	CRA 9 N 99 99 999	5719999999	99999@correlibre.org	0	\N	\N	\N	PRUEBAS	0	PRUEBAS APELLIDO 	0	0	99999999	170	1	\N	\N	\N	\N	\N	\N	\N	\N	APELLIDO 	0	\N
\.


--
-- Data for Name: sgd_dnufe_docnufe; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_dnufe_docnufe (sgd_dnufe_codi, sgd_pnufe_codi, sgd_tpr_codigo, sgd_dnufe_label, trte_codi, sgd_dnufe_main, sgd_dnufe_path, sgd_dnufe_gerarq, anex_tipo_codi) FROM stdin;
\.


--
-- Data for Name: sgd_eanu_estanulacion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_eanu_estanulacion (sgd_eanu_desc, sgd_eanu_codi) FROM stdin;
RADICADO EN SOLICITUD DE ANULACION	1
RADICADO ANULADO	2
RADICADO EN SOLICITUD DE REVIVIR	3
RADICADO IMPOSIBLE DE ANULAR	9
\.


--
-- Data for Name: sgd_einv_inventario; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_einv_inventario (sgd_einv_codigo, sgd_depe_nomb, sgd_depe_codi, sgd_einv_expnum, sgd_einv_titulo, sgd_einv_unidad, sgd_einv_fech, sgd_einv_fechfin, sgd_einv_radicados, sgd_einv_folios, sgd_einv_nundocu, sgd_einv_nundocubodega, sgd_einv_caja, sgd_einv_cajabodega, sgd_einv_srd, sgd_einv_nomsrd, sgd_einv_sbrd, sgd_einv_nomsbrd, sgd_einv_retencion, sgd_einv_disfinal, sgd_einv_ubicacion, sgd_einv_observacion) FROM stdin;
\.


--
-- Data for Name: sgd_eit_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_eit_items (sgd_eit_codigo, sgd_eit_cod_padre, sgd_eit_nombre, sgd_eit_sigla, codi_dpto, codi_muni, sgd_eit_archivador, sgd_eit_cajas, sgd_eit_captol, sgd_eit_dpto, sgd_eit_edificio, sgd_eit_estante, sgd_eit_itemsn, sgd_eit_itemso, sgd_eit_muni, sgd_eit_piso, sgd_eit_pisonom, sgd_eit_zona) FROM stdin;
1	0	METROVIVIENDA 	MV	11	1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
2	1	PISO	P	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
3	1	ZONA	Z	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
4	3	ESTANTE 1	EST1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
5	4	ENTREPAñO 1	ENT1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
6	5	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
7	5	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
8	5	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
9	5	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
10	4	ENTREPAñO 2	ENT2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
11	10	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
12	10	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
13	10	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
14	10	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
15	4	ENTREPAñO 3	ENT3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
16	15	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
17	15	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
18	15	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
19	15	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
20	4	ENTREPAñO 4	ENT4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
21	20	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
22	20	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
23	20	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
24	20	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
25	4	ENTREPAñO 5	ENT5	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
26	25	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
27	25	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
28	25	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
29	25	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
30	4	ENTREPAñO 6	ENT6	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
31	30	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
32	30	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
33	30	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
34	30	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
35	3	ESTANTE 2	EST2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
36	35	ENTREPAñO 1	ENT1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
37	36	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
38	36	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
39	36	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
40	36	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
41	35	ENTREPAñO 2	ENT2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
42	41	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
43	41	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
44	41	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
45	41	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
46	35	ENTREPAñO 3	ENT3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
47	46	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
48	46	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
49	46	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
50	46	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
51	35	ENTREPAñO 4	ENT4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
52	51	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
53	51	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
54	51	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
55	51	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
56	35	ENTREPAñO 5	ENT5	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
57	56	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
58	56	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
59	56	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
60	56	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
61	35	ENTREPAñO 6	ENT6	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
62	61	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
63	61	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
64	61	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
65	61	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
66	3	ESTANTE 3	EST3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
67	66	ENTREPAñO 1	ENT1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
68	67	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
69	67	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
70	67	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
71	67	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
72	66	ENTREPAñO 2	ENT2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
73	72	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
74	72	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
75	72	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
76	72	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
77	66	ENTREPAñO 3	ENT3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
78	77	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
79	77	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
80	77	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
81	77	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
82	66	ENTREPAñO 4	ENT4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
83	82	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
84	82	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
85	82	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
86	82	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
87	66	ENTREPAñO 5	ENT5	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
88	87	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
89	87	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
90	87	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
91	87	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
92	66	ENTREPAñO 6	ENT6	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
93	92	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
94	92	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
95	92	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
96	92	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
97	3	ESTANTE 4	EST4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
98	97	ENTREPAñO 1	ENT1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
99	98	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
100	98	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
101	98	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
102	98	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
103	97	ENTREPAñO 2	ENT2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
104	103	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
105	103	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
106	103	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
107	103	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
108	97	ENTREPAñO 3	ENT3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
109	108	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
110	108	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
111	108	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
112	108	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
113	97	ENTREPAñO 4	ENT4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
114	113	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
115	113	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
116	113	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
117	113	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
118	97	ENTREPAñO 5	ENT5	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
119	118	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
120	118	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
121	118	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
122	118	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
123	97	ENTREPAñO 6	ENT6	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
124	123	CAJAS 1	CJ1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
125	123	CAJAS 2	CJ2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
126	123	CAJAS 3	CJ3	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
127	123	CAJAS 4	CJ4	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
\.


--
-- Data for Name: sgd_empus_empusuario; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_empus_empusuario (sgd_empus_codigo, sgd_empus_estado, usua_login, identificador_empresa) FROM stdin;
\.


--
-- Data for Name: sgd_ent_entidades; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_ent_entidades (sgd_ent_nit, sgd_ent_codsuc, sgd_ent_pias, dpto_codi, muni_codi, sgd_ent_descrip, sgd_ent_direccion, sgd_ent_telefono) FROM stdin;
12345	1	\N	11	1	ALCALDIA JJJJ	CRA 5333	33333
\.


--
-- Data for Name: sgd_enve_envioespecial; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_enve_envioespecial (sgd_fenv_codigo, sgd_enve_valorl, sgd_enve_valorn, sgd_enve_desc) FROM stdin;
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
109	1400	3500	Valor Descuento Automatico
109	160	160	Valor Alistamiento
109	1400	3500	Valor Certificado Acuse de Rec
\.


--
-- Data for Name: sgd_estc_estconsolid; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_estc_estconsolid (sgd_estc_codigo, sgd_tpr_codigo, dep_nombre, depe_codi, sgd_estc_ctotal, sgd_estc_centramite, sgd_estc_csinriesgo, sgd_estc_criesgomedio, sgd_estc_criesgoalto, sgd_estc_ctramitados, sgd_estc_centermino, sgd_estc_cfueratermino, sgd_estc_fechgen, sgd_estc_fechini, sgd_estc_fechfin, sgd_estc_fechiniresp, sgd_estc_fechfinresp, sgd_estc_dsinriesgo, sgd_estc_driesgomegio, sgd_estc_driesgoalto, sgd_estc_dtermino, sgd_estc_grupgenerado) FROM stdin;
\.


--
-- Data for Name: sgd_estinst_estadoinstancia; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_estinst_estadoinstancia (sgd_estinst_codi, sgd_apli_codi, sgd_instorf_codi, sgd_estinst_valor, sgd_estinst_habilita, sgd_estinst_mensaje) FROM stdin;
\.


--
-- Data for Name: sgd_exp_expediente; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_exp_expediente (id, sgd_exp_numero, radi_nume_radi, sgd_exp_fech, sgd_exp_fech_mod, depe_codi, usua_codi, usua_doc, sgd_exp_estado, sgd_exp_titulo, sgd_exp_asunto, sgd_exp_carpeta, sgd_exp_ufisica, sgd_exp_isla, sgd_exp_estante, sgd_exp_caja, sgd_exp_fech_arch, sgd_srd_codigo, sgd_sbrd_codigo, sgd_fexp_codigo, sgd_exp_subexpediente, sgd_exp_archivo, sgd_exp_unicon, sgd_exp_fechfin, sgd_exp_folios, sgd_exp_rete, sgd_exp_entrepa, radi_usua_arch, sgd_exp_edificio, sgd_exp_caja_bodega, sgd_exp_carro, sgd_exp_carpeta_bodega, sgd_exp_privado, sgd_exp_cd, sgd_exp_nref, sgd_exp_fechafin, sgd_prd_codigo) FROM stdin;
\.


--
-- Data for Name: sgd_fars_faristas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_fars_faristas (sgd_fars_codigo, sgd_pexp_codigo, sgd_fexp_codigoini, sgd_fexp_codigofin, sgd_fars_diasminimo, sgd_fars_diasmaximo, sgd_fars_desc, sgd_trad_codigo, sgd_srd_codigo, sgd_sbrd_codigo, sgd_fars_tipificacion, sgd_tpr_codigo, sgd_fars_automatico, sgd_fars_rolgeneral, sgd_fars_frmnombre, sgd_fars_frmlink, sgd_fars_frmlinkselect, sgd_fars_frmsql) FROM stdin;
\.


--
-- Data for Name: sgd_fenv_frmenvio; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_fenv_frmenvio (sgd_fenv_codigo, sgd_fenv_descrip, sgd_fenv_estado, sgd_fenv_planilla) FROM stdin;
103	ENTREGA PERSONAL	1	0
102	CERTIFICADO POLO	1	1
106	CORREO ELECTRONICO	1	0
985	ENVIOS - DEVOLUCIONES - NO ENVIOS	1	1
109	CERTIFICADO CON ACUSE 	1	0
71	ENTREGADO CORRECTAMENTE	1	0
115	Mensajero1	1	1
101	CERTIFICADO	1	0
25	CORREO	1	1
20	MOTORIZADO	1	1
15	COM COMETA	1	1
105	DOCUMENTOS INTERNOS	1	0
\.


--
-- Data for Name: sgd_fexp_flujoexpedientes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_fexp_flujoexpedientes (sgd_fexp_codigo, sgd_pexp_codigo, sgd_fexp_orden, sgd_fexp_terminos, sgd_fexp_imagen, sgd_fexp_descrip, sgd_fld_posleft, sgd_fld_postop) FROM stdin;
\.


--
-- Data for Name: sgd_firrad_firmarads; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_firrad_firmarads (sgd_firrad_id, radi_nume_radi, usua_doc, sgd_firrad_firma, sgd_firrad_fecha, sgd_firrad_docsolic, sgd_firrad_fechsolic, sgd_firrad_pk) FROM stdin;
\.


--
-- Data for Name: sgd_fld_flujodoc; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_fld_flujodoc (sgd_fld_codigo, sgd_fld_desc, sgd_tpr_codigo, sgd_fld_imagen, sgd_fld_grupoweb) FROM stdin;
\.


--
-- Data for Name: sgd_fun_funciones; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_fun_funciones (sgd_fun_codigo, sgd_fun_descrip, sgd_fun_fech_ini, sgd_fun_fech_fin) FROM stdin;
\.


--
-- Data for Name: sgd_hfld_histflujodoc; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_hfld_histflujodoc (id, sgd_hfld_codigo, sgd_fexp_codigo, sgd_exp_fechflujoant, sgd_hfld_fech, sgd_exp_numero, radi_nume_radi, usua_doc, usua_codi, depe_codi, sgd_ttr_codigo, sgd_fexp_observa, sgd_hfld_observa, sgd_fars_codigo, sgd_hfld_automatico) FROM stdin;
\.


--
-- Data for Name: sgd_hmtd_hismatdoc; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_hmtd_hismatdoc (sgd_hmtd_codigo, sgd_hmtd_fecha, radi_nume_radi, usua_codi, sgd_hmtd_obse, usua_doc, depe_codi, sgd_mtd_codigo) FROM stdin;
\.


--
-- Data for Name: sgd_instorf_instanciasorfeo; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_instorf_instanciasorfeo (sgd_instorf_codi, sgd_instorf_desc) FROM stdin;
1	Radicacion
2	Informacion General
3	Documentos anexados
4	Transacciones Basicas
10	Envios
5	Radicacion de Documentos Anexados
11	Anulacion
\.


--
-- Data for Name: sgd_lip_linkip; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_lip_linkip (sgd_lip_id, sgd_lip_ipini, sgd_lip_ipfin, sgd_lip_dirintranet, depe_codi, sgd_lip_arch, sgd_lip_diascache, sgd_lip_rutaftp, sgd_lip_servftp, sgd_lip_usbd, sgd_lip_nombd, sgd_lip_pwdbd, sgd_lip_usftp, sgd_lip_pwdftp) FROM stdin;
\.


--
-- Data for Name: sgd_masiva_excel; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_masiva_excel (sgd_masiva_dependencia, sgd_masiva_usuario, sgd_masiva_tiporadicacion, sgd_masiva_codigo, sgd_masiva_radicada, sgd_masiva_intervalo, sgd_masiva_rangoini, sgd_masiva_rangofin) FROM stdin;
\.


--
-- Data for Name: sgd_mat_matriz; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_mat_matriz (sgd_mat_codigo, depe_codi, sgd_fun_codigo, sgd_prc_codigo, sgd_prd_codigo, sgd_mat_fech_ini, sgd_mat_fech_fin, sgd_mat_peso_prd) FROM stdin;
\.


--
-- Data for Name: sgd_mpes_mddpeso; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_mpes_mddpeso (sgd_mpes_codigo, sgd_mpes_descrip) FROM stdin;
1	Arrobas
2	Kilos
3	Gramos
4	Toneladas
5	Libras
\.


--
-- Data for Name: sgd_mrd_matrird; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_mrd_matrird (sgd_mrd_codigo_old, depe_codi, depe_codi_aplica, sgd_srd_codigo, sgd_sbrd_codigo, sgd_tpr_codigo, soporte, sgd_mrd_fechini, sgd_mrd_fechfin, sgd_mrd_esta, sgd_mrd_codigo, sgd_srd_id, sgd_sbrd_id) FROM stdin;
\N	900		900	1	1	1	\N	\N	1	3	2	2
\.


--
-- Data for Name: sgd_msdep_msgdep; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_msdep_msgdep (sgd_msdep_codi, depe_codi, sgd_msg_codi) FROM stdin;
\.


--
-- Data for Name: sgd_msg_mensaje; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_msg_mensaje (sgd_msg_codi, sgd_tme_codi, sgd_msg_desc, sgd_msg_fechdesp, sgd_msg_url, sgd_msg_veces, sgd_msg_ancho, sgd_msg_largo, sgd_msg_etiqueta) FROM stdin;
100	100	Para su información	2014-07-31 00:00:00	http://locahost	0	0	0	Información
105	105	Para hablar conmigo del asunto	2014-08-26 00:00:00	http://localhost	0	0	0	Hablar  Asunto
103	103	Para su consideración y firma	2014-08-26 00:00:00	htpp://localhost	0	0	0	Firma
102	102	Preparar respuesta para firma	2014-07-31 00:00:00	http://localhost	0	0	0	Respuesta firma
106	106	Para tràmite con prioridad	2014-08-26 00:00:00	http://localhost	0	0	0	Prioridad Alta
101	101	Para dar tramite del asunto	2014-07-31 00:00:00	http://localhost	0	0	0	Asunto
104	104	Para comentarios	2014-08-26 00:00:00	http://localhost	0	0	0	Comentarios
107	\N	Por favor realizar la supervision del contrato de prestacion de servicios	2016-02-01 00:00:00	\N	\N	\N	\N	Supervision
108	\N	Por favor revisar el informe de actividades	2016-02-01 00:00:00	\N	\N	\N	\N	Revision
109	\N		2016-12-12 00:00:00	\N	\N	\N	\N	
\.


--
-- Data for Name: sgd_mtd_matriz_doc; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_mtd_matriz_doc (sgd_mtd_codigo, sgd_mat_codigo, sgd_tpr_codigo) FROM stdin;
\.


--
-- Data for Name: sgd_nfn_notifijacion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_nfn_notifijacion (radi_nume_radi, sgd_tdf_codigo, sgd_nfn_fechnotusu, sgd_nfn_fechnotemp, sgd_nfn_fechfiusu, sgd_nfn_fechfiemp, sgd_nfn_fechdesfiusu, sgd_nfn_fechdesfiemp, sgd_nfn_sspdapela) FROM stdin;
\.


--
-- Data for Name: sgd_noh_nohabiles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_noh_nohabiles (noh_fecha) FROM stdin;
2016-01-01 00:00:00
2016-01-11 00:00:00
2016-03-20 00:00:00
2016-03-21 00:00:00
2016-03-24 00:00:00
2016-03-25 00:00:00
2016-03-27 00:00:00
2016-05-01 00:00:00
2016-05-09 00:00:00
2016-05-30 00:00:00
2016-06-06 00:00:00
2016-07-04 00:00:00
2016-07-20 00:00:00
2016-08-07 00:00:00
2016-08-15 00:00:00
2016-10-17 00:00:00
2016-11-07 00:00:00
2016-11-14 00:00:00
2016-12-08 00:00:00
2016-12-25 00:00:00
2017-01-01 00:00:00
2017-01-09 00:00:00
2017-03-20 00:00:00
2017-04-09 00:00:00
2017-04-13 00:00:00
2017-04-14 00:00:00
2017-04-16 00:00:00
2017-05-01 00:00:00
2017-05-29 00:00:00
2017-06-19 00:00:00
2017-06-26 00:00:00
2017-07-03 00:00:00
2017-07-20 00:00:00
2017-08-07 00:00:00
2017-08-21 00:00:00
2017-10-16 00:00:00
2017-11-06 00:00:00
2017-11-13 00:00:00
2017-12-08 00:00:00
2017-12-25 00:00:00
2018-01-01 00:00:00
2018-01-08 00:00:00
2018-03-19 00:00:00
2018-03-25 00:00:00
2018-03-29 00:00:00
2018-03-30 00:00:00
2018-04-01 00:00:00
2018-05-01 00:00:00
2018-05-14 00:00:00
2018-06-04 00:00:00
2018-06-11 00:00:00
2018-07-02 00:00:00
2018-07-20 00:00:00
2018-08-07 00:00:00
2018-08-20 00:00:00
2018-10-15 00:00:00
2018-11-05 00:00:00
2018-11-12 00:00:00
2018-12-08 00:00:00
2018-12-25 00:00:00
2019-01-01 00:00:00
2019-01-07 00:00:00
2019-03-25 00:00:00
2019-04-14 00:00:00
2019-04-18 00:00:00
2019-04-19 00:00:00
2019-04-21 00:00:00
2019-05-01 00:00:00
2019-06-03 00:00:00
2019-06-24 00:00:00
2019-07-01 00:00:00
2019-07-20 00:00:00
2019-08-07 00:00:00
2019-08-19 00:00:00
2019-10-14 00:00:00
2019-11-04 00:00:00
2019-11-11 00:00:00
2019-12-08 00:00:00
2019-12-25 00:00:00
2020-01-01 00:00:00
2020-01-06 00:00:00
2020-03-23 00:00:00
2020-04-05 00:00:00
2020-04-09 00:00:00
2020-04-10 00:00:00
2020-04-12 00:00:00
2020-05-01 00:00:00
2020-05-25 00:00:00
2020-06-15 00:00:00
2020-06-22 00:00:00
2020-06-29 00:00:00
2020-07-20 00:00:00
2020-08-07 00:00:00
2020-08-17 00:00:00
2020-10-12 00:00:00
2020-11-02 00:00:00
2020-11-16 00:00:00
2020-12-08 00:00:00
2020-12-25 00:00:00
\.


--
-- Data for Name: sgd_not_notificacion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_not_notificacion (sgd_not_codi, sgd_not_descrip) FROM stdin;
4	CONDUCTA CONCLUYENTE
1	PERSONAL
3	EDICTO
\.


--
-- Data for Name: sgd_novedad_usuario; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_novedad_usuario (usua_doc, nov_infor, nov_reasig, nov_vobo, nov_dev, nov_entr) FROM stdin;
\.


--
-- Data for Name: sgd_ntrd_notifrad; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_ntrd_notifrad (radi_nume_radi, sgd_not_codi, sgd_ntrd_notificador, sgd_ntrd_notificado, sgd_ntrd_fecha_not, sgd_ntrd_num_edicto, sgd_ntrd_fecha_fija, sgd_ntrd_fecha_desfija, sgd_ntrd_observaciones) FROM stdin;
\.


--
-- Data for Name: sgd_oem_oempresas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_oem_oempresas (sgd_oem_codigo, tdid_codi, sgd_oem_oempresa, sgd_oem_rep_legal, sgd_oem_nit, sgd_oem_sigla, muni_codi, dpto_codi, sgd_oem_direccion, sgd_oem_telefono, id_cont, id_pais, sgd_oem_email) FROM stdin;
0	0	--	\N	\N	\N	\N	\N	\N	\N	1	170	\N
\.


--
-- Data for Name: sgd_panu_peranulados; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_panu_peranulados (sgd_panu_codi, sgd_panu_desc) FROM stdin;
1	PERMISO DE SOLICITUD DE ANULACION 
2	PERMISO ANULACION 
3	PERMISO SOLICITUD Y ANULACION 
\.


--
-- Data for Name: sgd_param_admin; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_param_admin (param_codigo, param_nombre, param_desc, param_valor) FROM stdin;
1	ALERT_FUNCTION	1	1
\.


--
-- Data for Name: sgd_parametro; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_parametro (param_nomb, param_codi, param_valor) FROM stdin;
PRESTAMO_ESTADO	5	Prestamo Indefinido
PRESTAMO_REQUERIMIENTO	1	Documento
PRESTAMO_REQUERIMIENTO	2	Anexo
PRESTAMO_REQUERIMIENTO	3	Documento y Anexo
PRESTAMO_DIAS_PREST	1	8
PRESTAMO_DIAS_CANC	1	2
PRESTAMO_ESTADO	4	Cancelado
PRESTAMO_ESTADO	7	Rechazado
PRESTAMO_ESTADO	3	Devuelto
PRESTAMO_ESTADO	2	Prestado
PRESTAMO_ESTADO	1	Solicitado
PRESTAMO_REQUERIMIENTO	4	Expediente
PRESTAMO_ESTADO	6	DEVOLUCION DE DOCUMENTO
PRESTAMO_PASW	1	0
\.


--
-- Data for Name: sgd_parexp_paramexpediente; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_parexp_paramexpediente (sgd_parexp_codigo, depe_codi, sgd_parexp_tabla, sgd_parexp_etiqueta, sgd_parexp_orden, sgd_parexp_editable, id) FROM stdin;
6	110	''	CC O NIT	2	1	6
7	110	''	ID EXPEDIENTE	3	1	7
9	120	''	NOMBRE EXP	1	1	9
10	120	''	CC O NIT	2	1	10
11	120	''	ID EXPEDIENTE	3	1	11
13	130	''	NOMBRE EXP	1	1	13
14	130	''	CC O NIT	2	1	14
15	130	''	ID EXPEDIENTE	3	1	15
17	200	''	NOMBRE EXP	1	1	17
18	200	''	CC O NIT	2	1	18
19	200	''	ID EXPEDIENTE	3	1	19
21	300	''	NOMBRE EXP	1	1	21
22	300	''	CC O NIT	2	1	22
23	300	''	ID EXPEDIENTE	3	1	23
33	500	''	NOMBRE EXP	1	1	33
34	500	''	CC O NIT	2	1	34
35	500	''	ID EXPEDIENTE	3	1	35
37	600	''	NOMBRE EXP	1	1	37
38	600	''	CC O NIT	2	1	38
39	600	''	ID EXPEDIENTE	3	1	39
45	900	''	NOMBRE EXP	1	1	45
46	900	''	CC O NIT	2	1	46
47	900	''	ID EXPEDIENTE	3	1	47
49	999	'	Patametro1	1	1	49
50	999	'	Parametro2	2	1	50
51	999	'	Parametro3	3	1	51
52	999	'	Parametro4	4	1	52
54	999	'	Parametro6	6	1	54
56	999		Descriptor 9	9	1	56
41	231	''	NOMBRE EXP	1	1	41
42	231	''	CC O NIT	2	1	42
43	231	''	ID EXPEDIENTE	3	1	43
25	232	''	NOMBRE EXP	1	1	25
26	232	''	CC O NIT	2	1	26
27	232	''	ID EXPEDIENTE	3	1	27
97	400		Descriptor 8	5	1	97
76	999		Descriptor 5	5	1	76
122	605		Descriptor 9	5	1	122
123	605		Descriptor 10	5	1	123
124	999		Descriptor 5	5	1	124
128	999		Descriptor 9	5	1	128
131	900		Descriptor 7	7	1	131
132	900		Descriptor 8	8	1	132
1	170		NOMBRE EXP	1	1	1
2	170	''	CC O NIT	2	1	2
3	170	''	ID EXPEDIENTE	3	1	3
5	110	''	NOMBRE EXP	1	1	5
8	110	''	Descriptor	4	1	8
119	140		CC O NIT	2	1	119
120	140		ID EXPEDIENTE	3	1	120
94	201		NOMBRE EXP	1	1	94
95	201		CC O NIT	2	1	95
96	201		ID EXPEDIENTE	3	1	96
88	202		NOMBRE EXP	1	1	88
53	211	'	NOMBRE EXP	1	1	53
55	211		CC O NIT	2	1	55
57	211		ID EXPEDIENTE	3	1	57
89	202		CC O NIT	2	1	89
90	202		ID EXPEDIENTE	3	1	90
91	212		NOMBRE EXP	1	1	91
92	212		CC O NIT	2	1	92
93	212		ID EXPEDIENTE	3	1	93
98	213		NOMBRE EXP	1	1	98
99	213		CC O NIT	2	1	99
112	102		NOMBRE EXP	1	1	112
113	102		CC O NIT	2	1	113
114	102		ID EXPEDIENTE	3	1	114
64	102		DESCRIPTOR	5	1	64
29	214	''	NOMBRE EXP	1	1	29
30	214	''	CC O NIT	2	1	30
31	214	''	ID EXPEDIENTE	3	1	31
103	221		NOMBRE EXP	1	1	103
104	221		CC O NIT	2	1	104
105	221		ID EXPEDIENTE	3	1	105
100	222		NOMBRE EXP	1	1	100
101	222		CC O NIT	2	1	101
102	222		ID EXPEDIENTE	3	1	102
108	223		NOMBRE EXP	1	1	108
109	223		CC O NIT	2	1	109
110	223		ID EXPEDIENTE	3	1	110
125	233		NOMBRE EXP	1	1	125
126	233		CC O NIT	2	1	126
127	233		ID EXPEDIENTE	3	1	127
106	301		NOMBRE EXP	1	1	106
107	301		CC O NIT	2	1	107
111	301		ID EXPEDIENTE	3	1	111
129	320		NOMBRE EXP	1	1	129
130	320		CC O NIT	2	1	130
133	320		ID EXPEDIENTE	3	1	133
77	999		Descriptor 6	5	1	77
78	999		Descriptor 7	5	1	78
79	999		Descriptor 8	5	1	79
115	400		NOMBRE EXP	4	1	115
116	400		CC O NIT	5	1	116
58	100		NOMBRE EXP	1	1	58
59	100		CC O NIT	2	1	59
60	100		ID EXPEDIENTE	3	1	60
61	101		NOMBRE EXP	1	1	61
62	101		CC O NIT	2	1	62
63	101		ID EXPEDIENTE	3	1	63
80	999		Descriptor 9	5	1	80
65	999		Descriptor 6	5	1	65
66	999		Descriptor 7	5	1	66
67	999		Descriptor 8	5	1	67
68	999		Descriptor 9	5	1	68
69	999		Descriptor 10	5	1	69
70	999		Descriptor 5	5	1	70
71	999		Descriptor 6	5	1	71
72	999		Descriptor 7	5	1	72
73	999		Descriptor 8	5	1	73
74	999		Descriptor 9	5	1	74
75	999		Descriptor 10	5	1	75
135	215		 \tCC O NIT	2	1	135
12	120	''	Descriptor	4	1	12
16	130	''	Descriptor	4	1	16
20	200	''	Descriptor	4	1	20
24	300	''	Descriptor	4	1	24
32	420	''	Descriptor	4	1	32
36	500	''	Descriptor	4	1	36
40	600	''	Descriptor	4	1	40
44	605	''	Descriptor	4	1	44
48	900	''	Descriptor	4	1	48
121	141		Descriptor 8	5	1	121
4	170	''	Descriptor	4	1	4
81	999		Descriptor 10	5	1	81
117	400		ID EXPEDIENTE	6	1	117
118	140		NOMBRE EXP	1	1	118
28	213	''	ID EXPEDIENTE	3	1	28
134	215		Nombre Expediente	1	1	134
136	215		ID EXPEDIENTE	3	1	136
\.


--
-- Data for Name: sgd_pexp_procexpedientes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_pexp_procexpedientes (sgd_pexp_codigo, sgd_pexp_descrip, sgd_pexp_terminos, sgd_srd_codigo, sgd_sbrd_codigo, sgd_pexp_automatico, sgd_pexp_tieneflujo) FROM stdin;
0	SIN PROCESO	0	0	0	0	1
\.


--
-- Data for Name: sgd_plan_plantillas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_plan_plantillas (id, plan_path, plan_nombre, plan_fecha, depe_codi, usua_codi, plan_tipo, plan_plantilla) FROM stdin;
1	\N		2014-04-21 19:25:07.2437	900	1	2	
3	\N	Oficio Respuesta	2014-05-26 21:37:06.177353	200	1	3	<p style="text-align: right;">\r\n\tNumero Documetno:RAD_S</p>\r\n<p style="text-align: right;">\r\n\tFecha : F_RAD_S</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t<strong>Bogota, Viernes, 25 de abril de 2014</strong><br />\r\n\t<br />\r\n\t<br />\r\n\tSe&ntilde;or(a)<br />\r\n\t<strong>NATALIA VALENCIA DAVILA</strong></p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t<strong>Ref: Proyecto tres quebradas</strong></p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t<strong>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXX XXXXXX X XXXXXXXX XXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXX&nbsp; <strong>XXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXX XXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXX XXXXXX X XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXX&nbsp;&nbsp;&nbsp; </strong><strong>XXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXX XXXXXX X XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXX&nbsp;&nbsp;&nbsp; </strong></strong><br />\r\n\t<br />\r\n\t&nbsp;</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t<strong>Atentamente</strong></p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><br />\r\n\t<br />\r\n\t&nbsp;</p>\r\n
4	\N	Memorando	2014-05-26 21:37:24.63168	200	1	3	<p>\r\n\t<strong>Bogota, Lunes, 26 de mayo de 2014</strong><br />\r\n\t<br />\r\n\t<br />\r\n\tSe&ntilde;or(a)<br />\r\n\t<strong>NATALIA VALENCIA DAVILA</strong><br />\r\n\t<br />\r\n\t&nbsp;</p>\r\n
17	\N	  CMUPC02-FM01 Comunicación Interna	2014-06-18 20:31:32.359912	900	4	3	<p style="text-align: right;">\r\n\t<strong>*RAD_S*</strong><br />\r\n\tAl contestar por favor cite estos datos:<br />\r\n\tRadicado No.:<strong> RAD_S</strong><br />\r\n\t&nbsp;</p>\r\n<p style="text-align: center;">\r\n\t<style type="text/css">\r\nP { margin-bottom: 0cm; direction: ltr; color: rgb(0, 0, 0); text-align: justify; widows: 2; orphans: 2; }P.western { font-family: "Arial",sans-serif; font-size: 11pt; }P.cjk { font-family: "Times New Roman",serif; font-size: 11pt; }P.ctl { font-family: "Arial",sans-serif; font-size: 10pt; }\t</style>\r\n</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: center;">\r\n\t<strong>MEMORANDO</strong></p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t<br />\r\n\t<strong>Para:</strong>(May&uacute;sculas iniciando sin Negrilla)<br />\r\n\t<br />\r\n\t<strong>De:<br />\r\n\t<br />\r\n\tAsunto</strong>:</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\tPor medio de la presente........<br />\r\n\t<br />\r\n\t<br />\r\n\t<br />\r\n\tCordialmente,<br />\r\n\t<br />\r\n\t<br />\r\n\tNombre de quien dirige el memorando (May&uacute;sculas Iniciando sin negrilla)<br />\r\n\tCargo<br />\r\n\t<br />\r\n\t<br />\r\n\t<br />\r\n\tProyect&oacute;:<br />\r\n\tRevis&oacute;:<br />\r\n\tAnexos:</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: right;">\r\n\t<strong>CMU-PC-02-FM-01-V9</strong></p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n<p class="western" style="margin-left: -0.32cm; text-align: left;">\r\n\t&nbsp;</p>\r\n
18	\N	  CMU-PC-01-FM01 Comunicación Oficial Externa	2014-06-18 20:50:33.278019	900	4	3	<p style="text-align: right;">\r\n\t<strong>*RAD_S*</strong><br />\r\n\tAl contestar por favor cite estos datos:<br />\r\n\tRadicado No.: <strong>RAD_S</strong></p>\r\n<p style="text-align: right;">\r\n\t&nbsp;</p>\r\n<p>\r\n\tBogot&aacute; D.C.<br />\r\n\t<br />\r\n\t<br />\r\n\t<strong>Se&ntilde;or (a):</strong> Nombre propio (May&uacute;sculas in&iacute;ciales y negrilla)&nbsp;&nbsp; &nbsp;<br />\r\n\tCargo<br />\r\n\tEmpresa o Entidad&hellip;<br />\r\n\tDirecci&oacute;n<br />\r\n\tTel&eacute;fono<br />\r\n\tLa ciudad</p>\r\n<p style="text-align: center;">\r\n\t<strong>Referencia:</strong>.............</p>\r\n<p style="text-align: center;">\r\n\t&nbsp;</p>\r\n<p>\r\n\tCordial saludo,</p>\r\n<p>\r\n\tRealizar la descripci&oacute;n del motivo de la comunicaci&oacute;n,..................................</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t<br />\r\n\tAtentamente,<br />\r\n\t<br />\r\n\t<br />\r\n\t<br />\r\n\tNombre<br />\r\n\tCargo<br />\r\n\t<br />\r\n\tProyect&oacute;:<br />\r\n\tRevis&oacute;:<br />\r\n\tAnexos:<br />\r\n\tC.C.: Nombre_______________Cargo________&nbsp; y Direcci&oacute;n ____________</p>\r\n<p style="text-align: right;">\r\n\t<strong>&nbsp;CMU-PC-01-FM-01-V11</strong><br />\r\n\t&nbsp;</p>\r\n
26	\N	RTA TUTELA SRA	2014-06-20 15:58:37.448067	900	4	3	<p style="text-align: right;">\r\n\t<strong>*RAD_S*</strong><br />\r\n\tAl contestar por favor cite estos datos:<br />\r\n\tRadicado No.:<strong> RAD_S</strong></p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p style="text-align: justify;">\r\n\tBogot&aacute; D.C.,<br />\r\n\t<br />\r\n\tSe&ntilde;ores:<br />\r\n\tJUZGADO<br />\r\n\tDireccion<br />\r\n\tTel&eacute;fono<br />\r\n\tCiudad<br />\r\n\t<br />\r\n\t<strong>Referencia:&nbsp;</strong>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Oficio:&nbsp;&nbsp;</strong> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Accionante:</strong>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Demandados:</strong>&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Asunto</strong>:&nbsp;&nbsp; &nbsp;CONTESTACI&Oacute;N ACCI&Oacute;N DE TUTELA METROVIVIENDA.<br />\r\n\t<strong>Radicado</strong>:&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<br />\r\n\t<br />\r\n\t<br />\r\n\t<strong>ILVA NUBIA HERRERA G&Aacute;LVEZ</strong>, mayor de edad, vecina y residente en esta ciudad, identificada con la c&eacute;dula de ciudadan&iacute;a No. 41.780.295 de Bogot&aacute;, en mi calidad de Directora Jur&iacute;dica de Metrovivienda, nombrada mediante Resoluci&oacute;n&nbsp; No. 120&nbsp; del 29 de octubre de 2013, debidamente posesionada mediante Acta No. 79 del primero de noviembre de 2013, con funciones de Representaci&oacute;n Legal de <strong>METROVIVIENDA</strong>, persona jur&iacute;dica de derecho p&uacute;blico, Empresa y Industrial y Comercial del Orden Distrital, creada por el Acuerdo 15 de 1998, vinculada a la Secretar&iacute;a Distrital del H&aacute;bitat de la Alcald&iacute;a Mayor de Bogot&aacute;, mediante Acuerdo 257 del 30 de noviembre de 2006 del Concejo de Bogot&aacute;, dentro del t&eacute;rmino de dos (2) d&iacute;as concedidos por el despacho, procedo a dar respuesta a la acci&oacute;n de tutela se&ntilde;alada en la referencia, pronunci&aacute;ndome&nbsp; en relaci&oacute;n con los hechos que son materia de la misma,&nbsp; bajo el marco de competencia de la Entidad, por cuanto podemos informar que por las razones que expondremos&nbsp; en la presente, no es Metrovivienda la entidad Distrital actualmente responsable de administrar y otorgar los recursos del Subsidio Distrital de Vivienda, siendo esta la Secretar&iacute;a Distrital del H&aacute;bitat quien inscribe, postula, califica, asigna y entrega los recursos del subsidio a los hogares que seg&uacute;n la reglamentaci&oacute;n pueden acceder al Subsidio Distrital de Vivienda.<br />\r\n\t<br />\r\n\tSobre el particular procedo ante el Despacho de conocimiento a proponer de manera previa la siguiente:</p>\r\n<p style="text-align: center;">\r\n\t<u><strong>EXCEPCI&Oacute;N DE M&Eacute;RITO</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tEn primera instancia es importante indicar que en visi&oacute;n de la accionante los hechos por ella se&ntilde;alados derivan en una supuesta amenaza o vulneraci&oacute;n a los siguientes derechos fundamentales: (1) A la Igualdad y no discriminaci&oacute;n ante la Ley (art. 13); (2) Derecho de Petici&oacute;n (art. 23), (3) al M&iacute;nimo Vital,&nbsp; (5) A la vida en conexidad a la dignidad humana, (6) (7) protecci&oacute;n especial a la familia, (8) protecci&oacute;n especial a las mujeres, ni&ntilde;os y ancianos, frente a los cuales plantea carencia de acciones tendientes a la estabilizaci&oacute;n de su situaci&oacute;n, hechos estos que no encuentran relaci&oacute;n con la competencia de Metrovivienda, tal como se explicar&aacute; en detalle en apartados posteriores.<br />\r\n\t<br />\r\n\tEn ese orden de ideas, interpongo al Despacho judicial <strong>FALTA DE LEGITIMACI&Oacute;N EN LA CAUSA POR PASIVA</strong>, toda vez que en relaci&oacute;n con ninguno de los hechos narrados, Metrovivienda en la actualidad tiene dentro de sus competencias&nbsp; la posibilidad de brindar&nbsp; estabilizaci&oacute;n socioecon&oacute;mica, y&nbsp; especificamos que en cuanto&nbsp; al otorgamiento de Subsidios de Vivienda adem&aacute;s de que a la fecha esta entidad no tiene competencia para otorgar Subsidios Distritales de Vivienda, como se narrar&aacute;&nbsp; en apartados posteriores, la presunta vulneraci&oacute;n radica en el hecho de no haber obtenido Subsidio Familiar de Vivienda tramitado ante una caja de compensaci&oacute;n familiar y otorgado por Fonvivienda, requisito sin el cual es imposible normativamente la asignaci&oacute;n de un Subsidio Distrital de Vivienda<u>, esto &uacute;ltimo se reitera, no siendo ya de competencia de esta entidad puesto que mediante el Decreto Distrital 583 de 2007 se le asign&oacute; a la Secretar&iacute;a Distrital del H&aacute;bitat la competencia para su otorgamiento y asignaci&oacute;n</u>, funci&oacute;n que se le hab&iacute;a otorgado a Metrovivienda a trav&eacute;s de los Decretos Distritales 226 de 2005 y 200 de 2006, derogados por el Decreto Distrital 063 de 2009, a su vez derogado por el Decreto Distrital 539 del 23 de noviembre de 2012, por lo tanto y de acuerdo al ordenamiento jur&iacute;dico de creaci&oacute;n de la entidad, esta tiene como prop&oacute;sito esencial asuntos diferentes a los alegados por el actor de tutela, siendo ellos:</p>\r\n<p style="text-align: justify;">\r\n\t&ldquo;<em>...A. Promover la oferta masiva de suelo urbano para facilitar la ejecuci&oacute;n de Proyectos Integrales de Vivienda de Inter&eacute;s Social.<br />\r\n\tB. Desarrollar las funciones propias de los bancos de tierras o bancos inmobiliarios, respecto de inmuebles destinados en particular para la ejecuci&oacute;n de proyectos urban&iacute;sticos que contemplen la provisi&oacute;n de Vivienda de Inter&eacute;s Social Prioritaria.<br />\r\n\tC. Promover la organizaci&oacute;n comunitaria de familias de bajos ingresos para facilitar su acceso al suelo destinado a la vivienda de inter&eacute;s social prioritaria.&rdquo;.</em><br />\r\n\t<br />\r\n\tPero con el fin de informar a su Despacho los requisitos que debe cumplir la poblaci&oacute;n desplazada para acceder al Subsidio Distrital de Vivienda, me permito informar lo siguiente:<br />\r\n\t<br />\r\n\tEl Subsidio Distrital de Vivienda para hogares en situaci&oacute;n de desplazamiento interno forzado por la violencia es complementario del Subsidio Familiar de Vivienda otorgado por la Naci&oacute;n,&nbsp; otorgado por una sola vez al hogar beneficiario, sin cargo de restituci&oacute;n, con el fin de facilitar una soluci&oacute;n habitacional dentro de las modalidades de vivienda nueva o usada y mejoramiento de vivienda.<br />\r\n\t<br />\r\n\tLa Alcald&iacute;a Mayor de Bogot&aacute; expidi&oacute; el Decreto Distrital 539 del 23 de noviembre de 2012 <em>&quot;Por el cual se reglamenta el subsidio distrital de vivienda en especie en el marco del Plan de Desarrollo Econ&oacute;mico, Social, Ambiental y de Obras P&uacute;blicas Para Bogot&aacute; D. C. 2012 - 2016 - Bogot&aacute; Humana</em>&quot;,&nbsp; que igualmente considera la situaci&oacute;n de la poblaci&oacute;n desplazada y contin&uacute;a otorgando el SDV complementario al Subsidio Familiar de Vivienda que otorga el Gobierno Nacional, en los t&eacute;rminos de la ley 1537 de 2012, ley de vivienda.<br />\r\n\t<br />\r\n\tLa Secretar&iacute;a Distrital del H&aacute;bitat en el marco del Decreto Distrital 539 de noviembre 23 de 2012 expidi&oacute; el Reglamento Operativo para el otorgamiento del Subsidio Distrital de Vivienda en Especie para Vivienda de Inter&eacute;s Prioritario en el Distrito Capital a trav&eacute;s de la Resoluci&oacute;n 176 del 2 de abril de 2013, determinando en el art&iacute;culo 5 que dentro de los hogares que pueden tener acceso al subsidio, est&aacute;n los hogares v&iacute;ctimas del conflicto interno armado.</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>PRONUNCIAMIENTO FRENTE A LOS HECHOS DE LA ACCI&Oacute;N:</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tNo se puede considerar en el presente caso responsable a METROVIVIENDA por los derechos fundamentales supuestamente vulnerados y especialmente en lo que concierne a su necesidad de contar con una soluci&oacute;n habitacional definitiva, obteniendo para el efecto el subsidio familiar de vivienda que a la fecha no le ha sido otorgado, para luego y en caso de que este le haya sido asignado en la ciudad de Bogot&aacute;, iniciar el proceso tendiente a la obtenci&oacute;n del Subsidio Distrital de Vivienda.<br />\r\n\t<br />\r\n\tAs&iacute; las cosas, nos pronunciaremos sobre los hechos alegados de la siguiente manera:<br />\r\n\t<br />\r\n\tComo primera medida debemos informar que la accionante DEMLLI&nbsp; USECHE AVILA C.C. 20.701.508 de La Palma, a la fecha, no ha presentado peticiones ante la entidad por tal raz&oacute;n Metrovivienda nunca ha incurrido en desconocimiento del art&iacute;culo 33 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo.<br />\r\n\t<br />\r\n\tEn cuanto a la asignaci&oacute;n de subsidios,&nbsp; la DEMLLI&nbsp; USECHE AVILA C.C. 20.701.508 de La Palma, manifiesta en la demanda de tutela&nbsp; que: &ldquo;Por otra parte,&nbsp; el no asignar oportunamente los subsidios de vivienda por parte de FONVIVIENDA, nos perjudica, ya que la administraci&oacute;n Distrital hace entrega de un subsidio de vivienda a los desplazados que nos encontramos en la ciudad de Bogot&aacute;, previo a la entrega del subsidio nacional como complemento para la soluci&oacute;n habitacional debemos, porque de no ser as&iacute;,&nbsp; se nos estar&iacute;a vulnerando el derecho a la igualdad. Para acreditar mi afirmaci&oacute;n, ruego al se&ntilde;or Juez requerir a la Alcald&iacute;a Mayor&nbsp; de Bogot&aacute;, con el fin de que esta, corrobore mi informaci&oacute;n, con el fin de que su fallo se ajuste a derecho&rdquo; ( Folio 4 de la acci&oacute;n de tutela.)<br />\r\n\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<br />\r\n\tMETROVIVIENDA en la actualidad no inscribe, postula, asigna ni desembolsa el Subsidio Distrital de Vivienda, esta competencia est&aacute; a cargo de la Secretaria Distrital del H&aacute;bitat.<br />\r\n\t<br />\r\n\tDe igual manera, Metrovivienda no puede efectuar ning&uacute;n pronunciamiento respecto a las ayudas de atenci&oacute;n a la poblaci&oacute;n desplazada, toda vez, que su objeto se encuentra encaminado entre otros a promover la oferta masiva del suelo urbano para facilitar la ejecuci&oacute;n de proyectos integrales de vivienda de inter&eacute;s social, desarrollar las funciones propias de los bancos de tierras o bancos inmobiliarios, respecto de inmuebles destinados en particular para la ejecuci&oacute;n de proyectos urban&iacute;sticos que contemplen la provisi&oacute;n de vivienda de inter&eacute;s social y prioritario, y promover la organizaci&oacute;n comunitaria de familias de bajos ingresos para facilitar el acceso al suelo destinado a este tipo de soluci&oacute;n habitacional, pero en la actualidad no tiene competencia sobre administraci&oacute;n de subsidios.<br />\r\n\t<br />\r\n\tEs importante se&ntilde;alar que frente las ayudas de atenci&oacute;n a la poblaci&oacute;n desplazada, en especial frente al Subsidio para Vivienda el Juzgado S&eacute;ptimo Civil del Circuito, se viene pronunciando as&iacute;:<br />\r\n\t<br />\r\n\t<em>&ldquo;En lo que concierne a la entrega del subsidio de vivienda por parte de FONVIVIENDA, cabe precisar que &eacute;ste es un tramite administrativo que debe ser adelantado directamente por el accionante conforme a las instrucciones que imparta dicha entidad, y no a trav&eacute;s de la presente acci&oacute;n de tutela, toda vez que la misma fue establecida por el legislador como un mecanismo para proteger los derechos fundamentales y no para simplificar u obviar tramites.&rdquo;</em><br />\r\n\t<br />\r\n\tIgualmente cita lo que sobre el particular expuso la Corte Constitucional en la sentencia T-610 de 1997 en los siguientes t&eacute;rminos:<br />\r\n\t<br />\r\n\t<em>&nbsp;&ldquo;&hellip;la acci&oacute;n de tutela no tiene por finalidad inmiscuir a los jueces en el proceso de adopci&oacute;n de toda clase de decisiones, confiadas por la Constituci&oacute;n a las Ramas y &oacute;rganos del poder p&uacute;blico, ni resolver por v&iacute;a general toda suerte de conflictos o los problemas de diversa &iacute;ndole que afectan a la comunidad. Su objetivo y su raz&oacute;n de ser, tienen que ver espec&iacute;ficamente con la protecci&oacute;n de los derechos fundamentales en eventos concretos, siempre que se establezca que &eacute;stos por acci&oacute;n u omisi&oacute;n de una autoridad o de un particular, en los casos previstos por la constituci&oacute;n o la Ley, se encuentran sujetos a una amenaza real o inminente o son objeto de vulneraci&oacute;n actual y directa&rdquo;.</em><br />\r\n\t<br />\r\n\tSe insiste en que<strong> METROVIVIENDA</strong> no es la entidad distrital encargada de administrar y otorgar el Subsidio Distrital de Vivienda, por lo tanto no contamos con los elementos normativos, financieros y log&iacute;sticos para asignar subsidios, como se indic&oacute; anteriormente. Actualmente, esta funci&oacute;n la cumple la Secretar&iacute;a Distrital del H&aacute;bitat.<br />\r\n\t<br />\r\n\tLa Secretar&iacute;a Distrital del H&aacute;bitat en el marco del Decreto Distrital 539 de noviembre 23 de 2012 expidi&oacute; el Reglamento Operativo para el otorgamiento del Subsidio Distrital de Vivienda en Especie para Vivienda de Inter&eacute;s Prioritario en el Distrito Capital a trav&eacute;s de la Resoluci&oacute;n 176 del 2 de abril de 2013, determinando en el art&iacute;culo 5 que dentro de los hogares que pueden tener acceso al subsidio, est&aacute;n los hogares v&iacute;ctimas del conflicto interno armado.<br />\r\n\t<br />\r\n\tAs&iacute; mismo, es pertinente precisar que los art&iacute;culos 12&ordm; y 39&deg; de la Resoluci&oacute;n 176 del 2 de abril de 2013, establece los requisitos que deben cumplir los hogares en situaci&oacute;n de desplazamiento interno forzado por la violencia, para acceder al Subsidio Distrital de Vivienda, los cuales se trascriben:<br />\r\n\t<br />\r\n\t&ldquo;<em>ART&Iacute;CULO 12. Cierre financiero. (&hellip;) En el caso de hogares v&iacute;ctimas del desplazamiento forzado por el&nbsp; conflicto interno, estos deber&aacute;n acreditar que cuentan con el subsidio asignado por Fonvivienda, por el Banco Agrario de Colombia o las entidades que hagan sus veces&hellip;&rdquo;.<br />\r\n\t&nbsp;&nbsp; &nbsp;<br />\r\n\t&ldquo;ART&Iacute;CULO 39. Requisitos b&aacute;sicos para tener derecho al SDVE. El hogar debe cumplir con los siguientes requisitos para acceder al SDVE por cualquiera de los cuatro esquemas establecidos:<br />\r\n\t<br />\r\n\t1. Que el hogar se encuentre inscrito en el Sistema de Informaci&oacute;n para la Financiaci&oacute;n de Soluciones de Vivienda &ndash; SIFSV- de la Secretar&iacute;a Distrital del H&aacute;bitat - SDHT.<br />\r\n\t2. Que al menos una de las personas que integran el hogar tenga ciudadan&iacute;a colombiana, se encuentre en capacidad de obligarse por s&iacute; misma y resida en Bogot&aacute;.<br />\r\n\t3. Que los ingresos totales mensuales del hogar no sean superiores al equivalente a cuatro (4) salarios m&iacute;nimos legales mensuales vigentes &ndash; SMLMV, sin perjuicio de la aplicaci&oacute;n de criterios de priorizaci&oacute;n.<br />\r\n\t4. Que ninguna persona integrante del hogar se encuentre afiliada a una caja de compensaci&oacute;n familiar que le permita acceder a un subsidio de vivienda otorgado con recursos de esas entidades.<br />\r\n\t5. Que ninguno de los integrantes del hogar haya adquirido una vivienda con recursos procedentes del subsidio nacional de vivienda, del subsidio distrital de vivienda o de los subsidios otorgados por las cajas de compensaci&oacute;n familiar.<br />\r\n\t6. Que ninguna de las personas que integran el hogar sea propietaria o poseedora de vivienda en el territorio nacional. Lo anterior no aplica para la propiedad o posesi&oacute;n de terrenos ubicados en zonas de alto riesgo no mitigable o que correspondan a ronda hidr&aacute;ulica o&nbsp; zona de manejo y protecci&oacute;n ambiental &ndash; ZMPA, o de terrenos donde sea imposible la conexi&oacute;n a servicios p&uacute;blicos domiciliarios de acueducto y alcantarillado. Este requisito no aplica para las modalidades de mejoramiento de vivienda.<br />\r\n\t<br />\r\n\tPAR&Aacute;GRAFO 1.&nbsp; Los hogares v&iacute;ctimas del desplazamiento por el conflicto interno deber&aacute;n estar inscritos en el Registro &Uacute;nico de V&iacute;ctimas y ninguna de la personas que integran el&nbsp; hogar podr&aacute; ser propietaria o poseedora de una vivienda en lugar diferente al sitio de desplazamiento. (&hellip;)&rdquo;.</em><br />\r\n\t<br />\r\n\tEs importante se&ntilde;alar que el Subsidio Distrital de Vivienda en Especie es complementario al Subsidio Familiar de Vivienda con relaci&oacute;n a la poblaci&oacute;n desplazada, conforme lo establecido en el Decreto Nacional 1168 de 1996 toda vez que estableci&oacute; en su art&iacute;culo 1&deg;, que los subsidios para vivienda de inter&eacute;s social que los municipios decidan otorgar son complementarios al subsidio nacional de vivienda y podr&aacute;n ser entregados en dinero o en especie, seg&uacute;n lo determinen las autoridades municipales competentes.<br />\r\n\t<br />\r\n\tAdicional a lo anterior, en el marco de nuestras competencias le informamos que la Administraci&oacute;n Distrital, dentro de su pol&iacute;tica no otorga viviendas 100% subsidiadas, pues este&nbsp; programa hace parte de la pol&iacute;tica habitacional del Gobierno Nacional.<br />\r\n\t<br />\r\n\tObserv&aacute;ndose con lo anterior, que el Distrito Capital ni Metrovivienda, han incumplido o presuntamente violado los derechos enunciados por la accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada, tal como ya se ha fallado sobre lo mismo en instancias judiciales.<br />\r\n\t<br />\r\n\tAdicional a lo anterior, el Concejo de Bogot&aacute;, D.C., mediante el Acuerdo 468 de 2011, autoriz&oacute; al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de desplazamiento interno forzado por la violencia en &aacute;reas por fuera del per&iacute;metro del Distrito Capital, con el fin de facilitar su retorno, al respecto contempla:</p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<em>&ldquo;<strong>ART&Iacute;CULO 1</strong>&ordm;.- Autorizar al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de Desplazamiento Interno Forzado por la Violencia para su reubicaci&oacute;n o retorno.<br />\r\n\t<strong>ART&Iacute;CULO 2</strong>. El Subsidio Distrital de Vivienda para Hogares en condici&oacute;n de Desplazamiento Interno Forzado por la Violencia ser&aacute; asignado para aquellos que se encuentren inscritos en el Distrito Capital en el Registro &Uacute;nico de Poblaci&oacute;n Desplazada.<br />\r\n\t<strong>ART&Iacute;CULO 3</strong>. El monto que se otorgue como Subsidio Distrital de Vivienda a cada hogar en situaci&oacute;n de Desplazamiento Interno Forzado ser&aacute; hasta por el mismo valor del subsidio de vivienda que otorga el gobierno Nacional.<br />\r\n\t<strong>ART&Iacute;CULO 4</strong>. La Secretar&iacute;a Distrital del H&aacute;bitat informar&aacute; al alcalde municipal del lugar en el que se aplique el subsidio distrital de vivienda, a las entidades distritales y a FONVIVIENDA, para los efectos de su competencia.<br />\r\n\t<strong>ART&Iacute;CULO 5</strong>. La administraci&oacute;n distrital establecer&aacute; el procedimiento para el desembolso del Subsidio Distrital de Vivienda...&rdquo;<br />\r\n\tEn ese orden de ideas, el Distrito Capital y en especial Metrovivienda no han incumplido o presuntamente violado los derechos enunciados por la accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada, toda vez que Metrovivienda y la Secretar&iacute;a Distrital del H&aacute;bitat, han contribuido al mejoramiento de las condiciones socioecon&oacute;micas de la poblaci&oacute;n desplazada, asignando recursos econ&oacute;micos a trav&eacute;s del Subsidio Distrital de Vivienda, para cubrir las necesidades habitacionales que tiene la poblaci&oacute;n en esta situaci&oacute;n, dirigiendo de esta manera la actuaci&oacute;n de la Administraci&oacute;n Distrital al cumplimiento de los fines consagrados en el art&iacute;culo 13 y 51 de la Constituci&oacute;n Pol&iacute;tica1.</em><br />\r\n\t<br />\r\n\tEn cuanto a la estabilizaci&oacute;n socioecon&oacute;mica. Es necesario indicar que esta obligaci&oacute;n es competencia del&nbsp; Departamento Administrativo para la prosperidad Social.<br />\r\n\t<br />\r\n\tAs&iacute;, Metrovivienda no desarrolla ni fomenta proyectos productivos, ni tiene injerencia alguna en la toma de las decisiones adoptadas por los entidades del Estado en otros niveles, como el Departamento Administrativo para la prosperidad Social, puesto que no hace parte del sector central de la rama ejecutiva del poder p&uacute;blico en el orden nacional. En tal sentido, no es la llamada a responder o al menos presentar justificaci&oacute;n acerca de las presuntas dilaciones en que haya incurrido o pueda incurrir la citada dependencia presidencial frente al accionante.<br />\r\n\t<br />\r\n\tCon claridad en cuanto a lo anterior, s&oacute;lo con fines ilustrativos y sin perjuicio de lo que manifieste el Departamento Administrativo para la prosperidad Social al momento de contestar esta acci&oacute;n, resulta pertinente indicar que en cuanto a la integraci&oacute;n de la poblaci&oacute;n en situaci&oacute;n de desplazamiento al desarrollo de proyectos productivos por parte del Gobierno Nacional, es &eacute;ste quien define -de acuerdo con sus disponibilidades- las condiciones y requisitos que deben reunir la poblaci&oacute;n desplazada en general y la accionante en particular, para tener derecho al auxilio econ&oacute;mico.<br />\r\n\t<br />\r\n\tEllo con fundamento en las siguientes razones de derecho:<br />\r\n\t<br />\r\n\tLa Ley 387 de 1997 dispone que el Fondo Nacional para la Atenci&oacute;n Integral a la Poblaci&oacute;n en Situaci&oacute;n de Desplazamiento interno forzado por la Violencia tiene por objeto</p>\r\n<p style="text-align: justify;">\r\n\t<em>&ldquo;financiar y/o cofinanciar los programas de prevenci&oacute;n del desplazamiento, de atenci&oacute;n humanitaria de emergencia, de retorno, de estabilizaci&oacute;n y consolidaci&oacute;n socioecon&oacute;mica y la instalaci&oacute;n y operaci&oacute;n de la Red Nacional de Informaci&oacute;n.&rdquo;<br />\r\n\t<br />\r\n\tPor su parte, el art&iacute;culo 25 de la misma ley ordena que &ldquo;el Gobierno Nacional har&aacute; los ajustes y traslados presupuestales correspondientes en el Presupuesto General de la Naci&oacute;n para dejar en cabeza del Fondo las apropiaciones necesarias para el cumplimiento de sus objetivos&rdquo;.<br />\r\n\t<br />\r\n\tAdicionalmente los art&iacute;culos 16, 17, 20, 21, 22, 25, 26 y 27 del Decreto 2569 de 2000, reglamentario de la ley 387 de 1997, condicionaron el acceso, tanto a la ayuda humanitaria de emergencia como a los programas de estabilizaci&oacute;n socioecon&oacute;mica, a la existencia de disponibilidades presupuestales. A manera de ejemplo, el art&iacute;culo 22 dispone que &ldquo;en atenci&oacute;n a los principios de solidaridad y de proporcionalidad, la Red de Solidaridad Social destinar&aacute; de los recursos que para tal fin reciba del presupuesto nacional y de manera proporcional al tama&ntilde;o y composici&oacute;n del grupo familiar, un monto m&aacute;ximo equivalente en bienes y servicios, de acuerdo con la disponibilidad presupuestal.&rdquo;</em><br />\r\n\t<br />\r\n\tDe acuerdo con lo expuesto y en virtud de lo resuelto por la Corte Constitucional en la sentencia T-025 de 2002, el Gobierno Nacional debe garantizar la protecci&oacute;n de la poblaci&oacute;n desplazada, por lo cual le resulta imperioso destinar el presupuesto necesario para que los derechos fundamentales de este grupo de persona tengan plena garant&iacute;a. As&iacute; las cosas, el Gobierno Nacional es el encargado de organizar las disponibilidades presupuestales para atender a la poblaci&oacute;n desplazada, de conformidad con los recursos de los que disponga para ello y con respeto a algunos factores de atenci&oacute;n prioritaria<br />\r\n\t<br />\r\n\tAhora bien, para el caso de la poblaci&oacute;n en situaci&oacute;n de desplazamiento, aunque la atenci&oacute;n prioritaria a las necesidades de la misma es un imperativo para diferentes instancias del Estado, debe advertirse que esto no implica que se dejen de regular o establecer formas de encauzar la ayuda destinada a este sector de poblaci&oacute;n. La simple condici&oacute;n de desplazado no habilita de manera autom&aacute;tica para recibir subvenciones, puesto que se requiere de unos m&iacute;nimos legales para su entrega. En el caso concreto del derecho a la vivienda para la poblaci&oacute;n desplazada en Bogot&aacute; D.C. existe una regulaci&oacute;n,&nbsp; que obedece tambi&eacute;n a proyectos y recursos destinados en cada vigencia fiscal para el cumplimiento de las metas.</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<br />\r\n\t<u><strong>IMPROCEDENCIA DE LA ACCI&Oacute;N DE TUTELA EN RELACI&Oacute;N CON METROVIVIENDA</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tCon fundamento en el anterior an&aacute;lisis, el Decreto 2591 de 1991 establece la procedencia de la Acci&oacute;n de Tutela de la siguiente manera:<br />\r\n\t<br />\r\n\t<em>&ldquo;Art&iacute;culo 5o. PROCEDENCIA DE LA ACCION DE TUTELA: La acci&oacute;n de tutela procede contra toda acci&oacute;n u omisi&oacute;n de las autoridades p&uacute;blicas, que haya violado, viole o amenace violar cualquiera de los derechos de que trata el art&iacute;culo 2o. de esta ley. Tambi&eacute;n procede contra acciones u omisiones de particulares, de conformidad con lo establecido en el Cap&iacute;tulo III de este Decreto. La procedencia de la tutela en ning&uacute;n caso est&aacute; sujeta a que la acci&oacute;n de la autoridad o del particular se haya manifestado en un acto jur&iacute;dico escrito.&rdquo;</em><br />\r\n\t<br />\r\n\tNos permitimos afirmar que esta acci&oacute;n constitucional es IMPROCEDENTE, por cuanto no existe violaci&oacute;n, vulneraci&oacute;n o amenaza de un derecho fundamental por parte de METROVIVIENDA, al no ser en el Distrito Capital la entidad responsable de la asignaci&oacute;n y administraci&oacute;n de los recursos del Subsidio Distrital de Vivienda, ni de la competencia para la estabilizaci&oacute;n de las familias que se encuentran en situaci&oacute;n de desplazamiento interno forzado por la violencia.<br />\r\n\tCon base en lo anteriormente expuesto, me permito formular muy respetuosamente la siguiente:<br />\r\n\tPETICI&Oacute;N<br />\r\n\tPor todo lo anterior se&ntilde;or Juez, de acuerdo con las normas parcialmente transcritas y la Jurisprudencia citada, se solicita denegar las pretensiones de la acci&oacute;n de tutela impetrada, respecto a METROVIVIENDA por considerarla improcedente, debido a que esta entidad no vulner&oacute; o amenaza vulnerar derecho fundamental alguno de la accionante, siendo improcedente tutelar los derechos fundamentales invocados, conforme se demostr&oacute; en la parte motiva del presente escrito de contestaci&oacute;n.<br />\r\n\t<br />\r\n\tCordialmente,<br />\r\n\t<br />\r\n\t<br />\r\n\t<br />\r\n\t<strong>Ilva Nubia Herrera G&aacute;lvez</strong><br />\r\n\tDirectora Jur&iacute;dica<br />\r\n\t<br />\r\n\t<br />\r\n\t<br />\r\n\t<br />\r\n\tProyect&oacute;: Lucelly Laverde Rico &ndash; Contratista Direcci&oacute;n Jur&iacute;dica<br />\r\n\tRevis&oacute;: Ilva Nubia Herrera G&aacute;lvez &ndash; Directora Jur&iacute;dica<br />\r\n\tAnexo: 2 folios<br />\r\n\t<br />\r\n\t<br />\r\n\t&nbsp;<br />\r\n\t<br />\r\n\tID-457640<br />\r\n\t&nbsp;</p>\r\n
31	\N	RTA TUTELA SR	2014-06-20 17:09:16.057127	900	4	3	<p style="text-align: right;">\r\n\t<strong>*RAD_S*</strong><br />\r\n\tAl contestar por favor cite estos datos:<br />\r\n\tRadicado No.: <strong>RAD_S</strong><br />\r\n\t&nbsp;</p>\r\n<p style="text-align: justify;">\r\n\tBogot&aacute; D.C.,<br />\r\n\t<br />\r\n\tSe&ntilde;ores:<br />\r\n\tJUZGADO .<br />\r\n\tDireccion&nbsp;<br />\r\n\tTelefax<br />\r\n\tCiudad<br />\r\n\t<br />\r\n\t<strong>Referencia:</strong>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Oficio:</strong>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Accionante</strong>:&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Demandados:&nbsp;</strong>&nbsp;&nbsp;<br />\r\n\t<strong>Asunto:&nbsp;</strong>&nbsp;<br />\r\n\t<strong>Radicado:&nbsp;&nbsp; &nbsp;&nbsp;</strong>&nbsp;&nbsp;<br />\r\n\t<br />\r\n\t<br />\r\n\t<strong>ILVA NUBIA HERRERA G&Aacute;LVEZ</strong>, mayor de edad, vecina y residente en esta ciudad, identificada con la c&eacute;dula de ciudadan&iacute;a No. 41.780.295 de Bogot&aacute;, en mi calidad de Directora Jur&iacute;dica de Metrovivienda, nombrada mediante Resoluci&oacute;n&nbsp; No. 120&nbsp; del 29 de octubre de 2013, debidamente posesionada mediante Acta No. 79 del primero de noviembre de 2013, con funciones de Representaci&oacute;n Legal de <strong>METROVIVIENDA</strong>, persona jur&iacute;dica de derecho p&uacute;blico, Empresa y Industrial y Comercial del Orden Distrital, creada por el Acuerdo 15 de 1998, vinculada a la Secretar&iacute;a Distrital del H&aacute;bitat de la Alcald&iacute;a Mayor de Bogot&aacute;, mediante Acuerdo 257 del 30 de noviembre de 2006 del Concejo de Bogot&aacute;, dentro del t&eacute;rmino de un (1) d&iacute;a concedido por el despacho,&nbsp; procedo a dar respuesta a la acci&oacute;n de tutela se&ntilde;alada en la referencia, pronunci&aacute;ndome&nbsp; en relaci&oacute;n con los hechos que son materia de la misma,&nbsp; bajo el marco de competencia de la Entidad, por cuanto podemos informar que por las razones que expondremos&nbsp; en la presente, no es Metrovivienda la entidad Distrital actualmente responsable de administrar y otorgar los recursos del Subsidio Distrital de Vivienda, siendo esta la Secretar&iacute;a Distrital del H&aacute;bitat quien inscribe, postula, califica, asigna y entrega los recursos del subsidio a los hogares que seg&uacute;n la reglamentaci&oacute;n pueden acceder al Subsidio Distrital de Vivienda.<br />\r\n\t<br />\r\n\tSobre el particular procedo ante el Despacho de conocimiento a proponer de manera previa la siguiente:</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>EXCEPCI&Oacute;N DE M&Eacute;RITO</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tEn primera instancia es importante indicar que en visi&oacute;n del accionante los hechos por &eacute;l se&ntilde;alados derivan en una supuesta amenaza o vulneraci&oacute;n a los siguientes derechos fundamentales: (1) A la Igualdad y no discriminaci&oacute;n ante la Ley (art. 13); (2) Derecho de Petici&oacute;n (art. 23), (3) al M&iacute;nimo Vital,&nbsp; (5) A la vida en conexidad a la dignidad humana, (6) (7) protecci&oacute;n especial a la familia, (8) protecci&oacute;n especial a las mujeres, ni&ntilde;os y ancianos, frente a los cuales plantea carencia de acciones tendientes a la estabilizaci&oacute;n de su situaci&oacute;n, hechos estos que no encuentran relaci&oacute;n con la competencia de Metrovivienda, tal como se explicar&aacute; en detalle en apartados posteriores.<br />\r\n\t<br />\r\n\tEn ese orden de ideas, interpongo al Despacho judicial<strong> FALTA DE LEGITIMACI&Oacute;N EN LA CAUSA POR PASIVA</strong>, toda vez que en relaci&oacute;n con ninguno de los hechos narrados, Metrovivienda en la actualidad tiene dentro de sus competencias&nbsp; la posibilidad de brindar&nbsp; estabilizaci&oacute;n socioecon&oacute;mica, y&nbsp; especificamos que en cuanto&nbsp; al otorgamiento de Subsidios de Vivienda adem&aacute;s de que a la fecha esta entidad no tiene competencia para otorgar Subsidios Distritales de Vivienda, como se narrar&aacute;&nbsp; en apartados posteriores, la presunta vulneraci&oacute;n radica en el hecho de no haber obtenido Subsidio Familiar de Vivienda tramitado ante una caja de compensaci&oacute;n familiar y otorgado por Fonvivienda, requisito sin el cual es imposible normativamente la asignaci&oacute;n de un Subsidio Distrital de Vivienda,<u> esto &uacute;ltimo se reitera, no siendo ya de competencia de esta entidad puesto que mediante el Decreto Distrital 583 de 2007 se le asign&oacute; a la Secretar&iacute;a Distrital del H&aacute;bitat la competencia para su otorgamiento y asignaci&oacute;n,</u> funci&oacute;n que se le hab&iacute;a otorgado a Metrovivienda a trav&eacute;s de los Decretos Distritales 226 de 2005 y 200 de 2006, derogados por el Decreto Distrital 063 de 2009, a su vez derogado por el Decreto Distrital 539 del 23 de noviembre de 2012, por lo tanto y de acuerdo al ordenamiento jur&iacute;dico de creaci&oacute;n de la entidad, esta tiene como prop&oacute;sito esencial asuntos diferentes a los alegados por el actor de tutela, siendo ellos:</p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<em>&ldquo;...A. Promover la oferta masiva de suelo urbano para facilitar la ejecuci&oacute;n de Proyectos Integrales de Vivienda de Inter&eacute;s Social.<br />\r\n\tB. Desarrollar las funciones propias de los bancos de tierras o bancos inmobiliarios, respecto de inmuebles destinados en particular para la ejecuci&oacute;n de proyectos urban&iacute;sticos que contemplen la provisi&oacute;n de Vivienda de Inter&eacute;s Social Prioritaria.<br />\r\n\tC. Promover la organizaci&oacute;n comunitaria de familias de bajos ingresos para facilitar su acceso al suelo destinado a la vivienda de inter&eacute;s social prioritaria.&rdquo;.</em></p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<br />\r\n\t<u><strong>PRONUNCIAMIENTO FRENTE A LOS HECHOS DE LA ACCI&Oacute;N:</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tNo se puede considerar en el presente caso responsable a METROVIVIENDA por los derechos fundamentales supuestamente vulnerados y especialmente en lo que concierne a su necesidad de contar con una soluci&oacute;n habitacional definitiva, obteniendo para el efecto el subsidio familiar de vivienda que a la fecha no le ha sido otorgado, para luego y en caso de que este le haya sido asignado en la ciudad de Bogot&aacute;, iniciar el proceso tendiente a la obtenci&oacute;n del Subsidio Distrital de Vivienda.<br />\r\n\t<br />\r\n\tAs&iacute; las cosas, nos pronunciaremos sobre los hechos alegados de la siguiente manera:<br />\r\n\t<br />\r\n\tComo primera medida debemos informar que el accionante se&ntilde;or WILSON GUTIERREZ MAHECHA C.C. 31.133.775 de Cimitarra,&nbsp;&nbsp; a la fecha, no ha presentado peticiones ante la entidad y&nbsp; por tal raz&oacute;n Metrovivienda nunca ha incurrido en desconocimiento del art&iacute;culo 33 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo.<br />\r\n\t<br />\r\n\t<em>En cuanto a la asignaci&oacute;n de subsidios,&nbsp; el WILSON GUTIERREZ MAHECHA C.C. 31.133.775 de Cimitarra&nbsp;&nbsp;&nbsp;&nbsp; ,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; manifiesta en la demanda de tutela&nbsp; que: &ldquo;Por otra parte,&nbsp; el no asignar oportunamente los subsidios de vivienda por parte de <strong>FONVIVIENDA</strong>, nos perjudica, ya que la administraci&oacute;n Distrital hace entrega de un subsidio de vivienda a los desplazados que nos encontramos en la ciudad de Bogot&aacute;, previo a la entrega del subsidio nacional como complemento para la soluci&oacute;n habitacional debemos, porque de no ser as&iacute;,&nbsp; se nos estar&iacute;a vulnerando el derecho a la igualdad. Para acreditar mi afirmaci&oacute;n, ruego al se&ntilde;or Juez requerir a la Alcald&iacute;a Mayor&nbsp; de Bogot&aacute;, con el fin de que esta, corrobore mi informaci&oacute;n, con el fin de que su&nbsp; fallo se ajuste a derecho.&rdquo; </em>(Folio 9 de la acci&oacute;n de tutela)<br />\r\n\t&nbsp;<br />\r\n\tMETROVIVIENDA en la actualidad no inscribe, postula, asigna ni desembolsa el Subsidio Distrital de Vivienda, esta competencia est&aacute; a cargo de la Secretaria Distrital del H&aacute;bitat.<br />\r\n\t<br />\r\n\tDe igual manera, Metrovivienda no puede efectuar ning&uacute;n pronunciamiento respecto a las ayudas de atenci&oacute;n a la poblaci&oacute;n desplazada, toda vez, que su objeto se encuentra encaminado entre otros a promover la oferta masiva del suelo urbano para facilitar la ejecuci&oacute;n de proyectos integrales de vivienda de inter&eacute;s social, desarrollar las funciones propias de los bancos de tierras o bancos inmobiliarios, respecto de inmuebles destinados en particular para la ejecuci&oacute;n de proyectos urban&iacute;sticos que contemplen la provisi&oacute;n de vivienda de inter&eacute;s social y prioritario, y promover la organizaci&oacute;n comunitaria de familias de bajos ingresos para facilitar el acceso al suelo destinado a este tipo de soluci&oacute;n habitacional, pero en la actualidad no tiene competencia sobre administraci&oacute;n de subsidios.<br />\r\n\t<br />\r\n\tSe insiste en que <strong>METROVIVIENDA</strong> no es la entidad distrital encargada de administrar y otorgar el Subsidio Distrital de Vivienda, por lo tanto no contamos con los elementos normativos, financieros y log&iacute;sticos para asignar subsidios, como se indic&oacute; anteriormente. Actualmente, esta funci&oacute;n la cumple la Secretar&iacute;a Distrital del H&aacute;bitat.<br />\r\n\t<br />\r\n\tCon el fin de brindar una estabilizaci&oacute;n socioecon&oacute;mica a la poblaci&oacute;n desplazada y en cumplimiento de las directrices contenidas en diferentes Sentencias y Autos proferidos por la Corte Constitucional, entre otras la Sentencia T-025 de 2004, el Alcalde Mayor de Bogot&aacute;, expidi&oacute; el Decreto Distrital 200 de 20061, norma que actualmente se encuentra derogada <strong>otorg&aacute;ndole tal competencia a la Secretar&iacute;a Distrital del H&aacute;bitat, confiri&eacute;ndole la facultad de convocar, postular y asignar Subsidios Distritales de Vivienda2</strong> para las modalidades de adquisici&oacute;n de vivienda nueva o usada, mejoramiento de vivienda y construcci&oacute;n en sitio propio, con el fin de brindar un apoyo a las <strong>soluciones habitacionales</strong> de la poblaci&oacute;n desplazada.<br />\r\n\t<br />\r\n\tLa Alcald&iacute;a Mayor de Bogot&aacute; expidi&oacute; el Decreto Distrital 063 del 2 de marzo de 2009 que reglament&oacute; el otorgamiento del Subsidio Distrital de Vivienda, el cual fue derogado por el Decreto Distrital 539 del 23 de noviembre de 2012<em> &quot;Por el cual se reglamenta el subsidio distrital de vivienda en especie en el marco del Plan de Desarrollo Econ&oacute;mico, Social, Ambiental y de Obras P&uacute;blicas Para Bogot&aacute; D. C. 2012 - 2016 - Bogot&aacute; Humana&quot;</em>,&nbsp; que igualmente considera la situaci&oacute;n de la poblaci&oacute;n desplazada y contin&uacute;a otorgando el SDV complementario al Subsidio Familiar de Vivienda que otorga el Gobierno Nacional, en los t&eacute;rminos de la ley 1537 de 2012, ley de vivienda.<br />\r\n\t<br />\r\n\tLa Secretar&iacute;a Distrital del H&aacute;bitat en el marco del Decreto Distrital 539 de noviembre 23 de 2012 expidi&oacute; el Reglamento Operativo para el otorgamiento del Subsidio Distrital de Vivienda en Especie para Vivienda de Inter&eacute;s Prioritario en el Distrito Capital a trav&eacute;s de la Resoluci&oacute;n 176 del 2 de abril de 2013, determinando en el art&iacute;culo 5 que dentro de los hogares que pueden tener acceso al subsidio, est&aacute;n los hogares v&iacute;ctimas del conflicto interno armado.<br />\r\n\t<br />\r\n\tAs&iacute; mismo, es pertinente precisar que los art&iacute;culos 12&ordm; y 39&deg; de la Resoluci&oacute;n 176 del 2 de abril de 2013<em> &ldquo;Por medio de la cual se adopta el reglamento operativo para el otorgamiento del Subsidio Distrital de Vivienda en Especie para Vivienda de Inter&eacute;s Prioritario en el Distrito Capital, en el marco del Decreto Distrital 539 de 2012&rdquo;</em>, establece los requisitos que deben cumplir los hogares en situaci&oacute;n de desplazamiento interno forzado por la violencia, para acceder al Subsidio Distrital de Vivienda, los cuales se trascriben:<br />\r\n\t<br />\r\n\t<em>&ldquo;ART&Iacute;CULO 12. Cierre financiero. (&hellip;) En el caso de hogares v&iacute;ctimas del desplazamiento forzado por el&nbsp; conflicto interno, estos deber&aacute;n acreditar que cuentan con el subsidio asignado por Fonvivienda, por el Banco Agrario de Colombia o las entidades que hagan sus veces&hellip;&rdquo;.<br />\r\n\t&nbsp;&nbsp; &nbsp;<br />\r\n\t&ldquo;ART&Iacute;CULO 39. Requisitos b&aacute;sicos para tener derecho al SDVE. El hogar debe cumplir con los siguientes requisitos para acceder al SDVE por cualquiera de los cuatro esquemas establecidos:</em><br />\r\n\t<br />\r\n\t<em>1. Que el hogar se encuentre inscrito en el Sistema de Informaci&oacute;n para la Financiaci&oacute;n de Soluciones de Vivienda &ndash; SIFSV- de la Secretar&iacute;a Distrital del H&aacute;bitat - SDHT.<br />\r\n\t2. Que al menos una de las personas que integran el hogar tenga ciudadan&iacute;a colombiana, se encuentre en capacidad de obligarse por s&iacute; misma y resida en Bogot&aacute;.<br />\r\n\t3. Que los ingresos totales mensuales del hogar no sean superiores al equivalente a cuatro (4) salarios m&iacute;nimos legales mensuales vigentes &ndash; SMLMV, sin perjuicio de la aplicaci&oacute;n de criterios de priorizaci&oacute;n.<br />\r\n\t4. Que ninguna persona integrante del hogar se encuentre afiliada a una caja de compensaci&oacute;n familiar que le permita acceder a un subsidio de vivienda otorgado con recursos de esas entidades.<br />\r\n\t5. Que ninguno de los integrantes del hogar haya adquirido una vivienda con recursos procedentes del subsidio nacional de vivienda, del subsidio distrital de vivienda o de los subsidios otorgados por las cajas de compensaci&oacute;n familiar.<br />\r\n\t6. Que ninguna de las personas que integran el hogar sea propietaria o poseedora de vivienda en el territorio nacional. Lo anterior no aplica para la propiedad o posesi&oacute;n de terrenos ubicados en zonas de alto riesgo no mitigable o que correspondan a ronda hidr&aacute;ulica o&nbsp; zona de manejo y protecci&oacute;n ambiental &ndash; ZMPA, o de terrenos donde sea imposible la conexi&oacute;n a servicios p&uacute;blicos domiciliarios de acueducto y alcantarillado. Este requisito no aplica para las modalidades de mejoramiento de vivienda.</em><br />\r\n\t<br />\r\n\t<em><strong>PAR&Aacute;GRAFO 1.</strong>&nbsp; Los hogares v&iacute;ctimas del desplazamiento por el conflicto interno deber&aacute;n estar inscritos en el Registro &Uacute;nico de V&iacute;ctimas y ninguna de la personas que integran el&nbsp; hogar podr&aacute; ser propietaria o poseedora de una vivienda en lugar diferente al sitio de desplazamiento. (&hellip;)&rdquo;.</em><br />\r\n\t<br />\r\n\tEs importante se&ntilde;alar que el Subsidio Distrital de Vivienda en Especie es complementario al Subsidio Familiar de Vivienda con relaci&oacute;n a la poblaci&oacute;n desplazada, conforme lo establecido en el Decreto Nacional 1168 de 1996 toda vez que estableci&oacute; en su art&iacute;culo 1&deg;, que los subsidios para vivienda de inter&eacute;s social que los municipios decidan otorgar son complementarios al subsidio nacional de vivienda y podr&aacute;n ser entregados en dinero o en especie, seg&uacute;n lo determinen las autoridades municipales competentes.<br />\r\n\t<br />\r\n\tAdicional a lo anterior, en el marco de nuestras competencias le informamos que la Administraci&oacute;n Distrital, dentro de su pol&iacute;tica no otorga viviendas 100% subsidiadas, pues este&nbsp; programa hace parte de la pol&iacute;tica habitacional del Gobierno Nacional.<br />\r\n\t<br />\r\n\t<strong>Observ&aacute;ndose con lo anterior, que el Distrito Capital ni Metrovivienda, han incumplido o presuntamente violado los derechos enunciados por la accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada, tal como ya se ha fallado sobre lo mismo en instancias judiciales.</strong><br />\r\n\t<br />\r\n\tAdicional a lo anterior, el Concejo de Bogot&aacute;, D.C., mediante el Acuerdo 468 de 2011, autoriz&oacute; al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de desplazamiento interno forzado por la violencia en &aacute;reas por fuera del per&iacute;metro del Distrito Capital, con el fin de facilitar su retorno, al respecto contempla:</p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<em><strong>&ldquo;ART&Iacute;CULO 1</strong>&ordm;.- Autorizar al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de Desplazamiento Interno Forzado por la Violencia para su reubicaci&oacute;n o retorno.<br />\r\n\t<strong>ART&Iacute;CULO 2</strong>. El Subsidio Distrital de Vivienda para Hogares en condici&oacute;n de Desplazamiento Interno Forzado por la Violencia ser&aacute; asignado para aquellos que se encuentren inscritos en el Distrito Capital en el Registro &Uacute;nico de Poblaci&oacute;n Desplazada.<br />\r\n\t<strong>ART&Iacute;CULO 3</strong>. El monto que se otorgue como Subsidio Distrital de Vivienda a cada hogar en situaci&oacute;n de Desplazamiento Interno Forzado ser&aacute; hasta por el mismo valor del subsidio de vivienda que otorga el gobierno Nacional.<br />\r\n\t<strong>ART&Iacute;CULO 4</strong>. La Secretar&iacute;a Distrital del H&aacute;bitat informar&aacute; al alcalde municipal del lugar en el que se aplique el subsidio distrital de vivienda, a las entidades distritales y a FONVIVIENDA, para los efectos de su competencia.<br />\r\n\t<strong>ART&Iacute;CULO 5</strong>. La administraci&oacute;n distrital establecer&aacute; el procedimiento para el desembolso del Subsidio Distrital de Vivienda...&rdquo;</em></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tEn ese orden de ideas, el <strong>Distrito Capital y en especial Metrovivienda no han incumplido o presuntamente violado los derechos enunciados por la accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada</strong>, toda vez que Metrovivienda y la Secretar&iacute;a Distrital del H&aacute;bitat, han contribuido al mejoramiento de las condiciones socioecon&oacute;micas de la poblaci&oacute;n desplazada, asignando recursos econ&oacute;micos a trav&eacute;s del Subsidio Distrital de Vivienda, para cubrir las necesidades habitacionales que tiene la poblaci&oacute;n en esta situaci&oacute;n, dirigiendo de esta manera la actuaci&oacute;n de la Administraci&oacute;n Distrital al cumplimiento de los fines consagrados en el<strong> art&iacute;culo 13 y 51 de la Constituci&oacute;n Pol&iacute;tica3.</strong><br />\r\n\t<br />\r\n\tEn cuanto a la estabilizaci&oacute;n socioecon&oacute;mica. Es necesario indicar que esta obligaci&oacute;n es competencia del&nbsp; Departamento Administrativo para la prosperidad Social.<br />\r\n\t<br />\r\n\tAs&iacute;, Metrovivienda no desarrolla ni fomenta proyectos productivos, ni tiene injerencia alguna en la toma de las decisiones adoptadas por los entidades del Estado en otros niveles, como el Departamento Administrativo para la prosperidad Social, puesto que no hace parte del sector central de la rama ejecutiva del poder p&uacute;blico en el orden nacional. En tal sentido, no es la llamada a responder o al menos presentar justificaci&oacute;n acerca de las presuntas dilaciones en que haya incurrido o pueda incurrir la citada dependencia presidencial frente al accionante.<br />\r\n\t<br />\r\n\tCon claridad en cuanto a lo anterior, s&oacute;lo con fines ilustrativos y sin perjuicio de lo que manifieste el Departamento Administrativo para la Prosperidad Social al momento de contestar esta acci&oacute;n, resulta pertinente indicar que en cuanto a la integraci&oacute;n de la poblaci&oacute;n en situaci&oacute;n de desplazamiento al desarrollo de proyectos productivos por parte del Gobierno Nacional, es &eacute;ste quien define -de acuerdo con sus disponibilidades- las condiciones y requisitos que deben reunir la poblaci&oacute;n desplazada en general y la accionante en particular, para tener derecho al auxilio econ&oacute;mico.<br />\r\n\t<br />\r\n\tEllo con fundamento en las siguientes razones de derecho:<br />\r\n\t<br />\r\n\tLa Ley 387 de 1997 dispone que el Fondo Nacional para la Atenci&oacute;n Integral a la Poblaci&oacute;n en Situaci&oacute;n de Desplazamiento interno forzado por la Violencia tiene por objeto</p>\r\n<p style="text-align: justify;">\r\n\t<em>&ldquo;financiar y/o cofinanciar los programas de prevenci&oacute;n del desplazamiento, de atenci&oacute;n humanitaria de emergencia, de retorno, de estabilizaci&oacute;n y consolidaci&oacute;n socioecon&oacute;mica y la instalaci&oacute;n y operaci&oacute;n de la Red Nacional de Informaci&oacute;n.&rdquo;<br />\r\n\t<br />\r\n\tPor su parte, el art&iacute;culo 25 de la misma ley ordena que &ldquo;el Gobierno Nacional har&aacute; los ajustes y traslados presupuestales correspondientes en el Presupuesto General de la Naci&oacute;n para dejar en cabeza del Fondo las apropiaciones necesarias para el cumplimiento de sus objetivos&rdquo;.<br />\r\n\t<br />\r\n\tAdicionalmente los art&iacute;culos 16, 17, 20, 21, 22, 25, 26 y 27 del Decreto 2569 de 2000, reglamentario de la ley 387 de 1997, condicionaron el acceso, tanto a la ayuda humanitaria de emergencia como a los programas de estabilizaci&oacute;n socioecon&oacute;mica, a la existencia de disponibilidades presupuestales. A manera de ejemplo, el art&iacute;culo 22 dispone que &ldquo;en atenci&oacute;n a los principios de solidaridad y de proporcionalidad, la Red de Solidaridad Social destinar&aacute; de los recursos que para tal fin reciba del presupuesto nacional y de manera proporcional al tama&ntilde;o y composici&oacute;n del grupo familiar, un monto m&aacute;ximo equivalente en bienes y servicios, de acuerdo con la disponibilidad presupuestal.&rdquo;</em><br />\r\n\t<br />\r\n\tDe acuerdo con lo expuesto y en virtud de lo resuelto por la Corte Constitucional en la sentencia T-025 de 2002, el Gobierno Nacional debe garantizar la protecci&oacute;n de la poblaci&oacute;n desplazada, por lo cual le resulta imperioso destinar el presupuesto necesario para que los derechos fundamentales de este grupo de persona tengan plena garant&iacute;a. As&iacute; las cosas, el Gobierno Nacional es el encargado de organizar las disponibilidades presupuestales para atender a la poblaci&oacute;n desplazada, de conformidad con los recursos de los que disponga para ello y con respeto a algunos factores de atenci&oacute;n prioritaria<br />\r\n\t<br />\r\n\tAhora bien, para el caso de la poblaci&oacute;n en situaci&oacute;n de desplazamiento, aunque la atenci&oacute;n prioritaria a las necesidades de la misma es un imperativo para diferentes instancias del Estado, debe advertirse que esto no implica que se dejen de regular o establecer formas de encauzar la ayuda destinada a este sector de poblaci&oacute;n. La simple condici&oacute;n de desplazado no habilita de manera autom&aacute;tica para recibir subvenciones, puesto que se requiere de unos m&iacute;nimos legales para su entrega. En el caso concreto del derecho a la vivienda para la poblaci&oacute;n desplazada en Bogot&aacute; D.C. existe una regulaci&oacute;n,&nbsp; que obedece tambi&eacute;n a proyectos y recursos destinados en cada vigencia fiscal para el cumplimiento de las metas.</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>IMPROCEDENCIA DE LA ACCI&Oacute;N DE TUTELA EN RELACI&Oacute;N CON METROVIVIENDA</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tCon fundamento en el anterior an&aacute;lisis, el Decreto 2591 de 1991 establece la procedencia de la Acci&oacute;n de Tutela de la siguiente manera:<br />\r\n\t<br />\r\n\t<em>&ldquo;Art&iacute;culo 5o. PROCEDENCIA DE LA ACCION DE TUTELA: La acci&oacute;n de tutela procede contra toda acci&oacute;n u omisi&oacute;n de las autoridades p&uacute;blicas, que haya violado, viole o amenace violar cualquiera de los derechos de que trata el art&iacute;culo 2o. de esta ley. Tambi&eacute;n procede contra acciones u omisiones de particulares, de conformidad con lo establecido en el Cap&iacute;tulo III de este Decreto. La procedencia de la tutela en ning&uacute;n caso est&aacute; sujeta a que la acci&oacute;n de la autoridad o del particular se haya manifestado en un acto jur&iacute;dico escrito.&rdquo;</em><br />\r\n\t<br />\r\n\tNos permitimos afirmar que esta acci&oacute;n constitucional es IMPROCEDENTE, por cuanto no existe violaci&oacute;n, vulneraci&oacute;n o amenaza de un derecho fundamental por parte de METROVIVIENDA, al no ser en el Distrito Capital la entidad responsable de la asignaci&oacute;n y administraci&oacute;n de los recursos del Subsidio Distrital de Vivienda, ni de la competencia para la estabilizaci&oacute;n de las familias que se encuentran en situaci&oacute;n de desplazamiento interno forzado por la violencia.<br />\r\n\tCon base en lo anteriormente expuesto, me permito formular muy respetuosamente la siguiente:</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<strong><u>PETICI&Oacute;N</u></strong></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tPor todo lo anterior se&ntilde;or Juez, de acuerdo con las normas parcialmente transcritas y la Jurisprudencia citada, se solicita denegar las pretensiones de la acci&oacute;n de tutela impetrada, respecto a METROVIVIENDA por considerarla improcedente, debido a que esta entidad no vulner&oacute; o amenaza vulnerar derecho fundamental alguno del accionante, siendo improcedente tutelar los derechos fundamentales invocados, conforme se demostr&oacute; en la parte motiva del presente escrito de contestaci&oacute;n.<br />\r\n\t<br />\r\n\tCordialmente,<br />\r\n\t<br />\r\n\t<br />\r\n\t<strong>ILVA NUBIA HERRERA G&Aacute;LVEZ</strong><br />\r\n\tDirectora Jur&iacute;dica<br />\r\n\t<br />\r\n\t<br />\r\n\tProyect&oacute;: Lucelly Laverde Rico &ndash; Contratista Direcci&oacute;n Jur&iacute;dica<br />\r\n\tRevis&oacute;: Ilva Nubia Herrera G&aacute;lvez &ndash; Directora Jur&iacute;dica<br />\r\n\tAnexo: 2 folios<br />\r\n\t<br />\r\n\tID-456631<br />\r\n\t&nbsp;</p>\r\n
33	\N	RTA TUTELA TEMERARIA	2014-06-20 18:50:40.265504	900	4	3	<p style="text-align: right;">\r\n\t<strong>*RAD_S*</strong><br />\r\n\tAl contestar por favor cite estos datos:<br />\r\n\tRadicado No.: <strong>RAD_S</strong><br />\r\n\t&nbsp;</p>\r\n<p style="text-align: justify;">\r\n\tBogot&aacute; D.C.,<br />\r\n\t<br />\r\n\tSe&ntilde;ores:<br />\r\n\tJUZGADO<br />\r\n\tDireccion&nbsp;<br />\r\n\tTelefono<br />\r\n\tCorreo<br />\r\n\tCiudad<br />\r\n\t<br />\r\n\t<strong>Referencia:&nbsp;&nbsp;</strong> &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Oficio:&nbsp;&nbsp; &nbsp;</strong>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Accionante:</strong>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Demandados</strong>:&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Asunto:&nbsp;&nbsp;</strong> &nbsp;CONTESTACI&Oacute;N ACCI&Oacute;N DE TUTELA METROVIVIENDA. DEMANDA TEMERARIA<br />\r\n\t<strong>Radicado:&nbsp;&nbsp;</strong> &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<br />\r\n\t<strong>ILVA NUBIA HERRERA G&Aacute;LVEZ</strong>, mayor de edad, vecina y residente en esta ciudad, identificada con la c&eacute;dula de ciudadan&iacute;a No. 41.780.295 de Bogot&aacute;, en mi calidad de Directora Jur&iacute;dica de Metrovivienda, nombrada mediante Resoluci&oacute;n&nbsp; No. 120&nbsp; del 29 de octubre de 2013, debidamente posesionada mediante Acta No. 79 del primero de noviembre de 2013, con funciones de Representaci&oacute;n Legal de <strong>METROVIVIENDA</strong>, persona jur&iacute;dica de derecho p&uacute;blico, Empresa y Industrial y Comercial del Orden Distrital, creada por el Acuerdo 15 de 1998, vinculada a la Secretar&iacute;a Distrital del H&aacute;bitat de la Alcald&iacute;a Mayor de Bogot&aacute;, mediante Acuerdo 257 del 30 de noviembre de 2006 del Concejo de Bogot&aacute;, dentro del t&eacute;rmino de dos (2) d&iacute;as concedidos por el despacho, pronunci&aacute;ndome&nbsp; primeramente en la temeridad del accionante al instaurar por segunda vez la misma acci&oacute;n de tutela que ya hab&iacute;a presentado en agosto de 2013 con los mismos demandados, bajo los mismos sustentos de hecho y de derecho que fue adelantada ante el Juzgado Octavo Civil del Circuito de de Bogot&aacute; D.C. (se anexan copias del auto de traslado de las demanda, apartes de la demanda y fotocopia de la c&eacute;dula de ciudadan&iacute;a). Nos llama la atenci&oacute;n que en las dos oportunidades tanto el Juzgado Octavo como &eacute;ste en el oficio que notifica el auto admisorio de la demanda,&nbsp; el n&uacute;mero de identificaci&oacute;n del accionante 3.207.941,&nbsp; no corresponde con el mencionado en los textos de las demandas, que es el mismo n&uacute;mero de la fotocopia de la c&eacute;dula, 93.349.876. De igual manera y como se contest&oacute; en las dos oportunidades, en relaci&oacute;n con los hechos que supuestamente ata&ntilde;en a esta Entidad, procedemos a informar, que por las razones que expondremos&nbsp; en la presente, no es Metrovivienda la entidad Distrital actualmente responsable de administrar y otorgar los recursos del Subsidio Distrital de Vivienda, siendo esta la Secretar&iacute;a Distrital del H&aacute;bitat quien inscribe, postula, califica, asigna y entrega los recursos del subsidio a los hogares que seg&uacute;n la reglamentaci&oacute;n pueden acceder al Subsidio Distrital de Vivienda.&nbsp; &nbsp;<br />\r\n\t<br />\r\n\tProcedo ante el Despacho de conocimiento a proponer de manera previa la siguiente:</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<br />\r\n\t<u><strong>ACTUACI&Oacute;N TEMERARIA POR PARTE DEL ACCIONANTE</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<br />\r\n\tEl art&iacute;culo 38 del Decreto 2591 de 1991 por el cual se reglamenta la acci&oacute;n de tutela es claro, en manifestar que hay actuaci&oacute;n temeraria por parte del Accionante &ldquo;Cuando, sin motivo expresamente justificado, la misma acci&oacute;n de tutela sea presentada por la misma persona o su representante ante varios jueces o tribunales, se rechazar&aacute;n o decidir&aacute;n desfavorablemente todas las solicitudes. (&hellip;)&rdquo;<br />\r\n\t<br />\r\n\tEl accionante ha instaurado la misma acci&oacute;n de tutela en la que se ha visto involucrada&nbsp; METROVIVIENDA en otra oportunidad anterior: la primera, ante el Juzgado Octavo Civil del Circuito de de Bogot&aacute; D.C., bajo la radicaci&oacute;n 11001310300820130049800, de agosto&nbsp; de 2013, existiendo en dicho proceso fallo en primera instancia del 12 de agosto que protege el derecho de petici&oacute;n desvinculando a METROVIVIENDA.<br />\r\n\t&nbsp;<br />\r\n\tAs&iacute; las cosas, la presente acci&oacute;n de tutela debe ser rechazada y el accionante como consecuencia, debe asumir los efectos del falso juramento establecido en el numeral VIII de la acci&oacute;n de tutela en referencia en lo que a METROVIVIENDA concierne.</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<br />\r\n\t<u><strong>EXCEPCI&Oacute;N DE M&Eacute;RITO</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<br />\r\n\tPese a la actuaci&oacute;n temeraria del Accionante, nos permitimos pronunciarnos frente a lo expuesto en la acci&oacute;n de tutela de la referencia, indicando que seg&uacute;n el se&ntilde;or FERNANDO PATI&Ntilde;O C.C. 93.349.876 de San Antonio - Tolima,&nbsp; supuestamente se le est&aacute;n amenazando o vulnerando los siguientes derechos: fundamentales: (1) A la vida digna; (2)&nbsp; Vivienda digna;&nbsp; (3) Uni&oacute;n familiar, (4) a la dignidad humana, (5) seguridad social, (6) protecci&oacute;n a la poblaci&oacute;n desplazada, (7) la igualdad y la reparaci&oacute;n integral de v&iacute;ctimas, (8) estabilizaci&oacute;n socioecon&oacute;mica y trabajo en los proyectos productivos o generaci&oacute;n de ingresos, frente a los cuales plantea carencia de acciones tendientes a la estabilizaci&oacute;n de su situaci&oacute;n, hechos estos que no encuentran relaci&oacute;n con la competencia de Metrovivienda, tal como se explicar&aacute; en detalle en apartados posteriores.<br />\r\n\t<br />\r\n\tEn ese orden de ideas, interpongo al Despacho judicial <strong>FALTA DE LEGITIMACI&Oacute;N EN LA CAUSA POR PASIVA</strong>, toda vez que en relaci&oacute;n con ninguno de los hechos narrados, Metrovivienda en la actualidad tiene dentro de sus competencias&nbsp; la posibilidad de brindar&nbsp; estabilizaci&oacute;n socioecon&oacute;mica, y&nbsp; especificamos que en cuanto&nbsp; al otorgamiento de Subsidios de Vivienda adem&aacute;s de que a la fecha esta entidad no tiene competencia para otorgar Subsidios Distritales de Vivienda, como se narrar&aacute;&nbsp; en apartados posteriores, la presunta vulneraci&oacute;n radica en el hecho de no haber obtenido Subsidio Familiar de Vivienda tramitado ante una caja de compensaci&oacute;n familiar y otorgado por Fonvivienda, requisito sin el cual es imposible normativamente la asignaci&oacute;n de un Subsidio Distrital de Vivienda, <u>esto &uacute;ltimo se reitera, no siendo ya de competencia de esta entidad puesto que mediante el Decreto Distrital 583 de 2007 se le asign&oacute; a la Secretar&iacute;a Distrital del H&aacute;bitat la competencia para su otorgamiento y asignaci&oacute;n,</u> funci&oacute;n que se le hab&iacute;a otorgado a Metrovivienda a trav&eacute;s de los Decretos Distritales 226 de 2005 y 200 de 2006, derogados por el Decreto Distrital 063 de 2009, por lo tanto y de acuerdo al ordenamiento jur&iacute;dico de creaci&oacute;n de la entidad, esta tiene como prop&oacute;sito esencial asuntos diferentes a los alegados por el actor de tutela, siendo ellos:</p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<em>&nbsp;&ldquo;...A. Promover la oferta masiva de suelo urbano para facilitar la ejecuci&oacute;n de Proyectos Integrales de Vivienda de Inter&eacute;s Social.<br />\r\n\tB. Desarrollar las funciones propias de los bancos de tierras o bancos inmobiliarios, respecto de inmuebles destinados en particular para la ejecuci&oacute;n de proyectos urban&iacute;sticos que contemplen la provisi&oacute;n de Vivienda de Inter&eacute;s Social Prioritaria.<br />\r\n\tC. Promover la organizaci&oacute;n comunitaria de familias de bajos ingresos para facilitar su acceso al suelo destinado a la vivienda de inter&eacute;s social prioritaria.&rdquo;.</em></p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>PRONUNCIAMIENTO FRENTE A LOS HECHOS DE LA ACCI&Oacute;N:</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tNo se puede considerar en el presente caso responsable a METROVIVIENDA por los derechos fundamentales supuestamente vulnerados y especialmente en lo que concierne a su necesidad de contar con una soluci&oacute;n habitacional definitiva, obteniendo para el efecto el subsidio familiar de vivienda que a la fecha no le ha sido otorgado, para luego y en caso de que este le haya sido asignado en la ciudad de Bogot&aacute;, iniciar el proceso tendiente a la obtenci&oacute;n del Subsidio Distrital de Vivienda.<br />\r\n\t<br />\r\n\tAs&iacute; las cosas, nos pronunciaremos sobre los hechos <strong><u>nuevamente</u></strong> alegados de la siguiente manera:<br />\r\n\t<br />\r\n\tEn cuanto a la asignaci&oacute;n de subsidios,&nbsp; el se&ntilde;or FERNANDO PATI&Ntilde;O C.C. 93.349.876&nbsp;&nbsp;&nbsp; manifiesta en la demanda de tutela&nbsp; que: &ldquo;<em>Por otra parte,&nbsp; el no asignar oportunamente los subsidios de vivienda por parte de <strong>FONVIVIENDA</strong>, nos perjudica, ya que la administraci&oacute;n Distrital hace entrega de un subsidio de vivienda a los desplazados que nos encontramos en la ciudad de Bogot&aacute;, previo a la entrega del subsidio nacional como complemento para la soluci&oacute;n habitacional debemos, porque de no ser as&iacute;,&nbsp; se nos estar&iacute;a vulnerando el derecho a la igualdad. Para acreditar mi afirmaci&oacute;n, ruego al se&ntilde;or Juez requerir a la Alcald&iacute;a Mayor&nbsp; de Bogot&aacute;, con el fin de que esta, corrobore mi informaci&oacute;n, con el fin de que su&nbsp; fallo se ajuste a derecho.&rdquo;</em> (Folio 9 de la acci&oacute;n de tutela)<br />\r\n\t&nbsp;<br />\r\n\tSe insiste en que <strong>METROVIVIENDA</strong> no es la entidad distrital encargada de administrar y otorgar el Subsidio Distrital de Vivienda, por lo tanto no contamos con los elementos normativos, financieros y log&iacute;sticos para asignar subsidios, como se indic&oacute; anteriormente. Actualmente, esta funci&oacute;n la cumple la Secretar&iacute;a Distrital del H&aacute;bitat. Pero con el fin de informar a su Despacho los requisitos que debe cumplir la poblaci&oacute;n desplazada para acceder al Subsidio Distrital de Vivienda, me permito informar lo siguiente:<br />\r\n\t<br />\r\n\tEl Subsidio Distrital de Vivienda para hogares en situaci&oacute;n de desplazamiento interno forzado por la violencia es complementario del Subsidio Familiar de Vivienda otorgado por la Naci&oacute;n, otorgado por una sola vez al hogar beneficiario, sin cargo de restituci&oacute;n, con el fin de facilitar una soluci&oacute;n habitacional dentro de las modalidades de vivienda nueva o usada y mejoramiento de vivienda.<br />\r\n\t<br />\r\n\tDe tal manera, que con el fin de brindar una estabilizaci&oacute;n socioecon&oacute;mica a la poblaci&oacute;n desplazada y en cumplimiento de las directrices contenidas en diferentes Sentencias y Autos proferidos por la Corte Constitucional, entre otras la Sentencia T-025 de 2004, el Alcalde Mayor de Bogot&aacute;, expidi&oacute; el Decreto Distrital 200 de 20061, norma que actualmente se encuentra derogada <strong>otorg&aacute;ndole tal competencia a la Secretar&iacute;a Distrital del H&aacute;bitat, confiri&eacute;ndole la facultad de convocar, postular y asignar Subsidios Distritales de Vivienda2</strong> para las modalidades de adquisici&oacute;n de vivienda nueva o usada, mejoramiento de vivienda y construcci&oacute;n en sitio propio, con el fin de brindar un apoyo a las <strong>soluciones habitacionales</strong> de la poblaci&oacute;n desplazada.<br />\r\n\t<br />\r\n\tLa Alcald&iacute;a Mayor de Bogot&aacute; expidi&oacute; el Decreto Distrital 063 del 2 de marzo de 2009 que reglament&oacute; el otorgamiento del Subsidio Distrital de Vivienda, el cual fue derogado por el Decreto Distrital 539 del 23 de noviembre de 2012 <em>&quot;Por el cual se reglamenta el subsidio distrital de vivienda en especie en el marco del Plan de Desarrollo Econ&oacute;mico, Social, Ambiental y de Obras P&uacute;blicas Para Bogot&aacute; D. C. 2012 - 2016 - Bogot&aacute; Humana</em>&quot;,&nbsp; que igualmente considera la situaci&oacute;n de la poblaci&oacute;n desplazada y contin&uacute;a otorgando el SDV complementario al Subsidio Familiar de Vivienda que otorga el Gobierno Nacional, en los t&eacute;rminos de la ley 1537 de 2012, ley de vivienda.<br />\r\n\t<br />\r\n\tObserv&aacute;ndose con lo anterior, que el Distrito Capital ni Metrovivienda, han incumplido o presuntamente violado los derechos enunciados por la accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada, tal como ya se ha fallado sobre lo mismo en instancias judiciales.<br />\r\n\t<br />\r\n\tAdicional a lo anterior, el Concejo de Bogot&aacute;, D.C., mediante el Acuerdo 468 de 2011, autoriz&oacute; al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de desplazamiento interno forzado por la violencia en &aacute;reas por fuera del per&iacute;metro del Distrito Capital, con el fin de facilitar su retorno, al respecto contempla:</p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<em><strong>&ldquo;ART&Iacute;CULO 1&ordm;</strong>.- Autorizar al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de Desplazamiento Interno Forzado por la Violencia para su reubicaci&oacute;n o retorno.<br />\r\n\t<strong>ART&Iacute;CULO 2</strong>. El Subsidio Distrital de Vivienda para Hogares en condici&oacute;n de Desplazamiento Interno Forzado por la Violencia ser&aacute; asignado para aquellos que se encuentren inscritos en el Distrito Capital en el Registro &Uacute;nico de Poblaci&oacute;n Desplazada.<br />\r\n\t<strong>ART&Iacute;CULO 3</strong>. El monto que se otorgue como Subsidio Distrital de Vivienda a cada hogar en situaci&oacute;n de Desplazamiento Interno Forzado ser&aacute; hasta por el mismo valor del subsidio de vivienda que otorga el gobierno Nacional.<br />\r\n\t<strong>ART&Iacute;CULO 4</strong>. La Secretar&iacute;a Distrital del H&aacute;bitat informar&aacute; al alcalde municipal del lugar en el que se aplique el subsidio distrital de vivienda, a las entidades distritales y a FONVIVIENDA, para los efectos de su competencia.<br />\r\n\t<strong>ART&Iacute;CULO 5</strong>. La administraci&oacute;n distrital establecer&aacute; el procedimiento para el desembolso del Subsidio Distrital de Vivienda...&rdquo;</em></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tEn ese orden de ideas, el<strong> Distrito Capital y en especial Metrovivienda no han incumplido o presuntamente violado los derechos enunciados por el accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada,</strong> toda vez que Metrovivienda y la Secretar&iacute;a Distrital del H&aacute;bitat, han contribuido al mejoramiento de las condiciones socioecon&oacute;micas de la poblaci&oacute;n desplazada, asignando recursos econ&oacute;micos a trav&eacute;s del Subsidio Distrital de Vivienda, para cubrir las necesidades habitacionales que tiene la poblaci&oacute;n en esta situaci&oacute;n, dirigiendo de esta manera la actuaci&oacute;n de la Administraci&oacute;n Distrital al cumplimiento de los fines consagrados en el<strong> art&iacute;culo 13 y 51 de la Constituci&oacute;n Pol&iacute;tica3.</strong><br />\r\n\t<br />\r\n\t2. En cuanto a la estabilizaci&oacute;n socioecon&oacute;mica. Es necesario indicar que esta obligaci&oacute;n es competencia del&nbsp; Departamento Administrativo para la prosperidad Social.<br />\r\n\t<br />\r\n\tAs&iacute;, Metrovivienda no desarrolla ni fomenta proyectos productivos, ni tiene injerencia alguna en la toma de las decisiones adoptadas por los entidades del Estado en otros niveles, como el Departamento Administrativo para la prosperidad Social, puesto que no hace parte del sector central de la rama ejecutiva del poder p&uacute;blico en el orden nacional. En tal sentido, no es la llamada a responder o al menos presentar justificaci&oacute;n acerca de las presuntas dilaciones en que haya incurrido o pueda incurrir la citada dependencia presidencial frente al accionante.<br />\r\n\t<br />\r\n\tCon claridad en cuanto a lo anterior, s&oacute;lo con fines ilustrativos y sin perjuicio de lo que manifieste el Departamento Administrativo para la prosperidad Social al momento de contestar esta acci&oacute;n, resulta pertinente indicar que en cuanto a la integraci&oacute;n de la poblaci&oacute;n en situaci&oacute;n de desplazamiento al desarrollo de proyectos productivos por parte del Gobierno Nacional, es &eacute;ste quien define -de acuerdo con sus disponibilidades- las condiciones y requisitos que deben reunir la poblaci&oacute;n desplazada en general y la accionante en particular, para tener derecho al auxilio econ&oacute;mico.<br />\r\n\t<br />\r\n\tEllo con fundamento en las siguientes razones de derecho:<br />\r\n\t<br />\r\n\tLa Ley 387 de 1997 dispone que el Fondo Nacional para la Atenci&oacute;n Integral a la Poblaci&oacute;n en Situaci&oacute;n de Desplazamiento interno forzado por la Violencia tiene por objeto<em>&ldquo;financiar y/o cofinanciar los programas de prevenci&oacute;n del desplazamiento, de atenci&oacute;n humanitaria de emergencia, de retorno, de estabilizaci&oacute;n y consolidaci&oacute;n socioecon&oacute;mica y la instalaci&oacute;n y operaci&oacute;n de la Red Nacional de Informaci&oacute;n.&rdquo;</em><br />\r\n\t<br />\r\n\tPor su parte, el art&iacute;culo 25 de la misma ley ordena que &ldquo;el Gobierno Nacional har&aacute; los ajustes y traslados presupuestales correspondientes en el Presupuesto General de la Naci&oacute;n para dejar en cabeza del Fondo las apropiaciones necesarias para el cumplimiento de sus objetivos&rdquo;.<br />\r\n\t<br />\r\n\tAdicionalmente los art&iacute;culos 16, 17, 20, 21, 22, 25, 26 y 27 del Decreto 2569 de 2000, reglamentario de la ley 387 de 1997, condicionaron el acceso, tanto a la ayuda humanitaria de emergencia como a los programas de estabilizaci&oacute;n socioecon&oacute;mica, a la existencia de disponibilidades presupuestales. A manera de ejemplo, el art&iacute;culo 22 dispone que<em> &ldquo;en atenci&oacute;n a los principios de solidaridad y de proporcionalidad, la Red de Solidaridad Social destinar&aacute; de los recursos que para tal fin reciba del presupuesto nacional y de manera proporcional al tama&ntilde;o y composici&oacute;n del grupo familiar, un monto m&aacute;ximo equivalente en bienes y servicios, de acuerdo con la disponibilidad presupuestal.&rdquo;</em><br />\r\n\t<br />\r\n\tDe acuerdo con lo expuesto y en virtud de lo resuelto por la Corte Constitucional en la sentencia T-025 de 2002, el Gobierno Nacional debe garantizar la protecci&oacute;n de la poblaci&oacute;n desplazada, por lo cual le resulta imperioso destinar el presupuesto necesario para que los derechos fundamentales de este grupo de persona tengan plena garant&iacute;a. As&iacute; las cosas, el Gobierno Nacional es el encargado de organizar las disponibilidades presupuestales para atender a la poblaci&oacute;n desplazada, de conformidad con los recursos de los que disponga para ello y con respeto a algunos factores de atenci&oacute;n prioritaria<br />\r\n\t<br />\r\n\tAhora bien, para el caso de la poblaci&oacute;n en situaci&oacute;n de desplazamiento, aunque la atenci&oacute;n prioritaria a las necesidades de la misma es un imperativo para diferentes instancias del Estado, debe advertirse que esto no implica que se dejen de regular o establecer formas de encauzar la ayuda destinada a este sector de poblaci&oacute;n. La simple condici&oacute;n de desplazado no habilita de manera autom&aacute;tica para recibir subvenciones, puesto que se requiere de unos m&iacute;nimos legales para su entrega. En el caso concreto del derecho a la vivienda para la poblaci&oacute;n desplazada en Bogot&aacute; D.C. existe una regulaci&oacute;n,&nbsp; que obedece tambi&eacute;n a proyectos y recursos destinados en cada vigencia fiscal para el cumplimiento de las metas.<br />\r\n\t&nbsp;</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>IMPROCEDENCIA DE LA ACCI&Oacute;N DE TUTELA EN RELACI&Oacute;N CON METROVIVIENDA</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tCon fundamento en el anterior an&aacute;lisis, el Decreto 2591 de 1991 establece la procedencia de la Acci&oacute;n de Tutela de la siguiente manera:<br />\r\n\t<br />\r\n\t<em>&ldquo;Art&iacute;culo 5o. PROCEDENCIA DE LA ACCION DE TUTELA: La acci&oacute;n de tutela procede contra toda acci&oacute;n u omisi&oacute;n de las autoridades p&uacute;blicas, que haya violado, viole o amenace violar cualquiera de los derechos de que trata el art&iacute;culo 2o. de esta ley. Tambi&eacute;n procede contra acciones u omisiones de particulares, de conformidad con lo establecido en el Cap&iacute;tulo III de este Decreto. La procedencia de la tutela en ning&uacute;n caso est&aacute; sujeta a que la acci&oacute;n de la autoridad o del particular se haya manifestado en un acto jur&iacute;dico escrito.&rdquo;</em><br />\r\n\t<br />\r\n\t<strong>As&iacute; mismo, es una actuaci&oacute;n temeraria de conformidad con el art&iacute;culo 38 del Decreto 2591 de 1991, la del accionante de instaurar nuevamente la misma acci&oacute;n de tutela contra METROVIVIENDA, cuando ya existen pronunciamientos judiciales al respecto, aprovech&aacute;ndose as&iacute; de la naturaleza de este tipo de acci&oacute;n y generando a su vez congesti&oacute;n en los despachos judiciales.</strong></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tCon base en lo anteriormente expuesto, me permito formular muy respetuosamente la siguiente:</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>PETICI&Oacute;N</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tPor todo lo anterior se&ntilde;or Juez de acuerdo con las normas parcialmente transcritas y la Jurisprudencia citada, se solicita rechazar la presente acci&oacute;n de tutela por corresponder a una actuaci&oacute;n temeraria consagrada en el art&iacute;culo 38 del Decreto 2591 de 1991 y por ende denegar las pretensiones de la acci&oacute;n de tutela impetrada, respecto a METROVIVIENDA, por considerarla improcedente, debido a que esta entidad como ya lo han decido fallos judiciales, no vulner&oacute; o amenaz&oacute; vulnerar derecho fundamental alguno del Accionante, conforme se demostr&oacute; en la parte motiva del presente escrito de contestaci&oacute;n sobre el que como ya se ha venido enunciando, ya cuentan con pronunciamientos judiciales.<br />\r\n\t<br />\r\n\tDel se&ntilde;or Juez, respetuosamente,<br />\r\n\t<br />\r\n\t<br />\r\n\t<strong>Ilva Nubia Herrera G&aacute;lvez</strong><br />\r\n\tDirectora Jur&iacute;dica<br />\r\n\t<br />\r\n\t<br />\r\n\t<br />\r\n\tProyect&oacute;: Lucelly Laverde Rico &ndash; Contratista Direcci&oacute;n Jur&iacute;dica<br />\r\n\tRevis&oacute;: Ilva Nubia Herrera G&aacute;lvez &ndash; Directora Jur&iacute;dica<br />\r\n\tAnexo: 6 folios<br />\r\n\t<br />\r\n\t<br />\r\n\tID: 457047</p>\r\n
39	\N	RTA TUTELA	2014-06-20 20:05:01.404738	900	4	3	<p style="text-align: right;">\r\n\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *RAD_S*</strong><br />\r\n\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Al contestar por favor cite estos datos:<br />\r\n\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Radicado No.<strong>: RAD_S</strong><br />\r\n\t&nbsp;</p>\r\n<p style="text-align: justify;">\r\n\tBogot&aacute; D.C.,<br />\r\n\t<br />\r\n\tSe&ntilde;ores:<br />\r\n\tJUZGADO...<br />\r\n\tDireccion<br />\r\n\tTel&eacute;fono<br />\r\n\tCiudad<br />\r\n\t<br />\r\n\t<strong>Referencia:</strong>&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Oficio:&nbsp;</strong>&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Accionante:&nbsp;</strong>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Demandados:</strong>&nbsp;&nbsp; &nbsp;<br />\r\n\t<strong>Asunto:&nbsp;&nbsp;&nbsp;&nbsp;</strong> &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<strong>Radicado:&nbsp;</strong>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br />\r\n\t<br />\r\n\t<strong>ILVA NUBIA HERRERA G&Aacute;LVEZ</strong>, mayor de edad, vecina y residente en esta ciudad, identificada con la c&eacute;dula de ciudadan&iacute;a No. 41.780.295 de Bogot&aacute;, en mi calidad de Directora Jur&iacute;dica de Metrovivienda, nombrada mediante Resoluci&oacute;n&nbsp; No. 120&nbsp; del 29 de octubre de 2013, debidamente posesionada mediante Acta No. 79 del primero de noviembre de 2013, con funciones de Representaci&oacute;n Legal de<strong> METROVIVIENDA</strong>, persona jur&iacute;dica de derecho p&uacute;blico, Empresa y Industrial y Comercial del Orden Distrital, creada por el Acuerdo 15 de 1998, vinculada a la Secretar&iacute;a Distrital del H&aacute;bitat de la Alcald&iacute;a Mayor de Bogot&aacute;, mediante Acuerdo 257 del 30 de noviembre de 2006 del Concejo de Bogot&aacute;, dentro del t&eacute;rmino de dos (2) d&iacute;as concedidos por el despacho, procedo a dar respuesta a la acci&oacute;n de tutela se&ntilde;alada en la referencia, pronunci&aacute;ndome&nbsp; en relaci&oacute;n con los hechos que son materia de la misma,&nbsp; bajo el marco de competencia de la Entidad, por cuanto podemos informar que por las razones que expondremos&nbsp; en la presente, no es Metrovivienda la entidad Distrital actualmente responsable de administrar y otorgar los recursos del Subsidio Distrital de Vivienda, siendo esta la Secretar&iacute;a Distrital del H&aacute;bitat quien inscribe, postula, califica, asigna y entrega los recursos del subsidio a los hogares que seg&uacute;n la reglamentaci&oacute;n pueden acceder al Subsidio Distrital de Vivienda.<br />\r\n\t<br />\r\n\tSobre el particular procedo ante el Despacho de conocimiento a proponer de manera previa la siguiente:<br />\r\n\t&nbsp;</p>\r\n<p style="text-align: center;">\r\n\t<u><strong>EXCEPCI&Oacute;N DE M&Eacute;RITO</strong></u></p>\r\n<p style="text-align: justify;">\r\n\tEn primera instancia es importante indicar que en visi&oacute;n de la accionante los hechos por ella se&ntilde;alados derivan en una supuesta amenaza o vulneraci&oacute;n a los siguientes derechos fundamentales: a la igualdad, al derecho de petici&oacute;n y a la vivienda, hechos estos que no encuentran relaci&oacute;n con la competencia de Metrovivienda, tal como se explicar&aacute; en detalle en apartados posteriores.<br />\r\n\t<br />\r\n\tEn ese orden de ideas, interpongo al Despacho judicial <strong>FALTA DE LEGITIMACI&Oacute;N EN LA CAUSA POR PASIVA</strong>, toda vez que en relaci&oacute;n con ninguno de los hechos narrados, Metrovivienda en la actualidad tiene dentro de sus competencias&nbsp; la posibilidad del otorgamiento de Subsidios de Vivienda adem&aacute;s de que a la fecha esta entidad no tiene competencia para otorgar Subsidios Distritales de Vivienda,<u><strong> puesto que mediante el Decreto Distrital 583 de 2007 se le asign&oacute; a la Secretar&iacute;a Distrital del H&aacute;bitat la competencia para su otorgamiento y asignaci&oacute;n</strong>,</u> funci&oacute;n que se le hab&iacute;a otorgado a Metrovivienda a trav&eacute;s de los Decretos Distritales 226 de 2005 y 200 de 2006, derogados por el Decreto Distrital 063 de 2009, a su vez derogado por el Decreto Distrital 539 del 23 de noviembre de 2012, por lo tanto y de acuerdo al ordenamiento jur&iacute;dico de creaci&oacute;n de la entidad, esta tiene como prop&oacute;sito esencial asuntos diferentes a los alegados por el actor de tutela, siendo ellos:</p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<em>&ldquo;...A. Promover la oferta masiva de suelo urbano para facilitar la ejecuci&oacute;n de Proyectos Integrales de Vivienda de Inter&eacute;s Social.<br />\r\n\tB. Desarrollar las funciones propias de los bancos de tierras o bancos inmobiliarios, respecto de inmuebles destinados en particular para la ejecuci&oacute;n de proyectos urban&iacute;sticos que contemplen la provisi&oacute;n de Vivienda de Inter&eacute;s Social Prioritaria.<br />\r\n\tC. Promover la organizaci&oacute;n comunitaria de familias de bajos ingresos para facilitar su acceso al suelo destinado a la vivienda de inter&eacute;s social prioritaria.&rdquo;.</em></p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>PRONUNCIAMIENTO FRENTE A LOS HECHOS DE LA ACCI&Oacute;N</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tLa accionante se&ntilde;ora EHULALIA CORDOBA VALENCIA&nbsp; C.C. 21.247.064 de Mitu,&nbsp; manifiesta que ha solicitado a trav&eacute;s de derecho de petici&oacute;n al Fondo Nacional de Vivienda &ndash; FONVIVIENDA &ndash; le informe la fecha en que le va a ser asignado un Subsidio Familiar de Vivienda, sin que hasta la fecha le haya sido contestado, a trav&eacute;s de la Acci&oacute;n de Tutela solicita respuesta y se le asigne un subsidio familiar de vivienda.<br />\r\n\t<br />\r\n\tComo primera medida debemos informar que la accionante se&ntilde;ora EHULALIA CORDOBA VALENCIA&nbsp; C.C. 21.247.064 de Mitu, a la fecha, no ha presentado peticiones ante la entidad y&nbsp; por tal raz&oacute;n Metrovivienda nunca ha incurrido en desconocimiento del art&iacute;culo 33 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo.<br />\r\n\t<br />\r\n\tDe otra parte no se puede considerar en el presente caso responsable a METROVIVIENDA por los derechos fundamentales supuestamente vulnerados y especialmente en lo que concierne a su necesidad de contar con una soluci&oacute;n habitacional definitiva, obteniendo para el efecto el subsidio familiar de vivienda que a la fecha no le ha sido otorgado, para luego y en caso de que este le sea asignado en la ciudad de Bogot&aacute;, pueda iniciar el proceso tendiente a la obtenci&oacute;n del Subsidio Distrital de Vivienda.<br />\r\n\t<br />\r\n\tSe insiste en que <strong>METROVIVIENDA</strong> no es la entidad distrital encargada de administrar y otorgar el Subsidio Distrital de Vivienda, por lo tanto no contamos con los elementos normativos, financieros y log&iacute;sticos para asignar subsidios, como se indic&oacute; anteriormente. Actualmente, esta funci&oacute;n la cumple la Secretar&iacute;a Distrital del H&aacute;bitat. Pero con el fin de informar a su Despacho los requisitos que debe cumplir la poblaci&oacute;n desplazada para acceder al Subsidio Distrital de Vivienda, me permito informar lo siguiente:<br />\r\n\t<br />\r\n\tCon el fin de brindar una estabilizaci&oacute;n socioecon&oacute;mica a la poblaci&oacute;n desplazada y en cumplimiento de las directrices contenidas en diferentes Sentencias y Autos proferidos por la Corte Constitucional, entre otras la Sentencia T-025 de 2004, el Alcalde Mayor de Bogot&aacute;, expidi&oacute; el Decreto Distrital 200 de 20061, norma que actualmente se encuentra derogada<strong> otorg&aacute;ndole tal competencia a la Secretar&iacute;a Distrital del H&aacute;bitat, confiri&eacute;ndole la facultad de convocar, postular y asignar Subsidios Distritales de Vivienda2</strong> para las modalidades de adquisici&oacute;n de vivienda nueva o usada, mejoramiento de vivienda y construcci&oacute;n en sitio propio, con el fin de brindar un apoyo a las <strong>soluciones habitacionales</strong> de la poblaci&oacute;n desplazada.<br />\r\n\t<br />\r\n\tLa Alcald&iacute;a Mayor de Bogot&aacute; expidi&oacute; el Decreto Distrital 063 del 2 de marzo de 2009 que reglament&oacute; el otorgamiento del Subsidio Distrital de Vivienda, el cual fue derogado por el Decreto Distrital 539 del 23 de noviembre de 2012 <em>&quot;Por el cual se reglamenta el subsidio distrital de vivienda en especie en el marco del Plan de Desarrollo Econ&oacute;mico, Social, Ambiental y de Obras P&uacute;blicas Para Bogot&aacute; D. C. 2012 - 2016 - Bogot&aacute; Humana&quot;,</em>&nbsp; que igualmente considera la situaci&oacute;n de la poblaci&oacute;n desplazada y contin&uacute;a otorgando el SDV complementario al Subsidio Familiar de Vivienda que otorga el Gobierno Nacional, en los t&eacute;rminos de la ley 1537 de 2012, ley de vivienda.<br />\r\n\t<br />\r\n\tLa Secretar&iacute;a Distrital del H&aacute;bitat en el marco del Decreto Distrital 539 de noviembre 23 de 2012 expidi&oacute; el Reglamento Operativo para el otorgamiento del Subsidio Distrital de Vivienda en Especie para Vivienda de Inter&eacute;s Prioritario en el Distrito Capital a trav&eacute;s de la Resoluci&oacute;n 176 del 2 de abril de 2013, determinando en el art&iacute;culo 5 que dentro de los hogares que pueden tener acceso al subsidio, est&aacute;n los hogares v&iacute;ctimas del conflicto interno armado.<br />\r\n\t<br />\r\n\tAs&iacute; mismo, es pertinente precisar que los art&iacute;culos 12&ordm; y 39&deg; de la Resoluci&oacute;n 176 del 2 de abril de 2013<em> &ldquo;Por medio de la cual se adopta el reglamento operativo para el otorgamiento del Subsidio Distrital de Vivienda en Especie para Vivienda de Inter&eacute;s Prioritario en el Distrito Capital, en el marco del Decreto Distrital 539 de 2012&rdquo;</em>, establece los requisitos que deben cumplir los hogares en situaci&oacute;n de desplazamiento interno forzado por la violencia, para acceder al Subsidio Distrital de Vivienda, los cuales se trascriben:<br />\r\n\t<br />\r\n\t<em>&ldquo;ART&Iacute;CULO 12. Cierre financiero. (&hellip;) En el caso de hogares v&iacute;ctimas del desplazamiento forzado por el&nbsp; conflicto interno, estos deber&aacute;n acreditar que cuentan con el subsidio asignado por Fonvivienda, por el Banco Agrario de Colombia o las entidades que hagan sus veces&hellip;&rdquo;.<br />\r\n\t&nbsp;&nbsp; &nbsp;<br />\r\n\t&ldquo;ART&Iacute;CULO 39. Requisitos b&aacute;sicos para tener derecho al SDVE. El hogar debe cumplir con los siguientes requisitos para acceder al SDVE por cualquiera de los cuatro esquemas establecidos:<br />\r\n\t<br />\r\n\t1. Que el hogar se encuentre inscrito en el Sistema de Informaci&oacute;n para la Financiaci&oacute;n de Soluciones de Vivienda &ndash; SIFSV- de la Secretar&iacute;a Distrital del H&aacute;bitat - SDHT.<br />\r\n\t2. Que al menos una de las personas que integran el hogar tenga ciudadan&iacute;a colombiana, se encuentre en capacidad de obligarse por s&iacute; misma y resida en Bogot&aacute;.<br />\r\n\t3. Que los ingresos totales mensuales del hogar no sean superiores al equivalente a cuatro (4) salarios m&iacute;nimos legales mensuales vigentes &ndash; SMLMV, sin perjuicio de la aplicaci&oacute;n de criterios de priorizaci&oacute;n.<br />\r\n\t4. Que ninguna persona integrante del hogar se encuentre afiliada a una caja de compensaci&oacute;n familiar que le permita acceder a un subsidio de vivienda otorgado con recursos de esas entidades.<br />\r\n\t5. Que ninguno de los integrantes del hogar haya adquirido una vivienda con recursos procedentes del subsidio nacional de vivienda, del subsidio distrital de vivienda o de los subsidios otorgados por las cajas de compensaci&oacute;n familiar.<br />\r\n\t6. Que ninguna de las personas que integran el hogar sea propietaria o poseedora de vivienda en el territorio nacional. Lo anterior no aplica para la propiedad o posesi&oacute;n de terrenos ubicados en zonas de alto riesgo no mitigable o que correspondan a ronda hidr&aacute;ulica o&nbsp; zona de manejo y protecci&oacute;n ambiental &ndash; ZMPA, o de terrenos donde sea imposible la conexi&oacute;n a servicios p&uacute;blicos domiciliarios de acueducto y alcantarillado. Este requisito no aplica para las modalidades de mejoramiento de vivienda.<br />\r\n\t<br />\r\n\t<strong>PAR&Aacute;GRAFO 1</strong>.&nbsp; Los hogares v&iacute;ctimas del desplazamiento por el conflicto interno deber&aacute;n estar inscritos en el Registro &Uacute;nico de V&iacute;ctimas y ninguna de la personas que integran el&nbsp; hogar podr&aacute; ser propietaria o poseedora de una vivienda en lugar diferente al sitio de desplazamiento. (&hellip;)&rdquo;.</em><br />\r\n\t<br />\r\n\tEs importante se&ntilde;alar que el Subsidio Distrital de Vivienda en Especie es complementario al Subsidio Familiar de Vivienda con relaci&oacute;n a la poblaci&oacute;n desplazada, conforme lo establecido en el Decreto Nacional 1168 de 1996 toda vez que estableci&oacute; en su art&iacute;culo 1&deg;, que los subsidios para vivienda de inter&eacute;s social que los municipios decidan otorgar son complementarios al subsidio nacional de vivienda y podr&aacute;n ser entregados en dinero o en especie, seg&uacute;n lo determinen las autoridades municipales competentes.<br />\r\n\t<br />\r\n\tAdicional a lo anterior, en el marco de nuestras competencias le informamos que la Administraci&oacute;n Distrital, dentro de su pol&iacute;tica no otorga viviendas 100% subsidiadas, pues este&nbsp; programa hace parte de la pol&iacute;tica habitacional del Gobierno Nacional.<br />\r\n\t<br />\r\n\tObserv&aacute;ndose con lo anterior, que el <strong>Distrito Capital ni Metrovivienda, han incumplido o presuntamente violado los derechos enunciados por el accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada, tal como ya se ha fallado sobre lo mismo en instancias judiciales.</strong><br />\r\n\t<br />\r\n\tAdicional a lo anterior, el Concejo de Bogot&aacute;, D.C., mediante el Acuerdo 468 de 2011, autoriz&oacute; al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de desplazamiento interno forzado por la violencia en &aacute;reas por fuera del per&iacute;metro del Distrito Capital, con el fin de facilitar su retorno, al respecto contempla:</p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\t<em><strong>&ldquo;ART&Iacute;CULO 1&ordm;.</strong>- Autorizar al Alcalde Mayor para aplicar el Subsidio Distrital de Vivienda para Hogares que se encuentren en situaci&oacute;n de Desplazamiento Interno Forzado por la Violencia para su reubicaci&oacute;n o retorno.<br />\r\n\t<strong>ART&Iacute;CULO 2</strong>. El Subsidio Distrital de Vivienda para Hogares en condici&oacute;n de Desplazamiento Interno Forzado por la Violencia ser&aacute; asignado para aquellos que se encuentren inscritos en el Distrito Capital en el Registro &Uacute;nico de Poblaci&oacute;n Desplazada.<br />\r\n\t<strong>ART&Iacute;CULO 3</strong>. El monto que se otorgue como Subsidio Distrital de Vivienda a cada hogar en situaci&oacute;n de Desplazamiento Interno Forzado ser&aacute; hasta por el mismo valor del subsidio de vivienda que otorga el gobierno Nacional.<br />\r\n\t<strong>ART&Iacute;CULO 4</strong>. La Secretar&iacute;a Distrital del H&aacute;bitat informar&aacute; al alcalde municipal del lugar en el que se aplique el subsidio distrital de vivienda, a las entidades distritales y a FONVIVIENDA, para los efectos de su competencia.<br />\r\n\t<strong>ART&Iacute;CULO 5</strong>. La administraci&oacute;n distrital establecer&aacute; el procedimiento para el desembolso del Subsidio Distrital de Vivienda...&rdquo;</em></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tEn ese orden de ideas, el <strong>Distrito Capital y en especial Metrovivienda no han incumplido o presuntamente violado los derechos enunciados por la accionante y los otorgados por la Ley y por v&iacute;a jurisprudencial a la poblaci&oacute;n desplazada,</strong> toda vez que Metrovivienda y la Secretar&iacute;a Distrital del H&aacute;bitat, han contribuido al mejoramiento de las condiciones socioecon&oacute;micas de la poblaci&oacute;n desplazada, asignando recursos econ&oacute;micos a trav&eacute;s del Subsidio Distrital de Vivienda, para cubrir las necesidades habitacionales que tiene la poblaci&oacute;n en esta situaci&oacute;n, dirigiendo de esta manera la actuaci&oacute;n de la Administraci&oacute;n Distrital al cumplimiento de los fines consagrados en el <strong>art&iacute;culo 13 y 51 de la Constituci&oacute;n Pol&iacute;tica3.</strong><br />\r\n\t<br />\r\n\tAhora bien, para el caso de la poblaci&oacute;n en situaci&oacute;n de desplazamiento, aunque la atenci&oacute;n prioritaria a las necesidades de la misma es un imperativo para diferentes instancias del Estado, debe advertirse que esto no implica que se dejen de regular o establecer formas de encauzar la ayuda destinada a este sector de poblaci&oacute;n. La simple condici&oacute;n de desplazado no habilita de manera autom&aacute;tica para recibir subvenciones, puesto que se requiere de unos m&iacute;nimos legales para su entrega. En el caso concreto del derecho a la vivienda para la poblaci&oacute;n desplazada en Bogot&aacute; D.C. existe una regulaci&oacute;n,&nbsp; que obedece tambi&eacute;n a proyectos y recursos destinados en cada vigencia fiscal para el cumplimiento de las metas.</p>\r\n<p style="text-align: center;">\r\n\t<br />\r\n\t<u><strong>IMPROCEDENCIA DE LA ACCI&Oacute;N DE TUTELA EN RELACI&Oacute;N CON METROVIVIENDA</strong></u></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tCon fundamento en el anterior an&aacute;lisis, el Decreto 2591 de 1991 establece la procedencia de la Acci&oacute;n de Tutela de la siguiente manera:<br />\r\n\t<br />\r\n\t<em>&ldquo;Art&iacute;culo 5o. PROCEDENCIA DE LA ACCION DE TUTELA: La acci&oacute;n de tutela procede contra toda acci&oacute;n u omisi&oacute;n de las autoridades p&uacute;blicas, que haya violado, viole o amenace violar cualquiera de los derechos de que trata el art&iacute;culo 2o. de esta ley. Tambi&eacute;n procede contra acciones u omisiones de particulares, de conformidad con lo establecido en el Cap&iacute;tulo III de este Decreto. La procedencia de la tutela en ning&uacute;n caso est&aacute; sujeta a que la acci&oacute;n de la autoridad o del particular se haya manifestado en un acto jur&iacute;dico escrito.&rdquo;</em><br />\r\n\t<br />\r\n\tNos permitimos afirmar que esta acci&oacute;n constitucional es IMPROCEDENTE, por cuanto no existe violaci&oacute;n, vulneraci&oacute;n o amenaza de un derecho fundamental por parte de METROVIVIENDA, al no ser en el Distrito Capital la entidad responsable de la asignaci&oacute;n y administraci&oacute;n de los recursos del Subsidio Distrital de Vivienda, ni de la competencia para la estabilizaci&oacute;n de las familias que se encuentran en situaci&oacute;n de desplazamiento interno forzado por la violencia.<br />\r\n\tCon base en lo anteriormente expuesto, me permito formular muy respetuosamente la siguiente:</p>\r\n<p style="text-align: center;">\r\n\t<strong><u>PETICI&Oacute;N</u></strong></p>\r\n<p style="text-align: justify;">\r\n\t<br />\r\n\tPor todo lo anterior se&ntilde;or Juez, de acuerdo con las normas parcialmente transcritas y la Jurisprudencia citada, se solicita denegar las pretensiones de la acci&oacute;n de tutela impetrada, respecto a METROVIVIENDA por considerarla improcedente, debido a que esta entidad no vulner&oacute; o amenaza vulnerar derecho fundamental alguno del accionante, siendo improcedente tutelar los derechos fundamentales invocados, conforme se demostr&oacute; en la parte motiva del presente escrito de contestaci&oacute;n.<br />\r\n\t<br />\r\n\tCordialmente,<br />\r\n\t<br />\r\n\t<strong>Ilva Nubia Herrera G&aacute;lvez</strong><br />\r\n\tDirectora Jur&iacute;dica<br />\r\n\t<br />\r\n\tProyect&oacute;: Lucelly Laverde Rico &ndash; Contratista Direcci&oacute;n Jur&iacute;dica<br />\r\n\tRevis&oacute;: Ilva Nubia Herrera G&aacute;lvez &ndash; Directora Jur&iacute;dica<br />\r\n\tAnexo: 3 folios<br />\r\n\t<br />\r\n\tID: 452699</p>\r\n
40	\N	Memorando	2014-07-19 16:33:44.938301	900	13	3	<p>\r\n\t<strong>Bogota, Lunes, 26 de mayo de 2014</strong><br />\r\n\t<br />\r\n\t<br />\r\n\tSe&ntilde;or(a)<br />\r\n\t*NOM_R*<br />\r\n\t<br />\r\n\t&nbsp;</p>\r\n
41	\N	AAAA	2014-07-21 18:39:12.560075	900	1	1	<p>\r\n\t<strong>Bogot&aacute;, Lunes, 21 de julio de 2014</strong><br />\r\n\t<br />\r\n\t<br />\r\n\tSe&ntilde;or(a)<br />\r\n\t<br />\r\n\t<br />\r\n\t&nbsp;</p>\r\n
44	\N	Wilson Hernández Ortiz	2014-10-02 13:37:51.311437	900	1	1	<p>\r\n\t<strong>Bogot&aacute;, Jueves, 02 de octubre de 2014</strong><br />\r\n\t<br />\r\n\t<br />\r\n\tSe&ntilde;or(a)<br />\r\n\t<br />\r\n\twilho123.123@gmail.com<br />\r\n\t&nbsp;</p>\r\n
46	\N	prueba mc	2014-10-03 17:53:21.398656	900	1	2	<p>\r\n\tCampos a Combinar (est&aacute;n con espacios en vez de _)</p>\r\n<p>\r\n\t*RAD S* &nbsp; &nbsp; *RAD E PADRE* &nbsp; &nbsp; *CTA INT* &nbsp; &nbsp; *ASUNTO* &nbsp; &nbsp; *F RAD E* &nbsp; &nbsp; *SAN FECHA RADICADO* &nbsp; &nbsp; *NOM R* &nbsp; &nbsp; *DIR R* &nbsp; &nbsp; *DIR E* &nbsp; &nbsp; *DEPTO R* &nbsp; &nbsp; *MPIO R* &nbsp; &nbsp; *TEL R* &nbsp; &nbsp; *MAIL R* &nbsp; &nbsp; *DOC R* &nbsp; &nbsp; *NOM P* &nbsp; &nbsp; *DIR P* &nbsp; &nbsp; *DEPTO P* &nbsp; &nbsp; *MPIO P* &nbsp; &nbsp; *TEL P* &nbsp; &nbsp; *MAIL P* &nbsp; &nbsp; *DOC P* &nbsp; &nbsp; *NOM E* &nbsp; &nbsp; *DIR E* &nbsp; &nbsp; *MPIO E* &nbsp; &nbsp; *DEPTO E* &nbsp; &nbsp; *TEL E* &nbsp; &nbsp; *MAIL E* &nbsp; &nbsp; *NIT E* &nbsp; &nbsp; *NUIR E* &nbsp; &nbsp; *F RAD S* &nbsp; &nbsp; *RAD E* &nbsp; &nbsp; *SAN RADICACION* &nbsp; &nbsp; *SECTOR* &nbsp; &nbsp; *NRO PAGS* &nbsp; &nbsp; *DESC ANEXOS* &nbsp; &nbsp; *F HOY CORTO* &nbsp; &nbsp; *F HOY* &nbsp; &nbsp; *NUM DOCTO* &nbsp; &nbsp; *F DOCTO* &nbsp; &nbsp; *F DOCTO1* &nbsp; &nbsp; *FUNCIONARIO* &nbsp; &nbsp; *LOGIN* &nbsp; &nbsp; *DEP NOMB* &nbsp; &nbsp; *CIU TER* &nbsp; &nbsp; *DEP SIGLA* &nbsp; &nbsp; *TER* &nbsp; &nbsp; *DIR TER* *TER L* &nbsp; &nbsp; *NOM REC* &nbsp; &nbsp; *EXPEDIENTE* &nbsp; &nbsp; *NUM EXPEDIENTE* &nbsp; &nbsp; *DIGNATARIO* &nbsp; &nbsp; *DEPE CODI* &nbsp; &nbsp; *DEPENDENCIA* &nbsp; &nbsp; *DEPENDENCIA NOMBRE*</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\tCampos separados con rayas:</p>\r\n<p>\r\n\t&nbsp; - &nbsp;RAD_S &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;RAD_E_PADRE &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;CTA_INT &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;ASUNTO &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;F_RAD_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;SAN_FECHA_RADICADO &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NOM_R &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DIR_R &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DIR_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEPTO_R &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;MPIO_R &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;TEL_R &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;MAIL_R &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DOC_R &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NOM_P &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DIR_P &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEPTO_P &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;MPIO_P &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;TEL_P &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;MAIL_P &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DOC_P &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NOM_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DIR_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;MPIO_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEPTO_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;TEL_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;MAIL_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NIT_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NUIR_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;F_RAD_S &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;RAD_E &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;SAN_RADICACION &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;SECTOR &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NRO_PAGS &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DESC_ANEXOS &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;F_HOY_CORTO &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;F_HOY &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NUM_DOCTO &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;F_DOCTO &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;F_DOCTO1 &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;FUNCIONARIO &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;LOGIN &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEP_NOMB &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;CIU_TER &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEP_SIGLA &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;TER &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DIR_TER &nbsp;- &nbsp; &nbsp; - &nbsp;TER_L &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NOM_REC &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;EXPEDIENTE &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;NUM_EXPEDIENTE &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DIGNATARIO &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEPE_CODI &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEPENDENCIA &nbsp;- &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;DEPENDENCIA_NOMBRE &nbsp;- &nbsp;</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\tCampos Separados con asteriscos:</p>\r\n<p>\r\n\t*RAD_S* &nbsp; &nbsp; *RAD_E_PADRE* &nbsp; &nbsp; *CTA_INT* &nbsp; &nbsp; *ASUNTO* &nbsp; &nbsp; *F_RAD_E* &nbsp; &nbsp; *SAN_FECHA_RADICADO* &nbsp; &nbsp; *NOM_R* &nbsp; &nbsp; *DIR_R* &nbsp; &nbsp; *DIR_E* &nbsp; &nbsp; *DEPTO_R* &nbsp; &nbsp; *MPIO_R* &nbsp; &nbsp; *TEL_R* &nbsp; &nbsp; *MAIL_R* &nbsp; &nbsp; *DOC_R* &nbsp; &nbsp; *NOM_P* &nbsp; &nbsp; *DIR_P* &nbsp; &nbsp; *DEPTO_P* &nbsp; &nbsp; *MPIO_P* &nbsp; &nbsp; *TEL_P* &nbsp; &nbsp; *MAIL_P* &nbsp; &nbsp; *DOC_P* &nbsp; &nbsp; *NOM_E* &nbsp; &nbsp; *DIR_E* &nbsp; &nbsp; *MPIO_E* &nbsp; &nbsp; *DEPTO_E* &nbsp; &nbsp; *TEL_E* &nbsp; &nbsp; *MAIL_E* &nbsp; &nbsp; *NIT_E* &nbsp; &nbsp; *NUIR_E* &nbsp; &nbsp; *F_RAD_S* &nbsp; &nbsp; *RAD_E* &nbsp; &nbsp; *SAN_RADICACION* &nbsp; &nbsp; *SECTOR* &nbsp; &nbsp; *NRO_PAGS* &nbsp; &nbsp; *DESC_ANEXOS* &nbsp; &nbsp; *F_HOY_CORTO* &nbsp; &nbsp; *F_HOY* &nbsp; &nbsp; *NUM_DOCTO* &nbsp; &nbsp; *F_DOCTO* &nbsp; &nbsp; *F_DOCTO1* &nbsp; &nbsp; *FUNCIONARIO* &nbsp; &nbsp; *LOGIN* &nbsp; &nbsp; *DEP_NOMB* &nbsp; &nbsp; *CIU_TER* &nbsp; &nbsp; *DEP_SIGLA* &nbsp; &nbsp; *TER* &nbsp; &nbsp; *DIR_TER* *TER_L* &nbsp; &nbsp; *NOM_REC* &nbsp; &nbsp; *EXPEDIENTE* &nbsp; &nbsp; *NUM_EXPEDIENTE* &nbsp; &nbsp; *DIGNATARIO* &nbsp; &nbsp; *DEPE_CODI* &nbsp; &nbsp; *DEPENDENCIA* &nbsp; &nbsp; *DEPENDENCIA_NOMBRE*</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t&nbsp;</p>\r\n<p>\r\n\t<strong>Bogot&aacute;, Viernes, 03 de octubre de 2014</strong><br />\r\n\t<br />\r\n\t<br />\r\n\tSe&ntilde;or(a)<br />\r\n\t<br />\r\n\txxxxxxx<br />\r\n\t&nbsp;</p>\r\n
47	\N		2015-11-26 10:25:59.4869	900	1	3	
\.


--
-- Data for Name: sgd_pnufe_procnumfe; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_pnufe_procnumfe (sgd_pnufe_codi, sgd_tpr_codigo, sgd_pnufe_descrip, sgd_pnufe_serie) FROM stdin;
1	29	Resolucion - RAP (Combo)	\N
2	31	Resolucion - REP (Combo)	\N
3	32	Resolucion - REQ (Combo)	\N
4	33	Resolucion - REV (Combo)	\N
5	34	Pliego de Cargos - SAP (Combo)	\N
6	34	Resolucion - SAP (Combo)	\N
\.


--
-- Data for Name: sgd_pnun_procenum; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_pnun_procenum (sgd_pnun_codi, sgd_pnufe_codi, depe_codi, sgd_pnun_prepone) FROM stdin;
\.


--
-- Data for Name: sgd_prc_proceso; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_prc_proceso (sgd_prc_codigo, sgd_prc_descrip, sgd_prc_fech_ini, sgd_prc_fech_fin) FROM stdin;
1	prueba	\N	\N
\.


--
-- Data for Name: sgd_prd_prcdmentos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_prd_prcdmentos (sgd_prd_codigo, sgd_prc_codigo, sgd_prd_descrip, sgd_prd_fech_ini, sgd_prd_fech_fin) FROM stdin;
\.


--
-- Data for Name: sgd_rad_metadatos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_rad_metadatos (rad_meta_id, rad_meta_proceso, rad_meta_datos, rad_meta_etapa, radi_nume_radi, sgd_exp_numero) FROM stdin;
\.


--
-- Data for Name: sgd_rda_retdoca; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_rda_retdoca (anex_radi_nume, anex_codigo, radi_nume_salida, anex_borrado, sgd_mrd_codigo, depe_codi, usua_codi, usua_doc, sgd_rda_fech, sgd_deve_codigo, anex_solo_lect, anex_radi_fech, anex_estado, anex_nomb_archivo, anex_tipo, sgd_dir_tipo) FROM stdin;
\.


--
-- Data for Name: sgd_rdf_retdocf; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_rdf_retdocf (id, sgd_mrd_codigo, radi_nume_radi, depe_codi, usua_codi, usua_doc, sgd_rdf_fech) FROM stdin;
2	3	20179000000012	900	1	10153900001	2017-06-09 18:30:23.546865
\.


--
-- Data for Name: sgd_renv_regenvio; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_renv_regenvio (id, sgd_renv_codigo, sgd_fenv_codigo, sgd_renv_fech, radi_nume_sal, sgd_renv_destino, sgd_renv_telefono, sgd_renv_mail, sgd_renv_peso, sgd_renv_valor, sgd_renv_certificado, sgd_renv_estado, usua_doc, sgd_renv_nombre, sgd_rem_destino, sgd_dir_codigo, sgd_renv_planilla, sgd_renv_fech_sal, depe_codi, sgd_dir_tipo, radi_nume_grupo, sgd_renv_dir, sgd_renv_depto, sgd_renv_mpio, sgd_renv_tel, sgd_renv_cantidad, sgd_renv_tipo, sgd_renv_observa, sgd_renv_grupo, sgd_deve_codigo, sgd_deve_fech, sgd_renv_valortotal, sgd_renv_valistamiento, sgd_renv_vdescuento, sgd_renv_vadicional, sgd_depe_genera, sgd_renv_pais) FROM stdin;
\.


--
-- Data for Name: sgd_rfax_reservafax; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_rfax_reservafax (sgd_rfax_codigo, sgd_rfax_fax, usua_login, sgd_rfax_fech, sgd_rfax_fechradi, radi_nume_radi, sgd_rfax_observa, sgd_rfax_dhojas) FROM stdin;
\.


--
-- Data for Name: sgd_rmr_radmasivre; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_rmr_radmasivre (sgd_rmr_grupo, sgd_rmr_radi) FROM stdin;
20162010248191	20162010248631
20162010248191	20162010248641
20162010298931	20162010299031
\.


--
-- Data for Name: sgd_sbrd_subserierd; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_sbrd_subserierd (sgd_srd_codigo, sgd_sbrd_codigo, sgd_sbrd_descrip, sgd_sbrd_fechini, sgd_sbrd_fechfin, sgd_sbrd_tiemag, sgd_sbrd_tiemac, sgd_sbrd_dispfin, sgd_sbrd_soporte, sgd_sbrd_procedi, id, sgd_srd_id, sgd_sbrd_estado) FROM stdin;
900	1	SUBSERIE DE  PRUEBA 2	2017-05-04 00:00:00	2037-05-04 00:00:00	5	5	1	1	asdfa afasdfasdf asdf	2	2	1
\.


--
-- Data for Name: sgd_sed_sede; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_sed_sede (sgd_sed_codi, sgd_sed_desc, sgd_tpr_codigo) FROM stdin;
1	Misma sede	34
2	Otra sede	34
\.


--
-- Data for Name: sgd_senuf_secnumfe; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_senuf_secnumfe (sgd_senuf_codi, sgd_pnufe_codi, depe_codi, sgd_senuf_sec) FROM stdin;
2	1	820	NUM_RESOL_DTN
3	1	830	NUM_RESOL_DTOC
4	1	840	NUM_RESOL_DTOR
5	1	850	NUM_RESOL_DTS
6	2	800	NUM_RESOL_GRAL
7	2	810	NUM_RESOL_DTC
1	1	814	NUM_RESOL_DTC
8	2	814	NUM_RESOL_DTC
9	2	815	NUM_RESOL_DTC
10	2	820	NUM_RESOL_DTN
11	2	830	NUM_RESOL_DTOC
12	2	840	NUM_RESOL_DTOR
13	2	850	NUM_RESOL_DTS
14	3	800	NUM_RESOL_GRAL
15	3	810	NUM_RESOL_DTC
16	3	814	NUM_RESOL_DTC
17	3	815	NUM_RESOL_DTC
18	3	820	NUM_RESOL_DTN
19	3	830	NUM_RESOL_DTOC
20	3	840	NUM_RESOL_DTOR
21	3	850	NUM_RESOL_DTS
22	4	800	NUM_RESOL_GRAL
23	4	810	NUM_RESOL_DTC
24	4	814	NUM_RESOL_DTC
25	4	815	NUM_RESOL_DTC
26	4	820	NUM_RESOL_DTN
27	4	830	NUM_RESOL_DTOC
28	4	840	NUM_RESOL_DTOR
29	4	850	NUM_RESOL_DTS
30	5	815	NUM_RESOL_DTC
31	5	820	NUM_RESOL_DTN
32	5	830	NUM_RESOL_DTOC
33	5	840	NUM_RESOL_DTOR
34	5	850	NUM_RESOL_DTS
35	6	815	NUM_RESOL_DTC
36	6	820	NUM_RESOL_DTN
37	6	830	NUM_RESOL_DTOC
38	6	840	NUM_RESOL_DTOR
39	6	850	NUM_RESOL_DTS
41	1	905	sec_rinterna_905
40	1	900	NUM_RESOL_DTN
\.


--
-- Data for Name: sgd_sexp_secexpedientes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_sexp_secexpedientes (sgd_exp_numero, sgd_srd_codigo, sgd_sbrd_codigo, sgd_sexp_secuencia, depe_codi, usua_doc, sgd_sexp_fech, sgd_fexp_codigo, sgd_sexp_ano, usua_doc_responsable, sgd_sexp_parexp1, sgd_sexp_parexp2, sgd_sexp_parexp3, sgd_sexp_parexp4, sgd_sexp_parexp5, sgd_pexp_codigo, sgd_exp_fech_arch, sgd_fld_codigo, sgd_exp_fechflujoant, sgd_mrd_codigo, sgd_exp_subexpediente, sgd_exp_privado, sgd_sexp_fechafin, sgd_exp_caja, id, sgd_sexp_parexp6, sgd_sexp_parexp7, sgd_sexp_parexp8, sgd_sexp_parexp9, sgd_sexp_parexp10, sgd_sexp_prestamo, sgd_cerrado, sgd_sexp_estado, sgd_srd_id, sgd_sbrd_id) FROM stdin;
\.


--
-- Data for Name: sgd_sop_soporte; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_sop_soporte (sgd_sop_id, usua_codi, depe_codi, sgd_sop_coment, sgd_sop_est, sgd_tsop_id, sgd_sop_fechaini, sgd_sop_fechafin) FROM stdin;
\.


--
-- Data for Name: sgd_srd_seriesrd; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_srd_seriesrd (sgd_srd_codigo, sgd_srd_descrip, sgd_srd_fechini, sgd_srd_fechfin, id, sgd_srd_estado) FROM stdin;
900	PRUEBAS	2017-05-04 00:00:00	2026-05-27 00:00:00	2	1
\.


--
-- Data for Name: sgd_tar_tarifas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tar_tarifas (sgd_fenv_codigo, sgd_tar_codser, sgd_tar_codigo, sgd_tar_valenv1, sgd_tar_valenv2, sgd_tar_valenv1g1, sgd_clta_codser, sgd_tar_valenv2g2, sgd_clta_descrip) FROM stdin;
115	\N	1	1	1	0	1	0	\N
103	\N	1	1	1	0	1	0	\N
109	\N	2	1	2	0	1	0	\N
101	\N	6	1	2	0	1	0	\N
101	\N	5	0	0	1	2	2	\N
101	\N	65	5500	6200	0	1	0	\N
101	\N	5	25000	35000	0	1	0	\N
101	\N	500	8000	10000	0	1	0	\N
101	\N	20163	7300	8600	0	1	0	\N
101	\N	10	5500	6200	0	1	0	\N
15	\N	15	4500	6500	0	1	0	\N
101	\N	152	75000	80000	0	1	0	\N
101	\N	25	6800	10000	0	1	0	\N
101	\N	1	5200	5200	0	1	0	\N
109	\N	12	5000	10000	0	1	0	\N
20	\N	99	1000	1500	0	1	0	\N
106	\N	1	1	1	0	1	0	\N
105	\N	10	0	0	0	1	0	\N
102	\N	102	6000	6000	0	1	0	\N
71	\N	3	1	1	0	1	0	\N
\.


--
-- Data for Name: sgd_tdec_tipodecision; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tdec_tipodecision (sgd_apli_codi, sgd_tdec_codigo, sgd_tdec_descrip, sgd_tdec_sancionar, sgd_tdec_firmeza, sgd_tdec_versancion, sgd_tdec_showmenu, sgd_tdec_updnotif, sgd_tdec_veragota) FROM stdin;
\.


--
-- Data for Name: sgd_tdf_tipodefallos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tdf_tipodefallos (sgd_tdf_codigo, sgd_tdf_nombre_fallo) FROM stdin;
1	ACLARATORIA
2	AMONESTACION
3	ARCHIVA
4	CADUCIDAD
6	CONFIRMAR
7	DECRETA PRUEBA
8	IMPROCEDENTE
11	INHIBIRSE
12	MODIFICAR
13	NO ACCEDER
16	NULIDAD
17	ORDENA RECONSTRUCCION
14	PROCEDENTE
15	RECHAZA PRUEBA
10	RECHAZAR
5	REVOCAR
9	SANCIONA
18	SOLICITUD DE PRORROGA
19	DEJAR SIN EFECTO
\.


--
-- Data for Name: sgd_tid_tipdecision; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tid_tipdecision (sgd_tid_codi, sgd_tid_desc, sgd_tpr_codigo, sgd_pexp_codigo, sgd_tpr_codigop) FROM stdin;
\.


--
-- Data for Name: sgd_tidm_tidocmasiva; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tidm_tidocmasiva (sgd_tidm_codi, sgd_tidm_desc) FROM stdin;
1	PLANTILLA
2	CSV
\.


--
-- Data for Name: sgd_tip3_tipotercero; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tip3_tipotercero (sgd_tip3_codigo, sgd_dir_tipo, sgd_tip3_nombre, sgd_tip3_desc, sgd_tip3_imgpestana, sgd_tpr_tp1, sgd_tpr_tp2, sgd_tpr_tp3, sgd_tpr_tp4, sgd_tpr_tp5, sgd_tpr_tp9, sgd_tpr_tp7, sgd_tpr_tp8, sgd_tpr_tp6) FROM stdin;
2	1	DESTINATARIO	DESTINATARIO	tip3destina.jpg	1	0	0	1	1	1	1	1	1
3	2	PREDIO	PREDIO	tip3predio.jpg	1	1	0	1	1	1	1	1	1
1	1	REMITENTE	REMITENTE	tip3remitente.jpg	0	1	0	0	0	1	0	0	0
\.


--
-- Data for Name: sgd_tma_temas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tma_temas (sgd_tma_codigo, depe_codi, sgd_prc_codigo, sgd_tma_descrip) FROM stdin;
\.


--
-- Data for Name: sgd_tme_tipmen; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tme_tipmen (sgd_tme_codi, sgd_tme_desc) FROM stdin;
1	POP-UP
\.


--
-- Data for Name: sgd_tpr_tpdcumento; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tpr_tpdcumento (sgd_tpr_codigo, sgd_tpr_descrip, sgd_tpr_termino, sgd_tpr_tpuso, sgd_tpr_numera, sgd_tpr_radica, sgd_tpr_tp1, sgd_tpr_tp2, sgd_tpr_tp3, sgd_tpr_estado, sgd_termino_real, sgd_tpr_web, sgd_tpr_tp4, sgd_tpr_tp5, sgd_tpr_tp9, sgd_tpr_tp7, sgd_tpr_tp8, sgd_tpr_tp6, id) FROM stdin;
0	No Definido	0	\N	\N	\N	1	1	0	1	\N	0	\N	\N	\N	\N	\N	\N	1
1	PREUBA JH	5	\N	\N	0	1	1	0	1	\N	0	\N	\N	\N	\N	\N	\N	2
\.


--
-- Data for Name: sgd_trad_tiporad; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_trad_tiporad (sgd_trad_codigo, sgd_trad_descr, sgd_trad_icono, sgd_trad_genradsal, sgd_trad_diasbloqueo, sgd_trad_alerta, sgd_trad_tiempo_alerta) FROM stdin;
1	Salida	RadSalida.gif	1	\N	\N	\N
2	Entrada	RadEntrada.gif	1	\N	\N	\N
3	Memorando	RadInterna.gif	1	\N	\N	\N
\.


--
-- Data for Name: sgd_tsop_tiposoporte; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_tsop_tiposoporte (sgd_tsop_id, sgd_tsop_descr, sgd_tsop_depe_codi, sgd_tsop_usua_codi, sgd_tsop_estado) FROM stdin;
\.


--
-- Data for Name: sgd_ttr_transaccion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_ttr_transaccion (sgd_ttr_codigo, sgd_ttr_descrip) FROM stdin;
61	Cambio de Etapa del Expediente
40	Firma Digital de Documento
41	Eliminacion solicitud de Firma Digital
50	Cambio de Estado Expediente
51	Creacion Expediente
1	Recuperacion Radicado
8	Informar
19	Cambiar Tipo de Documento
20	Crear Registro
21	Editar Registro
10	Movimiento entre Carpetas
7	Borrar Informado
14	Agendar
15	Sacar de la agenda
0	--
16	Reasignar para Vo.Bo.
2	Radicacion
22	Digitalizacion de Radicado
23	Digitalizacion - Modificacion
24	Asociacion Imagen fax
30	Radicacion Masiva
17	Modificacion de Causal
18	Modificacion del Sector
25	Solicitud de Anulacion
26	Anulacion Rad
27	Rechazo de Anulacion
37	Cambio de Estado del Documento
28	Devolucion de correo
29	Digitalizacion de Anexo
31	Borrado de Anexo a radicado
32	Modificacion TRD
33	Eliminar TRD
35	Tipificacion de la decision
36	Cambio en la Notificacion
38	Cambio Vinculacion Documento
39	Solicitud de Firma
42	Digitalizacion Radicado(Asoc. Imagen Web)
60	Cambio seguridad Expediente
52	Excluir radicado de expediente
53	Incluir radicado en expediente
54	Cambio Seguridad del Documento
57	Ingreso al Archivo Fisico
65	Archivar NRR
55	Creación Subexpediente
56	Cambio de Responsable
58	Expediente Cerrado
59	Expediente Reabierto
90	Devolucion Correspondencia por No entrega de Fisico a tiempo
70	Edición de formulario
9	Enviado
12	Devuelto
11	Modificación Radicado
91	Anexo
97	Regenerar
96	Radicacion anexo
13	Finalizar Trámite
6	Envio de Respuesta por correo electronico
62	Descargar expediente
67	Prestamo
66	Solicitud de Prestamo
68	Devolver Documento Prestado
69	Cancelar Solicitud de Prestado
88	Cambio de Prioridad
89	Trabajo en Grupo
98	Eliminar TRD anexo
99	Rechazar solicitud de prestamo
\.


--
-- Data for Name: sgd_ush_usuhistorico; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_ush_usuhistorico (sgd_ush_admcod, sgd_ush_admdep, sgd_ush_admdoc, sgd_ush_usucod, sgd_ush_usudep, sgd_ush_usudoc, sgd_ush_modcod, sgd_ush_fechevento, sgd_ush_usulogin) FROM stdin;
\.


--
-- Data for Name: sgd_usm_usumodifica; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sgd_usm_usumodifica (sgd_usm_modcod, sgd_usm_moddescr) FROM stdin;
\.


--
-- Data for Name: tbl_table_survey; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.tbl_table_survey (id, id_proyecto, name_table) FROM stdin;
\.


--
-- Data for Name: tipo_doc_identificacion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.tipo_doc_identificacion (tdid_codi, tdid_desc) FROM stdin;
0	Cedula de Ciudadania
1	Tarjeta de Identidad
2	Cedula de Extranjeria
3	Pasaporte
4	Nit
5	Nuir
\.


--
-- Data for Name: tipo_remitente; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.tipo_remitente (trte_codi, trte_desc, sgd_edd_codi) FROM stdin;
0	ENTIDAD	\N
1	OTRA EMPRESA	\N
2	PERSONA NATURAL	\N
5	PREDIO	\N
3	OTRO	\N
\.


--
-- Data for Name: ubicacion_fisica; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.ubicacion_fisica (ubic_depe_radi, ubic_depe_arch, ubic_inv_piso, ubic_inv_piso_desc, ubic_inv_itemso, ubic_inv_itemsn, ubic_inv_archivador) FROM stdin;
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.usuario (id, usua_codi, depe_codi, usua_login, usua_fech_crea, usua_pasw, usua_esta, usua_nomb, perm_radi, usua_admin, usua_nuevo, usua_doc, codi_nivel, usua_sesion, usua_fech_sesion, usua_ext, usua_nacim, usua_email, usua_at, usua_piso, perm_radi_sal, usua_admin_archivo, usua_masiva, usua_perm_dev, usua_perm_numera_res, usua_doc_suip, usua_perm_numeradoc, sgd_panu_codi, usua_prad_tp1, usua_prad_tp2, usua_prad_tp3, usua_perm_envios, usua_perm_modifica, usua_perm_impresion, usua_prad_tp9, sgd_aper_codigo, usu_telefono1, usua_encuesta, sgd_perm_estadistica, usua_perm_sancionados, usua_admin_sistema, usua_perm_trd, usua_perm_firma, usua_perm_prestamo, usuario_publico, usuario_reasignar, usua_perm_notifica, usua_perm_expediente, usua_login_externo, id_pais, id_cont, perm_tipif_anexo, perm_vobo, perm_archi, perm_borrar_anexo, usua_perm_adminflujos, usua_perm_comisiones, usua_exp_trd, usua_perm_rademail, sgd_rol_codigo, usua_email_1, usua_email_2, usua_perm_respuesta, idacapella1, usua_prad_tp4, idacapella, usua_prad_tp5, usua_prad_tp7, usua_archivo_dig, usua_auth_ldap, usua_login_ldap, usua_prad_tpx1, usua_prad_tpx2, usua_prad_tpx3, usua_prad_tp8, usua_prad_tp6, usua_perm_td) FROM stdin;
2	3	900	CAPACIT1	2015-04-18 09:35:36	1dc9bdb52d04dc20036dbd8313	1	CAPACIT1	0	0	0	999000	1	l9i2fmfrjkcdgrrioucl1gfqe0	2016-06-09 21:36:42.95942	\N	\N	orfeopruebas1@gmail.com	\N	\N	0	0	0	0	\N	\N	\N	\N	0	0	0	0	0	0	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	170	1	\N	1	1	\N	0	\N	\N	\N	0	orfeopruebas@gmail.com	orfeopruebas@gmail.com	0	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	,
1	1	900	ADMON	2007-09-21 00:00:00	2960f70941d29b6123e6ebe493	1	ADMINISTRADOR	1	1	0	10153900001	5	190730052954olocal1ADMON	2019-07-30 12:29:54.99283	1111	\N	orfeopruebas1@gmail.com	\N	5	2	2	1	1	1	\N	\N	3	3	1	0	1	1	2	1	\N	\N	\N	2	\N	1	1	0	1	0	1	0	1	\N	170	1	1	0	1	1	1	\N	0	0	0	pruebascnsc@gmail.com	\N	1	\N	3	\N	3	\N	\N	\N	\N	\N	\N	\N	\N	\N	,
\.


--
-- Name: anexos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.anexos_id_seq', 146617, true);


--
-- Name: choices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.choices_id_seq', 1, false);


--
-- Name: choices_table_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.choices_table_id_seq', 1, false);


--
-- Name: dependencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.dependencia_id_seq', 92, true);


--
-- Name: field_type_id_field_type_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.field_type_id_field_type_seq', 1, false);


--
-- Name: fields_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.fields_id_seq', 1, false);


--
-- Name: fields_idpk_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.fields_idpk_seq', 1, false);


--
-- Name: frmf_frmfields_frmf_code_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.frmf_frmfields_frmf_code_seq', 272, true);


--
-- Name: frmf_frmfields_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.frmf_frmfields_id_seq', 239, true);


--
-- Name: fun_funcionario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.fun_funcionario_id_seq', 1, false);


--
-- Name: hist_eventos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hist_eventos_id_seq', 497, true);


--
-- Name: projects_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.projects_id_seq', 1, false);


--
-- Name: radicado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.radicado_id_seq', 163, true);


--
-- Name: sec_ciu_ciudadano; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sec_ciu_ciudadano', 2, true);


--
-- Name: sec_dir_direcciones; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sec_dir_direcciones', 5, true);


--
-- Name: sec_dir_drecciones; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sec_dir_drecciones', 168, true);


--
-- Name: sec_edificio; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sec_edificio', 127, true);


--
-- Name: sec_oem_oempresas; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sec_oem_oempresas', 1, true);


--
-- Name: sec_prestamo; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sec_prestamo', 1, true);


--
-- Name: secr_planillas; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_planillas', 1, true);


--
-- Name: secr_tp1_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp1_900', 0, true);


--
-- Name: secr_tp2_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp2_900', 4, true);


--
-- Name: secr_tp3_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp3_900', 1, false);


--
-- Name: secr_tp4_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp4_900', 1, false);


--
-- Name: secr_tp5_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp5_900', 1, false);


--
-- Name: secr_tp6_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp6_900', 1, false);


--
-- Name: secr_tp7_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp7_900', 1, false);


--
-- Name: secr_tp8_900; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.secr_tp8_900', 1, false);


--
-- Name: sgd_acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_acl_id_seq', 31, true);


--
-- Name: sgd_acl_profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_acl_profiles_id_seq', 11, true);


--
-- Name: sgd_agen_agendados_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_agen_agendados_id_seq', 1, true);


--
-- Name: sgd_anu_id; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_anu_id', 1, true);


--
-- Name: sgd_dir_direcciones; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_dir_direcciones', 1, true);


--
-- Name: sgd_dir_drecciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_dir_drecciones_id_seq', 7, true);


--
-- Name: sgd_exp_expediente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_exp_expediente_id_seq', 1, true);


--
-- Name: sgd_hfld_histflujodoc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_hfld_histflujodoc_id_seq', 8, true);


--
-- Name: sgd_mrd_matrird_sgd_mrd_codigo_new_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_mrd_matrird_sgd_mrd_codigo_new_seq', 3, true);


--
-- Name: sgd_parexp_paramexpediente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_parexp_paramexpediente_id_seq', 10, true);


--
-- Name: sgd_plan_plantillas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_plan_plantillas_id_seq', 1, true);


--
-- Name: sgd_rad_metadatos_rad_meta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_rad_metadatos_rad_meta_id_seq', 1, false);


--
-- Name: sgd_rdf_retdocf_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_rdf_retdocf_id_seq', 2, true);


--
-- Name: sgd_renv_regenvio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_renv_regenvio_id_seq', 1, true);


--
-- Name: sgd_sbrd_subserierd_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_sbrd_subserierd_id_seq', 2, true);


--
-- Name: sgd_sexp_secexpedientes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_sexp_secexpedientes_id_seq', 1, true);


--
-- Name: sgd_srd_seriesrd_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_srd_seriesrd_id_seq', 2, true);


--
-- Name: sgd_tpr_tpdcumento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sgd_tpr_tpdcumento_id_seq', 3, true);


--
-- Name: tbl_table_survey_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tbl_table_survey_seq', 1, false);


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuario_id_seq', 1, true);


--
-- Name: sgd_mrd_matrird PK_SGDMRDCODIGO; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mrd_matrird
    ADD CONSTRAINT "PK_SGDMRDCODIGO" PRIMARY KEY (sgd_mrd_codigo);


--
-- Name: anexos_historico anex_hist_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.anexos_historico
    ADD CONSTRAINT anex_hist_pk PRIMARY KEY (anex_hist_anex_codi, anex_hist_num_ver);


--
-- Name: anexos anex_pk_anex_codigo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.anexos
    ADD CONSTRAINT anex_pk_anex_codigo PRIMARY KEY (anex_codigo);


--
-- Name: anexos_tipo anex_pk_anex_tipo_codi; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.anexos_tipo
    ADD CONSTRAINT anex_pk_anex_tipo_codi PRIMARY KEY (anex_tipo_codi);


--
-- Name: carpeta carpetas_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.carpeta
    ADD CONSTRAINT carpetas_pk PRIMARY KEY (carp_codi);


--
-- Name: centro_poblado centro_poblado_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.centro_poblado
    ADD CONSTRAINT centro_poblado_pk PRIMARY KEY (cpob_codi, muni_codi, dpto_codi);


--
-- Name: departamento departamento_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departamento
    ADD CONSTRAINT departamento_pk PRIMARY KEY (dpto_codi);


--
-- Name: dependencia_visibilidad dependencia_visibilidad_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.dependencia_visibilidad
    ADD CONSTRAINT dependencia_visibilidad_pk PRIMARY KEY (codigo_visibilidad);


--
-- Name: estado estados_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.estado
    ADD CONSTRAINT estados_pk PRIMARY KEY (esta_codi);


--
-- Name: frmf_frmfields pkFrmCodeName; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frmf_frmfields
    ADD CONSTRAINT "pkFrmCodeName" PRIMARY KEY (frm_code, frmf_name);


--
-- Name: sgd_parexp_paramexpediente pk_Parexp_Id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_parexp_paramexpediente
    ADD CONSTRAINT "pk_Parexp_Id" PRIMARY KEY (id);


--
-- Name: sgd_anu_anulados pk_anu_naludos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_anu_anulados
    ADD CONSTRAINT pk_anu_naludos PRIMARY KEY (sgd_anu_id);


--
-- Name: bodega_empresas pk_bodega_empresas_secue; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bodega_empresas
    ADD CONSTRAINT pk_bodega_empresas_secue PRIMARY KEY (identificador_empresa);


--
-- Name: autg_grupos pk_constrain_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.autg_grupos
    ADD CONSTRAINT pk_constrain_id PRIMARY KEY (id);


--
-- Name: dependencia pk_depe; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.dependencia
    ADD CONSTRAINT pk_depe PRIMARY KEY (depe_codi);


--
-- Name: sgd_eanu_estanulacion pk_estanulacion; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_eanu_estanulacion
    ADD CONSTRAINT pk_estanulacion PRIMARY KEY (sgd_eanu_codi);


--
-- Name: medio_recepcion pk_medio_recepcion; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medio_recepcion
    ADD CONSTRAINT pk_medio_recepcion PRIMARY KEY (mrec_codi);


--
-- Name: municipio pk_municipio; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipio
    ADD CONSTRAINT pk_municipio PRIMARY KEY (muni_codi, dpto_codi);


--
-- Name: par_serv_servicios pk_par_serv_servicios; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.par_serv_servicios
    ADD CONSTRAINT pk_par_serv_servicios PRIMARY KEY (par_serv_secue);


--
-- Name: sgd_panu_peranulados pk_peranualdos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_panu_peranulados
    ADD CONSTRAINT pk_peranualdos PRIMARY KEY (sgd_panu_codi);


--
-- Name: prestamo pk_prestamo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.prestamo
    ADD CONSTRAINT pk_prestamo PRIMARY KEY (pres_id);


--
-- Name: sgd_sbrd_subserierd pk_sbrdId; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sbrd_subserierd
    ADD CONSTRAINT "pk_sbrdId" PRIMARY KEY (id);


--
-- Name: sgd_sexp_secexpedientes pk_sgdExpNumero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sexp_secexpedientes
    ADD CONSTRAINT "pk_sgdExpNumero" UNIQUE (sgd_exp_numero);


--
-- Name: CONSTRAINT "pk_sgdExpNumero" ON sgd_sexp_secexpedientes; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON CONSTRAINT "pk_sgdExpNumero" ON public.sgd_sexp_secexpedientes IS 'Llave para los expedientes unicos.';


--
-- Name: sgd_acm_acusemsg pk_sgd_acm_acusemsg; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_acm_acusemsg
    ADD CONSTRAINT pk_sgd_acm_acusemsg PRIMARY KEY (sgd_msg_codi, usua_doc);


--
-- Name: sgd_anar_anexarg pk_sgd_anar_anexarg; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_anar_anexarg
    ADD CONSTRAINT pk_sgd_anar_anexarg PRIMARY KEY (sgd_anar_codi);


--
-- Name: sgd_aper_adminperfiles pk_sgd_aper_adminperfiles; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_aper_adminperfiles
    ADD CONSTRAINT pk_sgd_aper_adminperfiles PRIMARY KEY (sgd_aper_codigo);


--
-- Name: sgd_aplfad_plicfunadi pk_sgd_aplfad_plicfunadi; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_aplfad_plicfunadi
    ADD CONSTRAINT pk_sgd_aplfad_plicfunadi PRIMARY KEY (sgd_aplfad_codi);


--
-- Name: sgd_apli_aplintegra pk_sgd_apli_aplintegra; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_apli_aplintegra
    ADD CONSTRAINT pk_sgd_apli_aplintegra PRIMARY KEY (sgd_apli_codi);


--
-- Name: sgd_aplmen_aplimens pk_sgd_aplmen_aplimens; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_aplmen_aplimens
    ADD CONSTRAINT pk_sgd_aplmen_aplimens PRIMARY KEY (sgd_aplmen_codi);


--
-- Name: sgd_aplus_plicusua pk_sgd_aplus_plicusua; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_aplus_plicusua
    ADD CONSTRAINT pk_sgd_aplus_plicusua PRIMARY KEY (sgd_aplus_codi);


--
-- Name: sgd_argd_argdoc pk_sgd_argd_argdoc; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_argd_argdoc
    ADD CONSTRAINT pk_sgd_argd_argdoc PRIMARY KEY (sgd_argd_codi);


--
-- Name: sgd_argup_argudoctop pk_sgd_argup_argudoctop; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_argup_argudoctop
    ADD CONSTRAINT pk_sgd_argup_argudoctop PRIMARY KEY (sgd_argup_codi);


--
-- Name: sgd_camexp_campoexpediente pk_sgd_camexp_campoexpediente; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_camexp_campoexpediente
    ADD CONSTRAINT pk_sgd_camexp_campoexpediente PRIMARY KEY (sgd_camexp_codigo);


--
-- Name: sgd_cau_causal pk_sgd_cau_causal; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_cau_causal
    ADD CONSTRAINT pk_sgd_cau_causal PRIMARY KEY (sgd_cau_codigo);


--
-- Name: sgd_caux_causales pk_sgd_caux_causales; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_caux_causales
    ADD CONSTRAINT pk_sgd_caux_causales PRIMARY KEY (sgd_caux_codigo);


--
-- Name: sgd_ciu_ciudadano pk_sgd_ciu_ciudadano; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_ciu_ciudadano
    ADD CONSTRAINT pk_sgd_ciu_ciudadano PRIMARY KEY (sgd_ciu_codigo);


--
-- Name: sgd_cob_campobliga pk_sgd_cob_campobliga; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_cob_campobliga
    ADD CONSTRAINT pk_sgd_cob_campobliga PRIMARY KEY (sgd_cob_codi);


--
-- Name: sgd_dcau_causal pk_sgd_dcau_causal; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dcau_causal
    ADD CONSTRAINT pk_sgd_dcau_causal PRIMARY KEY (sgd_dcau_codigo);


--
-- Name: sgd_ddca_ddsgrgdo pk_sgd_ddca_ddsgrgdo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_ddca_ddsgrgdo
    ADD CONSTRAINT pk_sgd_ddca_ddsgrgdo PRIMARY KEY (sgd_ddca_codigo);


--
-- Name: sgd_def_continentes pk_sgd_def_continentes; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_def_continentes
    ADD CONSTRAINT pk_sgd_def_continentes PRIMARY KEY (id_cont);


--
-- Name: sgd_def_paises pk_sgd_def_paises; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_def_paises
    ADD CONSTRAINT pk_sgd_def_paises PRIMARY KEY (id_pais);


--
-- Name: sgd_deve_dev_envio pk_sgd_deve; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_deve_dev_envio
    ADD CONSTRAINT pk_sgd_deve PRIMARY KEY (sgd_deve_codigo);


--
-- Name: sgd_dir_drecciones pk_sgd_dir; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dir_drecciones
    ADD CONSTRAINT pk_sgd_dir PRIMARY KEY (sgd_dir_codigo);


--
-- Name: sgd_dnufe_docnufe pk_sgd_dnufe_docnufe; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dnufe_docnufe
    ADD CONSTRAINT pk_sgd_dnufe_docnufe PRIMARY KEY (sgd_dnufe_codi);


--
-- Name: sgd_empus_empusuario pk_sgd_empus_usuario; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_empus_empusuario
    ADD CONSTRAINT pk_sgd_empus_usuario PRIMARY KEY (sgd_empus_codigo);


--
-- Name: sgd_ent_entidades pk_sgd_ent; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_ent_entidades
    ADD CONSTRAINT pk_sgd_ent PRIMARY KEY (sgd_ent_nit, sgd_ent_codsuc);


--
-- Name: sgd_estinst_estadoinstancia pk_sgd_estinst_estadoinstancia; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_estinst_estadoinstancia
    ADD CONSTRAINT pk_sgd_estinst_estadoinstancia PRIMARY KEY (sgd_estinst_codi);


--
-- Name: sgd_fenv_frmenvio pk_sgd_fenv; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_fenv_frmenvio
    ADD CONSTRAINT pk_sgd_fenv PRIMARY KEY (sgd_fenv_codigo);


--
-- Name: sgd_fexp_flujoexpedientes pk_sgd_fexp_descrip; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_fexp_flujoexpedientes
    ADD CONSTRAINT pk_sgd_fexp_descrip PRIMARY KEY (sgd_fexp_codigo);


--
-- Name: sgd_firrad_firmarads pk_sgd_firrad_firmarads; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_firrad_firmarads
    ADD CONSTRAINT pk_sgd_firrad_firmarads PRIMARY KEY (sgd_firrad_id);


--
-- Name: sgd_fun_funciones pk_sgd_fun_funciones; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_fun_funciones
    ADD CONSTRAINT pk_sgd_fun_funciones PRIMARY KEY (sgd_fun_codigo);


--
-- Name: sgd_hmtd_hismatdoc pk_sgd_hmtd_hismatdoc; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_hmtd_hismatdoc
    ADD CONSTRAINT pk_sgd_hmtd_hismatdoc PRIMARY KEY (sgd_hmtd_codigo);


--
-- Name: sgd_instorf_instanciasorfeo pk_sgd_instorf_instanciasorfeo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_instorf_instanciasorfeo
    ADD CONSTRAINT pk_sgd_instorf_instanciasorfeo PRIMARY KEY (sgd_instorf_codi);


--
-- Name: sgd_mat_matriz pk_sgd_mat_matriz; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mat_matriz
    ADD CONSTRAINT pk_sgd_mat_matriz PRIMARY KEY (sgd_mat_codigo);


--
-- Name: sgd_mpes_mddpeso pk_sgd_mpes; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mpes_mddpeso
    ADD CONSTRAINT pk_sgd_mpes PRIMARY KEY (sgd_mpes_codigo);


--
-- Name: sgd_msdep_msgdep pk_sgd_msdep_msgdep; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_msdep_msgdep
    ADD CONSTRAINT pk_sgd_msdep_msgdep PRIMARY KEY (sgd_msdep_codi);


--
-- Name: sgd_msg_mensaje pk_sgd_msg_mensaje; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_msg_mensaje
    ADD CONSTRAINT pk_sgd_msg_mensaje PRIMARY KEY (sgd_msg_codi);


--
-- Name: sgd_mtd_matriz_doc pk_sgd_mtd_matriz_doc; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mtd_matriz_doc
    ADD CONSTRAINT pk_sgd_mtd_matriz_doc PRIMARY KEY (sgd_mtd_codigo);


--
-- Name: sgd_not_notificacion pk_sgd_not; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_not_notificacion
    ADD CONSTRAINT pk_sgd_not PRIMARY KEY (sgd_not_codi);


--
-- Name: sgd_oem_oempresas pk_sgd_oem_oempresas; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_oem_oempresas
    ADD CONSTRAINT pk_sgd_oem_oempresas PRIMARY KEY (sgd_oem_codigo);


--
-- Name: sgd_pexp_procexpedientes pk_sgd_pexp_procexpedientes; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_pexp_procexpedientes
    ADD CONSTRAINT pk_sgd_pexp_procexpedientes PRIMARY KEY (sgd_pexp_codigo);


--
-- Name: sgd_pnufe_procnumfe pk_sgd_pnufe_procnumfe; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_pnufe_procnumfe
    ADD CONSTRAINT pk_sgd_pnufe_procnumfe PRIMARY KEY (sgd_pnufe_codi);


--
-- Name: sgd_pnun_procenum pk_sgd_pnun_procenum; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_pnun_procenum
    ADD CONSTRAINT pk_sgd_pnun_procenum PRIMARY KEY (sgd_pnun_codi);


--
-- Name: sgd_prc_proceso pk_sgd_prc_proceso; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_prc_proceso
    ADD CONSTRAINT pk_sgd_prc_proceso PRIMARY KEY (sgd_prc_codigo);


--
-- Name: sgd_prd_prcdmentos pk_sgd_prd_prcdmentos; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_prd_prcdmentos
    ADD CONSTRAINT pk_sgd_prd_prcdmentos PRIMARY KEY (sgd_prd_codigo);


--
-- Name: sgd_rmr_radmasivre pk_sgd_rmr_radmasivre; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rmr_radmasivre
    ADD CONSTRAINT pk_sgd_rmr_radmasivre PRIMARY KEY (sgd_rmr_grupo, sgd_rmr_radi);


--
-- Name: sgd_sed_sede pk_sgd_sed_sede; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sed_sede
    ADD CONSTRAINT pk_sgd_sed_sede PRIMARY KEY (sgd_sed_codi);


--
-- Name: sgd_senuf_secnumfe pk_sgd_senuf_secnumfe; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_senuf_secnumfe
    ADD CONSTRAINT pk_sgd_senuf_secnumfe PRIMARY KEY (sgd_senuf_codi);


--
-- Name: sgd_tdec_tipodecision pk_sgd_tdec_tipodecision; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tdec_tipodecision
    ADD CONSTRAINT pk_sgd_tdec_tipodecision PRIMARY KEY (sgd_tdec_codigo);


--
-- Name: sgd_tid_tipdecision pk_sgd_tid_tipdecision; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tid_tipdecision
    ADD CONSTRAINT pk_sgd_tid_tipdecision PRIMARY KEY (sgd_tid_codi);


--
-- Name: sgd_tip3_tipotercero pk_sgd_tip_tipotercero; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tip3_tipotercero
    ADD CONSTRAINT pk_sgd_tip_tipotercero PRIMARY KEY (sgd_tip3_codigo);


--
-- Name: sgd_tma_temas pk_sgd_tma_temas; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tma_temas
    ADD CONSTRAINT pk_sgd_tma_temas PRIMARY KEY (sgd_tma_codigo);


--
-- Name: sgd_tme_tipmen pk_sgd_tme_tipmen; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tme_tipmen
    ADD CONSTRAINT pk_sgd_tme_tipmen PRIMARY KEY (sgd_tme_codi);


--
-- Name: sgd_tpr_tpdcumento pk_sgd_tpr_tpdcumento; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tpr_tpdcumento
    ADD CONSTRAINT pk_sgd_tpr_tpdcumento PRIMARY KEY (sgd_tpr_codigo);


--
-- Name: sgd_ttr_transaccion pk_sgd_ttr_transaccion; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_ttr_transaccion
    ADD CONSTRAINT pk_sgd_ttr_transaccion PRIMARY KEY (sgd_ttr_codigo);


--
-- Name: sgd_tidm_tidocmasiva pk_tdm_tidomasiva; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tidm_tidocmasiva
    ADD CONSTRAINT pk_tdm_tidomasiva PRIMARY KEY (sgd_tidm_codi);


--
-- Name: radicado radicado_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.radicado
    ADD CONSTRAINT radicado_pk PRIMARY KEY (radi_nume_radi);


--
-- Name: sgd_acl sgd_acl_hierarchy_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_acl
    ADD CONSTRAINT sgd_acl_hierarchy_key UNIQUE (hierarchy);


--
-- Name: sgd_carp_descripcion sgd_carp_descripcion_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_carp_descripcion
    ADD CONSTRAINT sgd_carp_descripcion_pk PRIMARY KEY (sgd_carp_depecodi, sgd_carp_tiporad);


--
-- Name: sgd_csop_coment sgd_csop_coment_pk1; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_csop_coment
    ADD CONSTRAINT sgd_csop_coment_pk1 UNIQUE (sgd_csop_id);


--
-- Name: sgd_einv_inventario sgd_einv_inventario_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_einv_inventario
    ADD CONSTRAINT sgd_einv_inventario_pk PRIMARY KEY (sgd_einv_codigo);


--
-- Name: sgd_eit_items sgd_eit_items_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_eit_items
    ADD CONSTRAINT sgd_eit_items_pk PRIMARY KEY (sgd_eit_codigo);


--
-- Name: sgd_exp_expediente sgd_exp_expediente_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_exp_expediente
    ADD CONSTRAINT sgd_exp_expediente_pk PRIMARY KEY (sgd_exp_numero, radi_nume_radi);


--
-- Name: sgd_fars_faristas sgd_fars_faristas_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_fars_faristas
    ADD CONSTRAINT sgd_fars_faristas_pk PRIMARY KEY (sgd_fars_codigo);


--
-- Name: sgd_hfld_histflujodoc sgd_hfld_histflujodoc_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_hfld_histflujodoc
    ADD CONSTRAINT sgd_hfld_histflujodoc_pkey PRIMARY KEY (id);


--
-- Name: sgd_masiva_excel sgd_masiva_codigo; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_masiva_excel
    ADD CONSTRAINT sgd_masiva_codigo PRIMARY KEY (sgd_masiva_codigo);


--
-- Name: sgd_nfn_notifijacion sgd_nfn_notifijacion_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_nfn_notifijacion
    ADD CONSTRAINT sgd_nfn_notifijacion_pk PRIMARY KEY (radi_nume_radi);


--
-- Name: sgd_novedad_usuario sgd_novedad_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_novedad_usuario
    ADD CONSTRAINT sgd_novedad_usuario_pkey PRIMARY KEY (usua_doc);


--
-- Name: sgd_param_admin sgd_param_admin_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_param_admin
    ADD CONSTRAINT sgd_param_admin_pkey PRIMARY KEY (param_codigo);


--
-- Name: sgd_parametro sgd_parametro_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_parametro
    ADD CONSTRAINT sgd_parametro_pk PRIMARY KEY (param_nomb, param_codi);


--
-- Name: sgd_rad_metadatos sgd_rad_metadatos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rad_metadatos
    ADD CONSTRAINT sgd_rad_metadatos_pkey PRIMARY KEY (rad_meta_id);


--
-- Name: sgd_sexp_secexpedientes sgd_sexp_secexpedientes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sexp_secexpedientes
    ADD CONSTRAINT sgd_sexp_secexpedientes_pkey PRIMARY KEY (id);


--
-- Name: sgd_sop_soporte sgd_sop_soporte_sgd_sop_id_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sop_soporte
    ADD CONSTRAINT sgd_sop_soporte_sgd_sop_id_key UNIQUE (sgd_sop_id);


--
-- Name: sgd_srd_seriesrd sgd_srd_seriesrd_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_srd_seriesrd
    ADD CONSTRAINT sgd_srd_seriesrd_pk PRIMARY KEY (id);


--
-- Name: sgd_tdf_tipodefallos sgd_tdf_tipodefallos_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tdf_tipodefallos
    ADD CONSTRAINT sgd_tdf_tipodefallos_pk PRIMARY KEY (sgd_tdf_codigo);


--
-- Name: sgd_trad_tiporad sgd_trad_tiporad_codigo_inx; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_trad_tiporad
    ADD CONSTRAINT sgd_trad_tiporad_codigo_inx PRIMARY KEY (sgd_trad_codigo);


--
-- Name: sgd_tsop_tiposoporte sgd_tsop_tiposoporte_pk1; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tsop_tiposoporte
    ADD CONSTRAINT sgd_tsop_tiposoporte_pk1 UNIQUE (sgd_tsop_id);


--
-- Name: tipo_doc_identificacion tipo_doc_identificacion_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_doc_identificacion
    ADD CONSTRAINT tipo_doc_identificacion_pk PRIMARY KEY (tdid_codi);


--
-- Name: tipo_remitente tipo_remitente_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipo_remitente
    ADD CONSTRAINT tipo_remitente_pk PRIMARY KEY (trte_codi);


--
-- Name: frmf_frmfields uk_idfrmfields; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frmf_frmfields
    ADD CONSTRAINT uk_idfrmfields UNIQUE (id);


--
-- Name: sgd_mat_matriz uk_sgd_mat; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mat_matriz
    ADD CONSTRAINT uk_sgd_mat UNIQUE (depe_codi, sgd_fun_codigo, sgd_prc_codigo, sgd_prd_codigo);


--
-- Name: sgd_mrd_matrird uk_sgd_mrd_matrird; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mrd_matrird
    ADD CONSTRAINT uk_sgd_mrd_matrird UNIQUE (depe_codi, sgd_srd_codigo, sgd_sbrd_codigo, sgd_tpr_codigo);


--
-- Name: sgd_rdf_retdocf uk_sgd_rdf_retdocf; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rdf_retdocf
    ADD CONSTRAINT uk_sgd_rdf_retdocf UNIQUE (radi_nume_radi, depe_codi, sgd_mrd_codigo);


--
-- Name: sgd_srd_seriesrd uk_sgd_srd_descrip; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_srd_seriesrd
    ADD CONSTRAINT uk_sgd_srd_descrip UNIQUE (sgd_srd_descrip);


--
-- Name: usuario unique_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT unique_id UNIQUE (id);


--
-- Name: usuario unique_usua_login; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT unique_usua_login UNIQUE (usua_login);


--
-- Name: usuario usuario_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pk PRIMARY KEY (id);


--
-- Name: usuario usuario_uk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_uk UNIQUE (usua_codi, depe_codi);


--
-- Name: idxAnexos; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idxAnexos" ON public.anexos USING btree (anex_radi_nume, anex_codigo, anex_fech_anex);


--
-- Name: idxDirDrecciones; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idxDirDrecciones" ON public.sgd_dir_drecciones USING btree (sgd_dir_codigo, sgd_trd_codigo, radi_nume_radi);


--
-- Name: idxRadicado; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idxRadicado" ON public.radicado USING btree (radi_nume_radi, radi_fech_radi, tdoc_codi);


--
-- Name: idxSgdExpediente; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idxSgdExpediente" ON public.sgd_exp_expediente USING btree (sgd_exp_numero, radi_nume_radi, sgd_exp_estado);


--
-- Name: idx_RadiUsuaDoc; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_RadiUsuaDoc" ON public.hist_eventos USING btree (radi_nume_radi, usua_doc);


--
-- Name: idx_UsuarioUsuaDoc; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_UsuarioUsuaDoc" ON public.usuario USING btree (usua_doc, usua_esta, depe_codi, usua_codi);


--
-- Name: idx_ciuCodigo; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_ciuCodigo" ON public.sgd_ciu_ciudadano USING btree (sgd_ciu_codigo, sgd_ciu_cedula, sgd_ciu_nombre, sgd_ciu_apell1, sgd_ciu_apell2);


--
-- Name: idx_deptoCodi; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_deptoCodi" ON public.departamento USING btree (dpto_codi, id_pais, id_cont);


--
-- Name: idx_expNumeroDepeUsuaMuni; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_expNumeroDepeUsuaMuni" ON public.sgd_exp_expediente USING btree (sgd_exp_numero, radi_nume_radi, depe_codi, usua_codi, usua_doc, sgd_exp_estado);


--
-- Name: idx_histEventos; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_histEventos" ON public.hist_eventos USING btree (radi_nume_radi, hist_fech, depe_codi, usua_codi, sgd_ttr_codigo);


--
-- Name: idx_matCodigo; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_matCodigo" ON public.sgd_mat_matriz USING btree (sgd_mat_codigo, sgd_prd_codigo);


--
-- Name: idx_mrd; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_mrd ON public.sgd_mrd_matrird USING btree (sgd_mrd_codigo_old, depe_codi, depe_codi_aplica, sgd_srd_codigo, sgd_sbrd_codigo, sgd_mrd_esta);


--
-- Name: idx_municipio; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_municipio ON public.municipio USING btree (dpto_codi, muni_codi, id_cont, id_pais);


--
-- Name: idx_novedad_usuario; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_novedad_usuario ON public.sgd_novedad_usuario USING btree (usua_doc);


--
-- Name: idx_param_admin; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_param_admin ON public.sgd_param_admin USING btree (param_codigo);


--
-- Name: idx_radiTipoName; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_radiTipoName" ON public.sgd_dir_drecciones USING btree (radi_nume_radi, sgd_dir_tipo, muni_codi, dpto_codi);


--
-- Name: idx_radicadoCarpetas; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_radicadoCarpetas" ON public.radicado USING btree (radi_nume_radi, carp_codi, carp_per);


--
-- Name: idx_radisalAnexEstado; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_radisalAnexEstado" ON public.anexos USING btree (radi_nume_salida, anex_estado);


--
-- Name: idx_sbrd; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_sbrd ON public.sgd_sbrd_subserierd USING btree (sgd_srd_codigo, sgd_sbrd_codigo, sgd_sbrd_fechini, sgd_sbrd_fechfin, sgd_sbrd_procedi);


--
-- Name: idx_sexp; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_sexp ON public.sgd_sexp_secexpedientes USING btree (sgd_exp_numero, sgd_srd_codigo, sgd_sbrd_codigo, sgd_pexp_codigo, sgd_sexp_fech);


--
-- Name: idx_sgdRenv; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_sgdRenv" ON public.sgd_renv_regenvio USING btree (radi_nume_sal);


--
-- Name: idx_srdCodi; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_srdCodi" ON public.sgd_srd_seriesrd USING btree (sgd_srd_codigo, sgd_srd_fechini, sgd_srd_fechfin);


--
-- Name: idx_tpr; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_tpr ON public.sgd_tpr_tpdcumento USING btree (sgd_tpr_codigo, sgd_tpr_estado);


--
-- Name: idx_tprCodigoName; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_tprCodigoName" ON public.sgd_tpr_tpdcumento USING btree (sgd_tpr_codigo, sgd_tpr_estado);


--
-- Name: idx_ttr; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_ttr ON public.sgd_ttr_transaccion USING btree (sgd_ttr_codigo);


--
-- Name: idx_usuaCodi; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx_usuaCodi" ON public.usuario USING btree (usua_codi, depe_codi, usua_esta);


--
-- Name: ndx_numero_fexp; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX ndx_numero_fexp ON public.sgd_sexp_secexpedientes USING btree (sgd_fexp_codigo, sgd_exp_numero);


--
-- Name: radicado_radi_nume_radi_ra_asun_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX radicado_radi_nume_radi_ra_asun_idx ON public.radicado USING btree (radi_nume_radi, ra_asun);


--
-- Name: sgd_csop_coment FK_SOP_SOPORTE; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_csop_coment
    ADD CONSTRAINT "FK_SOP_SOPORTE" FOREIGN KEY (sgd_sop_id) REFERENCES public.sgd_sop_soporte(sgd_sop_id);


--
-- Name: anexos anexos_radicado_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.anexos
    ADD CONSTRAINT anexos_radicado_fk FOREIGN KEY (anex_radi_nume) REFERENCES public.radicado(radi_nume_radi) ON UPDATE CASCADE;


--
-- Name: anexos_historico fk_anex_hist_anex_codi; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.anexos_historico
    ADD CONSTRAINT fk_anex_hist_anex_codi FOREIGN KEY (anex_hist_anex_codi) REFERENCES public.anexos(anex_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: dependencia fk_depe_padre; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.dependencia
    ADD CONSTRAINT fk_depe_padre FOREIGN KEY (depe_codi_padre) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: hist_eventos fk_hist_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hist_eventos
    ADD CONSTRAINT fk_hist_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: informados fk_info_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.informados
    ADD CONSTRAINT fk_info_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: municipio fk_municipi_ref_128_departam; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipio
    ADD CONSTRAINT fk_municipi_ref_128_departam FOREIGN KEY (dpto_codi) REFERENCES public.departamento(dpto_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_def_paises fk_paises_continentes; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_def_paises
    ADD CONSTRAINT fk_paises_continentes FOREIGN KEY (id_cont) REFERENCES public.sgd_def_continentes(id_cont) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: prestamo fk_prestamo_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.prestamo
    ADD CONSTRAINT fk_prestamo_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: prestamo fk_prestamo_depe_arch; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.prestamo
    ADD CONSTRAINT fk_prestamo_depe_arch FOREIGN KEY (pres_depe_arch) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: radicado fk_radi_cpob; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.radicado
    ADD CONSTRAINT fk_radi_cpob FOREIGN KEY (cpob_codi, cen_muni_codi, cen_dpto_codi) REFERENCES public.centro_poblado(cpob_codi, muni_codi, dpto_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: radicado fk_radi_esta; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.radicado
    ADD CONSTRAINT fk_radi_esta FOREIGN KEY (esta_codi) REFERENCES public.estado(esta_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: radicado fk_radi_mrec; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.radicado
    ADD CONSTRAINT fk_radi_mrec FOREIGN KEY (mrec_codi) REFERENCES public.medio_recepcion(mrec_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: radicado fk_radi_muni; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.radicado
    ADD CONSTRAINT fk_radi_muni FOREIGN KEY (muni_codi, dpto_codi) REFERENCES public.municipio(muni_codi, dpto_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_anu_anulados fk_radicado_nume; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_anu_anulados
    ADD CONSTRAINT fk_radicado_nume FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: radicado fk_radicado_par_serv; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.radicado
    ADD CONSTRAINT fk_radicado_par_serv FOREIGN KEY (par_serv_secue) REFERENCES public.par_serv_servicios(par_serv_secue) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_sexp_secexpedientes fk_sexp_secexp_pexp_codigo; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sexp_secexpedientes
    ADD CONSTRAINT fk_sexp_secexp_pexp_codigo FOREIGN KEY (sgd_pexp_codigo) REFERENCES public.sgd_pexp_procexpedientes(sgd_pexp_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_anar_anexarg fk_sgd_anar_anexos; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_anar_anexarg
    ADD CONSTRAINT fk_sgd_anar_anexos FOREIGN KEY (anex_codigo) REFERENCES public.anexos(anex_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: usuario fk_sgd_aper_adminp; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT fk_sgd_aper_adminp FOREIGN KEY (sgd_aper_codigo) REFERENCES public.sgd_aper_adminperfiles(sgd_aper_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_aplmen_aplimens fk_sgd_aplmen_sgd_apli; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_aplmen_aplimens
    ADD CONSTRAINT fk_sgd_aplmen_sgd_apli FOREIGN KEY (sgd_apli_codi) REFERENCES public.sgd_apli_aplintegra(sgd_apli_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_caux_causales fk_sgd_caux_radicado; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_caux_causales
    ADD CONSTRAINT fk_sgd_caux_radicado FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_dcau_causal fk_sgd_dcau_sgd_cau_; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dcau_causal
    ADD CONSTRAINT fk_sgd_dcau_sgd_cau_ FOREIGN KEY (sgd_cau_codigo) REFERENCES public.sgd_cau_causal(sgd_cau_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_ddca_ddsgrgdo fk_sgd_ddca_ref_678_par_serv; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_ddca_ddsgrgdo
    ADD CONSTRAINT fk_sgd_ddca_ref_678_par_serv FOREIGN KEY (par_serv_secue) REFERENCES public.par_serv_servicios(par_serv_secue) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_ddca_ddsgrgdo fk_sgd_ddca_sgd_dcau; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_ddca_ddsgrgdo
    ADD CONSTRAINT fk_sgd_ddca_sgd_dcau FOREIGN KEY (sgd_dcau_codigo) REFERENCES public.sgd_dcau_causal(sgd_dcau_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_dir_drecciones fk_sgd_dir_municipio; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dir_drecciones
    ADD CONSTRAINT fk_sgd_dir_municipio FOREIGN KEY (muni_codi, dpto_codi) REFERENCES public.municipio(muni_codi, dpto_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_dir_drecciones fk_sgd_dir_sgd_ciu; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dir_drecciones
    ADD CONSTRAINT fk_sgd_dir_sgd_ciu FOREIGN KEY (sgd_ciu_codigo) REFERENCES public.sgd_ciu_ciudadano(sgd_ciu_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_dnufe_docnufe fk_sgd_dnufe_anex_tipo; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_dnufe_docnufe
    ADD CONSTRAINT fk_sgd_dnufe_anex_tipo FOREIGN KEY (anex_tipo_codi) REFERENCES public.anexos_tipo(anex_tipo_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_estinst_estadoinstancia fk_sgd_estinst_sgd_apli; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_estinst_estadoinstancia
    ADD CONSTRAINT fk_sgd_estinst_sgd_apli FOREIGN KEY (sgd_apli_codi) REFERENCES public.sgd_apli_aplintegra(sgd_apli_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_exp_expediente fk_sgd_exp_dependencia; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_exp_expediente
    ADD CONSTRAINT fk_sgd_exp_dependencia FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_exp_expediente fk_sgd_exp_radicado; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_exp_expediente
    ADD CONSTRAINT fk_sgd_exp_radicado FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_firrad_firmarads fk_sgd_firr_ref_82_radicado; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_firrad_firmarads
    ADD CONSTRAINT fk_sgd_firr_ref_82_radicado FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_hmtd_hismatdoc fk_sgd_hmtd_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_hmtd_hismatdoc
    ADD CONSTRAINT fk_sgd_hmtd_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_hmtd_hismatdoc fk_sgd_hmtd_radicado; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_hmtd_hismatdoc
    ADD CONSTRAINT fk_sgd_hmtd_radicado FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_lip_linkip fk_sgd_lip__ref_27_dependen; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_lip_linkip
    ADD CONSTRAINT fk_sgd_lip__ref_27_dependen FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_mat_matriz fk_sgd_mat_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mat_matriz
    ADD CONSTRAINT fk_sgd_mat_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_mat_matriz fk_sgd_mat_sgd_fun; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mat_matriz
    ADD CONSTRAINT fk_sgd_mat_sgd_fun FOREIGN KEY (sgd_fun_codigo) REFERENCES public.sgd_fun_funciones(sgd_fun_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_msdep_msgdep fk_sgd_msde_ref_27_dependen; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_msdep_msgdep
    ADD CONSTRAINT fk_sgd_msde_ref_27_dependen FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_mtd_matriz_doc fk_sgd_mtd_sgd_mtd; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mtd_matriz_doc
    ADD CONSTRAINT fk_sgd_mtd_sgd_mtd FOREIGN KEY (sgd_mat_codigo) REFERENCES public.sgd_mat_matriz(sgd_mat_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_ntrd_notifrad fk_sgd_ntrd_notifrad_radicado; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_ntrd_notifrad
    ADD CONSTRAINT fk_sgd_ntrd_notifrad_radicado FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_oem_oempresas fk_sgd_oem_municipio; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_oem_oempresas
    ADD CONSTRAINT fk_sgd_oem_municipio FOREIGN KEY (muni_codi, dpto_codi) REFERENCES public.municipio(muni_codi, dpto_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_pnun_procenum fk_sgd_pnun_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_pnun_procenum
    ADD CONSTRAINT fk_sgd_pnun_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_pnun_procenum fk_sgd_pnun_sgd_pnufe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_pnun_procenum
    ADD CONSTRAINT fk_sgd_pnun_sgd_pnufe FOREIGN KEY (sgd_pnufe_codi) REFERENCES public.sgd_pnufe_procnumfe(sgd_pnufe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_rdf_retdocf fk_sgd_rdf_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rdf_retdocf
    ADD CONSTRAINT fk_sgd_rdf_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_renv_regenvio fk_sgd_renv_dependecia; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_renv_regenvio
    ADD CONSTRAINT fk_sgd_renv_dependecia FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_renv_regenvio fk_sgd_renv_sgd_deve; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_renv_regenvio
    ADD CONSTRAINT fk_sgd_renv_sgd_deve FOREIGN KEY (sgd_deve_codigo) REFERENCES public.sgd_deve_dev_envio(sgd_deve_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_tdec_tipodecision fk_sgd_tdec_tipodecision_apli; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tdec_tipodecision
    ADD CONSTRAINT fk_sgd_tdec_tipodecision_apli FOREIGN KEY (sgd_apli_codi) REFERENCES public.sgd_apli_aplintegra(sgd_apli_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_tma_temas fk_sgd_tma_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tma_temas
    ADD CONSTRAINT fk_sgd_tma_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_tma_temas fk_sgd_tma_sgd_prc; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_tma_temas
    ADD CONSTRAINT fk_sgd_tma_sgd_prc FOREIGN KEY (sgd_prc_codigo) REFERENCES public.sgd_prc_proceso(sgd_prc_codigo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_sop_soporte fk_sop_soporte_fk2; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sop_soporte
    ADD CONSTRAINT fk_sop_soporte_fk2 FOREIGN KEY (sgd_tsop_id) REFERENCES public.sgd_tsop_tiposoporte(sgd_tsop_id);


--
-- Name: usuario fk_usua_depe; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT fk_usua_depe FOREIGN KEY (depe_codi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_agen_agendados sgd_agen_agendados_r01; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_agen_agendados
    ADD CONSTRAINT sgd_agen_agendados_r01 FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_aplus_plicusua sgd_aplus_sgd_apli; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_aplus_plicusua
    ADD CONSTRAINT sgd_aplus_sgd_apli FOREIGN KEY (sgd_apli_codi) REFERENCES public.sgd_apli_aplintegra(sgd_apli_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_carp_descripcion sgd_carp_descripcion_fk1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_carp_descripcion
    ADD CONSTRAINT sgd_carp_descripcion_fk1 FOREIGN KEY (sgd_carp_depecodi) REFERENCES public.dependencia(depe_codi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_exp_expediente sgd_exp_expediente_sgd_sexp_secexpedientes_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_exp_expediente
    ADD CONSTRAINT sgd_exp_expediente_sgd_sexp_secexpedientes_fk FOREIGN KEY (sgd_exp_numero) REFERENCES public.sgd_sexp_secexpedientes(sgd_exp_numero);


--
-- Name: sgd_fars_faristas sgd_fars_faristas_sgd_pexp_procexpedientes_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_fars_faristas
    ADD CONSTRAINT sgd_fars_faristas_sgd_pexp_procexpedientes_fk FOREIGN KEY (sgd_pexp_codigo) REFERENCES public.sgd_pexp_procexpedientes(sgd_pexp_codigo);


--
-- Name: sgd_mrd_matrird sgd_mrd_matrird_sgd_sbrd_subserierd_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mrd_matrird
    ADD CONSTRAINT sgd_mrd_matrird_sgd_sbrd_subserierd_fk FOREIGN KEY (sgd_sbrd_id) REFERENCES public.sgd_sbrd_subserierd(id);


--
-- Name: sgd_mrd_matrird sgd_mrd_matrird_sgd_srd_seriesrd_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mrd_matrird
    ADD CONSTRAINT sgd_mrd_matrird_sgd_srd_seriesrd_fk FOREIGN KEY (sgd_srd_id) REFERENCES public.sgd_srd_seriesrd(id);


--
-- Name: sgd_mrd_matrird sgd_mrd_matrird_sgd_tpr_tpdcumento_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_mrd_matrird
    ADD CONSTRAINT sgd_mrd_matrird_sgd_tpr_tpdcumento_fk FOREIGN KEY (sgd_tpr_codigo) REFERENCES public.sgd_tpr_tpdcumento(sgd_tpr_codigo);


--
-- Name: sgd_nfn_notifijacion sgd_nfn_notifijacion_radi_fk1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_nfn_notifijacion
    ADD CONSTRAINT sgd_nfn_notifijacion_radi_fk1 FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: sgd_rdf_retdocf sgd_rdf_retdocf_radicado_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rdf_retdocf
    ADD CONSTRAINT sgd_rdf_retdocf_radicado_fk FOREIGN KEY (radi_nume_radi) REFERENCES public.radicado(radi_nume_radi);


--
-- Name: sgd_rdf_retdocf sgd_rdf_retdocf_sgd_mrd_matrird_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_rdf_retdocf
    ADD CONSTRAINT sgd_rdf_retdocf_sgd_mrd_matrird_fk FOREIGN KEY (sgd_mrd_codigo) REFERENCES public.sgd_mrd_matrird(sgd_mrd_codigo);


--
-- Name: sgd_sbrd_subserierd sgd_sbrd_subserierd_sgd_srd_seriesrd_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sgd_sbrd_subserierd
    ADD CONSTRAINT sgd_sbrd_subserierd_sgd_srd_seriesrd_fk FOREIGN KEY (sgd_srd_id) REFERENCES public.sgd_srd_seriesrd(id);


--
-- Name: vista_rad1; Type: MATERIALIZED VIEW DATA; Schema: public; Owner: -
--

REFRESH MATERIALIZED VIEW public.vista_rad1;


--
-- PostgreSQL database dump complete
--

