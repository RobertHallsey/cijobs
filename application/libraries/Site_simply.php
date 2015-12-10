<?php defined('BASEPATH') OR exit('No direct script access allowed');

include ('Site.php');

class Site_simply extends Site {

const SITE = 'http://www.simplyhired.com';
const SITE_CODE = 'SH';

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
			$elements = $xpath->query('//a[@class="evtc next-pagination"]');
			if ($elements->length == 1)
			{
				$url = self::SITE . $elements->item(0)->getAttribute('href');
			}
			// extract jobs from page
			$elements = $xpath->query('//div[@class="card js-job"]');
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
			'agency' => '',
			'description' => '',
			'url' => '',
			'date' => '',
			'code' => ''
		);
		$dom = new DomDocument;
		$dom->appendChild($dom->importNode($job, true));
		$xpath = new DomXPath($dom);
		$fields['title'] = self::clean_field($xpath->query('//h2')->item(0)->textContent);
		$fields['city'] = self::clean_field($xpath->query('//span[@itemprop="jobLocation"]')->item(0)->textContent);
		$fields['employer'] = self::clean_field($xpath->query('//span[@itemprop="name"]')->item(0)->textContent);
		$fields['description'] = self::clean_field($xpath->query('//p[@itemprop="description"]')->item(0)->textContent);
		preg_match('/(?:Sponsored by | from )(.*?)$/', self::clean_field($xpath->query('//p[@class="serp-timesource"]')->item(0)->textContent), $matches);
		$fields['agency'] = $matches[1];
		$fields['url'] =  $xpath->query('//a[@data-event="job_description_link_click"]')->item(0)->getAttribute('href');
		$fields['date'] = date('Ymd');
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
