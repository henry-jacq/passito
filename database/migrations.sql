create table users (
    id int primary key auto_increment,
    email varchar(64) unique,
    password varchar(256),
    role enum('admin', 'user'),
    created_at datetime,
    updated_at datetime
);

create table students (
    id int primary key auto_increment,
    user_id int,
    name varchar(64),
    digital_id int unique,
    year int,
    branch varchar(32),
    room_no varchar(8),
    parent_no varchar(20),
    institution varchar(128),
    created_at datetime,
    updated_at datetime,
    constraint fk_stu_user_id foreign key (user_id) references users(id)
);

create table wardens (
    id int primary key auto_increment,
    user_id int,
    name varchar(64),
    phone_no varchar(20),
    created_at datetime,
    updated_at datetime,
    constraint fk_war_user_id foreign key (user_id) references users(id)
);
