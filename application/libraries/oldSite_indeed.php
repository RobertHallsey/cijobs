<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_indeed {

const SITE = 'http://www.indeed.com';
const SITE_CODE = 'in';
const URL_SEGS = '/rc/clk?jk=';


	public function scrape($search)
	{
		$url = $search['url'];
		//$url = '1.html';
		$reg_next_url = '/<a href="([^"]*)" rel="nofollow"><span class=pn><span class=np>Next&nbsp;&raquo;/';
		$reg_get_job = '/data-jk=".*?<div class="result-link-bar-container">/s';
		$reg_get_fields = array();
		$reg_get_fields_paid = array(
			'title'       => '/<a target=.*?>(.*?)<\/a>/s',
			'city'        => '/<span class=location>(.*?)<\/span>/s',
			'employer'    => '/<span class="company">.*?(\S.*?)<\/span>/s',
			'agency'      => '/(?!a)a/',
			'description' => '/<span class=summary>(.*?)<\/span>/s',
			'url'         => '/data-jk="(.*?)"/s'
		);
		$reg_get_fields_free = array(
			'title'       => '/data-tn-element="jobTitle">(.*?)<\/a>/s',
			'city'        => '/<span itemprop="addressLocality">(.*?)<\/span>/s',
			'employer'    => '/<span itemprop="name">.*?(\S.*?)<\/span>/s',
			'agency'      => '/(?!a)a/',
			'description' => '/<span class=summary itemprop="description">.*?(\S.*?)<\/span>/s',
			'url'         => '/data-jk="(.*?)"/s'
		);
		$output = '';
		$pagenum = 0;
/*
		while ($url)
		{
			$page = file_get_contents($url);
			file_put_contents(++$pagenum . '.html', $page);
			$url = ((preg_match($reg_next_url, $page, $matches) == 1) ? self::SITE . $matches[1] : '');
			$x = 0;
		}
		$n = 0;
*/
//		while ($n <= $pagenum)
		{
			$page = file_get_contents('2.html');
			preg_match_all($reg_get_job, $page, $matches);
			foreach ($matches[0] as $job)
			{
				$reg_get_fields = ((strpos($job, '"sponsoredJob"') !== false) ? $reg_get_fields_paid : $reg_get_fields_free);
				$line = '';
				foreach ($reg_get_fields as $field_key => $field_regex)
				{
					$field = ((preg_match($field_regex, $job, $field_data) == 1) ? $field_data[1] : 'N/A');
					$field = (($field_key == 'url') ? self::SITE . $field : $field);
					$field = str_replace('"', "'", $field);
					$line .= $field . '","';
				}
				$line = '"' . $line . date('dmY') . '","' . self::SITE_CODE . '"' . "\r\n";
				$output .= strip_tags(html_entity_decode($line, ENT_QUOTES));
			}
		}
		return $output;
	}

}
