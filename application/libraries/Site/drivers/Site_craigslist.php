<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_craigslist extends CI_Driver {

const SITE = 'http://sfbay.craigslist.com';
const SITE_CODE = 'CL';

	public function scrape($search)
	{
		$output = '';
		$url = $search['url'];
		while ($url)
		{
			$dom = $this->get_page($url);
			$xpath = new DOMXPath($dom);
			// find next page
			$elements = $xpath->query('//div[contains(@class, "paginator")]');
			$url = ($elements->length == 2 ? '' : self::SITE . $xpath->query('//a[@class="button next"]')->item(0)->getAttribute('href'));
			// extract rows from page
			$elements = $xpath->query('//p[@class="row"]');
			foreach ($elements as $element)
			{
				$output .= $this->extract_row($element);
			}
		}
		return $output;
	}
	
	public function extract_row($row)
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
		$field = $xpath->query('//a[@class="hdrlnk"]');
		$fields['title'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//a[@class="hdrlnk"]')->item(0)->getAttribute('href');
		if ($field[0] == '/') $field = self::SITE . $field;
		$fields['url'] = $field;
		$field = $xpath->query('//span[@class="l2"]')->item(0)->textContent;
		if (preg_match('/ *?\((.*?)\)/', $field, $matches)) $fields['city'] = $matches[1];
		$xpath->query('//time')->item(0)->getAttribute('datetime');
		$fields['date'] = date('Ymd', strtotime($xpath->query('//time')->item(0)->getAttribute('datetime')));
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
