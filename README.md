# Search Scraper, a CodeIgniter 3.0 application.

## Purpose

This CodeIgniter application provides a framework designed to automate your regular web searches.

How does searching on the web work, from a user's perspective? You fill out a form with what you're looking for and click the submit button. The website then shows you the first page of hits or results, arranged in rows. If there are more results, the page will have a link to the next page of results, proceeding page by page until there are no more search results. Wouldn't it be nice if you didn't have to sit there, clicking on the next page link? Wouldn't it be nice if you could just search and actually download the search results as data you could then further search and sort with Excel or similar applications? Well, that's what this application does!

This application stores your searches and executes them on your command, extracting the search results page after page and downloading them in CSV format. CSV files can be easily imported into Excel and other applications for further processing. This type of data extraction cannot be generalized because not only is every website different, but also website code changes over time. This means that you will have to supply the code to extract search results from the websites you want. This is not difficult to do, and if you're learning PHP or CodeIgniter, it's an excellent step-up in complexity from the usual beginner tutorials. The application includes class files for three job websites: Craigslist, Indeed, and Simply Hired. Keep in mind that _what_ it extracts is dictated by the class files, so you could use this to extract apartment listings, parts from a catalog, personal ads, anything! You are encouraged to submit any class files you create.

The use of webscrapers can be abusive and is thus controversial. Please do not use this application abusively!

## Files Included

In the zip are two folders, `application` and `assets`, and one file, `createdb.sql`.

The application folder consists of these files:

* application/controllers/Searches.php
* application/libraries/Scraper/Scraper.php
* application/libraries/Scraper/drivers/Scraper_craigslist.php
* application/libraries/Scraper/drivers/Scraper_indeed.php
* application/libraries/Scraper/drivers/Scraper_simply.php
* application/libraries/Scraper/drivers/Scraper_template.php
* application/models/Searches_model.php
* application/views/header.php
* application/views/footer.php
* application/views/searches_view.php
* application/views/search_add_view.php
* application/views/search_edit_view.php
* application/views/search_delete_view.php
* application/views/search_execute_view.php

## Installation

Start with a fresh CodeIgniter installation that successfully shows the welcome page.

Place the application files from the zip into their corresponding folders in your CodeIgniter installation.

Place the assets folder from the zip into the root of your CodeIgniter installation. It should be at the same level as the application folder. The folder contains a single file, `style.css`, which you can use to style the application's appearance.

Create the database according to the `createdb.sql` file.

Note: On some older, less expensive hosts, you may have only one database, which may already be created for you, and even if you create it, you may not be free to name it whatever you want. In this case, you typically create just the tables and name them with prefixes to indicate they are part of the same application. If you do that, make sure to update `application/models/Searches_model.php`.

Edit the following files:

* config/routes.php - Set the default controller. `$route['default_controller'] = 'searches';`
* config/database.php - Set up your database. The application's database's name is `searchscraper` (or the current name if you have only one database).
* config/config.php - Set your base URL, something like `$config['base_url'] = 'http://www.example.com/searchscraper/';`

Run the application.

## Support

The zip file includes a documentation file. Feel free to email me with questions, suggestions, or corrections. I'm only an intermediate CodeIgniter developer, so I'm not likely to be able to answer questions about CodeIgniter itself. Please use the excellent CodeIgniter forums for that (http://forum.codeigniter.com/)

## License

This work is copyright 2015 Robert Hallsey, rhallsey@yahoo.com.

This work is released under the GNU GPL 3.0 license. For more info, see https://www.gnu.org/copyleft/gpl.html
