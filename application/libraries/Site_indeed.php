<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_indeed {

const SITE = 'http://www.indeed.com';
const SITE_CODE = 'in';
const URL_SEGS = '/rc/clk?jk=';


	public function scrape($search)
	{
		$url = $search['url'];
		$jobs = array();
		$output = '';
		libxml_use_internal_errors(true);
		while ($url)
		{
			$dom = new domDocument;
			@$dom->loadHTMLFile($url, LIBXML_NOWARNING | LIBXML_NOERROR);
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
			$elements = $dom->getElementsByTagName('div');
			foreach ($elements as $element)
			{
				if ($element->getAttribute('data-tn-component') == 'sponsoredJob' ||
					$element->getAttribute('data-tn-component') == 'organicJob')
				{
					$output .= self::extract_job($element);
					$x = 3;
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