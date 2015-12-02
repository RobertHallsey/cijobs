<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_simply {

const SITE = 'http://www.simplyhired.com';
const SITE_CODE = 'sh';

	public function scrape($search)
	{
		$url = $search['url'];
		$jobs = array();
		$output = '';
		libxml_use_internal_errors(true);
		while ($url)
		{
			$page = curl_init($url);
			curl_setopt($page, CURLOPT_RETURNTRANSFER, true);
			$dom = new domDocument;
			@$dom->loadHTML(curl_exec($page), LIBXML_NOWARNING | LIBXML_NOERROR);
			curl_close($page);
			$url = '';
			$elements = $dom->getElementsByTagName('a');
			foreach ($elements as $element)
			{
				if ($element->getAttribute('class') == 'evtc next-pagination')
				{
					$url = $element->getAttribute('href');
				}
			}
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
	
	public function clean_field ($field)
	{
		$field = trim($field);
		$field = preg_replace('/\s\s+/', ' ', $field);
		$field = html_entity_decode($field, ENT_QUOTES);
		$field = strip_tags($field);
		$field = str_replace('"', '""', $field);
		return $field;
	}
}