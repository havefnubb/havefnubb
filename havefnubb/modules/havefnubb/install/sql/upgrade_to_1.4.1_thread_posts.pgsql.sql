ALTER TABLE %%PREFIX%%hfnu_forum 
 ADD nb_msg DEFAULT 0::bigint NOT NULL,
 ADD nb_thread DEFAULT 0::bigint NOT NULL;
