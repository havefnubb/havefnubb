update hf_posts set status = 1 where status = "pined";
update hf_posts set status = 2 where status = "pinedclosed";
update hf_posts set status = 3 where status = "opened";
update hf_posts set status = 4 where status = "closed";
update hf_posts set status = 5 where status = "censored";
update hf_posts set status = 6 where status = "uncensored";
update hf_posts set status = 7 where status = "hidden";

alter table hf_posts CHANGE status status INT( 2 ) NOT NULL DEFAULT '3';
