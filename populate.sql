-- Create users table and populate data
INSERT INTO USERS (id, name, username, birthday, country, gender, type, url, email, password, reputation)
VALUES (1, 'John Doe', 'johndoe', '1990-05-15', 'USA', 'Male', 'Regular', 'http://example.com', 'johndoe@example.com', 'password123', 100),
       (2, 'Alice Johnson', 'alicej', '1988-03-10', 'Toronto', 'Female', 'Regular', 'http://example.com/alice', 'alice@example.com', 'pass123', 120),
       (3, 'Bob Anderson', 'bob123', '1995-11-28', 'USA', 'Male', 'Regular', 'http://example.com/bob', 'bob@example.com', 'securepass', 90),
       (4, 'Eva Brown', 'evab', '1982-07-15', 'UK', 'Female', 'Regular', 'http://example.com/eva', 'eva@example.com', 'eva123', 80),
       (5, 'Jane Smith', 'janesmith', '1985-08-20', 'USA', 'Female', 'Regular', 'http://example.com/jane', 'janesmith@example.com', 'securepass', 150),
       (6, 'Michael Adams', 'mike123', '1980-09-05', 'USA', 'Male', 'Regular', 'http://example.com/mike', 'mike@example.com', 'mikepass', 110),
       (7, 'Sophie Martinez', 'sophie89', '1992-02-18', 'Spain', 'Female', 'Regular', 'http://example.com/sophie', 'sophie@example.com', 'sophie123', 95),
       (8, 'David Lee', 'davidl', '1986-07-30', 'Canada', 'Male', 'Regular', 'http://example.com/david', 'david@example.com', 'davidpass', 85),
       (9, 'Emma White', 'emmaw', '1998-12-12', 'Australia', 'Female', 'Regular', 'http://example.com/emma', 'emma@example.com', 'emma456', 75),
       (10, 'Olivia Taylor', 'olivia88', '1984-06-14', 'USA', 'Female', 'Regular', 'http://example.com/olivia', 'olivia@example.com', 'olivia567', 130),
       (11, 'Liam Brown', 'liamb', '1991-09-22', 'UK', 'Male', 'Regular', 'http://example.com/liam', 'liam@example.com', 'liampass', 105),
       (12, 'Ava Clark', 'ava.c', '1989-03-30', 'Canada', 'Female', 'Regular', 'http://example.com/ava', 'ava@example.com', 'ava321', 95),
       (13, 'Noah Evans', 'noah.e', '1995-12-05', 'Australia', 'Male', 'Regular', 'http://example.com/noah', 'noah@example.com', 'noahpass', 85);

-- Create admins table and populate data
INSERT INTO ADMINS (user_id)
VALUES (1),
       (3),
       (6),
       (11);

-- Create topic table and populate data
INSERT INTO TOPIC (title, caption, followers)
VALUES ('Technology', 'Discussions about the latest tech trends', 500),
       ('Travel', 'Explore the world and share your experiences', 300),
       ('Science', 'Discussion about scientific discoveries', 200),
       ('Food', 'Delicious recipes and culinary adventures', 400),
       ('Music', 'Explore the world of music and artists', 300),
       ('Art', 'Appreciating and creating art in all forms', 150),
       ('Fitness', 'Tips, tricks, and motivation for staying fit', 250),
       ('Books', 'Discussing favorite books and literary works', 180),
       ('Fashion', 'Trends, styles, and fashion tips', 180),
       ('Gaming', 'Video games, reviews, and gaming culture', 280),
       ('Health', 'Wellness, fitness, and healthy living', 200);

