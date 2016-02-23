CREATE DATABASE galactic_news;
USE galactic_news;

CREATE TABLE users (
  id              INT NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY ,
  user_name       VARCHAR(50) NOT NULL UNIQUE,
  email           VARCHAR(50) NOT NULL UNIQUE,
  passwrd         VARCHAR(250) NOT NULL,
  side            VARCHAR(10) NOT NULL
);

CREATE TABLE followers (
  id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  follower_id     INT NOT NULL,
  followed_id     INT NOT NULL,
  CONSTRAINT      follower_followed UNIQUE (follower_id, followed_id),
  FOREIGN KEY     (follower_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY     (followed_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE posts(
  id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id         INT NOT NULL, 
  content         VARCHAR(250),
  place           VARCHAR(50),
  created_at      DATETIME,
  FOREIGN KEY     (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (user_name, email, passwrd, side)
VALUES  ('abrahamrq', 'abraham.rq03@gmail.com', 'password', 'dark');

INSERT INTO posts (user_id, content, place, created_at)
VALUES  (1, 'lasers', 'Death Star', '');