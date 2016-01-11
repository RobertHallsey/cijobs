<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_simply extends CI_Driver {

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
		
	public function get_fields($row)
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
		$dom = new DOMDocument;
		$dom->appendChild($dom->importNode($row, true));
		$xpath = new DOMXPath($dom);
		$field = $xpath->query('//h2');
		$fields['title'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@itemprop="jobLocation"]');
		$fields['city'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@itemprop="name"]');
		$fields['employer'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//p[@itemprop="description"]');
		$fields['description'] = (($field->length) ? $this->clean_field($xpath->query('//p[@itemprop="description"]')->item(0)->textContent) : '');
		preg_match('/(?:Sponsored by | from )(.*?)$/', $this->clean_field($xpath->query('//p[@class="serp-timesource"]')->item(0)->textContent), $matches);
		$fields['agency'] = $matches[1];
		$fields['url'] =  $xpath->query('//a[@data-event="job_description_link_click"]')->item(0)->getAttribute('href');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
