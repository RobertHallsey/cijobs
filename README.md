# Search Results Extractor, a CodeIgniter 3.0 application.

## Purpose

This CodeIgniter application provides a framework designed to automate your regular web searches. When you search for something on a website, search results are displayed on the screen in rows, up to a certain number of them, and one or more links lead to the next page of search results, until reaching the last page. This application stores your search queries and executes them on your command, extracting the search results page after page and downloading them in CSV format. CSV files can be easily imported into Excel and other applications for further processing. 

This type of data extraction cannot be generalized because not only is every website different, but also website code changes over time. This means that you will have to supply the code to extract search results from the websites you want. This is not difficult to do, and if you're learning PHP or CodeIgniter, it's an excellent step-up in complexity from the usual beginner tutorials. The application includes class files for three job websites: Craigslist, Indeed, and Simply Hired. Keep in mind that _what_ it extracts is dictated by the class files, so you could use this to extract apartment listings, parts from a catalog, personal ads, anything! You are encouraged to submit any class files you create.

The use of webscrapers can be abusive and is thus controversial. Please do not use this application abusively!

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

Run the application.

## Support

The zip file includes a documentation file. Feel free to email me with corrections, suggestions, and questions about the application. I'm only an intermediate CodeIgniter developer, so I'm not likely to be able to answer questions about CodeIgniter itself. Please use the excellent CodeIgniter forums for that (http://forum.codeigniter.com/)

## License

This work is copyright 2015 Robert Hallsey, rhallsey@yahoo.com.

This work is released under the GNU GPL 3.0 license. For more info, see https://www.gnu.org/copyleft/gpl.html
