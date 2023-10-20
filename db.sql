CREATE TABLE users (
    id PRIMARY KEY,
    name text NOT NULL,
    username TEXT,
    birthday DATE,
    country TEXT,
    city TEXT,
    gender TEXT,
    type TEXT,
    url TEXT,
    email TEXT UNIQUE,
    password TEXT,
    reputation INTEGER
);

CREATE TABLE admin(
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT ,
    birthday DATE,
    country TEXT,
    city TEXT,
    gender TEXT,
    type TEXT,
    url TEXT,
    email TEXT UNIQUE,
    password TEXT,
    reputation INTEGER,
    user_id INTEGER references users(id)
);

CREATE TABLE post(
    id INTEGER PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    postdate DATE,
    upvotes INTEGER,
    downvotes INTEGER,
    user_id INTEGER references users(id),
    topic_id INTEGER references topic(id),
    CONSTRAINT upvotes_check CHECK ((upvotes>= 0)),
    CONSTRAINT downvotes_check CHECK ((downvotes>= 0))
);

CREATE TABLE comment(
    id INTEGER PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    commentdate DATE,
    upvotes INTEGER,
    downvotes INTEGER,    
    post_id INTEGER references post(id),
    user_id INTEGER references users(id),
    CONSTRAINT upvotes_check CHECK ((upvotes>= 0)),
    CONSTRAINT downvotes_check CHECK ((downvotes>= 0))
);

CREATE TABLE notification(
    id INTEGER PRIMARY KEY,
    user_id INTEGER references users(id)
    title TEXT NOT NULL,
    caption TEXT,
    type TEXT,
);

CREATE TABLE topic(
    id INTEGER PRIMARY KEY,
    title TEXT not null,
    caption TEXT,
    followers INTEGER,
    CONSTRAINT followers_check CHECK ((followers >= 0)) 
);

CREATE TABLE topic_proposal(
    id INTEGER PRIMARY KEY,
    user_id INTEGER references users(id),
    admin_id INTEGER references admin(id),
    title TEXT,
    caption TEXT NOT NULL
);

CREATE TABLE image(
    id PRIMARY KEY,
    post_id INTEGER references post(id),
    comment_id INTEGER references comment(id),
    path TEXT,
);

CREATE TABLE video(
    video_id PRIMARY KEY,
    post_id INTEGER references post(id),
    comment_id INTEGER references comment(id),
    path TEXT,
);