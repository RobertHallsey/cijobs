<?php defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Site {

	static private $curl_options = array(
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36',
		CURLOPT_AUTOREFERER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_MAXREDIRS => 4
	);

	abstract protected function scrape ($search);

	abstract protected function extract_row ($row);

	public function get_page($url)
	{
		libxml_use_internal_errors(true);
		$page = curl_init($url);
		curl_setopt_array($page, self::$curl_options);
		$dom = new domDocument;
		@$dom->loadHTML(curl_exec($page), LIBXML_NOBLANKS | LIBXML_NOWARNING | LIBXML_NOERROR);
		curl_close($page);
		return $dom;
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
