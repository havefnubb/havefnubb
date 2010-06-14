CREATE TABLE %%PREFIX%%rates (
    id_user   INT(10) NOT NULL ,
    id_source INT(10) NOT NULL ,
    source    VARCHAR(40) NOT NULL,
    ip        VARCHAR(80) NOT NULL,
    level FLOAT NOT NULL
);
CREATE UNIQUE INDEX rates_id  ON %%PREFIX%%rates  (  id_user , id_source , source ) ;
CREATE INDEX %%PREFIX%%rates_id_user ON %%PREFIX%%rates ( id_user );
CREATE INDEX %%PREFIX%%rates_id_source ON %%PREFIX%%rates( id_source );
CREATE INDEX %%PREFIX%%rates_source ON %%PREFIX%%rates( source );
