<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Robert Hallsey <rhallsey@yahoo.com>
 * @copyright 2016 Robert Hallsey
 * @license GPL v 3.0
 *
 * This is the driver file for Craigslist
 *
 */
class Sites_craigslist extends CI_Driver {

const SITE = 'http://sfbay.craigslist.com';
const SITE_CODE = 'CL';

	public function get_next_page_url($xpath)
	{
		$url = '';
		if (strpos($xpath->query('//div[contains(@class, "paginator")]')->item(0)->getAttribute('class'), 'lastpage') === false)
		{
			$url = self::SITE . $xpath->query('//a[@class="button next"]')->item(0)->getAttribute('href');
		}
		return $url;
	}
	
	public function get_rows($xpath)
	{
		return $xpath->query('//p[@class="row"]');
	}
	
	public function get_fields($xpath)
	{
		$fields = array(
			'title' => '',
			'city' => '',
			'employer' => '',
			'agency' => '',
			'description' => '',
			'url' => '',
			'date' => '',
			'code' => ''
		);
		$fields['title'] = $this->get_text_content($xpath->query('//a[@class="hdrlnk"]'));
		$field = $xpath->query('//a[@class="hdrlnk"]')->item(0)->getAttribute('href');
		if ($field[0] == '/') $field = self::SITE . $field;
		$fields['url'] = $field;
		$field = $xpath->query('//span[@class="l2"]')->item(0)->textContent;
		if (preg_match('/ *?\((.*?)\)/', $field, $matches)) $fields['city'] = $matches[1];
		$xpath->query('//time')->item(0)->getAttribute('datetime');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		return $fields;
	}
}
