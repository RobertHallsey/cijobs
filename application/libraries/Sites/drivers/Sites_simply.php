<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sites_simply extends CI_Driver {

const SITE = 'http://www.simplyhired.com';
const SITE_CODE = 'SH';

	public function get_next_page_url($xpath)
	{
		$url = '';
		$elements = $xpath->query('//a[@class="evtc next-pagination"]');
		if ($elements->length)
		{
			$url = self::SITE . $elements->item(0)->getAttribute('href');
		}
		return $url;
	}

	public function get_rows($xpath)
	{
		return $xpath->query('//div[@class="card js-job"]');
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
		$fields['title'] = $this->get_text_content($xpath->query('//h2'));
		$fields['city'] =  $this->get_text_content($xpath->query('//span[@itemprop="jobLocation"]'));
		$fields['employer'] =  $this->get_text_content($xpath->query('//span[@itemprop="name"]'));
		$fields['description'] =  $this->get_text_content($xpath->query('//span[@itemprop="description"]'));
		$field = $this->get_text_content($xpath->query('//p[@class="serp-timesource"]'));
		preg_match('/(?:Sponsored by | from )(.*?)$/', $field, $matches);
		$fields['agency'] = $matches[1];
		$fields['url'] =  self::SITE . $xpath->query('//a[@class="card-link js-job-link"]')->item(0)->getAttribute('href');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		return $fields;
	}
}
