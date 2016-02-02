<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Your Name <your_email@example.com>
 * @copyright 20-- by you
 * @license GPL v 3.0 (ok, you don't have to, but I hope you do)
 *
 * This is the driver file for Your Site
 *
 */
class Scraper_template extends CI_Driver {

/**
 * @param XPath object, $xpath, contains the entire page
   @return string, must be the URL to the next page or ''
 */
	public function get_next_page_url($xpath)
	{
		$url = '';
		// xpath query and other code to find url to next page
		return $url;
	}

/**
 * @param XPath object, $xpath, contains the entire page
   @return NodeList, containing rows as queried or null
 */
	public function get_rows($xpath)
	{
		return $xpath->query('put-your-query-here');
	}
	
/**
 * @param XPath object, $xpath, contains only one row
   @return string array, containing fields
 */
	public function get_fields($xpath)
	{
		$fields = array(
			// 'field_1' => '',
			// 'field_2' => '',
			// 'field_3' => '',
			// whatever fields you want
		);
		// your code goes here
		// get data from row and
		// assign it to $fields array
		return $fields;
	}
}
