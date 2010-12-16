DROP TABLE IF EXISTS %%PREFIX%%hfcalendar;
CREATE TABLE %%PREFIX%%hfcalendar (
    id SERIAL,
    date INTEGER NOT NULL,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL,
    user_name TEXT NOT NULL,
    id_post INTEGER,
    parent_id INTEGER,
    cat_id INTEGER,
    CONSTRAINT %%PREFIX%%hfcalendar_id_pk PRIMARY KEY (id)
);

DROP TABLE IF EXISTS %%PREFIX%%hfcalendar_cat;
CREATE TABLE  %%PREFIX%%hfcalendar_cat (
    cat_id SERIAL,
    cat_name TEXT  NOT NULL default '',
    cat_url TEXT NOT NULL default '',
    cat_desc TEXT,
    cat_ord INTEGER NOT NULL default '0',
    forum_id INTEGER NOT NULL default '0',
    CONSTRAINT %%PREFIX%%hfcalendar_cat_cat_id_pk PRIMARY KEY (cat_id)
);
