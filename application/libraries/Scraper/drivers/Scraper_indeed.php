<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Robert Hallsey <rhallsey@yahoo.com>
 * @copyright 2016 Robert Hallsey
 * @license GPL v 3.0
 *
 * This is the driver file for Indeed
 *
 */
class Scraper_indeed extends CI_Driver {

const SITE = 'http://www.indeed.com';
const SITE_URL = '/rc/clk?jk=';
const SITE_CODE = 'ND';

	public function get_next_page_url($xpath)
	{
		$url = '';
		$elements = $xpath->query('//div[@class="pagination"]');
		if ($elements->length && $elements->item(0)->lastChild->textContent == 'Next' . html_entity_decode('&nbsp;') . '»')
		{
			$url = self::SITE . $elements->item(0)->lastChild->getAttribute('href');
		}
		return $url;
	}

	public function get_rows($xpath)
	{
		return $xpath->query('//div[@data-jk]');
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
		$fields['title'] = $this->get_text_content($xpath->query('//a[@data-tn-element="jobTitle"]'));
		$fields['city'] = $this->get_text_content($xpath->query('//span[@class="location"]'));
		$fields['employer'] = $this->get_text_content($xpath->query('//span[@class="company"]'));
		$fields['agency'] = ''; // for compatibility with Simply Hired
		$fields['description'] = $this->get_text_content($xpath->query('//span[@class="summary"]'));
		$fields['url'] = self::SITE . self::SITE_URL . $xpath->query('//div[@data-jk]')->item(0)->getAttribute('data-jk');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		return $fields;
	}

}
