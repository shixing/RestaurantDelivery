create table employee 
(
uid int AUTO_INCREMENT,
username text NOT NULL,
password text NOT NULL,
fn text,
ln text,
age int,
type text NOT NULL,
payment int NOT NULL,
PRIMARY KEY (uid)
);

create table category
(
cid int AUTO_INCREMENT,
name text NOT NULL,
descripition text,
PRIMARY KEY (cid)
);

create table product
(
pid int AUTO_INCREMENT,
price Double NOT NULL,
cid int,
product_name text NOT NULL,
product_description text,
product_img text,
PRIMARY KEY(pid),
FOREIGN KEY (cid) REFERENCES category(cid)
);

create table sale
(
sid int AUTO_INCREMENT,
pid int NOT NULL,
percent double NOT NULL,
start_date Date NOT NULL,
end_date Date NOT NULL,
PRIMARY KEY(sid),
FOREIGN KEY(pid) REFERENCES product(pid)
);

insert into employee(username,password,fn,ln,age,type,payment) values('xingshi',password('xingshi'),'Xing','Shi',24,'admin',2000);
insert into employee(username,password,fn,ln,age,type,payment) values('henry',password('henry'),'Henry','Zhang',25,'sale_manager',3000);

