<?php defined('BASEPATH') OR exit('No direct script access allowed');

include ('Site_general.php');

class Site_simply extends Site_general {

const SITE = 'http://www.simplyhired.com';
const SITE_CODE = 'sh';

	public function scrape($search)
	{
		$url = $search['url'];
		$output = '';
		while ($url)
		{
			$dom = self::get_page($url);
			// find next page
			$url = '';
			$elements = $dom->getElementsByTagName('a');
			foreach ($elements as $element)
			{
				if ($element->getAttribute('class') == 'evtc next-pagination')
				{
					$url = $element->getAttribute('href');
					break;
				}
			}
			// extract jobs from page
			$elements = $dom->getElementsByTagName('div');
			foreach ($elements as $element)
			{
				if ($element->getAttribute('class') == 'card js-job')
				{
					$output .= self::extract_job($element);
				}
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
			'url' => ''
		);
		// get title
		$elements = $job->getElementsByTagName('h2');
		$element = $elements->item(0);
		$fields['title'] = self::clean_field($element->textContent);
		
		// get city and employer
		$elements = $job->getElementsByTagName('span');
		foreach ($elements as $element)
		{
			if ($element->getAttribute('class') == 'serp-location' &&
				$element->getAttribute('itemprop') == 'jobLocation')
			{
				$fields['city'] = self::clean_field($element->textContent);
				continue;
			}
			if ($element->getAttribute('class') == 'serp-company' &&
				$element->getAttribute('itemprop') == 'name')
			{
				$fields['employer'] = self::clean_field($element->textContent);
				continue;
			}
		}
		$elements = $job->getElementsByTagName('p');
		foreach ($elements as $element)
		{
			if ($element->getAttribute('itemprop') == 'description')
			{
				$fields['description'] = self::clean_field($element->textContent);
				continue;
			}
			if ($element->getAttribute('class') == 'serp-timesource')
			{
				preg_match('/(?:Sponsored by | from )(.*?)$/', self::clean_field($element->textContent), $matches);
				$fields['agency'] = $matches[1];
				continue;
			}
		}
		// get url
		$elements = $job->getElementsByTagName('p');
		foreach ($elements as $element)
		{
			if ($element->getAttribute('data-event') == 'job_description_link_click')
			{
				$fields['url'] = $element->getAttribute('href');
			}
		}
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}