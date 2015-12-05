<?php defined('BASEPATH') OR exit('No direct script access allowed');

include ('Site.php');

class Site_craigslist extends Site {

const SITE = 'http://sfbay.craigslist.com';
const SITE_CODE = 'CL';

	public function scrape($search)
	{
		$url = $search['url'];
		$jobs = array();
		$output = '';
		while ($url)
		{
			$dom = self::get_page($url);
			// find next page
			$elements = $dom->getElementsByTagName('span');
			foreach ($elements as $element)
			{
				if ($element->getAttribute('class') == 'paginator buttongroup  lastpage')
				{
					$url = '';
					break;
				}
			}
			if ($url != '')
			{
				$elements = $dom->getElementsByTagName('a');
				foreach ($elements as $element)
				{
					if ($element->getAttribute('class') == 'button next')
					{
						$url = self::SITE . $element->getAttribute('href');
						break;
					}
				}
			}
			// extract jobs from page
			$elements = $dom->getElementsByTagName('p');
			foreach ($elements as $element)
			{
				if ($element->getAttribute('class') == 'row')
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
			'url' => '',
			'date' => '',
			'code' => '',
			'map' => '',
			'img' => '',
			'pic' => ''
		);
		// get title and url
		$elements = $job->getElementsByTagName('a');
		foreach ($elements as $element)
		{
			if ($element->getAttribute('class') == 'hdrlnk')
			{
				$fields['title'] = self::clean_field($element->textContent);
				$fields['url'] = self::SITE . $element->getAttribute('href');
				break;
			}
		}
		// get city
		$elements = $job->getElementsByTagName('span');
		foreach ($elements as $element)
		{
			switch ($element->getAttribute('class'))
			{
				case 'l2':
					$text = $element->textContent;
					if (preg_match('/ *?\((.*?)\)/', $text, $matches)) $fields['city'] = $matches[1];
					if (strpos($text, ' map ')) $fields['map'] = 'map';
					if (strpos($text, ' img ')) $fields['img'] = 'img';
					if (strpos($text, ' pic ')) $fields['pic'] = 'pic';
					break;
			}
		}
		$elements = $job->getElementsByTagName('time');
		$fields['date'] = date('Ymd', strtotime($elements[0]->getAttribute('datetime')));
		$fields['code'] = self::SITE_CODE;
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}