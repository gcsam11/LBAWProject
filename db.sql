----------------------------------------------
--Drop Old Schema
----------------------------------------------

DROP TABLE IF users EXISTS CASCADE;
DROP TABLE IF admins EXISTS CASCADE;
DROP TABLE IF post EXISTS CASCADE;
DROP TABLE IF comment EXISTS CASCADE;
DROP TABLE IF topic EXISTS CASCADE;
DROP TABLE IF  notification EXISTS CASCADE;
DROP TABLE IF topic_proposal CASCADE;
DROP TABLE IF image CASCADE;
DROP TABLE IF video CASCADE;

----------------------------------------------
--Tables
----------------------------------------------

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


CREATE TABLE admins(
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

----------------------------------------------
--Indexes
----------------------------------------------

CREATE INDEX post_user ON post USING btree (user_id);
CLUSTER post WITH post_user;

CREATE INDEX comment_post ON comment USING btree (post_id);
CLUSTER comment USING comment_post;

CREATE INDEX notification_user ON notificatin USING hash (user_id);

-- FTS INDEXES

--FTS Post Search
CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.title), 'A') ||
         setweight(to_tsvector('english', NEW.caption), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.title <> OLD.title OR NEW.caption <> OLD.caption) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english', NEW.title), 'A') ||
             setweight(to_tsvector('english', NEW.caption), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER post_search_update
 BEFORE INSERT OR UPDATE ON post
 FOR EACH ROW
 EXECUTE PROCEDURE post_search_update();

CREATE INDEX search_post_idx ON post USING GIN (tsvectors);

--FTS User Search

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.name), 'A') ||
         setweight(to_tsvector('english', NEW.username), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.name <> OLD.name OR NEW.username <> OLD.username) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english', NEW.name), 'A') ||
             setweight(to_tsvector('english', NEW.username), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
 BEFORE INSERT OR UPDATE ON user
 FOR EACH ROW
 EXECUTE PROCEDURE user_search_update();

CREATE INDEX search_user_idx ON user USING GIN (tsvectors);