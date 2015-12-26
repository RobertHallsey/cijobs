<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_indeed extends CI_Driver {

const SITE = 'http://www.indeed.com';
const SITE_CODE = 'ND';

	public function scrape($url)
	{
		$output = '';
		while ($url)
		{
			$dom = $this->get_page($url);
			$xpath = new DOMXPath($dom);
			// get URL to next page if any
			$elements = $xpath->query('//div[@class="pagination"]');
			$url = (($elements->length && $elements->item(0)->lastChild->textContent == 'Next' . html_entity_decode('&nbsp;') . '»')
					? $url = self::SITE . $elements->item(0)->lastChild->getAttribute('href') : '' );
			// extract rows from page
			$elements = $xpath->query('//div[@data-jk]');
			foreach ($elements as $element)
			{
				$output .= $this->extract_row($element);
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
		$dom = new DOMDocument;
		$dom->appendChild($dom->importNode($row, true));
		$xpath = new DOMXPath($dom);
		$field = $xpath->query('//a[@data-tn-element]');
		$fields['title'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@class="location"]');
		$fields['city'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@class="company"]');
		$fields['employer'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$field = $xpath->query('//span[@class="summary"]');
		$fields['description'] = (($field->length) ? $this->clean_field($field->item(0)->textContent) : '');
		$fields['url'] = self::SITE . '/rc/clk?jk=' . $row->getAttribute('data-jk');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
