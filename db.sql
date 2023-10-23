-- Drop Old Schema
DROP TABLE IF EXISTS USERS CASCADE;
DROP TABLE IF EXISTS ADMINS CASCADE;
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
DROP INDEX IF EXISTS post_user;
DROP INDEX IF EXISTS comment_post;
DROP INDEX IF EXISTS notification_user;


-- Create Tables
CREATE TABLE IMAGE (
    id SERIAL PRIMARY KEY,
    path TEXT NOT NULL
);

CREATE TABLE TOPIC (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    followers INTEGER CHECK (followers >= 0)
);

CREATE TABLE VIDEO (
    id SERIAL PRIMARY KEY,
    path TEXT NOT NULL
);

CREATE TABLE USERS (
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
    CONSTRAINT fk_userimage FOREIGN KEY(image_id) REFERENCES IMAGE(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE ADMINS (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_admin FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE
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
    CONSTRAINT fk_userpost FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_posttopic FOREIGN KEY(topic_id) REFERENCES TOPIC(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_postvideo FOREIGN KEY(video_id) REFERENCES VIDEO(id) ON UPDATE CASCADE ON DELETE CASCADE
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
    CONSTRAINT fk_commentuser FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_postcomment FOREIGN KEY(post_id) REFERENCES POST(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE UPVOTE_POST(
    id SERIAL PRIMARY KEY,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_postupvote FOREIGN KEY(post_id) REFERENCES POST(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_userupvote FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE DOWNVOTE_POST(
    id SERIAL PRIMARY KEY,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_postdownvote FOREIGN KEY(post_id) REFERENCES POST(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_userdownvote FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE UPVOTE_COMMENT(
    id SERIAL PRIMARY KEY,
    comment_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_commentupvote FOREIGN KEY(comment_id) REFERENCES COMMENT(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_usercommentupvote FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE DOWNVOTE_COMMENT(
    id SERIAL PRIMARY KEY,
    comment_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    CONSTRAINT fk_commentdownvote FOREIGN KEY(comment_id) REFERENCES COMMENT(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_usercommentdownvote FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE NOTIFICATION (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    type TEXT NOT NULL,
    CONSTRAINT fk_notificationuser FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE USER_TOPIC(
    user_id INTEGER,
    topic_id INTEGER,
    PRIMARY KEY(user_id, topic_id),
    CONSTRAINT fk_usertopic FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE 
    ON DELETE CASCADE,
    CONSTRAINT fk_topic FOREIGN KEY(topic_id) REFERENCES TOPIC(id) ON UPDATE CASCADE 
    ON DELETE CASCADE
);

CREATE TABLE TOPIC_PROPOSAL (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    admin_id INTEGER, --DEFAULT?
    title TEXT NOT NULL,
    caption TEXT NOT NULL,
    CONSTRAINT fk_topicproposaluser FOREIGN KEY(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_topicproposaladmin FOREIGN KEY (admin_id) REFERENCES ADMINS(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IMAGE_POST(
    image_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    CONSTRAINT fk_image_post FOREIGN KEY(image_id) REFERENCES IMAGE(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_post FOREIGN KEY(post_id) REFERENCES POST(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY(image_id, post_id)
);

CREATE TABLE IMAGE_COMMENT(
    image_id INTEGER NOT NULL,
    comment_id INTEGER NOT NULL,
    CONSTRAINT fk_image_comment FOREIGN KEY(image_id) REFERENCES IMAGE(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_comment FOREIGN KEY(comment_id) REFERENCES COMMENT(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY(image_id, comment_id)
);

-- Create Indexes
CREATE INDEX post_user ON POST USING btree (user_id);
CLUSTER POST USING post_user;

CREATE INDEX comment_post ON COMMENT USING btree (post_id);
CLUSTER COMMENT USING comment_post;

CREATE INDEX notification_user ON notification USING btree (user_id);

-- Full Text Search Indexes
-- (Assuming you have a tsvector column tsvectors in POST and USERS tables)

-- Create function for updating POST search index
/*CREATE OR REPLACE FUNCTION post_search_update()
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

-- Trigger for updating POST search index
CREATE TRIGGER post_search_update_trigger
BEFORE INSERT OR UPDATE ON POST
FOR EACH ROW
EXECUTE FUNCTION post_search_update();

-- Create the GIN index for POST full-text search
CREATE INDEX search_post_idx ON POST USING GIN(tsvector);

-- Create function for updating USERS search index
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

-- Trigger for updating USERS search index
CREATE TRIGGER user_search_update_trigger
BEFORE INSERT OR UPDATE ON USERS
FOR EACH ROW
EXECUTE FUNCTION user_search_update();

-- Create the GIN index for USERS full-text search
--CREATE INDEX search_user_idx ON USERS USING GIN(tsvector);

-- Trigger for creating notification when a USERS comments on a POST
CREATE OR REPLACE FUNCTION new_comment_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO notification (user_id, title, type)
    VALUES ((SELECT user_id FROM POST WHERE id = NEW.post_id), 'New COMMENT on your POST', 'New COMMENT');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger for enforcing COMMENT date constraint
CREATE OR REPLACE FUNCTION enforce_comment_date_constraint()
RETURNS TRIGGER AS $$
BEGIN
  CREATE TABLE news_published_date;
  SELECT POST.postdate INTO news_published_date FROM POST WHERE POST.id = NEW.post_id;

  IF NEW.commentdate < news_published_date THEN
    RAISE EXCEPTION 'COMMENT date must be greater than or equal to the news publication date';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Attach triggers to the COMMENT table
CREATE TRIGGER new_comment_trigger
AFTER INSERT ON COMMENT
FOR EACH ROW
EXECUTE FUNCTION new_comment_notification();

CREATE TRIGGER prevent_comment_on_own_post_trigger
BEFORE INSERT ON COMMENT
FOR EACH ROW
EXECUTE FUNCTION prevent_comment_on_own_post();

CREATE TRIGGER enforce_comment_date_constraint_trigger
BEFORE INSERT ON COMMENT
FOR EACH ROW
EXECUTE FUNCTION enforce_comment_date_constraint();

--Create Trigger for POST UPVOTE/DOWNVOTE counter

--Create Trigger for COMMENT UPVOTE/DOWNVOTE counter

--  Insert new POST
BEGIN;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
INSERT INTO POST (title, caption, postdate, upvotes, downvotes, user_id, topic_id)
VALUES (%title,%caption,%postdate, 0, 0, %user_id, %topic_id);
INSERT INTO image (post_id, path)
VALUES (currval('post_id_seq'), %path);
COMMIT;

-- Retrieve latest comments from active USERS
BEGIN;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY;
WITH LatestComments AS (
    SELECT user_id, MAX(commentdate) AS latest_comment_date
    FROM COMMENT
    GROUP BY user_id
    HAVING MAX(commentdate) >= CURRENT_DATE - INTERVAL '3 months'
)
SELECT USERS.id AS user_id, USERS.name AS user_name, 
       COMMENT.id AS comment_id, COMMENT.title AS comment_title, 
       COMMENT.caption AS comment_caption, COMMENT.commentdate
FROM USERS
JOIN COMMENT ON USERS.id = COMMENT.user_id
JOIN LatestComments ON COMMENT.user_id = LatestComments.user_id
WHERE COMMENT.commentdate = LatestComments.latest_comment_date
ORDER BY USERS.id, COMMENT.id;
COMMIT;
*/