-- Create post table and populate data
INSERT INTO POST (title, caption, postdate, upvotes, downvotes, user_id, topic_id)
VALUES ('Introduction to AI', 'A beginners guide to Artificial Intelligence', '2023-10-20', 50, 5, 1, 1),
       ('Best Travel Destinations', 'Must-visit places around the globe', '2023-10-21', 30, 2, 2, 2),
       ('The Future of Space Exploration', 'Exciting developments in space science', '2023-10-22', 70, 3, 1, 1),
       ('Classic Italian Pasta Recipes', 'Mouth-watering pasta recipes from Italy', '2023-10-23', 40, 1, 3, 2),
       ('Top 10 Rock Albums of All Time', 'A journey through iconic rock music', '2023-10-24', 60, 2, 2, 3),
       ('Modern Abstract Art', 'Exploring the world of abstract art', '2023-10-25', 35, 2, 1, 1),
       ('Effective Cardio Workouts', 'Maximize your cardio sessions', '2023-10-26', 45, 3, 2, 2),
       ('Classic Novels You Must Read', 'Timeless literary classics', '2023-10-27', 55, 1, 3, 3),
       ('Australian Wildlife Photography', 'Capturing unique wildlife in Australia', '2023-10-28', 40, 1, 4, 1),
       ('Latest Fashion Trends', 'Stay in style with these trends', '2023-10-29', 42, 2, 1, 1),
       ('Top RPG Games of All Time', 'Explore the best role-playing games', '2023-10-30', 63, 1, 2, 2),
       ('Healthy Eating Habits', 'Tips for a balanced and nutritious diet', '2023-10-31', 55, 3, 3, 3),
       ('Australian Beaches Guide', 'Discover the most beautiful beaches', '2023-11-01', 48, 2, 4, 1);


-- Create comment table and populate data
INSERT INTO COMMENT (title, caption, commentdate, upvotes, downvotes, post_id, user_id)
VALUES ('Great article!', 'Very informative, thanks for sharing.', '2023-10-20', 20, 0, 1, 2),
       ('I completely agree', 'These destinations are amazing!', '2023-10-21', 15, 1, 2, 1),
       ('Amazing!', 'Im fascinated by space exploration.', '2023-10-22', 25, 1, 1, 3),
       ('Delicious!', 'I tried one of the recipes, and it was fantastic!', '2023-10-23', 20, 0, 2, 1),
       ('Rock on!', 'Great list! I love classic rock albums.', '2023-10-24', 30, 1, 3, 2),
       ('Inspiring!', 'Abstract art always sparks creativity.', '2023-10-25', 18, 1, 1, 2),
       ('Great tips!', 'I tried these workouts, and they work wonders!', '2023-10-26', 22, 0, 2, 3),
       ('Love these classics!', 'Pride and Prejudice is my favorite.', '2023-10-27', 25, 1, 3, 1),
       ('Amazing photos!', 'The wildlife in Australia is truly unique.', '2023-10-28', 20, 0, 4, 2),
       ('Love these outfits!', 'Fashion inspiration at its best.', '2023-10-29', 30, 1, 1, 3),
       ('Great list!', 'Ive played most of these games, they are fantastic.', '2023-10-30', 40, 0, 2, 4),
       ('Important tips!', 'Healthy eating is crucial for a good life.', '2023-10-31', 35, 2, 3, 2),
       ('Fantastic guide!', 'Planning my next beach vacation already.', '2023-11-01', 25, 0, 4, 3);


-- Create notification table and populate data
INSERT INTO NOTIFICATION (user_id, title, caption, type)
VALUES (1, 'New Follower', 'User xyz started following you.', 'follower'),
       (2, 'New Comment', 'Your post received a new comment.', 'comment'),
       (3, 'New Follower', 'User xyz started following you.', 'follower'),
       (1, 'New Comment', 'Your post received a new comment.', 'comment'),
       (4, 'New Follower', 'User abc started following you.', 'follower'),
       (1, 'New Comment', 'Your post received a new comment.', 'comment'),
       (2, 'New Follower', 'User xyz started following you.', 'follower'),
       (3, 'New Comment', 'Your post received a new comment.', 'comment');


-- Create topic_proposal table and populate data
INSERT INTO TOPIC_PROPOSAL (user_id, admin_id, title, caption)
VALUES (2, 1, 'Foodie Adventures', 'Exploring different cuisines and food cultures'),
       (1, 2, 'Wildlife Photography', 'Capturing the beauty of nature through photography'),
       (4, 3, 'Healthy Cooking', 'Delicious and nutritious recipes for a healthy lifestyle'),
       (1, 4, 'Home Decor Ideas', 'Creative and budget-friendly home decoration tips');


