# Job Board Scraper, a CodeIgniter 3.0 application.

## Purpose

This CodeIgniter application lets you store and execute job board searches on specific job sites. Executing a job search downloads a CSV file containing the job postings from that search. This CSV file can then be process with Excel or similar to weed out duplicates, perform finer job groupings, etc. The application separates the scraping code into a site-specific files, so it can be used to turn any series of row-based web pages into CSV data. You need to write the code for this, but this is explained later on.

CodeIgniter is a PHP framework that makes developing web applications easier and faster. For those learning CodeIgniter, exploring this application should serve as a good step up from the basic tutorials. The application is applicable in the real world and uses a few intermediate concepts, yet it's not overwhelmingly complex. It has only one controller and uses only two tables. Increasing the number of sites the application works on does not increase the number of tables, only the number of rows in the tables.

The use of webscrapers can be abusive and is thus controversial. This application is designed and intended to assist jobseekers in their search for work. An average jobseeker will download a few dozen or even a few hundred jobs per day, and this usage is not a problem at all. Please do not use this application abusively!

## Files Included

In the zip are two folders, `application` and `assets`, and one file, `createdb.sql`.

The application folder consists of these files:

* application/controllers/Searches.php
* application/libraries/Site.php
* application/libraries/Site_craigslist.php
* application/libraries/Site_indeed.php
* application/libraries/Site_simply.php
* application/models/Searches_model.php
* application/views/header.php
* application/views/footer.php
* application/views/searches_view.php
* application/views/search_add_view.php
* application/views/search_edit_view.php
* application/views/search_delete_view.php
* applications/views/search_execute_view.php

## Installation

Experienced users can set up single CodeIgniter installations to run multiple applications, but otherwise, start with a fresh CodeIgniter installation that successfully shows the welcome page.

Place the application files from the zip into their corresponding folders in your CodeIgniter installation.

Place the assets folder from the zip into the root of your CodeIgniter installation. It should be at the same level as the application folder.

Create the database according to the createdb.sql file. It's a plain text file. Open and copy the contents into the SQL command interface of whatever SQL administrator you use.

Edit the following files:

* config/routes.php - Set the default controller. `$route['default_controller'] = 'searches';`
* config/database.php - Set up your database. The application's database's name is `cijobs`.
* config/config.php - Set your base URL, something like `$config['base_url'] = 'http://www.example.com/cijobs/';`

The application should now run.

## Support

The zip file includes a documentation file. Feel free to email me with corrections, suggestions, and questions about the application. I'm only an intermediate CodeIgniter developer, so I'm not likely to be able to answer questions about CodeIgniter itself. Please use the excellent CodeIgniter forums for that (http://forum.codeigniter.com/)

## License

This work is copyright 2015 Robert Hallsey, rhallsey@yahoo.com.

This work is released under the GNU GPL 3.0 license. For more info, see https://www.gnu.org/copyleft/gpl.html
