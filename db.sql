CREATE TABLE accounts (
	user_id serial PRIMARY KEY,
	username VARCHAR ( 50 ) UNIQUE NOT NULL,
	password VARCHAR ( 50 ) NOT NULL,
	email VARCHAR ( 255 ) UNIQUE NOT NULL,
	created_on TIMESTAMP NOT NULL,
        last_login TIMESTAMP 
);


CREATE TABLE users (
    id serial PRIMARY KEY,
    
    name text NOT NULL,
    username VARCHAR ( 50 ),
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
    post_id INTEGER,
    title VARCHAR (50),
    caption VARCHAR (50),
    postdate DATE,
    upvotes INTEGER,
    downvotes INTEGER,
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