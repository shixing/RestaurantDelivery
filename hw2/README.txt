URL: cs-server.usc.edu:10000/hw2/login2.php
-------------------------------------------
Login Info:
username password type
xingshi	 xingshi  admin
henry	 henry	  manager
dingna	 dingna	  sale_manager
-------------------------------------------
DateBase design:
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
-------------------------------------------
File Description:

PHP Files:
ad_login_error.php	contain err_msg
create.php		process create operation
delete.php		process delete operation
layout.php		seems we don't need this file
list.php		when delete and modify, list all the entries in that table
login2.php	login page
logout.php	logout page after logged out
modify.php	       process modify operation
modify_record.php      list the record we want to modify
post.php	last half page;
pre.php		first half page;
sql.php		process ajax call: receive a sql, return the results
table.php	
time_out.php	timeout page after time out 
user_home.php	after login, the page for user to choose operation and table
utils.php	contain almost every function needed!
view.php	process searching option for manager
welcome_row.php	the welcome row, print user's name and type

HTML FILES:
ad_head.html	contain <head></head> 
ad_header.html	contain the navigation bar
ad_login_form.html	contain login form which is required by login2.php

JS FILES:
main2.js	Data validation and Ajax call for report pages

CSS FILES:
main.css
---------------------------------------------
Validate: Both js and php validation

Timeout: set $_SESSION['time'] and compare with time(), will timeout after 5 minutes. After timeout, automatically log out.

Logout: session_unset(); session_destroy();

Generate Reports: javascript generate sql statements, send to sql.php, sql.php render the output in a <table/> element, and send back, js send it as a <div>'s innerHTML. (AJAX call);

