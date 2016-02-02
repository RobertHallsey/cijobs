-- Search Scraper, a CodeIgniter 3.0 application
-- Copyright (c) 2016 Robert Hallsey
-- Released under GPL 3.0
-- January 2016

-- Script to create and load initial values

-- If you have only one database, include a prefix in
-- the table names to indicate they belong together.
-- For example, ss_searches and ss_sites, where ss
-- stands for Search Scraper. If you do this, make sure
-- to update the model file! Also make sure you set the
-- right database name in config/database.php.

-- If you can create your own databases, you should be
-- able to execute this script in your SQL admin panel.


CREATE DATABASE searchscraper;

USE searchscraper;

CREATE TABLE searches (
  id int(11) NOT NULL AUTO_INCREMENT,
  site_id int(11) NOT NULL,
  name varchar(32) NOT NULL,
  url varchar(254) NOT NULL,
  saveas varchar(32) NOT NULL,
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