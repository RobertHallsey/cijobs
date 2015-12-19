<?php defined('BASEPATH') OR exit('No direct script access allowed');

include ('Site.php');

class Site_indeed extends Site {

const SITE = 'http://www.indeed.com';
const SITE_CODE = 'ND';

	public function scrape($search)
	{
		$output = '';
		$url = $search['url'];
		while ($url)
		{
			$dom = self::get_page($url);
			$xpath = new DomXPath($dom);
			// get URL to next page if any
			$url = '';
			$elements = $xpath->query('//div[@class="pagination"]')->item(0)->lastChild;
			if ($elements->textContent == 'Next' . html_entity_decode('&nbsp;') . '»')
			{
				$url = self::SITE . $elements->getAttribute('href');
			}
			// extract rows from page
			$elements = $xpath->query('//div[@data-jk]');
			foreach ($elements as $element)
			{
				$output .= self::extract_row($element);
			}
		}
		return $output;
	}
	
	public function extract_row ($row)
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
		$field = '';
		$dom = new DomDocument;
		$dom->appendChild($dom->importNode($row, true));
		$xpath = new DomXPath($dom);
		$field = $xpath->query('//a[@data-tn-element]');
		$fields['title'] = (($field->length) ? self::clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@class="location"]');
		$fields['city'] = (($field->length) ? self::clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@class="company"]');
		$fields['employer'] = (($field->length) ? self::clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@class="summary"]');
		$fields['description'] = (($field->length) ? self::clean_field($field->item(0)->textContent) : '');
		$fields['url'] = self::SITE . '/rc/clk?jk=' . $row->getAttribute('data-jk');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
