CREATE DATABASE searchscraper;

USE searchscraper;

CREATE TABLE searches (
  id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) NOT NULL,
  name varchar(32) NOT NULL,
  url varchar(254) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE sites (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(32) NOT NULL,
  driver varchar(16) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO sites (name, class) VALUES ('Indeed', 'site_indeed');
INSERT INTO sites (name, class) VALUES ('Simply Hired', 'site_simply');