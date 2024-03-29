<?php
include_once('classes.php');
$pdo=Tools::connect(); // вызов статичного метода из класса Tools

$role='CREATE TABLE roles(
id int not null auto_increment primary key,
role varchar(32) not null unique
)default charset="utf8"';

$customer='CREATE TABLE customers(
id int not null auto_increment primary key,
login varchar(32) not null unique,
pass varchar(128) not null,
roleid int,
foreign key(roleid) references roles(id) on update cascade,
discount int,
total int,
imagepath varchar(255)
)default charset="utf8"';

$cat='CREATE TABLE categories(
id int not null auto_increment primary key,
category varchar(64) not null unique
)default charset="utf8"';

$sub = 'CREATE TABLE subCategories(
id int not null auto_increment primary key,
subcategory varchar(64) not null unique,
catid int,
foreign key(catid) references categories(id) on update cascade
)default charset="utf8"';

$item = 'CREATE TABLE items(
id int not null auto_increment primary key,
itemname varchar(128) not null,
catid int,
foreign key(catid) references categories(id) on update cascade,
pricein int not null,
pricesale int not null,
info varchar(256) not null,
rate double,
imagepath varchar(256) not null,
action int
)default charset="utf8"';

$images = 'CREATE TABLE images(
id int not null auto_increment primary key,
itemid int,
foreign key(itemid) references items(id) on delete cascade,
imagepath varchar(255)
)default charset="utf8"';

$sale = 'CREATE TABLE sales(
id int not null auto_increment primary key,
customername varchar(32),
itemname varchar(128),
pricein int,
pricesale int,
datesale date
)default charset="utf8"';

// функция exec() - вызывает выполнение запроса, указанного в кач-ве аргумента

$pdo->exec($role);
$pdo->exec($customer);
$pdo->exec($cat);
$pdo->exec($sub);
$pdo->exec($item);
$pdo->exec($images);
$pdo->exec($sale);
echo 'VSE OK';

