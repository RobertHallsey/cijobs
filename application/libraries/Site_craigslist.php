<?php defined('BASEPATH') OR exit('No direct script access allowed');

include ('Site.php');

class Site_craigslist extends Site {

const SITE = 'http://sfbay.craigslist.com';
const SITE_CODE = 'CL';

	public function scrape($search)
	{
		$output = '';
		$url = $search['url'];
		while ($url)
		{
			$dom = self::get_page($url);
			$xpath = new DomXPath($dom);
			// find next page
			$elements = $xpath->query('//span[@class="paginator buttongroup  lastpage"]');
			$url = ($elements->length == 2 ? '' : self::SITE . $xpath->query('//a[@class="button next"]')->item(0)->getAttribute('href'));
			// extract jobs from page
			$elements = $dom->getElementsByTagName('p');
			$elements = $xpath->query('//p[@class="row"]');
			foreach ($elements as $element)
			{
				$output .= self::extract_job($element);
			}
		}
		return $output;
	}
	
	public function extract_job ($job)
	{
		$fields = array(
			'title' => '',
			'city' => '',
			'url' => '',
			'date' => '',
			'code' => '',
			'map' => '',
			'img' => '',
			'pic' => ''
		);
		$dom = new DomDocument;
		$dom->appendChild($dom->importNode($job, true));
		$xpath = new DomXPath($dom);
		$fields['title'] = self::clean_field($xpath->query('//a[@class="hdrlnk"]')->item(0)->textContent);
		$field = $xpath->query('//a[@class="hdrlnk"]')->item(0)->getAttribute('href');
		if ($field[0] == '/') $field = self::SITE . $field;
		$fields['url'] = $field;
		$field = $xpath->query('//span[@class="l2"]')->item(0)->textContent;
		if (preg_match('/ *?\((.*?)\)/', $field, $matches)) $fields['city'] = $matches[1];
		if (strpos($field, ' map ')) $fields['map'] = 'map';
		if (strpos($field, ' img ')) $fields['img'] = 'img';
		if (strpos($field, ' pic ')) $fields['pic'] = 'pic';
		$xpath->query('//time')->item(0)->getAttribute('datetime');
		$fields['date'] = date('Ymd', strtotime($xpath->query('//time')->item(0)->getAttribute('datetime')));
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
