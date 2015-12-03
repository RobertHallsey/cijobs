<?php defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Site {

	abstract protected function scrape ($search);

	public function get_page($url)
	{
		libxml_use_internal_errors(true);
		$page = curl_init($url);
		curl_setopt($page, CURLOPT_RETURNTRANSFER, true);
		$dom = new domDocument;
		@$dom->loadHTML(curl_exec($page), LIBXML_NOWARNING | LIBXML_NOERROR);
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
