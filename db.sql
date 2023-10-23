-- Drop Old Schema
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS admins CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS topic CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS topic_proposal CASCADE;
DROP TABLE IF EXISTS image CASCADE;
DROP TABLE IF EXISTS video CASCADE;

-- Create Tables
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

CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
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
    user_id INTEGER REFERENCES users(id),
    admin_id INTEGER,
    title TEXT,
    caption TEXT NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES admins(id)
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

-- Create Indexes
CREATE INDEX post_user ON post USING btree (user_id);
CLUSTER post USING post_user;

CREATE INDEX comment_post ON comment USING btree (post_id);
CLUSTER comment USING comment_post;

CREATE INDEX notification_user ON notification USING btree (user_id);

-- Full Text Search Indexes
-- (Assuming you have a tsvector column tsvectors in post and users tables)

-- Create function for updating post search index
CREATE OR REPLACE FUNCTION post_search_update()
RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
     NEW.tsvectors := (
         setweight(to_tsvector('english', COALESCE(NEW.title, '')), 'A') ||
         setweight(to_tsvector('english', COALESCE(NEW.caption, '')), 'B')
     );
 END IF;
 RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger for updating post search index
CREATE TRIGGER post_search_update_trigger
BEFORE INSERT OR UPDATE ON post
FOR EACH ROW
EXECUTE FUNCTION post_search_update();

-- Create the GIN index for post full-text search
CREATE INDEX search_post_idx ON post USING GIN (tsvectors);

-- Create function for updating user search index
CREATE OR REPLACE FUNCTION user_search_update()
RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
     NEW.tsvectors := (
         setweight(to_tsvector('english', COALESCE(NEW.name, '')), 'A') ||
         setweight(to_tsvector('english', COALESCE(NEW.username, '')), 'B')
     );
 END IF;
 RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger for updating user search index
CREATE TRIGGER user_search_update_trigger
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION user_search_update();

-- Create the GIN index for user full-text search
CREATE INDEX search_user_idx ON users USING GIN (tsvectors);

-- Trigger for creating notification when a user comments on a post
CREATE OR REPLACE FUNCTION new_comment_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO notification (user_id, title, type)
    VALUES ((SELECT user_id FROM post WHERE id = NEW.post_id), 'New comment on your post', 'New comment');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger for preventing commenting on own post
CREATE OR REPLACE FUNCTION prevent_comment_on_own_post()
RETURNS TRIGGER AS $$
BEGIN
  IF NEW.user_id = (SELECT user_id FROM post WHERE id = NEW.post_id) THEN
    RAISE EXCEPTION 'You cannot comment on your own post';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger for enforcing comment date constraint
CREATE OR REPLACE FUNCTION enforce_comment_date_constraint()
RETURNS TRIGGER AS $$
BEGIN
  DECLARE news_published_date DATE;
  SELECT post.postdate INTO news_published_date FROM post WHERE post.id = NEW.post_id;

  IF NEW.commentdate < news_published_date THEN
    RAISE EXCEPTION 'Comment date must be greater than or equal to the news publication date';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Attach triggers to the comment table
CREATE TRIGGER new_comment_trigger
AFTER INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION new_comment_notification();

CREATE TRIGGER prevent_comment_on_own_post_trigger
BEFORE INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION prevent_comment_on_own_post();

CREATE TRIGGER enforce_comment_date_constraint_trigger
BEFORE INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION enforce_comment_date_constraint();

--  Insert new post
BEGIN;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
INSERT INTO post (title, caption, postdate, upvotes, downvotes, user_id, topic_id);
VALUES (%title,%caption,%postdate, 0, 0, %user_id, %topic_id);
INSERT INTO image (post_id, path);
VALUES (currval('post_id_seq'), %path);
COMMIT;

-- Retrieve latest comments from active users
BEGIN;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY;
WITH LatestComments AS (
    SELECT user_id, MAX(commentdate) AS latest_comment_date
    FROM comment
    GROUP BY user_id
    HAVING MAX(commentdate) >= CURRENT_DATE - INTERVAL '3 months'
)
SELECT users.id AS user_id, users.name AS user_name, 
       comment.id AS comment_id, comment.title AS comment_title, 
       comment.caption AS comment_caption, comment.commentdate
FROM users
JOIN comment ON users.id = comment.user_id
JOIN LatestComments ON comment.user_id = LatestComments.user_id
WHERE comment.commentdate = LatestComments.latest_comment_date
ORDER BY users.id, comment.id;
COMMIT;



