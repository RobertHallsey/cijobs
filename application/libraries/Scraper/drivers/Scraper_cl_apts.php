<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Robert Hallsey <rhallsey@yahoo.com>
 * @copyright 2016 Robert Hallsey
 * @license GPL v 3.0
 *
 * This is the driver file for Craigslist Housing
 *
 */
class Scraper_cl_apts extends CI_Driver {

const SITE = 'http://sfbay.craigslist.com';

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
			'date' => '',
			'description' => '',
			'rent' => '',
			'location' => '',
			'url' => '',
		);
		$fields['date'] = date('M j', strtotime($xpath->query('//time')->item(0)->getAttribute('datetime')));
		$fields['description'] = $this->get_text_content($xpath->query('//span[@class="pl"]/a'));
		$fields['rent'] = ltrim($this->get_text_content($xpath->query('//span[@class="price"]')), '$');
		$fields['location'] = ucwords(trim($this->get_text_content($xpath->query('//span[@class="pnr"]/small')), ' ()'));
		$fields['url'] = self::SITE . $xpath->query('//span[@class="pl"]/a')->item(0)->getAttribute('href');
		return $fields;
	}
}
