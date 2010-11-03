CREATE TABLE %%PREFIX%%hfnu_rates (
    id_user   INT(10) NOT NULL ,
    id_source INT(10) NOT NULL ,
    source    VARCHAR(40) NOT NULL,
    ip        VARCHAR(80) NOT NULL,
    level FLOAT NOT NULL
);
CREATE UNIQUE INDEX %%PREFIX%%hfnu_rates_id  ON %%PREFIX%%hfnu_rates  (  id_user , id_source , source ) ;
CREATE INDEX %%PREFIX%%hfnu_rates_id_user ON %%PREFIX%%hfnu_rates ( id_user );
CREATE INDEX %%PREFIX%%hfnu_rates_id_source ON %%PREFIX%%hfnu_rates( id_source );
CREATE INDEX %%PREFIX%%hfnu_rates_source ON %%PREFIX%%hfnu_rates( source );
