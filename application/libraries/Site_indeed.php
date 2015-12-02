<?php defined('BASEPATH') OR exit('No direct script access allowed');

include ('Site_general.php');

class Site_indeed extends Site_general {

const SITE = 'http://www.indeed.com';
const SITE_CODE = 'in';
const URL_SEGS = '/rc/clk?jk=';

	public function scrape($search)
	{
		$url = $search['url'];
		$jobs = array();
		$output = '';
		while ($url)
		{
			$dom = self::get_page($url);
			// find next page
			$url = '';
			$elements = $dom->getElementsByTagName('a');
			foreach ($elements as $element)
			{
				if ($element->textContent == 'Next' . html_entity_decode('&nbsp;&raquo;'))
				{
					$url = self::SITE . $element->getAttribute('href');
					break;
				}
			}
			// extract jobs from page
			$elements = $dom->getElementsByTagName('div');
			foreach ($elements as $element)
			{
				if ($element->getAttribute('data-tn-component') == 'sponsoredJob' ||
					$element->getAttribute('data-tn-component') == 'organicJob')
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
		$elements = $job->getElementsByTagName('a');
		foreach ($elements as $element)
		{
			if ($element->getAttribute('data-tn-element') == 'jobTitle')
			{
				$fields['title'] = self::clean_field($element->textContent);
				break;
			}
		}
		// get city, employer, and description
		$elements = $job->getElementsByTagName('span');
		foreach ($elements as $element)
		{
			switch ($element->getAttribute('class'))
			{
				case 'location':
					$fields['city'] = self::clean_field($element->textContent);
					break;
				case 'company':
					$fields['employer'] = self::clean_field($element->textContent);
					break;
				case 'summary':
					$fields['description'] = self::clean_field($element->textContent);
					break;
			}
		}
		$fields['agency'] = 'none';
		// get url
		$fields['url'] = self::SITE . self::URL_SEGS . $job->getAttribute('data-jk');
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}