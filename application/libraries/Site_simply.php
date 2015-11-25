<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_simply {

const SITE = 'http://www.simplyhired.com';
const SITE_CODE = 'sh';

	public function scrape($search)
	{
		$url = $search['url'];
		$reg_next_url = '/<a href="([^"]*?)" rel="nofollow" class="evtc next-pagination" data-event="pagination_next"/';
		$reg_get_jobs = '/<div id="r:.*?<div class="divclear"><\/div>/s';
		$reg_get_fields = array(
			'title'       => '/<h2 class="serp-title" itemprop="title">(.*?)<\/h2>/s',
			'city'        => '/<span class="serp-location" itemprop="addressLocality">(.*?)<\/span>/s',
			'employer'    => '/<span class="serp-company" itemprop="name">.*?(\S.*?)\n.*?<\/span>/s',
			'agency'      => '/<p class="serp-timesource"><span class="serp-timestamp">.*?<\/span>(?: by | from )(.*?)<\/p>/s',
			'description' => '/<p class="serp-snippet" itemprop="description">(.*?)<\/p>/s',
			'url'         => '/data-event="job_description_link_click".*?href="(.*?)">Description<\/a>/s'
		);
		$output = '';
		while ($url)
		{
			$page = file_get_contents($url);
			$url = ((preg_match($reg_next_url, $page, $matches) == 1) ? self::SITE . $matches[1] : '');
			preg_match_all($reg_get_jobs, $page, $matches);
			foreach ($matches[0] as $job)
			{
				$line = '';
				foreach ($reg_get_fields as $reg_field)
				{
					$field = ((preg_match($reg_field, $job, $field) == 1) ? $field[1] : '');
					$field = str_replace('"', '\'', $field);
					$line .= $field . '","';
				}
				$line = '"' . $line . date('dmY') . '","' . self::SITE_CODE . '"' . "\r\n";
				$output .= strip_tags(html_entity_decode($line, ENT_QUOTES));
			}
		}
		return $output;
	}
	
}
