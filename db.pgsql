CREATE TABLE users(
    name VARCHAR (50) NOT NULL,
    username VARCHAR (50) PRIMARY KEY,
    birthday DATE,
    country VARCHAR (50),
    city VARCHAR (50),
    gender VARCHAR (50),
    type VARCHAR (50),
    url VARCHAR (50),
    email VARCHAR (50) UNIQUE,
    password VARCHAR (50),
    reputation INTEGER
);

CREATE TABLE admin(
    name VARCHAR (50) NOT NULL,
    username VARCHAR (50) PRIMARY KEY,
    birthday DATE,
    country VARCHAR (50),
    city VARCHAR (50),
    gender VARCHAR (50),
    type VARCHAR (50),
    url VARCHAR (50),
    email VARCHAR (50) UNIQUE,
    password VARCHAR (50),
    reputation INTEGER
);

CREATE TABLE post(
    title VARCHAR (50),
    caption VARCHAR (50),
    postdate DATE,
    
);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);

CREATE TABLE (

);