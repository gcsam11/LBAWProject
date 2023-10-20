CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
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


CREATE TABLE admin (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT,
    birthday DATE,
    country TEXT,
    city TEXT,
    gender TEXT,
    type TEXT,
    url TEXT,
    email TEXT UNIQUE,
    password TEXT,
    reputation INTEGER,
    user_id INTEGER REFERENCES users(id)
);


CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    postdate DATE,
    upvotes INTEGER CHECK (upvotes >= 0),
    downvotes INTEGER CHECK (downvotes >= 0),
    user_id INTEGER REFERENCES users(id),
    topic_id INTEGER REFERENCES topic(id)
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    commentdate DATE,
    upvotes INTEGER CHECK (upvotes >= 0),
    downvotes INTEGER CHECK (downvotes >= 0),    
    post_id INTEGER REFERENCES post(id),
    user_id INTEGER REFERENCES users(id)
);


CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    title TEXT NOT NULL,
    caption TEXT,
    type TEXT
);


CREATE TABLE topic (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT,
    followers INTEGER CHECK (followers >= 0)
);


CREATE TABLE topic_proposal (
    id SERIAL PRIMARY KEY,
    user_id INTEGER,
    admin_id INTEGER,
    title TEXT,
    caption TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (admin_id) REFERENCES admin(id)
);


CREATE TABLE image (
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id),
    comment_id INTEGER REFERENCES comment(id),
    path TEXT
);


CREATE TABLE video (
    video_id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES post(id),
    comment_id INTEGER REFERENCES comment(id),
    path TEXT
);