-- Drop Old Schema
DROP TABLE IF EXISTS USER CASCADE;
DROP TABLE IF EXISTS ADMIN CASCADE;
DROP TABLE IF EXISTS POST CASCADE;
DROP TABLE IF EXISTS COMMENT CASCADE;
DROP TABLE IF EXISTS UPVOTE_POST CASCADE;
DROP TABLE IF EXISTS DOWNVOTE_POST CASCADE;
DROP TABLE IF EXISTS UPVOTE_COMMENT CASCADE;
DROP TABLE IF EXISTS DOWNVOTE_COMMENT CASCADE;
DROP TABLE IF EXISTS TOPIC CASCADE;
DROP TABLE IF EXISTS USER_TOPIC CASCADE;
DROP TABLE IF EXISTS NOTIFICATION CASCADE;
DROP TABLE IF EXISTS TOPIC_PROPOSAL CASCADE;
DROP TABLE IF EXISTS IMAGE CASCADE;
DROP TABLE IF EXISTS VIDEO CASCADE;
DROP TABLE IF EXISTS IMAGE_POST CASCADE;
DROP TABLE IF EXISTS IMAGE_COMMENT CASCADE;

-- Create Tables
CREATE TABLE USER (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE,
    name TEXT NOT NULL,
    birthday DATE,
    country TEXT,
    gender TEXT,
    type TEXT,
    url TEXT,
    email TEXT UNIQUE,
    password TEXT,
    reputation INTEGER,
    image_id INTEGER,
    CONSTRAINT fk_userimage FOREIGN KEY(image_id) REFERENCES IMAGE(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE ADMIN (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_admin FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE POST (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    postdate DATE,
    upvotes INTEGER CHECK (upvotes >= 0),
    downvotes INTEGER CHECK (downvotes >= 0),
    user_id INTEGER NOT NULL,
    topic_id INTEGER,
    video_id INTEGER,
    CONSTRAINT fk_userpost FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_posttopic FOREIGN KEY(topic_id) REFERENCES TOPIC(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_postvideo FOREIGN KEY(video_id) REFERENCES VIDEO(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE COMMENT (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    commentdate DATE,
    upvotes INTEGER CHECK (upvotes >= 0),
    downvotes INTEGER CHECK (downvotes >= 0),
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_commentuser REFERENCES USER(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_postcomment REFERENCES POST(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE UPVOTE_POST(
    id SERIAL PRIMARY KEY,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_postupvote FOREIGN KEY(post_id) REFERENCES POST(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_userupvote FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE DOWNVOTE_POST(
    id SERIAL PRIMARY KEY,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_postdownvote FOREIGN KEY(post_id) REFERENCES POST(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_userdownvote FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE UPVOTE_COMMENT(
    id SERIAL PRIMARY KEY,
    comment_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_commentupvote FOREIGN KEY(comment_id) REFERENCES COMMENT(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_usercommentupvote FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE DOWNVOTE_COMMENT(
    id SERIAL PRIMARY KEY,
    comment_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_commentdownvote FOREIGN KEY(comment_id) REFERENCES COMMENT(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_usercommentdownvote FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE NOTIFICATION (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    type TEXT NOT NULL,
    CONSTRAINT fk_notificationuser FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE TOPIC (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    followers INTEGER CHECK (followers >= 0)
);

CREATE TABLE USER_TOPIC(
    user_id INTEGER,
    topic_id INTEGER,
    PRIMARY KEY(user_id, topic_id),
    CONSTRAINT fk_usertopic FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_topic FOREIGN KEY(topic_id) REFERENCES TOPIC(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE TOPIC_PROPOSAL (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    admin_id INTEGER --DEFAULT?,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    CONSTRAINT fk_topicproposaluser FOREIGN KEY(user_id) REFERENCES USER(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_topicproposaladmin FOREIGN KEY (admin_id) REFERENCES ADMIN(id) ON UPDATE ON DELETE CASCADE
);

CREATE TABLE IMAGE (
    id SERIAL PRIMARY KEY,
    path TEXT NOT NULL
);

CREATE TABLE VIDEO (
    id SERIAL PRIMARY KEY,
    path TEXT NOT NULL
);

CREATE TABLE IMAGE_POST(
    image_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    CONSTRAINT fk_image_post FOREIGN KEY(image_id) REFERENCES IMAGE(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_post FOREIGN KEY(post_id) REFERENCES POST(id) ON UPDATE ON DELETE CASCADE,
    PRIMARY KEY(image_id, post_id)
);

CREATE TABLE IMAGE_COMMENT(
    image_id INTEGER NOT NULL,
    comment_id INTEGER NOT NULL,
    CONSTRAINT fk_image_comment FOREIGN KEY(image_id) REFERENCES IMAGE(id) ON UPDATE ON DELETE CASCADE,
    CONSTRAINT fk_comment FOREIGN KEY(comment_id) REFERENCES COMMENT(id) ON UPDATE ON DELETE CASCADE,
    PRIMARY KEY(image_id, comment_id)
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

--Create Trigger for POST UPVOTE/DOWNVOTE counter

--Create Trigger for COMMENT UPVOTE/DOWNVOTE counter

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

