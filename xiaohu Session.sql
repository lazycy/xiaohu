show tables;

drop table users;

desc migrations;

select * from migrations;

desc users;

insert into users (username, `password`) value ('韩梅梅', '123123');

select * from users;

select * from questions;

desc questions;

select * from answers;

select * from comments;

desc answer_user;

select * from answer_user;

update users set phone = '15889607823' where id =1;