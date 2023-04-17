drop database if exists youtube;

create database youtube;
use youtube;

create table users(
    id int auto_increment primary key,
    email varchar(255),
    username varchar(255),
    password varchar(255),
    deleted boolean default false,
    pb varchar(255),
    createddate date,
    subscribers int default 0
);

create table videos(
    id int auto_increment primary key,
    users_id int,
    url varchar(500),
    likes int default 0,
    thumbnail varchar(500),
    description varchar(500),
    dislikes int default 0,
    date datetime,
    title varchar(255),
    views int default 0,
    deleted boolean default false,
    foreign key (users_id) references users(id)
);

create table comments(
    id int auto_increment,
    video_id int,
    users_id int,
    comment text,
    date_created datetime,
    deleted boolean default false,
    primary key (id),
    foreign key (video_id) references videos(id),
    foreign key (users_id) references users(id)
);

CREATE TABLE likes (
    users_id int,
    video_id int,
    primary key (users_id, video_id),
    foreign key (users_id) references users(id),
    foreign key (video_id) references videos(id)
);

CREATE TABLE dislikes (
    users_id int,
    video_id int,
    primary key (users_id, video_id),
    foreign key (users_id) references users(id),
    foreign key (video_id) references videos(id)
);

create table subscribers(
    user_id int,
    creator_id int,
    primary key (user_id, creator_id),
    foreign key (user_id) references users(id),
    foreign key (creator_id) references users(id)
);
