CREATE DATABASE yeticave;

USE yeticave;

CREATE TABLE lot(
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_start DATETIME,
  date_expires DATETIME,
  name CHAR(128),
  description TEXT,
  image CHAR(128),
  price_start INT,
  bet_step INT,
  likes INT,
  author_id INT,
  winner_id INT,
  category_id INT
);

CREATE INDEX lot_name on lot(name);
CREATE INDEX lot_author on lot(author_id);
CREATE INDEX lot_winner on lot(winner_id);
CREATE INDEX lot_category on lot(category_id);

CREATE TABLE category(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128)
);

CREATE UNIQUE INDEX category_id on category(id);

CREATE TABLE user(
  id INT AUTO_INCREMENT PRIMARY KEY,
  registeration_date DATETIME,
  email CHAR(255),
  password CHAR(128),
  name CHAR(128),
  avatar CHAR(128),
  contacts_info TEXT
);

CREATE UNIQUE INDEX user_email ON user(email);

CREATE TABLE bet(
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATETIME,
  price INT,
  lot_id INT,
  user_id INT
);

CREATE INDEX bet_user_id ON bet(user_id);
CREATE INDEX bet_lot_id ON bet(lot_id);
