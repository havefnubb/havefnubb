CREATE TABLE hf_calendar (
    id INT(10) NOT NULL AUTO_INCREMENT ,
    date INT(10) NOT NULL ,
    title VARCHAR(255) NOT NULL ,
    content TEXT NOT NULL ,
    user_id INT(10) NOT NULL ,
    user_name VARCHAR(200) NOT NULL ,
    id_post INT(10) ,
    parent_id INT(10) ,
    cat_id INT(10),
    INDEX (cat_id),
    PRIMARY KEY (id)
) DEFAULT CHARSET=utf8;


CREATE TABLE hf_calendar_cat (
    `cat_id` int( 10 ) NOT NULL AUTO_INCREMENT ,
    `cat_name` varchar( 255 )  NOT NULL default '',
    `cat_url` varchar( 255 ) NOT NULL default '',
    `cat_desc` text ,
    `cat_ord` int( 10 ) NOT NULL default '0',
    forum_id int(12) NOT NULL default '0',
    PRIMARY KEY ( `cat_id` ) ,
    UNIQUE KEY `cat_name` ( `cat_name` ) ,
    UNIQUE KEY `cat_url` ( `cat_url` )
) DEFAULT CHARSET=utf8;