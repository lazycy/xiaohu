create table users (
  id int(10) unsigned NOT NULL auto_increment,
  username varchar(12),
  password VARCHAR(255) not NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_username_unique(username)
) engine = innodb;