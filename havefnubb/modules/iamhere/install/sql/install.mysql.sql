CREATE TABLE %%PREFIX%%iah_pages (
    iah_id_pages INT(10) NOT NULL AUTO_INCREMENT ,
    iah_template VARCHAR(255) NOT NULL ,
    iah_label VARCHAR(80) NOT NULL ,
    iah_page_name VARCHAR(80) NOT NULL ,
    PRIMARY KEY (iah_id_pages)
) DEFAULT CHARSET=utf8;


CREATE TABLE %%PREFIX%%iah_pages_visitors (
    iah_id_pages INT(10) NOT NULL,
    iah_login varchar(50) NOT NULL,
    PRIMARY KEY  (iah_id_pages,iah_login)
) DEFAULT CHARSET=utf8;
