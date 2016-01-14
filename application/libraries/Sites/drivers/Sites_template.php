<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Your Name <your_email@example.com>
 * @copyright 20-- by you
 * @license GPL v 3.0 (ok, you don't have to, but I hope you do)
 *
 * This is the driver file for Your Site
 *
 */
class Sites_template extends CI_Driver {

	public function get_next_page_url($xpath)
	{
		$url = '';
		// xpath query and other code to find url to next page
		return $url;
	}

	public function get_rows($xpath)
	{
		return $xpath->query('put-your-query-here');
	}
	
	public function get_fields(    )
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
