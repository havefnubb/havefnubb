CREATE TABLE IF NOT EXISTS %%PREFIX%%hfnu_subscript_forums (
    id_user int(12) NOT NULL,
    id_forum int(12) NOT NULL,
    PRIMARY KEY (id_user , id_forum)
) DEFAULT CHARSET=utf8;
