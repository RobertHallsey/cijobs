<?php defined('BASEPATH') OR exit('No direct script access allowed');

include ('Site.php');

class Site_indeed extends Site {

const SITE = 'http://www.indeed.com';
const SITE_CODE = 'ND';
const URL_SEGS = '/rc/clk?jk=';

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
			// extract jobs from page
			$elements = $xpath->query('//div[@data-jk]');
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
			'employer' => '',
			'description' => '',
			'url' => '',
			'date' => '',
			'code' => ''
		);
		$dom = new DomDocument;
		$dom->appendChild($dom->importNode($job, true));
		$xpath = new DomXPath($dom);
		$fields['title'] = self::clean_field($xpath->query('//a[@data-tn-element]')->item(0)->textContent);
		$fields['city'] = self::clean_field($xpath->query('//span[@class="location"]')->item(0)->textContent);
		$fields['employer'] = self::clean_field($xpath->query('//span[@class="company"]')->item(0)->textContent);
		$fields['description'] = self::clean_field($xpath->query('//span[@class="summary"]')->item(0)->textContent);
		$fields['url'] = self::SITE . self::URL_SEGS . $job->getAttribute('data-jk');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
