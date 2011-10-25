CREATE TABLE %%PREFIX%%iah_pages (
    iah_id_pages SERIAL NOT NULL,
    iah_template character varying(255) NOT NULL ,
    iah_label character varying(80) NOT NULL ,
    iah_page_name character varying(80) NOT NULL ,
    PRIMARY KEY (iah_id_pages)
);


CREATE TABLE %%PREFIX%%iah_pages_visitors (
    iah_id_pages integer NOT NULL,
    iah_login character varying(50) NOT NULL,
    CONSTRAINT id_pages_login PRIMARY KEY (iah_id_pages,iah_login)
);
